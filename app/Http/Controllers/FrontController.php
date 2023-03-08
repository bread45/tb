<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Users\Entities\FrontUsers;
use Modules\Testimonials\Entities\Testimonials;
use Modules\CMSPages\Entities\CMSPages;
use App\Tips;
use App\Ratings;
use App\User;
use App\TrainerServices;
use App\Services;
use App\StripeAccounts;
use App\TrainerPhoto;
use App\Resource;
use App\ResourceCategory;
use App\TrainerEvent;
use Modules\Contactus\Entities\ContactUs;
use Validator;
use Mail,
    Redirect;
use Stripe;
use Route;
use URL;
use Cookie;
use Session,
    DB;
use Image;
use Carbon\Carbon;
use Modules\Blogs\Entities\Blogs;
use Modules\Blogs\Entities\BlogCategories;
use Modules\Locations\Entities\Locations;
use Modules\Advertisement\Entities\Advertisement;
use Modules\Advertisement\Entities\AdvertisementDetails;
use App\RecommendedProviders;
use App\States;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

        require 'mail/PHPMailer/Exception.php';
        require 'mail/PHPMailer/PHPMailer.php';
        require 'mail/PHPMailer/SMTP.php';
        require 'mail/vendor/autoload.php';

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
        session()->put('book-service-url',false);
        session()->put('provider-resource-url',false);
        session()->put('resource-detail-url',false);
        session()->put('resource-url',false);               
        $tips = cache()->remember('Tips', 60*60*24, function(){
            return Tips::All();
        });
        $aboutus_page = cache()->remember('CMSPages', 60*60*24, function(){
            return CMSPages::where('slug', 'about-us')->first();
        });
        $Ratings = cache()->remember('Ratings', 60*60*24, function(){
            return Ratings::with('orders.users')->first();
        });
        $featuredTrainerlist = cache()->remember('FrontUsers', 60*60*24, function(){
            return FrontUsers::where('user_role', 'trainer')->where('status','active')->where('is_feature', '=', 1)->has('services')->limit(3)->get();
        });
    // Featured Providers based on current location
    if(isset($_COOKIE['locations']) && $_COOKIE['locations'] != ''){
        $currentLocation = $_COOKIE['locations'];
        $currentLocationLat = $_COOKIE['locationsLat'];
        $currentLocationLng = $_COOKIE['locationsLng'];
        $featuredTrainerlist = DB::table('trainer_services')
        ->rightJoin('services', 'trainer_services.service_id', '=', 'services.id')
        ->rightJoin('front_users', 'front_users.id', '=', 'trainer_services.trainer_id')
        ->selectRaw('services.*, front_users.*, (3959 * acos(cos(radians(' . $currentLocationLat . ')) * cos(radians(front_users.map_latitude)) *
        cos(radians(front_users.map_longitude) - radians(' . $currentLocationLng . ')) +
        sin(radians(' . $currentLocationLat . ')) * sin(radians(front_users.map_latitude))))
        AS distance')
        ->where('front_users.user_role', 'trainer')
        ->where('front_users.status','active')
        ->where('services.status','active')
        ->where('front_users.is_feature', '=', 1)
        ->orderBy('distance', 'ASC')
        ->groupBy('front_users.id')
        ->get();
    }
    else {
    $featuredTrainerlist = DB::table('trainer_services')
        ->rightJoin('services', 'trainer_services.service_id', '=', 'services.id')
        ->rightJoin('front_users', 'front_users.id', '=', 'trainer_services.trainer_id')
        ->where('front_users.user_role', 'trainer')
        ->where('front_users.status','active')
        ->where('services.status','active')
        ->where('front_users.is_feature', '=', 1)
        ->groupBy('front_users.id')
        ->get();
    }
    date_default_timezone_set('America/New_York');
    $currentTimeStamp = Carbon::now()->toDateTimeString();
    $futureEvents = TrainerEvent::where('event_start_datetime','>', $currentTimeStamp)
        ->join('front_users', 'front_users.id', '=', 'trainer_events.trainer_id')
        ->where('trainer_events.status', '=', 1)
        ->where('front_users.status', '=', 'active')
        ->select('trainer_events.*', 'front_users.photo');

    $futureEvents->leftJoin('provider_orders', function($query){
        $query->on('trainer_events.trainer_id', '=', 'provider_orders.trainer_id')
                ->whereRaw('provider_orders.id IN (select MAX(a2.ID) from provider_orders as a2 join trainer_events as u2 on u2.trainer_id = a2.trainer_id group by u2.trainer_id)');
        });

    $futureEvents->where(function ($query) {
        $query->where('front_users.is_subscription','=', 0)
              ->orWhere('front_users.is_subscription','=', 1)
              ->where('provider_orders.subscription_status','!=', 'cancelled');
    });         
    $futureEvents->orderBy('trainer_events.event_start_datetime', 'ASC');
    $futureEvents = $futureEvents->get();

    $latestResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')
        ->join('front_users','front_users.id','=','resource.trainer_id')
        ->where('front_users.status','active')
        ->where(function($query) {
        $query->where('front_users.is_subscription', '0')
              ->orwhere('front_users.is_payment', '1');
    })
    ->orderBy('id', 'DESC')->limit(3)->get();

    $testimonials = DB::Table('testimonials')->where('status', '=', 'active')->orderBy('id', 'DESC')->get();
   
    $services = Services::where('status', '=', 'active')->orderBy('weight', 'ASC')->get();

    $trainerList = FrontUsers::where('user_role', 'trainer')->where('status','active')->with(['services', 'orders.Ratting','ratings'])->has('services')->get();

        $trainerList->transform(function ($v) {
            $r = 0;
            if (isset($v->ratings)) {
                $retingdata = $v->ratings->transform(function ($v1) use($r) {
                    if (isset($v1->rating)) {
                        return $v1->rating;
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
        $ipurl = 'http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR'];
        $ip_location_data = file_get_contents($ipurl);
        $ip_location_data = json_decode($ip_location_data);
//        }
        $lon = $ip_location_data->lon;
        $lat = $ip_location_data->lat;

        $circle_radius = 3959;
        $max_distance = 300;
        $locations = DB::select(
                        'SELECT * FROM
                    (SELECT id,name, latitude, longitude, (' . $circle_radius . ' * acos(cos(radians(' . $lat . ')) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(' . $lon . ')) +
                    sin(radians(' . $lat . ')) * sin(radians(latitude))))
                    AS distance
                    FROM locations) AS distances
                 WHERE distance < ' . $max_distance . '
                ORDER BY distance asc limit 1;
            ');
        $location_name = '';
        if (!empty($locations)) {
            foreach ($locations as $location) {
                $location_name = $location->name;
            }
        } 
        $location_name = $ip_location_data->city;
        $users = cache()->remember('front_auth', 60*60*24, function(){
            return Auth::guard('front_auth')->user();
        });
        $SquareupToken = array(); 
        if(!empty($users)){
//            $SquareupToken = SquareupToken::where('user_id', $users->id)->first();
    }
        return view('front.home', compact('users','location_name','tips', 'aboutus_page', 'featuredTrainerlist', 'trainerList','SquareupToken','services', 'futureEvents', 'latestResources', 'testimonials'));
    }

    public function exploreservices() {
        $tips = Tips::All();
        $aboutus_page = CMSPages::where('slug', 'about-us')->first();
        $Ratings = Ratings::with('orders.users')->first();
        return view('front.exploreservices', compact('tips', 'aboutus_page'));
    }

    public function exploreservicessearch(Request $request) {
        session()->put('book-service-url',false);
        session()->put('provider-resource-url',false);
        session()->put('resource-detail-url',false);
        session()->put('resource-url',false);
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else{
             $page = 1;
        }
        $ipurl = 'http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR'];
        $ip_location_data = file_get_contents($ipurl);
        $ip_location_data = json_decode($ip_location_data);
//        }
        $lon = $ip_location_data->lon;
        $lat = $ip_location_data->lat;
//        $lon = '-81.506';
//                $lat = '30.3511';
        $circle_radius = 3959;
        $max_distance = 300;
        $current_locations = DB::select(
                        'SELECT * FROM
                    (SELECT id,name,  latitude, longitude, (' . $circle_radius . ' * acos(cos(radians(' . $lat . ')) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(' . $lon . ')) +
                    sin(radians(' . $lat . ')) * sin(radians(latitude))))
                    AS distance
                    FROM locations) AS distances
                 WHERE distance < ' . $max_distance . '
                ORDER BY distance asc limit 1;
            ');
            $current_location_ids = array();
            $current_location_id = '';
            $current_location_name = '';
            if (!empty($current_locations)) {
                foreach ($current_locations as $location) {
                    $current_location_id = $location->id; 
                    $current_location_name = $location->name; 
                }
            }
            $current_location_name = $ip_location_data->city;
         $location_name = '';
        //if (isset($request->location) && !empty($request->location)) {
           
            if (isset($_GET['lat'])){
                $lon = $_GET['lng'];
                $lat = $_GET['lat'];
                $circle_radius = $_GET['radius'];
                
                $locations = DB::select("SELECT id,city,  map_latitude, map_longitude, state, SQRT(
                                    POW(69.1 * (map_latitude - ($lat)), 2) +
                                    POW(69.1 * (($lon) - map_longitude) * COS(map_latitude / 57.3), 2)) AS distance
                                FROM front_users HAVING distance < $circle_radius ORDER BY distance;");
                $location_ids = array();
                
                if (!empty($locations)) {
                    foreach ($locations as $location) {
                        $location_ids[] = $location->id; 
                        $location_name = $location->city; 
                    }
                }   
            } else {
                $location_name = $request->location;
            }

            if (isset($request->miles) && !empty($request->miles) && !empty($request->location)) {
                if(!empty($request->location)){
                    $location_mile = $request->location;
                }
                else {
                    $location_mile = "Boulder, CO";
                }
                $miles_locations = explode(',', $location_mile);
                $circle_radius = 3959;
                $zipCount = DB::table('front_users')->where(["zip_code" => $request->location])->count();
                
                $apiKey  = 'AIzaSyD0sBm7n3sKRiVvtBekP81GCR4r0cjmSDQ';
                $apiData = array(
                    'address' => $request->location,
                    'key' => $apiKey
                );

                // Location lat lng for miles
                $endURL = http_build_query($apiData, '', '&');
                $api     = "https://maps.googleapis.com/maps/api/geocode/json?".$endURL;
                $resp    = json_decode( file_get_contents( $api ), true );
                $milesLat    = $resp['results'][0]['geometry']['location']['lat'] ?? '';
                $milesLong   = $resp['results'][0]['geometry']['location']['lng'] ?? '';

                if(!empty($milesLat) && !empty($milesLong)){ 
                $miles_locations = DB::select(
                    'SELECT * FROM
                (SELECT id, business_name, city,  map_latitude, map_longitude, state, (' . $circle_radius . ' * acos(cos(radians(' . $milesLat . ')) * cos(radians(map_latitude)) *
                cos(radians(map_longitude) - radians(' . $milesLong . ')) +
                sin(radians(' . $milesLat . ')) * sin(radians(map_latitude))))
                AS distance
                FROM front_users) AS distances
             WHERE distance < ' . $request->miles . ';
        ');

    $mile_location_ids = array();

                    // $location_ids = array();
                    
                if (!empty($miles_locations)) {
                    foreach ($miles_locations as $mile_location) {
                        $mile_location_ids[] = $mile_location->id; 
                        //$location_name = $location->city; 
                    }
                }  
            } 

            
        }

        //} else {
        //    $location_name = '';
        //}    
      
        // print_r(trim($location_name));exit();
        $services = Services::where('status','active')->orderby('name','asc')->get();
        $TrainerServicesdataall = TrainerServices::All();

        $TrainerServicesdata = FrontUsers::select('front_users.*',DB::raw('GROUP_CONCAT(DISTINCT services.name) as service_name'), DB::raw('(SELECT count( rating ) FROM ratings WHERE ratings.trainer_id = front_users.id) as retting_count'), DB::raw('(SELECT AVG( rating ) FROM ratings WHERE ratings.trainer_id = front_users.id) as retting_avg' ))
                ->leftJoin('trainer_services', 'trainer_services.trainer_id', '=', 'front_users.id')
                ->leftJoin('services', 'services.id', '=', 'trainer_services.service_id')
                ->leftJoin('ratings', 'ratings.trainer_id', '=', 'front_users.id')
                ->distinct('front_users.id')
                ->orderBy('retting_count', 'desc') 
                ->orderBy('retting_avg', 'desc')
               ->orderBy('id', 'asc');
               
        $TrainerAcountdata = StripeAccounts::All();
        
        if(!empty($mile_location_ids)){
            $TrainerServicesdata->where(function ($query) use ($mile_location_ids) { 
                    foreach($mile_location_ids as $val)
                    {
                        
                        $query->orWhereRaw('FIND_IN_SET('.$val.',front_users.id)');
                    }
                });
        }
// keyword Search using selected occurences
        $keyword = $request->keyword;
        if($keyword != ''){
            $keywordOccurences = DB::Table('keyword_explore')->select('*')->Where(function ($query) use($keyword) {
                $query->orWhere('keywords', 'like',  '%' . $keyword .'%');
            })->get();
        if(isset($keywordOccurences) && count($keywordOccurences) != 0){
            $keywords = preg_split('/,/', $keywordOccurences[0]->keywords, -1, PREG_SPLIT_NO_EMPTY);
            $keywords = implode(',', $keywords);
            $occurencearray = explode(",", $keywords);
        }else{
            $occurencearray = [];
            $keyword = $request->keyword;
         }
        }
             if (isset($_GET['lat'])){
                //$location_id = implode(', ', $location_id);
                
               // $TrainerServicesdata->whereIn('front_users.id', array($location_id));
               if(!empty($location_ids)){
                    $TrainerServicesdata->where(function ($query) use ($location_ids) { 
                            foreach($location_ids as $val)
                            {
                                
                                $query->orWhereRaw('FIND_IN_SET('.$val.',front_users.id)');
                            }
                        });
                    if (isset($request->location) && !empty($request->location)) {
                         $locations = explode(',', $request->location);
                        
                        if(count($locations) == 1){
                           $cityCount = DB::table('front_users')->where(["city" => $request->location])->count();
                           $zipCount = DB::table('front_users')->where(["zip_code" => $request->location])->count();
                           if(strlen($request->location) == 2){
                                $stateCount = DB::table('front_users')->where(["state_code" => $request->location])->count();
                           } else {
                                $stateCount = DB::table('front_users')->where(["state" => $request->location])->count();
                           }
                           if($cityCount != 0){
                                $TrainerServicesdata->where('front_users.city', '=', $request->location);
                           }
                           else if($zipCount != 0){
                            if(!empty($request->keyword) && isset($request->keyword)){
                                $keyword = $request->keyword;
                                $location = $request->location;
                                $TrainerServicesdata->where(function ($query) use ($location) {
                                    $query->where('zip_code','=', $location);
                                })->where(function ($query) use ($keyword, $occurencearray) {
                            if(!(isset($occurencearray)) || count($occurencearray) < 1){
                                $keywordarray = explode(' ', $keyword);
                            }
                            else {
                                $keywordarray = $occurencearray;
                            }
                            if(!(isset($occurencearray)) || count($occurencearray) < 1){
        
                                $query->where(function ($subquery) use ($keywordarray) { 
                                    foreach($keywordarray as $key => $keyword){
                                      
                                      if($key == 0){
                                          $subquery->where('business_name', 'LIKE', '%' . $keyword . '%');
                                      }
                                      else {
                                          $subquery->where('business_name', 'LIKE', '%' . $keyword . '%');
                                      }
                                      }
                                  });
              
                                  $query->orWhere(function ($subquery) use ($keywordarray) {
                                    foreach($keywordarray as $key => $keyword){
                                      
                                      if($key == 0){
                                          $subquery->where('first_name', 'LIKE', '%' . $keyword . '%');
                                      }
                                      else {
                                          $subquery->where('first_name', 'LIKE', '%' . $keyword . '%');
                                      }
                                      }
                                  });
                                  
                                  $query->orWhere(function ($subquery) use ($keywordarray) {
                                    foreach($keywordarray as $key => $keyword){
                                      if($key == 0){
                                          $subquery->where('last_name', 'LIKE', '%' . $keyword . '%');
                                      }
                                      else {
                                          $subquery->where('last_name', 'LIKE', '%' . $keyword . '%');
                                      }
                                  }
                              });
                              $query->orWhere(function ($subquery) use ($keywordarray) {
                                    foreach($keywordarray as $key => $keyword){
                                      if($key == 0){
                                          $subquery->where('bio', 'LIKE', '%' . $keyword . '%');
                                      }
                                      else {
                                          $subquery->where('bio', 'LIKE', '%' . $keyword . '%');
                                      }
                                  }
                              });
                              $query->orWhere(function ($subquery) use ($keywordarray) {
                                    foreach($keywordarray as $key => $keyword){
                                      if($key == 0){
                                          $subquery->where('headline', 'LIKE', '%' . $keyword . '%');
                                      }
                                      else {
                                          $subquery->where('headline', 'LIKE', '%' . $keyword . '%');
                                      }
                                  }
                              });
                              $query->orWhere(function ($subquery) use ($keywordarray) {
                                    foreach($keywordarray as $key => $keyword){
                                      if($key == 0){
                                          $subquery->where('address_1', 'LIKE', '%' . $keyword . '%');
                                      }
                                      else {
                                          $subquery->where('address_1', 'LIKE', '%' . $keyword . '%');
                                      }
                                  }
                              });
                              $query->orWhere(function ($subquery) use ($keywordarray) {
                                    foreach($keywordarray as $key => $keyword){
                                      if($key == 0){
                                          $subquery->where('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                      }
                                      else {
                                          $subquery->where('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                      }
                                  }
                              });
        
                            }
                            else {
                                $query->where(function ($subquery) use ($keywordarray) { 
                              foreach($keywordarray as $key => $keyword){
                                
                                if($key == 0){
                                    $subquery->orWhere('business_name', 'LIKE', '%' . $keyword . '%');
                                }
                                else {
                                    $subquery->orWhere('business_name', 'LIKE', '%' . $keyword . '%');
                                }
                                }
                            });
        
                            $query->orWhere(function ($subquery) use ($keywordarray) {
                              foreach($keywordarray as $key => $keyword){
                                
                                if($key == 0){
                                    $subquery->orWhere('first_name', 'LIKE', '%' . $keyword . '%');
                                }
                                else {
                                    $subquery->orWhere('first_name', 'LIKE', '%' . $keyword . '%');
                                }
                                }
                            });
                            
                            $query->orWhere(function ($subquery) use ($keywordarray) {
                              foreach($keywordarray as $key => $keyword){
                                if($key == 0){
                                    $subquery->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
                                }
                                else {
                                    $subquery->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
                                }
                            }
                            });
                            $query->orWhere(function ($subquery) use ($keywordarray) {
                                  foreach($keywordarray as $key => $keyword){
                                    if($key == 0){
                                        $subquery->orWhere('bio', 'LIKE', '%' . $keyword . '%');
                                    }
                                    else {
                                        $subquery->orWhere('bio', 'LIKE', '%' . $keyword . '%');
                                    }
                                }
                            });
                            $query->orWhere(function ($subquery) use ($keywordarray) {
                                  foreach($keywordarray as $key => $keyword){
                                    if($key == 0){
                                        $subquery->orWhere('headline', 'LIKE', '%' . $keyword . '%');
                                    }
                                    else {
                                        $subquery->orWhere('headline', 'LIKE', '%' . $keyword . '%');
                                    }
                                }
                            });
                            $query->orWhere(function ($subquery) use ($keywordarray) {
                                  foreach($keywordarray as $key => $keyword){
                                    if($key == 0){
                                        $subquery->orWhere('address_1', 'LIKE', '%' . $keyword . '%');
                                    }
                                    else {
                                        $subquery->orWhere('address_1', 'LIKE', '%' . $keyword . '%');
                                    }
                                }
                            });
                            $query->orWhere(function ($subquery) use ($keywordarray) {
                                  foreach($keywordarray as $key => $keyword){
                                    if($key == 0){
                                        $subquery->orWhere('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                    }
                                    else {
                                        $subquery->orWhere('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                    }
                                }
                            });
                        }
                        });	
                        }else {	
                        $TrainerServicesdata->where('front_users.zip_code', '=', $request->location);	
                        }	
                        }	
                           else if($stateCount !=0){
                                if(strlen($request->location) == 2){
                                    $TrainerServicesdata->where('front_users.state_code', '=', $request->location);
                                } else {
                                    $TrainerServicesdata->where('front_users.state', '=', $request->location);
                                }
                            }

                         else {
                                $TrainerServicesdata->where('front_users.city', '=', $request->location);
                            }

                        } else {
                            if(!empty($locations[0])){
                            
                                $TrainerServicesdata->where('front_users.city', '=', $locations[0]);
                            }
                            if(!empty($locations[1])){
                                if(strlen(trim($locations[1])) == 2){
                                    $TrainerServicesdata->where('front_users.state_code', '=', trim($locations[1]));
                                } else {
                                    $TrainerServicesdata->where('front_users.state', '=', trim($locations[1]));
                                }
                            }
                        }
                    }
                } else {
                    $TrainerServicesdata->where('front_users.city', '=', '');
                }

             } else {
                if (isset($request->location) && !empty($request->location)) {
                     // condition for miles for outer regions
                     if (empty($mile_location_ids)) {
                    $locations = explode(',', $request->location);
                  if(count($locations) == 1){
                       $cityCount = DB::table('front_users')->where(["city" => $request->location])->count();
                       $zipCount = DB::table('front_users')->where(["zip_code" => $request->location])->count();
                       if(strlen($request->location) == 2){
                            $stateCount = DB::table('front_users')->where(["state_code" => $request->location])->count();
                        } else {
                            $stateCount = DB::table('front_users')->where(["state" => $request->location])->count();
                        }

                       if($cityCount != 0){
                            $TrainerServicesdata->where('front_users.city', '=', $request->location);
                       }
                        
                       else if($zipCount != 0){
                        if(!empty($request->keyword) && isset($request->keyword)){
                            $keyword = $request->keyword;
                            $location = $request->location;
                            $TrainerServicesdata->where(function ($query) use ($location) {
                                $query->where('zip_code','=', $location);
                           })->where(function ($query) use ($keyword, $occurencearray) {
                                if(!(isset($occurencearray)) || count($occurencearray) < 1){
                                    $keywordarray = explode(' ', $keyword);
                                }
                                else {
                                    $keywordarray = $occurencearray;
                                }
                                if(!(isset($occurencearray)) || count($occurencearray) < 1){
            
                                    $query->where(function ($subquery) use ($keywordarray) { 
                                        foreach($keywordarray as $key => $keyword){
                                          
                                          if($key == 0){
                                              $subquery->where('business_name', 'LIKE', '%' . $keyword . '%');
                                          }
                                          else {
                                              $subquery->where('business_name', 'LIKE', '%' . $keyword . '%');
                                          }
                                          }
                                      });
                  
                                      $query->orWhere(function ($subquery) use ($keywordarray) {
                                        foreach($keywordarray as $key => $keyword){
                                          
                                          if($key == 0){
                                              $subquery->where('first_name', 'LIKE', '%' . $keyword . '%');
                                          }
                                          else {
                                              $subquery->where('first_name', 'LIKE', '%' . $keyword . '%');
                                          }
                                          }
                                      });
                                      
                                      $query->orWhere(function ($subquery) use ($keywordarray) {
                                        foreach($keywordarray as $key => $keyword){
                                          if($key == 0){
                                              $subquery->where('last_name', 'LIKE', '%' . $keyword . '%');
                                          }
                                          else {
                                              $subquery->where('last_name', 'LIKE', '%' . $keyword . '%');
                                          }
                                      }
                                  });
                                  $query->orWhere(function ($subquery) use ($keywordarray) {
                                        foreach($keywordarray as $key => $keyword){
                                          if($key == 0){
                                              $subquery->where('bio', 'LIKE', '%' . $keyword . '%');
                                          }
                                          else {
                                              $subquery->where('bio', 'LIKE', '%' . $keyword . '%');
                                          }
                                      }
                                  });
                                  $query->orWhere(function ($subquery) use ($keywordarray) {
                                        foreach($keywordarray as $key => $keyword){
                                          if($key == 0){
                                              $subquery->where('headline', 'LIKE', '%' . $keyword . '%');
                                          }
                                          else {
                                              $subquery->where('headline', 'LIKE', '%' . $keyword . '%');
                                          }
                                      }
                                  });
                                  $query->orWhere(function ($subquery) use ($keywordarray) {
                                        foreach($keywordarray as $key => $keyword){
                                          if($key == 0){
                                              $subquery->where('address_1', 'LIKE', '%' . $keyword . '%');
                                          }
                                          else {
                                              $subquery->where('address_1', 'LIKE', '%' . $keyword . '%');
                                          }
                                      }
                                  });
                                  $query->orWhere(function ($subquery) use ($keywordarray) {
                                        foreach($keywordarray as $key => $keyword){
                                          if($key == 0){
                                              $subquery->where('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                          }
                                          else {
                                              $subquery->where('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                          }
                                      }
                                  });
            
                                }
                                else {
                                    $query->where(function ($subquery) use ($keywordarray) { 
                                  foreach($keywordarray as $key => $keyword){
                                    
                                    if($key == 0){
                                        $subquery->orWhere('business_name', 'LIKE', '%' . $keyword . '%');
                                    }
                                    else {
                                        $subquery->orWhere('business_name', 'LIKE', '%' . $keyword . '%');
                                    }
                                    }
                                });
            
                                $query->orWhere(function ($subquery) use ($keywordarray) {
                                  foreach($keywordarray as $key => $keyword){
                                    
                                    if($key == 0){
                                        $subquery->orWhere('first_name', 'LIKE', '%' . $keyword . '%');
                                    }
                                    else {
                                        $subquery->orWhere('first_name', 'LIKE', '%' . $keyword . '%');
                                    }
                                    }
                                });
                                
                                $query->orWhere(function ($subquery) use ($keywordarray) {
                                  foreach($keywordarray as $key => $keyword){
                                    if($key == 0){
                                        $subquery->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
                                    }
                                    else {
                                        $subquery->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
                                    }
                                }
                                });
                                $query->orWhere(function ($subquery) use ($keywordarray) {
                                      foreach($keywordarray as $key => $keyword){
                                        if($key == 0){
                                            $subquery->orWhere('bio', 'LIKE', '%' . $keyword . '%');
                                        }
                                        else {
                                            $subquery->orWhere('bio', 'LIKE', '%' . $keyword . '%');
                                        }
                                    }
                                });
                                $query->orWhere(function ($subquery) use ($keywordarray) {
                                      foreach($keywordarray as $key => $keyword){
                                        if($key == 0){
                                            $subquery->orWhere('headline', 'LIKE', '%' . $keyword . '%');
                                        }
                                        else {
                                            $subquery->orWhere('headline', 'LIKE', '%' . $keyword . '%');
                                        }
                                    }
                                });
                                $query->orWhere(function ($subquery) use ($keywordarray) {
                                      foreach($keywordarray as $key => $keyword){
                                        if($key == 0){
                                            $subquery->orWhere('address_1', 'LIKE', '%' . $keyword . '%');
                                        }
                                        else {
                                            $subquery->orWhere('address_1', 'LIKE', '%' . $keyword . '%');
                                        }
                                    }
                                });
                                $query->orWhere(function ($subquery) use ($keywordarray) {
                                      foreach($keywordarray as $key => $keyword){
                                        if($key == 0){
                                            $subquery->orWhere('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                        }
                                        else {
                                            $subquery->orWhere('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                        }
                                    }
                                });
                            }
                                });
                        }else {
                        $TrainerServicesdata->where('front_users.zip_code', '=', $request->location);
                         }
                        }

                        else if($stateCount !=0){
                            if(strlen($request->location) == 2){
                                $TrainerServicesdata->where('front_users.state_code', '=', $request->location);
                            } else {
                                $TrainerServicesdata->where('front_users.state', '=', $request->location);
                            }
                        }
                        
                        else {
                            $TrainerServicesdata->where('front_users.city', '=', $request->location);
                        }
                        
                    } else {
                        if(!empty($locations[0])){
                        
                            $TrainerServicesdata->where('front_users.city', '=', $locations[0]);
                        }

                        if(!empty($locations[1])){
                            if(strlen(trim($locations[1])) == 2){
                                $TrainerServicesdata->where('front_users.state_code', '=', trim($locations[1]));
                            } else {
                                $TrainerServicesdata->where('front_users.state', '=', trim($locations[1]));
                            }
                        }
                    }
                }
             }
            }
        //}

        if (isset($request->services) && !empty($request->services)) {
            $TrainerServicesdata->where('trainer_services.service_id', $request->services);
            
        } else {
            //$TrainerServicesdata->where('is_featured', 'yes');
            
        }
        // dd($TrainerServicesdata);
        if ($request->virtual_in_both == 'In Person')
		{
            $TrainerServicesdata->where('front_users.address1_virtual', 0);
        } 
		else if($request->virtual_in_both == 'Virtual Only')
		{
            // $TrainerServicesdata->where('front_users.address1_virtual', 1);
            $TrainerServicesdata->where(function ($query){
                $query->where('front_users.address1_virtual', 1)
                ->orWhere(function ($virtualQuery){
                    $virtualQuery->where('front_users.address1_virtual', 0)
                    ->where('trainer_services.format', '=', 'Virtual - Single Appointment')
                    ->orWhere('trainer_services.format', '=', 'Virtual - Group Appointment')
                    ->orWhere('trainer_services.format', '=', 'Virtual - Monthly Membership')
                    ->orWhere('trainer_services.format', '=', 'Virtual - Yearly Membership')
                    ->orWhere('trainer_services.format', '=', 'Virtual - Package Deal');
                });
            });

        }
        
        $TrainerServicesdata->groupBy('front_users.id');
        // if (isset($request->ratings) && !empty($request->ratings)) {
            
        // }
        if (isset($request->format) && !empty($request->format)) {
            $TrainerServicesdata->where('format','like', '%' . $request->format . '%');
        }
        if (isset($request->keyword) && !empty($request->keyword)) {
            $keyword = $request->keyword; 

             if($request->keyword_search != 1 && $request->keyword_search != 2){
                // Keyword Search with multiple condition
                $TrainerServicesdata->where(function ($query) use ($keyword, $occurencearray) { 
                    if(!(isset($occurencearray)) || count($occurencearray) < 1){
                        $keywordarray = explode(' ', $keyword);
                    }
                    else {
                        $keywordarray = $occurencearray;
                    }
                    if(!(isset($occurencearray)) || count($occurencearray) < 1){

                        $query->where(function ($subquery) use ($keywordarray) { 
                            foreach($keywordarray as $key => $keyword){
                              
                              if($key == 0){
                                  $subquery->where('business_name', 'LIKE', '%' . $keyword . '%');
                              }
                              else {
                                  $subquery->where('business_name', 'LIKE', '%' . $keyword . '%');
                              }
                              }
                          });
      
                          $query->orWhere(function ($subquery) use ($keywordarray) {
                            foreach($keywordarray as $key => $keyword){
                              
                              if($key == 0){
                                  $subquery->where('first_name', 'LIKE', '%' . $keyword . '%');
                              }
                              else {
                                  $subquery->where('first_name', 'LIKE', '%' . $keyword . '%');
                              }
                              }
                          });
                          
                          $query->orWhere(function ($subquery) use ($keywordarray) {
                            foreach($keywordarray as $key => $keyword){
                              if($key == 0){
                                  $subquery->where('last_name', 'LIKE', '%' . $keyword . '%');
                              }
                              else {
                                  $subquery->where('last_name', 'LIKE', '%' . $keyword . '%');
                              }
                          }
                      });
                      $query->orWhere(function ($subquery) use ($keywordarray) {
                            foreach($keywordarray as $key => $keyword){
                              if($key == 0){
                                  $subquery->where('bio', 'LIKE', '%' . $keyword . '%');
                              }
                              else {
                                  $subquery->where('bio', 'LIKE', '%' . $keyword . '%');
                              }
                          }
                      });
                      $query->orWhere(function ($subquery) use ($keywordarray) {
                            foreach($keywordarray as $key => $keyword){
                              if($key == 0){
                                  $subquery->where('headline', 'LIKE', '%' . $keyword . '%');
                              }
                              else {
                                  $subquery->where('headline', 'LIKE', '%' . $keyword . '%');
                              }
                          }
                      });
                      $query->orWhere(function ($subquery) use ($keywordarray) {
                            foreach($keywordarray as $key => $keyword){
                              if($key == 0){
                                  $subquery->where('address_1', 'LIKE', '%' . $keyword . '%');
                              }
                              else {
                                  $subquery->where('address_1', 'LIKE', '%' . $keyword . '%');
                              }
                          }
                      });
                      $query->orWhere(function ($subquery) use ($keywordarray) {
                            foreach($keywordarray as $key => $keyword){
                              if($key == 0){
                                  $subquery->where('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                              }
                              else {
                                  $subquery->where('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                              }
                          }
                      });

                    }
                    else {
                        $query->where(function ($subquery) use ($keywordarray) { 
                      foreach($keywordarray as $key => $keyword){
                        
                        if($key == 0){
                            $subquery->orWhere('business_name', 'LIKE', '%' . $keyword . '%');
                        }
                        else {
                            $subquery->orWhere('business_name', 'LIKE', '%' . $keyword . '%');
                        }
                        }
                    });

                    $query->orWhere(function ($subquery) use ($keywordarray) {
                      foreach($keywordarray as $key => $keyword){
                        
                        if($key == 0){
                            $subquery->orWhere('first_name', 'LIKE', '%' . $keyword . '%');
                        }
                        else {
                            $subquery->orWhere('first_name', 'LIKE', '%' . $keyword . '%');
                        }
                        }
                    });
                    
                    $query->orWhere(function ($subquery) use ($keywordarray) {
                      foreach($keywordarray as $key => $keyword){
                        if($key == 0){
                            $subquery->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
                        }
                        else {
                            $subquery->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
                        }
                    }
                    });
                    $query->orWhere(function ($subquery) use ($keywordarray) {
                          foreach($keywordarray as $key => $keyword){
                            if($key == 0){
                                $subquery->orWhere('bio', 'LIKE', '%' . $keyword . '%');
                            }
                            else {
                                $subquery->orWhere('bio', 'LIKE', '%' . $keyword . '%');
                            }
                        }
                    });
                    $query->orWhere(function ($subquery) use ($keywordarray) {
                          foreach($keywordarray as $key => $keyword){
                            if($key == 0){
                                $subquery->orWhere('headline', 'LIKE', '%' . $keyword . '%');
                            }
                            else {
                                $subquery->orWhere('headline', 'LIKE', '%' . $keyword . '%');
                            }
                        }
                    });
                    $query->orWhere(function ($subquery) use ($keywordarray) {
                          foreach($keywordarray as $key => $keyword){
                            if($key == 0){
                                $subquery->orWhere('address_1', 'LIKE', '%' . $keyword . '%');
                            }
                            else {
                                $subquery->orWhere('address_1', 'LIKE', '%' . $keyword . '%');
                            }
                        }
                    });
                    $query->orWhere(function ($subquery) use ($keywordarray) {
                          foreach($keywordarray as $key => $keyword){
                            if($key == 0){
                                $subquery->orWhere('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                            }
                            else {
                                $subquery->orWhere('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                            }
                        }
                    });
                }
                });
              // end of Keyword Search with multiple condition
            }
        }
        $TrainerServicesdata->where('front_users.status','active');
                $TrainerServicesdata->where('front_users.user_role','trainer');
                $TrainerServicesdata->where(function ($query) { 
                        $query->where('front_users.is_subscription','0');
                        //$query->orwhere('front_users.is_subscription','1');
                        $query->orwhere('front_users.is_payment','1');
                    }); 
        /*print_r($TrainerServicesdata->toSql());
dd( $TrainerServicesdata->getBindings() );exit();*/

        $TrainerServicesdata = $TrainerServicesdata->paginate(10, ['*'], 'page', $page)->appends(request()->all());

        /* Start By Ujas */
        $TrainerServicesdata->transform(function ($v) {
            $r = 0;
            $t = 1;
            if (isset($v->ratings)) {
                $retingdata = $v->ratings->transform(function ($v1) use($r) {
                    if (isset($v1->rating)) {
                        return $v1->rating;
                    }
                });
                $p = 0;
                foreach ($retingdata as $rdata) {
                    if ($rdata != '') {
                        $r += $rdata;
                        $p++;
                        $v->ratting_count = $t++;
                    }
                }
                if ($r != 0) {
                    $v->ratting = ($r / $p);
                } else {
                    if (!isset($v->ratting)) {
                        $v->ratting = 0;
                    }
                }
            }
            return $v;
        });

if(count($TrainerServicesdata) == 0 && (!empty($mile_location_ids))){
    $TrainerServicesdata1 = FrontUsers::select('front_users.*',DB::raw('GROUP_CONCAT(DISTINCT services.name) as service_name'), DB::raw('(SELECT count( rating ) FROM ratings WHERE ratings.trainer_id = front_users.id) as retting_count'), DB::raw('(SELECT AVG( rating ) FROM ratings WHERE ratings.trainer_id = front_users.id) as retting_avg'))
    ->leftJoin('trainer_services', 'trainer_services.trainer_id', '=', 'front_users.id')
    ->leftJoin('services', 'services.id', '=', 'trainer_services.service_id')
    ->leftJoin('ratings', 'ratings.trainer_id', '=', 'front_users.id')
    ->distinct('front_users.id')
    ->orderBy('retting_count', 'desc') 
    ->orderBy('retting_avg', 'desc')
    ->orderBy('id', 'asc');

            $TrainerAcountdata = StripeAccounts::All();
            
            if(!empty($mile_location_ids)){
                $TrainerServicesdata1->where(function ($query) use ($mile_location_ids) { 
                        foreach($mile_location_ids as $val)
                        {
                            
                            $query->orWhereRaw('FIND_IN_SET('.$val.',front_users.id)');
                        }
                    });
            }

            if(isset($milesLat) && isset($milesLong) && $milesLat == 'null' && $milesLong =='null'){

                $TrainerServicesdata1->Where('front_users.id', '=', '0' );
            }
            
            //if (isset($request->location) && !empty($request->location)) {

                        
                 //$TrainerServicesdata->where('locations.name', '=', trim($location_name));
                 if (isset($_GET['lat'])){
                    //$location_id = implode(', ', $location_id);
                    
                   // $TrainerServicesdata->whereIn('front_users.id', array($location_id));
                   if(!empty($location_ids)){
                        $TrainerServicesdata1->where(function ($query) use ($location_ids) { 
                                foreach($location_ids as $val)
                                {
                                    
                                    $query->orWhereRaw('FIND_IN_SET('.$val.',front_users.id)');
                                }
                            });
                        if (isset($request->location) && !empty($request->location)) {
                            $locations = explode(',', $request->location);
                            
                            if(count($locations) == 1){
                               $cityCount = DB::table('front_users')->where(["city" => $request->location])->count();
                               $zipCount = DB::table('front_users')->where(["zip_code" => $request->location])->count();
                               if(strlen($request->location) == 2){
                                    $stateCount = DB::table('front_users')->where(["state_code" => $request->location])->count();
                                } else {
                                    $stateCount = DB::table('front_users')->where(["state" => $request->location])->count();
                                }

                               if($cityCount != 0){
                                    $TrainerServicesdata1->where('front_users.city', '=', $request->location);
                               }
                                
                               else if($zipCount != 0){
                                if(!empty($request->keyword) && isset($request->keyword)){
                                    $keyword = $request->keyword;
                                    $location = $request->location;
                                    $TrainerServicesdata1->where(function ($query) use ($location) {
                                        $query->where('zip_code','=', $location);
                                     })->where(function ($query) use ($keyword, $occurencearray) {
                                        if(!(isset($occurencearray)) || count($occurencearray) < 1){
                                            $keywordarray = explode(' ', $keyword);
                                        }
                                        else {
                                            $keywordarray = $occurencearray;
                                        }
                                        if(!(isset($occurencearray)) || count($occurencearray) < 1){
                    
                                            $query->where(function ($subquery) use ($keywordarray) { 
                                                foreach($keywordarray as $key => $keyword){
                                                  
                                                  if($key == 0){
                                                      $subquery->where('business_name', 'LIKE', '%' . $keyword . '%');
                                                  }
                                                  else {
                                                      $subquery->where('business_name', 'LIKE', '%' . $keyword . '%');
                                                  }
                                                  }
                                              });
                          
                                              $query->orWhere(function ($subquery) use ($keywordarray) {
                                                foreach($keywordarray as $key => $keyword){
                                                  
                                                  if($key == 0){
                                                      $subquery->where('first_name', 'LIKE', '%' . $keyword . '%');
                                                  }
                                                  else {
                                                      $subquery->where('first_name', 'LIKE', '%' . $keyword . '%');
                                                  }
                                                  }
                                              });
                                              
                                              $query->orWhere(function ($subquery) use ($keywordarray) {
                                                foreach($keywordarray as $key => $keyword){
                                                  if($key == 0){
                                                      $subquery->where('last_name', 'LIKE', '%' . $keyword . '%');
                                                  }
                                                  else {
                                                      $subquery->where('last_name', 'LIKE', '%' . $keyword . '%');
                                                  }
                                              }
                                          });
                                          $query->orWhere(function ($subquery) use ($keywordarray) {
                                                foreach($keywordarray as $key => $keyword){
                                                  if($key == 0){
                                                      $subquery->where('bio', 'LIKE', '%' . $keyword . '%');
                                                  }
                                                  else {
                                                      $subquery->where('bio', 'LIKE', '%' . $keyword . '%');
                                                  }
                                              }
                                          });
                                          $query->orWhere(function ($subquery) use ($keywordarray) {
                                                foreach($keywordarray as $key => $keyword){
                                                  if($key == 0){
                                                      $subquery->where('headline', 'LIKE', '%' . $keyword . '%');
                                                  }
                                                  else {
                                                      $subquery->where('headline', 'LIKE', '%' . $keyword . '%');
                                                  }
                                              }
                                          });
                                          $query->orWhere(function ($subquery) use ($keywordarray) {
                                                foreach($keywordarray as $key => $keyword){
                                                  if($key == 0){
                                                      $subquery->where('address_1', 'LIKE', '%' . $keyword . '%');
                                                  }
                                                  else {
                                                      $subquery->where('address_1', 'LIKE', '%' . $keyword . '%');
                                                  }
                                              }
                                          });
                                          $query->orWhere(function ($subquery) use ($keywordarray) {
                                                foreach($keywordarray as $key => $keyword){
                                                  if($key == 0){
                                                      $subquery->where('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                                  }
                                                  else {
                                                      $subquery->where('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                                  }
                                              }
                                          });
                    
                                        }
                                        else {
                                            $query->where(function ($subquery) use ($keywordarray) { 
                                          foreach($keywordarray as $key => $keyword){
                                            
                                            if($key == 0){
                                                $subquery->orWhere('business_name', 'LIKE', '%' . $keyword . '%');
                                            }
                                            else {
                                                $subquery->orWhere('business_name', 'LIKE', '%' . $keyword . '%');
                                            }
                                            }
                                        });
                    
                                        $query->orWhere(function ($subquery) use ($keywordarray) {
                                          foreach($keywordarray as $key => $keyword){
                                            
                                            if($key == 0){
                                                $subquery->orWhere('first_name', 'LIKE', '%' . $keyword . '%');
                                            }
                                            else {
                                                $subquery->orWhere('first_name', 'LIKE', '%' . $keyword . '%');
                                            }
                                            }
                                        });
                                        
                                        $query->orWhere(function ($subquery) use ($keywordarray) {
                                          foreach($keywordarray as $key => $keyword){
                                            if($key == 0){
                                                $subquery->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
                                            }
                                            else {
                                                $subquery->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
                                            }
                                        }
                                        });
                                        $query->orWhere(function ($subquery) use ($keywordarray) {
                                              foreach($keywordarray as $key => $keyword){
                                                if($key == 0){
                                                    $subquery->orWhere('bio', 'LIKE', '%' . $keyword . '%');
                                                }
                                                else {
                                                    $subquery->orWhere('bio', 'LIKE', '%' . $keyword . '%');
                                                }
                                            }
                                        });
                                        $query->orWhere(function ($subquery) use ($keywordarray) {
                                              foreach($keywordarray as $key => $keyword){
                                                if($key == 0){
                                                    $subquery->orWhere('headline', 'LIKE', '%' . $keyword . '%');
                                                }
                                                else {
                                                    $subquery->orWhere('headline', 'LIKE', '%' . $keyword . '%');
                                                }
                                            }
                                        });
                                        $query->orWhere(function ($subquery) use ($keywordarray) {
                                              foreach($keywordarray as $key => $keyword){
                                                if($key == 0){
                                                    $subquery->orWhere('address_1', 'LIKE', '%' . $keyword . '%');
                                                }
                                                else {
                                                    $subquery->orWhere('address_1', 'LIKE', '%' . $keyword . '%');
                                                }
                                            }
                                        });
                                        $query->orWhere(function ($subquery) use ($keywordarray) {
                                              foreach($keywordarray as $key => $keyword){
                                                if($key == 0){
                                                    $subquery->orWhere('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                                }
                                                else {
                                                    $subquery->orWhere('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                                }
                                            }
                                        });
                                    }
                                     });
                                    
                                 }else {
                                $TrainerServicesdata1->where('front_users.zip_code', '=', $request->location);
                                 }
                                }

                                else if($stateCount !=0){
                                    if(strlen($request->location) == 2){
                                        $TrainerServicesdata1->where('front_users.state_code', '=', $request->location);
                                    } else {
                                        $TrainerServicesdata1->where('front_users.state', '=', $request->location);
                                    }
                                } 
                                else {
                                    $TrainerServicesdata1->where('front_users.city', '=', $request->location);
                                }
                            }
                             else {
                                if(!empty($locations[0])){
                                
                                    $TrainerServicesdata1->where('front_users.city', '=', $locations[0]);
                                }
                                if(!empty($locations[1])){
                                    if(strlen(trim($locations[1])) == 2){
                                        $TrainerServicesdata1->where('front_users.state_code', '=', trim($locations[1]));
                                    } else {
                                        $TrainerServicesdata1->where('front_users.state', '=', trim($locations[1]));
                                    }
                                }
                            }
                        }
                    } else {
                        $TrainerServicesdata1->where('front_users.city', '=', '');
                    }
                 } else {
                    if (isset($request->location) && !empty($request->location)) {
                    if (empty($mile_location_ids)) {
                        $locations = explode(',', $request->location);
                        if(count($locations) == 1){
                           $cityCount = DB::table('front_users')->where(["city" => $request->location])->count();
                           $zipCount = DB::table('front_users')->where(["zip_code" => $request->location])->count();
                           if(strlen($request->location) == 2){
                                    $stateCount = DB::table('front_users')->where(["state_code" => $request->location])->count();
                                } else {
                                    $stateCount = DB::table('front_users')->where(["state" => $request->location])->count();
                                }
                           if($cityCount != 0){
                                $TrainerServicesdata1->where('front_users.city', '=', $request->location);
                           }
                           else if($zipCount != 0){
                            if(!empty($request->keyword) && isset($request->keyword)){
                                $keyword = $request->keyword;
                                $location = $request->location;
                                $TrainerServicesdata1->where(function ($query) use ($location) {
                                    $query->where('zip_code','=', $location);
                                 })->where(function ($query) use ($keyword, $occurencearray) {
                                    if(!(isset($occurencearray)) || count($occurencearray) < 1){
                                        $keywordarray = explode(' ', $keyword);
                                    }
                                    else {
                                        $keywordarray = $occurencearray;
                                    }
                                    if(!(isset($occurencearray)) || count($occurencearray) < 1){
                
                                        $query->where(function ($subquery) use ($keywordarray) { 
                                            foreach($keywordarray as $key => $keyword){
                                              
                                              if($key == 0){
                                                  $subquery->where('business_name', 'LIKE', '%' . $keyword . '%');
                                              }
                                              else {
                                                  $subquery->where('business_name', 'LIKE', '%' . $keyword . '%');
                                              }
                                              }
                                          });
                      
                                          $query->orWhere(function ($subquery) use ($keywordarray) {
                                            foreach($keywordarray as $key => $keyword){
                                              
                                              if($key == 0){
                                                  $subquery->where('first_name', 'LIKE', '%' . $keyword . '%');
                                              }
                                              else {
                                                  $subquery->where('first_name', 'LIKE', '%' . $keyword . '%');
                                              }
                                              }
                                          });
                                          
                                          $query->orWhere(function ($subquery) use ($keywordarray) {
                                            foreach($keywordarray as $key => $keyword){
                                              if($key == 0){
                                                  $subquery->where('last_name', 'LIKE', '%' . $keyword . '%');
                                              }
                                              else {
                                                  $subquery->where('last_name', 'LIKE', '%' . $keyword . '%');
                                              }
                                          }
                                      });
                                      $query->orWhere(function ($subquery) use ($keywordarray) {
                                            foreach($keywordarray as $key => $keyword){
                                              if($key == 0){
                                                  $subquery->where('bio', 'LIKE', '%' . $keyword . '%');
                                              }
                                              else {
                                                  $subquery->where('bio', 'LIKE', '%' . $keyword . '%');
                                              }
                                          }
                                      });
                                      $query->orWhere(function ($subquery) use ($keywordarray) {
                                            foreach($keywordarray as $key => $keyword){
                                              if($key == 0){
                                                  $subquery->where('headline', 'LIKE', '%' . $keyword . '%');
                                              }
                                              else {
                                                  $subquery->where('headline', 'LIKE', '%' . $keyword . '%');
                                              }
                                          }
                                      });
                                      $query->orWhere(function ($subquery) use ($keywordarray) {
                                            foreach($keywordarray as $key => $keyword){
                                              if($key == 0){
                                                  $subquery->where('address_1', 'LIKE', '%' . $keyword . '%');
                                              }
                                              else {
                                                  $subquery->where('address_1', 'LIKE', '%' . $keyword . '%');
                                              }
                                          }
                                      });
                                      $query->orWhere(function ($subquery) use ($keywordarray) {
                                            foreach($keywordarray as $key => $keyword){
                                              if($key == 0){
                                                  $subquery->where('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                              }
                                              else {
                                                  $subquery->where('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                              }
                                          }
                                      });
                
                                    }
                                    else {
                                        $query->where(function ($subquery) use ($keywordarray) { 
                                      foreach($keywordarray as $key => $keyword){
                                        
                                        if($key == 0){
                                            $subquery->orWhere('business_name', 'LIKE', '%' . $keyword . '%');
                                        }
                                        else {
                                            $subquery->orWhere('business_name', 'LIKE', '%' . $keyword . '%');
                                        }
                                        }
                                    });
                
                                    $query->orWhere(function ($subquery) use ($keywordarray) {
                                      foreach($keywordarray as $key => $keyword){
                                        
                                        if($key == 0){
                                            $subquery->orWhere('first_name', 'LIKE', '%' . $keyword . '%');
                                        }
                                        else {
                                            $subquery->orWhere('first_name', 'LIKE', '%' . $keyword . '%');
                                        }
                                        }
                                    });
                                    
                                    $query->orWhere(function ($subquery) use ($keywordarray) {
                                      foreach($keywordarray as $key => $keyword){
                                        if($key == 0){
                                            $subquery->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
                                        }
                                        else {
                                            $subquery->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
                                        }
                                    }
                                    });
                                    $query->orWhere(function ($subquery) use ($keywordarray) {
                                          foreach($keywordarray as $key => $keyword){
                                            if($key == 0){
                                                $subquery->orWhere('bio', 'LIKE', '%' . $keyword . '%');
                                            }
                                            else {
                                                $subquery->orWhere('bio', 'LIKE', '%' . $keyword . '%');
                                            }
                                        }
                                    });
                                    $query->orWhere(function ($subquery) use ($keywordarray) {
                                          foreach($keywordarray as $key => $keyword){
                                            if($key == 0){
                                                $subquery->orWhere('headline', 'LIKE', '%' . $keyword . '%');
                                            }
                                            else {
                                                $subquery->orWhere('headline', 'LIKE', '%' . $keyword . '%');
                                            }
                                        }
                                    });
                                    $query->orWhere(function ($subquery) use ($keywordarray) {
                                          foreach($keywordarray as $key => $keyword){
                                            if($key == 0){
                                                $subquery->orWhere('address_1', 'LIKE', '%' . $keyword . '%');
                                            }
                                            else {
                                                $subquery->orWhere('address_1', 'LIKE', '%' . $keyword . '%');
                                            }
                                        }
                                    });
                                    $query->orWhere(function ($subquery) use ($keywordarray) {
                                          foreach($keywordarray as $key => $keyword){
                                            if($key == 0){
                                                $subquery->orWhere('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                            }
                                            else {
                                                $subquery->orWhere('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                            }
                                        }
                                    });
                                }
                              });
                            }else {
                            $TrainerServicesdata1->where('front_users.zip_code', '=', $request->location);
                            }
                            }
                           else if($stateCount !=0){
                                if(strlen($request->location) == 2){
                                        $TrainerServicesdata1->where('front_users.state_code', '=', $request->location);
                                    } else {
                                        $TrainerServicesdata1->where('front_users.state', '=', $request->location);
                                    }
                            }
                            else {
                                $TrainerServicesdata1->where('front_users.city', '=', $request->location);
                            }
                        } else {
                            if(!empty($locations[0])){
                            
                                $TrainerServicesdata1->where('front_users.city', '=', $locations[0]);
                            }
                            if(!empty($locations[1])){
                                if(strlen(trim($locations[1])) == 2){
                                        $TrainerServicesdata1->where('front_users.state_code', '=', trim($locations[1]));
                                    } else {
                                        $TrainerServicesdata1->where('front_users.state', '=', trim($locations[1]));
                                    }
                            }
                        }
                    }
                 }
                }
            //}

            if (isset($request->services) && !empty($request->services)) {
                $TrainerServicesdata1->where('service_id', $request->services);
            } else {
                //$TrainerServicesdata->where('is_featured', 'yes');
                
            }
            
            if ($request->virtual_in_both == 'In Person')
            {
                $TrainerServicesdata1->where('address1_virtual', 0);
            } 
            else if($request->virtual_in_both == 'Virtual Only')
            {
                $TrainerServicesdata1->where(function ($query){
                    $query->where('front_users.address1_virtual', 1)
                    ->orWhere(function ($virtualQuery){
                        $virtualQuery->where('front_users.address1_virtual', 0)
                        ->where('trainer_services.format', '=', 'Virtual - Single Appointment')
                        ->orWhere('trainer_services.format', '=', 'Virtual - Group Appointment')
                        ->orWhere('trainer_services.format', '=', 'Virtual - Monthly Membership')
                        ->orWhere('trainer_services.format', '=', 'Virtual - Yearly Membership')
                        ->orWhere('trainer_services.format', '=', 'Virtual - Package Deal');
                    });
                });
                
            }
            
            $TrainerServicesdata1->groupBy('front_users.id');
            if (isset($request->ratings) && !empty($request->ratings)) {
                
            }
            if (isset($request->format) && !empty($request->format)) {
                $TrainerServicesdata1->where('format','like', '%' . $request->format . '%');
            }
            if (isset($request->keyword) && !empty($request->keyword)) {
                $keyword = $request->keyword; 
              if($request->keyword_search != 1 && $request->keyword_search != 2){
                    $TrainerServicesdata1->where(function ($query) use ($keyword, $occurencearray) { 
                        if(!(isset($occurencearray)) || count($occurencearray) < 1){
                            $keywordarray = explode(' ', $keyword);
                        }
                        else {
                            $keywordarray = $occurencearray;
                        }
                        if(!(isset($occurencearray)) || count($occurencearray) < 1){
    
                            $query->where(function ($subquery) use ($keywordarray) { 
                                foreach($keywordarray as $key => $keyword){
                                  
                                  if($key == 0){
                                      $subquery->where('business_name', 'LIKE', '%' . $keyword . '%');
                                  }
                                  else {
                                      $subquery->where('business_name', 'LIKE', '%' . $keyword . '%');
                                  }
                                  }
                              });
          
                              $query->orWhere(function ($subquery) use ($keywordarray) {
                                foreach($keywordarray as $key => $keyword){
                                  
                                  if($key == 0){
                                      $subquery->where('first_name', 'LIKE', '%' . $keyword . '%');
                                  }
                                  else {
                                      $subquery->where('first_name', 'LIKE', '%' . $keyword . '%');
                                  }
                                  }
                              });
                              
                              $query->orWhere(function ($subquery) use ($keywordarray) {
                                foreach($keywordarray as $key => $keyword){
                                  if($key == 0){
                                      $subquery->where('last_name', 'LIKE', '%' . $keyword . '%');
                                  }
                                  else {
                                      $subquery->where('last_name', 'LIKE', '%' . $keyword . '%');
                                  }
                              }
                          });
                          $query->orWhere(function ($subquery) use ($keywordarray) {
                                foreach($keywordarray as $key => $keyword){
                                  if($key == 0){
                                      $subquery->where('bio', 'LIKE', '%' . $keyword . '%');
                                  }
                                  else {
                                      $subquery->where('bio', 'LIKE', '%' . $keyword . '%');
                                  }
                              }
                          });
                          $query->orWhere(function ($subquery) use ($keywordarray) {
                                foreach($keywordarray as $key => $keyword){
                                  if($key == 0){
                                      $subquery->where('headline', 'LIKE', '%' . $keyword . '%');
                                  }
                                  else {
                                      $subquery->where('headline', 'LIKE', '%' . $keyword . '%');
                                  }
                              }
                          });
                          $query->orWhere(function ($subquery) use ($keywordarray) {
                                foreach($keywordarray as $key => $keyword){
                                  if($key == 0){
                                      $subquery->where('address_1', 'LIKE', '%' . $keyword . '%');
                                  }
                                  else {
                                      $subquery->where('address_1', 'LIKE', '%' . $keyword . '%');
                                  }
                              }
                          });
                          $query->orWhere(function ($subquery) use ($keywordarray) {
                                foreach($keywordarray as $key => $keyword){
                                  if($key == 0){
                                      $subquery->where('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                  }
                                  else {
                                      $subquery->where('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                  }
                              }
                          });
    
                        }
                        else {
                            $query->where(function ($subquery) use ($keywordarray) { 
                          foreach($keywordarray as $key => $keyword){
                            
                            if($key == 0){
                                $subquery->orWhere('business_name', 'LIKE', '%' . $keyword . '%');
                            }
                            else {
                                $subquery->orWhere('business_name', 'LIKE', '%' . $keyword . '%');
                            }
                            }
                        });
    
                        $query->orWhere(function ($subquery) use ($keywordarray) {
                          foreach($keywordarray as $key => $keyword){
                            
                            if($key == 0){
                                $subquery->orWhere('first_name', 'LIKE', '%' . $keyword . '%');
                            }
                            else {
                                $subquery->orWhere('first_name', 'LIKE', '%' . $keyword . '%');
                            }
                            }
                        });
                        
                        $query->orWhere(function ($subquery) use ($keywordarray) {
                          foreach($keywordarray as $key => $keyword){
                            if($key == 0){
                                $subquery->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
                            }
                            else {
                                $subquery->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
                            }
                        }
                        });
                        $query->orWhere(function ($subquery) use ($keywordarray) {
                              foreach($keywordarray as $key => $keyword){
                                if($key == 0){
                                    $subquery->orWhere('bio', 'LIKE', '%' . $keyword . '%');
                                }
                                else {
                                    $subquery->orWhere('bio', 'LIKE', '%' . $keyword . '%');
                                }
                            }
                        });
                        $query->orWhere(function ($subquery) use ($keywordarray) {
                              foreach($keywordarray as $key => $keyword){
                                if($key == 0){
                                    $subquery->orWhere('headline', 'LIKE', '%' . $keyword . '%');
                                }
                                else {
                                    $subquery->orWhere('headline', 'LIKE', '%' . $keyword . '%');
                                }
                            }
                        });
                        $query->orWhere(function ($subquery) use ($keywordarray) {
                              foreach($keywordarray as $key => $keyword){
                                if($key == 0){
                                    $subquery->orWhere('address_1', 'LIKE', '%' . $keyword . '%');
                                }
                                else {
                                    $subquery->orWhere('address_1', 'LIKE', '%' . $keyword . '%');
                                }
                            }
                        });
                        $query->orWhere(function ($subquery) use ($keywordarray) {
                              foreach($keywordarray as $key => $keyword){
                                if($key == 0){
                                    $subquery->orWhere('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                }
                                else {
                                    $subquery->orWhere('trainer_services.name', 'LIKE', '%' . $keyword . '%');
                                }
                            }
                        });
                    }
                    }); 
                }
                        
            }
            $TrainerServicesdata1->where('front_users.status','active');
                $TrainerServicesdata1->where('front_users.user_role','trainer');
                $TrainerServicesdata1->where(function ($query) { 
                        $query->where('front_users.is_subscription','0');
                        //$query->orwhere('front_users.is_subscription','1');
                        $query->orwhere('front_users.is_payment','1');
                    }); 
/*print_r($TrainerServicesdata1->toSql());
dd( $TrainerServicesdata1->getBindings() );exit();*/
            $TrainerServicesdata = $TrainerServicesdata1->paginate(10, ['*'], 'page', $page)->appends(request()->all());

            /* Start By Ujas */
            $TrainerServicesdata->transform(function ($v) {
                $r = 0;
                $t = 1;
                if (isset($v->ratings)) {
                    $retingdata = $v->ratings->transform(function ($v1) use($r) {
                        if (isset($v1->rating)) {
                            return $v1->rating;
                        }
                    });
                    $p = 0;
                    foreach ($retingdata as $rdata) {
                        if ($rdata != '') {
                            $r += $rdata;
                            $p++;
                            $v->ratting_count = $t++;
                        }
                    }
                    if ($r != 0) {
                        $v->ratting = ($r / $p);
                    } else {
                        if (!isset($v->ratting)) {
                            $v->ratting = 0;
                        }
                    }
                }
                return $v;
            });

        }
/*print_r($location_ids);*/
         $sponsorTrainerServicesdata = collect();
         if (isset($request->location) && !empty($request->location) && !(empty($mile_location_ids))) {
            $location = Locations::where('status','active')->where('name', $request->location)->first();

            if(isset($location->name)){
                    
                $sponsorTrainerServicesdata = TrainerServices::select('trainer_services.*',DB::raw('GROUP_CONCAT(DISTINCT services.name) as service_name'), DB::raw('(SELECT count( rating ) FROM ratings WHERE ratings.trainer_id = front_users.id) as retting_count'), DB::raw('(SELECT AVG( rating ) FROM ratings WHERE ratings.trainer_id = front_users.id) as retting_avg'))
                ->Join('front_users', 'front_users.id', '=', 'trainer_services.trainer_id')
                ->leftJoin('ratings', 'ratings.trainer_id', '=', 'front_users.id')
                ->leftJoin('services', 'services.id', '=', 'trainer_services.service_id')
                ->leftJoin('provider_locations', 'provider_locations.user_id', '=', 'front_users.id')
                ->leftJoin('locations', 'locations.id', '=', 'provider_locations.location_id')
                ->where('front_users.is_feature',1)
                ->where('front_users.status','active')
                // ->orderBy('front_users.business_name');
                ->orderBy('retting_count', 'desc')
                ->orderBy('retting_avg', 'desc')
                ->orderBy('front_users.id', 'asc');
                // ->groupBy('trainer_services.trainer_id');
            
                   $sponsorTrainerServicesdata->whereRaw('FIND_IN_SET('.$location->id.',service_location)');
                 

                   
        $sponsorTrainerServicesdata = $sponsorTrainerServicesdata->paginate(10, ['*'], 'page', $page)->appends(request()->all());
        $sponsorTrainerServicesdata->transform(function ($v) {
            $r = 0;
            $t = 1;
            if (isset($v->trainer->ratings)) {
                $retingdata = $v->trainer->ratings->transform(function ($v1) use($r) {
                    if (isset($v1->rating)) {
                        return $v1->rating;
                    }
                });
                $p = 0;
                foreach ($retingdata as $rdata) {
                    if ($rdata != '') {
                        $r += $rdata;
                        $p++;
                        $v->ratting_count = $t++;
                    }
                }
                if ($r != 0) {
                    $v->ratting = ($r / $p);
                } else {
                    if (!isset($v->ratting)) {
                        $v->ratting = 0;
                    }
                }
            }
            return $v;
        });
                    } 
    }
 
        

        
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            
        } else{
             $page = 1;
             
        }
        if (isset($_GET['search'])) {
            $page = 1;
            
        }   
        $user = \Auth::guard('front_auth')->user();
        $provider_locations = array();
        if(isset($user->id)){
            $provider_locations = \App\ProviderLocations::where('user_id',$user->id)->pluck('location_id')->toArray();
        }   
        $today = date('Y-m-d');
        $advertisement = Advertisement::where('status','active')->where('pageview','explore')->where('view','horizontal');
        $advertisement->whereDate('start_date','<=', $today)
            ->whereDate('end_date','>=', $today);
//        foreach($provider_locations as $val)
//        {
//            $advertisement->orwhere(DB::Raw('FIND_IN_SET('.$val.',locations)'));
//        } 
        if(isset($location->id)){
           // echo $location->id;
            $advertisement->whereRaw('FIND_IN_SET('.$location->id.',locations)');
        }
//        if ($location_id != '') {
//            $advertisement->where(DB::Raw('FIND_IN_SET('.$location_id.',locations)'));
//        }
        $advertisement = $advertisement->orderByRaw("RAND()")->first();
        if (isset($_GET['lat'])) {
            $lat = $_GET['lat'];
        }else{
            $lat = '40.016869';
        }
        if (isset($_GET['lng'])) {
            $lng = $_GET['lng'];
        }else{
            $lng = '-105.279617';
        }
        if (isset($_GET['mapzoom'])) {
            $mapzoom = $_GET['mapzoom'];
        }else{
            $mapzoom = '10';
        }

        if (isset($_GET['map_latitude'])) {
            $map_latitude = $_GET['map_latitude'];
        } else {
            $map_latitude = '';
        }
        if (isset($_GET['map_longitude'])) {
            $map_longitude = $_GET['map_longitude'];
        } else {
            $map_longitude = '';
        }
        
           /* echo '<pre>';
        print_r(count($TrainerServicesdata));
        exit;*/
        $metaTitle = "Explore";
        if ($request->ajax()) {
            return view('front.exploreservicessearch', compact('metaTitle','page','services', 'TrainerServicesdata', 'request', 'TrainerServicesdataall', 'TrainerAcountdata', 'sponsorTrainerServicesdata','advertisement', 'lat', 'lng', 'mapzoom', 'map_latitude', 'map_longitude', 'current_location_name'));
        } else {
            return view('front.exploreservices', compact('metaTitle', 'page', 'location_name', 'services', 'TrainerServicesdata', 'request', 'TrainerServicesdataall', 'TrainerAcountdata', 'sponsorTrainerServicesdata','advertisement', 'lat', 'lng', 'mapzoom', 'map_latitude', 'map_longitude', 'current_location_name'));
        }
    }

    public function aboutus() {
        $cmsdata = getCmsPages('about-us');
        $howitwork = getCmsPages('how-it-works');
        $ourmission = getCmsPages('our-mission');
        $metaTitle = "AboutUs";
		$metaDescription = "Training Block was created with a mission to support and empower runners, in order to elevate our sport. We do so by giving runners access to a network of local.";
        return view('front.aboutus', compact('metaTitle','metaDescription','cmsdata', 'howitwork', 'ourmission'));
    }

    public function terms_conditions(){
        $cmsdata = getCmsPages('terms-conditions');
        $metaTitle = "Terms-Conditions";
        return view('front.terms', compact('cmsdata', 'metaTitle'));
    }

    public function contactus() {
        $cmsdata = getCmsPages('about-us');
        $howitwork = getCmsPages('how-it-works');
        $ourmission = getCmsPages('our-mission');
        $metaTitle = "ContactUs";
        return view('front.contactus', compact('metaTitle','cmsdata', 'howitwork', 'ourmission'));
    }

    public function contactussave(Request $request) {
        $input = $request->all();
                    
        $secretKey = "6LdnSykaAAAAAI9zFdiYtuycZ6IgbXIibqXNhlwg";
        $message = array();
$ip = $_SERVER['REMOTE_ADDR'];
$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$input['g-recaptcha-response']."&remoteip=".$ip);
$responseKeys = json_decode($response,true);
if(intval($responseKeys["success"]) !== 1) {    
    $rules['g-recaptcha-response'] = 'required';
    $message['g-recaptcha-response.required'] = 'Captcha verification failed';
}  
        $rules['name'] = 'required';
        $rules['email'] = 'required';
        $validator = Validator::make($request->all(), $rules,$message);
        if ($validator->fails()) {
             return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
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
//            Mail::send('email.contact', [
//                'name' => ucfirst($input['name']),
//                'email' => $input['email'],
//                'messages' => $input['message'],
//                'phone_number' => $input['phone_number'],
//                    ], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
//                $message->from($admin_email, $admin_name);
//                $message->to($emails, $emails_name)->subject($subject);
//            });
//            $mailToadmin = "maulik@mailinator.com";
//            Mail::send('email.contact_email_admin', [
//                'name' => ucfirst($input['name']),
//                'email' => $input['email'],
//                'messages' => $input['message'],
//                'phone_number' => $input['phone_number'],
//                    ], function ($message) use ($admin_email, $admin_name, $subject, $mailToadmin, $emails_name) {
//                $message->from($admin_email, $admin_name);
//                $message->to($mailToadmin, $emails_name)->subject($subject);
//            });

            $msg = "Thank you for filling out your information!";
            return Redirect::route('contactuspage')->with('success', $msg);
        }
    }

    public function ResourceLike($resourceId, $name){
        //echo $resourceId;echo $name;exit();
        $customerId = Auth::guard('front_auth')->user()->id;
        $resourceLikeCount = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resourceId])->count();
        $resourceLike = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resourceId])->first();
        
        if($resourceLikeCount == 0){
            $resourceLikeInsert = DB::table('resource_count')->insert([
                        'resource_id' => $resourceId,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'likes' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
        } else {
            if($resourceLike->likes == 0 && $resourceLike->dislike == 0){
                $resourceLikeUpdate = DB::update('update resource_count set likes="1" where id="'.$resourceLike->id.'"');
            } else if($resourceLike->likes == 0 && $resourceLike->dislike == 1){
                $resourceLikeUpdate = DB::update('update resource_count set likes="1", dislike="0" where id="'.$resourceLike->id.'"');
            }
        }

        return redirect('provider/'.$name.'#nav-resources');
        
    }

    public function ResourceDisLike($resourceId, $name){
        $customerId = Auth::guard('front_auth')->user()->id;
        $resourceDisLikeCount = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resourceId])->count();
        $resourceDisLike = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resourceId])->first();
        
        if($resourceDisLikeCount == 0){
            $resourceDisLike = DB::table('resource_count')->insert([
                        'resource_id' => $resourceId,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'dislike' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
        } else {
            //echo '<pre>';print_r($resourceDisLike->like);exit();
            if($resourceDisLike->likes == 0 && $resourceDisLike->dislike == 0){
                $resourceLikeUpdate = DB::update('update resource_count set dislike="1" where id="'.$resourceDisLike->id.'"');
            } else if($resourceDisLike->likes == 1 && $resourceDisLike->dislike == 0){
                $resourceLikeUpdate = DB::update('update resource_count set likes=0, dislike=1 where id="'.$resourceDisLike->id.'"');
            }
        }

        return redirect('provider/'.$name.'#nav-resources');
        
    }

    

    public function ResourceSave($resourceId, $name){
        $customerId = Auth::guard('front_auth')->user()->id;
        $resourcereSaveCount = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resourceId])->count();
        $resourceSave = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resourceId])->first();
        
        if($resourcereSaveCount == 0){
            $resourceSave = DB::table('resource_count')->insert([
                        'resource_id' => $resourceId,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'saved' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
        } else {
            //echo '<pre>';print_r($resourceDisLike->like);exit();
            if($resourceSave->saved == 0 && $resourceSave->unsaved == 0){
                $resourceSaveUpdate = DB::update('update resource_count set saved="1" where id="'.$resourceSave->id.'"');
            } else if($resourceSave->saved == 1 && $resourceSave->unsaved == 0){
                $resourceSaveUpdate = DB::update('update resource_count set saved=0, unsaved=1 where id="'.$resourceSave->id.'"');
            } else if($resourceSave->saved == 0 && $resourceSave->unsaved == 1){
                $resourceSaveUpdate = DB::update('update resource_count set saved=1, unsaved=0 where id="'.$resourceSave->id.'"');
            }
        }

        return redirect('provider/'.$name.'#nav-resources');
        
    }

    public function ResourceComment(Request $request){
        
        $customerId = Auth::guard('front_auth')->user()->id;
        $resourcereCommentCount = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $request->resource_id])->count();
        $resourceComment = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $request->resource_id])->first();
        
        if($resourcereCommentCount == 0){
            $resourceComment = DB::table('resource_count')->insert([
                        'resource_id' => $request->resource_id,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'comments' => $request->comments,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            $resourceComments = DB::table('resource_comments')->insert([
                        'resource_id' => $request->resource_id,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'comments' => $request->comments,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
        } else {
            $resourceCommentUpdate = DB::update('update resource_count set comments="'.$request->comments.'", updated_at="'.date('Y-m-d H:i:s').'" where id="'.$resourceComment->id.'"');
            $resourceComments = DB::table('resource_comments')->insert([
                        'resource_id' => $request->resource_id,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'comments' => $request->comments,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
            
        }

        $resource_Comment_Count = DB::table('resource_comments')->where(["resource_id" => $request->resource_id])->count();

        $resourceCommentCountUpdate = DB::update('update resource set comment_count="'.$resource_Comment_Count.'" where id="'.$request->resource_id.'"');

          // Mail Notification while athelete comments the resource        

    $trainer_email = $request->provider_email;
    // $trainer_email = "gowthamank@themajesticpeople.com";
    $comment_detail_mail = new PHPMailer;
    $comment_detail_mail->IsSMTP();
    $comment_detail_mail->SMTPAuth = true;
    $comment_detail_mail->SMTPSecure = env('MAIL_SECURE');
    $comment_detail_mail->Host = env('MAIL_HOST');
    $comment_detail_mail->Port = env('MAIL_PORT');
    $comment_detail_mail->Username = env('MAIL_USERNAME');
    $comment_detail_mail->Password = env('MAIL_PASSWORD');
    $comment_detail_mail->SetFrom(env('MAIL_FROM'), 'Training Block');
    $comment_detail_mail->Subject = "Training Block - Resource Comments";
    $comment_detail_mail->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
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
        <p>Someone just left a comment on one of your resources! Check it out here:</p><br /> </td>
    </tr>
    <tr>
        <td style="padding-bottom:15px;"> 
            <a href="'.$request->resource_url.'" style="background: #00ab91;color: #fff;padding: 20px;border-radius: 5px;text-decoration: none;">View Comment</a>
        </td>
    </tr>
    <tr>
                <td style="background:#555;color:#fff;padding:15px;"> <span>Thanks,</span><br>
                <p style="margin-top:5px;">The Training Block Team</p>
                </td>
            </tr>
    </tbody></table></body></html>');
    $comment_detail_mail->AddAddress($trainer_email , 'Training Block');

    if (!$comment_detail_mail->send()) {
        echo 'Mailer Error: ' . $comment_detail_mail->ErrorInfo;
    } else {
        echo 'Message sent!';
    }

        return redirect('provider/'.$request->provider_name.'#nav-resources');
        
    }

    public function showTrainerDetails($name) {
        
        $user = \Auth::guard('front_auth')->user();
        
        if(!$user)
        {
            $baseurl = URL::to('/provider/'.$name.'#nav-resourcess');
            
            session()->put('provider-resource-url',$baseurl);
            return redirect('login');
        }
    }

    public function showTrainerDetail($name, Request $request) {
        $user = \Auth::guard('front_auth')->user();

        if($user)
        {
            session()->put('provider-resource-url',false);
        }
        session()->flash('previous-route', Route::current()->getName());
       
    if(FrontUsers::where('spot_description', $name)->first() !== null){    
            $trainer = FrontUsers::where('spot_description', $name)->first();
    }else{
        $userOld = Auth::guard('front_auth')->user();
        $routingDbProIds = DB::table('provider_routing')->where('old_name', $name)->first();
        if($routingDbProIds !== null){
        return redirect('/provider/'.$routingDbProIds->new_name);
        }
    }

        if (empty($trainer)) {
            return redirect()->intended(route('exploreservices'));
        }
        $trainerData = FrontUsers::where(["id" => $trainer->id])
                ->with(["services.service", "Orders.Ratting"])
                ->with(['services' => function ($query) {
                        $query->where(['status' => "1"])->orderBy('id', 'DESC');
                    }
                        ])
                
                        ->get();
        $TrainerAcountdata = StripeAccounts::where('user_id', $trainer->id)->first();
                //dd($TrainerAcountdata);
                //$trainerService = TrainerServices::where('trainer_id', $trainer->id)->where('is_featured', 'yes')->with(['service','trainer','Orders.Ratting'])->get();
                // if($trainerService->count() == 0){
                //     return redirect()->intended(route('exploreservices'));
                // }
                $serviceEventsData = FrontUsers::where(["id" => $trainer->id])->with(["featuredservice", "services.service", "Orders.Ratting", "allOrders.service"])->first();
                $trainerList = FrontUsers::where('user_role', 'trainer')->where('status','active')->with(['services', 'orders.Ratting','ratings'])->has('services')->get();

                $trainerResources = Resource::where(["trainer_id" => $trainer->id, "status" => '1'])->get();
                $resourceCategory = Services::where(["status" => 'active'])->orderBy('name','ASC')->get();
                // dd($trainerResources);
                /*$trainerList->transform(function ($v) {
                    $r = 0;
                    $t = 1;
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
                                $v->ratting_count = $t++;
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
                });*/

                /*Start By Ujas*/
                $trainerList->transform(function ($v) {
                    $r = 0;
                    $t = 1;
                    if (isset($v->ratings)) {
                        $retingdata = $v->ratings->transform(function ($v1) use($r) {
                            if (isset($v1->rating)) {
                                return $v1->rating;
                            }
                        });
                        $p = 0;
                        foreach ($retingdata as $rdata) {
                            if ($rdata != '') {
                                $r += $rdata;
                                $p++;
                                $v->ratting_count = $t++;
                            }
                        }
                        if ($r != 0) {
                            $v->ratting = ($r / $p);
                        } else {
                            if (!isset($v->ratting)) {
                                $v->ratting = 0;
                            }
                        }
                    }
                    return $v;
                });
                /*End By Ujas*/
                
                // $trainerList->transform(function ($v) {
                //     if (isset($v->Orders)) {
                //         $retingdata = $v->Orders->transform(function ($v1) {
                //             if (isset($v1->Ratting->rating)) {
                //                 return $v1->Ratting->rating;
                //             }
                //         });
                //         $retingdata = $retingdata->reject(function ($item) {
                //             return is_null($item);
                //         });
                //         $r = 0;
                //         foreach ($retingdata as $rdata) {
                //             $r += $rdata;
                //         }
                //         if ($r != 0) {
                //             $v->ratting = $r / $retingdata->count();
                //         } else {
                //             $v->ratting = 0;
                //         }
                //     }
                //     return $v;
                // });
                // dd($serviceEventsData);
                $returnarray = array();
                $array = array();
                foreach ($serviceEventsData->allOrders as $serviceEvent) {
                    if($serviceEvent->order_status == null){
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
                    
                }

                /*$trainerData->transform(function ($v) {
                    $r = 0;
                    $t = 1;
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
                                $v->ratting_count = $t++;
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
                });*/

                /*Start By Ujas*/
                $trainerData->transform(function ($v) {
                    $r = 0;
                    $t = 1;
                    if (isset($v->ratings)) {
                        $retingdata = $v->ratings->transform(function ($v1) use($r) {
                            if (isset($v1->rating)) {
                                return $v1->rating;
                            }
                        });
                        $p = 0;
                        foreach ($retingdata as $rdata) {
                            if ($rdata != '') {
                                $r += $rdata;
                                $p++;
                                $v->ratting_count = $t++;
                            }
                        }
                        if ($r != 0) {
                            $v->ratting = ($r / $p);
                        } else {
                            if (!isset($v->ratting)) {
                                $v->ratting = 0;
                            }
                        }
                    }
                    return $v;
                });
                /*End By Ujas*/

                $trainerData = $trainerData->first();

                !empty($trainerData->business_name)  ? $title = $trainerData->business_name : $title = $trainerData->first_name." ".$trainerData->last_name;
                
                $trainerreviewData = FrontUsers::where(["id" => $trainer->id])
                ->with(["ratings.user"])->has('ratings')
                        ->first();
//              dd($trainerreviewData->toArray());
                $TrainerPhoto = TrainerPhoto::where('trainer_id', $trainer->id)->orderBy('position', 'asc')->limit(4)->get();    
                 //dd($trainer->id);
                 $recommended = RecommendedProviders::with('users','customer')->where('provider_id',$trainer->id)->get();
                 $recommended_authlete = RecommendedProviders::with('users','customer')->where('provider_id',$trainer->id)->where('role','customer')->get();
                 $recommended_provider = RecommendedProviders::with('users','customer')->where('provider_id',$trainer->id)->where('role','trainer')->get();
                 $Trainerdata =  RecommendedProviders::with('users','customer')->where('user_id',$trainer->id)->get();
                

                // dd($request->search);
                if($request->search == 1){

                    // $query = DB::Table('resource')->Where("trainer_id", $request->trainer_id)
                    $keyword = $request->keyword;
                    $category = $request->category;
                    $format = $request->format;

                    if (!empty($keyword) && !empty($category) && !empty($format)) {
                        $resource_like_count = Resource::max('like_count');
                        $resource_comment_count = Resource::max('comment_count');
                        if($resource_comment_count >$resource_like_count ){
                            $searchResources = DB::Table('resource')->Where("trainer_id", $request->trainer_id)->Where('title', 'like', "%{$keyword}%")->Where(["category" => $request->category])->Where(["format" => $format])->orderBy('comment_count', 'DESC')->orderBy('like_count', 'DESC')->get();
                        } else {
                            $searchResources = DB::Table('resource')->Where("trainer_id", $request->trainer_id)->Where('title', 'like', "%{$keyword}%")->Where(["category" => $request->category])->Where(["format" => $format])->orderBy('like_count', 'DESC')->orderBy('comment_count', 'DESC')->get();
                        }
                    }
                    else if (!empty($keyword) && !empty($category) && empty($format)) {
                        $resource_like_count = Resource::max('like_count');
                        $resource_comment_count = Resource::max('comment_count');
                        if($resource_comment_count >$resource_like_count ){
                            $searchResources = DB::Table('resource')->Where("trainer_id", $request->trainer_id)->Where('title', 'like', "%{$keyword}%")->Where(["category" => $request->category])->orderBy('comment_count', 'DESC')->orderBy('like_count', 'DESC')->get();
                        } else {
                            $searchResources = DB::Table('resource')->Where("trainer_id", $request->trainer_id)->Where('title', 'like', "%{$keyword}%")->Where(["category" => $request->category])->orderBy('like_count', 'DESC')->orderBy('comment_count', 'DESC')->get();
                        }
                    }
                    else if (!empty($keyword) && empty($category) && !empty($format)) {
                        $resource_like_count = Resource::max('like_count');
                        $resource_comment_count = Resource::max('comment_count');
                        if($resource_comment_count >$resource_like_count ){
                            $searchResources = DB::Table('resource')->Where("trainer_id", $request->trainer_id)->Where('title', 'like', "%{$keyword}%")->Where(["format" => $format])->orderBy('comment_count', 'DESC')->orderBy('like_count', 'DESC')->get();
                        } else {
                            $searchResources = DB::Table('resource')->Where("trainer_id", $request->trainer_id)->Where('title', 'like', "%{$keyword}%")->Where(["format" => $format])->orderBy('like_count', 'DESC')->orderBy('comment_count', 'DESC')->get();
                        }
                    }
                     else if (empty($keyword) && !empty($category) && !empty($format)) {
                        $searchResources = DB::Table('resource')->Where("trainer_id", $request->trainer_id)->Where(["category" => $request->category])->Where(["format" => $format])->get();
                    }
                    else if(!empty($keyword)){
                        $resource_like_count = Resource::max('like_count');
                        $resource_comment_count = Resource::max('comment_count');
                        if($resource_comment_count >$resource_like_count ){
                            $searchResources = DB::Table('resource')->Where("trainer_id", $request->trainer_id)->Where('title', 'like', "%{$keyword}%")->orderBy('comment_count', 'DESC')->orderBy('like_count', 'DESC')->get();
                        } else {
                            $searchResources = DB::Table('resource')->Where("trainer_id", $request->trainer_id)->Where('title', 'like', "%{$keyword}%")->orderBy('like_count', 'DESC')->orderBy('comment_count', 'DESC')->get();
                        }
                    }
                     else if(!empty($format)){
                        $searchResources = DB::Table('resource')->Where("trainer_id", $request->trainer_id)->Where(["format" => $format])->get();
                    } else if(!empty($category)){
                        $searchResources = DB::Table('resource')->Where("trainer_id", $request->trainer_id)->Where(["category" => $request->category])->get();
                    }
                     else{
                        $searchResources = DB::Table('resource')->Where("trainer_id", $request->trainer_id)->get();
                    }

                    // $searchResources = DB::Table('resource')->Where("trainer_id", $request->trainer_id)->Where('title', 'like', "%{$keyword}%")->Where(["category" => $request->category])->get();
                    // dd($searchResources);
                     
                       $customerId = Auth::guard('front_auth')->user();
                       if($customerId !=''){
                            if($customerId->user_role =='customer'){
                                $customerId = Auth::guard('front_auth')->user()->id;
                            } else {
                                $customerId = Auth::guard('front_auth')->user()->id;
                            }
                       } else {
                            $customerId = '0';
                       }
                    $resource_tab_search_name = '10';



                    if($trainerData->day1 != null){
                        $monday = $trainerData->day1;
                        $mondayArray = explode(",", $monday);    
                        $mondayOpen = $mondayArray[0];    
                        $mondayClose = $mondayArray[count($mondayArray) - 1];    
                    }else{
                        $mondayOpen = " ";
                        $mondayClose = " ";
                    }
                    
                    if($trainerData->day2 != null){
                        $tuesday = $trainerData->day2;
                        $tuesdayArray = explode(",", $tuesday);    
                        $tuesdayOpen = $tuesdayArray[0];    
                        $tuesdayClose = $tuesdayArray[count($tuesdayArray) - 1];    
                    }else{
                        $tuesdayOpen = " ";
                        $tuesdayClose = " ";
                    }
                    
                    if($trainerData->day3 != null){
                        $wednesday = $trainerData->day3;
                        $wednesdayArray = explode(",", $wednesday);    
                        $wednesdayOpen = $wednesdayArray[0];    
                        $wednesdayClose = $wednesdayArray[count($wednesdayArray) - 1];    
                    }else{
                        $wednesdayOpen = " ";
                        $wednesdayClose = " ";
                    }
                    
                    if($trainerData->day4 != null){
                        $thusday = $trainerData->day4;
                        $thusdayArray = explode(",", $thusday);    
                        $thusdayOpen = $thusdayArray[0];    
                        $thusdayClose = $thusdayArray[count($thusdayArray) - 1];    
                    }else{
                        $thusdayOpen = " ";
                        $thusdayClose = " ";
                    }
                    
                    if($trainerData->day5 != null){
                        $friday = $trainerData->day5;
                        $fridayArray = explode(",", $friday);    
                        $fridayOpen = $fridayArray[0];    
                        $fridayClose = $fridayArray[count($fridayArray) - 1];    
                    }else{
                        $fridayOpen = " ";
                        $fridayClose = " ";
                    }
                    
                    if($trainerData->day6 != null){
                        $saturday = $trainerData->day6;
                        $saturdayArray = explode(",", $saturday);    
                        $saturdayOpen = $saturdayArray[0];    
                        $saturdayClose = $saturdayArray[count($saturdayArray) - 1];    
                    }else{
                        $saturdayOpen = " ";
                        $saturdayClose = " ";
                    }
                    
                    if($trainerData->day7 != null){
                        $sunday = $trainerData->day7;
                        $sundayArray = explode(",", $sunday);    
                        $sundayOpen = $sundayArray[0];    
                        $sundayClose = $sundayArray[count($sundayArray) - 1 ];    
                    }else{
                        $sundayOpen = " ";
                        $sundayClose = " ";
                    }
                    
                    
                    if(count($TrainerPhoto) > 0){
                        $trainerPhoto1 = $TrainerPhoto[0]->image;
                    }else{
                        $trainerPhoto1 = "";
                    }
                    
                    
                    if (!empty( $trainerData->first_name)) {
                      $first_name = $trainerData->first_name;
                    }else{
                        $first_name = "";
                    }
                    
                    
                    if (!empty( $trainerData->phone_number )) {
                        $phone_number = $trainerData->phone_number;
                    }else{
                        $phone_number = "";
                    }
                    
                    
                    if (!empty( $trainerData->map_longitude)) {
                        $map_longitude = $trainerData->map_longitude;
                    }else{
                        $map_longitude = "";
                    }
                        
                    if (!empty( $trainerData->map_latitude)) {
                        $map_latitude = $trainerData->map_latitude;
                    }else{
                        $map_latitude = "";
                    }
                        
                    if (!empty($trainerData->email)) {
                        $email = $trainerData->email;
                    }else{
                        $email = "";
                    }
                        
                    if (!empty($trainerData->zip_code)) {
                        $postal_code = $trainerData->zip_code;
                    }else{
                        $postal_code = "";
                    }
                        
                    if (!empty($trainerData->country)) {
                        $region = $trainerData->country;
                    }else{
                        $region = "";
                    }
                        
                    if (!empty($trainerData->state)) {
                        $address_localty = $trainerData->state;
                    }else{
                        $address_localty = "";
                    }
                        
                    if (!empty($trainerData->address_1)) {
                        $address = $trainerData->address_1;
                    }else{
                        $address = "";
                    }
                        
                    if (!empty($trainerData->business_name)) {
                        $business_name = $trainerData->business_name;
                    }else{
                        $business_name = "";
                    }
                        
                    if (!empty($trainerData->business_name)) {
                        $metaTitle = $trainerData->business_name;
                    }else{
                        $metaTitle = "";
                    }
                        
                    if (!empty($trainerData->bio)) {
                        $metaDescription = str_limit(strip_tags($trainerData->bio), $limit = 160, $end = '...');
                    }else{
                        $metaDescription = "";
                    }
                        
                        
                    if (!empty($trainerData->business_name)) {
                        $metaOgTitle = $trainerData->business_name;
                    }else{
                        $metaOgTitle = "";
                    }
                        
                    if (!empty($trainerData->bio)) {
                        $metaOgDescription = str_limit(strip_tags($trainerData->bio), $limit = 160, $end = '...');
                    }else{
                        $metaOgDescription = "";
                    }
                        
                    if (!empty($trainerData->photo)) {
                        $metaOgImage = $trainerData->photo;
                    }else{
                        $metaOgImage = "";
                    }
                        
                    if (!empty($trainerData->photo)) {
                        $metaTwitterCard = $trainerData->photo;
                    }else{
                        $metaTwitterCard = "";
                    }
                        
                    if (!empty($trainerData->business_name)) {
                        $metaTwitterTitle = $trainerData->business_name;
                    }else{
                        $metaTwitterTitle = "";
                    }
    
if (!empty($trainerData->bio)) {
    $metaDescription = str_limit(strip_tags($trainerData->bio), $limit = 160, $end = '...');
}else{
    $metaDescription = " ";
}
    
    
if (!empty($trainerData->business_name)) {
    $metaOgTitle = $trainerData->business_name;
}else{
    $metaOgTitle = "";
}
    
if (!empty($trainerData->bio)) {
    $metaOgDescription = str_limit(strip_tags($trainerData->bio), $limit = 160, $end = '...');
}else{
    $metaOgDescription = " ";
}
    
if (!empty($trainerData->photo)) {
    $metaOgProImage = $trainerData->photo;
}else{
    $metaOgProImage = "";
}
    
if (!empty($trainerData->photo)) {
    $metaTwitterCard = $trainerData->photo;
}else{
    $metaTwitterCard = "";
}
// dd($trainerData);
    
if (!empty($trainerData->business_name)) {
    $metaTwitterTitle = $trainerData->business_name;
}else{
    $metaTwitterTitle = "";
}
    
if (!empty($trainerData->bio)) {
    $metaTwitterDescription = str_limit(strip_tags($trainerData->bio), $limit = 160, $end = '...');
}else{
    $metaTwitterDescription = "";   
}

if (!empty($trainerData->photo)) {
    $metaTwitterProImage = $trainerData->photo;
}else{
    $metaTwitterProImage = "";
}
                        $metaCanonical = URL::current();  
                        $metaOgUrl = URL::current();   
                        // $ratings = Ratings::where('id', 2);
                    
                    $rating = DB::table('ratings')
                                     ->select(DB::raw('round(AVG(rating),0) as AvgRating'))
                                     ->where('trainer_id', '=', $trainerData->id)
                                     ->get();   
                    
                    
                                     if (!empty($rating)) {
                                        $ratings = $rating;
                                    }else{
                                        $ratings = "0";
                                    }


if ($rating[0]->AvgRating != null) {
    $ratings = $rating[0]->AvgRating;
}else{
    $ratings = "0";
}

                       
if (!empty($trainerData->business_name)) {
    $metakeywords = $trainerData->business_name;
}else{
    $metakeywords = " ";
}

                    return view('front.trainer-detail', compact('ratings','mondayOpen', 'mondayClose', 'tuesdayOpen', 'tuesdayClose', 'wednesdayOpen', 'wednesdayClose', 'thusdayOpen', 'thusdayClose', 'fridayOpen', 'fridayClose', 
                'saturdayOpen', 'saturdayClose', 'sundayOpen', 'sundayClose', 'trainerPhoto1', 'first_name','phone_number','map_longitude','map_latitude','email',
                'postal_code','region','address_localty','address','business_name','metaDescription','metaTitle','metaOgDescription','metaOgProImage','metaTwitterCard','metaTwitterTitle',
                'metaTwitterDescription','metaTwitterProImage','metaCanonical','metaOgUrl','metakeywords'),['Trainerdata' => $Trainerdata,'recommended' => $recommended,'recommended_authlete' => $recommended_authlete,'recommended_provider' => $recommended_provider,'TrainerPhoto' => $TrainerPhoto,"trainerreviewData" => $trainerreviewData, "trainerData" => $trainerData, "eventData" => json_encode($returnarray), "trainerList" => $trainerList, "TrainerAcountdata" => $TrainerAcountdata, "trainerResources" => $trainerResources , "resourceCategory" =>  $resourceCategory, "title" => $title, "customerId" => $customerId, "keyword" => $keyword, "category" => $category, "format" => $format, "name" => $name, 'resource_tab_search_name' => $resource_tab_search_name]);
        }
                 else{
//                 if (isset(Auth::guard('front_auth')->user()->id)){
//                     $Trainerdata->where('front_users.id','!=', Auth::guard('front_auth')->user()->id);
//                 }
//                 $Trainerdata = $Trainerdata->orderByRaw("RAND()")->limit(5)->get();
                //$services = FrontUsers::find($trainerService->trainer_id)->services()->with(['service'])->get();
                 $customerId = Auth::guard('front_auth')->user();
                       if($customerId !=''){
                            if($customerId->user_role =='customer'){
                                $customerId = Auth::guard('front_auth')->user()->id;
                            } else {
                                $customerId = Auth::guard('front_auth')->user()->id;
                            }
                       } else {
                            $customerId = '0';
                       }
                   $keyword = '';
                   $category = '';
                   $format = '';
                   $resource_tab_search_name = '';



                   if($trainerData->day1 != null){
                    $monday = $trainerData->day1;
                    $mondayArray = explode(",", $monday);    
                    $mondayOpen = $mondayArray[0];    
                    $mondayClose = $mondayArray[count($mondayArray) - 1];    
                }else{
                    $mondayOpen = " ";
                    $mondayClose = " ";
                }
                
                if($trainerData->day2 != null){
                    $tuesday = $trainerData->day2;
                    $tuesdayArray = explode(",", $tuesday);    
                    $tuesdayOpen = $tuesdayArray[0];    
                    $tuesdayClose = $tuesdayArray[count($tuesdayArray) - 1];    
                }else{
                    $tuesdayOpen = " ";
                    $tuesdayClose = " ";
                }
                
                if($trainerData->day3 != null){
                    $wednesday = $trainerData->day3;
                    $wednesdayArray = explode(",", $wednesday);    
                    $wednesdayOpen = $wednesdayArray[0];    
                    $wednesdayClose = $wednesdayArray[count($wednesdayArray) - 1];    
                }else{
                    $wednesdayOpen = " ";
                    $wednesdayClose = " ";
                }
                
                if($trainerData->day4 != null){
                    $thusday = $trainerData->day4;
                    $thusdayArray = explode(",", $thusday);    
                    $thusdayOpen = $thusdayArray[0];    
                    $thusdayClose = $thusdayArray[count($thusdayArray) - 1];    
                }else{
                    $thusdayOpen = " ";
                    $thusdayClose = " ";
                }
                
                if($trainerData->day5 != null){
                    $friday = $trainerData->day5;
                    $fridayArray = explode(",", $friday);    
                    $fridayOpen = $fridayArray[0];    
                    $fridayClose = $fridayArray[count($fridayArray) - 1];    
                }else{
                    $fridayOpen = " ";
                    $fridayClose = " ";
                }
                
                if($trainerData->day6 != null){
                    $saturday = $trainerData->day6;
                    $saturdayArray = explode(",", $saturday);    
                    $saturdayOpen = $saturdayArray[0];    
                    $saturdayClose = $saturdayArray[count($saturdayArray) - 1];    
                }else{
                    $saturdayOpen = " ";
                    $saturdayClose = " ";
                }

                if($trainerData->day7 != null){
                    $sunday = $trainerData->day7;
                    $sundayArray = explode(",", $sunday);    
                    $sundayOpen = $sundayArray[0];    
                    $sundayClose = $sundayArray[count($sundayArray) - 1 ];    
                }else{
                    $sundayOpen = " ";
                    $sundayClose = " ";
                }
                

if(count($TrainerPhoto) > 0){
    $trainerPhoto1 = $TrainerPhoto[0]->image;
}else{
    $trainerPhoto1 = "";
}


if (!empty( $trainerData->first_name)) {
  $first_name = $trainerData->first_name;
}else{
    $first_name = "";
}


if (!empty( $trainerData->phone_number )) {
    $phone_number = $trainerData->phone_number;
}else{
    $phone_number = "";
}


if (!empty( $trainerData->map_longitude)) {
    $map_longitude = $trainerData->map_longitude;
}else{
    $map_longitude = "";
}
    
if (!empty( $trainerData->map_latitude)) {
    $map_latitude = $trainerData->map_latitude;
}else{
    $map_latitude = "";
}
    
if (!empty($trainerData->email)) {
    $email = $trainerData->email;
}else{
    $email = "";
}
    
if (!empty($trainerData->zip_code)) {
    $postal_code = $trainerData->zip_code;
}else{
    $postal_code = "";
}
    
if (!empty($trainerData->country)) {
    $region = $trainerData->country;
}else{
    $region = "";
}
    
if (!empty($trainerData->state)) {
    $address_localty = $trainerData->state;
}else{
    $address_localty = "";
}
    
if (!empty($trainerData->address_1)) {
    $address = $trainerData->address_1;
}else{
    $address = "";
}
    
if (!empty($trainerData->business_name)) {
    $business_name = $trainerData->business_name;
}else{
    $business_name = "";
}
    
if (!empty($trainerData->business_name)) {
    $metaTitle = $trainerData->business_name;
}else{
    $metaTitle = "";
}
    
if (!empty($trainerData->bio)) {
    $metaDescription = str_limit(strip_tags($trainerData->bio), $limit = 160, $end = '...');
}else{
    $metaDescription = " ";
}
    
    
if (!empty($trainerData->business_name)) {
    $metaOgTitle = $trainerData->business_name;
}else{
    $metaOgTitle = "";
}
    
if (!empty($trainerData->bio)) {
    $metaOgDescription = str_limit(strip_tags($trainerData->bio), $limit = 160, $end = '...');
}else{
    $metaOgDescription = " ";
}
    
if (!empty($trainerData->photo)) {
    $metaOgProImage = $trainerData->photo;
}else{
    $metaOgProImage = "";
}
    
if (!empty($trainerData->photo)) {
    $metaTwitterCard = $trainerData->photo;
}else{
    $metaTwitterCard = "";
}
// dd($trainerData);
    
if (!empty($trainerData->business_name)) {
    $metaTwitterTitle = $trainerData->business_name;
}else{
    $metaTwitterTitle = "";
}
    
if (!empty($trainerData->bio)) {
    $metaTwitterDescription = str_limit(strip_tags($trainerData->bio), $limit = 160, $end = '...');
}else{
    $metaTwitterDescription = "";   
}

if (!empty($trainerData->photo)) {
    $metaTwitterProImage = $trainerData->photo;
}else{
    $metaTwitterProImage = "";
}
 
    $metaCanonical = URL::current();  
    $metaOgUrl = URL::current();   
    // $ratings = Ratings::where('id', 2);

$rating = DB::table('ratings')
                 ->select(DB::raw('round(AVG(rating),0) as AvgRating'))
                 ->where('trainer_id', '=', $trainerData->id)
                 ->get();   


if ($rating[0]->AvgRating != null) {
    $ratings = $rating[0]->AvgRating;
}else{
    $ratings = "0";
}

                       
if (!empty($trainerData->business_name)) {
    $metakeywords = $trainerData->business_name;
}else{
    $metakeywords = " ";
}
return view('front.trainer-detail', compact('ratings','mondayOpen', 'mondayClose', 'tuesdayOpen', 'tuesdayClose', 'wednesdayOpen', 'wednesdayClose', 'thusdayOpen', 'thusdayClose', 'fridayOpen', 'fridayClose', 
'saturdayOpen', 'saturdayClose', 'sundayOpen', 'sundayClose', 'trainerPhoto1', 'first_name','phone_number','map_longitude','map_latitude','email',
'postal_code','region','address_localty','address','business_name','metaDescription','metaTitle','metaOgDescription','metaOgProImage','metaTwitterCard','metaTwitterTitle',
'metaTwitterDescription','metaTwitterProImage','metaCanonical','metaOgUrl','metakeywords'),['Trainerdata' => $Trainerdata,'recommended' => $recommended,'recommended_authlete' => $recommended_authlete,'recommended_provider' => $recommended_provider,'TrainerPhoto' => $TrainerPhoto,"trainerreviewData" => $trainerreviewData, "trainerData" => $trainerData, "eventData" => json_encode($returnarray), "trainerList" => $trainerList, "TrainerAcountdata" => $TrainerAcountdata, "trainerResources" => $trainerResources , "resourceCategory" =>  $resourceCategory, "title" => $title, "customerId" => $customerId, "keyword" => $keyword, "category" => $category, "format" => $format, "name" => $name, 'resource_tab_search_name' => $resource_tab_search_name]);
}
            }



            public function bookNowLogin($booknow) {
                //dd($booknow);
                if ($booknow == 'booknow') {
                    $tabName = "customer";
                    session(['booknow' => true, 'link' => url()->previous()]);
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
                        // Mail::send('email.payment-invoice', ["invoice" => $invoice], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                        //     $message->from($admin_email, $admin_name);
                        //     $message->to($emails, $emails_name)->subject($subject);
                        // });
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
                        // Mail::send('email.payment-invoice', ["invoice" => $invoice], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                        //     $message->from($admin_email, $admin_name);
                        //     $message->to($emails, $emails_name)->subject($subject);
                        // });
                        break;
                    // ... handle other event types
                    default:
                        // Unexpected event type
                        http_response_code(400);
                        exit();
                }

                http_response_code(200);
            }

            public function sendMail(Request $request){
                $subject = "Your profile created.";
                $emails_name = 'Training Block';
                $admin_email = "auto-reply@trainingblockusa.com"; 
                $admin_name = "Training Block";
                $emails = "testineed@gmail.com";
                $data = [];
                $frontUser = FrontUsers::where('id', 107)->first();
                // Mail::send('email.test', ["invoice" => $data], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                //     $message->from($admin_email, $admin_name);
                //     $message->to($emails, $emails_name)->subject($subject);
                // });
                //dd($frontUser);
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

            public function blogs(){

                $category = BlogCategories::where('status','active')
                                            ->orderBy('title','ASC')
                                            ->get();
                $metaTitle = "Blog";
                $data = ['category'=>$category, 'metaTitle'=>$metaTitle];
                return view('front.blogs-list',$data);
            }

            public function blogsList(Request $request){
                $id = $request->id;

                $where = array('status'=>'active');
                if($id != "all" && $id != null){
                    $where['blog_category_id'] = $id;
                }

                $blogs = Blogs::where($where)->orderBy('created_time','DESC')->paginate(4);
                $pagination = view('front.ajax.blogslist',['blogs'=>$blogs,'pagination'=>true])->render();
                $blogs = view('front.ajax.blogslist',['blogs'=>$blogs])->render();

                $data = ['pagination'=>$pagination,'blogs'=>$blogs];
                return response()->json($data);
            }

            public function blogsDetails($slug){
                $details = Blogs::where('slug',$slug)->firstOrFail();

                $blogs = array();
                if($details){
                       
if (!empty($details->title)) {
    $metaTitle = str_limit(strip_tags($details->title), $limit = 160, $end = '...');
}else{
    $metaTitle = "";
}
                         
if (!empty($details->title)) {
    $metaTwitterTitle = str_limit(strip_tags($details->title), $limit = 160, $end = '...');
}else{
    $metaTwitterTitle = "";
}
                       
if (!empty($details->title)) {
    $metaOgTitle = str_limit(strip_tags($details->title), $limit = 160, $end = '...');
}else{
    $metaOgTitle = "";
}
                       
if (!empty($details->meta_description)) {
    $metaDescription = str_limit(strip_tags($details->meta_description), $limit = 160, $end = '...');
}else{
    $metaDescription = "";
}
                       
if (!empty($details->meta_description)) {
    $metaTwitterDescription = str_limit(strip_tags($details->meta_description), $limit = 160, $end = '...');
}else{
    $metaTwitterDescription = "";
}
                       
if (!empty($details->meta_keywords)) {
    $metakeywords = $details->meta_keywords;
}else{
    $metakeywords = "";
}
                       
if (!empty($details->image)) {
    $metaOgBlogImage = $details->image;
}else{
    $metaOgBlogImage = "";
}
                       
if (!empty($details->image)) {
    $metaTwitterBlogImage = $details->image;
}else{
    $metaTwitterBlogImage = "";
}
                       
if (!empty($details->slug)) {
    $metaSlug = $details->slug;
}else{
    $metaSlug = "";
}



                    $where = array(
                                'status'=>'active',
                                'blog_category_id'=>$details->blog_category_id
                            );
                    $blogs = Blogs::where($where)
                                    ->orderBy('created_time','DESC')
                                    ->where('id','!=',$details->id)
                                    ->get();

                    $metaCanonical = URL::current();

                    return view('front.blogs-details',['details'=>$details,'metaSlug'=>$metaSlug,'blogs'=>$blogs,'metaCanonical'=>$metaCanonical
                    ,'metaTitle'=>$metaTitle,'metaTwitterTitle'=>$metaTwitterTitle,'metaTwitterDescription'=>$metaTwitterDescription,'metaOgTitle'=>$metaOgTitle,
                    'metaDescription'=>$metaDescription,'metakeywords'=>$metakeywords,'metaOgBlogImage'=>$metaOgBlogImage,'metaTwitterBlogImage'=>$metaTwitterBlogImage]);
                }
            }

            public function review_rating($id){
                $user = \Auth::guard('front_auth')->user();
                if(!$user)
                {
                    session()->put('review-url',\URL::current());
                    return redirect('login');
                }else{
                    session()->put('review-url',false);
                    if($user->user_role != "trainer"){
                        $trainerId = base64_decode($id);


                        $trainer = FrontUsers::where(["id" => $trainerId])->firstOrFail();
                        $rating = Ratings::where(["user_id" => $user->id,"trainer_id" => $trainerId])->first();
                        return view('front.rating',["trainer" => $trainer,'rating'=>$rating]);
                    }else{
                        return redirect('/');
                    }
                }
            }

            public function getlocationdata(Request $request) {
                $search = $request->search;
                $location = FrontUsers::Where('user_role','trainer')
                ->where(function($query) use ($search) {
                    $query->where('city', 'like', '%' . trim($search) . '%')
                          ->orwhere('state', 'like', '%' . trim($search) . '%');
                })
                ->groupBy('city', 'state')->get();
                //if(count($location) > 0){
                    $output = '<ul class="list-group">';  
                    foreach ($location as $value) {
                            $output .= '<li class="list-group-item">'.ucwords(strtolower($value->city)).', '.$value->state_code.'</li>';          
                    }
                    $output .= '</ul>'; 
                /*} else {
                    $location = FrontUsers::Where('user_role','trainer')->where('state', 'like', '%' . trim($search) . '%')->groupBy('city', 'state')->get();
                    $output = '<ul class="list-group">';  
                    foreach ($location as $value) {
                            $output .= '<li class="list-group-item">'.$value->city.', '.$value->state_code.'</li>';               
                    }
                    $output .= '</ul>'; 
                } */
                echo $output;  
            }
        
            public function changecounter($id) {
                $user = \Auth::guard('front_auth')->user();
                $advertisement = Advertisement::find($id);
                $changecounter = new AdvertisementDetails ();
                $changecounter->advertisement_id = $id;
                if (!empty($user)) {
                    $changecounter->user_id = $user->id;
        }
                $changecounter->price = $advertisement->amount;
                $changecounter->save();
            }
            public function getadv(Request $request) {
                $user = \Auth::guard('front_auth')->user();
                $ipurl = 'http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR'];
                $ip_location_data = file_get_contents($ipurl);
                $file = public_path('ipdetails.txt');
                if (file_exists($file)) {
                    file_put_contents($file, PHP_EOL . PHP_EOL . "----------------------   IP Details   ----------------------", FILE_APPEND | LOCK_EX);
                    file_put_contents($file, PHP_EOL . $ip_location_data, FILE_APPEND | LOCK_EX); 
                }
                $ip_location_data = json_decode($ip_location_data);
                //        }
                $lon = $ip_location_data->lon;
                $lat = $ip_location_data->lat;
//                $lon = '-81.506';
//                $lat = '30.3511';
                $circle_radius = 3959;
                $max_distance = 100;
                $locations = DB::select(
                                'SELECT * FROM
                            (SELECT id, latitude, longitude, (' . $circle_radius . ' * acos(cos(radians(' . $lat . ')) * cos(radians(latitude)) *
                            cos(radians(longitude) - radians(' . $lon . ')) +
                            sin(radians(' . $lat . ')) * sin(radians(latitude))))
                            AS distance
                            FROM locations) AS distances
                         WHERE distance < ' . $max_distance . '
                        ORDER BY distance asc;
                    '); 
                $location_ids = array();
                if (!empty($locations)) {
                    foreach ($locations as $location) {
                        $location_ids[] = $location->id;
                    }
                }  
                //$location_ids[] = 600;
                $provider_locations = array();
//                if(isset($user->id)){
//                    $provider_locations = \App\ProviderLocations::where('user_id',$user->id)->pluck('location_id')->toArray();
//                }   
                $advertisement = Advertisement::where('status','active')->where('pageview','home')->where('view',$request->type)->where('typeview',$request->side);
//                foreach($provider_locations as $val)
//                {
//                    $advertisement->orwhere(DB::Raw('FIND_IN_SET('.$val.',locations)'));
//                }   
                    
                if(!empty($location_ids)){
                    $advertisement->where(function ($query) use ($location_ids) { 
                        foreach($location_ids as $val)
                        {
                            $query->orWhereRaw('FIND_IN_SET('.$val.',locations)');
                        }
                    });
                }
                $today = date('y-m-d');
                $advertisement->whereDate('start_date','<=', $today)
            ->whereDate('end_date','>=', $today);
//                    echo $advertisement->toSql();exit;
                $advertisement = $advertisement->orderByRaw("RAND()")->first();
                 $html = ''; 
                 if(!empty($advertisement) && !empty($location_ids)){
                     $url = url('public/sitebucket/advertisement/'.$advertisement->image);
                     if($request->type == 'horizontal'){
                         $style = 'style="background-image: url('.$url.');cursor: pointer;height: 90px;"';
                     }else{
                         $style = 'style="background-image: url('.$url.');cursor: pointer;height: 600px;max-width: 160px;margin: 0 auto;"';
                     } 
                     
                     $onclick = 'onclick="linkcount(\''.$advertisement->url.'\','.$advertisement->id.')"';
                     $html = '<div class="advertisement_image" '.$onclick.'   ><img src="'.$url.'" alt="Default Image"></div>';
                 }else{ 
                     if($request->type == 'horizontal'){
                         $url = url('public/images/placeholder_horizontal.png');
                         $style = 'style="background-image: url('.$url.');cursor: pointer;height: 90px;"';
                     }else{
                         $url = url('public/images/placeholder_vertical.png');
                         $style = 'style="background-image: url('.$url.');cursor: pointer;height: 600px;max-width: 160px;margin: 0 auto;"';
                     } 
                     
                     $onclick = '';
                     $html = '<div class="advertisement_image" '.$onclick.'   ><img src="'.$url.'" alt="Default Image"></div>';
                 }
                 echo $html;
            }

            public function showBookServiceDetail($serviceid){
                $user = \Auth::guard('front_auth')->user();
                if(!$user)
                {
                    session()->put('book-service-url',\URL::current());
                    return redirect('login');
                }else{
                    session()->put('book-service-url',false);
                    $serviceid = base64_decode($serviceid);
                    $getServiceDetails = DB::table('trainer_services')->where(["id" => $serviceid])->first(); 
 // dd($getServiceDetails);
 if($getServiceDetails->format == 'In person - Monthly Membership' || $getServiceDetails->format == 'Virtual - Monthly Membership' || $getServiceDetails->format == 'In person - Yearly Membership' || $getServiceDetails->format == 'Virtual - Yearly Membership' || $getServiceDetails->format == 'In person - Package Deal' || $getServiceDetails->format == 'Virtual - Package Deal'){

                        if(Session::has('url.intended'))
                            {
                                Session::forget('url.intended');
                            }
                            
                            $trainerId = $getServiceDetails->trainer_id;
                            if(!(is_numeric($trainerId) && $trainerId > 0 && $trainerId == round($trainerId, 0))){
                              abort(404);
                            }
                            if($trainerId){
                            $states = States::all();
                            
                            $customer = Auth::guard('front_auth')->user();
                          
                            $trainer = FrontUsers::where(["id" => $trainerId])->first();
                              $services = $getServiceDetails;
                            
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
                                    
                                    $adminFees = 0;
                                    $StripeAccountsData = StripeAccounts::where('user_id',$trainer->id)->orderBy('id', 'desc')->first();
                                    if($getServiceDetails->format == 'In person - Package Deal' || $getServiceDetails->format == 'Virtual - Package Deal'){
                                        if($getServiceDetails->book_type == 1){
                                            return view('front.package-deal-book-now', ['customer' => $customer, 'states' => $states, 'trainer'=> $trainer, 'timeSlots' => '', "refDiscount" => $total_discount, "adminFees" => $adminFees,'StripeAccountsData'=> $StripeAccountsData, "services" => $services]);
                                        } else {
                                            return view('front.package-deal-request-now', ['customer' => $customer, 'states' => $states, 'trainer'=> $trainer, 'timeSlots' => '', "refDiscount" => $total_discount, "adminFees" => $adminFees,'StripeAccountsData'=> $StripeAccountsData, "services" => $services]);
                                        }
                                    } else {
                                        if($getServiceDetails->book_type == 1){
                                            return view('front.monthly-yearly-book-now', ['customer' => $customer, 'states' => $states, 'trainer'=> $trainer, 'timeSlots' => '', "refDiscount" => $total_discount, "adminFees" => $adminFees,'StripeAccountsData'=> $StripeAccountsData, "services" => $services]);
                                        } else {
                                            return view('front.monthly-yearly-request-now', ['customer' => $customer, 'states' => $states, 'trainer'=> $trainer, 'timeSlots' => '', "refDiscount" => $total_discount, "adminFees" => $adminFees,'StripeAccountsData'=> $StripeAccountsData, "services" => $services]);
                                        }
                                    }
                               }else{
                                abort(404);
                               }   
                            }else{
                              abort(404);
                            }

                    } else {
                        $providerScheduling = DB::table('provider_service_book')->where(["service_id" => $serviceid])->get();
                        $providerSchedulingcount = DB::table('provider_service_book')->where(["service_id" => $serviceid])->count();
                        $maximumServiceCoint = DB::table('trainer_services')->where(["id" => $serviceid])->first();
                        $returnarray = [];
                        if($providerSchedulingcount !=0){
                            
                            foreach($providerScheduling as $val){
                                $time = explode(',', $val->time);
                                for($i=0;$i<count($time);$i++){
                                    $bookedtimeslot = DB::table('orders')->where(["service_id" => $serviceid])->where(["days" => $val->days])->where(["service_time" => $time[$i]])->count();
                                    $getbookedtimeslot = DB::table('orders')->where(["service_id" => $serviceid])->where(["days" => $val->days])->where(["service_time" => $time[$i]])->first();
                                    if(isset($getbookedtimeslot->appointment_date)){
                                        $appointment_date = $getbookedtimeslot->appointment_date;
                                    } else {
                                        $appointment_date = '';
                                    }
                                    //echo '<pre>';print_r($val);exit();
                                    if($bookedtimeslot == 0){
                                        if($val->date == ''){
                                            if($val->days == 7){
                                                $array = array(
                                                    'title' => $time[$i],
                                                    'daysOfWeek' => [ 0 ],
                                                    'id' => $val->id,
                                                    'start' => date('Y-m-d'),
                                                    'description' => $val->service_id,
                                                );
                                            } else {
                                                $array = array(
                                                    'title' => $time[$i],
                                                    'daysOfWeek' => [ $val->days ],
                                                    'id' => $val->id,
                                                    'start' => date('Y-m-d'),
                                                    'description' => $val->service_id,
                                                );
                                            }
                                            
                                        } else {
                                            $array = array(
                                                'title' => $time[$i],
                                                'id' => $val->id,
                                                'start' => $val->date,
                                                'description' => $val->service_id,
                                            );
                                        }
                                        
                                        $returnarray[] = $array;
                                    } else if($bookedtimeslot < $maximumServiceCoint->max_bookings){
                                        if($val->date == ''){
                                            if($val->days == 7){
                                                $array = array(
                                                    'title' => $time[$i],
                                                    'daysOfWeek' => [ 0 ],
                                                    'id' => $val->id,
                                                    'start' => date('Y-m-d'),
                                                    'description' => $val->service_id,
                                                );
                                            } else {
                                               $array = array(
                                                    'title' => $time[$i],
                                                    'daysOfWeek' => [ $val->days ],
                                                    'id' => $val->id,
                                                    'start' => date('Y-m-d'),
                                                    'description' => $val->service_id,
                                                ); 
                                            }
                                            
                                        } else {
                                            $array = array(
                                                'title' => $time[$i],
                                                'id' => $val->id,
                                                'start' => $val->date,
                                                'description' => $val->service_id,
                                            );
                                        }
                                        $returnarray[] = $array;
                                    } else {
                                        if($val->date == ''){
                                            if($val->days == 7){
                                                $array = array(
                                                    'title' => $time[$i],
                                                    'daysOfWeek' => [ 0 ],
                                                    'id' => $val->id,
                                                    'start' => date('Y-m-d'),
                                                   // 'end' => $appointment_date,
                                                    'description' => $val->service_id,
                                                );
                                            } else {
                                                $array = array(
                                                    'title' => $time[$i],
                                                    'daysOfWeek' => [ $val->days ],
                                                    'id' => $val->id,
                                                    'start' => date('Y-m-d'),
                                                   // 'end' => $appointment_date,
                                                    'description' => $val->service_id,
                                                );
                                            }
                                            
                                        } else {
                                            $array = array(
                                                'title' => $time[$i],
                                                'id' => $val->id,
                                                'start' => $val->date,
                                                //'end' => $appointment_date,
                                                'description' => $val->service_id,
                                            );
                                        }
                                        $returnarray[] = $array;
                                    }
                                    
                                }
                                
                            }

                        }
                       
                        return view('front.services-book-now',["eventData" => json_encode($returnarray), "serviceid" => $serviceid]);
                    }
                }
               
                
            }


            public function serviceRequestDetail($serviceid){
                //echo '<pre>';print_r(Auth::guard('front_auth')->user());exit();
                $serviceid = base64_decode($serviceid);
                $getServiceDetails = DB::table('trainer_services')->where(["id" => $serviceid])->first();
                $resource_category = DB::table('order_request')->insert([
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'service_id' => $serviceid,
                        'trainer_id' => $getServiceDetails->trainer_id,
                        'first_name' => Auth::guard('front_auth')->user()->first_name,
                        'last_name' => Auth::guard('front_auth')->user()->last_name,
                        'email_id' => Auth::guard('front_auth')->user()->email,
                        'phone' => Auth::guard('front_auth')->user()->phone_number,
                        'reuest_date_time' => date('Y-m-d H:i:s'),
                        'status' => 1
                    ]);

                if($getServiceDetails->format == 'In person - Single Appointment' || $getServiceDetails->format == 'In person - Group Appointment' || $getServiceDetails->format == 'Virtual - Single Appointment' || $getServiceDetails->format == 'Virtual - Group Appointment'){

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
                    <td style="padding:10px;border-bottom:solid 1px #555;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
                </tr>
                <tr>
                    <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
                </tr>

                <tr>
                    <td style="padding-bottom:15px;"> 
                        <p>You have requested to book an appointment for '.$services->name.' with '.$trainer->business_name.', at '.date("F j, Y, g:i a").'. You will receive an email confirmation once this provider has reviewed your information and confirmed or denied this request.</p>
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
                        <td style="padding:10px;border-bottom:solid 1px #555;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
                    </tr>
                    <tr>
                        <td style="padding-top:20px;"> <h4>Hello!</h4> </td>
                    </tr>

                    <tr>
                        <td style="padding-bottom:15px;"> 
                            <p> '.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.' has requested to book an appointment with you for '.$services->name.' at '.date("F j, Y, g:i a").'. Please log in to your account to confirm this appointment.</p>
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

                } else {

                    

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
                        <td style="padding:10px;border-bottom:solid 1px #555;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
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

                }

                
                       
                    //$msg = "Resource Category created successfully.";
                    //Session::flash('message', $msg); 
                    return redirect()->back();
            }
        
            function provider_bussiness_name_check($name){

                $provider_bussiness_name_check = DB::table('front_users')->where('id', '!=' , Auth::guard('front_auth')->user()->id)->where(["business_name" => $name, "user_role" => "trainer"])->count();
                return $provider_bussiness_name_check;
            }

            public function testimonialDetail($id) {
                $id = base64_decode($id);
                $testimonials = Testimonials::find($id);
                return view('front.testimonial', compact('testimonials'));
            }
            
            public function businessPage() {
                $testimonials = DB::Table('testimonials')->where('status', '=', 'active')->orderBy('id', 'DESC')->get();
                return view('front.business', compact('testimonials'));
            }
            
            function provider_login(Request $request)
            {
                // dd(Auth::guard('front_auth')->check());
                // $this->middleware(function ($request, $next) {
                    if (Auth::guard('front_auth')->check()){
                    Session::flash('message', 'You have already logged in...!');
                    return redirect()->to('/');
                    }
                    else {
                // Validate the form data
                $this->validate($request, [
                    'email'    => 'required|email',
                    'password' => 'required|min:6',
                ]);
        
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
                            // return redirect($request->session()->get('url.intended'));
                            return redirect()->to(env('FRONT_URL')."trainer/dashboard");
                        } else {
                            $curUrl = explode("/",$request->prevUrl);
        
                            if(count($curUrl) > 3 && $curUrl[3] === "event-details"){
                                return redirect()->to('/event-details/'. $curUrl[4]);                      
                            }else{
                                return redirect()->to(env('FRONT_URL')."trainer/dashboard");              
                            }   
                        }
                    }
                    if (Auth::guard('front_auth')->user()->user_role == 'customer') {
                        Auth::guard('front_auth')->logout();
                        Session::flash('message', 'Invalid email or password!');
                        return redirect()->to('/business');
        
                    }
        
                } else {
                    $request->session()->flash('tabName', $request->user_role);
                    // if unsuccessful, then redirect back to the login with the form data
                    return redirect()->back()->withErrors(['Invalid email or password!'])->withInput($request->only('email', 'remember'));
                }
             
                        }
                    // });
                    }
        
    
            }
            