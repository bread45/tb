<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Users\Entities\FrontUsers;
use Auth;
use Config;
use App\TrainerServices;
use App\States;
use Modules\Orders\Entities\Orders;
use App\ReferralBonus;
use App\Mail\OrderConfirmNotify;
use App\Mail\TrainerNotify;
use App\StripeAccounts;
use App\TrainerEvent;
use Session;
use Validator;
use Stripe;
use Mail;
use Redirect;
use DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once(app_path().'/../mail/PHPMailer/Exception.php');
include_once(app_path().'/../mail/PHPMailer/PHPMailer.php');
include_once(app_path().'/../mail/PHPMailer/SMTP.php');
include_once(app_path().'/../mail/vendor/autoload.php');

class PaymentController extends Controller
{
    public function book_now($id=null){ 
        // in_1GlWjCBn3qI2bf8mF3OzqyU1
        //    Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));  

        //   $invoice = \Stripe\Plan::retrieve(
        //    'plan_HOl1ORieG6V0vv'
        //   );
        //dd($id);
        if(Session::has('url.intended'))
        {
            Session::forget('url.intended');
        }
        if(!$id){
          abort(404);
        }
        $trainerId = base64_decode($id);
        if(!(is_numeric($trainerId) && $trainerId > 0 && $trainerId == round($trainerId, 0))){
          abort(404);
        }
       // dd($trainerId);
        if($trainerId){
          //$states = ['Jacksonville','Florida','Orlando'];
        $states = States::all();
        $timeSlots = Config::get('constants.timeSlots');
        $customer = Auth::guard('front_auth')->user();
      
        $trainer = FrontUsers::where(["id" => $trainerId])
                              ->with(["services.service", "featuredservice", "onlyservices"])
                              ->with(['services' => function ($query) {
                                $query->where(['status'=> "1"]);}
                                ])
                              ->first();
      
           if($trainer){
            $user = Auth::guard('front_auth')->user();
            $walletAmount = $user->referral_wallet;
            $maxWalletAmount = getSetting('max-wallet');
            $adminFeesPercent = getSetting('admin-fees');

            if($walletAmount <= $maxWalletAmount){
              $total_discount = $walletAmount;
              $walletAmount = 0;
            }else{
              $total_discount = $maxWalletAmount;
              $walletAmount = $walletAmount - $maxWalletAmount;
            }
            //$adminFees = $trainer->featuredservice->price*($adminFeesPercent/100);
            $adminFees = 0;
            $StripeAccountsData = StripeAccounts::where('user_id',$trainer->id)->orderBy('id', 'desc')->first();
                  
            return view('front.book-now', ['customer' => $customer, 'states' => $states, 'trainer'=> $trainer, 'timeSlots' => $timeSlots, "refDiscount" => $total_discount, "adminFees" => $adminFees,'StripeAccountsData'=> $StripeAccountsData]);
           }else{
            abort(404);
           }   
        }else{
          abort(404);
        }
        
    }


    public function book_now_service(Request $request){
      
      

      if(Session::has('url.intended'))
        {
            Session::forget('url.intended');
        }

        $providerScheduling = DB::table('provider_service_book')->where(["id" => $request->trainer_id])->first();
        
        $trainerId = $providerScheduling->trainer_id;
        if(!(is_numeric($trainerId) && $trainerId > 0 && $trainerId == round($trainerId, 0))){
          abort(404);
        }
       // dd($trainerId);exit();
        if($trainerId){
          //$states = ['Jacksonville','Florida','Orlando'];
        $states = States::all();
        //$timeSlots = Config::get('constants.timeSlots');
        $timeSlots = $request->timeslot;
        $customer = Auth::guard('front_auth')->user();
      
        $trainer = FrontUsers::where(["id" => $trainerId])
                              //->with(["services.service", "featuredservice", "onlyservices"])
                              //->with(['services' => function ($query) {
                               // $query->where(['status'=> "1"]);}
                               // ])
                              ->first();
          $services = TrainerServices::where('id', $providerScheduling->service_id)->first();
        //echo '<pre>';print_r($services->book_type);exit();
           if($trainer){
            $user = Auth::guard('front_auth')->user();
            $walletAmount = $user->referral_wallet;
            $maxWalletAmount = getSetting('max-wallet');
            $adminFeesPercent = getSetting('admin-fees');

            if($walletAmount <= $maxWalletAmount){
              $total_discount = $walletAmount;
              $walletAmount = 0;
            }else{
              $total_discount = $maxWalletAmount;
              $walletAmount = $walletAmount - $maxWalletAmount;
            }
            //$adminFees = $trainer->featuredservice->price*($adminFeesPercent/100);
            $adminFees = 0;
            $StripeAccountsData = StripeAccounts::where('user_id',$trainer->id)->orderBy('id', 'desc')->first();
            $week_days = $request->week_days;
            $appointment_date = $request->appointment_date;
            $event_id = $request->event_id;
            if($services->book_type == 2){
              return view('front.request-now', ['customer' => $customer, 'states' => $states, 'trainer'=> $trainer, 'timeSlots' => $timeSlots, "refDiscount" => $total_discount, "adminFees" => $adminFees,'StripeAccountsData'=> $StripeAccountsData, "services" => $services, "week_days" => $week_days, "appointment_date" => $appointment_date, "event_id" => $event_id]);
            } else {
              return view('front.book-now', ['customer' => $customer, 'states' => $states, 'trainer'=> $trainer, 'timeSlots' => $timeSlots, "refDiscount" => $total_discount, "adminFees" => $adminFees,'StripeAccountsData'=> $StripeAccountsData, "services" => $services, "week_days" => $week_days, "appointment_date" => $appointment_date, "event_id" => $event_id]);
            }
           }else{
            abort(404);
           }   
        }else{
          abort(404);
        }
    }

