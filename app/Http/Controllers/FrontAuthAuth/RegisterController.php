<?php

namespace App\Http\Controllers\FrontAuthAuth;

use Modules\Users\Entities\FrontUsers;
use App\ReferralBonus;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Hash;
use Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Redirect;
use App\Mail\EmailVerify;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DB;
use Stripe;

include_once(app_path().'/../mail/PHPMailer/Exception.php');
include_once(app_path().'/../mail/PHPMailer/PHPMailer.php');
include_once(app_path().'/../mail/PHPMailer/SMTP.php');
include_once(app_path().'/../mail/vendor/autoload.php');

class RegisterController extends Controller
{   
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {  
        $this->middleware('guest:front_auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:front_auths',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return FrontAuth
     */
    protected function create(array $data)
    {
        return FrontAuth::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {   $metaTitle = "Athlete-Registration";
        return view('front.auth.register', compact('metaTitle'));
    }

    public function showTrainerRegistrationForm()
    { 
        $metaTitle = "Trainer-Registration";
        return view('front.auth.trainer_register', compact('metaTitle'));
    }
    public function accountinformationdetails($plan_type) {
        $user = Auth::guard('front_auth')->user();
        $accountData = DB::select("select * from subscriptionplan where subcription_plan='".$plan_type."'");
        if (count($accountData) > 0) {
            $subcription_plan = $accountData[0]->subcription_plan;
            $price = $accountData[0]->price;
            $free_trial_months = $accountData[0]->free_trial_months;
            $product_id = $accountData[0]->product_id;
            $plan_id = $accountData[0]->plan_id;
            $response = ['subcription_plan' => $subcription_plan, "price" => $price, "free_trial_months" => $free_trial_months, "product_id" => $product_id, "plan_id" => $plan_id];
            
            return json_encode($response);
        } 
        
    }

    function createnewproviderpaymentintent(Request $request) {
            $data = $request->all();
            $price = $data['planPrice'];
            $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); 

            $response = array();

            // $amountToPay = 100;
            $amountToPay = $price;

            $payment_intent = \Stripe\PaymentIntent::create([
            'payment_method_types' => ['card'],
            'amount' => $amountToPay*100,
            'currency' => 'usd',
            ]);


            $response = ['status' => true, "Message" => 'Record deleted successfuly','client_secret' => $payment_intent->client_secret];
            return $response;
        }

    public function provider_register(Request $request){
        $requestData = $request->all();
        if(isset($requestData['ref'])){
            $referredByUser = FrontUsers::where('affiliate_id', $requestData['ref'])->first();
            $referredBy = $referredByUser->id;
        }
        $message = array();
        $rules = array();
        $rules['first_name'] = 'required';
        $rules['last_name'] = 'required';
        $rules['email'] = 'required|email|unique:front_users,email';
        $rules['password'] = 'required|confirmed|min:8';
        $rules['password_confirmation'] = 'required';
        if($requestData['user_role'] == "trainer"){
            $errors = $this->validate($request, [
                    'business_name'=>'required|max:50|unique:front_users,business_name',
                ]);
        }
        
        $errors = Validator::make($requestData, $rules,$message);
        $accountData = DB::select("select * from subscriptionplan");
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
            return view('front.auth.trainer_billing_information', compact('requestData','accountData'));
        }
       
    }


    public function register(Request $request){
        
        //$referred_by = Cookie::get('referral');
//dd($referred_by);
        $requestData = $request->all();
        if(isset($requestData['ref'])){
            $referredByUser = FrontUsers::where('affiliate_id', $requestData['ref'])->first();
            $referredBy = $referredByUser->id;
        }
        //$secretKey = "6LdnSykaAAAAAI9zFdiYtuycZ6IgbXIibqXNhlwg";
        $message = array();
        $rules = array();
        //$ip = $_SERVER['REMOTE_ADDR'];
       /* $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$requestData['g-recaptcha-response']."&remoteip=".$ip);
        $responseKeys = json_decode($response,true);
        if(intval($responseKeys["success"]) !== 1) {    
            $rules['g-recaptcha-response'] = 'required';
            $message['g-recaptcha-response.required'] = 'Captcha verification failed';
        } */
        
        $rules['first_name'] = 'required';
        $rules['last_name'] = 'required';
        $rules['email'] = 'required|email|unique:front_users,email';
        $rules['password'] = 'required|confirmed|min:8';
        $rules['password_confirmation'] = 'required';
        if($requestData['user_role'] == "trainer"){
            $errors = $this->validate($request, [
                    'business_name'=>'required|max:50|unique:front_users,business_name',
                ]);
        }
        $errors = Validator::make($requestData, $rules,$message);
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
            $discountAmount = getSetting('discount');
            $frontUser = new FrontUsers();
            $frontUser->first_name = $requestData['first_name'];
            $frontUser->last_name = $requestData['last_name'];
            $frontUser->email = $requestData['email'];
            $frontUser->password = Hash::make($requestData['password']);
            $frontUser->affiliate_id = str_random(5);
            $frontUser->referred_by = isset($requestData['ref']) ? $referredBy : null;
            $frontUser->referral_wallet = isset($requestData['ref']) ? $discountAmount : null;
            $frontUser->user_role = $requestData['user_role'];
            $frontUser->status = 'active';
            if($requestData['user_role'] == "trainer"){
                $frontUser->business_name = $requestData['business_name'];
                //$frontUser->phone_number = $requestData['phone_number'];
                $confirmation_code = str_random(30);
                $frontUser->confirmation_code = $confirmation_code;
                $frontUser->status = 'active';
                $frontUser->is_subscription = 1;
                $frontUser->confirmed = 1;

                $string = $requestData['first_name'].' '.$requestData['last_name'];
                if(isset($requestData['business_name']) && $requestData['business_name'] != ''){
                    $string = $requestData['business_name'];
                }
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
                $num_str = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $frontUser->spot_description = $slug;
            }
            if($frontUser->save()){
                
                if($requestData['user_role'] == "trainer"){
                    
                    Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
                    if($requestData['free_trial'] == 0){

                        $startDate = $requestData['start_date'];
                    } else {
                        $effectiveDate = date('m-d-Y', strtotime("+".$requestData['free_trial']." months", strtotime($requestData['start_date'])));
                 
                        $startDate_str=explode('-',$effectiveDate);

                        $startDate = $startDate_str[2].'-'.$startDate_str[0].'-'.$startDate_str[1];
                    }
                      
                   
                      $stripeCustomer = \Stripe\Customer::create([
                          'email' => $requestData['email'],
                          "source" => $request->stripeToken,
                        ]);
                    //   echo '<pre>';print_r($stripeCustomer);exit();
                        $stripeCustomerId = $stripeCustomer->id;
                        $planId = $requestData['plan_id'];
                      
                      
                        $subscriptionParam = ['customer' => $stripeCustomerId,
                        'items' => [['plan' => $planId]],
                        ];
                      //create subscription
                      
                      $date = new \Carbon\Carbon;
                      if($startDate > $date){
                        $timestamp = \Carbon\Carbon::parse($startDate)->timestamp;
                        $subscriptionParam["trial_end"] = $timestamp;
                      }
                    //   echo '<pre>';print_r($subscriptionParam);exit();
                      try {
                            $subscription = \Stripe\Subscription::create($subscriptionParam);
                            // $subscription = 1;
                        } catch(\Stripe\Exception\CardException $e) {
                        //   echo '<pre>';print_r($e->getError()->message);exit();
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
                          
                          $resource_category = DB::table('provider_orders')->insert([
                                  'trainer_id' => $frontUser->id,
                                  'plan_type' => $requestData["subscription_plan"],
                                  'amount' => $requestData["price"],
                                  'start_date' => date('Y-m-d'),
                                  'stripe_subscription_id' => $subscription->id,
                                  'subscription_status' => $subscription->status,
                                  'json_response' => json_encode($subscription),
                                  'stripeToken' => $request->stripeToken
                              ]);
                          $users_status_Update = DB::update('update front_users set is_payment="1" where id="'.$frontUser->id.'"');
                      }
                }
                if(isset($requestData['ref'])){
                    if($referredByUser){
                        $referredByUser->referral_wallet = $referredByUser->referral_wallet + $discountAmount;
                        $referredByUser->save();
                    }

                    $refBonus = ReferralBonus::create([
                        'referred_by' => $referredBy,
                        'user_id' => $frontUser->id,
                        'discount' => $discountAmount,
                    ]);
                }
               // Cookie::forget('referral'); 

               $subject = "Your profile created.";
                $emails_name = 'Training Block';
                $admin_email = "trainingblockusa@gmail.com";
                $admin_name = "Training Block";
                $emails = $requestData['email'];
            //    $emails = "trainingblockusa@gmail.com";

                $login_url = url('/login');

                if($requestData['user_role'] == "customer"){
                    // Mail::send('email.register_user', ["user" => $frontUser], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                    //     $message->from($admin_email, $admin_name);
                    //     $message->to($emails, $emails_name)->subject($subject);
                    // }); 

            // Sent Mail after Registration

            $mail = new PHPMailer;

            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = env('MAIL_SECURE');
            $mail->Host = env('MAIL_HOST');
            $mail->Port = env('MAIL_PORT');
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SetFrom(env('MAIL_FROM'), 'Training Block');
            $mail->Subject = "Training Block Profile Created";
            $mail->MsgHTML('<html xmlns="http://www.w3.org/1999/xhtml">

            <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            </head>
            
            <body style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; color: #74787E; height: 100%; hyphens: auto; line-height: 1.4; margin: 0; -moz-hyphens: auto; -ms-word-break: break-all; width: 100% !important; -webkit-hyphens: auto; -webkit-text-size-adjust: none; word-break: break-word;">
                <style>
                    @media only screen and (max-width: 600px) {
                        .inner-body {
                            width: 100% !important;
                        }
            
                        .footer {
                            width: 100% !important;
                        }
                    }
            
                    @media only screen and (max-width: 500px) {
                        .button {
                            width: 100% !important;
                        }
                    }
                </style>
                <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                    <tr>
                        <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                            <table class="content" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                <tr>
                                    <td class="header" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 25px 0; text-align: center; background: #282a3c;">
                                        <a href="'.url("/").'" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;">
                                            <img src="'.url("/public/images/logo.png").'" alt="img" style="border: none; max-width: 150px;">
                                        </a>
                                    </td>
                                </tr>
                                <!-- Email Body -->
                                <tr>
                                    <td class="body" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #FFFFFF; border-bottom: 1px solid #EDEFF2; border-top: 1px solid #EDEFF2; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #FFFFFF; margin: 0 auto; padding: 0; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                                            <!-- Body content -->
                                            <tr>
                                                <td class="content-cell" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                                    <h1 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #2F3133; font-size: 19px; font-weight: bold; margin-top: 0; text-align: left;">
                                                        Hi '.$frontUser->first_name.',
                                                    </h1>
                                                    
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">You have successfully registered for an account with Training Block! You can find quality services, resources, and events you need for your training, right here.</p>
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                                    Your registered email address is '.$frontUser->email.'.</p>
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Once logged on, you can easily edit your profile photo and information by going to the “Profile” tab on the drop down menu at the top right of the page. There, you can also connect with other athletes, review your saved resources, and edit your RSVP status for upcoming events.</p>
                                                    
                                                    <br>
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Happy training!</p>
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 0.5em; margin-top: 10px; text-align: left;">The Training Block Team</p>                                                    
                                                    <br>
                                                    <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-top: 1px solid #EDEFF2; margin-top: 25px; padding-top: 25px;">
                                                        <tr>
                                                            <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                                <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 12px;">If you having trouble
                                                                    into your web browser:
                                                                    <a href="'.$login_url.'" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #3869D4;">Login</a>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr style="background: #282a3c;">
                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto; padding: 0; text-align: center; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                                            <tr>
                                                <td class="content-cell" align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #AEAEAE; font-size: 12px; text-align: center;">Copyrights © '.date("Y").' Training Block. All rights reserved.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            
            </html>');
            $mail->AddAddress($emails, 'Training Block');

            if (!$mail->send()) {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message sent!';
            }


                    Session::flash('message', 'User registered successfully.');
                    Auth::guard('front_auth')->login($frontUser);                
                    return redirect()->intended(route('customer.profile'));
                }elseif($requestData['user_role'] == "trainer"){
                    //Send verification link
                   
                    // try { 
                    // Mail::to($emails)->send(new EmailVerify($frontUser)); 
                    // } catch (\Exception $exc) {
                    //     echo $exc->getTraceAsString();
                    // }

            // Sent Mail after Registration

                    $mail = new PHPMailer;

                    $mail->IsSMTP();
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = env('MAIL_SECURE');
                    $mail->Host = env('MAIL_HOST');
                    $mail->Port = env('MAIL_PORT');
                    $mail->Username = env('MAIL_USERNAME');
                    $mail->Password = env('MAIL_PASSWORD');
                    $mail->SetFrom(env('MAIL_FROM'), 'Training Block');
                    $mail->Subject = "Training Block Profile Created";
                    $mail->MsgHTML('<html xmlns="http://www.w3.org/1999/xhtml">

                    <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                </head>
                
                <body style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; color: #74787E; height: 100%; hyphens: auto; line-height: 1.4; margin: 0; -moz-hyphens: auto; -ms-word-break: break-all; width: 100% !important; -webkit-hyphens: auto; -webkit-text-size-adjust: none; word-break: break-word;">
                    <style>
                        @media only screen and (max-width: 600px) {
                            .inner-body {
                                width: 100% !important;
                            }
                
                            .footer {
                                width: 100% !important;
                            }
                        }
                
                        @media only screen and (max-width: 500px) {
                            .button {
                                width: 100% !important;
                            }
                        }
                    </style>
                    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                        <tr>
                            <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                <table class="content" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                    <tr>
                                        <td class="header" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 25px 0; text-align: center; background: #282a3c;">
                                            <a href="'.url("/").'" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;">
                                                <img src="'.url("/public/images/logo.png").'" alt="img" style="border: none; max-width: 150px;">
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- Email Body -->
                                    <tr>
                                        <td class="body" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #FFFFFF; border-bottom: 1px solid #EDEFF2; border-top: 1px solid #EDEFF2; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #FFFFFF; margin: 0 auto; padding: 0; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                                                <!-- Body content -->
                                                <tr>
                                                    <td class="content-cell" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                                        <h1 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #2F3133; font-size: 19px; font-weight: bold; margin-top: 0; text-align: left;">
                                                            Welcome '.$frontUser->first_name.' '.$frontUser->last_name.',
                                                        </h1>
                                                        
                                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                                        <h4>Thank you for creating a user profile with Training Block!</h4> 
                                                        Your registered Email ID is '.$frontUser->email.'
                                                        </p>
                                                        
                                                        <br>
                                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Training Block</p>
                                                        <br>
                                                        <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-top: 1px solid #EDEFF2; margin-top: 25px; padding-top: 25px;">
                                                            <tr>
                                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 12px;">If you having trouble
                                                                        into your web browser:
                                                                        <a href="'.$login_url.'" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #3869D4;">Login</a>
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="background: #282a3c;">
                                        <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto; padding: 0; text-align: center; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                                                <tr>
                                                    <td class="content-cell" align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #AEAEAE; font-size: 12px; text-align: center;">Copuright © '.date("Y").' Training Block. All rights reserved.</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                    
                    </html>');
                    $mail->AddAddress($emails, 'Training Block');

                    if (!$mail->send()) {
                        echo 'Mailer Error: ' . $mail->ErrorInfo;
                    } else {
                        echo 'Message sent!';
                    }
               
                    Session::flash('message', 'Thanks for signing up!');
                    Auth::guard('front_auth')->login($frontUser);
                   // Session::flash('message', 'Thanks for signing up! Please check your email.');
                    Session::put('tabName', "trainer");                
                    return redirect()->intended(route('front.dashboard'));            
                    // return redirect('trainer/dashboard');
                }


                
            }
            
            
        }
    }

    public function confirm($confirmation_code){
        if( ! $confirmation_code)
        {
            abort(404);
        }

        $user = FrontUsers::whereConfirmationCode($confirmation_code)->first();

        if ( ! $user)
        {
            abort(404);
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->status = 'active';
        $user->save();

        Auth::guard('front_auth')->login($user);      
        Session::flash('message', 'You have successfully verified your account.');  
        $subject = "Your profile created.";
        $emails_name = 'Training Block';
        $admin_email = "auto-reply@trainingblockusa.com";
        $admin_name = "Training Block";
        $emails = $user->email;
        // Mail::send('email.register_user', ["user" => $user], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
        //     $message->from($admin_email, $admin_name);
        //     $message->to($emails, $emails_name)->subject($subject);
        // });        
        return redirect()->intended(route('front.profile'));

         
        // Session::put('tabName', "trainer");
        // return Redirect::route('front.login');
    }

    public function sendMail(Request $request){
        $subject = "Your profile created.";
        $emails_name = 'Training Block';
        $admin_email = "auto-reply@trainingblockusa.com"; 
        $admin_name = "Training Block";
        $emails = "testineed@gmail.com";
        $data = [];
        $frontUser = FrontUsers::where('id', 107)->first();
        
        Mail::send('email.verify-email', ["user" => $frontUser], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
            $message->from($admin_email, $admin_name);
            $message->to($emails, $emails_name)->subject($subject);
        });
        if( count(Mail::failures()) > 0 ){
            echo "mail not sent!";
        }else{                      
            echo "mail sent!";
        }
    }

    // protected function registered(Request $request, $user)
    // {
    //     dd("hello");
    //     if ($user->referrer !== null) {
    //         Notification::send($user->referrer, new ReferralBonus($user));
    //     }

    //     return redirect($this->redirectPath());
    // }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('front_auth');
    }

    function bussiness_name_check($name){

        $bussiness_name_check = DB::table('front_users')->where(["business_name" => $name, "user_role" => "trainer"])->count();
        return $bussiness_name_check;
    }
    
}
