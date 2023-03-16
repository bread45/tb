<?php

namespace App\Http\Controllers;

use App\TrainerEvent;
use Modules\Users\Entities\FrontUsers;
use App\Countries;
use App\States;
use Illuminate\Http\Request;
use Validator;
use Mail,
    Redirect;
use Stripe;
use Route;
use URL;
use Cookie;
use Session,
    DB,Auth;
use Image;
use Illuminate\Http\Response;
use DataTables;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\StripeAccounts;
use DateTime;
include_once(app_path().'/../mail/PHPMailer/Exception.php');
include_once(app_path().'/../mail/PHPMailer/PHPMailer.php');
include_once(app_path().'/../mail/PHPMailer/SMTP.php');
include_once(app_path().'/../mail/vendor/autoload.php');

class TrainerEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct() {
        // $this->middleware('front.user');
        $this->middleware(function ($request, $next) {
            if(Auth::guard('front_auth')->check()){
            $this->id = Auth::guard('front_auth')->user()->id;
            $providerStatus = DB::table('front_users')->where(["id" => $this->id])->get();
            $trailingProviderOrders = DB::table('provider_orders')->where(["trainer_id" => $this->id, "status" => 0, "subscription_status" => "trialing"])->get()->count();
            $providerOrdersCount = DB::table('provider_orders')->where(["trainer_id" => $this->id, "status" => 0, "subscription_status" => "active"])->get()->count();
            // dd($trailingProviderOrders);
            \View::share(['providerOrdersCount' => $providerOrdersCount, 'providerStatus' => $providerStatus, 'trailingProviderOrders' => $trailingProviderOrders]);
            
            }
            return $next($request);
        });
    }


    public function index(Request $request)
    {
        $trainerId = Auth::guard('front_auth')->user()->id;
        $events = DB::select('select * from trainer_events where trainer_id="'.$trainerId.'" order by id desc');
        $StripeAccountsdata = StripeAccounts::where('user_id', $trainerId)->orderBy('id', 'desc')->first();
        return view('front.trainer.events.events',["events" => $events, "StripeAccountsdata" => $StripeAccountsdata]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $trainerId = Auth::guard('front_auth')->user()->id;
        $StripeAccountsdata = StripeAccounts::where('user_id', $trainerId)->orderBy('id', 'desc')->first();
        return view('front.trainer.events.add-events' ,["StripeAccountsdata" => $StripeAccountsdata]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        $trainerId = Auth::guard('front_auth')->user()->id;
         $rules['title'] = 'required';
         $rules['venue'] = $request->format === 'In Person' ? 'required': '';
        //  $rules['url'] = $request->format === 'Virtual' ? 'required': '';
         $rules['start_date'] = 'required';
         $rules['start_time'] = 'required';
         $rules['end_time'] = 'required';
        //  $rules['recurring_day'] = $request->repeat_type == 'Weekly' && $request->is_recurring == 1 ? 'required': '';
         $rules['cost'] = $request->type === 'Paid' ? 'required': '';
        $validator = Validator::make($requestData, $rules);
         if ($validator->fails()) {
             return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
         } else {

        $accept_promo = ($request->input('accept_promo')!= '')?true:false;
        $event_timestamp = date('Y-m-d H:i:s' ,strtotime($request->input('start_date').' '.$request->input('start_time')));
        $insertEventData = array(
            'trainer_id' => $trainerId, 
            'title' => $request->input('title'),
            'category' => $request->input('category'),
            'type' => $request->input('type'),
            'format' => $request->input('format'),
            'venue' => ($request->input('format') == 'In Person')?$request->input('venue'):NULL,
            'url' => ($request->input('format') == 'Virtual')?$request->input('url'):NULL,
            'cost' => ($request->input('type') == 'Paid')?$request->input('cost'):NULL,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date')?$request->input('end_date'):NULL,
            'start_time' =>$request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'accept_promo' => $accept_promo,
            'members_allowed' => $request->input('members_allowed'),
            'description' => $request->input('description'),
            'is_recurring' => $request->input('is_recurring')?$request->input('is_recurring'):0,
            'recurring_type' => $request->input('is_recurring')?$request->input('repeat_type'):NULL,
            // 'recurring_day' => $request->input('is_recurring')?$request->input('recurring_day'):NULL,
            'recurring_end' => $request->input('is_recurring')?$request->input('recurring_end'):NULL,
            'recurring_end_date' => $request->input('recurring_end_date'),
            'event_start_datetime' => $event_timestamp,
            'status' => 1,
        );
        /////////////////// Event Update Operation //////////////////////////////////
        if(isset($requestData["event_id"]) && !empty($requestData["event_id"])){

            $insertEvent = TrainerEvent::where('id', '=', $requestData["event_id"])->update($insertEventData);
            $msg = "Event Updated successfully.";
            
        } 
        /////////////////// End of Event Update Operation //////////////////////////////////
        else {
            /////////////////// Single and Recurring Event Insert Operation //////////////////////////////////
            
            if(isset($requestData['is_recurring']) && $requestData['is_recurring'] == 1){


               /////////////////// Daily Recurring Event Insert Operation //////////////////////////////////
                if($requestData['repeat_type'] == 'Daily'){

                    $startTimeStamp = strtotime(date('Y-m-d', strtotime($requestData["start_date"])));
                    $endTimeStamp = strtotime(date('Y-m-d', strtotime($requestData["recurring_end_date"])));
                    $timeDiff = abs($endTimeStamp - $startTimeStamp);
                    $recurrenceDate = $requestData["start_date"];
                    $numberDays = $timeDiff/86400;  // 86400 seconds in one day
                    $numberDays = intval($numberDays);
                    // Insert first event
                    $insertEvent = TrainerEvent::insertGetId($insertEventData);
                    // Insert Recurring Event Entries
                    if($numberDays > 0){
                    $recurrence_id = $insertEvent;
                    for($i = 1; $i <= $numberDays; $i++){
                            $recurrenceDate = strtotime("+1 day", strtotime($recurrenceDate));
                            $recurrenceDate = date('m/d/Y', $recurrenceDate);
                            $event_timestamp = date('Y-m-d H:i:s' ,strtotime($recurrenceDate.' '.$request->input('start_time')));
                        if(strtotime($requestData['recurring_end_date']) >=  strtotime($recurrenceDate)){
                            $insertRecurringData = array(
                                'trainer_id' => $trainerId, 
                                'title' => $request->input('title'),
                                'category' => $request->input('category'),
                                'type' => $request->input('type'),
                                'format' => $request->input('format'),
                                'venue' => ($request->input('format') == 'In Person')?$request->input('venue'):NULL,
                                'url' => ($request->input('format') == 'Virtual')?$request->input('url'):NULL,
                                'cost' => ($request->input('type') == 'Paid')?$request->input('cost'):NULL,
                                'start_date' => $recurrenceDate,
                                'end_date' => $request->input('end_date')?$request->input('end_date'):NULL,
                                'start_time' =>$request->input('start_time'),
                                'end_time' => $request->input('end_time'),
                                'recurrence_id' => $recurrence_id,
                                'accept_promo' => $accept_promo,
                                'members_allowed' => $request->input('members_allowed'),
                                'description' => $request->input('description'),
                                'is_recurring' => $request->input('is_recurring')?$request->input('is_recurring'):0,
                                'recurring_type' => $request->input('is_recurring')?$request->input('repeat_type'):NULL,
                                // 'recurring_day' => $request->input('is_recurring')?$request->input('recurring_day'):NULL,
                                'recurring_end' => $request->input('is_recurring')?$request->input('recurring_end'):NULL,
                                'recurring_end_date' => $request->input('recurring_end_date'),
                                'event_start_datetime' => $event_timestamp,
                                'status' => 1,
                            );
                            $insertEvent = TrainerEvent::insert($insertRecurringData);
                        }
                        
                    }
                }
                }
                /////////////////// End of Daily Recurring Event Insert Operation //////////////////////////////////

                ///////////////////  Weekly Recurring Event Insert Operation //////////////////////////////////
                else if($requestData['repeat_type'] == 'Weekly'){
                    $startTimeStamp = strtotime(date('Y-m-d', strtotime($requestData["start_date"])));
                    $endTimeStamp = strtotime(date('Y-m-d', strtotime($requestData["recurring_end_date"])));
                    $timeDiff = abs($endTimeStamp - $startTimeStamp);
                    $recurrenceDate = $requestData["start_date"];
                    $numberDays = $timeDiff/86400;
                    $numberWeeks = intval($numberDays/7);
                    if($numberWeeks == 0){
                        $numberWeeks = 1;
                    }
                    // Insert first event
                    $insertEvent = TrainerEvent::insertGetId($insertEventData);
                    // Insert Recurring Event Entries
                    // dd($numberDays);
                    if(isset($requestData['recurring_day'])){
                    if($numberDays >= 1){
                    $recurrence_id = $insertEvent;
                    // get week first date
                    $recurrenceDate = new DateTime(date('Y-m-d', strtotime($requestData["start_date"])));
                    $weekNo = $recurrenceDate->format("W");
                    $newDate = new DateTime();
                    $newDate->setISODate($recurrenceDate->format("Y"), $weekNo);
                    $recurrenceDate = $newDate->format('m/d/Y');
                    foreach($requestData['recurring_day'] as $key => $recurring_day){
                         $nextDay[] = $recurring_day;
                    }
             

                    for($i = 0; $i < $numberWeeks+1; $i++){

                        foreach($nextDay as $key => $recurring_day){
                            if($key>0){
                                $checkKey = $key-1;
                            }
                            else {
                                $checkKey = $key;
                            }
                            if ((strtotime($recurrenceDate) <= strtotime($requestData["start_date"])) && (($i == 0) || (strtotime($recurrenceDate) != strtotime($weekDay[$checkKey]))) ) { 
                                    $nextEventDay = date('m/d/Y', strtotime($recurring_day, strtotime($recurrenceDate)));
                                    $weekDay[] = $nextEventDay;
                                    $recurrenceDate = $nextEventDay;
                             }
                            else {
                                    $nextEventDay = date('m/d/Y', strtotime('next '.$recurring_day.'', strtotime($recurrenceDate)));
                                    $weekDay[] = $nextEventDay;
                                    $recurrenceDate = $nextEventDay;
                            }
                    }
                }

                if(!empty(isset($weekDay))){
                        foreach($weekDay as $eventDay){
                        if(strtotime($eventDay) <= strtotime($requestData['recurring_end_date']) && strtotime($eventDay) > strtotime($requestData['start_date'])){
                                $recurrenceDate = $eventDay;
                                $event_timestamp = date('Y-m-d H:i:s' ,strtotime($recurrenceDate.' '.$request->input('start_time')));
                                if(strtotime($requestData['recurring_end_date']) >=  strtotime($recurrenceDate)){
                                    
                                    $insertRecurringData = array(
                                        'trainer_id' => $trainerId, 
                                        'title' => $request->input('title'),
                                        'category' => $request->input('category'),
                                        'type' => $request->input('type'),
                                        'format' => $request->input('format'),
                                        'venue' => ($request->input('format') == 'In Person')?$request->input('venue'):NULL,
                                        'url' => ($request->input('format') == 'Virtual')?$request->input('url'):NULL,
                                        'cost' => ($request->input('type') == 'Paid')?$request->input('cost'):NULL,
                                        'start_date' => $recurrenceDate,
                                        'end_date' => $request->input('end_date')?$request->input('end_date'):NULL,
                                        'start_time' =>$request->input('start_time'),
                                        'end_time' => $request->input('end_time'),
                                        'recurrence_id' => $recurrence_id,
                                        'accept_promo' => $accept_promo,
                                        'members_allowed' => $request->input('members_allowed'),
                                        'description' => $request->input('description'),
                                        'is_recurring' => $request->input('is_recurring')?$request->input('is_recurring'):0,
                                        'recurring_type' => $request->input('is_recurring')?$request->input('repeat_type'):NULL,
                                        // 'recurring_day' => $request->input('is_recurring')?$request->input('recurring_day'):NULL,
                                        'recurring_end' => $request->input('is_recurring')?$request->input('recurring_end'):NULL,
                                        'recurring_end_date' => $request->input('recurring_end_date'),
                                        'event_start_datetime' => $event_timestamp,
                                        'status' => 1,
                                    );
                                    $insertEvent = TrainerEvent::insert($insertRecurringData);
                                }
                            }
                        }
                    }
                }
            }
                }
                /////////////////// End of Weekly Recurring Event Insert Operation //////////////////////////////////

                /////////////////// Monthly Recurring Event Insert Operation ///////////////////////////////////////
                else if($requestData['repeat_type'] == 'Monthly'){

                    $startTimeStamp = strtotime(date('Y-m-d', strtotime($requestData["start_date"])));
                    $endTimeStamp = strtotime(date('Y-m-d', strtotime($requestData["recurring_end_date"])));
                    $timeDiff = abs($endTimeStamp - $startTimeStamp);
                    $recurrenceDate = $requestData["start_date"];
                    $numberDays = $timeDiff/86400;  // 86400 seconds in one day
                    $numberMonths = intval($numberDays/30);

                    // Insert first event
                    $insertEvent = TrainerEvent::insertGetId($insertEventData);
                    // Insert Recurring Event Entries
                    if($numberDays > 30){
                    $recurrence_id = $insertEvent;
                    
                    for($i = 1; $i <= $numberMonths+1; $i++){

                        $currentday = date('d',strtotime($recurrenceDate));
                        $lastday = date('t',strtotime($recurrenceDate));
                        if($currentday == $lastday){
                            $recurrenceDate =  Carbon::parse($recurrenceDate)->addMonthWithNoOverflow()->lastOfMonth()->format('m/d/Y');
                        }
                        else {
                            $recurrenceDate =  Carbon::parse($recurrenceDate)->addMonthWithNoOverflow()->format('m/d/Y');
                        }
                        $event_timestamp = date('Y-m-d H:i:s' ,strtotime($recurrenceDate.' '.$request->input('start_time')));
                        if(strtotime($requestData['recurring_end_date']) >=  strtotime($recurrenceDate)){
                            $insertRecurringData = array(
                                'trainer_id' => $trainerId, 
                                'title' => $request->input('title'),
                                'category' => $request->input('category'),
                                'type' => $request->input('type'),
                                'format' => $request->input('format'),
                                'venue' => ($request->input('format') == 'In Person')?$request->input('venue'):NULL,
                                'url' => ($request->input('format') == 'Virtual')?$request->input('url'):NULL,
                                'cost' => ($request->input('type') == 'Paid')?$request->input('cost'):NULL,
                                'start_date' => $recurrenceDate,
                                'end_date' => $request->input('end_date')?$request->input('end_date'):NULL,
                                'start_time' =>$request->input('start_time'),
                                'end_time' => $request->input('end_time'),
                                'recurrence_id' => $recurrence_id,
                                'accept_promo' => $accept_promo,
                                'members_allowed' => $request->input('members_allowed'),
                                'description' => $request->input('description'),
                                'is_recurring' => $request->input('is_recurring')?$request->input('is_recurring'):0,
                                'recurring_type' => $request->input('is_recurring')?$request->input('repeat_type'):NULL,
                                // 'recurring_day' => $request->input('is_recurring')?$request->input('recurring_day'):NULL,
                                'recurring_end' => $request->input('is_recurring')?$request->input('recurring_end'):NULL,
                                'recurring_end_date' => $request->input('recurring_end_date'),
                                'event_start_datetime' => $event_timestamp,
                                'status' => 1,
                            );
                            $insertEvent = TrainerEvent::insert($insertRecurringData);
                        }
                        
                    }
                }
                }
                 /////////////////// End of Monthly Recurring Event Insert Operation ///////////////////////////////////////
            }
            /////////////////// Single Event Insert Operation //////////////////////////////////
            else {
                $insertEvent = TrainerEvent::insert($insertEventData);
            }

            /////////////////// End of Single Event Insert Operation //////////////////////////////////

            $msg = "Event created successfully.";
        }
        
        // if(!$insertEvent){
        //     $msg = "Something went wrong.";
        // }
        Session::flash('message', $msg); 
        return redirect('trainer/events');
    }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\TrainerEvent  $trainerEvent
     * @return \Illuminate\Http\Response
     */
    public function show(TrainerEvent $trainerEvent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TrainerEvent  $trainerEvent
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $events = DB::select('select * from trainer_events where id="'.base64_decode($id).'"');
        $trainerId = Auth::guard('front_auth')->user()->id;
        $StripeAccountsdata = StripeAccounts::where('user_id', $trainerId)->orderBy('id', 'desc')->first();
        // dd($StripeAccountsdata);
        return view('front.trainer.events.add-events',["events" => $events, "StripeAccountsdata" => $StripeAccountsdata]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TrainerEvent  $trainerEvent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrainerEvent $trainerEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TrainerEvent  $trainerEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $removeEvent = TrainerEvent::find(base64_decode($id));
        // $removeRecurrence = TrainerEvent::where('recurrence_id', $id);
        if (isset($removeEvent) && isset($removeEvent->id)) {
            $removeEvent->delete();
            $msg = "Event has been deleted.";
            if($removeEvent){
                Session::flash('message', $msg);
                return response()->json(['status'=> 'Success', 'msg' => $msg]); 
            }
            else{
                $msg = "Something went wrong.";
                return response()->json(['status'=> 'Failed', 'msg' => $msg]); 
            } 
            // return redirect('trainer/events');
        }
        else {
            $msg = "Something went wrong.";
            return response()->json(['status'=> 'Failed', 'msg' => $msg]);
        }
    }

    public function destroy_recurrence($id)
    {
        $removeEvent = TrainerEvent::where('recurrence_id', base64_decode($id))
                        ->orWhere('id', '=', base64_decode($id))->delete();
        if (isset($removeEvent)) {
            // $removeEvent->delete();
            $msg = "Event Recurrences deleted.";
            if($removeEvent){
                Session::flash('message', $msg);
                return response()->json(['status'=> 'Success', 'msg' => $msg]); 
            }
            else{
                $msg = "Something went wrong.";
                return response()->json(['status'=> 'Failed', 'msg' => $msg]); 
            } 
            // return redirect('trainer/events');
        }
        else {
            $msg = "Something went wrong.";
            return response()->json(['status'=> 'Failed', 'msg' => $msg]);
        }
    }

    public function eventCalendar(Request $request) {
        date_default_timezone_set('America/New_York');
        $currentTimeStamp = Carbon::now()->toDateTimeString();

        if (isset($request->event_filter) && !empty($request->event_filter)) {
            // List event with filter condition
            $eventList = FrontUsers::join('trainer_events', 'front_users.id', '=', 'trainer_events.trainer_id')
            // ->join('provider_orders', 'trainer_events.trainer_id', '=', 'provider_orders.trainer_id')
            // ->select('trainer_events.*', 'front_users.business_name', 'front_users.photo','provider_orders.subscription_status')
            ->where('trainer_events.status', '=', 1)
            ->where('front_users.status', '=', 'active')
            ->where('trainer_events.event_start_datetime','>', $currentTimeStamp)
            ->orderBy('trainer_events.event_start_datetime', 'ASC');

            $eventList->leftJoin('provider_orders', function($query){
                $query->on('trainer_events.trainer_id', '=', 'provider_orders.trainer_id')
                        ->whereRaw('provider_orders.id IN (select MAX(a2.ID) from provider_orders as a2 join trainer_events as u2 on u2.trainer_id = a2.trainer_id group by u2.trainer_id)');
            });

            $eventList->where(function ($query) {
            $query->where('front_users.is_subscription','=', 0)
                  ->orWhere('front_users.is_subscription','=', 1)
                  ->where('provider_orders.subscription_status','!=', 'cancelled');
            });     

            if (isset($request->keyword) && !empty($request->keyword)) {
                $keyword = $request->keyword;
                $eventList->where(function ($query) use ($keyword) {
                        $query->where('trainer_events.title', 'LIKE', '%' . $keyword . '%')
                              ->orWhere('trainer_events.description', 'LIKE', '%' . $keyword . '%');
                });
            }
            if (isset($request->start_date) && !empty($request->start_date)) {
                $eventList->where('trainer_events.start_date', '=', $request->start_date);
            }
            if (isset($request->category) && !empty($request->category)) {
                $eventList->where('trainer_events.category', '=', $request->category);
            }
             if (isset($request->location) && !empty($request->location)) {
                $location = $request->location;
                $eventList->where(function ($query) use ($location) {
                        $query->where('trainer_events.venue', 'LIKE', '%' . $location . '%');
                });
            }
            
        }
        else {
            // List event without filter
            $eventList = FrontUsers::join('trainer_events', 'front_users.id', '=', 'trainer_events.trainer_id')
                            // ->join('provider_orders', 'trainer_events.trainer_id', '=', 'provider_orders.trainer_id')
                            // ->select('trainer_events.*', 'front_users.business_name', 'front_users.photo','provider_orders.subscription_status')
                            ->where('trainer_events.status', '=', 1)
                            ->where('front_users.status', '=', 'active')
                            ->where('trainer_events.event_start_datetime','>', $currentTimeStamp)
                            ->orderBy('trainer_events.event_start_datetime', 'ASC');
                
            $eventList->leftJoin('provider_orders', function($query){
                    $query->on('trainer_events.trainer_id', '=', 'provider_orders.trainer_id')
                            ->whereRaw('provider_orders.id IN (select MAX(a2.ID) from provider_orders as a2 join trainer_events as u2 on u2.trainer_id = a2.trainer_id group by u2.trainer_id)');
            });

            $eventList->where(function ($query) {
                $query->where('front_users.is_subscription','=', 0)
                      ->orWhere('front_users.is_subscription','=', 1)
                      ->where('provider_orders.subscription_status','!=', 'cancelled');
            });                             
            
            // $eventList->where(function ($query) {
            //     $query->where('provider_orders.subscription_status','=', 'active')
            //           ->orWhere('provider_orders.subscription_status','=', 'trialing');
            // });

            // $eventList->where(function ($query) {
            //     $query->where('trainer_events.type','=', 'Free')
            //           ->orWhere('trainer_events.type','=', 'Paid')
            //           ->where('stripe_accounts.stripe_user_id','!=', '');
            // });
        }
        $eventList->select('trainer_events.*', 'front_users.business_name', 'front_users.photo','provider_orders.subscription_status');
        $eventList = $eventList->get();
        // dd($eventList);
        if($request->category != null){
                $category = $request->category;
            }else{
                $category = null;
            }
			$metaDescription = "Connect with the community by attending local group runs, online webinars, or in-person workshops hosted by practitioners and coaches";
        return view('front.event-calendar', ['Listevents' => $eventList,'metaDescription' => $metaDescription, 'category' => $category]);
    }

   public function showEventDetailss($eventId){
        $user = \Auth::guard('front_auth')->user();
        
        if(!$user)
        {
            $baseurl = URL::to('/event-details/'.$eventId);
            
            session()->put('event-detail-url',$baseurl);
            return redirect('login');
        }
    }

public function showEventDetails($id){
    $user = \Auth::guard('front_auth')->user();
    if($user)
        {
            session()->put('event-detail-url',false);
        }

    $eventID = base64_decode($id);
    $customerId = Auth::guard('front_auth')->user();
    $eventDetails = TrainerEvent::join('front_users', 'trainer_events.trainer_id', '=', 'front_users.id')
                ->leftJoin('stripe_accounts', 'trainer_events.trainer_id', '=', 'stripe_accounts.user_id')
                ->where('trainer_events.id', '=', $eventID)
                ->get(['trainer_events.*', 'front_users.business_name', 'front_users.spot_description', 'front_users.photo', 'stripe_accounts.stripe_user_id']);
    // dd($eventDetails);
    if($customerId != null){
        $selfRegistered = DB::table('event_registration')->where('event_id', '=', $eventID )
                ->where('attender_id', '=', $customerId->id)->get()->count();
    }else{ 
        $selfRegistered = 0;
    }
    $attendeesCount = DB::table('event_registration')->where('event_id', '=', $eventID )
    ->join('front_users', 'event_registration.attender_id', '=', 'front_users.id')
    ->get()->count();
                       if($customerId !=''){
                            if($customerId->user_role =='customer'){
                                $customerId = Auth::guard('front_auth')->user()->id;
                            } else {
                                $customerId = Auth::guard('front_auth')->user()->id;
                            }
                       } else {
                            $customerId = '0';
                       }
    $atendesCurs =  DB::table('event_registration')
                    ->join('front_users', 'event_registration.attender_id', '=', 'front_users.id' )
                    ->where('event_registration.event_id', '=', $eventID)->get();

    return view('front.event-details', ['event' => $eventDetails, "customerId" => $customerId, 'attendeesCount' => $attendeesCount, 'selfRegistered' => $selfRegistered, 'atendesCurs' => $atendesCurs]);
}


 

public function showEventRegisterForm($id){
    $eventID = base64_decode($id);
    $athleteID = Auth::guard('front_auth')->user()->id;
    $eventDetails = TrainerEvent::where('trainer_events.id', '=', $eventID)->get();
    $athleteDetails = DB::table('front_users')->where(["id" => $athleteID])->first();

    $customerId = Auth::guard('front_auth')->user();
    if($customerId != null){
        $selfRegistered = DB::table('event_registration')->where('event_id', '=', $eventID )
                ->where('attender_id', '=', $customerId->id)->get()->count();
    }else{ 
        $selfRegistered = 0;
    }

    $attendeesCount = DB::table('event_registration')->where('event_id', '=', $eventID )->get()->count();
                       if($customerId !=''){
                            if($customerId->user_role =='customer'){
                                $customerId = Auth::guard('front_auth')->user()->id;
                            } else {
                                $customerId = Auth::guard('front_auth')->user()->id;
                            }
                       } else {
                            $customerId = '0';
                       }

    return view('front.event-register', ['event' => $eventDetails, 'attendeesCount' => $attendeesCount, 'selfRegistered' => $selfRegistered,'athleteDetails' => $athleteDetails]);
}

public function attendees_list() {
    $trainerId = Auth::guard('front_auth')->user()->id;
 //    $attendeesDetails = DB::table('event_registration')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->get();
    
    $attendeesDetails = DB::table('event_registration')
             ->Join('trainer_events', 'event_registration.event_id', '=', 'trainer_events.id')
             ->join('front_users', 'event_registration.attender_id', '=', 'front_users.id')
             ->where(["event_registration.trainer_id" => Auth::guard('front_auth')->user()->id])
             ->get();
    $attendeesFormDetails = DB::table('event_registration')
             ->leftJoin('trainer_events', 'event_registration.event_id', '=', 'trainer_events.id')
             ->groupBy('trainer_events.id')
             ->where(["event_registration.trainer_id" => Auth::guard('front_auth')->user()->id])
             ->get();
    // dd($attendeesDetails);

     $paid_date = '';
     $event_name = '';
     $event_category = '';
     $event_date = '';
     $status = '';
     return view('front.trainer.events.attendees-list', ["attendeesDetails" => $attendeesDetails,"event_name" => $event_name,"event_category" => $event_category,"event_date" => $event_date,"status" => $status,
     "attendeesFormDetails" => $attendeesFormDetails,"paid_date" => $paid_date]);
     
 }

 public function find_attendees(Request $request) {
      $trainerId = Auth::guard('front_auth')->user()->id;
     $attendeesDetails = DB::table('event_registration')
             ->leftJoin('trainer_events', 'event_registration.event_id', '=', 'trainer_events.id')
             ->join('front_users', 'event_registration.attender_id', '=', 'front_users.id')
             ->where(["event_registration.trainer_id" => Auth::guard('front_auth')->user()->id])
             ->get();
     $attendeesFormDetails = DB::table('event_registration')
             ->leftJoin('trainer_events', 'event_registration.event_id', '=', 'trainer_events.id')
             ->groupBy('trainer_events.id')
             ->where(["event_registration.trainer_id" => Auth::guard('front_auth')->user()->id])
             ->get();  
        //    dd($attendeesDetails);  
     $event_name = '';
     $event_category = '';
     $event_date = '';
     $status = '';
     $where = '';
     $paid_date = '';
     if($request->search == 1){
         $event_name = $request->event_name;
         $event_category = $request->event_category;
         $event_date = $request->event_date;
         $status = $request->status;
         
         if(!empty($event_name)){
             $where .= " and te.id = '".$event_name."'";
         }

         if(!empty($event_category)){
             $where .= " and te.category = '".$event_category."'";
         }

         if(!empty($event_date)){
             $where .= " and te.start_date = '".$event_date."'";
         }

         if(!empty($status)){
             $where .= " and er.rsvp = '".$status."'";
         }

         
         $attendeesDetails = DB::select("SELECT * FROM event_registration AS er 
                 JOIN trainer_events AS te ON er.event_id = te.id 
                 JOIN front_users AS fu ON er.attender_id = fu.id 
                     WHERE er.trainer_id = '".$trainerId."'". $where);
     } else {
          $attendeesDetails = DB::select("SELECT * FROM event_registration AS er 
                 JOIN trainer_events AS te ON er.event_id = te.id 
                 JOIN front_users AS fu ON er.attender_id = fu.id 
                     WHERE er.trainer_id = '".$trainerId."'");
     }
    //  dd($attendeesDetails); 
     //echo $paid_date;exit();
    return view('front.trainer.events.attendees-list', ["attendeesDetails" => $attendeesDetails,"event_name" => $event_name,
    "event_category" => $event_category,"event_date" => $event_date,"status" => $status,"attendeesFormDetails" => $attendeesFormDetails, "paid_date" => $paid_date]);
 }

 public function EventLike($eventId){
     $customerId = Auth::guard('front_auth')->user()->id;
     $eventLikeCount = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $eventId])->count();
     $eventLike = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $eventId])->first();
     if($eventLikeCount == 0){
         $eventLikeInsert = DB::table('event_count')->insert([
                     'event_id' => $eventId,
                     'user_id' => Auth::guard('front_auth')->user()->id,
                     'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                     'likes' => 1,
                     'created_at' => date('Y-m-d H:i:s')
                 ]);
         $likes = 1;
     } else {
         if($eventLike->likes == 0 && $eventLike->dislike == 0){
             $eventLikeUpdate = DB::update('update event_count set likes="1", name="'.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.'" where id="'.$eventLike->id.'"');
             $likes = 1;
         } else if($eventLike->likes == 0 && $eventLike->dislike == 1 ){
             $eventLikeUpdate = DB::update('update event_count set likes=1, dislike=0, name="'.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.'" where id="'.$eventLike->id.'"');
             $likes = 0;
         } else if($eventLike->likes == 1 && $eventLike->dislike == 0){
             $eventLikeUpdate = DB::update('update event_count set likes=0, name="'.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.'" where id="'.$eventLike->id.'"');
             $likes = 0;
         }
     }
     $event_like_count = DB::table('event_count')->where(["event_id" => $eventId, "likes" => 1])->count();
     $event_dislike_count = DB::table('event_count')->where(["event_id" => $eventId, "dislike" => 1])->count();
     $event_like_name = DB::select('select name, user_id, created_at from event_count where event_id="'.$eventId.'" and likes = 1 order by id desc');
     $event_dislike_name = DB::select('select name, user_id, created_at from event_count where event_id="'.$eventId.'" and dislike = 1 order by id desc');
     $get_event_like_dislike = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $eventId])->first();
     $like_names ='';
     foreach ($event_like_name as $like_name) {
         $profile_img = DB::table('front_users')->where(["id" => $like_name->user_id])->first();
         if(isset($profile_img->photo) && !empty($profile_img->photo)){ 
             $image = asset('front/profile/'.$profile_img->photo);
         } else {
             $image = asset('front/images/details_default.png');
         }
         if(isset($profile_img->spot_description)){
           $spot_desc = $profile_img->spot_description;
         } else {
           $spot_desc = '';
         }
         if(isset($profile_img->user_role)){
           $user_role = $profile_img->user_role;
         } else {
           $user_role = '';
         }
         if(Auth::guard('front_auth')->user()->user_role && $user_role == 'customer'){
             $url = route('customer.newprofile',$profile_img->first_name.'-'.$profile_img->last_name.'-'.$profile_img->id);
         } else {
             $url = url('provider/'.$spot_desc);
         } 
         $like_names .= '<a href="'.$url.'" >
                       <img src="'.$image.'" class="comments_profile">
                       <span class="likes_name">'.$like_name->name.'</span>
                       <span class="short_text">'.\Carbon\Carbon::parse($like_name->created_at)->diffForHumans().'</span></a><br><br>';
         
         
         
     }
     $dislike_names ='';
     foreach ($event_dislike_name as $dislike_name) {
         $profile_img = DB::table('front_users')->where(["id" => $dislike_name->user_id])->first();
         if(isset($profile_img->photo) && !empty($profile_img->photo)){ 
             $image = asset('front/profile/'.$profile_img->photo);
         } else {
             $image = asset('front/images/details_default.png');
         }

         if(isset($profile_img->spot_description)){
           $spot_desc = $profile_img->spot_description;
         } else {
           $spot_desc = '';
         }
          if(isset($profile_img->user_role)){
           $user_role = $profile_img->user_role;
         } else {
           $user_role = '';
         }
         if(Auth::guard('front_auth')->user()->user_role && $user_role == 'customer'){
             $url = route('customer.newprofile',$profile_img->first_name.'-'.$profile_img->last_name.'-'.$profile_img->id);
         } else {
             $url = url('provider/'.$spot_desc);
         }
         $dislike_names .= '<a href="'.$url.'" >
                       <img src="'.$image.'" class="comments_profile">
                       <span class="likes_name">'.$dislike_name->name.'</span>
                       <span class="short_text">'.\Carbon\Carbon::parse($dislike_name->created_at)->diffForHumans().'</span></a><br><br>';
         
         
     }
     $like_name = json_encode($like_names,JSON_FORCE_OBJECT);
     $dislike_name = json_encode($dislike_names,JSON_FORCE_OBJECT);
     $likes = $get_event_like_dislike->likes;
     $dislike = $get_event_like_dislike->dislike;
     $eventLikeCountUpdate = DB::update('update event_count set like_count="'.$event_like_count.'" where id="'.$eventId.'"');

     return response()->json(['like'=>$likes, 'dislike'=>$dislike, 'event_like_count'=>$event_like_count, 'event_dislike_count'=>$event_dislike_count, 'like_name'=>json_decode($like_name), 'dislike_name'=>json_decode($dislike_name)]);
     
 }

 public function EventDisLike($eventId){
     
     $customerId = Auth::guard('front_auth')->user()->id;
     $eventDisLikeCount = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $eventId])->count();
     $eventDisLike = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $eventId])->first();
     
     if($eventDisLikeCount == 0){
         $eventDisLikeInsert = DB::table('event_count')->insert([
                     'event_id' => $eventId,
                     'user_id' => Auth::guard('front_auth')->user()->id,
                     'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                     'dislike' => 1,
                     'created_at' => date('Y-m-d H:i:s')
                 ]);
         $dislike = 1;
     } else {
         if($eventDisLike->likes == 0 && $eventDisLike->dislike == 0){
             $eventLikeUpdate = DB::update('update event_count set dislike="1", name="'.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.'" where id="'.$eventDisLike->id.'"');
             $dislike = 1;
         } else if($eventDisLike->likes == 1 && $eventDisLike->dislike == 0){
             $eventLikeUpdate = DB::update('update event_count set likes=0, dislike=1, name="'.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.'" where id="'.$eventDisLike->id.'"');
             $dislike = 0;
         } else if($eventDisLike->likes == 0 && $eventDisLike->dislike == 1){
             $eventLikeUpdate = DB::update('update event_count set dislike=0, name="'.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.'" where id="'.$eventDisLike->id.'"');
             $dislike = 0;
         }
     }
     $event_like_count = DB::table('event_count')->where(["event_id" => $eventId, "likes" => 1])->count();
     $event_dislike_count = DB::table('event_count')->where(["event_id" => $eventId, "dislike" => 1])->count();
     $event_dislike_name = DB::select('select name, user_id, created_at from event_count where event_id="'.$eventId.'" and dislike = 1 order by id desc');
     $event_like_name = DB::select('select name, user_id, created_at from event_count where event_id="'.$eventId.'" and likes = 1 order by id desc');
     $get_event_like_dislike = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $eventId])->first();
     $dislike_names ='';
     foreach ($event_dislike_name as $dislike_name) {
         $profile_img = DB::table('front_users')->where(["id" => $dislike_name->user_id])->first();
         if(isset($profile_img->photo) && !empty($profile_img->photo)){ 
             $image = asset('front/profile/'.$profile_img->photo);
         } else {
             $image = asset('front/images/details_default.png');
         }

         if(isset($profile_img->spot_description)){
           $spot_desc = $profile_img->spot_description;
         } else {
           $spot_desc = '';
         }
          if(isset($profile_img->user_role)){
           $user_role = $profile_img->user_role;
         } else {
           $user_role = '';
         }
         if(Auth::guard('front_auth')->user()->user_role && $user_role == 'customer'){
             $url = route('customer.newprofile',$profile_img->first_name.'-'.$profile_img->last_name.'-'.$profile_img->id);
         } else {
             $url = url('provider/'.$spot_desc);
         }
         $dislike_names .= '<a href="'.$url.'" >
                       <img src="'.$image.'" class="comments_profile">
                       <span class="likes_name">'.$dislike_name->name.'</span>
                       <span class="short_text">'.\Carbon\Carbon::parse($dislike_name->created_at)->diffForHumans().'</span></a><br><br>';
         
         
     }
     $like_names ='';
     foreach ($event_like_name as $like_name) {
         $profile_img = DB::table('front_users')->where(["id" => $like_name->user_id])->first();
         if(isset($profile_img->photo) && !empty($profile_img->photo)){ 
             $image = asset('front/profile/'.$profile_img->photo);
         } else {
             $image = asset('front/images/details_default.png');
         }
         if(isset($profile_img->spot_description)){
           $spot_desc = $profile_img->spot_description;
         } else {
           $spot_desc = '';
         }
         if(isset($profile_img->user_role)){
           $user_role = $profile_img->user_role;
         } else {
           $user_role = '';
         }
         if(Auth::guard('front_auth')->user()->user_role && $user_role == 'customer'){
             $url = route('customer.newprofile',$profile_img->first_name.'-'.$profile_img->last_name.'-'.$profile_img->id);
         } else {
             $url = url('provider/'.$spot_desc);
         } 
         $like_names .= '<a href="'.$url.'" >
                       <img src="'.$image.'" class="comments_profile">
                       <span class="likes_name">'.$like_name->name.'</span>
                       <span class="short_text">'.\Carbon\Carbon::parse($like_name->created_at)->diffForHumans().'</span></a><br><br>';            
         
     }
     $dislike_name = json_encode($dislike_names,JSON_FORCE_OBJECT);
     $like_name = json_encode($like_names,JSON_FORCE_OBJECT);
     $likes = $get_event_like_dislike->likes;
     $dislike = $get_event_like_dislike->dislike;
     $eventLikeCountUpdate = DB::update('update trainer_events set like_count="'.$event_like_count.'" where id="'.$eventId.'"');

     return response()->json(['dislike'=>$dislike, 'like'=>$likes, 'event_like_count'=>$event_like_count, 'event_dislike_count'=>$event_dislike_count, 'dislike_name'=>json_decode($dislike_name), 'like_name'=>json_decode($like_name)]);
     
 }



