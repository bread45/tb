<?php

namespace App\Http\Controllers;

use Auth;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use Modules\Users\Entities\FrontUsers;
use App\Countries;
use App\States;
use App\TrainerServices;
use Modules\Orders\Entities\Orders;
use Modules\Locations\Entities\Locations;
use App\Ratings;
use App\StripeAccounts;
use App\TrainerPhoto;
use App\TrainerResourcePhoto;
use App\ProviderLocations;
use App\ResourceImage;

use Session;
use Stripe;
use Validator;
use Hash;
use Image;
use DB,
    DataTables,
    Redirect,
    Mail,Str;
use Carbon\Carbon;
use App\RecommendedProviders;
use App\Groups;
use App\GroupsUsers;
use App\InviteFriend;
use App\Services;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once(app_path().'/../mail/PHPMailer/Exception.php');
include_once(app_path().'/../mail/PHPMailer/PHPMailer.php');
include_once(app_path().'/../mail/PHPMailer/SMTP.php');
include_once(app_path().'/../mail/vendor/autoload.php');

class FrontUserCountroller extends Controller {


    public function __construct() {

        $this->middleware(function ($request, $next) {
            if(Auth::guard('front_auth')->check()){
            $this->id = Auth::guard('front_auth')->user()->id;
            $providerStatus = DB::table('front_users')->where(["id" => $this->id])->get();
            $trailingProviderOrders = DB::table('provider_orders')->where(["trainer_id" => $this->id, "status" => 0, "subscription_status" => "trialing"])->get()->count();
            $providerOrdersCount = DB::table('provider_orders')->where(["trainer_id" => $this->id, "status" => 0, "subscription_status" => "active"])->get()->count();
            \View::share(['providerOrdersCount' => $providerOrdersCount, 'providerStatus' => $providerStatus, 'trailingProviderOrders' => $trailingProviderOrders]);
            
            }
            return $next($request);
        });
    }

    // public function __construct(Request $request) {
    //     $this->middleware('front.user');
    //     $user = Auth::guard('front_auth')->user();

    //     $this->middleware(function ($request, $next) {
    //         $this->id = Auth::guard('front_auth')->user()->id;
    //         // dd($this->id);
    //         $providerStatus = DB::table('front_users')->where(["id" => $this->id])->get();
    //         $trailingProviderOrders = DB::table('provider_orders')->where(["trainer_id" => $this->id, "status" => 0, "subscription_status" => "trialing"])->get()->count();
    //         $providerOrdersCount = DB::table('provider_orders')->where(["trainer_id" => $this->id, "status" => 0, "subscription_status" => "active"])->get()->count();
    //         // dd($trailingProviderOrders);
    //         \View::share(['providerOrdersCount' => $providerOrdersCount, 'providerStatus' => $providerStatus, 'trailingProviderOrders' => $trailingProviderOrders]);
    //         return $next($request);
    //     });
    // }


    public function profile(Request $request) {
        $user = Auth::guard('front_auth')->user();
        $countries = Countries::all();
        //$states = ['Jacksonville','Florida','Orlando'];
        $TrainerData = FrontUsers::all();
        // dd($user);
        $states = States::all();
        $locations = Locations::where('status', 'active')->orderBy('name', 'asc')->get();
        $providerlocations = ProviderLocations::where('user_id', $user->id)->pluck('location_id')->toArray();
        //dd($states);providerlocations
        $StripeAccountsdata = StripeAccounts::where('user_id', $user->id)->orderBy('id', 'desc')->first();
        $TrainerPhoto = TrainerPhoto::where('trainer_id', $user->id)->orderBy('position', 'asc')->get();
        return view('front.trainer.profile', ['user' => $user, 'countries' => $countries, 'states' => $states, 'StripeAccountsdata' => $StripeAccountsdata, 'TrainerPhoto' => $TrainerPhoto, 'locations' => $locations, 'providerlocations' => $providerlocations]);
    }

      public function get_cloud_thumbnail(Request $request) {
        echo ($img = Image::make(file_get_contents($request->image))->encode('jpg', 100)->encode('data-url'));
    }

     public function update_profile_images(Request $request) {
        /*foreach($request->profile_data as $vv){
            $user = FrontUsers::find(Auth::guard('front_auth')->user()->id);

            if($vv['name'] == 'phone_number'){
                $user->phone_number = isset($vv['value']) ? $vv['value'] : '';
            }
            if($vv['name'] == 'address_1'){
                $user->address_1 = isset($vv['value']) ? $vv['value'] : '';
            }
            if($vv['name'] == 'address_2'){
                $user->address_2 = isset($vv['value']) ? $vv['value'] : '';
            }
            if($vv['name'] == 'city'){
                $user->city = isset($vv['value']) ? $vv['value'] : '';
            }
            if($vv['name'] == 'state'){
                $user->state = isset($vv['value']) ? $vv['value'] : '';
            }
            if($vv['name'] == 'country'){
                $user->country = isset($vv['value']) ? $vv['value'] : '';
            }
            if($vv['name'] == 'zip_code'){
                $user->zip_code = isset($vv['value']) ? $vv['value'] : '';
            }
            if($vv['name'] == 'fb'){
                $user->facebook = isset($vv['value']) ? $vv['value'] : '';
            }
            if($vv['name'] == 'insta'){
                $user->instagram = isset($vv['value']) ? $vv['value'] : '';
            }
            if($vv['name'] == 'linkedin'){
                $user->linkedin = isset($vv['value']) ? $vv['value'] : '';
            }
            if($vv['name'] == 'website'){
                $user->website = isset($vv['value']) ? $vv['value'] : '';
            }
            if($vv['name'] == 'headline'){
                $user->headline = isset($vv['value']) ? $vv['value'] : '';
            }
            /*if($vv['name'] == 'bio'){
                $user->bio = isset($vv['value']) ? $vv['value'] : '';
            }*/
            /*$user->bio = isset($request->editorData) ? $request->editorData : '';
        $user->save();
        }*///exit();
        if ($request->profile_type == 'profile') {
            //get filename without extension
            $filename = 'profile_image';
            $photoImageNametrainer = $filename . time() . '.jpg';
            $destinationPath = public_path('/front/profile');
//                        $img = Image::make($photoImage->getRealPath());
//                        $img->resize(325, 210 );
            $img = Image::make(file_get_contents($request->image));
            // $img->resize(325, 210);
            $img->save($destinationPath . '/' . $photoImageNametrainer);
            $user = FrontUsers::find(Auth::guard('front_auth')->user()->id);
            $user->photo = !empty($photoImageNametrainer) ? $photoImageNametrainer : $user->photo;
            $user->save();
            echo asset('front/profile/' . $photoImageNametrainer);
        } else {
            //get filename without extension
            $key = $request->datakey;
            DB::table('trainer_photo')->where('trainer_id', Auth::guard('front_auth')->user()->id)->where('position', $request->dataid)->delete();
            $TrainerPhoto = TrainerPhoto::where('id', $key)->where('trainer_id', Auth::guard('front_auth')->user()->id)->first();
            if (empty($TrainerPhoto)) {
                $TrainerPhoto = new TrainerPhoto();
            }
            $TrainerPhoto->is_video= ['image'=>0,'video' =>1, 'cloud_video'=>2][$request->profile_type];
            if($request->profile_type=='video'){
                $filename = 'profile_video';
                $photoVideoNametrainer = $filename . time() . '.mp4';
                $destinationPath = public_path('/front/profile');
                $data = base64_decode(explode(',', $request->video)[1]);
                // $TrainerPhoto->is_video=1;
            }
            $filename = 'profile_image';
             if($request->profile_type=='cloud_video'){
                $filename='profile_thumb_'. $request->videoid .'_';
            }
            $photoImageNametrainer = $filename . time() . '.jpg';
            $destinationPath = public_path('/front/profile');
            //                        $img = Image::make($photoImage->getRealPath());
            //                        $img->resize(325, 210 );
            $img = Image::make(file_get_contents($request->image));
            // $img->resize(325, 210);

            $TrainerPhoto->trainer_id = Auth::guard('front_auth')->user()->id;
//        $TrainerPhoto->is_featured = (isset($request->featured_image[$key]))?1:0;
            $TrainerPhoto->image = ($request->profile_type=='video'? $photoVideoNametrainer  : $photoImageNametrainer);
            $TrainerPhoto->position = $request->dataid;
            $TrainerPhoto->save();
            $img->save($destinationPath . '/' . $photoImageNametrainer);
            if($request->profile_type=='video'){file_put_contents($destinationPath . '/' . $photoVideoNametrainer , $data);}
            echo json_encode(array('path' => asset('front/profile/' . $photoImageNametrainer), 'id' => $TrainerPhoto->id, 'file_name' => $photoImageNametrainer));
        }
    }

    public function update_resource_images(Request $request) {
        //get filename without extension
        $key = $request->datakey;
        $TrainerPhoto = TrainerResourcePhoto::where('id', $key)->where('trainer_id', Auth::guard('front_auth')->user()->id)->first();
        if (empty($TrainerPhoto)) {
            $TrainerPhoto = new TrainerResourcePhoto();
        }
        $TrainerPhoto->is_video= ['image'=>0,'video' =>1, 'cloud_video'=>2][$request->profile_type];
        if($request->profile_type=='video'){
            $filename = 'resource_video';
            $photoVideoNametrainer = $filename . time() . '.mp4';
            $destinationPath = public_path('/front/images/resource');
            $data = base64_decode(explode(',', $request->video)[1]);
            // $TrainerPhoto->is_video=1;
        }
        $filename = 'resource_image';
         if($request->profile_type=='cloud_video'){
            $filename='resource_thumb_'. $request->videoid .'_';
        }
        $photoVideoNametrainer = $filename . time() . '.mp4';
        $photoImageNametrainer = $filename . time() . '.jpg';
        $destinationPath = public_path('/front/images/resource');
        //                        $img = Image::make($photoImage->getRealPath());
        //                        $img->resize(325, 210 );
        $img = Image::make(file_get_contents($request->image));
        // $img->resize(325, 210);
        $TrainerPhoto->trainer_id = Auth::guard('front_auth')->user()->id;
//        $TrainerPhoto->is_featured = (isset($request->featured_image[$key]))?1:0;
        $TrainerPhoto->image = ($request->profile_type=='video'? $photoVideoNametrainer  : $photoImageNametrainer);
        $TrainerPhoto->position = $request->dataid;
        $TrainerPhoto->save();
        $img->save($destinationPath . '/' . $photoImageNametrainer);
        if($request->profile_type=='video'){file_put_contents($destinationPath . '/' . $photoVideoNametrainer , $data);}
        echo json_encode(array('path' => asset('front/images/resource/' . $photoImageNametrainer), 'id' => $TrainerPhoto->id, 'file_name' => $photoImageNametrainer, 'video_name' => asset('front/images/resource/' . $photoVideoNametrainer)));
        
    }


     public function not_null_time($hoptime) {
    return($hoptime);
 }


