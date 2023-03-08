<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Users\Entities\FrontUsers;
use Modules\CMSPages\Entities\CMSPages;
use App\Tips;
use App\Ratings;
use App\User;
use App\TrainerServices;
use App\Services;
use App\StripeAccounts;
use Modules\Contactus\Entities\ContactUs;
use Validator;
use Mail,
    Redirect;
use Stripe;

class FrontController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $tips = Tips::All();
        $aboutus_page = CMSPages::where('slug', 'about-us')->first();
        $Ratings = Ratings::with('orders.users')->first();
        $featuredTrainerlist = FrontUsers::where('user_role', 'trainer')->where('is_feature', '=', 1)->has('featuredservice')->limit(3)->get();
        $trainerList = FrontUsers::where('user_role', 'trainer')->with(['featuredservice', 'orders.Ratting'])->has('featuredservice')->get();

        $trainerList->transform(function ($v) {
            if (isset($v->Orders)) {
                $retingdata = $v->Orders->transform(function ($v1) {
                    if (isset($v1->Ratting->rating)) {
                        return $v1->Ratting->rating;
                    }
                });
                $retingdata = $retingdata->reject(function ($item) {
                    return is_null($item);
                });
                $r = 0;
                foreach ($retingdata as $rdata) {
                    $r += $rdata;
                }
                if ($r != 0) {
                    $v->ratting = $r / $retingdata->count();
                } else {
                    $v->ratting = 0;
                }
            }
            return $v;
        });


        return view('front.home', compact('tips', 'aboutus_page', 'featuredTrainerlist', 'trainerList'));
    }

    public function exploreservices() {
        $tips = Tips::All();
        $aboutus_page = CMSPages::where('slug', 'about-us')->first();
        $Ratings = Ratings::with('orders.users')->first();
        return view('front.exploreservices', compact('tips', 'aboutus_page'));
    }

    public function exploreservicessearch(Request $request) {
        $services = Services::all();
        $TrainerServicesdataall = TrainerServices::All();
        $TrainerServicesdata = TrainerServices::with(['trainer.orders.Ratting', 'service']);
        // dd($TrainerServicesdata->get());
        if (isset($request->keyword) && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $TrainerServicesdata->whereHas('trainer', function ($query) use ($keyword) {
                $query->where('first_name', 'LIKE', '%' . $keyword . '%')->orwhere('last_name', 'LIKE', '%' . $keyword . '%');
            })->orwhere('name', 'LIKE', '%' . $keyword . '%');
        }
        if (isset($request->location) && !empty($request->location)) {
            $location = $request->location;
            $TrainerServicesdata->whereHas('trainer', function ($query) use ($location) {
                $query->where('address_1', 'LIKE', '%' . $location . '%');
                $query->orWhere('city', 'like', '%' . $location . '%');
                $query->orWhere('state', 'like', '%' . $location . '%');
            });
        }
        if (isset($request->services) && !empty($request->services)) {
            $TrainerServicesdata->where('service_id', $request->services);
        } else {
            $TrainerServicesdata->where('is_featured', 'yes');
            $TrainerServicesdata->groupBy('trainer_id');
        }
        if (isset($request->ratings) && !empty($request->ratings)) {
            
        }
        if (isset($request->format) && !empty($request->format)) {
            $TrainerServicesdata->where('format', $request->format);
        }

        $TrainerServicesdata = $TrainerServicesdata->paginate(15);

        $TrainerServicesdata->transform(function ($v) {
            $r = 0;
            if (isset($v->trainer->orders)) {
                $retingdata = $v->trainer->orders->transform(function ($v1) use($r) {
                    if (isset($v1->Ratting->rating)) {
                        return $v1->Ratting->rating;
                    }
                });
                $p = 0;
                foreach ($retingdata as $rdata) {
                    if ($rdata != '') {
                        $r += $rdata;
                        $p++;
                    }
                }
                if ($r != 0) {
                    $v->ratting = round($r / $p);
                } else {
                    if (!isset($v->ratting)) {
                        $v->ratting = 0;
                    }
                }
            }
            return $v;
        });
        if ($request->ajax()) {
            return view('front.exploreservicessearch', compact('services', 'TrainerServicesdata', 'request', 'TrainerServicesdataall'));
        } else {
            return view('front.exploreservices', compact('services', 'TrainerServicesdata', 'request', 'TrainerServicesdataall'));
        }
    }

    public function aboutus() {
        $cmsdata = getCmsPages('about-us');
        $howitwork = getCmsPages('how-it-works');
        $ourmission = getCmsPages('our-mission');
        return view('front.aboutus', compact('cmsdata', 'howitwork', 'ourmission'));
    }

    public function contactus() {
        $cmsdata = getCmsPages('about-us');
        $howitwork = getCmsPages('how-it-works');
        $ourmission = getCmsPages('our-mission');
        return view('front.contactus', compact('cmsdata', 'howitwork', 'ourmission'));
    }

    public function contactussave(Request $request) {
        $input = $request->all();
        $valid = Validator::make($input, [
                    'name' => "required",
                    'email' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $contactform = ContactUs::create([
                        'name' => $input['name'],
                        'email' => $input['email'],
                        'phone_number' => @$input['phone_number'],
                        'message' => @$input['message'],
            ]);
            $emails = User::where('role_id', '1')->first()->email;
            $subject = "Contact & Support";
            $emails_name = 'Training Block';
            $admin_email = "auto-reply@trainingblockusa.com";
            $admin_name = "Training Block";
            $emails = $input['email'];
            Mail::send('email.contact', [
                'name' => ucfirst($input['name']),
                'email' => $input['email'],
                'messages' => $input['message'],
                'phone_number' => $input['phone_number'],
                    ], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                $message->from($admin_email, $admin_name);
                $message->to($emails, $emails_name)->subject($subject);
            });
            $mailToadmin = "maulik@mailinator.com";
            Mail::send('email.contact_email_admin', [
                'name' => ucfirst($input['name']),
                'email' => $input['email'],
                'messages' => $input['message'],
                'phone_number' => $input['phone_number'],
                    ], function ($message) use ($admin_email, $admin_name, $subject, $mailToadmin, $emails_name) {
                $message->from($admin_email, $admin_name);
                $message->to($mailToadmin, $emails_name)->subject($subject);
            });

            $msg = "Thank you for filling out your information!";
            return Redirect::route('contactuspage')->with('success', $msg);
        }
    }

    public function showTrainerDetail($name) {
        $trainer = FrontUsers::where('spot_description', $name)->first();

        if (empty($trainer)) {
            return redirect()->intended(route('exploreservices'));
        }
        $trainerData = FrontUsers::where(["id" => $trainer->id])
                ->with(["featuredservice", "services.service", "Orders.Ratting"])
                ->with(['services' => function ($query) {
                        $query->where(['status' => "1"]);
                    }
                        ])
                        ->get();
                //dd($trainerData);
                //$trainerService = TrainerServices::where('trainer_id', $trainer->id)->where('is_featured', 'yes')->with(['service','trainer','Orders.Ratting'])->get();
                // if($trainerService->count() == 0){
                //     return redirect()->intended(route('exploreservices'));
                // }
                $serviceEventsData = FrontUsers::where(["id" => $trainer->id])->with(["featuredservice", "services.service", "Orders.Ratting", "allOrders.service"])->first();
                $trainerList = FrontUsers::where('user_role', 'trainer')->with(['featuredservice', 'orders.Ratting'])->has('featuredservice')->get();
                // dd($serviceEventsData);
                $returnarray = array();
                $array = array();
                foreach ($serviceEventsData->allOrders as $serviceEvent) {
                    $startDate = strtotime($serviceEvent->start_date);
                    $startDate = date('Y-m-d', $startDate);

                    if ($serviceEvent->start_date == $serviceEvent->end_date) {
                        $endDate = strtotime($serviceEvent->end_date);
                    } else {
                        $endDate = strtotime($serviceEvent->end_date . ' +1 day');
                    }
                    $endDate = date('Y-m-d', $endDate);

                    $time = explode("-", $serviceEvent->service_time);
                    $starttime = strtotime($time[0]);
                    $starttime = date('H:i:s', $starttime);

                    $array = array(
                        'title' => $serviceEvent->service->name,
                        'start' => $startDate . 'T' . $starttime,
                        'end' => $endDate,
                        //'time' => $serviceEvent->service_time
                        'description' => '<b>Service Name : </b>' . $serviceEvent->service->name . '<br><b>Start Date : </b>' . $startDate . '<br><b>End Date : </b>' . $serviceEvent->end_date . '<br><b>Time : </b>' . $serviceEvent->service_time . '<br>',
                    );
                    $returnarray[] = $array;
                }

                $trainerData->transform(function ($v) {
                    $r = 0;
                    if (isset($v->orders)) {
                        $retingdata = $v->orders->transform(function ($v1) use($r) {
                            if (isset($v1->Ratting->rating)) {
                                return $v1->Ratting->rating;
                            }
                        });
                        $p = 0;
                        foreach ($retingdata as $rdata) {
                            if ($rdata != '') {
                                $r += $rdata;
                                $p++;
                            }
                        }
                        if ($r != 0) {
                            $v->ratting = round($r / $p);
                        } else {
                            if (!isset($v->ratting)) {
                                $v->ratting = 0;
                            }
                        }
                    }
                    return $v;
                });

                $trainerData = $trainerData->first();
                //$services = FrontUsers::find($trainerService->trainer_id)->services()->with(['service'])->get();

                return view('front.trainer-detail', ["trainerData" => $trainerData, "eventData" => json_encode($returnarray), "trainerList" => $trainerList]);
            }

            public function bookNowLogin($booknow) {
                //dd($booknow);
                if ($booknow == 'booknow') {
                    $tabName = "customer";
                    return view('front.auth.login', ["booknow" => true, "tabName" => $tabName]);
                }
            }

            public function webhookcustom(Request $request) {
                //dd($request->all());
                $payload = @file_get_contents('php://input');
                $event = null;

                try {
                    $event = \Stripe\Event::constructFrom(
                                    json_decode($payload, true)
                    );
                } catch (\UnexpectedValueException $e) {
                    // Invalid payload
                    http_response_code(400);
                    exit();
                }

                // Handle the event
                switch ($event->type) {
                    case 'payment_intent.succeeded':
                        $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                        // Then define and call a method to handle the successful payment intent.
                        // handlePaymentIntentSucceeded($paymentIntent);
                        break;
                    case 'invoice.payment_succeeded':
                        $invoice = $event->data->object;
                        // echo "<pre>";
                        // print_r($invoice);
                        // echo "</pre>";
                        $subject = "Subscription payment invoice";
                        $emails_name = 'Training Block';
                        $admin_email = "auto-reply@trainingblockusa.com";
                        $admin_name = "Training Block";
                        $emails = $invoice->customer_email;
                        Mail::send('email.payment-invoice', ["invoice" => $invoice], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                            $message->from($admin_email, $admin_name);
                            $message->to($emails, $emails_name)->subject($subject);
                        });
                        break;
                    case 'invoice.payment_failed':
                        $invoice = $event->data->object;
                        // echo "<pre>";
                        // print_r($invoice);
                        // echo "</pre>";
                        $subject = "Subscription payment invoice";
                        $emails_name = 'Training Block';
                        $admin_email = "auto-reply@trainingblockusa.com";
                        $admin_name = "Training Block";
                        $emails = $invoice->customer_email;
                        Mail::send('email.payment-invoice', ["invoice" => $invoice], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                            $message->from($admin_email, $admin_name);
                            $message->to($emails, $emails_name)->subject($subject);
                        });
                        break;
                    // ... handle other event types
                    default:
                        // Unexpected event type
                        http_response_code(400);
                        exit();
                }

                http_response_code(200);
            }

            public function stripeconnect() {

                return view('front.stripeconnect', ["booknow" => true]);
            }

            function getstripeurl() {
                return 'https://connect.stripe.com/oauth/authorize?client_id=ca_HM9JmrpeLtNkNIDRwuw5xCoGfW4afEXH&state=traingblock&scope=read_write&response_type=code&stripe_user[email]=user@example.com&stripe_user[url]=example.com';
            }

            function stripestore(Request $request) {
                 $rerdata = $request->all(); 
                 if (isset($rerdata['code'])) {
                $dbdata = array();
                     $code = $rerdata['code'];
                //$code = 'ac_HMNFw2mTi5tfPen14gOxvccGM96QhNQl';
                     
                $client_secret = 'sk_test_p94u4c8YUZ8Mydw7r3GT1Cjg';
                Stripe\Stripe::setApiKey($client_secret);
                $url = 'https://connect.stripe.com/oauth/token';
                //$tokendata = GetToken($url, $client_secret, $code);
                $tokendata = \Stripe\OAuth::token([
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                  ]);
                if (!empty($tokendata) && isset($tokendata->access_token)) {
                    $dbdata['user_id'] = '1';
                    $dbdata['access_token'] = $tokendata->access_token;
                    $dbdata['token_type'] = $tokendata->token_type;
                    if (isset($tokendata->refresh_token)) {
                        $dbdata['refresh_token'] = $tokendata->refresh_token;
                    }
                    $dbdata['stripe_publishable_key'] = $tokendata->stripe_publishable_key;
                    $dbdata['stripe_user_id'] = $tokendata->stripe_user_id;
                    $formData = StripeAccounts::where(['user_id' => $dbdata['user_id'], 'stripe_user_id' => $tokendata->stripe_user_id])->orderBy('id', 'desc')->first();
                    if (empty($formData)) {
                        $contactform = StripeAccounts::create($dbdata);
                    } else {
                        $contactform = StripeAccounts::where('stripe_user_id', $tokendata->stripe_user_id)->update($dbdata);
                    }
                    $msg = "Thank you for Connecting !";
                    return Redirect::route('stripecreate')->with('success', $msg);
                }else{
					$msg = "Please try again!";
					return Redirect::route('stripecreate')->with('error', $msg);
				}
                 }
            }

        }
        