public function attendeesSaveing(Request $request) {

 $data = array();
 $data['event_id'] = $request->get('event_id');
 $data['trainer_id'] = $request->get('trainer_id');
 $data['attender_id'] = $request->get('attender_id');
 $data['attender_first'] = $request->get('attender_first');
 $data['attender_last'] = $request->get('attender_last');
 $data['attender_email'] = $request->get('attender_email');
 $data['is_payment'] = 1;
 $data['event_type'] = $request->get('event_type');
 $data['rsvp'] = "Attending";
 $data['created_at'] = Carbon::now(); 

 $query_insert = DB::table('event_registration')->insert($data);

        $user = Auth::guard('front_auth')->user();
        $attendeeDetails = FrontUsers::where('id', '=', $user->id)->first();
        $trainerDetails = FrontUsers::where('id', '=', $request->get('trainer_id'))->first();
        $eventDetails = TrainerEvent::where('id', '=', $request->get('event_id'))->first();
        $eventURL = url('/event-details/'.base64_encode($request->get('event_id')));
 if($query_insert){
          $event_mail_athelete = new PHPMailer;
          $event_mail_athelete->IsSMTP();
          $event_mail_athelete->SMTPAuth = true;
          $event_mail_athelete->SMTPSecure = env('MAIL_SECURE');
          $event_mail_athelete->Host = env('MAIL_HOST');
          $event_mail_athelete->Port = env('MAIL_PORT');
          $event_mail_athelete->Username = env('MAIL_USERNAME');
          $event_mail_athelete->Password = env('MAIL_PASSWORD');
          $event_mail_athelete->SetFrom(env('MAIL_FROM'), 'Training Block');
          $event_mail_athelete->Subject = "Event Registration Successful";
          $event_mail_athelete->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
          <tbody><tr style="
              background: #555;
          ">
              <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="'.url("/public/images/logo.png").'"> </td>
          </tr>
          <tr>
              <td style="padding-top:20px;"> <h4>Hi '.$attendeeDetails->first_name.'!</h4> </td>
          </tr>
            
          <tr>
              <td style="padding-bottom:15px;"> 
                 <p>You have successfully registered for: <a href="'.$eventURL.'">'.$eventDetails->title.'</a> . This event is on '.$eventDetails->start_date.' at '.$eventDetails->start_time.'.</p>
                 <p>You can find out more information, or manage your RSVP, in your Training Block account.</p>
              </td>
          </tr>
          <tr>
                <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
                <p></p>
                <span style="margin-top:5px;">The Training Block Team</span>
                </td>
          </tr>
            
          </tbody></table></body></html>');
          $event_mail_athelete->AddAddress($attendeeDetails->email, 'Training Block');

          if (!$event_mail_athelete->send()) {
              echo 'Mailer Error: ' . $event_mail_athelete->ErrorInfo;
          } else {
              echo 'Message sent!';
              //
              $event_mail_provider = new PHPMailer;
              $event_mail_provider->IsSMTP();
              $event_mail_provider->SMTPAuth = true;
              $event_mail_provider->SMTPSecure = env('MAIL_SECURE');
              $event_mail_provider->Host = env('MAIL_HOST');
              $event_mail_provider->Port = env('MAIL_PORT');
              $event_mail_provider->Username = env('MAIL_USERNAME');
              $event_mail_provider->Password = env('MAIL_PASSWORD');
              $event_mail_provider->SetFrom(env('MAIL_FROM'), 'Training Block');
              $event_mail_provider->Subject = "Event Registration";
              $event_mail_provider->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
              <tbody><tr style="
                  background: #555;
              ">
                  <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="'.url("/public/images/logo.png").'"> </td>
              </tr>
              <tr>
                  <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
              </tr>

              <tr>
                  <td style="padding-bottom:15px;"> 
                     <p> '.$attendeeDetails->first_name.' '.$attendeeDetails->last_name.' has registered the event <a href="'.$eventURL.'">'.$eventDetails->title.'</a>. Kindly log in to your account for more information.</p>
                  </td>
              </tr>
              <tr>
              <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
              <p></p>
              <span style="margin-top:5px;">The Training Block Team</span>
              </td>
              </tr>

              </tbody></table></body></html>');
              $event_mail_provider->AddAddress($trainerDetails->email, 'Training Block');

              if (!$event_mail_provider->send()) {
                  echo 'Mailer Error: ' . $event_mail_provider->ErrorInfo;
              } else {
                  echo 'Message sent!';
              }
          }

          Session::flash('message', 'You have successfully registered for this event.');
        }
 


Session::flash('message', 'You have successfully registered for this event.');

 $userCurrent = Auth::guard('front_auth')->user();
 return redirect()->to("/profile/$userCurrent->first_name-$userCurrent->last_name-$userCurrent->id#rsvp");

 }

 