     public function update_profile(Request $request) {
        ini_set('memory_limit', '-1');
        $userOld = Auth::guard('front_auth')->user();
        $requestData = $request->all();
if ($requestData["role_type"] == 'customer') {
    $errors = Validator::make($requestData, [
                    'first_name' => 'required_with:last_name',
                    'last_name' => 'required_with:first_name',
                    'email' => 'required|email|unique:front_users,email,' . Auth::guard('front_auth')->user()->id . ',id',
//                    'business_name' => 'required',
//                    'confirm_password' => 'required_with:password|same:password',
                    'photo' => 'sometimes|nullable|mimes:jpeg,png,jpg',
//                    "headline" => 'max:150'
        ]);
}else{
    $errors = Validator::make($requestData, [
//                    'first_name' => 'required_with:last_name',
//                    'last_name' => 'required_with:first_name',
                    'email' => 'required|email|unique:front_users,email,' . Auth::guard('front_auth')->user()->id . ',id',
                    'business_name' => 'required|max:50|unique:front_users,business_name,' . Auth::guard('front_auth')->user()->id . ',id',
                    'confirm_password' => 'required_with:password|same:password',
                    'photo' => 'sometimes|nullable|mimes:jpeg,png,jpg',
                    "headline" => 'max:150'
        ]);
}
        
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
            $user = FrontUsers::find(Auth::guard('front_auth')->user()->id);

            if ($user) {
                $photoImageName = '';
                if (isset($requestData['photo']) && !empty($requestData['photo'])) {
                    $photoImage = $request->photo;
                    $photoImageName = time() . '.' . $photoImage->getClientOriginalExtension();
                    $destinationPath = public_path('/front/profile');
                    $img = Image::make($photoImage->getRealPath());
                    //$img->resize(150, 150);
//                    $img->resize(150, 150, function ($constraint) {
//                         $constraint->aspectRatio();
//                     });
                    $img->save($destinationPath . '/' . $photoImageName);
//                    $photoImage->move($destinationPath, $photoImageName);
                    if ($requestData["role_type"] == 'customer') {                    
                      $user->photo = !empty($photoImageName) ? $photoImageName : '';  
                  }                    
                }
                if (isset($requestData['providerlocations']) && !empty($requestData['providerlocations'])) {
                    $locations = Locations::find($requestData['providerlocations'][0]);
                    $user->city = $locations->name;
                    foreach ($request->providerlocations as $key => $locations) {
                        $providerlocations = ProviderLocations::where('user_id', $user->id)->where('location_id', $locations)->first();
                        if (empty($providerlocations)) {
                            $providerlocations = new ProviderLocations();
                        }
                        $providerlocations->user_id = $user->id;
                        $providerlocations->location_id = $locations;
                        $providerlocations->save();
                    }
                    $providerlocationsids = ProviderLocations::where('user_id', $user->id)->get();

                    foreach ($providerlocationsids as $key => $locations) {
                        if (!in_array($locations->location_id, $request->providerlocations)) {
                            $providerlocations = ProviderLocations::where('user_id', $user->id)->where('location_id', $locations->location_id)->delete();
                        }
                    }
                }
                if (isset($requestData['featured_image']) && !empty($requestData['featured_image'])) {
                    foreach ($request->featured_image as $key => $photo) {
                        $Trainerimage = TrainerPhoto::where('trainer_id', $user->id)->update(['is_featured' => 0]);
                        $TrainerPhoto = TrainerPhoto::where('id', $key)->where('trainer_id', $user->id)->first();
                        if (!empty($TrainerPhoto)) {
                            $TrainerPhoto->is_featured = 1;
                            $photoImageName = $TrainerPhoto->image;
                            $TrainerPhoto->save();
                        }
                    }
                } else {
                    $Trainerimage = TrainerPhoto::where('trainer_id', $user->id)->update(['is_featured' => 0]);
                    $TrainerPhoto = TrainerPhoto::where('trainer_id', $user->id)->orderBy('position', 'asc')->first();
                    if (!empty($TrainerPhoto)) {
                        $TrainerPhotoCount = TrainerPhoto::where('trainer_id', $user->id)->count();
                        $TrainerVideoPhotoCount = TrainerPhoto::where('is_video', 0)->where('trainer_id', $user->id)->count();
                        if($TrainerVideoPhotoCount == 4){
                            $TrainerPhoto = TrainerPhoto::where('trainer_id', $user->id)->where('position', 1)->first();
                                $photoImageName = $TrainerPhoto->image;
                        } else if($TrainerPhotoCount == 3){
                            $TrainerPhotoLeftCount = TrainerPhoto::where('trainer_id', $user->id)->whereIn('position', [2, 3, 4])->count();
                            ///$TrainerPhotoLeftCounts = TrainerPhoto::where('trainer_id', $user->id)->whereIn('position', [1, 2, 4])->count();
                            //echo '<pre>';print_r($TrainerPhotoLeftCount);exit();
                            if($TrainerPhotoLeftCount == 3){
                                $TrainerPhoto = TrainerPhoto::where('trainer_id', $user->id)->where('position', 2)->first();
                                $photoImageName = $TrainerPhoto->image;
                           /* } else if($TrainerPhotoLeftCounts == 3){
                                $TrainerPhoto = TrainerPhoto::where('trainer_id', $user->id)->where('position', 2)->first();
                                $photoImageName = $TrainerPhoto->image;*/
                            }else {
                                if($TrainerPhoto->position == 1 && $TrainerVideoPhotoCount == 0){
                                        $imagePath = preg_replace('/.mp4|.mov/i', '.jpg', $TrainerPhoto->image);
                                        $imagePath = preg_replace('/profile_video/i', 'profile_image', $imagePath);
                                      $photoImageName = $imagePath;
                                    } else {
                                        $TrainerImagePhoto = TrainerPhoto::where('is_video', 0)->where('trainer_id', $user->id)->first();
                                        $TrainerPhotoLeftCount = TrainerPhoto::where('trainer_id', $user->id)->whereIn('position', [2, 3])->count();
                                        $TrainerPhotoLeftCounts = TrainerPhoto::where('trainer_id', $user->id)->whereIn('position', [2, 4])->count();
                                        $TrainerPhotoLeftCountss = TrainerPhoto::where('trainer_id', $user->id)->whereIn('position', [3, 4])->count();
                                        if($TrainerPhotoLeftCount == 2){
                                            $TrainerPhoto = TrainerPhoto::where('trainer_id', $user->id)->where('position', 3)->first();
                                            $photoImageName = $TrainerPhoto->image;
                                        } else if($TrainerPhotoLeftCounts == 2){
                                            $TrainerPhoto = TrainerPhoto::where('trainer_id', $user->id)->where('position', 2)->first();
                                            $photoImageName = $TrainerPhoto->image;
                                        } else if($TrainerPhotoLeftCountss == 2){
                                            $TrainerPhoto = TrainerPhoto::where('trainer_id', $user->id)->where('position', 3)->first();
                                            $photoImageName = $TrainerPhoto->image;
                                        }
                                    }
                            }
                        } else if($TrainerPhoto->position == 1 && $TrainerVideoPhotoCount == 0){
                            $imagePath = preg_replace('/.mp4|.mov/i', '.jpg', $TrainerPhoto->image);
                            $imagePath = preg_replace('/profile_video/i', 'profile_image', $imagePath);
                            $photoImageName = $imagePath;
                        } else {
                             $TrainerPhotoLeftCount = TrainerPhoto::where('trainer_id', $user->id)->whereIn('position', [2, 3, 4])->count();
                             if($TrainerPhotoLeftCount == 3){
                                $TrainerPhoto = TrainerPhoto::where('trainer_id', $user->id)->where('position', 2)->first();
                                            $photoImageName = $TrainerPhoto->image;
                             } else {
                                    $TrainerImagePhoto = TrainerPhoto::where('is_video', 0)->where('trainer_id', $user->id)->first();
                                    $TrainerPhotoLeftCount = TrainerPhoto::where('trainer_id', $user->id)->whereIn('position', [2, 3])->count();
                                    $TrainerPhotoLeftCounts = TrainerPhoto::where('trainer_id', $user->id)->whereIn('position', [2, 4])->count();
                                    $TrainerPhotoLeftCountss = TrainerPhoto::where('trainer_id', $user->id)->whereIn('position', [3, 4])->count();
                                    if($TrainerPhotoLeftCount == 2){
                                        $TrainerPhoto = TrainerPhoto::where('trainer_id', $user->id)->where('position', 3)->first();
                                        $photoImageName = $TrainerPhoto->image;
                                    } else if($TrainerPhotoLeftCounts == 2){
                                        $TrainerPhoto = TrainerPhoto::where('trainer_id', $user->id)->where('position', 2)->first();
                                        $photoImageName = $TrainerPhoto->image;
                                    } else if($TrainerPhotoLeftCountss == 2){
                                        $TrainerPhoto = TrainerPhoto::where('trainer_id', $user->id)->where('position', 3)->first();
                                        $photoImageName = $TrainerPhoto->image;
                                    } else{
                                        $TrainerImagePhoto = TrainerPhoto::where('is_video', 0)->where('trainer_id', $user->id)->first();
                                        $photoImageName = $TrainerImagePhoto->image;
                                    }
                                
                             }
                            
                        }
                        
                    }
                }
                if (isset($requestData['profile_image']) && !empty($requestData['profile_image'])) {
                    foreach ($request->profile_image as $key => $photo) {
//                        $Trainerimage = TrainerPhoto::where('trainer_id', $user->id)->update(['is_featured' => 0]);
                        $TrainerPhoto = TrainerPhoto::where('id', $key)->where('trainer_id', $user->id)->first();
                        if (!empty($TrainerPhoto)) {
                            $photoImageName = $TrainerPhoto->image;
                        }
                    }
                }
                if (isset($requestData['trainer_image']) && !empty($requestData['trainer_image'])) {
                    //TrainerPhoto::where('trainer_id', $user->id)->delete();
//                    foreach ($request->trainer_image as $key => $photo) {
//                        $photoImage = $photo;
//                        $filenamewithextension = $photoImage->getClientOriginalName();
//                        if($photoImage->getClientOriginalExtension() == 'jfif'){
//                            $filenamewithextension = time() . '.jpg';
//                        } 
//                        //get filename without extension
//                        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
//                         
//                        $photoImageNametrainer = $filename . time() . '.' . $photoImage->getClientOriginalExtension();
//                        if($photoImage->getClientOriginalExtension() == 'jfif'){
//                            $photoImageNametrainer = $filename . time() . '.jpg';
//                        
//                        }
//                        $destinationPath = public_path('/front/profile');
////                        $img = Image::make($photoImage->getRealPath());
////                        $img->resize(325, 210 );
//                        $img = Image::make($photoImage->getRealPath());
//                        $img->resize(325, 210)->stream();
//                        
//                        $TrainerPhoto = TrainerPhoto::where('id',$key)->where('trainer_id',$user->id)->first();
//                        if(empty($TrainerPhoto)){
//                        $TrainerPhoto = new TrainerPhoto();
//                        }
//                         
//                        $TrainerPhoto->trainer_id = $user->id;
//                        $TrainerPhoto->is_featured = (isset($request->featured_image[$key]))?1:0;
//                        $TrainerPhoto->image = $photoImageNametrainer;
//                        $TrainerPhoto->save();
//                        $img->save($destinationPath . '/' . $photoImageNametrainer);
////                        $photoImage->move($destinationPath, $photoImageNametrainer);
//                    }
                }
//                $string = $requestData['first_name'].' '.$requestData['last_name'];
                if ($requestData["role_type"] == 'trainer') {
                    if (isset($requestData['business_name']) && $requestData['business_name'] != '') {
                        $string = $requestData['business_name'];
                    }
                    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
                    $num_str = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
                    $user->spot_description = $slug;
                    $user->address1_virtual = isset($requestData['address_virtual']) ? $requestData['address_virtual'] : '';
                 }else{
                     $user->spot_description = isset($requestData['tag_line']) ? $requestData['tag_line'] : '';
                 } 
                if ($requestData["role_type"] == 'customer') {                    
                      
                      $user->first_name = isset($requestData['first_name']) ? $requestData['first_name'] : '';
                      $user->last_name = isset($requestData['last_name']) ? $requestData['last_name'] : ''; 
                  }
                $user->business_name = isset($requestData['business_name']) ? $requestData['business_name'] : '';   
                $user->trainer_email_contact = isset($requestData['trainer_email_contact']) ? $requestData['trainer_email_contact'] : '';
                $user->phone_number = isset($requestData['phone_number']) ? $requestData['phone_number'] : '';
                $user->password = isset($requestData['password']) ? Hash::make($requestData['password']) : $user->password;
                // $user->address_1 = isset($requestData['address_1']) ? $requestData['address_1'] : '';
                $user->address_1 = isset($requestData['address_1']) ? $requestData['address_1'] : '';
				$user->address_2 = isset($requestData['address_2']) ? $requestData['address_2'] : '';
                if(isset($requestData['address_virtual']) && $requestData['address_virtual'] == 1){
                    $user->address1_virtual = 1;
                    $user->address_1 = '';
                    $user->address_2 = '';
                } else {
                    $user->address1_virtual = 0;
                }
                
                if ($requestData["role_type"] == 'customer') {  
                    $user->state = isset($requestData['state']) ? $requestData['state'] : '';
                } else {
                    $state_code = States::where('name', $requestData['state'])->first();
                    $user->state_code = $state_code->postal_code;
                    $user->state = isset($requestData['state']) ? $requestData['state'] : '';
                }
                $user->country = isset($requestData['country']) ? $requestData['country'] : '';
                if ($requestData["role_type"] == 'customer') {                    
                      
                    $user->city = isset($requestData['city']) ? $requestData['city'] : ''; 
                } else {
                    $user->city = isset($requestData['city']) ? $requestData['city'] : ''; 
                }
                $user->zip_code = isset($requestData['zip_code']) ? $requestData['zip_code'] : '';
                $user->facebook = isset($requestData['fb']) ? $requestData['fb'] : '';
                $user->instagram = isset($requestData['insta']) ? $requestData['insta'] : '';
                $user->linkedin = isset($requestData['linkedin']) ? $requestData['linkedin'] : '';
                $user->twitter = isset($requestData['twitter']) ? $requestData['twitter'] : '';
                $user->website = isset($requestData['website']) ? $requestData['website'] : '';
                $user->bio = isset($requestData['bio']) ? $requestData['bio'] : '';
                $user->headline = isset($requestData['headline']) ? $requestData['headline'] : '';
                if ($requestData["role_type"] == 'trainer') {                    
                      $user->photo = !empty($photoImageName) ? $photoImageName : '';  
                      $user->map_latitude = isset($requestData['map_latitude']) ? $requestData['map_latitude'] : '';
                $user->map_longitude = isset($requestData['map_longitude']) ? $requestData['map_longitude'] : '';
                  }

                if(isset($requestData['day1_check'])){
                    $user->day1_check = 1;
                } else {
                    $user->day1_check = 0;
                    if (!empty($requestData['day1'])) 
                    {
                        for($i=0; $i < count($requestData['day1']); $i++){

                            if($requestData['day1'][$i] !=''){ 
                                if ( $i % 2 == 0) { 
                                 
                                      $day1Shed[] = date("g:i A", strtotime($requestData['day1'][$i]));
                                    }
                                    else {
                                    $day1Shed[] = ' - '.date("g:i A", strtotime($requestData['day1'][$i]));
                                    if($i == count($requestData['day1'])-1){
                                     $day1Shed[] = ".";
                                     }
                                     else {
                                        $day1Shed[] = ",";
                                     } 
                                }
                            } else {
                                $day1Shed = '';
                            }
                        }
                        
                        if($day1Shed !=''){
                            $day1Sheduling = implode("", array_filter($day1Shed));
                        } else {
                            $day1Sheduling = '';
                        }
                        $day1String = implode(",", array_filter($requestData['day1']));
                    }
                }
               
                if(isset($requestData['day2_check'])){
                    $user->day2_check = 1;
                } else {
                    $user->day2_check = 0;
                    if (isset($requestData['day2'])) 
                    {
                        
                        for($i=0; $i < count($requestData['day2']); $i++){

                            if($requestData['day2'][$i] !=''){ 
                                if ( $i % 2 == 0) { 
                                 
                                      $day2Shed[] = date("g:i A", strtotime($requestData['day2'][$i]));
                                    }
                                    else {
                                    $day2Shed[] = ' - '.date("g:i A", strtotime($requestData['day2'][$i]));
                                    if($i == count($requestData['day2'])-1){
                                     $day2Shed[] = ".";
                                     }
                                     else {
                                        $day2Shed[] = ",";
                                     } 
                                }
                            } else {
                                $day2Shed = '';
                            }
                        }
                        
                        if($day2Shed !=''){
                            $day2Sheduling = implode("", array_filter($day2Shed));
                        } else {
                            $day2Sheduling = '';
                        }
                        $day2String = implode(",", array_filter($requestData['day2']));
                    }
                }
                if(isset($requestData['day3_check'])){
                    $user->day3_check = 1;
                } else {
                    $user->day3_check = 0;
                    if (isset($requestData['day3'])) 
                    {
                        for($i=0; $i < count($requestData['day3']); $i++){

                            if($requestData['day3'][$i] !=''){ 
                                if ( $i % 2 == 0) { 
                                 
                                      $day3Shed[] = date("g:i A", strtotime($requestData['day3'][$i]));
                                    }
                                    else {
                                    $day3Shed[] = ' - '.date("g:i A", strtotime($requestData['day3'][$i]));
                                    if($i == count($requestData['day3'])-1){
                                     $day3Shed[] = ".";
                                     }
                                     else {
                                        $day3Shed[] = ",";
                                     } 
                                }
                            } else {
                                $day3Shed = '';
                            }
                        }
                        
                        if($day3Shed !=''){
                            $day3Sheduling = implode("", array_filter($day3Shed));
                        } else {
                            $day3Sheduling = '';
                        }
                        $day3String = implode(",", array_filter($requestData['day3']));
                    }
                }
                if(isset($requestData['day4_check'])){
                    $user->day4_check = 1;
                } else {
                    $user->day4_check = 0;
                    if (isset($requestData['day4'])) 
                    {
                        for($i=0; $i < count($requestData['day4']); $i++){

                            if($requestData['day4'][$i] !=''){ 
                                if ( $i % 2 == 0) { 
                                 
                                      $day4Shed[] = date("g:i A", strtotime($requestData['day4'][$i]));
                                    }
                                    else {
                                    $day4Shed[] = ' - '.date("g:i A", strtotime($requestData['day4'][$i]));
                                    if($i == count($requestData['day4'])-1){
                                     $day4Shed[] = ".";
                                     }
                                     else {
                                        $day4Shed[] = ",";
                                     } 
                                }
                            } else {
                                $day4Shed = '';
                            }
                        }
                        
                        if($day4Shed !=''){
                            $day4Sheduling = implode("", array_filter($day4Shed));
                        } else {
                            $day4Sheduling = '';
                        }
                        $day4String = implode(",", array_filter($requestData['day4']));
                    }
                }
                if(isset($requestData['day5_check'])){
                    $user->day5_check = 1;
                } else {
                    $user->day5_check = 0;
                    if (isset($requestData['day5'])) 
                    {
                        for($i=0; $i < count($requestData['day5']); $i++){

                            if($requestData['day5'][$i] !=''){ 
                                if ( $i % 2 == 0) { 
                                 
                                      $day5Shed[] = date("g:i A", strtotime($requestData['day5'][$i]));
                                    }
                                    else {
                                    $day5Shed[] = ' - '.date("g:i A", strtotime($requestData['day5'][$i]));
                                    if($i == count($requestData['day5'])-1){
                                     $day5Shed[] = ".";
                                     }
                                     else {
                                        $day5Shed[] = ",";
                                     } 
                                }
                            } else {
                                $day5Shed = '';
                            }
                        }
                        
                        if($day5Shed !=''){
                            $day5Sheduling = implode("", array_filter($day5Shed));
                        } else {
                            $day5Sheduling = '';
                        }
                        $day5String = implode(",", array_filter($requestData['day5']));
                    }
                }
                if(isset($requestData['day6_check'])){
                    $user->day6_check = 1;
                } else {
                    $user->day6_check = 0;
                    if (isset($requestData['day6'])) 
                    {
                        for($i=0; $i < count($requestData['day6']); $i++){

                            if($requestData['day6'][$i] !=''){ 
                                if ( $i % 2 == 0) { 
                                 
                                      $day6Shed[] = date("g:i A", strtotime($requestData['day6'][$i]));
                                    }
                                    else {
                                    $day6Shed[] = ' - '.date("g:i A", strtotime($requestData['day6'][$i]));
                                    if($i == count($requestData['day6'])-1){
                                     $day6Shed[] = ".";
                                     }
                                     else {
                                        $day6Shed[] = ",";
                                     } 
                                }
                            } else {
                                $day6Shed = '';
                            }
                        }
                        
                        if($day6Shed !=''){
                            $day6Sheduling = implode("", array_filter($day6Shed));
                        } else {
                            $day6Sheduling = '';
                        }
                        $day6String = implode(",", array_filter($requestData['day6']));
                    }
                }
                if(isset($requestData['day7_check'])){
                    $user->day7_check = 1;
                } else {
                    $user->day7_check = 0;
                    if (isset($requestData['day7'])) 
                    {
                        for($i=0; $i < count($requestData['day7']); $i++){

                            if($requestData['day7'][$i] !=''){ 
                                if ( $i % 2 == 0) { 
                                 
                                      $day7Shed[] = date("g:i A", strtotime($requestData['day7'][$i]));
                                    }
                                    else {
                                    $day7Shed[] = ' - '.date("g:i A", strtotime($requestData['day7'][$i]));
                                    if($i == count($requestData['day7'])-1){
                                     $day7Shed[] = ".";
                                     }
                                     else {
                                        $day7Shed[] = ",";
                                     } 
                                }
                            } else {
                                $day7Shed = '';
                            }
                        }
                        
                        if($day7Shed !=''){
                            $day7Sheduling = implode("", array_filter($day7Shed));
                        } else {
                            $day7Sheduling = '';
                        }
                        $day7String = implode(",", array_filter($requestData['day7']));
                    
                    }
                }
                $user->day1 = isset($day1String) ? $day1String : '';
                $user->day2 = isset($day2String) ? $day2String : '';
                $user->day3 = isset($day3String) ? $day3String : '';
                $user->day4 = isset($day4String) ? $day4String : '';
                $user->day5 = isset($day5String) ? $day5String : '';
                $user->day6 = isset($day6String) ? $day6String : '';
                $user->day7 = isset($day7String) ? $day7String : '';
                $user->save();
                $day1Sheduling = isset($day1Sheduling) ? $day1Sheduling : '';
                $day2Sheduling = isset($day2Sheduling) ? $day2Sheduling : '';
                $day3Sheduling = isset($day3Sheduling) ? $day3Sheduling : '';
                $day4Sheduling = isset($day4Sheduling) ? $day4Sheduling : '';
                $day5Sheduling = isset($day5Sheduling) ? $day5Sheduling : '';
                $day6Sheduling = isset($day6Sheduling) ? $day6Sheduling : '';
                $day7Sheduling = isset($day7Sheduling) ? $day7Sheduling : '';
                
                 $providerCount = DB::table('provider_scheduling_temp')->where(["trainer_id" => $user->id])->count();
                 if($providerCount == 0){
                    $providerSchedulingInsert = DB::table('provider_scheduling_temp')->insert([
                        'trainer_id' => $user->id,
                        'day1' => $day1Sheduling,
                        'day2' => $day2Sheduling,
                        'day3' => $day3Sheduling,
                        'day4' => $day4Sheduling,
                        'day5' => $day5Sheduling,
                        'day6' => $day6Sheduling,
                        'day7' => $day7Sheduling,
                    ]);
                 } else {
                    $providerSchedulingUpdate = DB::update('update provider_scheduling_temp set day1="'.$day1Sheduling.'",day2="'.$day2Sheduling.'",day3="'.$day3Sheduling.'",day4="'.$day4Sheduling.'",day5="'.$day5Sheduling.'",day6="'.$day6Sheduling.'",day7="'.$day7Sheduling.'" where trainer_id="'.$user->id.'"');
                 }
                 if ($requestData["role_type"] == 'trainer') {
                    $modifyBusssinessName = str_replace(' ', '-', $requestData['business_name']);
                    $modifyOldBusssinessName = str_replace(' ', '-', $userOld->business_name);                    

                     $routingDbProIds = DB::table('provider_routing')->where('provider_id', $userOld->id)->get();

                     if(count($routingDbProIds) > 0){
                         DB::table('provider_routing')->insert(['provider_id' => $userOld->id, 'old_name' => $modifyOldBusssinessName, 'new_name' => $modifyBusssinessName ]);
                    
                     foreach ($routingDbProIds as $routingDbProId){ 
                        DB::table('provider_routing')->where('id', $routingDbProId->id)->update(['new_name' => $modifyBusssinessName ]);                         
                        }       
                     }else{
                         DB::table('provider_routing')->insert(['provider_id' => $userOld->id, 'old_name' => $modifyOldBusssinessName, 'new_name' => $modifyBusssinessName ]);
                     }
                    $providerNameUpdate = DB::update('update resource set name="'.$requestData['business_name'].'" where trainer_id="'.$user->id.'"');
                }
                Session::flash('message', 'Profile updated successfully.');
                if ($requestData["role_type"] == 'customer') {
                    //return redirect()->route('customer.profile');
                    return redirect()->route('customer.newprofile', $user->first_name.'-'.$user->last_name.'-'.$user->id);
                    return redirect()->to('/');
                }
                if ($requestData["role_type"] == 'trainer') {
                    return redirect()->route('front.profile');
                    //return redirect()->to('/');
                }
            }
        }
    }

    public function customer_profile(Request $request) {
        $user = Auth::guard('front_auth')->user();
        $countries = Countries::all();
        //$states = ['Jacksonville','Florida','Orlando'];
        $states = States::all();

        return view('front.edit-profile', ['user' => $user, 'countries' => $countries, 'states' => $states]);
    }

    public function order_history() {
        $user = Auth::guard('front_auth')->user();

        // $trainer = Orders::find($user->id)->Users()->get();

        if ($user->user_role == "customer") {

            $orders = Orders::where(['user_id' => $user->id])->latest('created_at')->with(['trainer', 'Ratting', 'service'])->get();
            // dd($orders->toArray());
            return view('front.customer-order-history', ['orders' => $orders]);
        } else if ($user->user_role == "trainer") {
            $location_id = Session::get('location_id');
            $orders = Orders::where(['trainer_id' => $user->id, "type" => "order"])->latest('created_at')->with(['users', 'Ratting', 'service'])
                    ->whereHas('service', function ($query) use ($location_id) {
                        $query->where('location_id', $location_id);
                    })
                    ->get();
            //dd($orders);
            return view('front.trainer.order-history', ['orders' => $orders]);
        }
    }

    public function availability() {

        //return view('front.trainer.comming_soon');

        $timeSlots = Config::get('constants.timeSlots');
        $trainerId = Auth::guard('front_auth')->user()->id;
        //$serviceEventsData = FrontUsers::where('id', $trainerId)->with(['allOrders.service'])->first();
        $serviceEventsData = DB::select("select o.*,ts.name as service_name,s.name as category_name from orders as o join trainer_services as ts on o.service_id=ts.id join services as s on ts.service_id=s.id where o.trainer_id='".$trainerId."' and o.plan_type IN ('In person - Single Appointment', 'In person - Group Appointment', 'Virtual - Single Appointment', 'Virtual - Group Appointment') and o.status='1'");
        $trainerServices = TrainerServices::where('trainer_id', $trainerId)->with(["service"])->get();
        $services = Services::where('status','active')->orderby('name','asc')->get();
        $returnarray = array();
        $array = array();
        $service_category = '';
        $service_name = '';
        $status = '';
        $package_type = '';
        foreach ($serviceEventsData as $serviceEvent) {
            
            $user = FrontUsers::find($serviceEvent->user_id);
            $startDate = strtotime($serviceEvent->created_at);
            $startDate = date('Y-m-d', $startDate);

            $time = explode("-", $serviceEvent->service_time);
            $starttime = strtotime($time[0]);
            $starttime = date('H:i:s', $starttime);
            if(isset($serviceEvent->order_status)){
                $status1 = $serviceEvent->order_status;
            } else {
                $status1 = 'Confirmed';
            }
            if($serviceEvent->amount != '0.00'){
                $serviceAmount = number_format((float)($serviceEvent->amount), 2);
            } else {
                $serviceAmount = 0;
            }
            $array = array(
                'title' => $serviceEvent->service_name,
                //'start' => $startDate . 'T' . $starttime,
                'start' => $serviceEvent->appointment_date,
               // 'end' => $endDate,
                //'time' => $serviceEvent->service_time
                'description' => '<b>Created Date and Time : </b>' . date('m-d-Y H:i:s', strtotime($serviceEvent->created_at)) . '<br><b>First Name : </b>' . $serviceEvent->first_name . '<br><b>Last Name : </b>' . $serviceEvent->last_name . '<br><b>Email ID : </b>' . $user->email . '<br><b>Phone Number : </b>' . $serviceEvent->phone . '<br><b>Service Name : </b>' . $serviceEvent->service_name . '<br><b>Service Category : </b>' . $serviceEvent->category_name . '<br><b>Service Type : </b>' . $serviceEvent->plan_type . '<br><b>Time : </b>' . $serviceEvent->service_time . '<br><b>Amount Paid: </b>$' . $serviceAmount . ' USD<br><b>Discount(percentage): </b>$' . number_format((float)($serviceEvent->ref_discount), 2) . ' USD<br><b>Status: </b>' . $status1 . '<br>',
            );
            $returnarray[] = $array;
        }
        //  dd($returnarray);
        return view('front.trainer.availability', ["eventData" => json_encode($returnarray), "trainerServices" => $trainerServices, "timeSlots" => $timeSlots, "services" => $services,"service_category" => $service_category,"service_name" => $service_name,"status" => $status,"package_type" => $package_type]);
    }

    public function searchavailability(Request $request) {

        //return view('front.trainer.comming_soon');
        $service_category = '';
        $service_name = '';
        $status = '';
        $package_type = '';
        $timeSlots = Config::get('constants.timeSlots');
        $trainerId = Auth::guard('front_auth')->user()->id;
        $services = Services::where('status','active')->orderby('name','asc')->get();
        //$serviceEventsData = FrontUsers::where('id', $trainerId)->with(['allOrders.service'])->first();
         $where = '';
        if($request->search == 1){
            $service_category = $request->service_category;
            $service_name = $request->service_name;
            $status = $request->status;
            $package_type = $request->package_type;
            if(!empty($service_category)){
                $where .= " and ts.service_id = '".$service_category."'";
            }

            if(!empty($service_name)){
                $where .= " and ts.name = '".$service_name."'";
            }

            if(!empty($status)){
                if($status == 2){
                    $where .= " and o.order_status = 'cancelled'";
                } else {
                    $where .= " and o.status = '".$status."'";
                    $where .= " and o.order_status IS NULL";
                }
                
            } else {
                $where .= " and o.status = '1'";
            }

            if(!empty($package_type)){
                $where .= " and o.plan_type = '".$package_type."'";
            }

            $serviceEventsData = DB::select("select o.*,ts.name as service_name,s.name as category_name from orders as o join trainer_services as ts on o.service_id=ts.id join services as s on ts.service_id=s.id where o.trainer_id='".$trainerId."' and o.plan_type IN ('In person - Single Appointment', 'In person - Group Appointment', 'Virtual - Single Appointment', 'Virtual - Group Appointment') ".$where."");
        } else {
            $serviceEventsData = DB::select("select o.*,ts.name as service_name,s.name as category_name from orders as o join trainer_services as ts on o.service_id=ts.id join services as s on ts.service_id=s.id where o.trainer_id='".$trainerId."' and o.plan_type IN ('In person - Single Appointment', 'In person - Group Appointment', 'Virtual - Single Appointment', 'Virtual - Group Appointment') and o.status='1' ".$where."");
        }

        
        $trainerServices = TrainerServices::where('trainer_id', $trainerId)->with(["service"])->get();
        $services = Services::where('status','active')->orderby('name','asc')->get();
        $returnarray = array();
        $array = array();
        
        foreach ($serviceEventsData as $serviceEvent) {
            

            $user = FrontUsers::find($serviceEvent->user_id);
            $startDate = strtotime($serviceEvent->created_at);
            $startDate = date('Y-m-d', $startDate);

            $time = explode("-", $serviceEvent->service_time);
            $starttime = strtotime($time[0]);
            $starttime = date('H:i:s', $starttime);
            if(isset($serviceEvent->order_status)){
                $status1 = $serviceEvent->order_status;
            } else {
                $status1 = 'Confirmed';
            }
            if($serviceEvent->amount != '0.00'){
                $serviceAmount = number_format((float)($serviceEvent->amount), 2);
            } else {
                $serviceAmount = 0;
            }
            $array = array(
                'title' => $serviceEvent->service_name,
                //'start' => $startDate . 'T' . $starttime,
                'start' => $serviceEvent->appointment_date,
               // 'end' => $endDate,
                //'time' => $serviceEvent->service_time
                'description' => '<b>Created Date and Time : </b>' . date('m-d-Y H:i:s', strtotime($serviceEvent->created_at)) . '<br><b>First Name : </b>' . $serviceEvent->first_name . '<br><b>Last Name : </b>' . $serviceEvent->last_name . '<br><b>Email ID : </b>' . $user->email . '<br><b>Phone Number : </b>' . $serviceEvent->phone . '<br><b>Service Name : </b>' . $serviceEvent->service_name . '<br><b>Service Category : </b>' . $serviceEvent->category_name . '<br><b>Service Type : </b>' . $serviceEvent->plan_type . '<br><b>Time : </b>' . $serviceEvent->service_time . '<br><b>Amount Paid: </b>$' . $serviceAmount . ' USD<br><b>Discount(percentage): </b>$' . number_format((float)($serviceEvent->ref_discount), 2) . ' USD<br><b>Status: </b>' . $status1 . '<br>',
            );
            $returnarray[] = $array;
        }
        //  dd($returnarray);
        return view('front.trainer.availability', ["eventData" => json_encode($returnarray), "trainerServices" => $trainerServices, "timeSlots" => $timeSlots, "services" => $services,"service_category" => $service_category,"service_name" => $service_name,"status" => $status,"package_type" => $package_type]);
    }

    public function month_annual_schedules() {

        $trainerId = Auth::guard('front_auth')->user()->id;
        $services = Services::where('status','active')->orderby('name','asc')->get();
        $serviceEventsData = DB::select("select o.*,ts.name as service_name,s.name as category_name from orders as o join trainer_services as ts on o.service_id=ts.id join services as s on ts.service_id=s.id where o.trainer_id='".$trainerId."' and o.plan_type IN ('In person - Monthly Membership', 'Virtual - Monthly Membership', 'In person - Yearly Membership', 'Virtual - Yearly Membership', 'In person - Package Deal', 'Virtual - Package Deal') and o.status='1' order by o.id desc");
        $service_category = '';
        $service_name = '';
        $package_type = '';
        $paid_date = '';
        $status = '';
        return view('front.trainer.services.month_annual_schedules', ["serviceEventsData" => $serviceEventsData,"services" => $services,"service_category" => $service_category,"service_name" => $service_name,"status" => $status,"package_type" => $package_type,"paid_date" => $paid_date]);
    }

    public function search_month_annual_schedules(Request $request) {

        $trainerId = Auth::guard('front_auth')->user()->id;
        $services = Services::where('status','active')->orderby('name','asc')->get();
        $service_category = '';
        $service_name = '';
        $status = '';
        $package_type = '';
        $paid_date = '';
        $where = '';
        if($request->search == 1){
            $service_category = $request->service_category;
            $service_name = $request->service_name;
            $status = $request->status;
            $package_type = $request->package_type;
            $paid_date = $request->paid_date;
            if(!empty($service_category)){
                $where .= " and ts.service_id = '".$service_category."'";
            }

            if(!empty($service_name)){
                $where .= " and ts.name = '".$service_name."'";
            }

            if(!empty($status)){
                if($status == 2){
                    $where .= " and o.order_status = 'cancelled'";
                } else {
                    $where .= " and o.status = '".$status."'";
                    $where .= " and o.order_status IS NULL";
                }
                
            } else {
                $where .= " and o.status = '1'";
            }

            if(!empty($package_type)){
                $where .= " and o.plan_type = '".$package_type."'";
            }

            if(!empty($paid_date)){
                //$paid_dates = date('Y-d-m', strtotime($paid_date));
                $paid_date_str=explode('-',$paid_date);
                $paid_dates = $paid_date_str[2].'-'.$paid_date_str[0].'-'.$paid_date_str[1];
                $where .= " and o.start_date = '".$paid_dates."'";
            }
            
            $serviceEventsData = DB::select("select o.*,ts.name as service_name,s.name as category_name from orders as o join trainer_services as ts on o.service_id=ts.id join services as s on ts.service_id=s.id where o.trainer_id='".$trainerId."' and o.plan_type IN ('In person - Monthly Membership', 'Virtual - Monthly Membership', 'In person - Yearly Membership', 'Virtual - Yearly Membership', 'In person - Package Deal', 'Virtual - Package Deal') ".$where." order by o.id desc");
            
        } else {
            $serviceEventsData = DB::select("select o.*,ts.name as service_name,s.name as category_name from orders as o join trainer_services as ts on o.service_id=ts.id join services as s on ts.service_id=s.id where o.trainer_id='".$trainerId."' and o.plan_type IN ('In person - Monthly Membership', 'Virtual - Monthly Membership', 'In person - Yearly Membership', 'Virtual - Yearly Membership', 'In person - Package Deal', 'Virtual - Package Deal') and o.status='1' ".$where." order by o.id desc");

        }
        //echo $paid_date;exit();
        return view('front.trainer.services.month_annual_schedules', ["serviceEventsData" => $serviceEventsData,"services" => $services,"service_category" => $service_category,"service_name" => $service_name,"status" => $status,"package_type" => $package_type,"paid_date" => $paid_date]);
    }

    public function notifications() {

        $trainerId = Auth::guard('front_auth')->user()->id;
        $services = Services::where('status','active')->orderby('name','asc')->get();
        $serviceRequestData = DB::select("select o.*,ts.name as service_name,s.name as category_name from order_request as o join trainer_services as ts on o.service_id=ts.id join services as s on ts.service_id=s.id where o.trainer_id='".$trainerId."' order by o.id desc");
        $service_category = '';
        $service_name = '';
        $status = '';
        $order_request_count = DB::table('order_request')->where(["trainer_id" => $trainerId])->count();
        return view('front.trainer.services.notifications', ["serviceRequestData" => $serviceRequestData,"services" => $services,"service_category" => $service_category,"service_name" => $service_name,"status" => $status, "order_request_count" => $order_request_count]);
    }

    public function Searchnotifications(Request $request) {
        $trainerId = Auth::guard('front_auth')->user()->id;
        $services = Services::where('status','active')->orderby('name','asc')->get();
        $service_category = '';
        $service_name = '';
        $status = '';
        $where = '';
        if($request->search == 1){
            $service_category = $request->service_category;
            $service_name = $request->service_name;
            $status = $request->status;
            if(!empty($service_category)){
                $where .= " and ts.service_id = '".$service_category."'";
            }

            if(!empty($service_name)){
                $where .= " and ts.name = '".$service_name."'";
            }

            if(!empty($status)){
                $where .= " and o.status = '".$status."'";
            }

            $serviceRequestData = DB::select("select o.*,ts.name as service_name,s.name as category_name from order_request as o join trainer_services as ts on o.service_id=ts.id join services as s on ts.service_id=s.id where o.trainer_id='".$trainerId."' ".$where." order by o.id desc");
            
        } else {
            $serviceRequestData = DB::select("select o.*,ts.name as service_name,s.name as category_name from order_request as o join trainer_services as ts on o.service_id=ts.id join services as s on ts.service_id=s.id where o.trainer_id='".$trainerId."' ".$where." order by o.id desc");

        }
        $order_request_count = DB::table('order_request')->where(["trainer_id" => $trainerId])->count();
        return view('front.trainer.services.notifications', ["serviceRequestData" => $serviceRequestData,"services" => $services,"service_category" => $service_category,"service_name" => $service_name,"status" => $status, "order_request_count" => $order_request_count]);
        
        
        
    }

    public function dashboard() {
        $user = Auth::guard('front_auth')->user();
        $location_id = session()->get('location_id');
        $services = TrainerServices::where("trainer_id", $user->id)
                ->select('*', DB::raw('count(*) as count'))
                ->groupBy('service_id')
                ->with(['service', 'Orders'])
                ->where('location_id', $location_id)
                ->get();

        $availabilityData = Orders::where(["trainer_id" => $user->id])
                ->with(['service'])
                ->whereHas('service', function ($query) use ($location_id) {
                    $query->where('location_id', $location_id);
                })
                ->get();

        //dd($availabilityData);

        $ordersData = Orders::where(["trainer_id" => $user->id, "status" => "1"])
                ->with(['Ratting'])
                ->whereHas('service', function ($query) use ($location_id) {
                    $query->where('location_id', $location_id);
                })
                ->get();
        //dd($ordersData->sum('amount'));  
        $currentMonthData = Orders::where(["trainer_id" => $user->id, "type" => "order"])->whereHas('service', function ($query) use ($location_id) {
                    $query->where('location_id', $location_id);
                })->whereMonth('created_at', Carbon::today()->month)->get();
        $athletsConnected = Orders::where(["trainer_id" => $user->id, "type" => "order"])->whereHas('service', function ($query) use ($location_id) {
                            $query->where('location_id', $location_id);
                        })
                        ->groupBy('user_id')->get();

        // dd($athletsConnected);
        $earnings = $ordersData->sum('amount');

        /* $retingdata =   $ordersData->transform(function ($v) { 
          if (isset($v->Ratting->rating)) {
          return $v->Ratting->rating;
          }
          });

          $retingdata = $retingdata->reject(function ($item) {
          return is_null($item);
          }); */

        $retingdata = Ratings::where('trainer_id', $user->id)->get();
        $retingdata = $retingdata->transform(function ($v) {
            if (isset($v->rating)) {
                return $v->rating;
            }
        });

        $r = 0;
        foreach ($retingdata as $rdata) {
            $r += $rdata;
        }

        if ($r != 0) {
            $ratting = $r / $retingdata->count();
        } else {
            $ratting = 0;
        }






        $returnarray = array();
        $array = array();

        // foreach($availabilityData as $trainerService){
        foreach ($availabilityData as $serviceEvent) {
            $date = explode("-", $serviceEvent->service_date);

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
                'id' => $serviceEvent->id,
                'title' => $serviceEvent->service->name,
                'start' => $startDate . 'T' . $starttime,
                'end' => $endDate,
                    //'time' => $serviceEvent->service_time
                    //'description' => '<b>Service Name : </b>' . $serviceEventsData->service->name . '<br><b>Start Date : </b>' . $startDate . '<br><b>End Date : </b>' . $endDate . '<br><b>Time : </b>' . $serviceEvent->service_time . '<br>',
            );
            $returnarray[] = $array;
        }
        // }
        //dd($returnarray);
        //
        $resources = DB::table('resource')->where(["trainer_id" => $user->id])->count();


        $trainerId = Auth::guard('front_auth')->user()->id;
        //$serviceEventsData = FrontUsers::where('id', $trainerId)->with(['allOrders.service'])->first();
        $serviceEventsDatas = DB::select("select o.*,ts.name as service_name,s.name as category_name from orders as o join trainer_services as ts on o.service_id=ts.id join services as s on ts.service_id=s.id where o.trainer_id='".$trainerId."' and o.plan_type IN ('In person - Single Appointment', 'In person - Group Appointment', 'Virtual - Single Appointment', 'Virtual - Group Appointment') and o.status='1'");
        
        $returnarraycal = array();
        $array = array();
       
        foreach ($serviceEventsDatas as $serviceEvent) {
            
            $user = FrontUsers::find($serviceEvent->user_id);
            $startDate = strtotime($serviceEvent->created_at);
            $startDate = date('Y-m-d', $startDate);

            $time = explode("-", $serviceEvent->service_time);
            $starttime = strtotime($time[0]);
            $starttime = date('H:i:s', $starttime);
            if(isset($serviceEvent->order_status)){
                $status = $serviceEvent->order_status;
            } else {
                $status = 'Confirmed';
            }
            if($serviceEvent->amount != '0.00'){
                $serviceAmount = number_format((float)($serviceEvent->amount), 2);
            } else {
                $serviceAmount = 0;
            }
            $array = array(
                'title' => $serviceEvent->service_name,
                //'start' => $startDate . 'T' . $starttime,
                'start' => $serviceEvent->appointment_date,
               // 'end' => $endDate,
                //'time' => $serviceEvent->service_time
                'description' => '<b>Created Date and Time : </b>' . date('m-d-Y H:i:s', strtotime($serviceEvent->created_at)) . '<br><b>First Name : </b>' . $serviceEvent->first_name . '<br><b>Last Name : </b>' . $serviceEvent->last_name . '<br><b>Email ID : </b>' . $user->email . '<br><b>Phone Number : </b>' . $serviceEvent->phone . '<br><b>Service Name : </b>' . $serviceEvent->service_name . '<br><b>Service Category : </b>' . $serviceEvent->category_name . '<br><b>Service Type : </b>' . $serviceEvent->plan_type . '<br><b>Time : </b>' . $serviceEvent->service_time . '<br><b>Amount Paid: </b>$' . $serviceAmount . ' USD<br><b>Discount(percentage): </b>$' . number_format((float)($serviceEvent->ref_discount), 2) . ' USD<br><b>Status: </b>' . $status . '<br>',
            );
            $returnarraycal[] = $array;
        }

        $nextSteps = DB::table('nextsections')
                    ->join('next_steps', 'next_steps.section_id', '=', 'nextsections.id')
                    ->get();
        $nextSections = DB::table('nextsections')->get();

        return view('front.trainer.default', [
            "services" => $services,
            "reviewCount" => $retingdata->count(),
            "ratting" => round($ratting, 1),
            "ordersCount" => $ordersData->count(),
            "currentMonthBookings" => $currentMonthData->count(),
            "earnings" => $earnings,
            "resources" => $resources,
            "eventData" => json_encode($returnarray),
            "eventDatacal" => json_encode($returnarraycal),
            "totalAthletsConnected" => count($athletsConnected),
            "nextSteps" => $nextSteps,
            "nextSections" => $nextSections,
        ]);
    }

    public function bookingform(Request $request) {
        $requestData = $request->all();
        $trainerId = Auth::guard('front_auth')->user()->id;
        // dd($requestData);
        $errors = Validator::make($requestData, [
                    'service' => 'required',
                    'start_date' => 'required',
                    //'end_date' => 'required',
                    'timeSlot' => 'required',
        ]);
        if ($errors->fails()) {
            return response()->json(['errors' => $errors->errors()->all()]);
        } else {
            //$dates = date("m-d-Y", strtotime($requestData["start_date"])).'-'.date("m-d-Y", strtotime($requestData["end_date"]));
            $startDate = date('Y-m-d', strtotime($requestData["start_date"]));
            if (!empty($requestData["end_date"])) {
                $endDate = date('Y-m-d', strtotime($requestData["end_date"]));
            } else {
                $endDate = $startDate;
            }


            $isBookedAlready = Orders::where(["service_id" => $requestData["service"], "trainer_id" => $trainerId, ["start_date", "<=", $endDate], ["end_date", ">=", $startDate], "service_time" => $requestData["timeSlot"]])->first();
            //dd($isBookedAlready);
            if ($isBookedAlready) {
                return response()->json(['fail' => "Sorry! This time slot is already booked."]);
                //     Session::flash ( 'fail-message', "Sorry! This service is already booked." );
                //    return redirect()->back();
            }

            $order = new Orders();
            $order->service_id = $requestData["service"];
            $order->trainer_id = $trainerId;
            //$order->service_date = $dates;
            $order->start_date = $startDate;
            $order->end_date = $endDate;
            $order->service_time = $requestData["timeSlot"];
            $order->status = 1;
            $order->type = 'manual';
            if ($order->save()) {
                return response()->json(['success' => 'Record is successfully added']);
            }
        }
    }

    public function servicebookingform(Request $request) {
        $requestData = $request->all();
        $trainerId = Auth::guard('front_auth')->user()->id;
        
        //dd($requestData);exit();
        if(isset($requestData['days'])){
            if($requestData['days'] == 0){
                $days = 'day7';
            } else {
                $days = 'day'.$requestData['days'];
            }
                
                $timeSlot_cnt = array();
                //$timeSlot = array();
            for($i=3;$i<count($requestData['timeSlot']);$i++){
                    if($requestData['timeSlot'][$i]['value'] !=''){
                        $timeSlot_cnt[] = $requestData['timeSlot'][$i];
                    }
                    $timeSlot[] = $requestData['timeSlot'][$i];
                }
                if(count($timeSlot_cnt) == 0){
                    $timeSlot = $timeSlot;
                } else {
                    $timeSlot = $timeSlot_cnt;
                }

                $time_count = 0;
                for($kk=0; $kk < count($timeSlot_cnt); $kk++){
                    if($timeSlot[$kk]['value'] !=''){ 
                        $time_count = $time_count+1;
                    }
                }
//dd($timeSlot);exit();
                //$dayShed = array();
                for($i=0; $i < count($timeSlot); $i++){
                    //$days = $timeSlot[$i]['name'];
                    if($timeSlot[$i]['value'] !=''){ 
                        if ( $i % 2 == 0) { 
                         
                              $dayShed[] = date("g:i A", strtotime($timeSlot[$i]['value']));
                            }
                            else {
                            $dayShed[] = ' - '.date("g:i A", strtotime($timeSlot[$i]['value']));
                            if($i == $time_count-1){
                             $dayShed[] = ".";
                             }
                             else {
                                $dayShed[] = ",";
                             } 
                        }
                    } else {
                        $dayShed[] = '';
                    }
                }
                //dd($dayShed);exit();
                if($dayShed !=''){
                    $daySheduling = implode("", array_filter($dayShed));
                } else {
                    $daySheduling = '';
                }
                if(isset($requestData['serviceIDs'])){
                    

                     $providerSchedulingDate = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId])->where(["day" => $days])->where(["date" => $requestData['appointment_date']])->where(["service_id" => $requestData['serviceIDs']])->count();
                    if($providerSchedulingDate == 0){
                        $InsertproviderSchedulingDate = DB::table('provider_scheduling_service_date')->insert([
                            'trainer_id' => $trainerId,
                            'service_id' => $requestData['serviceIDs'],
                            'day' => $days,
                            'date' => $requestData['appointment_date'],
                            'time' => $daySheduling
                        ]);
                    } else {
                        DB::update('update provider_scheduling_service_date set time="'.$daySheduling.'" where trainer_id="'.$trainerId.'" and service_id="'.$requestData['serviceIDs'].'" and day="'.$days.'" and date="'.$requestData['appointment_date'].'"');
                    }

                    
                    
                } else {
                    
                    $providerSchedulingDate = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId])->where(["day" => $days])->where(["date" => $requestData['appointment_date']])->count();
                    if($providerSchedulingDate == 0){
                        $InsertproviderSchedulingDate = DB::table('provider_scheduling_date')->insert([
                            'trainer_id' => $trainerId,
                            'day' => $days,
                            'date' => $requestData['appointment_date'],
                            'time' => $daySheduling
                        ]);
                    } else {
                        DB::update('update provider_scheduling_date set time="'.$daySheduling.'" where trainer_id="'.$trainerId.'" and day="'.$days.'" and date="'.$requestData['appointment_date'].'"');
                    }
                }
        } else {
            $timeSlot_cnt = array();
            for($i=3;$i<count($requestData['timeSlot']);$i++){

                    if($requestData['timeSlot'][$i]['value'] !=''){
                        $timeSlot_cnt[] = $requestData['timeSlot'][$i];
                    }
                    $timeSlot[] = $requestData['timeSlot'][$i];
                }
                if(count($timeSlot_cnt) == 0){
                    $timeSlot = $timeSlot;
                } else {
                    $timeSlot = $timeSlot_cnt;
                }
                $time_count = 0;
                for($kk=0; $kk < count($timeSlot_cnt); $kk++){
                    if($timeSlot[$kk]['value'] !=''){ 
                        $time_count = $time_count+1;
                    }
                }
                
                for($i=0; $i < count($timeSlot); $i++){
                    $days = $timeSlot[$i]['name'];
                    if($timeSlot[$i]['value'] !=''){ 
                        if ( $i % 2 == 0) { 
                         
                              $dayShed[] = date("g:i A", strtotime($timeSlot[$i]['value']));
                            }
                            else {
                            $dayShed[] = ' - '.date("g:i A", strtotime($timeSlot[$i]['value']));
                            if($i == $time_count-1){
                             $dayShed[] = ".";
                             }
                             else {
                                $dayShed[] = ",";
                             } 
                        }
                    } else {
                        $dayShed[] = '';
                    }
                }
                
                if($dayShed !=''){
                    $daySheduling = implode("", array_filter($dayShed));
                } else {
                    $daySheduling = '';
                }
                //dd($requestData);exit();
                if(isset($requestData['serviceIDs'])){
                    

                     $providerSchedulingDate = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId])->where(["day" => $days])->where(["date" => $requestData['appointment_date']])->where(["service_id" => $requestData['serviceIDs']])->count();
                    if($providerSchedulingDate == 0){
                        $InsertproviderSchedulingDate = DB::table('provider_scheduling_service_date')->insert([
                            'trainer_id' => $trainerId,
                            'service_id' => $requestData['serviceIDs'],
                            'day' => $days,
                            'date' => $requestData['appointment_date'],
                            'time' => $daySheduling
                        ]);
                    } else {
                        DB::update('update provider_scheduling_service_date set time="'.$daySheduling.'" where trainer_id="'.$trainerId.'" and service_id="'.$requestData['serviceIDs'].'" and day="'.$days.'" and date="'.$requestData['appointment_date'].'"');
                    }
                    

                } else {
                   

                    $providerSchedulingDate = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId])->where(["day" => $days])->where(["date" => $requestData['appointment_date']])->count();
                    if($providerSchedulingDate == 0){
                        $InsertproviderSchedulingDate = DB::table('provider_scheduling_date')->insert([
                            'trainer_id' => $trainerId,
                            'day' => $days,
                            'date' => $requestData['appointment_date'],
                            'time' => $daySheduling
                        ]);
                    } else {
                        DB::update('update provider_scheduling_date set time="'.$daySheduling.'" where trainer_id="'.$trainerId.'" and day="'.$days.'" and date="'.$requestData['appointment_date'].'"');
                    }
                    
                    
                }
                
        }

        if(isset($requestData['serviceIDs'])){
            $providerScheduling = DB::table('provider_scheduling_service')->where(["trainer_id" => $trainerId, "service_id" => $requestData['serviceIDs']])->first();
            $providerSchedulingDateCnt = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId, "service_id" => $requestData['serviceIDs']])->count();
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
                        
                            $providerSchedulingDate = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId])->where(["day" => $days])->where(["service_id" => $requestData['serviceIDs']])->get();
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
                        //}
                        
                    //}

                    //echo '<pre>';print_r($returnarray);exit();
                    
                }
            } elseif($providerSchedulingDateCnt != 0){
                $returnarray = array();
                for($i=1;$i<8;$i++){
                    $days = 'day'.$i;

                     
                        
                    $providerSchedulingDate = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId])->where(["day" => $days])->where(["service_id" => $requestData['serviceIDs']])->get();
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
                $returnarray = array();
            }
        } else {

            $providerScheduling = DB::table('provider_scheduling')->where(["trainer_id" => $trainerId])->first();
            $providerSchedulingDateCnt = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId])->count();
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
                        //}
                        
                    //}

                    //echo '<pre>';print_r($returnarray);exit();
                    
                }
            } elseif($providerSchedulingDateCnt != 0){
                $returnarray = array();
                for($i=1;$i<8;$i++){
                    $days = 'day'.$i;

                     
                        
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
                $returnarray = array();
            }

        }

        if(isset($requestData['serviceIDs'])){
            $providerSchedulingDates = DB::table('provider_scheduling_service_date')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->where(["service_id" => $requestData['serviceIDs']])->get();
            $serviceDate = array();
            $serviceTime = array();
            foreach ($providerSchedulingDates as $value) {
                    if($value->time !=''){
                        $serviceDate[] = $value->date;
                        $serviceTime[] = $value->time;
                    } else {
                        $serviceDate[] = $value->date;
                        $serviceTime[] = 'Closed';
                    }
                }
                $serviceDate = implode('/', $serviceDate);
                $serviceTime = implode('/', $serviceTime);
        } else {
            $providerSchedulingDates = DB::table('provider_scheduling_date')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])->get();
            $serviceDate = array();
            $serviceTime = array();
            foreach ($providerSchedulingDates as $value) {
                    if($value->time !=''){
                        $serviceDate[] = $value->date;
                        $serviceTime[] = $value->time;
                    } else {
                        $serviceDate[] = $value->date;
                        $serviceTime[] = 'Closed';
                    }
                }
                $serviceDate = implode('/', $serviceDate);
                $serviceTime = implode('/', $serviceTime);

        }
        
            //$day7String = implode(",", array_filter($requestData['day7']));
        return response()->json(['success' => 'Record is successfully added', 'eventData' => json_encode($returnarray), 'serviceDate' => $serviceDate, 'serviceTime' => $serviceTime]);
        
        
    }

    public function change_password(Request $request) {
        $requestData = $request->all();
        $messages = [
            'old_password.required' => 'Please enter current password',
            'password.required' => 'Please enter password',
            'confirm_password.same' => 'The confirm password and new password do not match '
        ];
        $errors = Validator::make($requestData, [
                    'old_password' => 'required',
                    'password' => 'required|min:8',
                    'confirm_password' => 'required|same:password',
                        ], $messages);
        if (!(Hash::check($request->get('old_password'), Auth::guard('front_auth')->user()->password))) {
            // The passwords matches
            return redirect()->back()->with("warning", "Your current password does not matches with the password you provided. Please try again.");
        }

        if (strcmp($request->get('old_password'), $request->get('password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("warning", "New Password cannot be same as your current password. Please choose a different password.");
        }
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
            $user = FrontUsers::find(Auth::guard('front_auth')->user()->id);
            $user->password = bcrypt($request->get('password'));
            $user->save();
            Auth::guard('front_auth')->logout();
            Session::flash('message', 'Your Password has been changed successfully! Please Login with your new password.');
            //return redirect('/');
            return Redirect::route('front.login');
            //return redirect()->back()->with("success","Password changed successfully !");
        }
    }

    function private_messaging() {

        return view('front.customer.private_messaging');
    }

    function private_messaging_send() {
        $user = Auth::guard('front_auth')->user();

        $ordersData = Orders::where("user_id", $user->id)
                ->with(['trainer'])
                ->groupBy('trainer_id')
                ->get();
        /* $ordersData = \Modules\Users\Entities\FrontUsers::where(["user_role" => 'trainer', "status" => "active"])
          ->get(); */
        return view('front.customer.private_messaging_send', [
            "ordersData" => $ordersData,
        ]);
    }

    function private_messaging_view($id) {
        $user = Auth::guard('front_auth')->user();
        $trainer_data = FrontUsers::find($id);
        $data = \Modules\Messages\Entities\Messages::with(['FromUsers', 'ToUsers'])->whereRaw('(to_id = ' . $user->id . ' and from_id = ' . $id . ') or (from_id = ' . $user->id . ' and to_id = ' . $id . ')')->get();

        $unreadData = \Modules\Messages\Entities\Messages::with(['FromUsers', 'ToUsers'])->where(["to_id" => $user->id, "from_id" => $id, "status" => "unread"])->get();
        $unreadIds = $unreadData->pluck('id');
        \Modules\Messages\Entities\Messages::whereIn('id', $unreadIds)->update([
            'status' => "read"
        ]);

        if ($user->user_role == 'customer') {
            $message_update = DB::update('update messages set status="read" where from_id="'.$id.'" and to_id="'.$user->id.'"');
            return view('front.customer.private_messaging_view', [
                "messagedata" => $data,
                "trainer_data" => $trainer_data,
                'to_id' => $user->id,
                'from_id' => $id,
                'userdata' => $user
            ]);
        } else {
            $message_update = DB::update('update messages set status="read" where from_id="'.$id.'" and to_id="'.$user->id.'"');
            return view('front.trainer.private_messaging_view', [
                "messagedata" => $data,
                "trainer_data" => $trainer_data,
                'to_id' => $user->id,
                'from_id' => $id,
                'userdata' => $user
            ]);
        }
    }

    function private_messaging_get_all() {
        $user = Auth::guard('front_auth')->user();
//        $data = \Modules\Messages\Entities\Messages::with(['FromUsers','ToUsers'])->where('to_id',$user->id)->latest()->get();
        $data = \Modules\Messages\Entities\Messages::with(['FromUsers', 'ToUsers'])->whereRaw('(to_id = ' . $user->id . ') or (from_id = ' . $user->id . ')')->orderBy('created_at', 'desc')->get()->unique('conversation_id');
        //dd($data);
        return Datatables::of($data)->addIndexColumn()
                        ->addColumn('date', function($row) {
                            $date = \Carbon\Carbon::parse($row->created_at)->format('D d M Y');
                            return $date;
                        })
                        ->addColumn('user_id', function($row) {
                            $user = Auth::guard('front_auth')->user();
                            if ($row->FromUsers->id == $user->id) {
                                $name = '<div class="d-flex align-items-center">
                                    <div class="profile-content">
                                        <h5>' . $row->ToUsers->first_name . ' ' . $row->ToUsers->last_name . '</h5>';
                                if (!empty($row->ToUsers->Address_1)) {
                                    $name .= '<p>' . $row->ToUsers->Address_1 . ', ';
                                }
                                if (!empty($row->ToUsers->city)) {
                                    $name .= $row->ToUsers->city . '</p>';
                                }
                                $name .= '</div>
                                </div>';
                            } else {
                                $name = '<div class="d-flex align-items-center">
                                    <div class="profile-content">
                                        <h5>' . $row->FromUsers->first_name . ' ' . $row->FromUsers->last_name . '</h5>';
                                if (!empty($row->FromUsers->Address_1)) {
                                    $name .= '<p>' . $row->FromUsers->Address_1 . ', ';
                                }
                                if (!empty($row->FromUsers->city)) {
                                    $name .= $row->FromUsers->city . '</p>';
                                }
                                $name .= '</div>
                                </div>';
                            }

                            return $name;
                        })
                        ->addColumn('message', function($row) {
                            $user = Auth::guard('front_auth')->user();
                            $message = '';
                            if ($row->status == "unread" && $row->to_id == $user->id) {
                                $message .= '<lable style="font-weight:bold;color:blue;">' . $row->message . '</label>';
                            } else {
                                $message .= $row->message;
                            }
                            return $message;
                        })
                        ->addColumn('action', function($row) {
                            $user = Auth::guard('front_auth')->user();
//                                $btn = '<a href="' . route('judgments.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                            //$btn = ' <a href="javascript:void(0)" data-id="' . route('messages.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
//                                 $btn = '<a href="javascript:void(0)" data-id="' . route('messages.show', $row->id) . '" class="view_message btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-eye"></i></a>';
                            if ($row->FromUsers->id == $user->id) {
                                $btn = '<div class="d-flex align-items-center justify-content-start justify-content-xl-center">
                                   <a href="' . route('customer.private_messaging.view', $row->to_id) . '#latest" class="view-link"></a>
                                </div>';
                            } else {
                                $btn = '<div class="d-flex align-items-center justify-content-start justify-content-xl-center">
                                   <a href="' . route('customer.private_messaging.view', $row->from_id) . '#latest" class="view-link"></a>
                                </div>';
                            }


                            return $btn;
                        })
                        ->rawColumns(['action', 'user_id', 'message'])
                        ->make(true);
    }

    function private_messaging_save(Request $request) {
        $user = Auth::guard('front_auth')->user();

        $input = $request->all();
        $rules['to_id'] = 'required';
        $rules['message'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {

            $conversation = \Modules\Messages\Entities\Messages::with(['FromUsers', 'ToUsers'])->whereRaw('(to_id = ' . $user->id . ' and from_id = ' . $input["to_id"] . ') or (from_id = ' . $user->id . ' and to_id = ' . $input["to_id"] . ')')->first();


            $input['from_id'] = $user->id;
            $input['status'] = 'unread';
            if (isset($input['id'])) {
                $services = \Modules\Messages\Entities\Messages::find($request->id);
                $msg = "Message Sent successfully.";
            } else {
                $services = new \Modules\Messages\Entities\Messages();
                $msg = "Message Sent successfully.";
            }
            $services->fill($input)->save();
            if ($conversation != null) {
                //dd($conversation);
                //get conversation id 
                $conversationId = $conversation->conversation_id;

                \Modules\Messages\Entities\Messages::where('id', $services->id)->update([
                    'conversation_id' => $conversationId
                ]);
            } else {
                \Modules\Messages\Entities\Messages::where('id', $services->id)->update([
                    'conversation_id' => $services->id
                ]);
            }




            if (isset($input['type'])) {
                return redirect()->back()->with('success', $msg);
            } else {
                return Redirect::route('customer.private_messaging')->with('success', $msg);
            }
        }
    }

    function private_messaging_trainer() {

        return view('front.trainer.private_messaging');
    }

    function private_messaging_trainer_get_all() {
        $user = Auth::guard('front_auth')->user();
//        $data = \Modules\Messages\Entities\Messages::with(['FromUsers', 'ToUsers'])->where('to_id', $user->id)->orderBy('created_at', 'desc')->get()->unique('from_id');
        $data = \Modules\Messages\Entities\Messages::with(['FromUsers', 'ToUsers'])->whereRaw('(to_id = ' . $user->id . ') or (from_id = ' . $user->id . ')')->orderBy('created_at', 'desc')->get()->unique('conversation_id');
//dd($data);
        return Datatables::of($data)->addIndexColumn()
                        ->addColumn('date', function($row) {
                            $date = \Carbon\Carbon::parse($row->created_at)->format('D d M Y');
                            return $date;
                        })
                        ->addColumn('user_id', function($row) {
                            $user = Auth::guard('front_auth')->user();
                            if ($row->FromUsers->id == $user->id) {
                                $name = '<div class="d-flex align-items-center">
                                    <div class="profile-content">
                                        <h5>' . $row->ToUsers->first_name . ' ' . $row->ToUsers->last_name . '</h5>';
                                if (!empty($row->ToUsers->Address_1)) {
                                    $name .= '<p>' . $row->ToUsers->Address_1 . ', ';
                                }
                                if (!empty($row->ToUsers->city)) {
                                    $name .= $row->ToUsers->city . '</p>';
                                }
                                $name .= '</div>
                                </div>';
                            } else {
                                $name = '<div class="d-flex align-items-center">
                                    <div class="profile-content">
                                        <h5>' . $row->FromUsers->first_name . ' ' . $row->FromUsers->last_name . '</h5>';
                                if (!empty($row->FromUsers->Address_1)) {
                                    $name .= '<p>' . $row->FromUsers->Address_1 . ', ';
                                }
                                if (!empty($row->FromUsers->city)) {
                                    $name .= $row->FromUsers->city . '</p>';
                                }
                                $name .= '</div>
                                </div>';
                            }
                            return $name;
                        })
                        ->addColumn('message', function($row) {
                            $user = Auth::guard('front_auth')->user();
                            $message = '';
                            if ($row->status == "unread" && $row->to_id == $user->id) {
                                $message .= '<lable style="font-weight:bold;color:blue;">' . $row->message . '</label>';
                            } else {
                                $message .= $row->message;
                            }
                            return $message;
                        })
                        ->addColumn('action', function($row) {
                            $user = Auth::guard('front_auth')->user();
                            if ($row->FromUsers->id == $user->id) {
                                $btn = '<div class="d-flex align-items-center justify-content-start justify-content-xl-center">
                                   <a href="' . route('trainer.private_messaging.view', $row->to_id) . '#latest" class="view-link"></a>
                                </div>';
                            } else {
                                $btn = '<div class="d-flex align-items-center justify-content-start justify-content-xl-center">
                                   <a href="' . route('trainer.private_messaging.view', $row->from_id) . '#latest" class="view-link"></a>
                                </div>';
                            }
                            return $btn;
                        })
                        ->rawColumns(['action', 'user_id', 'message'])
                        ->make(true);
    }

    function private_messaging_trainer_send() {
        $user = Auth::guard('front_auth')->user();
        $ordersData = Orders::where(["trainer_id" => $user->id, "type" => "order"])
                ->with(['Users'])
                ->groupBy('user_id')
                ->get();
//        $ordersData = \Modules\Users\Entities\FrontUsers::where(["user_role" => 'customer', "status" => "active"])
//                ->get();
        return view('front.trainer.private_messaging_send', [
            "ordersData" => $ordersData,
        ]);
    }

    function private_messaging_trainer_save(Request $request) {
        $user = Auth::guard('front_auth')->user();

        $input = $request->all();

        $rules['to_id'] = 'required';
        $rules['message'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {

            $conversation = \Modules\Messages\Entities\Messages::with(['FromUsers', 'ToUsers'])->whereRaw('(to_id = ' . $user->id . ' and from_id = ' . $input["to_id"] . ') or (from_id = ' . $user->id . ' and to_id = ' . $input["to_id"] . ')')->first();

            $input['from_id'] = $user->id;
            $input['status'] = 'unread';
            if (isset($input['id'])) {
                $services = \Modules\Messages\Entities\Messages::find($request->id);
                $msg = "Message Sent successfully.";
            } else {
                $services = new \Modules\Messages\Entities\Messages();
                $msg = "Message Sent successfully.";
            }
            $services->fill($input)->save();
            if ($conversation != null) {
                //get conversation id 
                $conversationId = $conversation->conversation_id;
                //dd($conversation[0]);
                \Modules\Messages\Entities\Messages::where('id', $services->id)->update([
                    'conversation_id' => $conversationId
                ]);
            } else {
                \Modules\Messages\Entities\Messages::where('id', $services->id)->update([
                    'conversation_id' => $services->id
                ]);
            }

            if (isset($input['type'])) {
                return redirect()->back()->with('success', $msg);
            } else {
                return Redirect::route('trainer.private_messaging')->with('success', $msg);
            }
        }
    }

    /* public function review_rating($id){
      $orderId = base64_decode($id);

      $order =  Orders::where("id", $orderId)->with(['trainer','Ratting'])->first();
      // dd($order);
      return view('front.rating',["order" => $order]);
      }

      public function submitReview(Request $request){
      //dd($request->all());
      $requestData = $request->all();
      $errors = Validator::make($requestData, [
      'ratingInput' => 'required',
      'title' => 'required',
      'review_sumary' => 'required',
      ]);
      if ($errors->fails()) {
      return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
      } else {
      if(isset($requestData["ratingId"]) && !empty($requestData["ratingId"])){
      $rating = Ratings::find($requestData["ratingId"]);
      $msg = "Review updated successfully!";
      }else{
      $rating = new Ratings();
      $msg = "Review submitted successfully!";
      }
      $rating->order_id = $requestData["order"];
      $rating->title = $requestData["title"];
      $rating->rating = $requestData["ratingInput"];
      $rating->description = $requestData["review_sumary"];
      if($rating->save()){
      Session::flash('message', $msg);
      return redirect()->intended(route('customer.order.history'));
      }
      }
      } */

    /* public function review_rating($id){
      $trainerId = base64_decode($id);

      $user = Auth::guard('front_auth')->user();

      $trainer = FrontUsers::where(["id" => $trainerId])->firstOrFail();
      $rating = Ratings::where(["user_id" => $user->id,"trainer_id" => $trainerId])->first();
      return view('front.rating',["trainer" => $trainer,'rating'=>$rating]);
      } */

    public function submitReview(Request $request) {
        $requestData = $request->all();
        $errors = Validator::make($requestData, [
                    'ratingInput' => 'required',
                    'title' => 'required',
                    'review_sumary' => 'required',
        ]);
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
            if (isset($requestData["ratingId"]) && !empty($requestData["ratingId"])) {
                $rating = Ratings::find($requestData["ratingId"]);
                $msg = "Review updated successfully!";
            } else {
                $rating = new Ratings();
                $msg = "Review submitted successfully!";
            }

            $user = Auth::guard('front_auth')->user();

            $rating->trainer_id = $requestData["trainer"];
            $rating->user_id = $user->id;
            $rating->title = $requestData["title"];
            $rating->rating = $requestData["ratingInput"];
            $rating->description = $requestData["review_sumary"];
            if ($rating->save()) {
                Session::flash('message', $msg);
                $trainerId = $requestData['trainer'];
                $user = \Auth::guard('front_auth')->user();
               // return redirect()->intended(route('customer.review', base64_encode($requestData['trainer'])));
               $trainer = FrontUsers::where(["id" => $trainerId])->firstOrFail();
                        $rating = Ratings::where(["user_id" => $user->id,"trainer_id" => $trainerId])->first();
                        return view('front.rating',["trainer" => $trainer,'rating'=>$rating]);
            }
        }
    }

    public function ratings_list() {
        $user = Auth::guard('front_auth')->user();

        if ($user->user_role == "customer") {

            /* $orders = Orders::where(['user_id'=>$user->id, "type" => "order"])->latest('created_at')->with(['trainer','Ratting','service'])->get();
              return view('front.customer-order-history', ['orders' => $orders]); */

            $ratings = Ratings::where(['user_id' => $user->id])->latest('created_at')->with(['trainer'])->get();
            return view('front.customer-ratings-list', ['ratings' => $ratings]);
        } else if ($user->user_role == "trainer") {
            $ratings = Ratings::where(['trainer_id' => $user->id])->latest('created_at')->with(['user'])->get();
            return view('front.trainer.ratings-list', ['ratings' => $ratings]);
        }
    }

    public function destroyRatings($id) {
        $rating = Ratings::findOrFail(base64_decode($id));

        if ($rating->delete()) {
            $msg = "Ratings & Reviews deleted successfully!";
            Session::flash('message', "Ratings & Reviews deleted successfully!");
            //return redirect()->intended(route('customer.ratings.list'));
            return Redirect::route('customer.ratings.list')->with('message', $msg);
        }
    }

    public function savecommant(Request $request) {
        $id = $request->retting_id;
        $rating = Ratings::find($id);
        if ($rating) {
            $rating->comment = $request->review_comment;
            $rating->save();
            Session::flash('message', "Ratings & Reviews Comment Added successfully!");
            return redirect()->intended(route('trainer.ratings.list'));
        }
    }

    public function trainer_image_delete($id) {
        $TrainerPhoto = TrainerPhoto::findOrFail($id);
        if ($TrainerPhoto->delete()) {
            $destinationPath = public_path('/front/profile');
            if (file_exists($destinationPath . '/' . $TrainerPhoto->image)) {
                unlink($destinationPath . '/' . $TrainerPhoto->image);
            }
        }
    }

    public function changelocation($id) {
        Session::put('location_id', $id);
    }

    public function customer_profile_new($name) {
        $name = explode('-', $name);
        if (isset($name[0]) && isset($name[1]) && isset($name[2])) {
            $user = FrontUsers::where('first_name', $name[0])->where('id', $name[2])->where('last_name', $name[1])->first();
            if (empty($user)) {
                abort(404);
            }
        } else {
            abort(404);
        }
        $Trainerdata = RecommendedProviders::with('users.ratings')->where('user_id', $user->id)->get();
        $friendlist = \App\Friend::with('user', 'friend');
        $friendlist->where(function ($query) use ($user) {
            $query->orWhere('friend_id', $user->id)
                    ->orWhere('user_id', $user->id);
        });
        $friendlist = $friendlist->where('accept', 1)->get();
        $requestlist = \App\Friend::with('user', 'friend')->where('friend_id', $user->id)->where('accept', 0)->get();
        $Groupdata = GroupsUsers::with('group')->where('user_id', $user->id)->get();
        $notification = \App\Notification::with('quotebyfrom')->with('quotebyto')->where('to_user_id', Auth::guard('front_auth')->user()->id)->orderBy('created_at', 'desc')->get();
        $eventsReg = DB::table('event_registration')->where('attender_id', '=', $user->id )->get();
        date_default_timezone_set('America/New_York');
        $currentTimeStamp = Carbon::now()->toDateTimeString();

        $events = DB::table('trainer_events')
                ->Join('event_registration', 'event_registration.event_id', '=', 'trainer_events.id' )
                ->where('event_registration.attender_id', $user->id)
                ->where('trainer_events.event_start_datetime','>=', $currentTimeStamp)
                ->orderBy('trainer_events.event_start_datetime', 'ASC')
                ->get();
                return view('front.customer.profile', ['friendlist' => $friendlist, 'Groupdata' => $Groupdata, 'user' => $user, 'Trainerdata' => $Trainerdata, 'notificationdata' => $notification, 'requestlist' => $requestlist, 'events' => $events]);
    }

    public function findfriends(Request $request) {
        $user = Auth::guard('front_auth')->user();
        $page = 1;
        return view('front.customer.findfriend', ['page' => $page, 'user' => $user]);
    }

    public function findfriendsdata(Request $request) {
        $user = Auth::guard('front_auth')->user();
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        $customerlist = FrontUsers::where('user_role', 'customer')->where('status', 'active')->where('id', '!=', $user->id);
        if (isset($request->keyword) && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $customerlist->where(function ($query) use ($keyword) {
                $keyworddata = explode(' ', $keyword);
                foreach ($keyworddata as $value) {
                         $query->orWhere('first_name', 'LIKE', '%' . $value . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $value . '%') 
                        ->orWhere('city', 'LIKE', '%' . $value . '%');               
                }
                
            });
        }
        $customerlist = $customerlist->paginate(10);
        return view('front.customer.findfrienddata', ['user' => $user, 'customerlist' => $customerlist]);
    }

    public function addfriend(Request $request) {
        $user = Auth::guard('front_auth')->user();
        $frienddata = \App\Friend::whereRaw('user_id = ' . $user->id . ' and friend_id = ' . $request->id)->orwhereRaw('user_id = ' . $request->id . ' and friend_id = ' . $user->id)->first();
        if (empty($frienddata)) {
            $frienddata = new \App\Friend();
            $frienddata->user_id = $user->id;
            $frienddata->friend_id = $request->id;
            $frienddata->save();
            $userdata = FrontUsers::find($request->id);
            $type = 'Send';
            $title = 'Received Friend Request';
            $notimessage = ucfirst($user->first_name) . ' ' . $user->last_name . ' send you friend request';
            notificationCreate($user->id, $request->id, $type, $title, $notimessage);
            $data['first_name'] = $userdata->first_name;
            $data['last_name'] = $userdata->last_name;
            $data['notimessage'] = $notimessage;
            $subject = $title;
            $emails_name = 'Training Block';
            $admin_email = "auto-reply@trainingblockusa.com";
            $admin_name = "Training Block";
            $emails = $userdata->email;
           /* Mail::send('email.notification', $data, function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                $message->from($admin_email, $admin_name);
                $message->to($emails, $emails_name)->subject($subject);
            });*/
        }
        $response = ['status' => true, "data" => ''];
        return json_encode($response);
    }

    public function getfrienddata(Request $request) {
        $user = FrontUsers::find($request->user_id);
        $countries = Countries::all();
        $states = States::all();
        $requestlist = \App\Friend::with('user', 'friend')->where('friend_id', $user->id)->where('accept', 0)->get();
        $friendlist = \App\Friend::with('user', 'friend');
        $friendlist->where(function ($query) use ($user) {
            $query->orWhere('friend_id', $user->id)
                    ->orWhere('user_id', $user->id);
        });
        $friendlist = $friendlist->where('accept', 1)->get();

        return view('front.customer.frienddata', ['requestlist' => $requestlist, 'friendlist' => $friendlist, 'user' => $user, 'countries' => $countries, 'states' => $states]);
    }

    public function orderhistory(Request $request) {
        $user = Auth::guard('front_auth')->user();
        $orders = Orders::where(['user_id' => $user->id, "type" => "order"])->latest('created_at')->with(['trainer', 'Ratting', 'service'])->get();

        return view('front.customer.orderhistory', ['orders' => $orders]);
    }

    public function recommendeddata(Request $request) {
        $user = FrontUsers::find($request->user_id);
        $recommended = RecommendedProviders::with('users.ratings')->where('user_id', $user->id)->get();
        $recommended->transform(function ($v) {
            $r = 0;
            $t = 1;
            if (isset($v->users->ratings)) {
                $retingdata = $v->users->ratings->transform(function ($v1) use($r) {
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

        return view('front.customer.recommendeddata', ['user' => $user, 'recommended' => $recommended]);
    }

    public function acceptrejectrequest(Request $request) {
        $user = Auth::guard('front_auth')->user();
        $frienddata = \App\Friend::where('id', $request->id)->first();

        if ($user->id == $frienddata->friend_id) {
            $userdata = FrontUsers::find($frienddata->user_id);
        } else {
            $userdata = FrontUsers::find($frienddata->friend_id);
        }
        //print_r($request->id);echo '<pre>';print_r($frienddata);exit();
        if (!empty($frienddata)) {
            if ($request->type == 'accept') {
                $frienddata->accept = 1;
                $frienddata->save();

                $message = 'Accepted!';
                $type = 'Accepted';
                $title = 'Friend Request Accept';
                $notimessage = ucfirst($user->first_name) . ' ' . $user->last_name . ' Accept your friend request';
                notificationCreate($user->id, $userdata->id, $type, $title, $notimessage);
                $data['first_name'] = $userdata->first_name;
                $data['last_name'] = $userdata->last_name;
                $data['notimessage'] = $notimessage;
                $subject = $title;
                $emails_name = 'Training Block';
                $admin_email = "auto-reply@trainingblockusa.com";
                $admin_name = "Training Block";
                $emails = $userdata->email;
               /* Mail::send('email.notification', $data, function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                    $message->from($admin_email, $admin_name);
                    $message->to($emails, $emails_name)->subject($subject);
                });*/
            } else {
                if($request->type == 'remove'){
                    $message = 'Unfriend!';
                    $type = 'Unfriend';
                    $title = 'Unfriend';
                }else{
                    $message = 'Rejected!';
                    $type = 'Rejected';
                    $title = 'Friend Request Reject';
                }
                
                $notimessage = ucfirst($user->first_name) . ' ' . $user->last_name . ' Reject your friend request';
                if ($frienddata->accept == 0) {
                    notificationCreate($user->id, $frienddata->user_id, $type, $title, $notimessage);
                    $data['first_name'] = $userdata->first_name;
                    $data['last_name'] = $userdata->last_name;
                    $data['notimessage'] = $notimessage;
                    $subject = $title;
                    $emails_name = 'Training Block';
                    $admin_email = "auto-reply@trainingblockusa.com";
                    $admin_name = "Training Block";
                    $emails = $userdata->email;
                    /*Mail::send('email.notification', $data, function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                        $message->from($admin_email, $admin_name);
                        $message->to($emails, $emails_name)->subject($subject);
                    });*/
                }
                $frienddata->delete();
            }
        }
        $response = ['status' => true, "message" => $message];
        return json_encode($response);
    }

    public function acceptrejectrequestindvidual(Request $request) {
        $user = Auth::guard('front_auth')->user();
        $frienddata = \App\Friend::where('id', $request->id)->first();
        $message = 'Rejected!';
        $type = 'Rejected';
        $title = 'Friend Request Reject';

        $recommendeds = DB::table('friends')->where('id', $request->id)->delete();

        $friendlist = \App\Friend::with('user', 'friend');
        $friendlist->where(function ($query) use ($user) {
            $query->orWhere('friend_id', $user->id)
                    ->orWhere('user_id', $user->id);
        });
        $friendlist = $friendlist->where('accept', 1)->get();
               
        $response = ['status' => 'Friend Request Reject', "message" => $message, 'friendlistcount' => $friendlist->count()];
        return json_encode($response);
    }

    public function addordernote(Request $request) {
        $orders = Orders::find($request->order_id);
        if (!empty($orders)) {
            $orders->order_note = $request->order_comment;
            $orders->save();
        }
        $response = ['status' => true, "message" => 'Note Added.'];
        return json_encode($response);
    }


    public function creategroup(Request $request) {
        $user = Auth::guard('front_auth')->user();
        ini_set('max_input_vars', 3000);

        $recommendeds = new Groups();
        $recommendeds->name = $request->group_name;
        if (isset($request->group_image)) {
            $filename = 'group_image';
            $photoImageNametrainer = $filename . time() . '.jpg';
            $destinationPath = public_path('/front/profile');
            $img = Image::make(file_get_contents($request->group_image));
            // $img->resize(325, 210);
            $img->save($destinationPath . '/' . $photoImageNametrainer);
            $recommendeds->image = !empty($photoImageNametrainer) ? $photoImageNametrainer : $user->photo;
        }

        $recommendeds->user_id = $user->id;
        $recommendeds->save();
        $GroupsUser = new GroupsUsers();
        $input['group_id'] = $recommendeds->id;
        $input['user_id'] = $user->id;
        $data = $GroupsUser->fill($input)->save();
        $response = ['status' => true, "message" => 'Group Created.'];
        return json_encode($response);
    }

    public function addrecommended(Request $request) {
        $user = Auth::guard('front_auth')->user();
        $userdata = FrontUsers::find($request->id);
        
        $recommendedscount = RecommendedProviders::where('user_id', $user->id)->get()->count();
        $recommendeds = RecommendedProviders::where('provider_id', $request->id)->where('user_id', $user->id)->first();
        if ($request->type == 'add') {
            /*if ($recommendedscount >= 5) {
                $response = ['status' => false, "message" => 'You can only add five recommended.'];
                return json_encode($response);
            }*/
            if (empty($recommendeds)) {
                $recommendeds = new RecommendedProviders();
                $recommendeds->provider_id = $request->id;
                $recommendeds->user_id = $user->id;
                $recommendeds->role = $user->user_role;
                $recommendeds->save();
            }
            $title = 'Added as Favorite';
                $type = 'Favorite';
                $notimessage = ucfirst($user->first_name) . ' ' . $user->last_name . ' Added you as Favorite.';
                notificationCreate($user->id, $request->id, $type, $title, $notimessage);
                $data['first_name'] = $userdata->business_name;
                $data['last_name'] = '';
                $data['notimessage'] = $notimessage;
                $subject = $title;
                $emails_name = 'Training Block';
                $admin_email = "auto-reply@trainingblockusa.com";
                $admin_name = "Training Block";
                $emails = $userdata->email;
                 // Mail::send('email.notification', $data, function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                 //        $message->from($admin_email, $admin_name);
                 //        $message->to($emails, $emails_name)->subject($subject);
                 //    });
                 //    
            $recommended = RecommendedProviders::with('users','customer')->where('provider_id',$request->id)->get();
            $recommended_authlete = RecommendedProviders::with('users','customer')->where('provider_id',$request->id)->where('role','customer')->get();
            $recommended_provider = RecommendedProviders::with('users','customer')->where('provider_id',$request->id)->where('role','trainer')->get();
            $favorited_by_athelete = array();
            $authlete_popup_details = array();
            $i=1;
            foreach($recommended_authlete as $recomm){
                if($i < 4){
                    if (isset($recomm->customer->user_role) && $recomm->customer->user_role == 'customer')
                        { 
                            $url = route('customer.newprofile',$recomm->customer->first_name.'-'.$recomm->customer->last_name.'-'.$recomm->customer->id);
                        } else { 
                            $url = url('provider/'.$recomm->customer->spot_description) ;
                         } 
                     if(!empty($recomm->customer->business_name)){
                        $title = $recomm->customer->business_name;
                    } else {
                        $title = $recomm->customer->first_name .' '.$recomm->customer->last_name;
                     
                    }    

                    if(isset($recomm->customer->photo) && !empty($recomm->customer->photo) && file_exists(public_path('front/profile/'.$recomm->customer->photo))) { 
                        $src = asset('front/profile/'.$recomm->customer->photo);
                    } else { 
                        $src = asset('/front/images/details_default.png');
                    }

                    $favorited_by_athelete[] = '<span class="b-avatar pull-up rounded-circle avatar_1">
                            <span class="b-avatar-img">
                                <a href="'.$url.'" title="'.$title.'"> 
                                    <img src="'.$src.'">
                                </a>
                            </span>
                        </span>'; 
                }
                $i =$i+1;

                if($recomm->customer->city != '' || $recomm->customer->state != ''){
                    $city_state = '<div class="location-taxt">  
                        <p><i class="fas fa-map-marker-alt"></i> '.$recomm->customer->city.' '.$recomm->customer->state.'</p>
                    </div>'; 
                 } else {
                    $city_state = '<div class="location-taxt">  
                        <p><i class="fas fa-map-marker-alt"></i> </p>
                    </div>';
                 }                      

                $url = route('customer.newprofile',$recomm->customer->first_name.'-'.$recomm->customer->last_name.'-'.$recomm->customer->id);
                $title = $recomm->customer->first_name .' '.$recomm->customer->last_name;
                $authlete_popup_details[] = '<div class="col-xl-6 mb-2">  
                        <div class="user-info d-flex">
                            <img class="user-image " style="height: 55px; width: 55px;object-fit: cover;border-radius: 50%;" src="'.$src.'">
                            <div class="user-name-btn ml-2"  style="max-width:100%"> 
                                <a href="'.$url.'"> 
                                    <h4 class="user-name m-0">  
                                        '.$title.'
                                    </h4>  

                                    '.$city_state.'
                                </a> 
                            </div> 
                        </div> 

                    </div>'; 
                           
            }

            if($recommended_authlete->count() > 3){
                $authlete_popup_count = '<h6 class="align-self-center cursor-pointer ml-2 mb-0"> <a href="#" data-toggle="modal" data-target="#recommendedviewall"><span> '.$recommended_authlete->count().' View All </span></a></h6>';
            } else {
                $authlete_popup_count = '1';
            }
            $favorited_by_provider = array();
            $provider_popup_details = array();
            $j =1;
            foreach($recommended_provider as $recomm){
                if($j < 8){
                    if (isset($recomm->customer->user_role) && $recomm->customer->user_role == 'customer')
                        { 
                            $url = route('customer.newprofile',$recomm->customer->first_name.'-'.$recomm->customer->last_name.'-'.$recomm->customer->id);
                        } else { 
                            $url = url('provider/'.$recomm->customer->spot_description) ;
                         } 
                     if(!empty($recomm->customer->business_name)){
                        $title = $recomm->customer->business_name;
                    } else {
                        $title = $recomm->customer->first_name .' '.$recomm->customer->last_name;
                     
                    }    

                    if(isset($recomm->customer->photo) && !empty($recomm->customer->photo) && file_exists(public_path('front/profile/'.$recomm->customer->photo))) { 
                        $src = asset('front/profile/'.$recomm->customer->photo);
                    } else { 
                        $src = asset('/front/images/details_default.png');
                    }

                    $favorited_by_provider[] = '<div class="col-md-3 mb-2"> 
                                    <div class="user-info d-flex">
                                         <div class="user-name-btn"> 
                                           <a href="'.$url.'" title="'.$title.'"> 
                                                <img class="user-image " style="height: 55px; width: 55px;object-fit: cover;border-radius: 50%;" src="'.$src.'">
                                        
                                            </a> 
                                        </div> 
                                    </div> 
                                </div> '; 
                }
                $j = $j+1;

                $title = $recomm->customer->first_name .' '.$recomm->customer->last_name;
                if($recomm->customer->city != '' || $recomm->customer->state != ''){
                    $city_state = '<div class="location-taxt">  
                                        <p><i class="fas fa-map-marker-alt"></i> '.$recomm->customer->city.' '.$recomm->customer->state.'</p>
                                    </div>'; 
                } else {
                    $city_state = '<div class="location-taxt">  
                                        
                                    </div>'; 
                }

                    $provider_popup_details[] = '<div class="col-xl-6 mb-2">  
                        <div class="user-info d-flex">
                            <img class="user-image " style="height: 55px; width: 55px;object-fit: cover;border-radius: 50%;" src="'.$src.'">
                            <div class="user-name-btn ml-2"  style="max-width:100%"> 
                                <a href="'.$url.'"> 
                                    <h4 class="user-name m-0">  
                                        '.$title.'
                                    </h4>  

                                    '.$city_state.'
                                </a> 
                            </div> 
                        </div> 

                    </div>'; 
                    
            }

            if($recommended_provider->count() > 7){
                $provider_popup_count = '<a href="javascript:void(0)" data-toggle="modal" data-target="#recommendedviewallProvider" class="btn btn-outline-danger">View All</a> ';
            } else {
                $provider_popup_count = '1';
            }

            $response = ['status' => true, "message" => 'Added as Favorite.', "favorited_by_provider" => $favorited_by_provider, "favorited_by_athelete" => $favorited_by_athelete, "authlete_popup_count" => $authlete_popup_count, "provider_popup_count" => $provider_popup_count, "athelete_count" => $recommended_authlete->count(), "provider_count" => $recommended_provider->count(), "authlete_popup_details" => $authlete_popup_details, "provider_popup_details" => $provider_popup_details];
        } else {
            //$recommendeds = RecommendedProviders::where('provider_id', $request->id)->where('user_id', $user->id)->first();
            $recommendeds = DB::table('recommended_providers')->where('provider_id', $request->id)->where('user_id', $user->id)->delete();
            //$recommendeds->delete();

            $recommended = RecommendedProviders::with('users','customer')->where('provider_id',$request->id)->get();
            $recommended_authlete = RecommendedProviders::with('users','customer')->where('provider_id',$request->id)->where('role','customer')->get();
            $recommended_provider = RecommendedProviders::with('users','customer')->where('provider_id',$request->id)->where('role','trainer')->get();
            $favorited_by_athelete = array();
            $authlete_popup_details = array();
            $i = 1;
            foreach($recommended_authlete as $recomm){
                if($i < 4){
                    if (isset($recomm->customer->user_role) && $recomm->customer->user_role == 'customer')
                        { 
                            $url = route('customer.newprofile',$recomm->customer->first_name.'-'.$recomm->customer->last_name.'-'.$recomm->customer->id);
                        } else { 
                            $url = url('provider/'.$recomm->customer->spot_description) ;
                         } 
                     if(!empty($recomm->customer->business_name)){
                        $title = $recomm->customer->business_name;
                    } else {
                        $title = $recomm->customer->first_name .' '.$recomm->customer->last_name;
                     
                    }    

                    if(isset($recomm->customer->photo) && !empty($recomm->customer->photo) && file_exists(public_path('front/profile/'.$recomm->customer->photo))) { 
                        $src = asset('front/profile/'.$recomm->customer->photo);
                    } else { 
                        $src = asset('/front/images/details_default.png');
                    }

                    $favorited_by_athelete[] = '<span class="b-avatar pull-up rounded-circle avatar_1">
                            <span class="b-avatar-img">
                                <a href="'.$url.'" title="'.$title.'"> 
                                    <img src="'.$src.'">
                                </a>
                            </span>
                        </span>'; 
                }
                $i = $i+1;

                if($recomm->customer->city != '' || $recomm->customer->state != ''){
                    $city_state = '<div class="location-taxt">  
                        <p><i class="fas fa-map-marker-alt"></i> '.$recomm->customer->city.' '.$recomm->customer->state.'</p>
                    </div>'; 
                 } else {
                    $city_state = '<div class="location-taxt">  
                        <p><i class="fas fa-map-marker-alt"></i> </p>
                    </div>';
                 }                       

                $url = route('customer.newprofile',$recomm->customer->first_name.'-'.$recomm->customer->last_name.'-'.$recomm->customer->id);
                $title = $recomm->customer->first_name .' '.$recomm->customer->last_name;
                $authlete_popup_details[] = '<div class="col-xl-6 mb-2">  
                        <div class="user-info d-flex">
                            <img class="user-image " style="height: 55px; width: 55px;object-fit: cover;border-radius: 50%;" src="'.$src.'">
                            <div class="user-name-btn ml-2"  style="max-width:100%"> 
                                <a href="'.$url.'"> 
                                    <h4 class="user-name m-0">  
                                        '.$title.'
                                    </h4>  

                                    '.$city_state.'
                                </a> 
                            </div> 
                        </div> 

                    </div>'; 
            }

            if($recommended_authlete->count() > 3){
                $authlete_popup_count = '<h6 class="align-self-center cursor-pointer ml-2 mb-0"> <a href="#" data-toggle="modal" data-target="#recommendedviewall"><span> '.$recommended_authlete->count().' View All </span></a></h6>';
            } else {
                $authlete_popup_count = '1';
            }
            $favorited_by_provider = array();
            $provider_popup_details = array();
            $j=1;
            foreach($recommended_provider as $recomm){
                if($j < 8){
                    if (isset($recomm->customer->user_role) && $recomm->customer->user_role == 'customer')
                        { 
                            $url = route('customer.newprofile',$recomm->customer->first_name.'-'.$recomm->customer->last_name.'-'.$recomm->customer->id);
                        } else { 
                            $url = url('provider/'.$recomm->customer->spot_description) ;
                         } 
                     if(!empty($recomm->customer->business_name)){
                        $title = $recomm->customer->business_name;
                    } else {
                        $title = $recomm->customer->first_name .' '.$recomm->customer->last_name;
                     
                    }    

                    if(isset($recomm->customer->photo) && !empty($recomm->customer->photo) && file_exists(public_path('front/profile/'.$recomm->customer->photo))) { 
                        $src = asset('front/profile/'.$recomm->customer->photo);
                    } else { 
                        $src = asset('/front/images/details_default.png');
                    }

                    $favorited_by_provider[] = '<div class="col-md-3 mb-2"> 
                                    <div class="user-info d-flex">
                                         <div class="user-name-btn"> 
                                           <a href="'.$url.'" title="'.$title.'"> 
                                                <img class="user-image " style="height: 55px; width: 55px;object-fit: cover;border-radius: 50%;" src="'.$src.'">
                                        
                                            </a> 
                                        </div> 
                                    </div> 
                                </div> '; 
                }
                $j=$j+1;
                $title = $recomm->customer->first_name .' '.$recomm->customer->last_name;
                if($recomm->customer->city != '' || $recomm->customer->state != ''){
                    $city_state = '<div class="location-taxt">  
                                        <p><i class="fas fa-map-marker-alt"></i> '.$recomm->customer->city.' '.$recomm->customer->state.'</p>
                                    </div>'; 
                } else {
                    $city_state = '<div class="location-taxt">  
                                        
                                    </div>'; 
                }

                    $provider_popup_details[] = '<div class="col-xl-6 mb-2">  
                        <div class="user-info d-flex">
                            <img class="user-image " style="height: 55px; width: 55px;object-fit: cover;border-radius: 50%;" src="'.$src.'">
                            <div class="user-name-btn ml-2"  style="max-width:100%"> 
                                <a href="'.$url.'"> 
                                    <h4 class="user-name m-0">  
                                        '.$title.'
                                    </h4>  

                                    '.$city_state.'
                                </a> 
                            </div> 
                        </div> 

                    </div>'; 
                            

                
            }
            
            if($recommended_provider->count() > 7){
                $provider_popup_count = '<a href="javascript:void(0)" data-toggle="modal" data-target="#recommendedviewallProvider" class="btn btn-outline-danger">View All</a> ';
            } else {
                $provider_popup_count = '1';
            }
             
            $response = ['status' => true, "message" => 'Removed as Favorite.', "favorited_by_provider" => $favorited_by_provider, "favorited_by_athelete" => $favorited_by_athelete, "authlete_popup_count" => $authlete_popup_count, "provider_popup_count" => $provider_popup_count, "athelete_count" => $recommended_authlete->count(), "provider_count" => $recommended_provider->count(), "authlete_popup_details" => $authlete_popup_details, "provider_popup_details" => $provider_popup_details];
        }



        return json_encode($response);
    }

    public function servicebookdetails($event_id) {
        $user = Auth::guard('front_auth')->user();
        $serviceEventsData = DB::select("select o.*,ts.price,ts.name as service_name,s.name as category_name,ts.format as service_type from provider_service_book as o join trainer_services as ts on o.service_id=ts.id join services as s on ts.service_id=s.id where o.id='".$event_id."'");
        $days = $serviceEventsData[0]->days;
        $service_name = $serviceEventsData[0]->service_name;
        $service_category = $serviceEventsData[0]->category_name;
        $service_price = $serviceEventsData[0]->price;
        $service_type = $serviceEventsData[0]->service_type;
        $service_id = $serviceEventsData[0]->service_id;
        $response = ['event_id' => $event_id, "days" => $days, "service_name" => $service_name, "service_category" => $service_category, "service_price" => $service_price, "service_type" => $service_type, "service_id" => $service_id];
        
        return json_encode($response);
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

     public function checkeventdetails($days,$serviceId,$appointment_date) {
        $trainerId = Auth::guard('front_auth')->user()->id;
        if($days == 0){
            $days = 'day7';
            if($serviceId == 0){
                $providerScheduling = DB::table('provider_scheduling')->where(["trainer_id" => $trainerId])->first();
                $providerSchedulingCnt = DB::table('provider_scheduling')->where(["trainer_id" => $trainerId, $days => ""])->count();
                $providerSchedulingDateCnt = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId, "day" => $days])->count();
                if(isset($providerScheduling)){
                    $providerSchedulingCnt = $providerSchedulingCnt;
                    if($providerSchedulingDateCnt != 0){
                        $providerSchedulingDateCnt = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId, "day" => $days, "date" => $appointment_date])->count();
                        if($providerSchedulingDateCnt == 0){
                            $providerSchedulingCnt = 1;
                        } else {
                            $providerSchedulingDateCnt = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId, "day" => $days, "date" => $appointment_date])->first();
                            if($providerSchedulingDateCnt->time == ''){

                                $providerSchedulingCnt = 1;
                            } else {
                                $providerSchedulingCnt = 0;
                            }
                        }
                        
                    }
                } elseif($providerSchedulingDateCnt != 0){
                    $providerSchedulingCnt = $providerSchedulingCnt;
                    if($providerSchedulingDateCnt != 0){
                        $providerSchedulingDateCnt = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId, "day" => $days, "date" => $appointment_date])->count();
                        if($providerSchedulingDateCnt == 0){
                            $providerSchedulingCnt = 1;
                        } else {
                            $providerSchedulingDateCnt = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId, "day" => $days, "date" => $appointment_date])->first();
                            if($providerSchedulingDateCnt->time == ''){

                                $providerSchedulingCnt = 1;
                            } else {
                                $providerSchedulingCnt = 0;
                            }
                        }
                        
                    }
                } else {
                    $providerSchedulingCnt = 1;
                }
            } else {
                $providerScheduling = DB::table('provider_scheduling_service')->where(["trainer_id" => $trainerId, "service_id" => $serviceId])->first();
                $providerSchedulingCnt = DB::table('provider_scheduling_service')->where(["trainer_id" => $trainerId, "service_id" => $serviceId, $days => ""])->count();
                $providerSchedulingDateCnt = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId, "service_id" => $serviceId, "day" => $days])->count();
                if(isset($providerScheduling)){
                    $providerSchedulingCnt = $providerSchedulingCnt;
                    if($providerSchedulingDateCnt != 0){
                        $providerSchedulingDateCnt = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId, "service_id" => $serviceId, "day" => $days, "date" => $appointment_date])->count();
                        if($providerSchedulingDateCnt == 0){
                            $providerSchedulingCnt = 1;
                        } else {
                            $providerSchedulingDateCnt = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId, "service_id" => $serviceId, "day" => $days, "date" => $appointment_date])->first();
                            if($providerSchedulingDateCnt->time == ''){

                                $providerSchedulingCnt = 1;
                            } else {
                                $providerSchedulingCnt = 0;
                            }
                        }
                        
                    }
                } elseif($providerSchedulingDateCnt != 0){
                    $providerSchedulingCnt = $providerSchedulingCnt;
                    if($providerSchedulingDateCnt != 0){
                        $providerSchedulingDateCnt = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId, "service_id" => $serviceId, "day" => $days, "date" => $appointment_date])->count();
                        if($providerSchedulingDateCnt == 0){
                            $providerSchedulingCnt = 1;
                        } else {
                            $providerSchedulingDateCnt = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId, "service_id" => $serviceId, "day" => $days, "date" => $appointment_date])->first();
                            if($providerSchedulingDateCnt->time == ''){

                                $providerSchedulingCnt = 1;
                            } else {
                                $providerSchedulingCnt = 0;
                            }
                        }
                        
                    }
                } else {
                    $providerSchedulingCnt = 1;
                }
            }
        } else {
            $days = 'day'.$days;
            $trainerId;
            if($serviceId == 0){
                $providerScheduling = DB::table('provider_scheduling')->where(["trainer_id" => $trainerId])->first();
                
                $providerSchedulingCnt = DB::table('provider_scheduling')->where(["trainer_id" => $trainerId, $days => ""])->count();
                $providerSchedulingDateCnt = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId, "day" => $days])->count();
                if(isset($providerScheduling)){
                    $providerSchedulingCnt = $providerSchedulingCnt;
                    if($providerSchedulingDateCnt != 0){
                        $providerSchedulingDateCnt = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId, "day" => $days, "date" => $appointment_date])->count();
                        if($providerSchedulingDateCnt == 0){
                            $providerSchedulingCnt = 1;
                        } else {
                            $providerSchedulingDateCnt = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId, "day" => $days, "date" => $appointment_date])->first();
                            if($providerSchedulingDateCnt->time == ''){

                                $providerSchedulingCnt = 1;
                            } else {
                                $providerSchedulingCnt = 0;
                            }
                        }
                        
                    }
                } elseif($providerSchedulingDateCnt != 0){
                    $providerSchedulingCnt = $providerSchedulingCnt;
                    if($providerSchedulingDateCnt != 0){
                        $providerSchedulingDateCnt = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId, "day" => $days, "date" => $appointment_date])->count();
                        if($providerSchedulingDateCnt == 0){
                            $providerSchedulingCnt = 1;
                        } else {
                            $providerSchedulingDateCnt = DB::table('provider_scheduling_date')->where(["trainer_id" => $trainerId, "day" => $days, "date" => $appointment_date])->first();
                            if($providerSchedulingDateCnt->time == ''){

                                $providerSchedulingCnt = 1;
                            } else {
                                $providerSchedulingCnt = 0;
                            }
                        }
                        
                    }
                } else {
                    $providerSchedulingCnt = 1;
                }
            } else {
                $providerScheduling = DB::table('provider_scheduling_service')->where(["trainer_id" => $trainerId, "service_id" => $serviceId])->first();
                $providerSchedulingCnt = DB::table('provider_scheduling_service')->where(["trainer_id" => $trainerId, "service_id" => $serviceId, $days => ""])->count();
                $providerSchedulingDateCnt = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId, "service_id" => $serviceId, "day" => $days])->count();
                if(isset($providerScheduling)){
                    $providerSchedulingCnt = $providerSchedulingCnt;
                    if($providerSchedulingDateCnt != 0){
                        $providerSchedulingDateCnt = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId, "service_id" => $serviceId, "day" => $days, "date" => $appointment_date])->count();
                        if($providerSchedulingDateCnt == 0){
                            $providerSchedulingCnt = 1;
                        } else {
                            $providerSchedulingDateCnt = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId, "service_id" => $serviceId, "day" => $days, "date" => $appointment_date])->first();
                            if($providerSchedulingDateCnt->time == ''){

                                $providerSchedulingCnt = 1;
                            } else {
                                $providerSchedulingCnt = 0;
                            }
                        }
                        
                    }
                } elseif($providerSchedulingDateCnt != 0){
                    $providerSchedulingCnt = $providerSchedulingCnt;
                    if($providerSchedulingDateCnt != 0){
                        $providerSchedulingDateCnt = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId, "service_id" => $serviceId, "day" => $days, "date" => $appointment_date])->count();
                        if($providerSchedulingDateCnt == 0){
                            $providerSchedulingCnt = 1;
                        } else {
                            $providerSchedulingDateCnt = DB::table('provider_scheduling_service_date')->where(["trainer_id" => $trainerId, "service_id" => $serviceId, "day" => $days, "date" => $appointment_date])->first();
                            if($providerSchedulingDateCnt->time == ''){

                                $providerSchedulingCnt = 1;
                            } else {
                                $providerSchedulingCnt = 0;
                            }
                        }
                        
                    }
                } else {
                    $providerSchedulingCnt = 1;
                }
            }
        }
        
        
        $response = ['providerSchedulingCnt' => $providerSchedulingCnt];
        
        return json_encode($response);
    }

    public function confirmservicebookdetails(Request $request){
    //public function confirmservicebookdetails($service_id, $event_time, $event_date){
        
        
        try {
            $appointmentdate_str=explode('-',$request->event_date);
            $appointmentdate = $appointmentdate_str[2].'-'.$appointmentdate_str[0].'-'.$appointmentdate_str[1];
            $serviceEventsData = DB::table('trainer_services')->where(["id" => $request->service_id])->first();
            if($serviceEventsData->format == 'In person - Single Appointment' || $serviceEventsData->format == 'Virtual - Single Appointment'){
                $event_count = DB::table('orders')->where(["service_id" => $request->service_id, "appointment_date" => $appointmentdate, "service_time" => $request->event_time])->count();
                $maximum_count = 0;
                $response = ['event_count' => $event_count, 'maximum_count' => $maximum_count];
            } else {
                $event_count = DB::table('orders')->where(["service_id" => $request->service_id, "appointment_date" => $appointmentdate, "service_time" => $request->event_time])->count();
                $response = ['event_count' => $event_count, 'maximum_count' => $serviceEventsData->max_bookings];
            }
            
            
            
            return json_encode($response);
        }catch(Exception $ex){
            return json_encode(["message" => $ex->getMessage()]);
        } 
        
       
        
        
    }

    public function JoinGroup(Request $request) {
        $user = Auth::guard('front_auth')->user();
        $recommendeds = GroupsUsers::where('group_id', $request->id)->where('user_id', $user->id)->first();
        $input = array();
        if ($request->type == 'add') {
            if (empty($recommendeds)) {
                $recommendeds = new GroupsUsers();
                $input['group_id'] = $request->id;
                $input['user_id'] = $user->id;
                $data = $recommendeds->fill($input)->save();
            }
            $response = ['status' => true, "message" => 'Joined Group Successfully.'];
        } else {
            $recommendeds->delete();
            $response = ['status' => true, "message" => 'Leave Group Successfully.'];
        }

        return json_encode($response);
    }

    public function findgroup(Request $request) {
        $user = Auth::guard('front_auth')->user();
        $page = 1;
        return view('front.customer.findgroup', ['page' => $page, 'user' => $user]);
    }

    public function findgroupsdata(Request $request) {
        $user = Auth::guard('front_auth')->user();
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        $grouplist = Groups::with('user');
        if (isset($request->keyword) && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $grouplist->where(function ($query) use ($keyword) {
                $query->orWhere('name', 'LIKE', '%' . $keyword . '%')
                ;
            });
        }
        $grouplist = $grouplist->paginate(10);
        return view('front.customer.findgroupsdata', ['user' => $user, 'grouplist' => $grouplist]);
    }

    public function getreviewsdata(Request $request) {
        $user_id = $request->user_id;
        $retingdata = Ratings::with('trainer')->where('user_id', $user_id)->get();
        return view('front.customer.reviewsdata', ['retingdata' => $retingdata, 'user_id' => $user_id]);
    }

    public function notificationdata(Request $request) {
        $user_id = $request->user_id;
        $user = FrontUsers::find($request->user_id);

        $notification = \App\Notification::with('quotebyfrom')->with('quotebyto')->where('to_user_id', Auth::guard('front_auth')->user()->id)->orderBy('created_at', 'desc')->get();
        $requestlist = \App\Friend::with('user', 'friend')->where('friend_id', $user->id)->where('accept', 0)->get();
        //$notification = \App\Notification::with('quotebyfrom')->with('quotebyto')->where('to_user_id', Auth::guard('front_auth')->user()->id)->where('notification_type', 'Send')->where('title', 'Received Friend Request')->orderBy('created_at', 'desc')->get();
        //$notification = \App\Friend::with('user', 'friend')->where('user_id', Auth::guard('front_auth')->user()->id)->where('accept', 0)->get();
       // echo '<pre>';print_r($requestlist);exit();
        return view('front.customer.notificationdata', ['notificationdata' => $notification, 'user_id' => $user_id, 'requestlist' => $requestlist]);
    }

    public function savedresourcedata(Request $request) {
        $user_id = $request->user_id;
        $resourcesaved = DB::table('resource_count')->where(["user_id" => $user_id, "saved" => 1])->get();
        
        return view('front.customer.savedresourcedata', ['resourcesaved' => $resourcesaved, 'user_id' => $user_id]);
    }
     public function InviteFriend(Request $request) {
        $validator = Validator::make($request->all(), [
//            'email' => 'required|email|unique:users,email'
            'email' => 'required'
        ]);
//            $validator->after(function ($validator) use ($request) {
//                if (InviteFriend::where('email', $request->input('email'))->exists()) {
//                    $validator->errors()->add('email', 'There exists an invite with this email!');
//                }
//            });
            if ($validator->fails()) {
                $errors = $validator->errors()->getMessages();
                return response()->json($errors, 422);
            }

            $invite_emails = explode(',', $request->input('email'));
            
            $user = Auth::guard('front_auth')->user();
            for($i=0;$i<count($invite_emails);$i++){
                
            do {
                $token = Str::random(20);
            } while (InviteFriend::where('token', $token)->first());
            $invite = InviteFriend::create([
                'token' => $token,
                'user_id' => $user->id,
                'email' => $invite_emails[$i]
            ]); 
            $subject = 'Training Block : Invite Friend';
            $emails_name = 'Training Block';
            $admin_email = "auto-reply@trainingblockusa.com";
            $admin_name = "Training Block";
            $to_emails = $invite_emails[$i];
                /*Mail::send('email.invite_friend', array('data' => $invite), function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                    $message->from($admin_email, $admin_name);
                    $message->to($emails, $emails_name)->subject($subject);
                });*/

            $mail = new PHPMailer;


            //Server settings                   // Enable verbose debug output
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = env('MAIL_SECURE');
            $mail->Host = env('MAIL_HOST');
            $mail->Port = env('MAIL_PORT');
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SetFrom(env('MAIL_FROM'), 'Training Block');
            $mail->Subject = "You have been invited to join the Training Block network";
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
                            <a href="'.url('/').'" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;">
                                <img src="https://trainingblockusa.com/public/images/logo.png" alt="img" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border: none; max-width: 150px;">
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
                                        <!--<h1 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #2F3133; font-size: 19px; font-weight: bold; margin-top: 0; text-align: left;">
                                         You have been invited to join the Training Block network
                                        </h1><br>-->
                                        
                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                        Welcome! '.$user->first_name.' '.$user->last_name.' has invited you to join Training Block. <a href="'. route('register').'" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #3869D4;">Click here</a> to Register!
                                        </p> 
                                        
                                        <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-top: 1px solid #EDEFF2; margin-top: 25px; padding-top: 25px;">
                                            <tr>
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 12px;">If you having trouble
                                                        into your web browser:
                                                        <a href="'.url('/login').'" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #3869D4;">Login</a>
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
                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #AEAEAE; font-size: 12px; text-align: center;">Copyrights © '.date('Y').' Training Block. All rights reserved.</p>
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
            $mail->AddAddress($to_emails, 'IDM');

            if (!$mail->send()) {
               // echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                //echo 'Message sent!';
            }
            }
            $response = ['status' => true, "message" => 'Invitation has been sent successfully.'];
            return json_encode($response); 
    }

    public function account_information(){
        $providerOrders = DB::table('provider_orders')->where(["trainer_id" => Auth::guard('front_auth')->user()->id, "status" => 0])->get();
        $checkproviderOrders = DB::table('provider_orders')->where(["trainer_id" => Auth::guard('front_auth')->user()->id])
        ->where(function($query) {
                    $query->where('subscription_status', '=', 'active')
                          ->orwhere('subscription_status', '=', 'trialing');
                })
        ->count();
        $accountData = DB::select("select * from subscriptionplan");
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

             $customers = $stripe->customers->all(['email'=>Auth::guard('front_auth')->user()->email
              ]);
             $list_all_card_details = array();
            foreach($customers as $val){
                
                $list_all_card_details[] =  $stripe->customers->allSources(
                      $val->id,
                      ['object' => 'card']
                    );
            }
                
            
        return view('front.trainer.account_information',['providerOrders' => $providerOrders, 'checkproviderOrders' => $checkproviderOrders, 'accountData' => $accountData, 'list_all_card_details' => $list_all_card_details]);
    }

    function createproviderpaymentintent(Request $request) {
            
        $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); 

            $response = array();

            $amountToPay = 100;

            $payment_intent = \Stripe\PaymentIntent::create([
            'payment_method_types' => ['card'],
            'amount' => $amountToPay*100,
            'currency' => 'usd',
            ]);


            $response = ['status' => true, "Message" => 'Record deleted successfuly','client_secret' => $payment_intent->client_secret];
        
            return $response;
        }

    public function provider_edit_plan_subscription($planid){
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $planid = base64_decode($planid);

        $providerOrders = DB::table('provider_orders')->where(["id" => $planid])->first();
       
        // $accountData = DB::select("select * from subscriptionplan where subcription_plan != '".$providerOrders->plan_type."'");
        $accountData = DB::select("select * from subscriptionplan");
        // echo '<pre>';print_r($accountData);exit();
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

             $customers = $stripe->customers->all(['email'=>Auth::guard('front_auth')->user()->email
              ]);
             $list_all_card_details = array();
            foreach($customers as $val){
                
                $list_all_card_details[] =  $stripe->customers->allSources(
                      $val->id,
                      ['object' => 'card']
                    );
            }
                
            
        return view('front.trainer.edit_account_information',['providerOrders' => $providerOrders, 'accountData' => $accountData, 'list_all_card_details' => $list_all_card_details]);
    }

    public function provider_cancel_subscription($orderid, $subscriptionid){ //dd("hello");
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
      $orderId = base64_decode($orderid);
      $subscriptionId = base64_decode($subscriptionid);
      
      if($subscriptionId){
        $subscription = \Stripe\Subscription::retrieve(
          $subscriptionId
        );
        $subscription->delete();
        $provider_order_Update = DB::update('update provider_orders set subscription_status="cancelled", cancel_date="'.date('Y-m-d').'" where id="'.$orderId.'"');
        $users_status_Update = DB::update('update front_users set is_payment="0" where id="'.Auth::guard('front_auth')->user()->id.'"');
      }
      if($provider_order_Update){

        // Email for Cancel Subscription
          $trainerEmail = Auth::guard('front_auth')->user()->email;
          $mail_provider = new PHPMailer;

          $mail_provider->IsSMTP();
          $mail_provider->SMTPAuth = true;
          $mail_provider->SMTPSecure = env('MAIL_SECURE');
          $mail_provider->Host = env('MAIL_HOST');
          $mail_provider->Port = env('MAIL_PORT');
          $mail_provider->Username = env('MAIL_USERNAME');
          $mail_provider->Password = env('MAIL_PASSWORD');
          $mail_provider->SetFrom(env('MAIL_FROM'), 'Training Block');
          $mail_provider->Subject = "Cancellation confirmation";
          $mail_provider->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
            <tbody><tr style="
                background: #555;
            ">
                <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="'.url("/public/images/logo.png").'"> </td>
            </tr>
            <tr>
                <td style="padding-top:20px;"> <h4>Hello '.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.',</h4> </td>
            </tr>
            <tr>
                <td style="padding-bottom:15px;"> 
                    <p>As you requested, your subscription to the Training Block network has been canceled effective  '.date('m-d-Y').'.</p>
                    <p>We hate to see you go, If you canceled your subscription in error, or would like to join again, click on the button below to restart the membership without any interruption.</p><br />
                    <p><a href="'.url('/trainer/account-information').'" style="background: #00ab91;color: #fff;padding: 10px;border-radius: 5px;text-decoration: none;">Restart Membership</a></p><br />
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

        Session::flash('message', 'Subscription cancelled successfully.');
        return redirect()->back();
      }
    }

    function providerpaymenteditsave(Request $request) {
        parse_str($request->form_data, $searcharray);
        $requestData = $request->all();
       // echo '<pre>';print_r($requestData);exit();

        //dd($requestData);
        //echo '<pre>';print_r($requestData['payment-source']);exit();
        $errors = Validator::make($requestData, [
                    'subscription_plan' => 'required',
                    //'price' => 'required',
        ]);
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
            /*$stripe = new \Stripe\StripeClient(
                              env('STRIPE_SECRET_KEY')
                            );*/
                    Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

                  $orderId = $requestData['order_id'];
                  $subscriptionId = $requestData['subscription_id'];
                  
                  if($subscriptionId){
                        $subscription = \Stripe\Subscription::retrieve(
                          $subscriptionId
                        );
                        $subscription->delete();
                        $provider_order_Update = DB::update('update provider_orders set subscription_status="cancelled", cancel_date="'.date('Y-m-d').'", status="1" where id="'.$orderId.'"');
                        $users_status_Update = DB::update('update front_users set is_payment="0" where id="'.Auth::guard('front_auth')->user()->id.'"');
                      }
                    
                        $startDate = $requestData['start_date'];
                    
                      $user = Auth::guard('front_auth')->user();
                      $accountData = DB::table('subscriptionplan')->where(["subcription_plan" => $requestData['subscription_plan']])->first();
                      $planId = $accountData->plan_id;

                    if($requestData['payment-source'] == 'New_card'){
                        $stripeCustomer = \Stripe\Customer::create([
                          'email' => $user->email,
                          "source" => $request->stripeToken,
                        ]);
                      
                        $stripeCustomerId = $stripeCustomer->id;
                    } else {
                        $stripeCustomerId = $requestData['stripeToken'];
                    }
                  
                    $subscriptionParam = ['customer' => $stripeCustomerId,
                    'items' => [['plan' => $planId]],
                    ];
                  //create subscription
                  
                  $date = new \Carbon\Carbon;
                  if($startDate > $date){
                    $timestamp = \Carbon\Carbon::parse($startDate)->timestamp;
                    $subscriptionParam["trial_end"] = $timestamp;
                  }
                  
                  try {
                        $subscription = \Stripe\Subscription::create($subscriptionParam);
                     
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
                      
                      $provider_orders = DB::table('provider_orders')->insert([
                              'trainer_id' => Auth::guard('front_auth')->user()->id,
                              'plan_type' => $requestData["subscription_plan"],
                              'amount' => $accountData->price,
                              'start_date' => date('Y-m-d'),
                              'stripe_subscription_id' => $subscription->id,
                              'subscription_status' => $subscription->status,
                              'json_response' => json_encode($subscription),
                              'stripeToken' => $request->stripeToken
                          ]);
                      $users_status_Update = DB::update('update front_users set is_payment="1" where id="'.Auth::guard('front_auth')->user()->id.'"');
                      
                      $toEmails = 'trainingblockusa@gmail.com'; 
                      $trainerEmail = Auth::guard('front_auth')->user()->email;
                    
                    
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
                    <td style="padding:10px;border-bottom:solid 1px #555;"> <img src="'.url("/public/images/logo.png").'"> </td>
                </tr>
                <tr>
                    <td style="padding-top:20px;"> <h4>Dear '.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.',</h4> </td>
                </tr>

                <tr>
                    <td style="padding-bottom:15px;"> 
                        <p>Congratulations! You have purchased  with . We hope this service brings you one step closer to your strongest performance yet. Happy training! </p>
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
                      <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="'.url("/public/images/logo.png").'"> </td>
                      </tr>
                      <tr>
                          <td style="padding-top:20px;"> <h4>Dear '.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.',</h4> </td>
                      </tr>

                      <tr>
                          <td style="padding-bottom:15px;"> 
                              <p>Congratulations!  has purchased your.  Please log in to your account to review the details. </p>
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
                    
                     
                      Session::flash('message', 'Provider has been subscription plan changed.');
                     // return redirect()->back(); 
                     return redirect('trainer/account-information');
                    }else{
                      Session::flash ( 'fail-message', "Error! Something went wrong." );
                      return redirect()->back();
                    }
              
           


         }
    }

    function providerpaymentsave(Request $request) {
            parse_str($request->form_data, $searcharray);
            $requestData = $request->all();
             
        //dd($requestData);
        //echo '<pre>';print_r($requestData['payment-source']);exit();
        $errors = Validator::make($requestData, [
                    'subscription_plan' => 'required',
                    //'price' => 'required',
        ]);
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
            /*$stripe = new \Stripe\StripeClient(
                              env('STRIPE_SECRET_KEY')
                            );*/
                            Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
                    
                        $startDate = $requestData['start_date'];
                    
                      $user = Auth::guard('front_auth')->user();
                      $accountData = DB::table('subscriptionplan')->where(["subcription_plan" => $requestData['subscription_plan']])->first();
                      $planId = $accountData->plan_id;

                    if($requestData['payment-source'] == 'New_card'){
                        $stripeCustomer = \Stripe\Customer::create([
                          'email' => $user->email,
                          "source" => $request->stripeToken,
                        ]);
                      
                        $stripeCustomerId = $stripeCustomer->id;
                    } else {
                        $stripeCustomerId = $requestData['stripeToken'];
                    }
                  
                    $subscriptionParam = ['customer' => $stripeCustomerId,
                    'items' => [['plan' => $planId]],
                    ];
                  //create subscription
                  
                  $date = new \Carbon\Carbon;
                  if($startDate > $date){
                    $timestamp = \Carbon\Carbon::parse($startDate)->timestamp;
                    $subscriptionParam["trial_end"] = $timestamp;
                  }
                  
                  try {
                        $subscription = \Stripe\Subscription::create($subscriptionParam);
                     
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
                      
                      $provider_orders = DB::table('provider_orders')->insert([
                              'trainer_id' => Auth::guard('front_auth')->user()->id,
                              'plan_type' => $requestData["subscription_plan"],
                              'amount' => $accountData->price,
                              'start_date' => date('Y-m-d'),
                              'stripe_subscription_id' => $subscription->id,
                              'subscription_status' => $subscription->status,
                              'json_response' => json_encode($subscription),
                              'stripeToken' => $request->stripeToken
                          ]);
                      $users_status_Update = DB::update('update front_users set is_payment="1" where id="'.Auth::guard('front_auth')->user()->id.'"');
                      
                      $toEmails = 'trainingblockusa@gmail.com'; 
                      $trainerEmail = Auth::guard('front_auth')->user()->email;
                    
                    
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
                    <td style="padding:10px;border-bottom:solid 1px #555;"> <img src="'.url("/public/images/logo.png").'"> </td>
                </tr>
                <tr>
                    <td style="padding-top:20px;"> <h4>Dear '.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.',</h4> </td>
                </tr>

                <tr>
                    <td style="padding-bottom:15px;"> 
                        <p>Congratulations! You have purchased  with . We hope this service brings you one step closer to your strongest performance yet. Happy training! </p>
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
                      <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="'.url("/public/images/logo.png").'"> </td>
                      </tr>
                      <tr>
                          <td style="padding-top:20px;"> <h4>Dear '.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.',</h4> </td>
                      </tr>

                      <tr>
                          <td style="padding-bottom:15px;"> 
                              <p>Congratulations!  has purchased your.  Please log in to your account to review the details. </p>
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
                    
                     
                      Session::flash('message', 'Your subscription has been purchased.');
                     // return redirect()->back(); 
                     return redirect()->back();
                    }else{
                        echo '<pre>';exit();
                      Session::flash ( 'fail-message', "Error! Something went wrong." );
                      return redirect()->back();
                    }
              
           


         }
        }


        function providerEmailUs(Request $request){
            $requestData = $request->all();

            $firstName = $requestData["trainerFirstName"];
            $lastName = $requestData["trainerLastName"];
            $toEmail = $requestData["trainer_email"];
            $atheleteFirstName = $requestData['firstName'];
            $atheleteLastName = $requestData['lastName'];
            $atheleteEmail = $requestData['email'];
            $atheletePhone = $requestData['phone'];
            $atheleteSubject = $requestData['subject'];
            $bodyContent = $requestData['email_body'];
            $senderID = isset(Auth::guard('front_auth')->user()->id) ? Auth::guard('front_auth')->user()->id : 'NULL';

            if(isset($requestData['emailRegister']) && $requestData['emailRegister'] == 1){
                $athelete_register = DB::table('front_users')->insert([
                    'first_name' => $requestData['firstName'],
                    'last_name' => $requestData['lastName'],
                    'email' => $requestData['email'],
                    'password' => Hash::make($requestData['password']),
                    'affiliate_id' => str_random(5),
                    'referred_by' => NULL,
                    'referral_wallet' => NULL,
                    'user_role' => 'customer',
                    'status' => 'active'
                ]);
            }

                $athelete_enquiry = DB::table('trainer_email_requests')->insert([
                    'sender_id' => $senderID,
                    'receiver_id' => $request->receiverId,
                    'receiver_email' => $toEmail,
                    'athelete_first_name' => $atheleteFirstName,
                    'athelete_last_name' => $atheleteLastName,
                    'athelete_email' => $atheleteEmail,
                    'athelete_phone' => $atheletePhone,
                    'athelete_subject' => $atheleteSubject,
                    'email_content' => $bodyContent,
                    'date' => date('Y-m-d')
                ]);

                if(isset($athelete_enquiry)){
                    $mail_trainer = new PHPMailer;
                    $mail_trainer->IsSMTP();
                    $mail_trainer->SMTPAuth = true;
                    $mail_trainer->SMTPSecure = env('MAIL_SECURE');
                    $mail_trainer->Host = env('MAIL_HOST');
                    $mail_trainer->Port = env('MAIL_PORT');
                    $mail_trainer->Username = env('MAIL_USERNAME');
                    $mail_trainer->Password = env('MAIL_PASSWORD');
                    $mail_trainer->SetFrom(env('MAIL_FROM'), 'Training Block');
                    $mail_trainer->Subject = "Training Block Inquiry";
                    $mail_trainer->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
                <tbody><tr style="
                    background: #555;
                ">
                    <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="'.url("/public/images/logo.png").'"> </td>
                </tr>
                <tr>
                    <td style="padding-top:20px;"> <h4>Dear '.$firstName.' '.$lastName.',</h4> </td>
                </tr>

                <tr>
                <td style="padding-bottom:10px;"><p>You received this message through Training Block :</p> </td>
                </tr>
                <tr>
                <td style="padding-bottom:10px;"><p><strong>Name :</strong> '.$atheleteFirstName. ' ' .$atheleteLastName.'</p> </td>
                </tr>
                <tr>
                <td style="padding-bottom:10px;"> <p><strong>Email Address :</strong> '.$atheleteEmail.'</p> </td>
                </tr>
                <tr>
                <td style="padding-bottom:10px;">  <p><strong>Phone Number :</strong> '.$atheletePhone.'</p> </td>
                </tr>
                <tr>
                <td style="padding-bottom:10px;">  <p><strong>Subject :</strong> '.$atheleteSubject.'</p> </td>
                </tr>
                <tr>
                 <td style="padding-bottom:10px;">  <p><strong>Message :</strong> <br />&nbsp;&nbsp;&nbsp;&nbsp;'.$bodyContent.'</p> </td>
                </tr>
                <tr>
                <td style="background: #00ab91;padding:10px;color:#fff;"> <p> This is an automated message. Please do not reply to this email</p> </td>
                </tr>
                <tr>
                <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
                <p></p>
                <span style="margin-top:5px;">The Training Block Team</span>
                </td>
                </tr>

                </tbody></table></body></html>');
                $mail_trainer->AddAddress($toEmail, 'Training Block');
            if($mail_trainer->send()){
                $response = ['status' => true, "message" => 'Something went wrong.'];
                return redirect()->back();
            }
            else {            
                $response = ['status' => true, "message" => 'Email Sent Successfully.'];
                echo json_encode($mail_trainer->ErrorInfo);
            }
            
        }



    }

    function emailVerify(Request $request){
        $requestData = $request->all();

        $emailCheck = DB::table('subscribe')
                    ->select('*')
                    ->where('email', '=', $requestData['email'])
                    ->count();
        if ($emailCheck > 0) {
            return  $response = ['status' => false, "message" => 'Email is already taken.'];
        } else {
            return  $response = ['status' => true, "message" => 'Email is unique.'];
        }
    
    }

    function sendAthleteHelpEmail(Request $request){
        $requestData = $request->all();
        $toEmail = "support@trainingblockusa.com";
        $atheleteFirstName = $requestData['firstName'];
        $atheleteLastName = $requestData['lastName'];
        $atheleteEmail = $requestData['email'];
        $atheletePhone = $requestData['phone'];
        $atheleteSubject = $requestData['subject'];
        $bodyContent = $requestData['email_body'];

        $athelete_requests = DB::table('athlete_help_requests')->insert([
            'athelete_first_name' => $atheleteFirstName,
            'athelete_last_name' => $atheleteLastName,
            'athelete_email' => $atheleteEmail,
            'athelete_phone' => $atheletePhone,
            'athelete_subject' => $atheleteSubject,
            'email_content' => $bodyContent,
            'date' => date('Y-m-d')
        ]);

            if(isset($athelete_requests)){
            $help_mail = new PHPMailer;
            $help_mail->IsSMTP();
            $help_mail->SMTPAuth = true;
            $help_mail->SMTPSecure = env('MAIL_SECURE');
            $help_mail->Host = env('MAIL_HOST');
            $help_mail->Port = env('MAIL_PORT');
            $help_mail->Username = env('MAIL_USERNAME');
            $help_mail->Password = env('MAIL_PASSWORD');
            $help_mail->SetFrom(env('MAIL_FROM'), 'Training Block');
            $help_mail->Subject = "Athlete Enquiry";
            $help_mail->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
            <tbody><tr style="
                background: #555;
            ">
                <td style="padding:10px;border-bottom:solid 1px #555;text-align:center;"> <img src="'.url("/public/images/logo.png").'"> </td>
            </tr>
            <tr>
                <td style="padding-top:20px;"> <h4>Dear Admin,</h4> </td>
            </tr>

            <tr>
            <td style="padding-bottom:10px;"><p>You received this message through Training Block :</p> </td>
            </tr>
            <tr>
            <td style="padding-bottom:10px;"><p><strong>Name :</strong> '.$atheleteFirstName. ' ' .$atheleteLastName.'</p> </td>
            </tr>
            <tr>
            <td style="padding-bottom:10px;"> <p><strong>Email Address :</strong> '.$atheleteEmail.'</p> </td>
            </tr>
            <tr>
            <td style="padding-bottom:10px;">  <p><strong>Phone Number :</strong> '.$atheletePhone.'</p> </td>
            </tr>
            <tr>
            <td style="padding-bottom:10px;">  <p><strong>Subject :</strong> '.$atheleteSubject.'</p> </td>
            </tr>
            <tr>
             <td style="padding-bottom:10px;">  <p><strong>Message :</strong> <br />&nbsp;&nbsp;&nbsp;&nbsp;'.$bodyContent.'</p> </td>
            </tr>
            <tr>
            <td style="background: #00ab91;padding:10px;color:#fff;"> <p> This is an automated message. Please do not reply to this email</p> </td>
            </tr>
            <tr>
            <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
            <p></p>
            <span style="margin-top:5px;">The Training Block Team</span>
            </td>
            </tr>

            </tbody></table></body></html>');
            $help_mail->AddAddress($toEmail, 'Training Block');
        if($help_mail->send()){
            $response = ['status' => true, "message" => 'Something went wrong.'];
            return redirect()->back();
        }
        else {            
            $response = ['status' => true, "message" => 'Email Sent Successfully.'];
            echo json_encode($response);
        }
        
    }
    }
}