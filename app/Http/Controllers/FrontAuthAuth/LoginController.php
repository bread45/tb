<?php

namespace App\Http\Controllers\FrontAuthAuth;

use App\FrontUsers;
use App\Http\Controllers\Controller;
use App\User;
use Hash;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Socialite;
use URL;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once(app_path().'/../mail/PHPMailer/Exception.php');
include_once(app_path().'/../mail/PHPMailer/PHPMailer.php');
include_once(app_path().'/../mail/PHPMailer/SMTP.php');
include_once(app_path().'/../mail/vendor/autoload.php');


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers, LogsoutGuard {
        LogsoutGuard::logout insteadof AuthenticatesUsers;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/trainer/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('guest:front_auth', ['except' => 'logout']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */

    function showLoginForm()
    {

        //dd(Session::get("tabName"));
        if (Session::get("tabName")) {
            $tabName = Session::get("tabName");
        } else {
            $tabName = "customer";
        }

        // Get URLs
        // $urlPrevious = url()->previous();
        if($tabName == "customer"){
            $urlBase     = url()->to('/');
        } else {
            $urlBase     = url()->to('trainer/dashboard');
        }
        

        // Set the previous url that we came from to redirect to after successful login but only if is internal
        /*if(($urlPrevious != $urlBase . '/login') && (substr($urlPrevious, 0, strlen($urlBase)) === $urlBase)) {
        session()->put('url.intended', $urlPrevious);
        }

        if(($urlPrevious != $urlBase . '/trainer') && (substr($urlPrevious, 0, strlen($urlBase)) === $urlBase)) {

        $name = substr($urlPrevious,strlen($urlBase . '/trainer/'));

        $trainer = \Modules\Users\Entities\FrontUsers::where('spot_description', $name)->first();
        if($trainer){
        $reviewpage = $urlBase."/customer/review-rating/".base64_encode($trainer->id);
        session()->put('url.intended', $reviewpage);
        }else{
        session()->put('url.intended', $urlPrevious);
        }
        }*/

        /*session()->put('url.intended', $urlBase);

        if (strpos($urlPrevious, '/trainer/') !== false && $tabName == "customer") {

        session()->put('review-btn',false);

        $name = substr($urlPrevious,strlen($urlBase . '/trainer/'));

        $trainer = \Modules\Users\Entities\FrontUsers::where('spot_description', $name)->first();
        if($trainer){
        $reviewpage = $urlBase."/customer/review-rating/".base64_encode($trainer->id);
        session()->put('url.intended', $reviewpage);
        }else{
        session()->put('url.intended', $urlBase);
        }

        }*/



        if (session()->get("review-url") != false) {
            $urlBase = session()->get("review-url");
        }
        if (session()->get("resource-url") != false) {
            $urlBase = session()->get("resource-url");
        }
        if (session()->get("resource-detail-url") != false) {
            $urlBase = session()->get("resource-detail-url");
        }
        if (session()->get("event-detail-url") != false) {
            $urlBase = session()->get("event-detail-url");
        } 
        if (session()->get("provider-resource-url") != false) {
            $urlBase = session()->get("provider-resource-url");
        }
        if (session()->get("book-service-url") != false) {
            $urlBase = session()->get("book-service-url");
        }


        //dd(session()->get("review-url"));
        session()->put('url.intended', $urlBase);

        $metaTitle = "Login";
        return view('front.auth.login', ["metaTitle" => $metaTitle, "tabName" => $tabName]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    function guard()
    {
        return Auth::guard('front_auth');
    }

    function login(Request $request)
    {
        // Validate the form data
        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to log the user in
        /*if ($request->user_role == "customer") {
            $credentials = [
                'email'     => $request->email,
                'password'  => $request->password,
                'user_role' => $request->user_role,
                'status'    => 'active',
            ];
        } elseif ($request->user_role == "trainer") {
            $credentials = [
                'email'     => $request->email,
                'password'  => $request->password,
                'user_role' => $request->user_role,
                //'confirmed' => 1,
                'status'    => 'active',
            ];
        }*/

        $credentials = [
                'email'     => $request->email,
                'password'  => $request->password,
                //'user_role' => $request->user_role,
                //'confirmed' => 1,
                'status'    => 'active',
            ];

        if (Auth::guard('front_auth')->attempt($credentials, $request->remember)) {

            if (Auth::guard('front_auth')->user()->user_role == 'trainer') {

                if (Auth::guard('front_auth')->user()->confirmed != 1) {

                    Auth::guard('front_auth')->logout();

                    return redirect()->back()->withErrors(['Your email address is not verified !'])->withInput($request->only('email', 'remember'));
                }

                //return redirect()->to('trainer/dashboard');
                if (session()->get("resource-url") != false || session()->get("resource-detail-url") != false || session()->get("provider-resource-url") != false) {
                    //return redirect(session('link'));
                    return redirect($request->session()->get('url.intended'));
                } else {
                    $curUrl = explode("/",$request->prevUrl);
                    if(count($curUrl) > 3 && $curUrl[3] === "event-details"){
                    return redirect()->to('/event-details/'. $curUrl[4]);                      
                    }else{
                    }
                    return redirect()->to('trainer/dashboard');                        
                    
                }
            }
            if (Auth::guard('front_auth')->user()->user_role == 'customer') {

                if (session()->has('url.intended')) {

                    $curUrl = explode("/",$request->prevUrl);   
                    if(count($curUrl) > 3 && $curUrl[3] === "event-details"){
                    return redirect()->to('/event-details/'. $curUrl[4]);                      
                    }else{
                    // return redirect($request->session()->get('url.intended'));  
                    return redirect()->to(env('FRONT_URL')."trainer/dashboard");                       
                    }
                    
                } else {
                    return redirect()->to('customer/edit-profile');
                }

            }

        } else {
            $request->session()->flash('tabName', $request->user_role);
            // if unsuccessful, then redirect back to the login with the form data
            return redirect()->back()->withErrors(['Invalid email or password!'])->withInput($request->only('email', 'remember'));
        }

    }

    function providerfbredirect($provider)
    {
        //echo $provider;exit();
            session()->put('role', 'trainer');
        return Socialite::driver($provider)->redirect();
    }
     function atheletefbredirect($provider)
    {
        //echo $provider;exit();
            session()->put('role', 'customer');
        return Socialite::driver($provider)->redirect();
    }
    function callback($provider)
    {

        //echo 'callback';exit();
        $getInfo = Socialite::driver($provider)->user();
        //echo '<pre>';
        //print_r($getInfo);exit();
        $user = $this->createUser($getInfo, $provider);
        auth()->login($user);
        return redirect()->to('/home');
    }

    function facebookCallback(Request $request)
    {
        if (!$request->has('code') || $request->has('denied')) {
            return redirect('/login');
        }

        $getInfo = Socialite::driver('facebook')->user();

        if (isset($getInfo)) {
            // $user = $this->createFacebookUser($getInfo, 'facebook');
            /*var_dump($user);
            exit();*/
            //auth()->login($user);
            // Auth::guard('front_auth')->login($user);
            $role = session()->get('role');
            $google_user = \Modules\Users\Entities\FrontUsers::where('email', $getInfo->email)->first();
            // get the event url from session
            $prevURL = session()->get('event-detail-url');

            if ($google_user) {

                Auth::login($google_user);
                auth()->login($google_user);
                Auth::guard('front_auth')->login($google_user);
                if($google_user->user_role == 'trainer'){
                    //return redirect('/provider-register')->with('message', 'email already taken.');
                    return redirect()->to('trainer/dashboard');
                    }
                    else {
                        if(isset($prevURL)){
                            return redirect($prevURL);
                        }
                        else {
                            return redirect('/');
                        }
                    }
            }else {

                if($role == 'trainer'){
                    return redirect()->route('front.provider.register.google', ['user_role' => $role, 'first_name' => $getInfo->name, 'last_name' => $getInfo->name, 'business_name' => $getInfo->name.' '.str_random(4), 'email' => $getInfo->email, 'password' => "12345678", 'password_confirmation' => "12345678"]);
                //  return redirect('/trainer/edit-profile')->with('message', 'Registered Successfully and Update Business Name, Address, City and Zip Code');
                }
                else {

                    $user = \Modules\Users\Entities\FrontUsers::create([
                    'first_name' => $getInfo->name,
                    'email'      => $getInfo->email,
                    'status'     => 'active',
                    'user_role'  => $role,
                    'password'   => Hash::make('12345678'),
                    'confirmed'  => '1',
                    'is_subscription' => '1',
                    'business_name' => $getInfo->name,
                    'google_id'  => $getInfo->id,
                ]);
                  // $request->session()->forget('role');
                  $fbToEmail = $getInfo->email;
                  $fbMail = new PHPMailer;
  
                  $fbMail->IsSMTP();
                  $fbMail->SMTPAuth = true;
                  $fbMail->SMTPSecure = env('MAIL_SECURE');
                  $fbMail->Host = env('MAIL_HOST');
                  $fbMail->Port = env('MAIL_PORT');
                  $fbMail->Username = env('MAIL_USERNAME');
                  $fbMail->Password = env('MAIL_PASSWORD');
                  $fbMail->SetFrom(env('MAIL_FROM'), 'Training Block');
                  $fbMail->Subject = "Training Block Profile Created";
                  $fbMail->MsgHTML('<html xmlns="http://www.w3.org/1999/xhtml">
  
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
                                                          Welcome '.$getInfo->name.',
                                                      </h1>
                                                      
                                                      <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                                      <h4>Thank you for creating a user profile with Training Block!</h4> 
                                                      Your registered Email ID is '.$getInfo->email.'
                                                      </p>
                                                      
                                                      <br>
                                                      <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Training Block</p>
                                                      <br>
                                                      <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-top: 1px solid #EDEFF2; margin-top: 25px; padding-top: 25px;">
                                                          <tr>
                                                              <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                                  <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 12px;">If you having trouble
                                                                      into your web browser:
                                                                      <a href="'.url("/login").'" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #3869D4;">Login</a>
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
                  $fbMail->AddAddress($fbToEmail, 'Training Block');
  
                  if (!$fbMail->send()) {
                      echo 'Mailer Error: ' . $fbMail->ErrorInfo;
                  } else {
                      echo 'Message sent!';
                      
                  }
  
                auth()->login($user); 
                Auth::guard('front_auth')->login($user);
                if(isset($prevURL)){
                    return redirect($prevURL)->with('message', 'Registered Successfully.');
                    }
                    else {
                        return redirect('/')->with('message', 'Registered Successfully.');
                    }
                }
        }
        } else {
            return redirect()->to('/login');
        }
    }
    // function createFacebookUser($getInfo, $provider)
    // {
    //     //$user = DB::table('front_users')->where("email", $getInfo->email)->first();
    //     $user = \Modules\Users\Entities\FrontUsers::where("email", $getInfo->email)->first();
    //     $role = session()->get('role');

    //     if (!isset($user)) {

    //         $userID = DB::table('front_users')->insertGetId([
    //             'first_name' => $getInfo->name,
    //             'email'      => $getInfo->email,
    //             'status'     => 'active',
    //             'user_role'  => $role,
    //             'password'   => Hash::make('12345678'),
    //             'confirmed'  => '1',
    //             'google_id'  => $getInfo->id,
    //         ]);

    //         $user = \Modules\Users\Entities\FrontUsers::where('id', $userID)->first();
           
    //         //$user = DB::table('front_users')->where('id', $userID)->first();
    //     }
    //     return $user;
    // }

    function atredirectToGoogle()
    {
        session()->put('role', 'customer');
        return Socialite::driver('google')->redirect();

    }

    function redirectToGoogle()
    {
        session()->put('role', 'trainer');
        return Socialite::driver('google')->redirect();

    }

    function createUser($getInfo, $provider)
    {
        $role = session()->get('role');
        $user = DB::table('front_users')->insert([
            'first_name' => $getInfo->name,
            'email'      => $getInfo->email,
            'status'     => 'active',
            'user_role'  => $role,
        ]);

        return $user;
    }

    function handleGoogleCallback(Request $request)
    {

        try {

            // $frontUser = new FrontUsers();
            $user = Socialite::driver('google')->user();
// echo '<pre>';print_r($user);exit();
$role = session()->get('role');
            $finduser = \Modules\Users\Entities\FrontUsers::where('email', $user->email)->first();
            // get the event url from session
            $prevURL = session()->get('event-detail-url');

            if ($finduser) {

                Auth::login($finduser);
                Auth::guard('front_auth')->login($finduser);
                if($finduser->user_role == 'trainer'){
                    //return redirect('/provider-register')->with('message', 'email already taken.');
                    return redirect()->to('trainer/dashboard');
                    }
                    else {
                        if(isset($prevURL)){
                        return redirect($prevURL);
                        }
                        else {
                            return redirect('/');
                        }
                    }

            } else {
                if($role == 'trainer'){
                    return redirect()->route('front.provider.register.google', ['user_role' => $role, 'first_name' => $user->name, 'last_name' => $user->name, 'business_name' => $user->name.' '.str_random(4), 'email' => $user->email, 'password' => "12345678", 'password_confirmation' => "12345678"]);
            //  return redirect('/trainer/edit-profile')->with('message', 'Registered Successfully and Update Business Name, Address, City and Zip Code');
                }
                else {

                    $user = \Modules\Users\Entities\FrontUsers::create([
                        'first_name' => $user->name,
                        'email'      => $user->email,
                        'status'     => 'active',
                        'user_role'  => $role,
                        'password'   => Hash::make('12345678'),
                        'confirmed'  => '1',
                        'is_subscription' => '1',
                        'business_name' => $user->name,
                        'google_id'  => $user->id,
                    ]);
                     // $request->session()->forget('role');
                $toEmail = $user->email;
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
                                                        Welcome '.$user->name.',
                                                    </h1>
                                                    
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                                    <h4>Thank you for creating a user profile with Training Block!</h4> 
                                                    Your registered Email ID is '.$user->email.'
                                                    </p>
                                                    
                                                    <br>
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Training Block</p>
                                                    <br>
                                                    <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-top: 1px solid #EDEFF2; margin-top: 25px; padding-top: 25px;">
                                                        <tr>
                                                            <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                                <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 12px;">If you having trouble
                                                                    into your web browser:
                                                                    <a href="'.url("/login").'" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #3869D4;">Login</a>
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
                $mail->AddAddress($toEmail, 'Training Block');

                if ($mail->send()) {
                    echo 'Message sent!';
                } else {
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                }
                
                    auth()->login($user); 
                    Auth::guard('front_auth')->login($user);
                    if(isset($prevURL)){
                        return redirect($prevURL)->with('message', 'Registered Successfully.');
                        }
                        else {
                            return redirect('/')->with('message', 'Registered Successfully.');
                        }
                    }

                // $resource = DB::table('front_users')->insert([
                //     'first_name' => $user->name,
                //     'email'      => $user->email,
                //     'status'     => 'active',
                //     'user_role'  => $role,
                //     'password'   => Hash::make('12345678'),
                //     'confirmed'  => '1',
                //     'google_id'  => $user->id,
                // ]);

                // return redirect('/login')->with('message', 'Registered Successfully.');

                //return redirect()->back();

            }

        } catch (Exception $e) {

            return redirect('auth/google');

        }

    }

    function logout()
    {
        Auth::guard('front_auth')->logout();
        return redirect('/');
        //return redirect('/login');
    }
}