    public function create_order(Request $request){
        //dd($request->all());
        $requestData = $request->all();
        //parse_str($request->form_data, $searcharray);
        //$requestData = $searcharray;
        //dd($requestDatas);exit();
        $errors = Validator::make($requestData, [
                    //'user_id' => 'required',
                    //'first_name' => 'required',
                    //'last_name' => 'required',
                   // 'phone_number' => 'required',
                    //'address' => 'required',
                    //'state' => 'required',
                    //'country' => 'required',
                    //'city' => 'required',
                    //'zip_code' => 'required',
                    //'service' => 'required',
                    //'start_date' => 'required',
                    //'time_slot' => 'required',
                    //'service_amount' => 'required_without:recurring_options',
                   // 'recurring_options' => 'required_with:recurring'
        ]);
        if ($errors->fails()) { 
          
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
            $adminFeesPercent = getSetting('admin-fees');
            $getCouponDetails = DB::table('coupons')->where(["trainer_id" => $requestData["trainerid"]])->first();
            $getServiceDetails = DB::table('trainer_services')->where(["id" => $requestData["service"]])->first();
            
            if($getServiceDetails->format == 'In person - Single Appointment' || $getServiceDetails->format == 'Virtual - Single Appointment'){

                if($getServiceDetails->promo_code == 1){
                    if($getCouponDetails->status == 1){
                        $promocode = $getCouponDetails->percentage+$adminFeesPercent;
                        $total_discount = ($promocode/100)*$getServiceDetails->price;
                        $amountToPay = $getServiceDetails->price - $total_discount;
                        $adminFees = ($adminFeesPercent/100)*$getServiceDetails->price;
                    } else {
                        $adminFees = ($adminFeesPercent/100)*$getServiceDetails->price;
                        $amountToPay = $getServiceDetails->price - $adminFees;
                        $total_discount = 0;
                    }
                    
                } else {
                    $adminFees = ($adminFeesPercent/100)*$getServiceDetails->price;
                    $amountToPay = $getServiceDetails->price - $adminFees;
                    $total_discount = 0;
                }

                    $order = new Orders();
                    $order->user_id = $requestData["user_id"];
                    $order->first_name = $requestData["first_name"];
                    $order->last_name = $requestData["last_name"];
                    $order->phone = $requestData["phone_number"];
                    $order->address = $requestData["address"];
                    $order->apartment_no = $requestData["apt_no"];
                    $order->state = $requestData["state"];
                    $order->country = $requestData["country"];
                    $order->city = $requestData["city"];
                    $order->zip_code = $requestData["zip_code"];
                    $order->type = "order";
                    $order->service_id = $requestData["service"];
                    $order->trainer_id = $requestData["trainerid"];
                   // $order->service_date = $requestData["dates"];
                    //$order->start_date = $startDate;
                    $order->plan_type =  $requestData["plan_type"];
                    $order->service_time = $requestData["time_slot"];
                    $order->status = 1;
                    $order->ref_discount = $total_discount;
                    $order->admin_fees = $adminFees;

                    Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));  

                    $TrainerServicesdata = StripeAccounts::where('user_id',$requestData["trainerid"])->first();
                    try {
                            if(!empty($TrainerServicesdata)){
                              $payment = Stripe\Charge::create ([
                                  "amount" => $amountToPay,
                                  "currency" => "usd",
                                  "source" => $request->stripeToken,
                                  "description" => "Single Appointment based Service Request to Book Payment" 
                                ]); 
                          
                            }else{
                                $payment = Stripe\Charge::create ([
                                  "amount" => $amountToPay,
                                  "currency" => "usd",
                                  "source" => $request->stripeToken,
                                  "description" => "Single Appointment based Service Request to Book Payment" 
                                ]);
                            }
                      } catch(\Stripe\Exception\CardException $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      } catch (\Stripe\Exception\RateLimitException $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      } catch (\Stripe\Exception\InvalidRequestException $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      } catch (\Stripe\Exception\AuthenticationException $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      } catch (\Stripe\Exception\ApiConnectionException $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      } catch (\Stripe\Exception\ApiErrorException $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      } catch (Exception $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      }
                      if($payment->paid){
                        $order->amount = $amountToPay;  
                        $order->stripe_payment_id = $payment->id;
                        $order->json_response = $request->paymentIntentJson;  
                        $authUser = Auth::guard('front_auth')->user();
                        $authUser->referral_wallet = $walletAmount;
                        $authUser->save();
                      }else{
                        Session::flash ( 'fail-message', "Error! Please Try again." );
                        return redirect()->back();
                      }

            } else if($getServiceDetails->format == 'In person - Group Appointment' || $getServiceDetails->format == 'Virtual - Group Appointment'){

                if($getServiceDetails->promo_code == 1){
                    if($getCouponDetails->status == 1){
                        $promocode = $getCouponDetails->percentage+$adminFeesPercent;
                        $total_discount = ($promocode/100)*$getServiceDetails->price;
                        $amountToPay = $getServiceDetails->price - $total_discount;
                        $adminFees = ($adminFeesPercent/100)*$getServiceDetails->price;
                    } else {
                        $adminFees = ($adminFeesPercent/100)*$getServiceDetails->price;
                        $amountToPay = $getServiceDetails->price - $adminFees;
                        $total_discount = 0;
                    }
                    
                } else {
                    $adminFees = ($adminFeesPercent/100)*$getServiceDetails->price;
                    $amountToPay = $getServiceDetails->price - $adminFees;
                    $total_discount = 0;
                }

                    $order = new Orders();
                    $order->user_id = $requestData["user_id"];
                    $order->first_name = $requestData["first_name"];
                    $order->last_name = $requestData["last_name"];
                    $order->phone = $requestData["phone_number"];
                    $order->address = $requestData["address"];
                    $order->apartment_no = $requestData["apt_no"];
                    $order->state = $requestData["state"];
                    $order->country = $requestData["country"];
                    $order->city = $requestData["city"];
                    $order->zip_code = $requestData["zip_code"];
                    $order->type = "order";
                    $order->service_id = $requestData["service"];
                    $order->trainer_id = $requestData["trainerid"];
                   // $order->service_date = $requestData["dates"];
                    //$order->start_date = $startDate;
                    $order->plan_type =  $requestData["plan_type"];
                    $order->service_time = $requestData["time_slot"];
                    $order->status = 1;
                    $order->ref_discount = $total_discount;
                    $order->admin_fees = $adminFees;

                    Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));  

                    $TrainerServicesdata = StripeAccounts::where('user_id',$requestData["trainerid"])->first();
                    
                    try {
                            if(!empty($TrainerServicesdata)){
                              $payment = Stripe\Charge::create ([
                                  "amount" => $amountToPay,
                                  "currency" => "usd",
                                  "source" => $request->stripeToken,
                                  'transfer_group' => 'ORDER10',
                                  "description" => "Group Appointment based Service Request to Book Payment" 
                                ]); 
                          
                            }else{
                                $payment = Stripe\Charge::create ([
                                  "amount" => $amountToPay,
                                  "currency" => "usd",
                                  "source" => $request->stripeToken,
                                  'transfer_group' => 'ORDER10',
                                  "description" => "Group Appointment based Service Request to Book Payment"
                                ]);
                            }
dd($payment);exit();

                      } catch(\Stripe\Exception\CardException $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      } catch (\Stripe\Exception\RateLimitException $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      } catch (\Stripe\Exception\InvalidRequestException $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      } catch (\Stripe\Exception\AuthenticationException $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      } catch (\Stripe\Exception\ApiConnectionException $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      } catch (\Stripe\Exception\ApiErrorException $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      } catch (Exception $e) {
                        Session::flash('error', $e->getError()->message);
                        return redirect()->back()->withInput($request->all());
                      }
                      if($payment->paid){
                        $order->amount = $amountToPay;  
                        $order->stripe_payment_id = $payment->id;
                        $order->json_response = $request->paymentIntentJson;  
                        $authUser = Auth::guard('front_auth')->user();
                        $authUser->referral_wallet = $walletAmount;
                        $authUser->save();
                      }else{
                        Session::flash ( 'fail-message', "Error! Please Try again." );
                        //return redirect()->back();
                      }

                    } else if($getServiceDetails->format == 'In person - Monthly Membership' || $getServiceDetails->format == 'Virtual - Monthly Membership'){

                if($getServiceDetails->promo_code == 1){
                    if($getCouponDetails->status == 1){
                        $promocode = $getCouponDetails->percentage+$adminFeesPercent;echo '---';
                        $total_discount = ($promocode/100)*$getServiceDetails->price;
                        $amountToPay = $getServiceDetails->price - $total_discount;
                        $adminFees = ($adminFeesPercent/100)*$getServiceDetails->price;
                    } else {
                        $adminFees = ($adminFeesPercent/100)*$getServiceDetails->price;
                        $amountToPay = $getServiceDetails->price - $adminFees;
                        $total_discount = 0;
                    }
                    
                } else {
                    $adminFees = ($adminFeesPercent/100)*$getServiceDetails->price;
                    $amountToPay = $getServiceDetails->price - $adminFees;
                    $total_discount = 0;
                }

                    $startDate = strtotime($requestData["start_date"]);
                    $startDate = date('Y-m-d',$startDate); 

                    $order = new Orders();
                    $order->user_id = $requestData["user_id"];
                    $order->first_name = $requestData["first_name"];
                    $order->last_name = $requestData["last_name"];
                    $order->phone = $requestData["phone_number"];
                    $order->address = $requestData["address"];
                    $order->apartment_no = $requestData["apt_no"];
                    $order->state = $requestData["state"];
                    $order->country = $requestData["country"];
                    $order->city = $requestData["city"];
                    $order->zip_code = $requestData["zip_code"];
                    $order->type = "order";
                    $order->service_id = $requestData["service"];
                    $order->trainer_id = $requestData["trainerid"];
                   // $order->service_date = $requestData["dates"];
                    $order->start_date = $startDate;
                    $order->plan_type =  $requestData["plan_type"];
                    //$order->service_time = $requestData["time_slot"];
                    $order->status = 1;
                    $order->ref_discount = $total_discount;
                    $order->admin_fees = $adminFees;

                    Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));  

                    $TrainerServicesdata = StripeAccounts::where('user_id',$requestData["trainerid"])->first();
                      if(empty($TrainerServicesdata)){
                        $stripeCustomer = \Stripe\Customer::create([
                          'email' => $requestData["email"],
                          "source" => $request->stripeToken,
                        ]);
                      }else{
                         $stripeCustomer = \Stripe\Customer::create([
                            'email' => $requestData["email"],
                            "source" => $request->stripeToken,
                          ], ['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                          //dd($stripeCustomer);
                      }
                    
                      $stripeCustomerId = $stripeCustomer->id;

                      //fetch plan
                      $plans = TrainerServices::where('id', $requestData["service"])->first();
                      $planId = 'plan_JMv9W8z0ydmjU6';
                      $order->plan_type = "monthly";
                      //create discount
                      if($total_discount > 0){
                        $discount = \Stripe\Coupon::create([
                          'duration' => 'once',
                          'amount_off' => $total_discount*100,
                          'currency' => 'usd'
                        ],['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                        $subscriptionParam = ['customer' => $stripeCustomerId,
                        'items' => [['plan' => $planId]],
                        "application_fee_percent" => 5,
                        'coupon' => $discount->id,
                        ];
                      }else{
                        $subscriptionParam = ['customer' => $stripeCustomerId,
                        'items' => [['plan' => $planId]],
                        "application_fee_percent" => 5,
                        ];
                      }
                      //create subscription
                    
                      $date = new \Carbon\Carbon;
                      if($startDate > $date){
                        $timestamp = \Carbon\Carbon::parse($startDate)->timestamp;
                        $subscriptionParam["trial_end"] = $timestamp;
                      }
                      
                      try {
                        if(empty($TrainerServicesdata)){
                            $subscription = \Stripe\Subscription::create($subscriptionParam);
                        }else{
                            $subscription = \Stripe\Subscription::create($subscriptionParam,['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                        }
                        dd($subscriptionParam);exit();
                        } catch(\Stripe\Exception\CardException $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        } catch (\Stripe\Exception\RateLimitException $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        } catch (\Stripe\Exception\InvalidRequestException $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        } catch (\Stripe\Exception\AuthenticationException $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        } catch (\Stripe\Exception\ApiConnectionException $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        } catch (\Stripe\Exception\ApiErrorException $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        } catch (Exception $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        }
                        if($subscription){

                          $order->stripe_subscription_id = $subscription->id; 
                          $order->subscription_status = $subscription->status;
                          $order->admin_fees = $adminFeesPercent;
                          $order->json_response = json_encode($subscription);
                        }else{
                          Session::flash ( 'fail-message', "Error! Something went wrong." );
                          return redirect()->back();
                        }

                      } else if($getServiceDetails->format == 'In person - Yearly Membership' || $getServiceDetails->format == 'Virtual - Yearly Membership'){
                if($getServiceDetails->promo_code == 1){
                    if($getCouponDetails->status == 1){
                        $promocode = $getCouponDetails->percentage+$adminFeesPercent;
                        $total_discount = ($promocode/100)*$getServiceDetails->price;
                        $amountToPay = $getServiceDetails->price - $total_discount;
                        $adminFees = ($adminFeesPercent/100)*$getServiceDetails->price;
                    } else {
                        $adminFees = ($adminFeesPercent/100)*$getServiceDetails->price;
                        $amountToPay = $getServiceDetails->price - $adminFees;
                        $total_discount = 0;
                    }
                    
                } else {
                    $adminFees = ($adminFeesPercent/100)*$getServiceDetails->price;
                    $amountToPay = $getServiceDetails->price - $adminFees;
                    $total_discount = 0;
                }

                    $startDate = strtotime($requestData["start_date"]);
                    $startDate = date('Y-m-d',$startDate); 

                    $order = new Orders();
                    $order->user_id = $requestData["user_id"];
                    $order->first_name = $requestData["first_name"];
                    $order->last_name = $requestData["last_name"];
                    $order->phone = $requestData["phone_number"];
                    $order->address = $requestData["address"];
                    $order->apartment_no = $requestData["apt_no"];
                    $order->state = $requestData["state"];
                    $order->country = $requestData["country"];
                    $order->city = $requestData["city"];
                    $order->zip_code = $requestData["zip_code"];
                    $order->type = "order";
                    $order->service_id = $requestData["service"];
                    $order->trainer_id = $requestData["trainerid"];
                   // $order->service_date = $requestData["dates"];
                    $order->start_date = $startDate;
                    $order->plan_type =  $requestData["plan_type"];
                    //$order->service_time = $requestData["time_slot"];
                    $order->status = 1;
                    $order->ref_discount = $total_discount;
                    $order->admin_fees = $adminFees;

                    Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));  

                    $TrainerServicesdata = StripeAccounts::where('user_id',$requestData["trainerid"])->first();
                      if(empty($TrainerServicesdata)){
                        $stripeCustomer = \Stripe\Customer::create([
                          'email' => $requestData["email"],
                          "source" => $request->stripeToken,
                        ]);
                      }else{
                         $stripeCustomer = \Stripe\Customer::create([
                            'email' => $requestData["email"],
                            "source" => $request->stripeToken,
                          ], ['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                          //dd($stripeCustomer);
                      }
                   
                      $stripeCustomerId = $stripeCustomer->id;

                      //fetch plan
                      $plans = TrainerServices::where('id', $requestData["service"])->first();
                      $planId = $plans->monthly_plan_id;
                      $order->plan_type = "yearly";
                      //create discount
                      if($total_discount > 0){
                        $discount = \Stripe\Coupon::create([
                          'duration' => 'once',
                          'amount_off' => $total_discount*100,
                          'currency' => 'usd'
                        ],['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                        $subscriptionParam = ['customer' => $stripeCustomerId,
                        'items' => [['plan' => $planId]],
                        "application_fee_percent" => 5,
                        'coupon' => $discount->id,
                        ];
                      }else{
                        $subscriptionParam = ['customer' => $stripeCustomerId,
                        'items' => [['plan' => $planId]],
                        "application_fee_percent" => 5,
                        ];
                      }
                      //create subscription
                    
                      $date = new \Carbon\Carbon;
                      if($startDate > $date){
                        $timestamp = \Carbon\Carbon::parse($startDate)->timestamp;
                        $subscriptionParam["trial_end"] = $timestamp;
                      }
                      
                      try {
                        if(empty($TrainerServicesdata)){
                            $subscription = \Stripe\Subscription::create($subscriptionParam);
                        }else{
                            $subscription = \Stripe\Subscription::create($subscriptionParam,['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                        }
                        } catch(\Stripe\Exception\CardException $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        } catch (\Stripe\Exception\RateLimitException $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        } catch (\Stripe\Exception\InvalidRequestException $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        } catch (\Stripe\Exception\AuthenticationException $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        } catch (\Stripe\Exception\ApiConnectionException $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        } catch (\Stripe\Exception\ApiErrorException $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        } catch (Exception $e) {
                          Session::flash('error', $e->getError()->message);
                          return redirect()->back()->withInput($request->all());
                        }
                        if($subscription){
                          $order->stripe_subscription_id = $subscription->id; 
                          $order->subscription_status = $subscription->status;
                          $order->admin_fees = $adminFeesPercent;
                          $order->json_response = json_encode($subscription);
                        }else{
                          Session::flash ( 'fail-message', "Error! Something went wrong." );
                          return redirect()->back();
                        }
            }
            
            
            
                if($order->save()){
                 // $authUser = Auth::guard('front_auth')->user();
                  //$authUser->referral_wallet = $walletAmount;
                  //$authUser->save();
                  //$orderData = Orders::where('id', $order->id)->with(['service' , 'trainer','Users'])->first();
                  //$toEmails = $orderData->Users->email; 
                  //$trainerEmail = $orderData->trainer->email;
                //   try { 
                //     Mail::to($toEmails)->send(new OrderConfirmNotify($orderData)); 
                //     Mail::to($trainerEmail)->send(new TrainerNotify($orderData)); 
                //  } catch (Exception $exc) {
                //      echo $exc->getTraceAsString();
                //  }

                  Session::flash('message', 'Service has been booked.');
                 // return redirect()->back(); 
                  return redirect()->intended(route('customer.order.history'));
                  
              }


         }
    }

    public function cancel_order($orderid, $paymentid){
        
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $orderId = base64_decode($orderid);
        $paymentId = base64_decode($paymentid);
        $order = Orders::find($orderId);
        $TrainerServicesdata = StripeAccounts::where('user_id',$order->trainer_id)->first();
        if($paymentId){
          // $fees_to_be_deducted = (13/100)*$order->amount;
          $order->admin_fees = 0;
          $amount_to_be_refunded = $order->amount - $order->admin_fees;
          $refund = \Stripe\Refund::create([
              'payment_intent' => $paymentId,
              'amount' => $amount_to_be_refunded * 100,
              'description' => 'Service Payment Refund.',
          ], ['stripe_account' => $TrainerServicesdata->stripe_user_id]);
          // $intent = \Stripe\PaymentIntent::retrieve($paymentId, ['stripe_account' => $TrainerServicesdata->stripe_user_id]);
          // $intent->cancel();
          
          $order->stripe_refund_id = $refund->id;
          $order->refund_amount = $amount_to_be_refunded;  
          $order->order_status = "cancelled";
          Session::flash('message', 'Order cancelled successfully.');
        }
      
        if($order->save()){
          return redirect()->back();
        }
        
    }

    public function cancel_subscription($orderid, $subscriptionid){ //dd("hello");
      \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
      $orderId = base64_decode($orderid);
      $subscriptionId = base64_decode($subscriptionid);
      $order = Orders::find($orderId);
      $TrainerServicesdata = StripeAccounts::where('user_id',$order->trainer_id)->first();
      //dd($TrainerServicesdata->stripe_user_id);
      if($subscriptionId){
        $subscription = \Stripe\Subscription::retrieve(
          $subscriptionId, ['stripe_account' => $TrainerServicesdata->stripe_user_id]
        );
        $subscription->delete();
        $order->subscription_status = $subscription->status;  
        $order->order_status = "cancelled";
      }
      if($order->save()){
        Session::flash('message', 'Subscription cancelled successfully.');
        return redirect()->back();
      }
    }
    
    public function stripeconnect() { 
        return view('front.stripeconnect', ["booknow" => true]);
    }
    function getstripeurl() {
        $user = Auth::guard('front_auth')->user();
        //return 'https://connect.stripe.com/express/oauth/authorize?client_id=ca_HUM5mICK2x5v6NegKgBimnPjoGulLc0Y=traingblock&scope=read_write&response_type=code&stripe_user[email]='.$user->email.'&stripe_user[url]=https://trainingblockusa.com&stripe_user[country]=US&redirect_uri=https://trainingblockusa.com/stripeconnect/store';
        //return 'https://connect.stripe.com/express/oauth/authorize?response_type=code&client_id=ca_HUM5mICK2x5v6NegKgBimnPjoGulLc0Y';
        return 'https://connect.stripe.com/express/oauth/authorize?response_type=code&client_id=ca_HUM53Qoy5cg2zOdag9lReSzlMXm82EQq';
        //
        //
        
    }
    
    function stripestore(Request $request) {
        $user = Auth::guard('front_auth')->user();

        $rerdata = $request->all(); 
        //dd($rerdata);exit();
        $user = Auth::guard('front_auth')->user();
  $services = TrainerServices::where("trainer_id", $user->id)->get();
             if (isset($rerdata['code'])) {
                $dbdata = array();
                $code = $rerdata['code'];
                //$code = 'ac_HMNFw2mTi5tfPen14gOxvccGM96QhNQl';
                $client_secret = env('STRIPE_SECRET_KEY');
                Stripe\Stripe::setApiKey($client_secret); 
                //$tokendata = GetToken($url, $client_secret, $code);
                $tokendata = \Stripe\OAuth::token([
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                  ]);
                if (!empty($tokendata) && isset($tokendata->access_token)) {
                    $dbdata['user_id'] = $user->id;
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
          foreach($services as $service){
                        $product = \Stripe\Product::create([
                          'name' => $service->name,
                          'type' => 'service',
                        ],['stripe_account' => $tokendata->stripe_user_id]);
                        $service->product_id = $product->id;
                        /*if($service->is_recurring == "yes"){
                          $monthly_price =  \Stripe\Plan::create([
                            'amount' => $service->price_monthly*100,
                            'currency' => 'usd',
                            'interval' => 'month',
                            'product' => $product->id,
                          ],['stripe_account' => $tokendata->stripe_user_id]);
                          $service->monthly_plan_id = $monthly_price->id;
                        }*/
                        $service->save();
                    }
                    $msg = "You have successfully connected your stripe account.";
                    return Redirect::route('services.list')->with('message', $msg);
                }else{
                    $msg = "Please try again!";
                    return Redirect::route('services.list')->with('error', $msg);
                }
            }
        }
        
        function createpaymentintent(Request $request) {
            
               $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); 
              
               $response = array();
              
           
                    if(isset($request->stripe_user_id)){
                   
                    $user = Auth::guard('front_auth')->user();
                    
                    $adminFeesPercent = getSetting('admin-fees');

                    
                    if($request->discounted_Price == ''){
                      $service_amounts = $request->service_amount;
                    } else {
                      $service_amounts = $request->discounted_Price;
                    }
                    $adminFees = ($adminFeesPercent/100)*$service_amounts;
                    $amountToPay = $service_amounts - $adminFees;
                    $adminFees = ($adminFeesPercent/100)*$service_amounts;
                    


                    $payment_intent = \Stripe\PaymentIntent::create([
                      'payment_method_types' => ['card'],
                      'amount' => $amountToPay*100,
                      'currency' => 'usd',
                      'application_fee_amount' => $adminFees*100,
                    ], ['stripe_account' => $request->stripe_user_id]);

                    
                   $response = ['status' => true, "Message" => 'Record deleted successfuly','client_secret' => $payment_intent->client_secret];
                   //echo '<pre>';print_r($payment_intent);exit();
        }
        //
        return $response;
        }

        function createmonthlyyearlypaymentintent(Request $request) {
            
               $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); 
               $response = array();
               
                if(isset($request->stripe_user_id)){
               
                $user = Auth::guard('front_auth')->user();
                $adminFeesPercent = getSetting('admin-fees');

                
                if($request->discounted_Price == ''){
                      $service_amounts = $request->service_amount;
                    } else {
                      $service_amounts = $request->discounted_Price;
                    }
                    $adminFees = ($adminFeesPercent/100)*$service_amounts;
                    $amountToPay = $service_amounts - $adminFees;
                    $adminFees = ($adminFeesPercent/100)*$service_amounts;
               
                $payment_intent = \Stripe\PaymentIntent::create([
                      'payment_method_types' => ['card'],
                      'amount' => $amountToPay*100,
                      'currency' => 'usd',
                      'application_fee_amount' => $adminFees*100,
                      'description' => 'Monthly/Yearly Service Booking Payment',
                    ], ['stripe_account' => $request->stripe_user_id]);
               
               $response = ['status' => true, "Message" => 'Record deleted successfuly','client_secret' => $payment_intent->client_secret];
        }
        return $response;
        }

        function createpackagedealpaymentintent(Request $request) {
            
          $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); 
          $response = array();
          
           if(isset($request->stripe_user_id)){
          
           $user = Auth::guard('front_auth')->user();
           $adminFeesPercent = getSetting('admin-fees');

           
           if($request->discounted_Price == ''){
                 $service_amounts = $request->service_amount;
               } else {
                 $service_amounts = $request->discounted_Price;
               }
               $adminFees = ($adminFeesPercent/100)*$service_amounts;
               $amountToPay = $service_amounts - $adminFees;
               $adminFees = ($adminFeesPercent/100)*$service_amounts;
          
           $payment_intent = \Stripe\PaymentIntent::create([
                 'payment_method_types' => ['card'],
                 'amount' => $amountToPay*100,
                 'currency' => 'usd',
                 'application_fee_amount' => $adminFees*100,
                 'description' => 'Package Deal Service Booking Payment',
               ], ['stripe_account' => $request->stripe_user_id]);
          
          $response = ['status' => true, "Message" => 'Record deleted successfuly','client_secret' => $payment_intent->client_secret];
   }
   return $response;
   }
        function createpaymentsave(Request $request) {
            parse_str($request->form_data, $searcharray);
             $requestData = $searcharray;
             
        $errors = Validator::make($requestData, [
                    'user_id' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'phone_number' => 'required',
                    //'address' => 'required',
                    //'state' => 'required',
                    //'country' => 'required',
                    //'city' => 'required',
                    //'zip_code' => 'required',
                    'service' => 'required',
                    //'start_date' => 'required',
                    'time_slot' => 'required',
                    'service_amount' => 'required',
                   // 'recurring_options' => 'required_with:recurring'
        ]);
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
           
              $user = Auth::guard('front_auth')->user();
              
              $adminFeesPercent = getSetting('admin-fees');
              if(isset($requestData["discounted_price"])){
              if($requestData["discounted_price"] == ''){
                  $service_amounts = $requestData["service_amount"];
                } else {
                  $service_amounts = $requestData["discounted_price"];
                }
              } else {
                $service_amounts = $requestData["service_amount"];
              }
                $adminFees = ($adminFeesPercent/100)*$service_amounts;
                $amountToPay = $service_amounts - $adminFees;
                $adminFees = ($adminFeesPercent/100)*$service_amounts;
            
                $appointment_date_str=explode('-',$requestData['appointment_date']);

                $appointment_date = $appointment_date_str[2].'-'.$appointment_date_str[0].'-'.$appointment_date_str[1];

                $order = new Orders();
                $order->user_id = $requestData["user_id"];
                $order->first_name = $requestData["first_name"];
                $order->last_name = $requestData["last_name"];
                $order->phone = $requestData["phone_number"];
                $order->address = $requestData["address"];
                //$order->apartment_no = $requestData["apt_no"];
                $order->state = $requestData["state"];
                //$order->country = $requestData["country"];
                $order->city = $requestData["city"];
                $order->zip_code = $requestData["zip_code"];
                $order->type = "order";
                $order->days = $requestData["week_days"];
                $order->service_id = $requestData["service"];
                $order->trainer_id = $requestData["trainerid"];
               // $order->service_date = $requestData["dates"];
                //$order->start_date = $startDate;
                //$order->end_date =  $endDate;
                $order->plan_type = $requestData["plan_type"];
                $order->service_time = $requestData["time_slot"];
                $order->appointment_date = $appointment_date;
                $order->event_id = $requestData["event_id"];
                $order->status = 1;
                $order->ref_discount = $adminFees;
                $order->admin_fees = $adminFees;
                
                if(isset($request->paymentIntent)){
                 // dd($request->paymentIntent);exit;
                    $order->amount = $amountToPay;  
                 $order->stripe_payment_id = $request->paymentIntent['id'];
                 $order->json_response = $request->paymentIntentJson;  
                }
                
            
            if($order->save()){
                   
             
              $orderData = Orders::where('id', $order->id)->with(['service' , 'trainer','Users'])->first();
              $toEmails = $orderData->Users->email; 
              $trainerEmail = $orderData->trainer->email;
            
            
            $services = TrainerServices::where('id', $requestData["service"])->first();
            $trainer = FrontUsers::where(["id" => $services->trainer_id])->first();
            //echo '<pre>';print_r($services->trainer_id);exit();

            $mail_athelete = new PHPMailer;

            
            //Server settings                   // Enable verbose debug output
            $mail_athelete->IsSMTP();
            $mail_athelete->SMTPAuth = true;
            $mail_athelete->SMTPSecure = env('MAIL_SECURE');
            $mail_athelete->Host = env('MAIL_HOST');
            $mail_athelete->Port = env('MAIL_PORT');
            $mail_athelete->Username = env('MAIL_USERNAME');
            $mail_athelete->Password = env('MAIL_PASSWORD');
            $mail_athelete->SetFrom(env('MAIL_FROM'), 'Training Block');
            $mail_athelete->Subject = "Booking Confirmation";
            $mail_athelete->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
        <tbody><tr style="
            background: #555;
        ">
        <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
        </tr>
        <tr>
            <td style="padding-top:20px;"> <h4>Dear '.$requestData["first_name"].' '.$requestData["last_name"].',</h4> </td>
        </tr>

        <tr>
            <td style="padding-bottom:15px;"> 
                <p>Congratulations! You have booked an appointment for '.$services->name.' with '.$trainer->business_name.', on '.date("F j, Y").' at '.date("g:i a").'.  We hope this appointment brings you one step closer to your strongest performance yet. Be sure to leave them a review on Training Block! </p>
            </td>
        </tr>
        <tr>
        <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
        <p></p>
        <span style="margin-top:5px;">The Training Block Team</span>
        </td>
        </tr>

        </tbody></table></body></html>');
            $mail_athelete->AddAddress($toEmails, 'Training Block');

            if (!$mail_athelete->send()) {
                //echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                //echo 'Message sent!';
                $mail_provider = new PHPMailer;
                $mail_provider->IsSMTP();
                $mail_provider->SMTPAuth = true;
                $mail_provider->SMTPSecure = env('MAIL_SECURE');
                $mail_provider->Host = env('MAIL_HOST');
                $mail_provider->Port = env('MAIL_PORT');
                $mail_provider->Username = env('MAIL_USERNAME');
                $mail_provider->Password = env('MAIL_PASSWORD');
                $mail_provider->SetFrom(env('MAIL_FROM'), 'Training Block');
                $mail_provider->Subject = "Booking Confirmation";
                  $mail_provider->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
              <tbody><tr style="
                  background: #555;
              ">
                  <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
              </tr>
              <tr>
                  <td style="padding-top:20px;"> <h4>Dear '.$trainer->first_name.' '.$trainer->last_name.',</h4> </td>
              </tr>

              <tr>
                  <td style="padding-bottom:15px;"> 
                      <p>Congratulations! '.$requestData["first_name"].' '.$requestData["last_name"].' has booked an appointment with you for '.$services->name.' on '.date("F j, Y").' at '.date("g:i a").'.  Please log in to your account to review the details. </p>
                  </td>
              </tr>
              <tr>
              <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
              <p></p>
              <span style="margin-top:5px;">The Training Block Team</span>
              </td>
              </tr>

              </tbody></table></body></html>');
                  $mail_provider->AddAddress($trainerEmail, 'Training Block');

                  if (!$mail_provider->send()) {
                      //echo 'Mailer Error: ' . $mail->ErrorInfo;
                  } else {
                      //echo 'Message sent!';
                  }
            }

            
             
              Session::flash('message', 'Service has been booked.');
             // return redirect()->back(); 
              //return redirect()->intended(route('customer.order.history'));
              
          }


         }
        }

        function createzeropaymentsave(Request $request) {
          $requestData = $request->all();
             //echo '<pre>';print_r($requestData);exit();
        $errors = Validator::make($requestData, [
                    'user_id' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'phone_number' => 'required',
                    'service' => 'required',
                    'time_slot' => 'required',
                    'service_amount' => 'required',
        ]);
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
           
              $user = Auth::guard('front_auth')->user();
              $amountToPay = $requestData["service_amount"];
          
              $appointment_date_str=explode('-',$requestData['appointment_date']);

              $appointment_date = $appointment_date_str[2].'-'.$appointment_date_str[0].'-'.$appointment_date_str[1];

              $order = new Orders();
              $order->user_id = $requestData["user_id"];
              $order->first_name = $requestData["first_name"];
              $order->last_name = $requestData["last_name"];
              $order->phone = $requestData["phone_number"];
              //$order->address = $requestData["address"];
              $order->state = $requestData["state"];
              $order->city = $requestData["city"];
              $order->zip_code = $requestData["zip_code"];
              $order->type = "order";
              $order->days = $requestData["week_days"];
              $order->service_id = $requestData["service"];
              $order->trainer_id = $requestData["trainerid"];
              $order->plan_type = $requestData["plan_type"];
              $order->service_time = $requestData["time_slot"];
              $order->appointment_date = $appointment_date;
              $order->event_id = $requestData["event_id"];
              $order->status = 1;
              $order->amount = $amountToPay;
                
                
            
            if($order->save()){
              $orderData = Orders::where('id', $order->id)->with(['service' , 'trainer','Users'])->first();
              $toEmails = $orderData->Users->email; 
              $trainerEmail = $orderData->trainer->email;
            
            
            $services = TrainerServices::where('id', $requestData["service"])->first();
            $trainer = FrontUsers::where(["id" => $services->trainer_id])->first();
            //echo '<pre>';print_r($services->trainer_id);exit();

            $mail_athelete = new PHPMailer;

            
            //Server settings                   // Enable verbose debug output
            $mail_athelete->IsSMTP();
            $mail_athelete->SMTPAuth = true;
            $mail_athelete->SMTPSecure = env('MAIL_SECURE');
            $mail_athelete->Host = env('MAIL_HOST');
            $mail_athelete->Port = env('MAIL_PORT');
            $mail_athelete->Username = env('MAIL_USERNAME');
            $mail_athelete->Password = env('MAIL_PASSWORD');
            $mail_athelete->SetFrom(env('MAIL_FROM'), 'Training Block');
            $mail_athelete->Subject = "Booking Confirmation";
            $mail_athelete->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
        <tbody><tr style="
            background: #555;
        ">
            <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
        </tr>
        <tr>
            <td style="padding-top:20px;"> <h4>Dear '.$requestData["first_name"].' '.$requestData["last_name"].',</h4> </td>
        </tr>

        <tr>
            <td style="padding-bottom:15px;"> 
                <p>Congratulations! You have booked an appointment for '.$services->name.' with '.$trainer->business_name.', on '.date("F j, Y").' at '.date("g:i a").'.  We hope this appointment brings you one step closer to your strongest performance yet. Be sure to leave them a review on Training Block! </p>
            </td>
        </tr>
        <tr>
        <tr>
        <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
        <p></p>
        <span style="margin-top:5px;">The Training Block Team</span>
        </td>
        </tr>

        </tbody></table></body></html>');
            $mail_athelete->AddAddress($toEmails, 'Training Block');

            if (!$mail_athelete->send()) {
                //echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                //echo 'Message sent!';
                $mail_provider = new PHPMailer;
                $mail_provider->IsSMTP();
                $mail_provider->SMTPAuth = true;
                $mail_provider->SMTPSecure = env('MAIL_SECURE');
                $mail_provider->Host = env('MAIL_HOST');
                $mail_provider->Port = env('MAIL_PORT');
                $mail_provider->Username = env('MAIL_USERNAME');
                $mail_provider->Password = env('MAIL_PASSWORD');
                $mail_provider->SetFrom(env('MAIL_FROM'), 'Training Block');
                $mail_provider->Subject = "Booking Confirmation";
                  $mail_provider->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
              <tbody><tr style="
                  background: #555;
              ">
                  <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
              </tr>
              <tr>
                  <td style="padding-top:20px;"> <h4>Dear '.$trainer->first_name.' '.$trainer->last_name.',</h4> </td>
              </tr>

              <tr>
                  <td style="padding-bottom:15px;"> 
                      <p>Congratulations! '.$requestData["first_name"].' '.$requestData["last_name"].' has booked an appointment with you for '.$services->name.' on '.date("F j, Y").' at '.date("g:i a").'.  Please log in to your account to review the details. </p>
                  </td>
              </tr>
              <tr>
              <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
              <p></p>
              <span style="margin-top:5px;">The Training Block Team</span>
              </td>
              </tr>

              </tbody></table></body></html>');
                  $mail_provider->AddAddress($trainerEmail, 'Training Block');

                  if (!$mail_provider->send()) {
                      //echo 'Mailer Error: ' . $mail->ErrorInfo;
                  } else {
                      //echo 'Message sent!';
                  }
            }

            
             
              Session::flash('message', 'Service has been booked.');
             
              return redirect()->intended(route('customer.order.history'));
              
          }


         }
        }

        function createmonthlyyearlypaymentsave(Request $request) {
            parse_str($request->form_data, $searcharray);
            $requestData = $request->all();
           
        $errors = Validator::make($requestData, [
                    'user_id' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'phone_number' => 'required',
                    //'address' => 'required',
                    //'state' => 'required',
                    //'country' => 'required',
                    //'city' => 'required',
                    //'zip_code' => 'required',
                    'service' => 'required',
                    'start_date' => 'required',
                    //'time_slot' => 'required',
                    'service_amount' => 'required',
                   // 'recurring_options' => 'required_with:recurring'
        ]);
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
          Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); 
              $startDate_str=explode('-',$requestData['start_date']);

              $startDate = $startDate_str[2].'-'.$startDate_str[0].'-'.$startDate_str[1];
              $user = Auth::guard('front_auth')->user();
              
              $adminFeesPercent = getSetting('admin-fees');

              if(isset($requestData["discounted_price"])){
                if($requestData["discounted_price"] == ''){
                    $service_amounts = $requestData["service_amount"];
                    $discounted_price = 0;
                  } else {
                    $service_amounts = $requestData["discounted_price"];
                    $discounted_price = $requestData["discounted_price"];
                  }
                } else {
                  $service_amounts = $requestData["service_amount"];
                  $discounted_price = 0;
                }
           
            $adminFees = ($adminFeesPercent/100)*$service_amounts;
            $amountToPay = $service_amounts - $adminFees;
            $adminFees = ($adminFeesPercent/100)*$service_amounts;
            
           
                $order = new Orders();
                $order->user_id = $requestData["user_id"];
                $order->first_name = $requestData["first_name"];
                $order->last_name = $requestData["last_name"];
                $order->phone = $requestData["phone_number"];
                //$order->address = $requestData["address"];
               // $order->apartment_no = $requestData["apt_no"];
                $order->state = $requestData["state"];
               // $order->country = $requestData["country"];
                $order->city = $requestData["city"];
                $order->zip_code = $requestData["zip_code"];
                $order->type = "order";
                $order->service_id = $requestData["service"];
                $order->trainer_id = $requestData["trainerid"];
                $order->plan_type = $requestData["plan_type"];
               // $order->service_date = $requestData["dates"];
                $order->start_date = $startDate;
                //$order->end_date =  $endDate;
                //$order->service_time = $requestData["time_slot"];
                $order->status = 1;
                $order->ref_discount = $adminFees;
                $order->admin_fees = $adminFees;
                $order->amount = $amountToPay;  
                if(isset($request->paymentIntent)){
                 // dd($request->paymentIntent);exit;
                    //$order->amount = $amountToPay;  
                 $order->stripe_payment_id = $request->paymentIntent['id'];
                // $order->json_response = $request->paymentIntentJson;  
                }
            
                
              
                  $TrainerServicesdata = StripeAccounts::where('user_id',$requestData["trainerid"])->first();
                  if(empty($TrainerServicesdata)){
                    $stripeCustomer = \Stripe\Customer::create([
                      'email' => $requestData["email"],
                      "source" => $request->stripeToken,
                    ]);
                  }else{
                    $stripeCustomer = \Stripe\Customer::create([
                        'email' => $requestData["email"],
                        "source" => $request->stripeToken,
                      ], ['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                      //
                  }
                 // dd($stripeCustomer);exit();
                  $stripeCustomerId = $stripeCustomer->id;

                  //fetch plan
                  $plans = TrainerServices::where('id', $requestData["service"])->first();
                  if($requestData['plan_type'] == 'In person - Monthly Membership' || $requestData['plan_type'] == 'Virtual - Monthly Membership'){
                  $planId = $plans->monthly_plan_id;
                  } else {
                    $planId = $plans->yearly_plan_id;
                  }
                  //$order->plan_type = "monthly";
                  //create discount
                  if($discounted_price > 0){
                    $discount = \Stripe\Coupon::create([
                      'duration' => 'once',
                      'amount_off' => $discounted_price*100,
                      'currency' => 'usd'
                    ],['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                    $subscriptionParam = ['customer' => $stripeCustomerId,
                    'items' => [['plan' => $planId]],
                    "application_fee_percent" => 5,
                    'coupon' => $discount->id,
                    ];
                  }else{
                    $subscriptionParam = ['customer' => $stripeCustomerId,
                    'items' => [['plan' => $planId]],
                    "application_fee_percent" => 5,
                    ];
                  }
                  //create subscription
                  
                  $date = new \Carbon\Carbon;
                  if($startDate > $date){
                    $timestamp = \Carbon\Carbon::parse($startDate)->timestamp;
                    $subscriptionParam["trial_end"] = $timestamp;
                  }
                  
                  try {
                    if(empty($TrainerServicesdata)){
                        $subscription = \Stripe\Subscription::create($subscriptionParam);
                        
                    }else{
                        $subscription = \Stripe\Subscription::create($subscriptionParam,['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                       
                    }
                    } catch(\Stripe\Exception\CardException $e) {
                      //echo '<pre>';print_r($e->getError()->message);exit();
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    } catch (\Stripe\Exception\RateLimitException $e) {
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    } catch (\Stripe\Exception\InvalidRequestException $e) {
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    } catch (\Stripe\Exception\AuthenticationException $e) {
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    } catch (\Stripe\Exception\ApiConnectionException $e) {
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    } catch (\Stripe\Exception\ApiErrorException $e) {
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    } catch (Exception $e) {
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    }
                    
                    if($subscription){
                      $order->stripe_subscription_id = $subscription->id; 
                      $order->subscription_status = $subscription->status;
                      //$order->admin_fees = $adminFees;
                      $order->json_response = json_encode($subscription);
                    }else{
                      Session::flash ( 'fail-message', "Error! Something went wrong." );
                      return redirect()->back();
                    }
              
            
            if($order->save()){
                   
             
              $orderData = Orders::where('id', $order->id)->with(['service' , 'trainer','Users'])->first();
              $toEmails = $orderData->Users->email; 
              $trainerEmail = $orderData->trainer->email;
            
            $services = TrainerServices::where('id', $requestData["service"])->first();
            $trainer = FrontUsers::where(["id" => $services->trainer_id])->first();
            $mail_athelete = new PHPMailer;
            $mail_athelete->IsSMTP();
            $mail_athelete->SMTPAuth = true;
            $mail_athelete->SMTPSecure = env('MAIL_SECURE');
            $mail_athelete->Host = env('MAIL_HOST');
            $mail_athelete->Port = env('MAIL_PORT');
            $mail_athelete->Username = env('MAIL_USERNAME');
            $mail_athelete->Password = env('MAIL_PASSWORD');
            $mail_athelete->SetFrom(env('MAIL_FROM'), 'Training Block');
            $mail_athelete->Subject = "Booking Confirmation";
            $mail_athelete->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
        <tbody><tr style="
            background: #555;
        ">
            <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
        </tr>
        <tr>
            <td style="padding-top:20px;"> <h4>Dear '.$requestData["first_name"].' '.$requestData["last_name"].',</h4> </td>
        </tr>

        <tr>
            <td style="padding-bottom:15px;"> 
                <p>Congratulations! You have purchased '.$services->name.' with '.$trainer->business_name.'. We hope this service brings you one step closer to your strongest performance yet. Happy training! </p>
            </td>
        </tr>
        <tr>
        <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
        <p></p>
        <span style="margin-top:5px;">The Training Block Team</span>
        </td>
        </tr>

        </tbody></table></body></html>');
            $mail_athelete->AddAddress($toEmails, 'Training Block');

            if (!$mail_athelete->send()) {
                //echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                //echo 'Message sent!';
                //
                $mail_provider = new PHPMailer;

                $mail_provider->IsSMTP();
                $mail_provider->SMTPAuth = true;
                $mail_provider->SMTPSecure = env('MAIL_SECURE');
                $mail_provider->Host = env('MAIL_HOST');
                $mail_provider->Port = env('MAIL_PORT');
                $mail_provider->Username = env('MAIL_USERNAME');
                $mail_provider->Password = env('MAIL_PASSWORD');
                $mail_provider->SetFrom(env('MAIL_FROM'), 'Training Block');
                $mail_provider->Subject = "Booking Confirmation";
                  $mail_provider->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
              <tbody><tr style="
                  background: #555;
              ">
                  <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
              </tr>
              <tr>
                  <td style="padding-top:20px;"> <h4>Dear '.$trainer->first_name.' '.$trainer->last_name.',</h4> </td>
              </tr>

              <tr>
                  <td style="padding-bottom:15px;"> 
                      <p>Congratulations! '.$requestData["first_name"].' '.$requestData["last_name"].' has purchased your '.$services->name.'.  Please log in to your account to review the details. </p>
                  </td>
              </tr>
              <tr>
              <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
              <p></p>
              <span style="margin-top:5px;">The Training Block Team</span>
              </td>
              </tr>

              </tbody></table></body></html>');
                  $mail_provider->AddAddress($trainerEmail, 'Training Block');

                  if (!$mail_provider->send()) {
                      //echo 'Mailer Error: ' . $mail->ErrorInfo;
                  } else {
                      //echo 'Message sent!';
                  }
            }
            
             
              Session::flash('message', 'Service has been booked.');
             // return redirect()->back(); 
              return redirect()->intended(route('customer.order.history'));
              
          }


         }
        }

        function createpackagedealpaymentsave(Request $request) {
          parse_str($request->form_data, $searcharray);
           $requestData = $searcharray;
           
      $errors = Validator::make($requestData, [
                  'user_id' => 'required',
                  'first_name' => 'required',
                  'last_name' => 'required',
                  'phone_number' => 'required',
                  //'address' => 'required',
                  //'state' => 'required',
                  //'country' => 'required',
                  //'city' => 'required',
                  //'zip_code' => 'required',
                  'service' => 'required',
                  //'start_date' => 'required',
                  //'time_slot' => 'required',
                  'service_amount' => 'required',
                 // 'recurring_options' => 'required_with:recurring'
      ]);
      if ($errors->fails()) {
          return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
      } else {
         
         
            $user = Auth::guard('front_auth')->user();
            
            $adminFeesPercent = getSetting('admin-fees');

          
            if(isset($requestData["discounted_price"])){
              if($requestData["discounted_price"] == ''){
                  $service_amounts = $requestData["service_amount"];
                } else {
                  $service_amounts = $requestData["discounted_price"];
                }
              } else {
                $service_amounts = $requestData["service_amount"];
              }
              $adminFees = ($adminFeesPercent/100)*$service_amounts;
              $amountToPay = $service_amounts - $adminFees;
              $adminFees = ($adminFeesPercent/100)*$service_amounts;
          
          
              $order = new Orders();
              $order->user_id = $requestData["user_id"];
              $order->first_name = $requestData["first_name"];
              $order->last_name = $requestData["last_name"];
              $order->phone = $requestData["phone_number"];
              $order->address = $requestData["address"];
             // $order->apartment_no = $requestData["apt_no"];
              $order->state = $requestData["state"];
             // $order->country = $requestData["country"];
              $order->city = $requestData["city"];
              $order->zip_code = $requestData["zip_code"];
              $order->type = "order";
              $order->service_id = $requestData["service"];
              $order->trainer_id = $requestData["trainerid"];
              $order->plan_type = $requestData["plan_type"];
             // $order->service_date = $requestData["dates"];
              //$order->start_date = $startDate;
              //$order->end_date =  $endDate;
              //$order->service_time = $requestData["time_slot"];
              $order->status = 1;
              $order->ref_discount = $adminFees;
              $order->admin_fees = $adminFees;
              
              if(isset($request->paymentIntent)){
               // dd($request->paymentIntent);exit;
                  $order->amount = $amountToPay;  
               $order->stripe_payment_id = $request->paymentIntent['id'];
               $order->json_response = $request->paymentIntentJson;  
              }
              
          
          if($order->save()){
                 // $authUser = Auth::guard('front_auth')->user();
                 // $authUser->referral_wallet = $walletAmount;
                 // $authUser->save();
           
            $orderData = Orders::where('id', $order->id)->with(['service' , 'trainer','Users'])->first();
            $toEmails = $orderData->Users->email; 
            $trainerEmail = $orderData->trainer->email;
          //   try {
          //     Mail::to($toEmails)->send(new OrderConfirmNotify($orderData)); 
          //     Mail::to($trainerEmail)->send(new TrainerNotify($orderData)); 
          //  } catch (Exception $exc) {
          //      echo $exc->getTraceAsString();
          //  }
          //  
          $services = TrainerServices::where('id', $requestData["service"])->first();
          $trainer = FrontUsers::where(["id" => $services->trainer_id])->first();
          $mail_athelete = new PHPMailer;
          $mail_athelete->IsSMTP();
          $mail_athelete->SMTPAuth = true;
          $mail_athelete->SMTPSecure = env('MAIL_SECURE');
          $mail_athelete->Host = env('MAIL_HOST');
          $mail_athelete->Port = env('MAIL_PORT');
          $mail_athelete->Username = env('MAIL_USERNAME');
          $mail_athelete->Password = env('MAIL_PASSWORD');
          $mail_athelete->SetFrom(env('MAIL_FROM'), 'Training Block');
          $mail_athelete->Subject = "Booking Confirmation";
          $mail_athelete->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
      <tbody><tr style="
          background: #555;
      ">
          <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
      </tr>
      <tr>
          <td style="padding-top:20px;"> <h4>Dear '.$requestData["first_name"].' '.$requestData["last_name"].',</h4> </td>
      </tr>

      <tr>
          <td style="padding-bottom:15px;"> 
              <p>Congratulations! You have purchased '.$services->name.' with '.$trainer->business_name.'. We hope this service brings you one step closer to your strongest performance yet. Happy training! </p>
          </td>
      </tr>
      <tr>
      <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
      <p></p>
      <span style="margin-top:5px;">The Training Block Team</span>
      </td>
      </tr>

      </tbody></table></body></html>');
          $mail_athelete->AddAddress($toEmails, 'Training Block');

          if (!$mail_athelete->send()) {
              //echo 'Mailer Error: ' . $mail->ErrorInfo;
          } else {
              //echo 'Message sent!';
              //
              $mail_provider = new PHPMailer;

              $mail_provider->IsSMTP();
              $mail_provider->SMTPAuth = true;
              $mail_provider->SMTPSecure = env('MAIL_SECURE');
              $mail_provider->Host = env('MAIL_HOST');
              $mail_provider->Port = env('MAIL_PORT');
              $mail_provider->Username = env('MAIL_USERNAME');
              $mail_provider->Password = env('MAIL_PASSWORD');
              $mail_provider->SetFrom(env('MAIL_FROM'), 'Training Block');
              $mail_provider->Subject = "Booking Confirmation";
                $mail_provider->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
            <tbody><tr style="
                background: #555;
            ">
                <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
            </tr>
            <tr>
                <td style="padding-top:20px;"> <h4>Dear '.$trainer->first_name.' '.$trainer->last_name.',</h4> </td>
            </tr>

            <tr>
                <td style="padding-bottom:15px;"> 
                    <p>Congratulations! '.$requestData["first_name"].' '.$requestData["last_name"].' has purchased your '.$services->name.'.  Please log in to your account to review the details. </p>
                </td>
            </tr>
            <tr>
            <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
            <p></p>
            <span style="margin-top:5px;">The Training Block Team</span>
            </td>
            </tr>

            </tbody></table></body></html>');
                $mail_provider->AddAddress($trainerEmail, 'Training Block');

                if (!$mail_provider->send()) {
                    //echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    //echo 'Message sent!';
                }
          }
          
           
            Session::flash('message', 'Service has been booked.');
           // return redirect()->back(); 
            //return redirect()->intended(route('customer.order.history'));
            
        }


       }
      }

      function createpackagedealzeropaymentsave(Request $request) {
       $requestData = $request->all();
           //echo '<pre>';print_r($requestData);exit();
      //dd($requestData);exit();
      $errors = Validator::make($requestData, [
                  'user_id' => 'required',
                  'first_name' => 'required',
                  'last_name' => 'required',
                  'phone_number' => 'required',
                  'service' => 'required',
                  'service_amount' => 'required',
      ]);
      if ($errors->fails()) {
          return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
      } else {
         
         //$startDate = strtotime($requestData["start_date"]);
          //$startDate = date('Y-m-d',$startDate); 
            $user = Auth::guard('front_auth')->user();
            
           $amountToPay = $requestData["service_amount"];
          
              $order = new Orders();
              $order->user_id = $requestData["user_id"];
              $order->first_name = $requestData["first_name"];
              $order->last_name = $requestData["last_name"];
              $order->phone = $requestData["phone_number"];
              //$order->address = $requestData["address"];
              $order->state = $requestData["state"];
              $order->city = $requestData["city"];
              $order->zip_code = $requestData["zip_code"];
              $order->type = "order";
              $order->service_id = $requestData["service"];
              $order->trainer_id = $requestData["trainerid"];
              $order->plan_type = $requestData["plan_type"];
              $order->status = 1;
              $order->amount = $amountToPay; 
              
              
          
          if($order->save()){
                
           
            $orderData = Orders::where('id', $order->id)->with(['service' , 'trainer','Users'])->first();
            $toEmails = $orderData->Users->email; 
            $trainerEmail = $orderData->trainer->email;
         
          $services = TrainerServices::where('id', $requestData["service"])->first();
          $trainer = FrontUsers::where(["id" => $services->trainer_id])->first();
          $mail_athelete = new PHPMailer;
          $mail_athelete->IsSMTP();
          $mail_athelete->SMTPAuth = true;
          $mail_athelete->SMTPSecure = env('MAIL_SECURE');
          $mail_athelete->Host = env('MAIL_HOST');
          $mail_athelete->Port = env('MAIL_PORT');
          $mail_athelete->Username = env('MAIL_USERNAME');
          $mail_athelete->Password = env('MAIL_PASSWORD');
          $mail_athelete->SetFrom(env('MAIL_FROM'), 'Training Block');
          $mail_athelete->Subject = "Booking Confirmation";
          $mail_athelete->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
      <tbody><tr style="
          background: #555;
      ">
          <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
      </tr>
      <tr>
          <td style="padding-top:20px;"> <h4>Dear '.$requestData["first_name"].' '.$requestData["last_name"].',</h4> </td>
      </tr>

      <tr>
          <td style="padding-bottom:15px;"> 
              <p>Congratulations! You have purchased '.$services->name.' with '.$trainer->business_name.'. We hope this service brings you one step closer to your strongest performance yet. Happy training! </p>
          </td>
      </tr>
      <tr>
      <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
      <p></p>
      <span style="margin-top:5px;">The Training Block Team</span>
      </td>
      </tr>

      </tbody></table></body></html>');
          $mail_athelete->AddAddress($toEmails, 'Training Block');

          if (!$mail_athelete->send()) {
              //echo 'Mailer Error: ' . $mail->ErrorInfo;
          } else {
              //echo 'Message sent!';
              //
              $mail_provider = new PHPMailer;

              $mail_provider->IsSMTP();
              $mail_provider->SMTPAuth = true;
              $mail_provider->SMTPSecure = env('MAIL_SECURE');
              $mail_provider->Host = env('MAIL_HOST');
              $mail_provider->Port = env('MAIL_PORT');
              $mail_provider->Username = env('MAIL_USERNAME');
              $mail_provider->Password = env('MAIL_PASSWORD');
              $mail_provider->SetFrom(env('MAIL_FROM'), 'Training Block');
              $mail_provider->Subject = "Booking Confirmation";
                $mail_provider->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
            <tbody><tr style="
                background: #555;
            ">
                <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
            </tr>
            <tr>
                <td style="padding-top:20px;"> <h4>Dear '.$trainer->first_name.' '.$trainer->last_name.',</h4> </td>
            </tr>

            <tr>
                <td style="padding-bottom:15px;"> 
                    <p>Congratulations! '.$requestData["first_name"].' '.$requestData["last_name"].' has purchased your '.$services->name.'.  Please log in to your account to review the details. </p>
                </td>
            </tr>
            <tr>
            <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
            <p></p>
            <span style="margin-top:5px;">The Training Block Team</span>
            </td>
            </tr>

            </tbody></table></body></html>');
                $mail_provider->AddAddress($trainerEmail, 'Training Block');

                if (!$mail_provider->send()) {
                    //echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    //echo 'Message sent!';
                }
          }
          
           
            Session::flash('message', 'Service has been booked.');
           // return redirect()->back(); 
            return redirect()->intended(route('customer.order.history'));
            
        }


       }
      }
        
      public function getTimeSlots(Request $request){
        //dd($request->service_id, $request->start_date, $request->end_date);
        $service = TrainerServices::find($request->service_id);
        $timeSlots = Config::get('constants.timeSlots');
        
        if(empty($request->end_date)){
          $request->end_date = $request->start_date;
        }
        if($service->is_recurring == "no"){
          $orders = Orders::where(["service_id" => $request->service_id,  ["start_date", "<=", $request->end_date], ["end_date" ,">=" , $request->start_date]])->whereNull('order_status')->pluck("service_time");
          //dd($orders);
        }elseif($service->is_recurring == "yes"){
          $orders = Orders::where(["service_id" => $request->service_id])->whereNull('order_status')->pluck("service_time");
          //dd($orders);
        }
        
        if($service->max_bookings > count($orders)){
          //can do booking for time slot
          $slotOptions = '<option value="">select time slot</option>';
          foreach($timeSlots as $timeSlot){
           $slotOptions .= '<option value="'.$timeSlot.'">'.$timeSlot.'</option>';
          }
        }else{
          //dd($orders);
          //can not do booking
          $slotOptions = '<option value="">select time slot</option>';
          foreach($timeSlots as $timeSlot){
            if ($orders->contains($timeSlot)){
              $slotOptions .= '<option disabled value="'.$timeSlot.'">'.$timeSlot.'</option>';
            }else{
              $slotOptions .= '<option value="'.$timeSlot.'">'.$timeSlot.'</option>';
            }
        
            
          }
        }
        $response = ['status' => true, "data" => $slotOptions];
        return json_encode($response);
       
      }  

      function requestpaymentsave(Request $request){
        parse_str($request->form_data, $searcharray);
         $requestData = $searcharray;
         $requestData = $request->all();
         //echo '<pre>';print_r($request->customer_id);exit();

          $user = Auth::guard('front_auth')->user();
          
          $adminFeesPercent = getSetting('admin-fees');
          if(isset($requestData["discounted_price"])){
          if($requestData["discounted_price"] == ''){
              $service_amounts = $requestData["service_amount"];
            } else {
              $service_amounts = $requestData["discounted_price"];
            }
          } else {
            $service_amounts = $requestData["service_amount"];
          }
            $adminFees = ($adminFeesPercent/100)*$service_amounts;
            $amountToPay = $service_amounts - $adminFees;
            $adminFees = ($adminFeesPercent/100)*$service_amounts;
            $appointment_date_str=explode('-',$requestData['appointment_date']);

            $appointment_date = $appointment_date_str[2].'-'.$appointment_date_str[0].'-'.$appointment_date_str[1];
        
            $order = new Orders();
            $order->user_id = $requestData["user_id"];
            $order->first_name = $requestData["first_name"];
            $order->last_name = $requestData["last_name"];
            $order->phone = $requestData["phone_number"];
            //$order->address = $requestData["address"];
            $order->state = $requestData["state"];
            $order->city = $requestData["city"];
            $order->zip_code = $requestData["zip_code"];
            $order->type = "request";
            $order->days = $requestData["week_days"];
            $order->service_id = $requestData["service"];
            $order->trainer_id = $requestData["trainerid"];
            $order->plan_type = $requestData["plan_type"];
            $order->service_time = $requestData["time_slot"];
            $order->appointment_date = $appointment_date;
            $order->event_id = $requestData["event_id"];
            $order->status = 4;
            //$order->customer_id = $request->customer_id;
            $order->ref_discount = $adminFees;
            $order->admin_fees = $adminFees;
            $order->amount = $amountToPay;  
            $order->stripeToken = $request->stripeToken;  
           
            
        
        if($order->save()){
          $insertedId = $order->id;     
          $serviceid = $requestData["service"];
          $getServiceDetails = DB::table('trainer_services')->where(["id" => $serviceid])->first();
          $resource_category = DB::table('order_request')->insert([
                  'user_id' => Auth::guard('front_auth')->user()->id,
                  'service_id' => $serviceid,
                  'trainer_id' => $getServiceDetails->trainer_id,
                  'order_id' => $insertedId,
                  'first_name' => Auth::guard('front_auth')->user()->first_name,
                  'last_name' => Auth::guard('front_auth')->user()->last_name,
                  'email_id' => Auth::guard('front_auth')->user()->email,
                  'phone' => $requestData["phone_number"],
                  'reuest_date_time' => date('Y-m-d H:i:s'),
                 // 'customer_id' => $request->customer_id,
                  'stripeToken' => $request->stripeToken,
                  'status' => 1
              ]);
            

              $services = TrainerServices::where('id', $serviceid)->first();
              $trainer = FrontUsers::where(["id" => $getServiceDetails->trainer_id])->first();

              $mail_athelete = new PHPMailer;

              
              //Server settings                   // Enable verbose debug output
              $mail_athelete->IsSMTP();
              $mail_athelete->SMTPAuth = true;
              $mail_athelete->SMTPSecure = env('MAIL_SECURE');
              $mail_athelete->Host = env('MAIL_HOST');
              $mail_athelete->Port = env('MAIL_PORT');
              $mail_athelete->Username = env('MAIL_USERNAME');
              $mail_athelete->Password = env('MAIL_PASSWORD');
              $mail_athelete->SetFrom(env('MAIL_FROM'), 'Training Block');
              $mail_athelete->Subject = "Request to Book";
              $mail_athelete->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
          <tbody><tr style="
              background: #555;
          ">
              <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
          </tr>
          <tr>
              <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
          </tr>

          <tr>
              <td style="padding-bottom:15px;"> 
                  <p>You have requested to book an appointment for '.$services->name.' with '.$trainer->business_name.', on '.date("F j, Y").' at '.date("g:i a").'. You will receive an email confirmation once this provider has reviewed your information and confirmed or denied this request.</p>
              </td>
          </tr>
          <tr>
          <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
          <p></p>
          <span style="margin-top:5px;">The Training Block Team</span>
          </td>
          </tr>

          </tbody></table></body></html>');
              $mail_athelete->AddAddress(Auth::guard('front_auth')->user()->email, 'Training Block');

              if (!$mail_athelete->send()) {
                  //echo 'Mailer Error: ' . $mail->ErrorInfo;
              } else {
                  //echo 'Message sent!';
                  $mail_provider = new PHPMailer;
                  $mail_provider->IsSMTP();
                  $mail_provider->SMTPAuth = true;
                  $mail_provider->SMTPSecure = env('MAIL_SECURE');
                  $mail_provider->Host = env('MAIL_HOST');
                  $mail_provider->Port = env('MAIL_PORT');
                  $mail_provider->Username = env('MAIL_USERNAME');
                  $mail_provider->Password = env('MAIL_PASSWORD');
                  $mail_provider->SetFrom(env('MAIL_FROM'), 'Training Block');
                  $mail_provider->Subject = "Request to Book";
                  $mail_provider->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
              <tbody><tr style="
                  background: #555;
              ">
                  <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
              </tr>
              <tr>
                  <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
              </tr>

              <tr>
                  <td style="padding-bottom:15px;"> 
                      <p> '.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.' has requested to book an appointment with you for '.$services->name.' on '.date("F j, Y").' at '.date("g:i a").'. Please log in to your account to confirm this appointment.</p>
                  </td>
              </tr>
              <tr>
              <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
              <p></p>
              <span style="margin-top:5px;">The Training Block Team</span>
              </td>
              </tr>

              </tbody></table></body></html>');
                  $mail_provider->AddAddress($trainer->email, 'Training Block');

                  if (!$mail_provider->send()) {
                      //echo 'Mailer Error: ' . $mail->ErrorInfo;
                  } else {
                      //echo 'Message sent!';
                  }
              }
         
          Session::flash('message', 'Service has been requested.');
          return redirect()->intended(route('customer.order.history'));
          
        }
      }

      function requestzeropaymentsave(Request $request){
        $requestData = $request->all();
         //echo '<pre>';print_r($request->customer_id);exit();

          $user = Auth::guard('front_auth')->user();
          
           $amountToPay = $requestData["service_amount"];
           
            $appointment_date_str=explode('-',$requestData['appointment_date']);

            $appointment_date = $appointment_date_str[2].'-'.$appointment_date_str[0].'-'.$appointment_date_str[1];
        
            $order = new Orders();
            $order->user_id = $requestData["user_id"];
            $order->first_name = $requestData["first_name"];
            $order->last_name = $requestData["last_name"];
            $order->phone = $requestData["phone_number"];
            $order->state = $requestData["state"];
            $order->city = $requestData["city"];
            $order->zip_code = $requestData["zip_code"];
            $order->type = "request";
            $order->days = $requestData["week_days"];
            $order->service_id = $requestData["service"];
            $order->trainer_id = $requestData["trainerid"];
            $order->plan_type = $requestData["plan_type"];
            $order->service_time = $requestData["time_slot"];
            $order->appointment_date = $appointment_date;
            $order->event_id = $requestData["event_id"];
            $order->status = 4;
            $order->amount = $amountToPay;  
           
            
        
        if($order->save()){
          $insertedId = $order->id;     
          $serviceid = $requestData["service"];
          $getServiceDetails = DB::table('trainer_services')->where(["id" => $serviceid])->first();
          $resource_category = DB::table('order_request')->insert([
                  'user_id' => Auth::guard('front_auth')->user()->id,
                  'service_id' => $serviceid,
                  'trainer_id' => $getServiceDetails->trainer_id,
                  'order_id' => $insertedId,
                  'first_name' => Auth::guard('front_auth')->user()->first_name,
                  'last_name' => Auth::guard('front_auth')->user()->last_name,
                  'email_id' => Auth::guard('front_auth')->user()->email,
                  'phone' => $requestData["phone_number"],
                  'reuest_date_time' => date('Y-m-d H:i:s'),
                  'status' => 1
              ]);
            

              $services = TrainerServices::where('id', $serviceid)->first();
              $trainer = FrontUsers::where(["id" => $getServiceDetails->trainer_id])->first();

              $mail_athelete = new PHPMailer;

              
              //Server settings                   // Enable verbose debug output
              $mail_athelete->IsSMTP();
              $mail_athelete->SMTPAuth = true;
              $mail_athelete->SMTPSecure = env('MAIL_SECURE');
              $mail_athelete->Host = env('MAIL_HOST');
              $mail_athelete->Port = env('MAIL_PORT');
              $mail_athelete->Username = env('MAIL_USERNAME');
              $mail_athelete->Password = env('MAIL_PASSWORD');
              $mail_athelete->SetFrom(env('MAIL_FROM'), 'Training Block');
              $mail_athelete->Subject = "Request to Book";
              $mail_athelete->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
          <tbody><tr style="
              background: #555;
          ">
              <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
          </tr>
          <tr>
              <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
          </tr>

          <tr>
              <td style="padding-bottom:15px;"> 
                  <p>You have requested to book an appointment for '.$services->name.' with '.$trainer->business_name.', on '.date("F j, Y").' at '.date("g:i a").'. You will receive an email confirmation once this provider has reviewed your information and confirmed or denied this request.</p>
              </td>
          </tr>
          <tr>
          <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
          <p></p>
          <span style="margin-top:5px;">The Training Block Team</span>
          </td>
          </tr>

          </tbody></table></body></html>');
              $mail_athelete->AddAddress(Auth::guard('front_auth')->user()->email, 'Training Block');

              if (!$mail_athelete->send()) {
                  //echo 'Mailer Error: ' . $mail->ErrorInfo;
              } else {
                  //echo 'Message sent!';
                  $mail_provider = new PHPMailer;
                  $mail_provider->IsSMTP();
                  $mail_provider->SMTPAuth = true;
                  $mail_provider->SMTPSecure = env('MAIL_SECURE');
                  $mail_provider->Host = env('MAIL_HOST');
                  $mail_provider->Port = env('MAIL_PORT');
                  $mail_provider->Username = env('MAIL_USERNAME');
                  $mail_provider->Password = env('MAIL_PASSWORD');
                  $mail_provider->SetFrom(env('MAIL_FROM'), 'Training Block');
                  $mail_provider->Subject = "Request to Book";
                  $mail_provider->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
              <tbody><tr style="
                  background: #555;
              ">
                  <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
              </tr>
              <tr>
                  <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
              </tr>

              <tr>
                  <td style="padding-bottom:15px;"> 
                      <p> '.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.' has requested to book an appointment with you for '.$services->name.' on '.date("F j, Y").' at '.date("g:i a").'. Please log in to your account to confirm this appointment.</p>
                  </td>
              </tr>
              <tr>
              <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
              <p></p>
              <span style="margin-top:5px;">The Training Block Team</span>
              </td>
              </tr>

              </tbody></table></body></html>');
                  $mail_provider->AddAddress($trainer->email, 'Training Block');

                  if (!$mail_provider->send()) {
                      //echo 'Mailer Error: ' . $mail->ErrorInfo;
                  } else {
                      //echo 'Message sent!';
                  }
              }
         
          Session::flash('message', 'Service has been requested.');
          return redirect()->intended(route('customer.order.history'));
          
        }
      }

      function requestpaymentintent(Request $request) {
        
        $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); 

        //$customer = \Stripe\Customer::create();

        $stripeCustomer = \Stripe\Customer::create([
          'email' => 'bala@sgstechie.com',
        ], ['stripe_account' => 'acct_1GyLbJJRYVtlIUWP']);

        $payment_intent = \Stripe\PaymentIntent::create([
          'amount' => 1099,
          'currency' => 'usd',
          'customer' => $stripeCustomer->id,
        ], ['stripe_account' => 'acct_1GyLbJJRYVtlIUWP']);
        //echo '<pre>';print_r($payment_intent);exit();
        $response = ['status' => true, "Message" => 'Record deleted successfuly','client_secret' => $payment_intent->client_secret, 'id' => $payment_intent->id, 'customer_id' => $stripeCustomer->id];
        return $response;
     
      }

      function requestmonthlyyearlypaymentintent(Request $request) {
        parse_str($request->form_data, $searcharray);
            $requestData = $request->all();
            
          
       
          Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); 
           
              $startDate_str=explode('-',$requestData['start_date']);

              $startDate = $startDate_str[2].'-'.$startDate_str[0].'-'.$startDate_str[1];
              $user = Auth::guard('front_auth')->user();
              
              $adminFeesPercent = getSetting('admin-fees');

              if(isset($requestData["discounted_price"])){
                if($requestData["discounted_price"] == ''){
                    $service_amounts = $requestData["service_amount"];
                    $discounted_price = 0;
                  } else {
                    $service_amounts = $requestData["discounted_price"];
                    $discounted_price = $requestData["discounted_price"];
                  }
                } else {
                  $service_amounts = $requestData["service_amount"];
                  $discounted_price = 0;
                }
           
            $adminFees = ($adminFeesPercent/100)*$service_amounts;
            $amountToPay = $service_amounts - $adminFees;
            $adminFees = ($adminFeesPercent/100)*$service_amounts;
            
           
                $order = new Orders();
                $order->user_id = $requestData["user_id"];
                $order->first_name = $requestData["first_name"];
                $order->last_name = $requestData["last_name"];
                $order->phone = $requestData["phone_number"];
                //$order->address = $requestData["address"];
               // $order->apartment_no = $requestData["apt_no"];
                $order->state = $requestData["state"];
               // $order->country = $requestData["country"];
                $order->city = $requestData["city"];
                $order->zip_code = $requestData["zip_code"];
                $order->type = "request";
                $order->service_id = $requestData["service"];
                $order->trainer_id = $requestData["trainerid"];
                $order->plan_type = $requestData["plan_type"];
               // $order->service_date = $requestData["dates"];
                $order->start_date = $startDate;
                //$order->end_date =  $endDate;
                //$order->service_time = $requestData["time_slot"];
                $order->status = 4;
                $order->ref_discount = $adminFees;
                $order->admin_fees = $adminFees;
                $order->amount = $amountToPay;  
                $order->stripeToken = $request->stripeToken; 
                
                if(isset($request->paymentIntent)){
                 // dd($request->paymentIntent);exit;
                    //$order->amount = $amountToPay;  
                 $order->stripe_payment_id = $request->paymentIntent['id'];
                // $order->json_response = $request->paymentIntentJson;  
                }

            if($order->save()){
              $insertedId = $order->id;
              $serviceid = $requestData["service"];
              $getServiceDetails = DB::table('trainer_services')->where(["id" => $serviceid])->first();
              $resource_category = DB::table('order_request')->insert([
                      'user_id' => Auth::guard('front_auth')->user()->id,
                      'service_id' => $serviceid,
                      'trainer_id' => $getServiceDetails->trainer_id,
                      'order_id' => $insertedId,
                      'first_name' => Auth::guard('front_auth')->user()->first_name,
                      'last_name' => Auth::guard('front_auth')->user()->last_name,
                      'email_id' => Auth::guard('front_auth')->user()->email,
                      'phone' => $requestData["phone_number"],
                      'reuest_date_time' => date('Y-m-d H:i:s'),
                      'stripeToken' => $request->stripeToken,
                      'status' => 1
                  ]);   
              $services = TrainerServices::where('id', $serviceid)->first();
              $trainer = FrontUsers::where(["id" => $getServiceDetails->trainer_id])->first();
              //Server settings                   // Enable verbose debug output
              $mail_athelete = new PHPMailer;
              $mail_athelete->IsSMTP();
              $mail_athelete->SMTPAuth = true;
              $mail_athelete->SMTPSecure = env('MAIL_SECURE');
              $mail_athelete->Host = env('MAIL_HOST');
              $mail_athelete->Port = env('MAIL_PORT');
              $mail_athelete->Username = env('MAIL_USERNAME');
              $mail_athelete->Password = env('MAIL_PASSWORD');
              $mail_athelete->SetFrom(env('MAIL_FROM'), 'Training Block');
              $mail_athelete->Subject = "Request to Book";
              $mail_athelete->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
          <tbody><tr style="
              background: #555;
          ">
              <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
          </tr>
          <tr>
              <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
          </tr>

          <tr>
              <td style="padding-bottom:15px;"> 
                 <p>You have requested to purchase '.$services->name.' with '.$trainer->business_name.'. You will receive an email confirmation once this provider has reviewed your information and confirmed or denied this request.</p>
              </td>
          </tr>
          <tr>
          <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
          <p></p>
          <span style="margin-top:5px;">The Training Block Team</span>
          </td>
          </tr>

          </tbody></table></body></html>');
              $mail_athelete->AddAddress(Auth::guard('front_auth')->user()->email, 'Training Block');

              if (!$mail_athelete->send()) {
                  //echo 'Mailer Error: ' . $mail->ErrorInfo;
              } else {
                  //echo 'Message sent!';
                  //
                  $mail_provider = new PHPMailer;
                  $mail_provider->IsSMTP();
                  $mail_provider->SMTPAuth = true;
                  $mail_provider->SMTPSecure = env('MAIL_SECURE');
                  $mail_provider->Host = env('MAIL_HOST');
                  $mail_provider->Port = env('MAIL_PORT');
                  $mail_provider->Username = env('MAIL_USERNAME');
                  $mail_provider->Password = env('MAIL_PASSWORD');
                  $mail_provider->SetFrom(env('MAIL_FROM'), 'Training Block');
                  $mail_provider->Subject = "Request to Book";
                  $mail_provider->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
              <tbody><tr style="
                  background: #555;
              ">
                  <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
              </tr>
              <tr>
                  <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
              </tr>

              <tr>
                  <td style="padding-bottom:15px;"> 
                     <p> '.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.' has requested to purchase '.$services->name.'. Please log in to your account to confirm this appointment.</p>
                  </td>
              </tr>
              <tr>
              <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
              <p></p>
              <span style="margin-top:5px;">The Training Block Team</span>
              </td>
              </tr>

              </tbody></table></body></html>');
                  $mail_provider->AddAddress($trainer->email, 'Training Block');

                  if (!$mail_provider->send()) {
                      //echo 'Mailer Error: ' . $mail->ErrorInfo;
                  } else {
                      //echo 'Message sent!';
                  }
              }
            
             
              Session::flash('message', 'Service has been requested.');
              return redirect()->intended(route('customer.order.history'));
          }


         
      }

      function requestpackagedealpaymentintent(Request $request) {
        parse_str($request->form_data, $searcharray);
             $requestData = $searcharray;
             $requestData = $request->all();
              $user = Auth::guard('front_auth')->user();
              
              $adminFeesPercent = getSetting('admin-fees');

              if(isset($requestData["discounted_price"])){
                if($requestData["discounted_price"] == ''){
                    $service_amounts = $requestData["service_amount"];
                  } else {
                    $service_amounts = $requestData["discounted_price"];
                  }
                } else {
                  $service_amounts = $requestData["service_amount"];
                }
            
                $adminFees = ($adminFeesPercent/100)*$service_amounts;
                $amountToPay = $service_amounts - $adminFees;
                $adminFees = ($adminFeesPercent/100)*$service_amounts;
            
                $order = new Orders();
                $order->user_id = $requestData["user_id"];
                $order->first_name = $requestData["first_name"];
                $order->last_name = $requestData["last_name"];
                $order->phone = $requestData["phone_number"];
                //$order->address = $requestData["address"];
                $order->state = $requestData["state"];
                $order->city = $requestData["city"];
                $order->zip_code = $requestData["zip_code"];
                $order->type = "request";
                $order->service_id = $requestData["service"];
                $order->trainer_id = $requestData["trainerid"];
                $order->plan_type = $requestData["plan_type"];
                $order->status = 4;
                $order->ref_discount = $adminFees;
                $order->admin_fees = $adminFees;
                $order->amount = $amountToPay;  
                $order->stripeToken = $request->stripeToken; 
                
                
            
            if($order->save()){
              $insertedId = $order->id;
              $serviceid = $requestData["service"];
              $getServiceDetails = DB::table('trainer_services')->where(["id" => $serviceid])->first();
              $resource_category = DB::table('order_request')->insert([
                      'user_id' => Auth::guard('front_auth')->user()->id,
                      'service_id' => $serviceid,
                      'trainer_id' => $getServiceDetails->trainer_id,
                      'order_id' => $insertedId,
                      'first_name' => Auth::guard('front_auth')->user()->first_name,
                      'last_name' => Auth::guard('front_auth')->user()->last_name,
                      'email_id' => Auth::guard('front_auth')->user()->email,
                      'phone' => $requestData["phone_number"],
                      'reuest_date_time' => date('Y-m-d H:i:s'),
                      'stripeToken' => $request->stripeToken,
                      'status' => 1
                  ]);   
              $services = TrainerServices::where('id', $serviceid)->first();
              $trainer = FrontUsers::where(["id" => $getServiceDetails->trainer_id])->first();
              //Server settings                   // Enable verbose debug output
              $mail_athelete = new PHPMailer;
              $mail_athelete->IsSMTP();
              $mail_athelete->SMTPAuth = true;
              $mail_athelete->SMTPSecure = env('MAIL_SECURE');
              $mail_athelete->Host = env('MAIL_HOST');
              $mail_athelete->Port = env('MAIL_PORT');
              $mail_athelete->Username = env('MAIL_USERNAME');
              $mail_athelete->Password = env('MAIL_PASSWORD');
              $mail_athelete->SetFrom(env('MAIL_FROM'), 'Training Block');
              $mail_athelete->Subject = "Request to Book";
              $mail_athelete->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
          <tbody><tr style="
              background: #555;
          ">
              <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
          </tr>
          <tr>
              <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
          </tr>

          <tr>
              <td style="padding-bottom:15px;"> 
                 <p>You have requested to purchase '.$services->name.' with '.$trainer->business_name.'. You will receive an email confirmation once this provider has reviewed your information and confirmed or denied this request.</p>
              </td>
          </tr>
          <tr>
          <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
          <p></p>
          <span style="margin-top:5px;">The Training Block Team</span>
          </td>
          </tr>

          </tbody></table></body></html>');
              $mail_athelete->AddAddress(Auth::guard('front_auth')->user()->email, 'Training Block');

              if (!$mail_athelete->send()) {
                  //echo 'Mailer Error: ' . $mail->ErrorInfo;
              } else {
                  //echo 'Message sent!';
                  //
                  $mail_provider = new PHPMailer;
                  $mail_provider->IsSMTP();
                  $mail_provider->SMTPAuth = true;
                  $mail_provider->SMTPSecure = env('MAIL_SECURE');
                  $mail_provider->Host = env('MAIL_HOST');
                  $mail_provider->Port = env('MAIL_PORT');
                  $mail_provider->Username = env('MAIL_USERNAME');
                  $mail_provider->Password = env('MAIL_PASSWORD');
                  $mail_provider->SetFrom(env('MAIL_FROM'), 'Training Block');
                  $mail_provider->Subject = "Request to Book";
                  $mail_provider->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
              <tbody><tr style="
                  background: #555;
              ">
                  <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
              </tr>
              <tr>
                  <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
              </tr>

              <tr>
                  <td style="padding-bottom:15px;"> 
                     <p> '.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.' has requested to purchase '.$services->name.'. Please log in to your account to confirm this appointment.</p>
                  </td>
              </tr>
              <tr>
              <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
              <p></p>
              <span style="margin-top:5px;">The Training Block Team</span>
              </td>
              </tr>

              </tbody></table></body></html>');
                  $mail_provider->AddAddress($trainer->email, 'Training Block');

                  if (!$mail_provider->send()) {
                      //echo 'Mailer Error: ' . $mail->ErrorInfo;
                  } else {
                      //echo 'Message sent!';
                  }
              }
            
             
              Session::flash('message', 'Service has been requested.');
              return redirect()->intended(route('customer.order.history'));
          }


         
      }

      function requestpackagedealzeropaymentintent(Request $request) {
        $requestData = $request->all();
              $user = Auth::guard('front_auth')->user();
              
              $amountToPay = $requestData["service_amount"];
                
            
                $order = new Orders();
                $order->user_id = $requestData["user_id"];
                $order->first_name = $requestData["first_name"];
                $order->last_name = $requestData["last_name"];
                $order->phone = $requestData["phone_number"];
                $order->state = $requestData["state"];
                $order->city = $requestData["city"];
                $order->zip_code = $requestData["zip_code"];
                $order->type = "request";
                $order->service_id = $requestData["service"];
                $order->trainer_id = $requestData["trainerid"];
                $order->plan_type = $requestData["plan_type"];
                $order->status = 4;
                $order->amount = $amountToPay;  
                
                
            
            if($order->save()){
              $insertedId = $order->id;
              $serviceid = $requestData["service"];
              $getServiceDetails = DB::table('trainer_services')->where(["id" => $serviceid])->first();
              $resource_category = DB::table('order_request')->insert([
                      'user_id' => Auth::guard('front_auth')->user()->id,
                      'service_id' => $serviceid,
                      'trainer_id' => $getServiceDetails->trainer_id,
                      'order_id' => $insertedId,
                      'first_name' => Auth::guard('front_auth')->user()->first_name,
                      'last_name' => Auth::guard('front_auth')->user()->last_name,
                      'email_id' => Auth::guard('front_auth')->user()->email,
                      'phone' => $requestData["phone_number"],
                      'reuest_date_time' => date('Y-m-d H:i:s'),
                      'status' => 1
                  ]);   
              $services = TrainerServices::where('id', $serviceid)->first();
              $trainer = FrontUsers::where(["id" => $getServiceDetails->trainer_id])->first();
              //Server settings                   // Enable verbose debug output
              $mail_athelete = new PHPMailer;
              $mail_athelete->IsSMTP();
              $mail_athelete->SMTPAuth = true;
              $mail_athelete->SMTPSecure = env('MAIL_SECURE');
              $mail_athelete->Host = env('MAIL_HOST');
              $mail_athelete->Port = env('MAIL_PORT');
              $mail_athelete->Username = env('MAIL_USERNAME');
              $mail_athelete->Password = env('MAIL_PASSWORD');
              $mail_athelete->SetFrom(env('MAIL_FROM'), 'Training Block');
              $mail_athelete->Subject = "Request to Book";
              $mail_athelete->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
          <tbody><tr style="
              background: #555;
          ">
              <td style="padding:10px;border-bottom:solid 1px #555;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
          </tr>
          <tr>
              <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
          </tr>

          <tr>
              <td style="padding-bottom:15px;"> 
                 <p>You have requested to purchase '.$services->name.' with '.$trainer->business_name.'. You will receive an email confirmation once this provider has reviewed your information and confirmed or denied this request.</p>
              </td>
          </tr>
          <tr>
          <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
          <p></p>
          <span style="margin-top:5px;">The Training Block Team</span>
          </td>
          </tr>

          </tbody></table></body></html>');
              $mail_athelete->AddAddress(Auth::guard('front_auth')->user()->email, 'Training Block');

              if (!$mail_athelete->send()) {
                  //echo 'Mailer Error: ' . $mail->ErrorInfo;
              } else {
                  //echo 'Message sent!';
                  //
                  $mail_provider = new PHPMailer;
                  $mail_provider->IsSMTP();
                  $mail_provider->SMTPAuth = true;
                  $mail_provider->SMTPSecure = env('MAIL_SECURE');
                  $mail_provider->Host = env('MAIL_HOST');
                  $mail_provider->Port = env('MAIL_PORT');
                  $mail_provider->Username = env('MAIL_USERNAME');
                  $mail_provider->Password = env('MAIL_PASSWORD');
                  $mail_provider->SetFrom(env('MAIL_FROM'), 'Training Block');
                  $mail_provider->Subject = "Request to Book";
                  $mail_provider->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
              <tbody><tr style="
                  background: #555;
              ">
                  <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
              </tr>
              <tr>
                  <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
              </tr>

              <tr>
                  <td style="padding-bottom:15px;"> 
                     <p> '.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.' has requested to purchase '.$services->name.'. Please log in to your account to confirm this appointment.</p>
                  </td>
              </tr>
              <tr>
              <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
              <p></p>
              <span style="margin-top:5px;">The Training Block Team</span>
              </td>
              </tr>

              </tbody></table></body></html>');
                  $mail_provider->AddAddress($trainer->email, 'Training Block');

                  if (!$mail_provider->send()) {
                      //echo 'Mailer Error: ' . $mail->ErrorInfo;
                  } else {
                      //echo 'Message sent!';
                  }
              }
            
             
              Session::flash('message', 'Service has been requested.');
              return redirect()->intended(route('customer.order.history'));
          }


         
      }

      function providerpaymentintent(Request $request) {
        $requestData = $request->all();
        $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); 
        $response = array();

        if($request->comments !=''){
            $comments = $requestData['comments'];
        } else {
            $comments = '';
        }

      $resourceCommentCountUpdate = DB::update('update order_request set status="'.$requestData['status'].'", comments="'.$comments.'" where id="'.$requestData['request_id'].'"');

      $getServiceDetails = DB::table('order_request')->where(["id" => $requestData['request_id']])->first();
      

      $services = TrainerServices::where('id', $getServiceDetails->service_id)->first();
      $trainer = FrontUsers::where(["id" => $getServiceDetails->trainer_id])->first();
      $users = FrontUsers::where(["id" => $getServiceDetails->user_id])->first();
      
      //if($requestData['status'] == '2' || $requestData['status'] == '3'){
          if($requestData['status'] == '2'){
              $status = 'Approved';
              
              $mail = new PHPMailer;

      
              //Server settings                   // Enable verbose debug output
              if($services->format == 'In person - Single Appointment' || $services->format == 'In person - Group Appointment' || $services->format == 'Virtual - Single Appointment' || $services->format == 'Virtual - Group Appointment' || $services->format == 'In person - Package Deal' || $services->format == 'Virtual - Package Deal'){
                $getOrderDetails = DB::table('orders')->where(["id" => $requestData['order_id']])->first();
                
                $TrainerServicesdata = StripeAccounts::where('user_id',$requestData["trainer_id"])->first();
                if($getOrderDetails->amount == '0.00'){
                  $ordersUpdate = DB::update('update orders set status="1" where id="'.$requestData['order_id'].'" and status="4"');
                } else {
                  try {

                    
                        $payment = Stripe\Charge::create ([
                          "amount" => $getOrderDetails->amount*100,
                          "currency" => "usd",
                          "source" => $requestData["stripeToken"],
                          'application_fee_amount' => $getOrderDetails->admin_fees*100,
                        ], ['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                    
                    
                      
                    } catch(\Stripe\Exception\CardException $e) {
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    } catch (\Stripe\Exception\RateLimitException $e) {
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    } catch (\Stripe\Exception\InvalidRequestException $e) {
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    } catch (\Stripe\Exception\AuthenticationException $e) {
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    } catch (\Stripe\Exception\ApiConnectionException $e) {
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    } catch (\Stripe\Exception\ApiErrorException $e) {
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    } catch (Exception $e) {
                      //echo '<pre>';print_r($e->getError()->message);exit();
                      Session::flash('error', $e->getError()->message);
                      return redirect()->back()->withInput($request->all());
                    }
                    if($payment->paid){
                    //echo '<pre>';print_r($payment->paid);exit();
                      $ordersUpdate = DB::update('update orders set stripe_payment_id="'.$payment->id.'", status="1" where id="'.$requestData['order_id'].'" and status="4"');
                    }else{
                      //echo '<pre>';echo 'failed';exit();
                      Session::flash ( 'fail-message', "Error! Please Try again." );
                      return redirect()->back();
                    }
                }

                  $mail->IsSMTP();
                  $mail->SMTPAuth = true;
                  $mail->SMTPSecure = env('MAIL_SECURE');
                  $mail->Host = env('MAIL_HOST');
                  $mail->Port = env('MAIL_PORT');
                  $mail->Username = env('MAIL_USERNAME');
                  $mail->Password = env('MAIL_PASSWORD');
                  $mail->SetFrom(env('MAIL_FROM'), 'Training Block');
                  $mail->Subject = "Update to your Training Block Request to Book a Service";
                  $mail->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
              <tbody><tr style="
                  background: #555;
              ">
                  <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
              </tr>
              <tr>
                  <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
              </tr>

              <tr>
                  <td style="padding-bottom:15px;"> 
                      
                      <p>Congratulations! Your request to book an appointment for '.$services->name.' has been '.$status.'. Please log in to your account to review the details.</p>
                  </td>
              </tr>
              <tr>
              <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
              <p></p>
              <span style="margin-top:5px;">The Training Block Team</span>
              </td>
              </tr>

              </tbody></table></body></html>');
                  $mail->AddAddress($users->email, 'Training Block');

                  if (!$mail->send()) {
                      //echo 'Mailer Error: ' . $mail->ErrorInfo;
                  } else {
                      //echo 'Message sent!';
                  }

              } else {
                

                $TrainerServicesdata = StripeAccounts::where('user_id',$requestData["trainer_id"])->first();
                if(empty($TrainerServicesdata)){
                  $stripeCustomer = \Stripe\Customer::create([
                    'email' => $requestData["email_id"],
                    "source" => $requestData["stripeToken"],
                  ]);
                }else{
                  $stripeCustomer = \Stripe\Customer::create([
                      'email' => $requestData["email_id"],
                      "source" => $requestData["stripeToken"],
                    ], ['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                    //
                }
               // dd($stripeCustomer);exit();
                $stripeCustomerId = $stripeCustomer->id;
                $getOrderDetails = DB::table('orders')->where(["id" => $requestData['order_id']])->first();
                $startDate = strtotime($getOrderDetails->start_date);
                $startDate = date('Y-m-d',$startDate); 
                //fetch plan
                $plans = TrainerServices::where('id', $requestData["service_id"])->first();
                //echo '<pre>';print_r($plans->format);exit();
                if($plans->format == 'In person - Monthly Membership' || $plans->format == 'Virtual - Monthly Membership'){
                  $planId = $plans->monthly_plan_id;
                  } else {
                    $planId = $plans->yearly_plan_id;
                  }
                  $discounted_price = 0;
                  $adminFeesPercent = getSetting('admin-fees');
                //$order->plan_type = "monthly";
                //create discount
                if($discounted_price > 0){
                  $discount = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'amount_off' => $discounted_price*100,
                    'currency' => 'usd'
                  ],['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                  $subscriptionParam = ['customer' => $stripeCustomerId,
                  'items' => [['plan' => $planId]],
                  "application_fee_percent" => $adminFeesPercent,
                  'coupon' => $discount->id,
                  ];
                }else{
                  $subscriptionParam = ['customer' => $stripeCustomerId,
                  'items' => [['plan' => $planId]],
                  "application_fee_percent" => $adminFeesPercent,
                  ];
                }
                //create subscription
                
                $date = new \Carbon\Carbon;
                if($startDate > $date){
                  $timestamp = \Carbon\Carbon::parse($startDate)->timestamp;
                  $subscriptionParam["trial_end"] = $timestamp;
                }
                
                try {
                  if(empty($TrainerServicesdata)){
                      $subscription = \Stripe\Subscription::create($subscriptionParam);
                      
                  }else{
                      $subscription = \Stripe\Subscription::create($subscriptionParam,['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                     
                  }
                  } catch(\Stripe\Exception\CardException $e) {
                    //echo '<pre>';print_r($e->getError()->message);exit();
                    Session::flash('error', $e->getError()->message);
                    return redirect()->back()->withInput($request->all());
                  } catch (\Stripe\Exception\RateLimitException $e) {
                    Session::flash('error', $e->getError()->message);
                    return redirect()->back()->withInput($request->all());
                  } catch (\Stripe\Exception\InvalidRequestException $e) {
                    Session::flash('error', $e->getError()->message);
                    return redirect()->back()->withInput($request->all());
                  } catch (\Stripe\Exception\AuthenticationException $e) {
                    Session::flash('error', $e->getError()->message);
                    return redirect()->back()->withInput($request->all());
                  } catch (\Stripe\Exception\ApiConnectionException $e) {
                    Session::flash('error', $e->getError()->message);
                    return redirect()->back()->withInput($request->all());
                  } catch (\Stripe\Exception\ApiErrorException $e) {
                    Session::flash('error', $e->getError()->message);
                    return redirect()->back()->withInput($request->all());
                  } catch (Exception $e) {
                    Session::flash('error', $e->getError()->message);
                    return redirect()->back()->withInput($request->all());
                  }
                  
                  if($subscription){
                   
                    $ordersUpdate = DB::update("update orders set stripe_subscription_id='".$subscription->id."', status='1', subscription_status='".$subscription->status."', json_response='".json_encode($subscription)."' where id='".$requestData['order_id']."' and status='4'");
                  }else{
                    Session::flash ( 'fail-message', "Error! Something went wrong." );
                    return redirect()->back();
                  }

                  $mail->IsSMTP();
                  $mail->SMTPAuth = true;
                  $mail->SMTPSecure = env('MAIL_SECURE');
                  $mail->Host = env('MAIL_HOST');
                  $mail->Port = env('MAIL_PORT');
                  $mail->Username = env('MAIL_USERNAME');
                  $mail->Password = env('MAIL_PASSWORD');
                  $mail->SetFrom(env('MAIL_FROM'), 'Training Block');
                  $mail->Subject = "Update to your Training Block Request to Book a Service";
                  $mail->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
                  <tbody><tr style="
                      background: #555;
                  ">
                      <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
                  </tr>
                  <tr>
                      <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
                  </tr>

                  <tr>
                      <td style="padding-bottom:15px;"> 
                          <p>Congratulations! Your request to book an appointment for '.$services->name.' has been '.$status.'. Please log in to your account to review the details.</p>
                      </td>
                  </tr>
                  <tr>
                  <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
                  <p></p>
                  <span style="margin-top:5px;">The Training Block Team</span>
                  </td>
                  </tr>

                  </tbody></table></body></html>');
                      $mail->AddAddress($users->email, 'Training Block');

                      if (!$mail->send()) {
                          //echo 'Mailer Error: ' . $mail->ErrorInfo;
                      } else {
                          //echo 'Message sent!';
                      }

              }
          } else {
              $status = 'Rejected';
              $ordersUpdate = DB::update("update orders set status='5' where user_id='".$getServiceDetails->user_id."' and service_id='".$getServiceDetails->service_id."' and trainer_id='".$getServiceDetails->trainer_id."' and status='4'");
              $mail_athelete = new PHPMailer;

                 // echo '<pre>';print_r($users->email);exit();
              //Server settings                   // Enable verbose debug output
              $mail_athelete->IsSMTP();
              $mail_athelete->SMTPAuth = true;
              $mail_athelete->SMTPSecure = env('MAIL_SECURE');
              $mail_athelete->Host = env('MAIL_HOST');
              $mail_athelete->Port = env('MAIL_PORT');
              $mail_athelete->Username = env('MAIL_USERNAME');
              $mail_athelete->Password = env('MAIL_PASSWORD');
              $mail_athelete->SetFrom(env('MAIL_FROM'), 'Training Block');
              $mail_athelete->Subject = "Update to your Training Block Request to Book a Service";
              $mail_athelete->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
          <tbody><tr style="
              background: #555;
          ">
              <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
          </tr>
          <tr>
              <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
          </tr>

          <tr>
              <td style="padding-bottom:15px;"> 
                  <p>We are sorry, but '.$trainer->business_name.' declined your request to book '.$services->name.'. Please review your request in your Training Block account.</p>
              </td>
          </tr>
          <tr>
          <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
          <p></p>
          <span style="margin-top:5px;">The Training Block Team</span>
          </td>
          </tr>

          </tbody></table></body></html>');
              $mail_athelete->AddAddress($users->email, 'Training Block');

              if (!$mail_athelete->send()) {
                  //echo 'Mailer Error: ' . $mail->ErrorInfo;
              } else {
                  //echo 'Message sent!';
                  $mail_provider = new PHPMailer;
                  $mail_provider->IsSMTP();
                  $mail_provider->SMTPAuth = true;
                  $mail_provider->SMTPSecure = env('MAIL_SECURE');
                  $mail_provider->Host = env('MAIL_HOST');
                  $mail_provider->Port = env('MAIL_PORT');
                  $mail_provider->Username = env('MAIL_USERNAME');
                  $mail_provider->Password = env('MAIL_PASSWORD');
                  $mail_provider->SetFrom(env('MAIL_FROM'), 'Training Block');
                  $mail_provider->Subject = "Request to Book";
                  $mail_provider->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
              <tbody><tr style="
                  background: #555;
              ">
                  <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
              </tr>
              <tr>
                  <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
              </tr>

              <tr>
                  <td style="padding-bottom:15px;"> 
                      <p>You have declined '.$users->first_name.' '.$users->last_name.' request to book '.$services->name.' with you. If you believe you made this decision in error, please review the request in your Training Block account.</p>
                  </td>
              </tr>
              <tr>
              <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
              <p></p>
              <span style="margin-top:5px;">The Training Block Team</span>
              </td>
              </tr>

              </tbody></table></body></html>');
                  $mail_provider->AddAddress($trainer->email, 'Training Block');

                  if (!$mail_provider->send()) {
                      //echo 'Mailer Error: ' . $mail->ErrorInfo;
                  } else {
                      //echo 'Message sent!';
                  }
              }
          }
      
  //}
//echo 'sasasas';exit();
        
        return redirect()->back();
     }
     
     function createeventpaymentsave(Request $request) {
      parse_str($request->form_data, $searcharray);
       $requestData = $searcharray;
        $user = Auth::guard('front_auth')->user();
        $attendeeDetails = FrontUsers::where('id', '=', $user->id)->first();
        $trainerDetails = FrontUsers::where('id', '=', $requestData["trainer_id"])->first();
        $eventDetails = TrainerEvent::where('id', '=', $requestData["event_id"])->first();
  
        $adminFeesPercent = getSetting('admin-fees');
        
      
        if(isset($requestData["discounted_price"])){
          if($requestData["discounted_price"] == ''){
              $event_amounts = $requestData["eventPrice"];
            } else {
              $event_amounts = $requestData["discounted_price"];
            }
          } else {
            $event_amounts = $requestData["eventPrice"];
          }
          $adminFees = ($adminFeesPercent/100)*$event_amounts;
          $amountToPay = $event_amounts - $adminFees;
          $adminFees = ($adminFeesPercent/100)*$event_amounts;
          
          if(isset($request->paymentIntent)){
            // dd($request->paymentIntent);exit;
            $event_cost = $amountToPay;  
            $stripe_payment_id = $request->paymentIntent['id'];
            $stripe_response = $request->paymentIntentJson;  
           }
  
          $insertEventData = array(
            'event_id' => $requestData["event_id"], 
            'trainer_id' => $requestData["trainer_id"],
            'attender_id' => $user->id,
            'attender_first' => $attendeeDetails->first_name,
            'attender_last' => $attendeeDetails->last_name,
            'attender_email' => $attendeeDetails->email,
            'is_payment' => 1,
            'rsvp' => 'Attending',
            'cost' => isset($event_cost)?$event_cost:NULL,
            'original_price' => isset($requestData["eventPrice"])?$requestData["eventPrice"]:NULL,
            'stripe_payment_id' => isset($stripe_payment_id)?$stripe_payment_id:NULL,
            'stripe_response' => isset($stripe_response)?$stripe_response:NULL,
            'event_type' => 'Paid',
        );
        $eventInsert = DB::table('event_registration')->insert($insertEventData);
        $eventURL = url('/event-details/'.base64_encode($requestData["event_id"]));
        // Send email after event registration

        if($eventInsert){
          $event_mail_athelete = new PHPMailer;
          $event_mail_athelete->IsSMTP();
          $event_mail_athelete->SMTPAuth = true;
          $event_mail_athelete->SMTPSecure = env('MAIL_SECURE');
          $event_mail_athelete->Host = env('MAIL_HOST');
          $event_mail_athelete->Port = env('MAIL_PORT');
          $event_mail_athelete->Username = env('MAIL_USERNAME');
          $event_mail_athelete->Password = env('MAIL_PASSWORD');;
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
                 <p>You have successfully registered for: <a href="'.$eventURL.'">'.$eventDetails->title.'</a>. This event is on '.$eventDetails->start_date.' at '.$eventDetails->start_time.'.</p>
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
       
   }
  
   function createeventpaymentintent(Request $request) {
            
    $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); 
    $response = array();
    
     if(isset($request->stripe_user_id)){
    
     $user = Auth::guard('front_auth')->user();
     $adminFeesPercent = getSetting('admin-fees');
  
     
     if($request->discounted_Price == ''){
           $event_amounts = $request->service_amount;
         } else {
           $event_amounts = $request->discounted_Price;
         }
         $adminFees = ($adminFeesPercent/100)*$event_amounts;
         $amountToPay = $event_amounts - $adminFees;
         $adminFees = ($adminFeesPercent/100)*$event_amounts;
    /*$payment_intent = \Stripe\PaymentIntent::create([
         'payment_method_types' => ['card'],
         'amount' => round($amountToPay)*100,
         'currency' => 'usd', 
       ], ['stripe_account' => $request->stripe_user_id]);*/
     $payment_intent = \Stripe\PaymentIntent::create([
           'payment_method_types' => ['card'],
           'amount' => $amountToPay*100,
           'currency' => 'usd',
           'application_fee_amount' => $adminFees*100,
           'description' => 'Event Registration Payment',
         ], ['stripe_account' => $request->stripe_user_id]);
    
    $response = ['status' => true, "Message" => 'Record deleted successfuly','client_secret' => $payment_intent->client_secret];
  }
  return $response;
  }
        
    
}
