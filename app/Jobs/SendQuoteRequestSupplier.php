<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\SupplierService;
use App\QuoteRequests;
use App\QuoteRequestServices;
use App\QuoteRequestSupplier;
use App\Notification;
use App\EventTypes;
use App\Availability;
use Auth;
use Carbon\Carbon;

class SendQuoteRequestSupplier implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        if (isset($data) && isset($data['quote_id'])) {
            /* Quote Request data */
            $quote_requests_count = QuoteRequests::where('id', $data['quote_id'])->count();
            if ($quote_requests_count > 0) {
                $quote_requests = QuoteRequests::with('quoteRequestsStepValues')->where('id', $data['quote_id'])->first()->toArray();

                /* Quote Request service selected data */
                $quote_request_services_count = QuoteRequestServices::where('quote_request_id', $data['quote_id'])->count();
                if ($quote_request_services_count > 0) {
                    $quote_request_services = QuoteRequestServices::where('quote_request_id', $data['quote_id'])->get();
                    foreach ($quote_request_services as $quote_request_service) {
                        
                        /* Quote supplier provice request service data */
                        $supplier_service_count = SupplierService::where('service_id', $quote_request_service->service_id)->where('supplier_id', '!=', $quote_requests['user_id'])->count();
                        if ($supplier_service_count > 0) {
                            $supplier_service = SupplierService::with(['serviceContent', 'selectedEventtype', 'selectedAnswers'])
                                ->where('service_id', $quote_request_service->service_id)
                                ->where('supplier_id', '!=', $quote_requests['user_id'])
                                ->get()
                                ->toArray();

                            // dd($quote_requests);
                            /* All suppliers to added this quote request service */
                            foreach ($supplier_service as $value) {


                                // dd($value);
                                // $addressFrom = $quote_requests['location'];
                                // $addressTo   = 'Satellite, Ahmedabad, Gujarat';
                                // dd([$quote_requests['location'], $this->getDistance($addressFrom, $addressTo)]);

                                // FILTER 1 : Event type match....
                                // Event type selected find or not...
                                $finalStatusOfEventStatus = false;
                                if ($quote_requests['event_type_id'] && isset($value['selected_eventtype']) && count($value['selected_eventtype']) > 0) {
                                    $eventTypes = array_column($value['selected_eventtype'], 'event_type_id');
                                    in_array($quote_requests['event_type_id'], $eventTypes);
                                    if (!in_array($quote_requests['event_type_id'], $eventTypes)) {
                                        $finalStatusOfEventStatus = true;
                                    }
                                }

                                // FILTER 2 : Dynamic Step match....
                                // Event Dynamic step request filter check
                                if (isset($quote_requests['quote_requests_step_values']) && count($quote_requests['quote_requests_step_values']) > 0 && isset($value['selected_answers']) && count($value['selected_answers']) > 0) {
                                    $stepStatus = false;
                                    foreach ($value['selected_answers'] as $key => $ans) {
                                        $ans['answers'] = json_decode($ans['answers']);
                                        if (isset($ans['answers']) && count($ans['answers']) > 0) {
                                            $stepStatus = $this->checkDynamicStepMatch($quote_requests['quote_requests_step_values'], $ans);
                                        }
                                    }
                                    if ($stepStatus) {
                                        $finalStatusOfEventStatus = true;
                                    }
                                }

                                // FILTER 3 : Availability match....
                                /* Users availability data get */
                                $Availability = Availability::where('user_id', $value['supplier_id'])->first();
                                if ($Availability) {

                                    /* Users availability check if user available or not this event day */
                                    $event_date_week_number = Carbon::parse($quote_requests['event_date'])->format('w');

                                    $Availability = $Availability->toArray();
                                    $Availability['available'] = json_decode($Availability['available']);
                                    $Availability['working_days'] = json_decode($Availability['working_days']);

                                    $checkBlockAvaibility = $this->checkBlockAvaibility($event_date_week_number, $quote_requests['event_date'], $Availability);

                                    if ($checkBlockAvaibility) {
                                        /* 
                                        Users Are not avaliable on this event day.
                                        Than not any event request send to supploer. 
                                        And also not any notification send.
                                    */
                                        $finalStatusOfEventStatus = true;
                                    }
                                }
                                
                                if (!$finalStatusOfEventStatus) {
                                    /* If supplier available not set than by default supplier are available for all days. */
                                    $this->QuoteRequestSendToSupplier($data, $value);
                                    $this->notificationSendSupplier($data, $quote_requests, $value);
                                }
                            }
                        } else {
                            return 'Not found supplier service!';
                        }
                    }
                } else {
                    return 'Not found quote request services!';
                }
            } else {
                return 'Not found event!';
            }
        } else {
            return 'Quote Id not found!';
        }
    }

    function QuoteRequestSendToSupplier($data, $value)
    {
        QuoteRequestSupplier::where('quote_request_id', $data['quote_id'])->delete();
        QuoteRequestSupplier::create([
            'quote_request_id' => $data['quote_id'],
            'supplier_id' => $value['supplier_id'],
            'isArchive' => '0',
            'isQuote' => 'request',
        ]);
    }

    function notificationSendSupplier($data, $quote_requests, $value)
    {
        /* START : Event request send notification to suppliers */
        $event_type = EventTypes::where('id', $quote_requests['event_type_id'])->first();
        $event_message = 'Event Request';
        $event_title = $event_type->title . ' in ' . $quote_requests['location'] . ' event are available.';
        $user_id = (Auth::user()) ? Auth::user()->id : ($data['user_id']) ? $data['user_id'] : '';
        Notification::create([
            'from_user_id' => @$user_id,
            'to_user_id' => $value['supplier_id'],
            'notification_type' => 'Event',
            'title' => $event_title,
            'message' => $event_message,
            'url' => ''
        ]);
        /* END : Event request send notification to suppliers */
    }

    /* Check supllier available or not on this evnt date */
    function checkDynamicStepMatch($quote_requests_step_values, $ans)
    {
        $status = false;
        foreach ($quote_requests_step_values as $key => $value) {
            $value['answer'] = json_decode($value['answer']);
            if ((isset($value['answer']) && count($value['answer']) > 0) && (isset($ans['answers']) && count($ans['answers']) > 0)) {
                $result = array_intersect($ans['answers'], $value['answer']);
                if (isset($result) && count($result) > 0) {
                    $status = false;
                } else {
                    $status = true;
                }
            }
        }
        return $status;
    }

    /* Check supllier available or not on this evnt date */
    function checkBlockAvaibility($event_date_week_number, $date, $availability)
    {
        $status = false;
        if (isset($event_date_week_number) && isset($availability) && isset($availability['working_days']) && count($availability['working_days']) > 0 && in_array($event_date_week_number, $availability['working_days'])) {
            $status = true;
        }
        if (isset($availability) && isset($availability['available']) && count($availability['available']) > 0) {
            foreach ($availability['available'] as $key => $val) {
                if ($val->date == $date) {
                    if (isset($event_date_week_number) && isset($availability) && isset($availability['working_days']) && count($availability['working_days']) > 0 && in_array($event_date_week_number, $availability['working_days'])) {
                        if (isset($val->is_over_available) && $val->is_over_available == true) {
                            $status = false;
                        } else {
                            $status = true;
                        }
                    } else {
                        $status = true;
                    }
                }
            }
        }
        return $status;
    }

    function getDistance($addressFrom, $addressTo, $unit = '')
    {
        // Google API key
        $apiKey = env('GOOGLE_MAP_KEY', 'AIzaSyCf3_v7t2l6tkIMHdpiGovpEOtK0aKYjJw');

        // Change address format
        $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
        $formattedAddrTo     = str_replace(' ', '+', $addressTo);

        // Geocoding API request with start address
        $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddrFrom . '&sensor=false&key=' . $apiKey);
        $outputFrom = json_decode($geocodeFrom);
        if (!empty($outputFrom->error_message)) {
            return $outputFrom->error_message;
        }

        // Geocoding API request with end address
        $geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddrTo . '&sensor=false&key=' . $apiKey);
        $outputTo = json_decode($geocodeTo);
        if (!empty($outputTo->error_message)) {
            return $outputTo->error_message;
        }

        // Get latitude and longitude from the geodata
        $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
        $longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
        $latitudeTo        = $outputTo->results[0]->geometry->location->lat;
        $longitudeTo    = $outputTo->results[0]->geometry->location->lng;

        // Calculate distance between latitude and longitude
        $theta    = $longitudeFrom - $longitudeTo;
        $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
        $dist    = acos($dist);
        $dist    = rad2deg($dist);
        $miles    = $dist * 60 * 1.1515;

        // Convert unit and return distance
        $unit = strtoupper($unit);
        if ($unit == "K") {
            return ['dist' => round($miles * 1.609344, 2), 'dist_type' => 'km'];
        } elseif ($unit == "M") {
            return ['dist' => round($miles * 1609.344, 2), 'dist_type' => 'meters'];
        } else {
            return ['dist' => round($miles, 2), 'dist_type' => 'miles'];
        }
    }
}