public function ChangeRsvp(Request $request) {
    $user = Auth::guard('front_auth')->user(); 
    
    DB::table('event_registration')
     ->where('id', $request->get('id'))  
     ->where('attender_id', $user->id)  
     ->update(['rsvp' => $request->get('rsvp_status')]);        
     
    return Redirect::to(URL::previous().'#rsvp');
}



 
    public function EventComment(Request $request){
        
        $customerId = Auth::guard('front_auth')->user()->id;
        $eventreCommentCount = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $request->event_id])->count();
        $eventComment = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $request->event_id])->first();
        
        if($eventreCommentCount == 0){
            $eventComment = DB::table('event_count')->insert([
                        'event_id' => $request->event_id,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'comments' => $request->comments,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            $eventComments = DB::table('event_comments')->insert([
                        'event_id' => $request->event_id,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'comments' => $request->comments,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
        } else {
            $eventCommentUpdate = DB::update('update event_count set comments="'.$request->comments.'", updated_at="'.date('Y-m-d H:i:s').'" where id="'.$eventComment->id.'"');
            $eventComments = DB::table('event_comments')->insert([
                        'event_id' => $request->event_id,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'comments' => $request->comments,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
            
        }

        $event_Comment_Count = DB::table('event_comments')->where(["event_id" => $request->event_id])->count();

        $eventCommentCountUpdate = DB::update('update trainer_events set comment_count="'.$event_Comment_Count.'" where id="'.$request->event_id.'"');


    // Mail Notification while athelete comments the event        

    $trainer_email = $request->provider_email;
    $comment_mail = new PHPMailer;
    $comment_mail->IsSMTP();
    $comment_mail->SMTPAuth = true;
    $comment_mail->SMTPSecure = env('MAIL_SECURE');
    $comment_mail->Host = env('MAIL_HOST');
    $comment_mail->Port = env('MAIL_PORT');
    $comment_mail->Username = env('MAIL_USERNAME');
    $comment_mail->Password = env('MAIL_PASSWORD');
    $comment_mail->SetFrom(env('MAIL_FROM'), 'Training Block');
    $comment_mail->Subject = "Training Block - event Comments";
    $comment_mail->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
    <tbody><tr style="
        background: #555;
    ">
        <td style="padding:10px;border-bottom:solid 1px #555;text-align: center;"> <img src="'.url("/public/images/logo.png").'"> </td>
    </tr>
    <tr>
        <td style="background: #00ab91;padding:10px;color:#fff;"> <p> This is an automated message. Please do not reply to this email</p> </td>
    </tr>
    <tr>
        <td style="padding-top:20px;"> <h4>Hi '.$request->provider_name.',</h4>
        <p>Someone just left a comment on one of your events! Check it out here:</p><br /> </td>
    </tr>
    <tr>
        <td style="padding-bottom:15px;"> 
            <br />
            <a href="'.$request->event_url.'" style="background: #00ab91;color: #fff;padding: 10px;border-radius: 5px;text-decoration: none;">View Comment</a>
            <br />
        </td>
    </tr>
    <tr>
                <td style="background:#555;color:#fff;padding:15px;"> <span>Thanks,</span><br>
                <p style="margin-top:5px;">The Training Block Team</p>
                </td>
            </tr>
    </tbody></table></body></html>');
    $comment_mail->AddAddress($trainer_email , 'Training Block');

    if (!$comment_mail->send()) {
        echo 'Mailer Error: ' . $comment_mail->ErrorInfo;
    } else {
        echo 'Message sent!';
    }

        return Redirect::to(URL::previous());
        
    }


    public function EventDetailComment(Request $request){
        
        $customerId = Auth::guard('front_auth')->user()->id;
        $eventreCommentCount = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $request->event_id])->count();
        $eventComment = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $request->event_id])->first();
        
        if($eventreCommentCount == 0){
            $eventComment = DB::table('event_count')->insert([
                        'event_id' => $request->event_id,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'comments' => $request->comments,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            $eventComments = DB::table('event_comments')->insert([
                        'event_id' => $request->event_id,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'comments' => $request->comments,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
        } else {
            $eventCommentUpdate = DB::update('update event_count set comments="'.$request->comments.'", updated_at="'.date('Y-m-d H:i:s').'" where id="'.$eventComment->id.'"');
            $eventComments = DB::table('event_comments')->insert([
                        'event_id' => $request->event_id,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'comments' => $request->comments,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
            
        }

        $event_Comment_Count = DB::table('event_comments')->where(["event_id" => $request->event_id])->count();

        $eventCommentCountUpdate = DB::update('update trainer_events set comment_count="'.$event_Comment_Count.'" where id="'.$request->event_id.'"');


    // Mail Notification while athelete comments the event        

    $trainer_email = $request->provider_email;
    $comment_mail = new PHPMailer;
    $comment_mail->IsSMTP();
    $comment_mail->SMTPAuth = true;
    $comment_mail->SMTPSecure = env('MAIL_SECURE');
    $comment_mail->Host = env('MAIL_HOST');
    $comment_mail->Port = env('MAIL_PORT');
    $comment_mail->Username = env('MAIL_USERNAME');
    $comment_mail->Password = env('MAIL_PASSWORD');
    $comment_mail->SetFrom(env('MAIL_FROM'), 'Training Block');
    $comment_mail->Subject = "Training Block - event Comments";
    $comment_mail->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
    <tbody><tr style="
        background: #555;
    ">
        <td style="padding:10px;border-bottom:solid 1px #555;text-align: center;"> <img src="'.url("/public/images/logo.png").'"> </td>
    </tr>
    <tr>
        <td style="background: #00ab91;padding:10px;color:#fff;"> <p> This is an automated message. Please do not reply to this email</p> </td>
    </tr>
    <tr>
        <td style="padding-top:20px;"> <h4>Hi '.$request->provider_name.',</h4>
        <p>Someone just left a comment on one of your events! Check it out here:</p><br /> </td>
    </tr>
    <tr>
        <td style="padding-bottom:15px;"> 
        <br />
            <a href="'.$request->event_url.'" style="background: #00ab91;color: #fff;padding: 10px;border-radius: 5px;text-decoration: none;">View Comment</a>
            <br />
        </td>
    </tr>
    <tr>
                <td style="background:#555;color:#fff;padding:15px;"> <span>Thanks,</span><br>
                <p style="margin-top:5px;">The Training Block Team</p>
                </td>
            </tr>
    </tbody></table></body></html>');
    $comment_mail->AddAddress($trainer_email , 'Training Block');

    if (!$comment_mail->send()) {
        echo 'Mailer Error: ' . $comment_mail->ErrorInfo;
    } else {
        echo 'Message sent!';
    }


        return back();
        
    }

    
    public function EventSave($eventId){
        $customerId = Auth::guard('front_auth')->user()->id;
        $eventeventSaveCount = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $eventId])->count();
        $eventSave = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $eventId])->first();
        
        if($eventeventSaveCount == 0){
            $eventSave = DB::table('event_count')->insert([
                        'event_id' => $eventId,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'saved' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
            $saved = 1;
        } else {
            if($eventSave->saved == 0){
                $eventSaveUpdate = DB::update('update event_count set saved="1" where id="'.$eventSave->id.'"');
                $saved = 1;
            } else if($eventSave->saved == 1 ){
                $eventSaveUpdate = DB::update('update event_count set saved=0 where id="'.$eventSave->id.'"');
                $saved = 0;
            } 
        }

        return response()->json(['saved'=>$saved]);
        
    }







}