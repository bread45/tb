<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\TrainerServices;
use App\Services;
use Validator;
use Session;
use Image;
use Modules\Orders\Entities\Orders;

use Illuminate\Http\Response;
use DataTables;
use Stripe;
use Stripe_Error;
use App\StripeAccounts;
use DB;
use DateTime;

class ServiceController extends Controller
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
        $StripeAccountsdata = StripeAccounts::where('user_id', $trainerId)->orderBy('id', 'desc')->first();
        
        $services = TrainerServices::where('trainer_id',$trainerId)->with('service')->orderBy('id', 'desc')->get();
        
        return view('front.trainer.services.services',["services" => $services, "StripeAccountsdata" => $StripeAccountsdata]);
    }

   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Services::where('status','active')->orderby('name','asc')->get();
        $formats = ["Online"=>"Online","In Person"=>"In Person","Online & In Person"=>"Online & In Person"];
        $trainerId = Auth::guard('front_auth')->user()->id;
        
        DB::table('provider_scheduling_date')->where('trainer_id', $trainerId)->delete();
        DB::table('provider_scheduling')->where('trainer_id', $trainerId)->delete();
        $providerScheduling = DB::table('provider_scheduling_temp')->where(["trainer_id" => $trainerId])->first();
        if(isset($providerScheduling)){
            $provider_service_book = DB::table('provider_scheduling')->insert([
                    'trainer_id' => $trainerId,
                    'day1' => $providerScheduling->day1,
                    'day2' => $providerScheduling->day2,
                    'day3' => $providerScheduling->day3,
                    'day4' => $providerScheduling->day4,
                    'day5' => $providerScheduling->day5,
                    'day6' => $providerScheduling->day6,
                    'day7' => $providerScheduling->day7
                ]);

             }
        $providerScheduling = DB::table('provider_scheduling')->where(["trainer_id" => $trainerId])->first();
        
        $weekdays ='';
        if(isset($providerScheduling)){
            $returnarray = array();
            for($i=1;$i<8;$i++){
                
                $days = 'day'.$i;
                
                

                 $times = $providerScheduling->$days;
                 
                    if($times != "," && $times != NULL){
                    $day1Result = explode(",", $times);
                    $weekdays='<input type="hidden" name="days" id="days" value=""><input type="hidden" name="serviceIDs" id="serviceIDs" value="">';
                    foreach ($day1Result as $key => $value) {
                        $startEnd = explode('-',$value);


                        $weekdays .=  '<div class="multi-field form-group">
                             <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[0])).'"  style="float: left;margin-right:5px;" >
                             <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[1])).'" style="float: left;">
                         
                             <button type="button" class="btn btn-danger remove-field">☓</button>
                             <button type="button" class="add-field btn btn-info">Add Field</button>
                             <div class="clearfix"></div>
                             
                           </div>';
                    }

                       
                   }
                   
                   else { 
                        $weekdays = '<div class="multi-field form-group">
                         <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value=""  style="float: left;margin-right:5px;" >
                    
                         <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="" style="float: left;">
                     
                         <button type="button" class="btn btn-danger remove-field">☓</button>
                         <button type="button" class="add-field btn btn-info">Add Field</button>
                         <div class="clearfix"></div>
                         
                         
                       </div>';
                  } 
                if($times !=''){
                    
                        if($i == 7){
                            $array = array(
                                'title' => $providerScheduling->$days,
                                'daysOfWeek' => [ 0 ],
                                //'time' => $serviceEventsData->$days,
                                'description' => $weekdays,
                            );
                        } else {
                              $array = array(
                                'title' => $providerScheduling->$days,
                                'daysOfWeek' => [ $i ],
                                //'time' => $serviceEventsData->$days,
                                'description' => $weekdays,
                            );  
                        }
                        
                        $returnarray[] = $array;
                    }
                    
                        $providerSchedulingDate = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId])->where(["day" => $days])->get();
                        foreach($providerSchedulingDate as $val){
                            $times = $val->time;
                 
                                if($times != "," && $times != NULL){
                                $day1Result = explode(",", $times);
                                $weekdays='<input type="hidden" name="days" id="days" value=""><input type="hidden" name="serviceIDs" id="serviceIDs" value="">';
                                foreach ($day1Result as $key => $value) {
                                    $startEnd = explode('-',$value);


                                    $weekdays .=  '<div class="multi-field form-group">
                                         <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[0])).'"  style="float: left;margin-right:5px;" >
                                         <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[1])).'" style="float: left;">
                                     
                                         <button type="button" class="btn btn-danger remove-field">☓</button>
                                         <button type="button" class="add-field btn btn-info">Add Field</button>
                                         <div class="clearfix"></div>
                                         
                                       </div>';
                                }

                                   
                               }
                               
                               else { 
                                    $weekdays = '<div class="multi-field form-group">
                                     <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value=""  style="float: left;margin-right:5px;" >
                                
                                     <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="" style="float: left;">
                                 
                                     <button type="button" class="btn btn-danger remove-field">☓</button>
                                     <button type="button" class="add-field btn btn-info">Add Field</button>
                                     <div class="clearfix"></div>
                                     
                                     
                                   </div>';
                              }
                              if($times !=''){
                                $array = array(
                                    'title' => $val->time,
                                    //'daysOfWeek' => [ $i ],
                                    'start' => $val->date,
                                    'description' => $weekdays,
                                );
                            } else {
                                $weekdays='<input type="hidden" name="days" id="days" value=""><input type="hidden" name="serviceIDs" id="serviceIDs" value="">';
                                $weekdays .= '<div class="multi-field form-group">
                                     <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value=""  style="float: left;margin-right:5px;" >
                                
                                     <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="" style="float: left;">
                                 
                                     <button type="button" class="btn btn-danger remove-field">☓</button>
                                     <button type="button" class="add-field btn btn-info">Add Field</button>
                                     <div class="clearfix"></div>
                                     
                                     
                                   </div>';
                                $array = array(
                                    'title' => 'Closed',
                                    //'daysOfWeek' => [ $i ],
                                    'start' => $val->date,
                                    'description' => $weekdays,
                                );
                            }
                            $returnarray[] = $array;
                        }
                        
                    //}
                    
                    //$returnarray[] = $array;
                //}

                
                
            }
            
        } else {
            $returnarray = array();
        }
        $StripeAccountsdata = StripeAccounts::where('user_id', $trainerId)->orderBy('id', 'desc')->first();
        return view('front.trainer.services.add-service',["eventData" => json_encode($returnarray),"formats" => $formats,'service' => '',"services" => $services, 'trainerService' => '', "providerScheduling" => $providerScheduling, "StripeAccountsdata" => $StripeAccountsdata]);
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
        
        $errors = Validator::make($requestData, [
                    'service' => 'required',
                    'name' => 'required',
                    'service_type' => 'required',
                   
        ]);
        if($requestData['service_type'] == 'In person - Group Appointment' || $requestData['service_type'] == 'Virtual - Group Appointment' || $requestData['service_type'] == 'In person - Single Appointment' || $requestData['service_type'] == 'Virtual - Single Appointment'){
            if($requestData["duration"] == 0){
                if(isset($requestData["duration_mins"])){
                    //$service->duration_mins = $requestData['duration_mins'];
                } else {
                    $msg = 'Please add to minutes';
                    Session::flash('error', $msg); 
                    return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
                }

            }
        }
        //echo '<pre>';print_r($requestData);exit();
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
            if(isset($requestData["serviceId"]) && !empty($requestData["serviceId"])){
                $service = TrainerServices::find($requestData["serviceId"]);
                $msg = "Service Updated successfully.";
                
            } else {     
                $service = new TrainerServices();
                $service->trainer_id = Auth::guard('front_auth')->user()->id;
                 $msg = "Service created successfully.";
            }

            if($requestData['service_type'] == 'In person - Group Appointment' || $requestData['service_type'] == 'Virtual - Group Appointment'){
                $max_booking_allowed = $requestData['max_booking'];

            } else {
                $max_booking_allowed = '';
            }

            /*if($requestData['service_price_type'] == 'monthly_price'){
                $service->price_weekly = 0;
                $service->price_monthly = 1;

            } else {
                $service->price_weekly = 1;
                $service->price_monthly = 0;
            }*/

            //if(isset($requestData['promo_code'])){
                if($requestData['promo_code'] == 1){
                    $service->promo_code = 1;

                } else {
                    $service->promo_code = 0;
                }
            //}

            if($requestData['book_type'] == 1){
                $service->book_type = $requestData['book_type'];
                // $service->desc = $requestData['auto_book_desc'];

            } else {
                $service->book_type = $requestData['book_type'];
                // $service->desc = $requestData['request_book_desc'];
            }
                
            $service->name = $requestData['name'];
            $service->service_id = $requestData['service'];
            
            $service->duration = $requestData['duration'];
            
            if(isset($requestData["duration_mins"])){
                $service->duration_mins = $requestData['duration_mins'];
            } else {
                $service->duration_mins = '';
            }
            if(isset($requestData['buffer_time'])){
                $service->buffer_time = $requestData['buffer_time'];
            } else {
                $service->buffer_time = '';
            }
            $service->price = $requestData['price'];
            
            $service->format = $requestData['service_type'];
            $service->max_bookings = $max_booking_allowed;
            $service->message = nl2br($requestData['message']);
            $service->status = 1;
            if(session()->has('location_id')){
                $service->location_id = session()->get('location_id');
            }
            if($requestData['service_type'] == 'In person - Monthly Membership' || $requestData['service_type'] == 'Virtual - Monthly Membership' || $requestData['service_type'] == 'In person - Yearly Membership' || $requestData['service_type'] == 'Virtual - Yearly Membership'){
                $TrainerServicesdata = StripeAccounts::where('user_id',Auth::guard('front_auth')->user()->id)->first();

                 \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
                
                 try {
                     if(isset($requestData["serviceId"]) && !empty($requestData["serviceId"])){
                        
                         $productService = TrainerServices::where(['id'=> $requestData["serviceId"]])->first();
                         $productId = $productService->product_id;
                         if($productId == null){
                             if(empty($TrainerServicesdata)){
                                 $product = \Stripe\Product::create([
                                     'name' => $requestData['name'],
                                     'type' => 'service',
                                 ]);
                             }else{
                                 $product = \Stripe\Product::create([
                                     'name' => $requestData['name'],
                                     'type' => 'service',
                                 ],['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                            }
                            
                             $productId = $product->id;
                         }
                     }else{
                         if(empty($TrainerServicesdata)){
                             $product = \Stripe\Product::create([
                                 'name' => $requestData['name'],
                                 'type' => 'service',
                             ]);
                         }else{
                             $product = \Stripe\Product::create([
                                 'name' => $requestData['name'],
                                 'type' => 'service',
                             ],['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                         }
                        
                         $productId = $product->id;
                     }
                     $service->product_id = $productId;   
        
                     if($requestData['service_type'] == 'In person - Monthly Membership' || $requestData['service_type'] == 'Virtual - Monthly Membership'){
                             if(empty($TrainerServicesdata)){
                                 $monthly_price =  \Stripe\Plan::create([
                                     'amount' => $requestData['price']*100,
                                     'currency' => 'usd',
                                     'interval' => 'month',
                                     'product' => $productId,
                                 ]);
                             }else{
                                 $monthly_price =  \Stripe\Plan::create([
                                     'amount' => $requestData['price']*100,
                                     'currency' => 'usd',
                                     'interval' => 'month',
                                     'product' => $productId,
                                 ],['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                             }
                            
                             $service->monthly_plan_id = $monthly_price->id;
                         }

                         if($requestData['service_type'] == 'In person - Yearly Membership' || $requestData['service_type'] == 'Virtual - Yearly Membership'){
                            if(empty($TrainerServicesdata)){
                                $yearly_price =  \Stripe\Plan::create([
                                    'amount' => $requestData['price']*100,
                                    'currency' => 'usd',
                                    'interval' => 'year',
                                    'product' => $productId,
                                ]);
                            }else{
                                $yearly_price =  \Stripe\Plan::create([
                                    'amount' => $requestData['price']*100,
                                    'currency' => 'usd',
                                    'interval' => 'year',
                                    'product' => $productId,
                                ],['stripe_account' => $TrainerServicesdata->stripe_user_id]);
                            }
                           
                            $service->yearly_plan_id = $yearly_price->id;
                        }
                   } catch(\Stripe\Exception\CardException $e) {
                     //Since it's a decline, \Stripe\Exception\CardException will be caught
                
                    echo 'Message is:' . $e->getError()->message . '\n';
                   //dd($e->getError()->message);
                   Session::flash('error', $e->getError()->message);
                   return redirect()->back()->withInput($request->all());
                   } catch (\Stripe\Exception\RateLimitException $e) {
                     //Too many requests made to the API too quickly
                    //dd($e->getError()->message);
                     Session::flash('error', 'Too many requests made to the API too quickly');
                    return redirect()->back()->withInput($request->all());
                   } catch (\Stripe\Exception\InvalidRequestException $e) {
                     //Invalid parameters were supplied to Stripe's API
                    //dd($e->getError()->message);
                     Session::flash('error', 'Invalid parameters were supplied to Stripe API');
                     return redirect()->back()->withInput($request->all());
                   } catch (\Stripe\Exception\AuthenticationException $e) {
                     //Authentication with Stripe's API failed
                     //(maybe you changed API keys recently)
                    //dd($e->getError()->message);
                     Session::flash('error', 'Authentication with Stripe API failed');
                     return redirect()->back()->withInput($request->all());
                   } catch (\Stripe\Exception\ApiConnectionException $e) {
                    // Network communication with Stripe failed
                    //dd($e->getError()->message);
                     Session::flash('error', 'Network communication with Stripe failed');
                     return redirect()->back()->withInput($request->all());
                   } catch (\Stripe\Exception\ApiErrorException $e) {
                     //Display a very generic error to the user, and maybe send
                     //yourself an email
                    //dd($e->getError()->message);
                  //   Session::flash('error', 'Display a very generic error to the user, and maybe send');
                     return redirect()->back()->withInput($request->all());
                   } catch (Exception $e) {
                     //Something else happened, completely unrelated to Stripe
                  //dd($e->getError()->message);
                     Session::flash('error', 'Something else happened, completely unrelated to Stripe');
                     return redirect()->back()->withInput($request->all());
                   }
                
            }
            if($service->save()){
                $insertedId = $service->id;
                if(isset($requestData["serviceId"]) && !empty($requestData["serviceId"])){
                    if($requestData['service_type'] == 'In person - Group Appointment' || $requestData['service_type'] == 'Virtual - Group Appointment' || $requestData['service_type'] == 'In person - Single Appointment' || $requestData['service_type'] == 'Virtual - Single Appointment'){
                        $providerScheduling = DB::table('provider_scheduling_service')->where(["trainer_id" => Auth::guard('front_auth')->user()->id, "service_id" => $requestData["serviceId"]])->first();
                        $providerSchedulingDateCnt = DB::table('provider_scheduling_service_date')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->where(["service_id" => $requestData['serviceId']])->count();
                        if(isset($providerScheduling)){
                        $returnarray = array();
                            for($i=1;$i<8;$i++){
                                $days = 'day'.$i;
                                if($providerScheduling->$days !=''){
                                    $providerSchedulingDate = DB::table('provider_scheduling_service_date')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->where(["day" => $days])->where(["service_id" => $requestData['serviceId']])->count();
                                    //if($providerSchedulingDate == 0){
                                        $array = array(
                                            'title' => $providerScheduling->$days,
                                            'daysOfWeek' => [ $i ],
                                            'daysOfDate' => ''
                                        );
                                        $returnarray[] = $array;
                                    /*} else {
                                        
                                    }*/
                                    
                                }
                                $providerSchedulingDate = DB::table('provider_scheduling_service_date')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->where(["day" => $days])->where(["service_id" => $requestData['serviceId']])->get();
                                        foreach($providerSchedulingDate as $val){
                                            if($val->time !=''){
                                                $array = array(
                                                    'title' => $val->time,
                                                    'daysOfWeek' => [ $i ],
                                                    'daysOfDate' => $val->date
                                                );
                                                $returnarray[] = $array;
                                            } else {
                                                $array = array(
                                                    'title' => 'Closed',
                                                    'daysOfWeek' => [ $i ],
                                                    'daysOfDate' => $val->date
                                                );
                                                $returnarray[] = $array;
                                            }
                                        }
                            }
                        } elseif($providerSchedulingDateCnt != 0){
                            $returnarray = array();
                            for($i=1;$i<8;$i++){
                                $days = 'day'.$i;
                                
                                $providerSchedulingDate = DB::table('provider_scheduling_service_date')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->where(["day" => $days])->where(["service_id" => $requestData['serviceId']])->get();
                                        foreach($providerSchedulingDate as $val){
                                            if($val->time !=''){
                                                $array = array(
                                                    'title' => $val->time,
                                                    'daysOfWeek' => [ $i ],
                                                    'daysOfDate' => $val->date
                                                );
                                                $returnarray[] = $array;
                                            } else {
                                                $array = array(
                                                    'title' => 'Closed',
                                                    'daysOfWeek' => [ $i ],
                                                    'daysOfDate' => $val->date
                                                );
                                                $returnarray[] = $array;
                                            }
                                        }
                            }
                        } else {
                             $returnarray = array();
                        }
                        $eventData = json_encode($returnarray);
                        $eventData = json_decode($eventData);
                        DB::table('provider_service_book')->where('service_id', $requestData["serviceId"])->delete();
                        //$eventData = json_decode($requestData['eventdata']);
                        foreach($eventData as $val){
                                if($val->title != 'Closed'){
                                    $tim = explode(",", $val->title);
                                    $schedules = [];
                                    for($i=0;$i<count($tim);$i++){
                                        $tt = explode("-", $tim[$i]);
                                        for($j=0;$j<count($tt);$j++){
                                           
                                            if($j==0){
                                                $from = date('H:i', strtotime($tt[$j]));
                                            } else {
                                                $to = date('H:i', strtotime($tt[$j]));
                                            }      
                                        }
                                       $duration = ($requestData['duration'])*60;
                                    
                                        $duration += $requestData['duration_mins'];
                                        $duration += $requestData['buffer_time'];
                                        $duration = $duration *60;
                                        
                                        $from = strtotime($from);
                                        $to = strtotime($to);
                                        
                                        $slots = (int)(($to-$from)/$duration);
                                        for($k=0; $k< $slots; $k++){
                                             $time=  $from + ($duration*$k);
                                            if($time>0 && $time < $to){
                                                $schedules[]= date('H:i', $time);
                                            }
                                        }
                                        if($slots < 1){$schedules[] = date('H:i',$from);}
                                       
                                    
                                    }
                                

                                $provider_service_book = DB::table('provider_service_book')->insert([
                                    'trainer_id' => Auth::guard('front_auth')->user()->id,
                                    'service_id' => $insertedId,
                                    'days' => $val->daysOfWeek[0],
                                    'date' => $val->daysOfDate,
                                    'time' => implode(",", $schedules)
                                ]);
                            } else {
                                $provider_service_book = DB::table('provider_service_book')->insert([
                                    'trainer_id' => Auth::guard('front_auth')->user()->id,
                                    'service_id' => $insertedId,
                                    'days' => $val->daysOfWeek[0],
                                    'date' => $val->daysOfDate,
                                    'time' => 'Closed'
                                ]);
                            }

                        }
                    }
                

                } else {
                    $providerScheduling = DB::table('provider_scheduling')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->first();
                        $providerSchedulingDateCnt = DB::table('provider_scheduling_date')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->count();
                        if(isset($providerScheduling)){
                        $returnarray = array();
                            for($i=1;$i<8;$i++){
                                $days = 'day'.$i;
                                if($providerScheduling->$days !=''){
                                    $providerSchedulingDate = DB::table('provider_scheduling_date')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->where(["day" => $days])->count();
                                    //if($providerSchedulingDate == 0){
                                        $array = array(
                                            'title' => $providerScheduling->$days,
                                            'daysOfWeek' => [ $i ],
                                            'daysOfDate' => ''
                                        );
                                        $returnarray[] = $array;
                                    /*} else {
                                        
                                    }*/
                                    
                                }
                                $providerSchedulingDate = DB::table('provider_scheduling_date')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->where(["day" => $days])->get();
                                    foreach($providerSchedulingDate as $val){
                                        if($val->time !=''){
                                            $array = array(
                                                'title' => $val->time,
                                                'daysOfWeek' => [ $i ],
                                                'daysOfDate' => $val->date
                                            );
                                            $returnarray[] = $array;
                                        } else {
                                                $array = array(
                                                    'title' => 'Closed',
                                                    'daysOfWeek' => [ $i ],
                                                    'daysOfDate' => $val->date
                                                );
                                                $returnarray[] = $array;
                                            }
                                    }
                            }
                        } elseif($providerSchedulingDateCnt !=0){
                            $returnarray = array();
                            for($i=1;$i<8;$i++){
                                $days = 'day'.$i;
                                
                                $providerSchedulingDate = DB::table('provider_scheduling_date')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->where(["day" => $days])->get();
                                    foreach($providerSchedulingDate as $val){
                                        if($val->time !=''){
                                            $array = array(
                                                'title' => $val->time,
                                                'daysOfWeek' => [ $i ],
                                                'daysOfDate' => $val->date
                                            );
                                            $returnarray[] = $array;
                                        } else {
                                                $array = array(
                                                    'title' => 'Closed',
                                                    'daysOfWeek' => [ $i ],
                                                    'daysOfDate' => $val->date
                                                );
                                                $returnarray[] = $array;
                                            }
                                    }
                            }
                        } else {
                             $returnarray = array();
                        }
                        $eventData = json_encode($returnarray);
                        $eventData = json_decode($eventData);
                //$eventData = json_decode($requestData['eventdata']);
                //echo '<pre>';print_r($eventData);exit();
                foreach($eventData as $val){
                    if($val->title !='Closed'){
                        $tim = explode(",", $val->title);
                        
                        $schedules = [];
                        for($i=0;$i<count($tim);$i++){
                            $tt = explode("-", $tim[$i]);

                            for($j=0;$j<count($tt);$j++){
                               
                                if($j==0){
                                    $from = date('H:i', strtotime($tt[$j]));
                                } else {
                                    $to = date('H:i', strtotime($tt[$j]));
                                }      
                            }
                            if($requestData['service_type'] == 'In person - Group Appointment' || $requestData['service_type'] == 'Virtual - Group Appointment' || $requestData['service_type'] == 'In person - Single Appointment' || $requestData['service_type'] == 'Virtual - Single Appointment'){
                                $duration = $requestData['duration'];
                                }
                                else {
                                    $duration = 1;
                                }

                               $duration = $duration*60;
                            
                                $duration += $requestData['duration_mins'];
                                $duration += $requestData['buffer_time'];
                                $duration = $duration *60;
                                
                                $from = strtotime($from);
                                $to = strtotime($to);
                                
                                $slots = (int)(($to-$from)/$duration);
                                
                                for($k=0; $k< $slots; $k++){
                                     $time=  $from + ($duration*$k);
                                    if($time>0 && $time < $to){
                                        $schedules[]= date('H:i', $time);
                                    }

                                }
                                if($slots < 1){$schedules[] = date('H:i',$from);}
                                
                           
                        
                        }
                        

                        $provider_service_book = DB::table('provider_service_book')->insert([
                            'trainer_id' => Auth::guard('front_auth')->user()->id,
                            'service_id' => $insertedId,
                            'days' => $val->daysOfWeek[0],
                            'date' => $val->daysOfDate,
                            'time' => implode(",", $schedules)
                        ]);
                    } else {
                        $provider_service_book = DB::table('provider_service_book')->insert([
                            'trainer_id' => Auth::guard('front_auth')->user()->id,
                            'service_id' => $insertedId,
                            'days' => $val->daysOfWeek[0],
                            'date' => $val->daysOfDate,
                            'time' => 'Closed'
                        ]);
                    }

                }

                $providerScheduling = DB::table('provider_scheduling')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->first();
                if(isset($providerScheduling)){
                    $provider_service_book = DB::table('provider_scheduling_service')->insert([
                            'trainer_id' => Auth::guard('front_auth')->user()->id,
                            'service_id' => $insertedId,
                            'day1' => $providerScheduling->day1,
                            'day2' => $providerScheduling->day2,
                            'day3' => $providerScheduling->day3,
                            'day4' => $providerScheduling->day4,
                            'day5' => $providerScheduling->day5,
                            'day6' => $providerScheduling->day6,
                            'day7' => $providerScheduling->day7
                        ]);

                     }

                     $providerSchedulingDate = DB::table('provider_scheduling_date')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->get();
                        if(isset($providerSchedulingDate)){
                            foreach($providerSchedulingDate as $val){
                                $provider_service_book_date = DB::table('provider_scheduling_service_date')->insert([
                                    'trainer_id' => Auth::guard('front_auth')->user()->id,
                                    'service_id' => $insertedId,
                                    'day' => $val->day,
                                    'date' => $val->date,
                                    'time' => $val->time
                                ]);
                            }
                            DB::table('provider_scheduling_date')->where('trainer_id', Auth::guard('front_auth')->user()->id)->delete();
                         }
                }

                Session::flash('message', $msg); 
                return redirect('trainer/services');
            }
            
        }
    }

    function decimalHours($time){
            switch (substr_count($time, ":")) {
                case 0:
                    $time .= ":00:00";
                    break;
                case 1:
                    $time .= ":00";
                    break;
            }
            $hms = explode(":", $time);
            $time= ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
            return $time;
            
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = TrainerServices::with('service')->findOrFail(base64_decode($id));
        //dd($service);
        return view('front.trainer.services.service-detail',['service' => $service]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $services = Services::where('status','active')->orderby('name','asc')->get();
        $service = TrainerServices::findOrFail(base64_decode($id));
        $formats = ["Online"=>"Online","In Person"=>"In Person","Online & In Person"=>"Online & In Person"];
       
       $trainerId = Auth::guard('front_auth')->user()->id;
       $service_id = base64_decode($id);
        $providerScheduling = DB::table('provider_scheduling_service')->where(["trainer_id" => $trainerId, "service_id" => $service_id])->first();
        $providerSchedulingCnt = DB::table('provider_scheduling_service')->where(["trainer_id" => $trainerId, "service_id" => $service_id, "day1" => '', "day2" => '', "day3" => '', "day4" => '', "day5" => '', "day6" => '', "day7" => ''])->count();
        $providerSchedulingDateCnt = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId, "service_id" => $service_id])->count();
        $weekdays ='';
        if(isset($providerScheduling) && $providerSchedulingCnt == 0){

            $returnarray = array();
            for($i=1;$i<8;$i++){
                $days = 'day'.$i;

                 $times = $providerScheduling->$days;
                
                    if($times != "," && $times != NULL){
                    $day1Result = explode(",", $times);
                    $weekdays='<input type="hidden" name="days" id="days" value=""><input type="hidden" name="serviceIDs" id="serviceIDs" value="'.$service_id.'">';
                    foreach ($day1Result as $key => $value) {
                        $startEnd = explode('-',$value);


                        $weekdays .=  '<div class="multi-field form-group">
                             <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[0])).'"  style="float: left;margin-right:5px;" >
                             <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[1])).'" style="float: left;">
                         
                             <button type="button" class="btn btn-danger remove-field">☓</button>
                             <button type="button" class="add-field btn btn-info">Add Field</button>
                             <div class="clearfix"></div>
                             
                           </div>';
                    }

                        
                   }
                   
                   else { 
                        $weekdays = '<input type="hidden" name="serviceIDs" id="serviceIDs" value="'.$service_id.'"><div class="multi-field form-group">
                         <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value=""  style="float: left;margin-right:5px;" >
                    
                         <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="" style="float: left;">
                     
                         <button type="button" class="btn btn-danger remove-field">☓</button>
                         <button type="button" class="add-field btn btn-info">Add Field</button>
                         <div class="clearfix"></div>
                         
                         
                       </div>';
                  } 
                if($times !=''){
                   
                            if($i==7){
                                $array = array(
                                    'title' => $providerScheduling->$days,
                                    'daysOfWeek' => [ 0 ],
                                    //'time' => $serviceEventsData->$days,
                                    'description' => $weekdays,
                                );
                            } else {
                               $array = array(
                                    'title' => $providerScheduling->$days,
                                    'daysOfWeek' => [ $i ],
                                    //'time' => $serviceEventsData->$days,
                                    'description' => $weekdays,
                                ); 
                            }
                            
                            $returnarray[] = $array;
                        }
                        
                            $providerSchedulingDate = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId])->where(["day" => $days])->where(["service_id" => $service_id])->get();
                            foreach($providerSchedulingDate as $val){
                                 $times = $val->time;
                
                                if($times != "," && $times != NULL){
                                $day1Result = explode(",", $times);
                                $weekdays='<input type="hidden" name="days" id="days" value=""><input type="hidden" name="serviceIDs" id="serviceIDs" value="'.$service_id.'">';
                                foreach ($day1Result as $key => $value) {
                                    $startEnd = explode('-',$value);


                                    $weekdays .=  '<div class="multi-field form-group">
                                         <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[0])).'"  style="float: left;margin-right:5px;" >
                                         <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[1])).'" style="float: left;">
                                     
                                         <button type="button" class="btn btn-danger remove-field">☓</button>
                                         <button type="button" class="add-field btn btn-info">Add Field</button>
                                         <div class="clearfix"></div>
                                         
                                       </div>';
                                }

                                    
                               }
                               
                               else { 
                                    $weekdays = '<input type="hidden" name="serviceIDs" id="serviceIDs" value="'.$service_id.'"><div class="multi-field form-group">
                                     <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value=""  style="float: left;margin-right:5px;" >
                                
                                     <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="" style="float: left;">
                                 
                                     <button type="button" class="btn btn-danger remove-field">☓</button>
                                     <button type="button" class="add-field btn btn-info">Add Field</button>
                                     <div class="clearfix"></div>
                                     
                                     
                                   </div>';
                              }
                              if($times !=''){
                                $array = array(
                                    'title' => $val->time,
                                    //'daysOfWeek' => [ $i ],
                                    'start' => $val->date,
                                    'description' => $weekdays,
                                );
                            } else {
                                $weekdays='<input type="hidden" name="days" id="days" value=""><input type="hidden" name="serviceIDs" id="serviceIDs" value="">';
                                $weekdays .= '<div class="multi-field form-group">
                                     <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value=""  style="float: left;margin-right:5px;" >
                                
                                     <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="" style="float: left;">
                                 
                                     <button type="button" class="btn btn-danger remove-field">☓</button>
                                     <button type="button" class="add-field btn btn-info">Add Field</button>
                                     <div class="clearfix"></div>
                                     
                                     
                                   </div>';
                                $array = array(
                                    'title' => 'Closed',
                                    //'daysOfWeek' => [ $i ],
                                    'start' => $val->date,
                                    'description' => $weekdays,
                                );
                            }
                                $returnarray[] = $array;
                            }
                        //}
                    
                //}

               // echo '<pre>';print_r($returnarray);exit();
                
            }
        } elseif($providerSchedulingDateCnt != 0){
                $returnarray = array();
                for($i=1;$i<8;$i++){
                    $days = 'day'.$i;

                     
                        
                    $providerSchedulingDate = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId])->where(["day" => $days])->where(["service_id" => $service_id])->get();
                    foreach($providerSchedulingDate as $val){
                        $times = $val->time;
             
                        if($times != "," && $times != NULL){
                        $day1Result = explode(",", $times);
                        $weekdays='<input type="hidden" name="days" id="days" value=""><input type="hidden" name="serviceIDs" id="serviceIDs" value="">';
                        foreach ($day1Result as $key => $value) {
                            $startEnd = explode('-',$value);


                            $weekdays .=  '<div class="multi-field form-group">
                                 <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[0])).'"  style="float: left;margin-right:5px;" >
                                 <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[1])).'" style="float: left;">
                             
                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                 <div class="clearfix"></div>
                                 
                               </div>';
                        }

                       }
                       
                       else { 
                            $weekdays = '<div class="multi-field form-group">
                             <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value=""  style="float: left;margin-right:5px;" >
                        
                             <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="" style="float: left;">
                         
                             <button type="button" class="btn btn-danger remove-field">☓</button>
                             <button type="button" class="add-field btn btn-info">Add Field</button>
                             <div class="clearfix"></div>
                             
                             
                           </div>';
                      } 
                      if($times !=''){
                        $array = array(
                            'title' => $val->time,
                            //'daysOfWeek' => [ $i ],
                            'start' => $val->date,
                            'description' => $weekdays,
                        );
                        
                    }  else {
                        $weekdays='<input type="hidden" name="days" id="days" value=""><input type="hidden" name="serviceIDs" id="serviceIDs" value="">';
                        $weekdays .= '<div class="multi-field form-group">
                             <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value=""  style="float: left;margin-right:5px;" >
                        
                             <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="" style="float: left;">
                         
                             <button type="button" class="btn btn-danger remove-field">☓</button>
                             <button type="button" class="add-field btn btn-info">Add Field</button>
                             <div class="clearfix"></div>
                             
                             
                           </div>';
                        $array = array(
                            'title' => 'Closed',
                            //'daysOfWeek' => [ $i ],
                            'start' => $val->date,
                            'description' => $weekdays,
                        );
                    }
                    $returnarray[] = $array;
                    }
                       
                    
                }
            } else {
                $providerScheduling = DB::table('provider_scheduling')->where(["trainer_id" => $trainerId])->first();
            
                $weekdays ='';
                if(isset($providerScheduling)){
                    $returnarray = array();
                    for($i=1;$i<8;$i++){
                        $days = 'day'.$i;

                         $times = $providerScheduling->$days;
                         
                            if($times != "," && $times != NULL){
                            $day1Result = explode(",", $times);
                            $weekdays='<input type="hidden" name="days" id="days" value=""><input type="hidden" name="serviceIDs" id="serviceIDs" value="">';
                            foreach ($day1Result as $key => $value) {
                                $startEnd = explode('-',$value);


                                $weekdays .=  '<div class="multi-field form-group">
                                     <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[0])).'"  style="float: left;margin-right:5px;" >
                                     <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[1])).'" style="float: left;">
                                 
                                     <button type="button" class="btn btn-danger remove-field">☓</button>
                                     <button type="button" class="add-field btn btn-info">Add Field</button>
                                     <div class="clearfix"></div>
                                     
                                   </div>';
                            }

                                
                           }
                           
                           else { 
                                $weekdays = '<div class="multi-field form-group">
                                 <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value=""  style="float: left;margin-right:5px;" >
                            
                                 <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="" style="float: left;">
                             
                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                 <div class="clearfix"></div>
                                 
                                 
                               </div>';
                          } 
                        if($times !=''){
                            //$providerSchedulingDate = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId])->where(["day" => $days])->count();
                            //if($providerSchedulingDate == 0){
                                if($i==7){
                                    $array = array(
                                        'title' => $providerScheduling->$days,
                                        'daysOfWeek' => [ 0 ],
                                        //'time' => $serviceEventsData->$days,
                                        'description' => $weekdays,
                                    );
                                } else {
                                    $array = array(
                                        'title' => $providerScheduling->$days,
                                        'daysOfWeek' => [ $i ],
                                        //'time' => $serviceEventsData->$days,
                                        'description' => $weekdays,
                                    );
                                }
                                
                                $returnarray[] = $array;
                            }
                            
                                $providerSchedulingDate = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId])->where(["day" => $days])->get();
                                foreach($providerSchedulingDate as $val){
                                    $times = $val->time;
                         
                                    if($times != "," && $times != NULL){
                                    $day1Result = explode(",", $times);
                                    $weekdays='<input type="hidden" name="days" id="days" value=""><input type="hidden" name="serviceIDs" id="serviceIDs" value="">';
                                    foreach ($day1Result as $key => $value) {
                                        $startEnd = explode('-',$value);


                                        $weekdays .=  '<div class="multi-field form-group">
                                             <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[0])).'"  style="float: left;margin-right:5px;" >
                                             <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="'.date("H:i", strtotime($startEnd[1])).'" style="float: left;">
                                         
                                             <button type="button" class="btn btn-danger remove-field">☓</button>
                                             <button type="button" class="add-field btn btn-info">Add Field</button>
                                             <div class="clearfix"></div>
                                             
                                           </div>';
                                    }

                                        
                                   }
                                   
                                   else { 
                                        $weekdays = '<div class="multi-field form-group">
                                         <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value=""  style="float: left;margin-right:5px;" >
                                    
                                         <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="" style="float: left;">
                                     
                                         <button type="button" class="btn btn-danger remove-field">☓</button>
                                         <button type="button" class="add-field btn btn-info">Add Field</button>
                                         <div class="clearfix"></div>
                                         
                                         
                                       </div>';
                                  } 
                                  if($times !=''){
                                    $array = array(
                                        'title' => $val->time,
                                        //'daysOfWeek' => [ $i ],
                                        'start' => $val->date,
                                        'description' => $weekdays,
                                    );
                                    
                                } else {
                                    $weekdays='<input type="hidden" name="days" id="days" value=""><input type="hidden" name="serviceIDs" id="serviceIDs" value="">';
                                    $weekdays .= '<div class="multi-field form-group">
                                         <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value=""  style="float: left;margin-right:5px;" >
                                    
                                         <input type="time" name="day'.$i.'" class="form-control w-25 p-3" value="" style="float: left;">
                                     
                                         <button type="button" class="btn btn-danger remove-field">☓</button>
                                         <button type="button" class="add-field btn btn-info">Add Field</button>
                                         <div class="clearfix"></div>
                                         
                                         
                                       </div>';
                                    $array = array(
                                        'title' => 'Closed',
                                        //'daysOfWeek' => [ $i ],
                                        'start' => $val->date,
                                        'description' => $weekdays,
                                    );
                                }
                                $returnarray[] = $array;
                                }
                            //}
                            
                        //}

                        //echo '<pre>';print_r($returnarray);exit();
                        
                    }
            } else {
                $returnarray = array();
            }
        }
        return view('front.trainer.services.add-service',["eventData" => json_encode($returnarray),"formats" => $formats, 'trainerService' => $service, 'services' => $services]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = TrainerServices::findOrFail(base64_decode($id));
        $isOrderAvailable = Orders::where("service_id", base64_decode($id))->first();
        //dd($isOrderAvailable);
        //if($service->is_featured == "yes"){
           // Session::flash('error', "Sorry! Featured service can not be deleted.");
           // return redirect()->back();
        //}else 
        if($isOrderAvailable){
            Session::flash('error', "Sorry! This service can not be deleted as it has bookings.");
            return redirect()->back();
        }else{
            if($service->delete()){
                Session::flash('message', "Service deleted successfully!");
                return redirect()->back();
            }
        }
        
    }

    public function statusChange($id){
        $statusdata = TrainerServices::find($id);
        $response = [];
        if ($statusdata->status){
            //check if service is featured
            if($statusdata->is_featured == "yes"){
                $response = ['status' => false, "Message" => 'Sorry! featured service can not be inactive.', "data" => []];
                return $response;
            }else{
                $statusdata->status = 0; 
            }    
        }else{
            $statusdata->status = 1; 
        }
        if($statusdata->save()){
        $response = ['status' => true, "Message" => 'Status changed successfully!', "data" => []];
        return $response;
        }
        
    }

    public function featuredChange($id) {  
        $statusdata = TrainerServices::find($id);
        $response = [];

        if (isset($statusdata) && isset($statusdata->is_featured)) {
            if ($statusdata->is_featured == 'yes') {

                $isFeaturedService = TrainerServices::where([
                    "trainer_id" => Auth::guard('front_auth')->user()->id,
                    "is_featured" => "yes",
                    "status" => 1
                ])->where('id', '!=' , $statusdata->id)->first();
    
                if($isFeaturedService){
                    $statusdata->is_featured = 'no';
                }else{
                    $response = ['status' => false, "Message" => 'Sorry! Atleast one service should be featured.', "data" => []];
                    return $response;
                }
    
            } else {
                if ($statusdata->status == 1) {
                   
                    $isFeaturedService = TrainerServices::where([
                        "trainer_id" => Auth::guard('front_auth')->user()->id,
                        "is_featured" => "yes",
                        "status" => 1
                     ])->where('id', '!=' , $statusdata->id)->first();
                    //dd($isFeaturedServices);
                    if($isFeaturedService){
                        $isFeaturedService->is_featured = "no"; 
                        $isFeaturedService->save();
                        $statusdata->is_featured = "yes";
                     }else{
                        $statusdata->is_featured = "yes"; 
                     }
                }
                else{
                   $response = ['status' => false, "Message" => 'Inactive service can not be featured!', "data" => []];
                   return $response;
                }
            }
          
             $statusdata->save();
             $response = ['status' => true, "Message" => 'Service updated successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    public function getServiceDetails($id){
        $service = TrainerServices::where(["id" => $id])->with(['trainer'])->first();
        //dd($service);
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
              //$adminFees = $service->price*($adminFeesPercent/100);
              $adminFees = 0;

        $response = [];
        
        if($service){
            $data = '<div id="content" class="card rounded-0 p-3 bg-light shadow-none mt-lg-2">';
            $data .= '<div class="card-image no-curve">';

            if(!empty($service->image)){
                $data .= '<img src="'.asset("front/images/services/".$service->image).'" alt="'.$service->name.'">';
            }else{
                $data .= '<img src="'.asset("images/Expert_01.jpg").'" alt="'.$service->name.'">'; 
            }   
             
            $data .= '<span class="badge badge-danger">Book</span></div>';
            $data .= '<div class="card-body pb-3"><h4 class="text-uppercase h3 mb-2">'.$service->name.'</h4>';
            $data .= '<h5 class="status mb-1 trainerservices-status">'.$service->format.'</h5>';
            $data .= '<p>'.$service->message.'</p>';
            $data .=  '<h5 class="location">'.$service->trainer->address_1;
            $data .= ' '.$service->trainer->city;
            $data .=  ' '.$service->trainer->state;
            $data .=  ' '.$service->trainer->country.'</h5>';
            if($service->is_recurring == "yes"){
                $isRecurring = "yes";
                // if($service->price_weekly){
                //     $data .= '<h5 class="h4 text-danger mb-0">$'.$service->price_weekly.' USD Weekly</h5>';  
                // }
                if($service->price_monthly){
                        $data .= '<h5 class="h4 text-danger mb-0">$'.$service->price_monthly.' USD (Monthly Recurring)</h5>';  
                }
                
                if($service->price_monthly){
                    $recurringOptions = '<input type="hidden" name="recurring_options" class="form-control" id="recurring_options" value="'.$service->price_monthly.'"  />';       
                }
            }else{
                $isRecurring = "no";
                $recurringOptions = null;
                $data .= '<h5 class="h3 text-danger mb-0">$'.$service->price.' USD</h5>';
            }
            
           
           // $data .= '<h5 class="h4 text-danger mb-0">Admin Fees: $'.$adminFees.' USD</h5>';
            if($total_discount){
            $data .= '<h5 class="h4 text-danger mb-0">Referral Discount: $'.$total_discount.' USD</h5>';
            //$data .= '<h5 class="h4 text-danger mb-0">Total Amount: $'.($service->price + $adminFees - $total_discount).' USD</h5>';
            }
            
            $data .= '<div class="ajax-loader"><img  src="'.asset("front/images/loader.gif").'" alt= "loader"/></div>';
            $data .= '</div></div>';
            
            $response = ['status' => true, "data" => $data, "isRecurring" => $isRecurring, "recurringData" => $recurringOptions]; 
        }else{
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }
}
