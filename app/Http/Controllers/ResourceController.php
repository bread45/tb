<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Users\Entities\FrontUsers;
use Auth;
use App\TrainerServices;
use App\Resource;
use App\Services;
use App\ResourceCategory;
use Validator;
use Session;
use Image;
use Mail;
use Modules\Orders\Entities\Orders;

use Illuminate\Http\Response;
use DataTables;
use Stripe;
use Stripe_Error;
use App\StripeAccounts;
use DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DateTime;
use URL;
use Newsletter;
require 'mail/PHPMailer/Exception.php';
require 'mail/PHPMailer/PHPMailer.php';
require 'mail/PHPMailer/SMTP.php';
require 'mail/vendor/autoload.php';

class ResourceController extends Controller
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
        $resource = DB::select('select * from resource where trainer_id="'.$trainerId.'" order by id desc');
        
        return view('front.trainer.resource.resource',["resource" => $resource]);
    }

   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $trainerId = Auth::guard('front_auth')->user()->id;
        //$resources_category = DB::select('select * from resource_category where status=1 AND trainer_id = "'.$trainerId.'" order by name asc');
        $resources_category = Services::where('status','active')->orderby('name','asc')->get();
        $formats = ["Article"=>"Article","Video"=>"Video"];
       
        return view('front.trainer.resource.add-resource',["formats" => $formats,"resources_category" => $resources_category]);
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
         //echo '<pre>';print_r($requestData);exit();
        if($requestData['format'] == 'Article'){
            $errors = Validator::make($requestData, [
                    'title' => 'required',
                   // 'name' => 'required',
                    'resource_category' => 'required',
                    //'resource_image_name' => 'required',
                    'format' => 'required',
                    //'tag' => 'required',
                    //'upload' => 'mimes:pdf,doc',
                    'image_upload' => 'mimes:jpeg,png,jpg',
                   
            ]);
        } else {

            $errors = Validator::make($requestData, [
                    'title' => 'required',
                   // 'name' => 'required',
                    'resource_category' => 'required',
                    'format' => 'required',
                    //'image_upload' => 'mimes:jpeg,png,jpg',
                   
            ]);
        }
        if($requestData['format'] == 'Video' && $requestData['url'] != ''){
            if (strpos($requestData['url'],'youtube') == false) {
               return redirect()->back()->withErrors($errors->errors());
            } 
        }
        
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->input());
        } else {
           
            $resourceImageName = '';
            $bigresourceImageName = '';
            
            $resourceImageFileName = $requestData["resource_image_name"];
            if(isset($resourceImageFileName) && !empty($resourceImageFileName)){
                $resourceImageFileName = $requestData["resource_image_name"];
            } else {
                $resourceImageFileName = '';
            }
            if(isset($requestData['image_upload']) && !empty($requestData['image_upload'])){
                
                $resourceImageFileName = $requestData["resource_image_name"];

                $bigresourceImage = $request->image_upload;
                $bigresourceImageName = time().'.'.$bigresourceImage->getClientOriginalName();
                

                //$img->save($destinationPath.'/'.$resourceImageName);
        
                $destinationPath = public_path('/front/images/resource');
                $bigresourceImage->move($destinationPath, $bigresourceImageName);

            }
        
           
            $resourceVideoFileName = $requestData["resource_video_path"];


            if(isset($requestData["resourceId"]) && !empty($requestData["resourceId"])){
               $resource = Resource::find($requestData["resourceId"]);
                $format_name = !empty($resourceImageName) ? $resourceImageName : $resource->format_name;
                $image_name = !empty($resourceImageFileName) ? $resourceImageFileName : $resource->image_name;
                if(isset($resourceImageFileName) && !empty($resourceImageFileName)){
                    $bigresourceImageName = !empty($bigresourceImageName) ? $bigresourceImageName : $resource->big_image_name;
                } else {
                    $bigresourceImageName = $resource->big_image_name;
                }

                if($requestData['format'] == 'Video'){
                    if($requestData['videoType'] == '1'){
                        $resource = DB::update("update resource set title='".addslashes($requestData['title'])."', subtitle='".addslashes($requestData['subtitle'])."', category='".$requestData['resource_category']."', description='".$requestData['message']."', format='".$requestData['format']."', format_name='".$requestData['url']."', image_name='".$image_name."', type='".$requestData['videoType']."' where id='".$requestData["resourceId"]."'");
                    }
                    else if($requestData['videoType'] == '2'){
                        $resourceVideoFileName = !empty($resourceVideoFileName) ? $resourceVideoFileName : $resource->format_name;
                        $resource = DB::update("update resource set title='".addslashes($requestData['title'])."', subtitle='".addslashes($requestData['subtitle'])."', category='".$requestData['resource_category']."', description='".$requestData['message']."', format='".$requestData['format']."', format_name='".$resourceVideoFileName."', image_name='".$image_name."', type='".$requestData['videoType']."' where id='".$requestData["resourceId"]."'");  
                    }
                    
                    else {
                        $resource = DB::update('update resource set title="'.addslashes($requestData['title']).'", category="'.addslashes($requestData['resource_category']).'", description="'.$requestData['message'].'", format="'.$requestData['format'].'", type="'.$requestData['type'].'", format_name="'.$format_name.'" where id="'.$requestData["resourceId"].'"');

                    }
                } else {
                    $resource = DB::update("update resource set title='".addslashes($requestData['title'])."', subtitle='".addslashes($requestData['subtitle'])."', category='".$requestData['resource_category']."', description='".$requestData['message']."', format='".$requestData['format']."',keyword='".$requestData['keyTag']."', type='', format_name='', image_name='".$image_name."', big_image_name='".$bigresourceImageName."' where id='".$requestData["resourceId"]."'");
                }
                

                $msg = "Resource Updated successfully.";
                Session::flash('message', $msg); 
                return redirect('trainer/resource');
               
            }else{
                

                if($requestData['format'] == 'Video'){
                    
                    if($requestData['url']){
                        $resource = DB::table('resource')->insert([
                            'trainer_id' => Auth::guard('front_auth')->user()->id,
                            'name' => Auth::guard('front_auth')->user()->business_name,
                            'title' => $requestData['title'],
                            'subtitle' => $requestData['subtitle'],
                            'category' => $requestData['resource_category'],
                            'description' => $requestData['message'],
                            'format' => $requestData['format'],
                            'keyword' => $requestData['keyTag'],
                            //'tags' => $requestData['tag'],
                            'format_name' => $requestData['url'],
                            'image_name' => $resourceImageFileName,
                            'type' => $requestData['videoType'],
                            'status' => '1',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    } 
                    else {
                        $resource = DB::table('resource')->insert([
                            'trainer_id' => Auth::guard('front_auth')->user()->id,
                            'name' => Auth::guard('front_auth')->user()->business_name,
                            'title' => $requestData['title'],
                            'subtitle' => $requestData['subtitle'],
                            'category' => $requestData['resource_category'],
                            'description' => $requestData['message'],
                            'format' => $requestData['format'],
                            'keyword' => $requestData['keyTag'],
                            //'tags' => $requestData['tag'],
                            'format_name' => $resourceVideoFileName,
                            'image_name' => $resourceImageFileName,
                            'type' => $requestData['videoType'],
                            'status' => '1',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }

                } else {
                    $resource = DB::table('resource')->insert([
                        'trainer_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->business_name,
                        'title' => $requestData['title'],
                        'subtitle' => $requestData['subtitle'],
                        'category' => $requestData['resource_category'],
                        'description' => $requestData['message'],
                        'format' => $requestData['format'],
                        'keyword' => $requestData['keyTag'],
                        //'tags' => $requestData['tag'],
                        //'format_name' => $resourceImageName,
                        'image_name' => $resourceImageFileName,
                        'big_image_name' => $bigresourceImageName,
                        'status' => '1',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
                   
                $msg = "Resource created successfully.";
                Session::flash('message', $msg); 
                return redirect('trainer/resource');
            
            }
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $resource = DB::select('select * from resource where id="'.base64_decode($id).'"');
        
        return view('front.trainer.resource.resource-detail',['resource' => $resource]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$resources_category = DB::select('select * from resource_category where status=1 and trainer_id="'.Auth::guard('front_auth')->user()->id.'" order by name asc');
        $resources_category = Services::where('status','active')->orderby('name','asc')->get();
        $resource = Resource::findOrFail(base64_decode($id));
        $formats = ["Article"=>"Article","Video"=>"Video"];
        return view('front.trainer.resource.add-resource',["formats" => $formats, 'trainerResource' => $resource, 'resources_category' => $resources_category]);

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
        $resource = Resource::findOrFail(base64_decode($id));
        //dd($isOrderAvailable);
        
        if($resource->delete()){
            Session::flash('message', "Resource deleted successfully!");
            return redirect()->back();
        }
        
        
    }


    public function resource_category(Request $request)
    {
        
        $trainerId = Auth::guard('front_auth')->user()->id;
        $resource_category = DB::select('select * from resource_category where trainer_id="'.$trainerId.'" and status = 1 order by name asc');
        
        return view('front.trainer.resource.resource_category',["resource_category" => $resource_category]);
    }

   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function category_create()
    {
       
        return view('front.trainer.resource.add-resource-category');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function category_store(Request $request)
    {
        $requestData = $request->all();
       
        $errors = Validator::make($requestData, [
                    'name' => 'required',
                   
        ]);
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
           
            
           

            if(isset($requestData["categoryId"]) && !empty($requestData["categoryId"])){
                $resourceCategory = ResourceCategory::findOrFail($requestData["categoryId"]);
                
               if($resourceCategory->name == $requestData['name']){
                    $resource_category = DB::update('update resource_category set name="'.$requestData['name'].'" where id="'.$requestData["categoryId"].'"');

                    $msg = "Resource Category Updated successfully.";
                    Session::flash('message', $msg); 
                    return redirect('trainer/resource-category');
               } else {
                    $resourceCategory = DB::select('select count(*) as count_category from resource_category where trainer_id="'.Auth::guard('front_auth')->user()->id.'" and status = 1 and name="'.$requestData['name'].'"');

                    if($resourceCategory[0]->count_category == 0){
                        $resource_category = DB::update('update resource_category set name="'.$requestData['name'].'" where id="'.$requestData["categoryId"].'"');

                        $msg = "Resource Category Updated successfully.";
                        Session::flash('message', $msg); 
                        return redirect('trainer/resource-category');
                    } else {
                        $msg = "Resource Category already exists.";
                        Session::flash('error', $msg); 
                        return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
                    }

               }
                
               
            }else{
                
               $resourceCategory = DB::select('select count(*) as count_category from resource_category where trainer_id="'.Auth::guard('front_auth')->user()->id.'" and status = 1 and name="'.$requestData['name'].'"');

                if($resourceCategory[0]->count_category == 0){
                    $resource_category = DB::table('resource_category')->insert([
                        'trainer_id' => Auth::guard('front_auth')->user()->id,
                        'name' => $requestData['name'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                       
                    $msg = "Resource Category created successfully.";
                    Session::flash('message', $msg); 
                    return redirect('trainer/resource-category');
                } else {
                    $msg = "Resource Category already exists.";
                    Session::flash('error', $msg); 
                    return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
                }
                
            
            }
            
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function category_edit($id)
    {
        $resource_category = ResourceCategory::findOrFail(base64_decode($id));
        return view('front.trainer.resource.add-resource-category',['resource_category' => $resource_category]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function category_destroy($id)
    {
        //$resource_category = ResourceCategory::findOrFail(base64_decode($id));
       $resource_category = DB::update('update resource_category set status=2 where id="'.base64_decode($id).'"');
        //if($resource_category->delete()){
            Session::flash('message', "Resource Category deleted successfully!");
            return redirect()->back();
        //}
        
        
    }

    /*public function statusChange($id){
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
        
    }*/

    public function ResourceLibrarys(Request $request) {
        $user = \Auth::guard('front_auth')->user();
        
        if(!$user)
        {
            $baseurl = URL::to('/resource-library');
            session()->put('resource-url',$baseurl);
            return redirect('login');
        }
    }
    public function ResourceLibrary(Request $request) {
        $user = \Auth::guard('front_auth')->user();

        if($user)
        {
            session()->put('resource-url',false);
        }
            //$resourceImages = Resource::all();
            $like_count = Resource::max('like_count');
            $comment_count = Resource::max('comment_count');
            if($comment_count >$like_count ){
                $resourceImages = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')
                ->join('front_users','front_users.id','=','resource.trainer_id')
                ->where('front_users.status','active')
                //->where('front_users.is_subscription','0')
                //->orwhere('front_users.is_payment','1')
                ->where(function($query) {
                    $query->where('front_users.is_subscription', '0')
                          ->orwhere('front_users.is_payment', '1');
                })
                ->orderBy('comment_count', 'DESC')
                ->orderBy('like_count', 'DESC')->get();
            } else {
                $resourceImages = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')
                ->join('front_users','front_users.id','=','resource.trainer_id')
                ->where('front_users.status','active')
               /* ->where('front_users.is_subscription','0')
                ->orwhere('front_users.is_payment','1')*/
                ->where(function($query) {
                    $query->where('front_users.is_subscription', '0')
                          ->orwhere('front_users.is_payment', '1');
                })
                ->orderBy('comment_count', 'DESC')
                ->orderBy('like_count', 'DESC')->get();
            }
            $ResourceCategory = Services::where('status','active')->orderby('name','asc')->get();
            $Image_count = DB::table('resource')->where('format', '=', 'image')->count();
            $Video_count = DB::table('resource')->where('format', '=', 'Video')->count();
            $Article_count = DB::table('resource')->where('format', '=', 'Article')->count();
            $EBook_count = DB::table('resource')->where('format', '=', 'EBook')->count();

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
           session()->put('page', 1);
           $metaTitle = "Resource-Library";
           return view('front.resource-library', ["metaTitle" => $metaTitle, "resourceImages" => $resourceImages, "ResourceCategory" => $ResourceCategory, "Image_count" => $Image_count, "Video_count" => $Video_count, "Article_count" => $Article_count, "EBook_count" => $EBook_count, "customerId" => $customerId, "keyword" => $keyword, "category" => $category, "format" => $format]);
        
        }

    public function ResourceLibrarySearch($resource_id) {

            //$resourceImages = DB::table('resource')->where('id', '=', $resource_id)->get();
            $like_count = Resource::max('like_count');
            $comment_count = Resource::max('comment_count');
            if($comment_count >$like_count ){
                $resourceImages = DB::Table('resource')->where('id', '=', $resource_id)->orderBy('comment_count', 'DESC')->orderBy('like_count', 'DESC')->get();
            } else {
                $resourceImages = DB::Table('resource')->where('id', '=', $resource_id)->orderBy('like_count', 'DESC')->orderBy('comment_count', 'DESC')->get();
            }
            $ResourceCategory = Services::where('status','active')->orderby('name','asc')->get();

            $Image_count = DB::table('resource')->where('format', '=', 'image')->where('id', '=', $resource_id)->count();
            $Video_count = DB::table('resource')->where('format', '=', 'Video')->where('id', '=', $resource_id)->count();
            $Article_count = DB::table('resource')->where('format', '=', 'Article')->where('id', '=', $resource_id)->count();
            $EBook_count = DB::table('resource')->where('format', '=', 'EBook')->where('id', '=', $resource_id)->count();

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

            return view('front.resource-library', ["resourceImages" => $resourceImages, "ResourceCategory" => $ResourceCategory, "Image_count" => $Image_count, "Video_count" => $Video_count, "Article_count" => $Article_count, "EBook_count" => $EBook_count, "customerId" => $customerId, "keyword" => $keyword, "category" => $category, "format" => $format]);
    }


    public function SearchResource(Request $request){
           $keyword = '';
           $category = '';
           $format = '';
           
        if($request->search == 1){

                    // $query = DB::Table('resource')->Where("trainer_id", $request->trainer_id)
                    $keyword = $request->keyword;
                    $category = $request->category;
                    $format = $request->format;

                    if (!empty($keyword) && !empty($category) && !empty($format)) {
                        
                        $like_count = Resource::max('like_count');
                        $comment_count = Resource::max('comment_count');
                        if($comment_count >$like_count ){
                            $searchResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')->join('front_users','front_users.id','=','resource.trainer_id')->where('front_users.status','active')->where(function($query) {
                                    $query->where('front_users.is_subscription', '0')
                                          ->orwhere('front_users.is_payment', '1');
                                })->Where('title', 'like', "%{$keyword}%")->Where(["category" => $category])->Where(["format" => $format])->orderBy('comment_count', 'DESC')->orderBy('like_count', 'DESC')->get();
                        } else {
                            $searchResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')->join('front_users','front_users.id','=','resource.trainer_id')->where('front_users.status','active')->where(function($query) {
                                    $query->where('front_users.is_subscription', '0')
                                          ->orwhere('front_users.is_payment', '1');
                                })->Where('title', 'like', "%{$keyword}%")->Where(["category" => $category])->Where(["format" => $format])->orderBy('like_count', 'DESC')->orderBy('comment_count', 'DESC')->get();
                        }
                        if($format == 'Image'){
                            $Image_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->Where(["category" => $category])->where('format', '=', 'Image')->count();
                            $Video_count = 0;
                            $Article_count = 0;
                            $EBook_count = 0;
                        } else if($format == 'Video'){
                            $Image_count = 0;
                            $Video_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->Where(["category" => $category])->where('format', '=', 'Video')->count();
                            $EBook_count = 0;
                            $Article_count = 0;
                        } else if($format == 'Article'){
                            $Image_count = 0;
                            $Video_count = 0;
                            $EBook_count = 0;
                            $Article_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->Where(["category" => $category])->where('format', '=', 'Article')->count();
                        } else if($format == 'EBook'){
                            $Image_count = 0;
                            $Video_count = 0;
                            $Article_count = 0;
                            $EBook_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->Where(["category" => $category])->where('format', '=', 'EBook')->count();
                        }
                    }
                    else if (!empty($keyword) && !empty($category) && empty($format)) {
                        $like_count = Resource::max('like_count');
                        $comment_count = Resource::max('comment_count');
                        if($comment_count >$like_count ){
                            $searchResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')->join('front_users','front_users.id','=','resource.trainer_id')->where('front_users.status','active')->where(function($query) {
                                    $query->where('front_users.is_subscription', '0')
                                          ->orwhere('front_users.is_payment', '1');
                                })->Where('title', 'like', "%{$keyword}%")->Where(["category" => $category])->orderBy('comment_count', 'DESC')->orderBy('like_count', 'DESC')->get();
                        } else {
                            $searchResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')->join('front_users','front_users.id','=','resource.trainer_id')->where('front_users.status','active')->where(function($query) {
                                    $query->where('front_users.is_subscription', '0')
                                          ->orwhere('front_users.is_payment', '1');
                                })->Where('title', 'like', "%{$keyword}%")->Where(["category" => $category])->orderBy('like_count', 'DESC')->orderBy('comment_count', 'DESC')->get();
                        }
                        $Image_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->Where(["category" => $category])->where('format', '=', 'Image')->count();
                        $Video_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->Where(["category" => $category])->where('format', '=', 'Video')->count();
                        $Article_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->Where(["category" => $category])->where('format', '=', 'Article')->count();
                        $EBook_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->Where(["category" => $category])->where('format', '=', 'EBook')->count();
                    }
                    else if (!empty($keyword) && empty($category) && !empty($format)) {
                        $like_count = Resource::max('like_count');
                        $comment_count = Resource::max('comment_count');
                        if($comment_count >$like_count ){
                            $searchResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')->join('front_users','front_users.id','=','resource.trainer_id')->where('front_users.status','active')->where(function($query) {
                                    $query->where('front_users.is_subscription', '0')
                                          ->orwhere('front_users.is_payment', '1');
                                })->Where('title', 'like', "%{$keyword}%")->Where(["format" => $format])->orderBy('comment_count', 'DESC')->orderBy('like_count', 'DESC')->get();
                        } else {
                            $searchResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')->join('front_users','front_users.id','=','resource.trainer_id')->where('front_users.status','active')->where(function($query) {
                                    $query->where('front_users.is_subscription', '0')
                                          ->orwhere('front_users.is_payment', '1');
                                })->Where('title', 'like', "%{$keyword}%")->Where(["format" => $format])->orderBy('like_count', 'DESC')->orderBy('comment_count', 'DESC')->get();
                        }
                        if($format == 'Image'){
                            $Image_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->where('format', '=', 'Image')->count();
                            $Video_count = 0;
                            $Article_count = 0;
                            $EBook_count = 0;
                        } else if($format == 'Video'){
                            $Image_count = 0;
                            $Video_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->where('format', '=', 'Video')->count();
                            $Article_count = 0;
                            $EBook_count = 0;
                        } else if($format == 'Article'){
                            $Image_count = 0;
                            $Video_count = 0;
                            $Article_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->where('format', '=', 'Article')->count();
                            $EBook_count = 0;
                        } else if($format == 'EBook'){
                            $Image_count = 0;
                            $Video_count = 0;
                            $Article_count = 0;
                            $EBook_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->where('format', '=', 'EBook')->count();
                        }
                    }
                    else if (empty($keyword) && !empty($category) && !empty($format)) {
                        $searchResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')->join('front_users','front_users.id','=','resource.trainer_id')->where('front_users.status','active')->where(function($query) {
                                $query->where('front_users.is_subscription', '0')
                                      ->orwhere('front_users.is_payment', '1');
                            })->Where(["category" => $category])->Where(["format" => $format])->get();
                        if($format == 'Image'){
                            $Image_count = DB::table('resource')->Where(["category" => $category])->where('format', '=', 'Image')->count();
                            $Video_count = 0;
                            $Article_count = 0;
                            $EBook_count = 0;
                        } else if($format == 'Video'){
                            $Image_count = 0;
                            $Video_count = DB::table('resource')->Where(["category" => $category])->where('format', '=', 'Video')->count();
                            $Article_count = 0;
                            $EBook_count = 0;
                        } else if($format == 'Article'){
                            $Image_count = 0;
                            $Video_count = 0;
                            $Article_count = DB::table('resource')->Where(["category" => $category])->where('format', '=', 'Article')->count();
                            $EBook_count = 0;
                        } else if($format == 'EBook'){
                            $Image_count = 0;
                            $Video_count = 0;
                            $Article_count = 0;
                            $EBook_count = DB::table('resource')->Where(["category" => $category])->where('format', '=', 'EBook')->count();
                        }
                    }
                    else if(!empty($keyword)){
                        $like_count = Resource::max('like_count');
                        $comment_count = Resource::max('comment_count');
                        if($comment_count >$like_count ){
                            $searchResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')->join('front_users','front_users.id','=','resource.trainer_id')->where('front_users.status','active')->where(function($query) {
                                    $query->where('front_users.is_subscription', '0')
                                          ->orwhere('front_users.is_payment', '1');
                                })->Where('title', 'like', "%{$keyword}%")->orderBy('comment_count', 'DESC')->orderBy('like_count', 'DESC')->get();
                        } else {
                            $searchResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')->join('front_users','front_users.id','=','resource.trainer_id')->where('front_users.status','active')->where(function($query) {
                                    $query->where('front_users.is_subscription', '0')
                                          ->orwhere('front_users.is_payment', '1');
                                })->Where('title', 'like', "%{$keyword}%")->orderBy('like_count', 'DESC')->orderBy('comment_count', 'DESC')->get();
                        }

                        $Image_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->where('format', '=', 'Image')->count();
                        $Video_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->where('format', '=', 'Video')->count();
                        $Article_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->where('format', '=', 'Article')->count();
                        $EBook_count = DB::table('resource')->Where('title', 'like', "%{$keyword}%")->where('format', '=', 'EBook')->count();
                    }
                     else if(!empty($format)){
                        $searchResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')->join('front_users','front_users.id','=','resource.trainer_id')->where('front_users.status','active')->where(function($query) {
                                $query->where('front_users.is_subscription', '0')
                                      ->orwhere('front_users.is_payment', '1');
                            })->Where(["format" => $format])->get();
                        if($format == 'Image'){
                            $Image_count = DB::table('resource')->where('format', '=', 'Image')->count();
                            $Video_count = 0;
                            $Article_count = 0;
                            $EBook_count = 0;
                        } else if($format == 'Video'){
                            $Image_count = 0;
                            $Video_count = DB::table('resource')->where('format', '=', 'Video')->count();
                            $Article_count = 0;
                            $EBook_count = 0;
                        } else if($format == 'Article'){
                            $Image_count = 0;
                            $Video_count = 0;
                            $Article_count = DB::table('resource')->where('format', '=', 'Article')->count();
                            $EBook_count = 0;
                        } else if($format == 'EBook'){
                            $Image_count = 0;
                            $Video_count = 0;
                            $Article_count = 0;
                            $EBook_count = DB::table('resource')->where('format', '=', 'EBook')->count();
                        }
                    } else if(!empty($category)){
                        $searchResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')->join('front_users','front_users.id','=','resource.trainer_id')->where('front_users.status','active')->where(function($query) {
                                $query->where('front_users.is_subscription', '0')
                                      ->orwhere('front_users.is_payment', '1');
                            })->Where(["category" => $category])->get();
                        $Image_count = DB::table('resource')->Where(["category" => $category])->where('format', '=', 'Image')->count();
                        $Video_count = DB::table('resource')->Where(["category" => $category])->where('format', '=', 'Video')->count();
                        $Article_count = DB::table('resource')->Where(["category" => $category])->where('format', '=', 'Article')->count();
                        $EBook_count = DB::table('resource')->Where(["category" => $category])->where('format', '=', 'EBook')->count();
                    }else{
                        //$searchResources = DB::Table('resource')->get();
                        $like_count = Resource::max('like_count');
                        $comment_count = Resource::max('comment_count');
                        if($comment_count >$like_count ){
                            $searchResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')->join('front_users','front_users.id','=','resource.trainer_id')->where('front_users.status','active')->where(function($query) {
                                    $query->where('front_users.is_subscription', '0')
                                          ->orwhere('front_users.is_payment', '1');
                                })->orderBy('comment_count', 'DESC')->orderBy('like_count', 'DESC')->get();
                        } else {
                            $searchResources = DB::Table('resource')->select('resource.*', 'front_users.is_subscription', 'front_users.is_payment')->join('front_users','front_users.id','=','resource.trainer_id')->where('front_users.status','active')->where(function($query) {
                                    $query->where('front_users.is_subscription', '0')
                                          ->orwhere('front_users.is_payment', '1');
                                })->orderBy('like_count', 'DESC')->orderBy('comment_count', 'DESC')->get();
                        }
                        $Image_count = DB::table('resource')->where('format', '=', 'Image')->count();
                        $Video_count = DB::table('resource')->where('format', '=', 'Video')->count();
                        $Article_count = DB::table('resource')->where('format', '=', 'Article')->count();
                        $EBook_count = DB::table('resource')->where('format', '=', 'EBook')->count();
                    }
    }

        
    
    $ResourceCategory = Services::where('status','active')->orderby('name','asc')->get();

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

                       

    return view('front.resource-library', ["searchResources" => $searchResources, "ResourceCategory" => $ResourceCategory, "Image_count" => $Image_count, "Video_count" => $Video_count, "Article_count" => $Article_count, "EBook_count" => $EBook_count, "customerId" => $customerId, "keyword" => $keyword, "category" => $category, "format" => $format]);

}

    public function ResourceLike($resourceId){
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
            $likes = 1;
        } else {
            if($resourceLike->likes == 0 && $resourceLike->dislike == 0){
                $resourceLikeUpdate = DB::update('update resource_count set likes="1", name="'.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.'" where id="'.$resourceLike->id.'"');
                $likes = 1;
            } else if($resourceLike->likes == 0 && $resourceLike->dislike == 1 ){
                $resourceLikeUpdate = DB::update('update resource_count set likes=1, dislike=0, name="'.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.'" where id="'.$resourceLike->id.'"');
                $likes = 0;
            } else if($resourceLike->likes == 1 && $resourceLike->dislike == 0){
                $resourceLikeUpdate = DB::update('update resource_count set likes=0, name="'.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.'" where id="'.$resourceLike->id.'"');
                $likes = 0;
            }
        }
        $resource_like_count = DB::table('resource_count')->where(["resource_id" => $resourceId, "likes" => 1])->count();
        $resource_dislike_count = DB::table('resource_count')->where(["resource_id" => $resourceId, "dislike" => 1])->count();
        $resource_like_name = DB::select('select name, user_id, created_at from resource_count where resource_id="'.$resourceId.'" and likes = 1 order by id desc');
        $resource_dislike_name = DB::select('select name, user_id, created_at from resource_count where resource_id="'.$resourceId.'" and dislike = 1 order by id desc');
        $get_resource_like_dislike = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resourceId])->first();
        $like_names ='';
        foreach ($resource_like_name as $like_name) {
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
        foreach ($resource_dislike_name as $dislike_name) {
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
        $likes = $get_resource_like_dislike->likes;
        $dislike = $get_resource_like_dislike->dislike;

        $resourceLikeCountUpdate = DB::update('update resource set like_count="'.$resource_like_count.'" where id="'.$resourceId.'"');

        return response()->json(['like'=>$likes, 'dislike'=>$dislike, 'resource_like_count'=>$resource_like_count, 'resource_dislike_count'=>$resource_dislike_count, 'like_name'=>json_decode($like_name), 'dislike_name'=>json_decode($dislike_name)]);
        
    }

    public function ResourceDisLike($resourceId){
        
        $customerId = Auth::guard('front_auth')->user()->id;
        $resourceDisLikeCount = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resourceId])->count();
        $resourceDisLike = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resourceId])->first();
        
        if($resourceDisLikeCount == 0){
            $resourceDisLikeInsert = DB::table('resource_count')->insert([
                        'resource_id' => $resourceId,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'dislike' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
            $dislike = 1;
        } else {
            if($resourceDisLike->likes == 0 && $resourceDisLike->dislike == 0){
                $resourceLikeUpdate = DB::update('update resource_count set dislike="1", name="'.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.'" where id="'.$resourceDisLike->id.'"');
                $dislike = 1;
            } else if($resourceDisLike->likes == 1 && $resourceDisLike->dislike == 0){
                $resourceLikeUpdate = DB::update('update resource_count set likes=0, dislike=1, name="'.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.'" where id="'.$resourceDisLike->id.'"');
                $dislike = 0;
            } else if($resourceDisLike->likes == 0 && $resourceDisLike->dislike == 1){
                $resourceLikeUpdate = DB::update('update resource_count set dislike=0, name="'.Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name.'" where id="'.$resourceDisLike->id.'"');
                $dislike = 0;
            }
        }
        $resource_like_count = DB::table('resource_count')->where(["resource_id" => $resourceId, "likes" => 1])->count();
        $resource_dislike_count = DB::table('resource_count')->where(["resource_id" => $resourceId, "dislike" => 1])->count();
        $resource_dislike_name = DB::select('select name, user_id, created_at from resource_count where resource_id="'.$resourceId.'" and dislike = 1 order by id desc');
        $resource_like_name = DB::select('select name, user_id, created_at from resource_count where resource_id="'.$resourceId.'" and likes = 1 order by id desc');
        $get_resource_like_dislike = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resourceId])->first();
        $dislike_names ='';
        foreach ($resource_dislike_name as $dislike_name) {
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
        foreach ($resource_like_name as $like_name) {
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
        $likes = $get_resource_like_dislike->likes;
        $dislike = $get_resource_like_dislike->dislike;
        $resourceLikeCountUpdate = DB::update('update resource set like_count="'.$resource_like_count.'" where id="'.$resourceId.'"');

        return response()->json(['dislike'=>$dislike, 'like'=>$likes, 'resource_like_count'=>$resource_like_count, 'resource_dislike_count'=>$resource_dislike_count, 'dislike_name'=>json_decode($dislike_name), 'like_name'=>json_decode($like_name)]);
        
    }

    

    public function ResourceSave($resourceId){
        $customerId = Auth::guard('front_auth')->user()->id;
        $resourceresourceSaveCount = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resourceId])->count();
        $resourceSave = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resourceId])->first();
        
        if($resourceresourceSaveCount == 0){
            $resourceSave = DB::table('resource_count')->insert([
                        'resource_id' => $resourceId,
                        'user_id' => Auth::guard('front_auth')->user()->id,
                        'name' => Auth::guard('front_auth')->user()->first_name.' '.Auth::guard('front_auth')->user()->last_name,
                        'saved' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
            $saved = 1;
        } else {
            if($resourceSave->saved == 0){
                $resourceSaveUpdate = DB::update('update resource_count set saved="1" where id="'.$resourceSave->id.'"');
                $saved = 1;
            } else if($resourceSave->saved == 1 ){
                $resourceSaveUpdate = DB::update('update resource_count set saved=0 where id="'.$resourceSave->id.'"');
                $saved = 0;
            } 
        }

        return response()->json(['saved'=>$saved]);
        
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
    $comment_mail = new PHPMailer;
    $comment_mail->IsSMTP();
    $comment_mail->SMTPAuth = true;
    $comment_mail->SMTPSecure = env('MAIL_SECURE');
    $comment_mail->Host = env('MAIL_HOST');
    $comment_mail->Port = env('MAIL_PORT');
    $comment_mail->Username = env('MAIL_USERNAME');
    $comment_mail->Password = env('MAIL_PASSWORD');
    $comment_mail->SetFrom(env('MAIL_FROM'), 'Training Block');
    $comment_mail->Subject = "Training Block - Resource Comments";
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
        <p>Someone just left a comment on one of your resources! Check it out here:</p><br /> </td>
    </tr>
    <tr>
        <td style="padding-bottom:15px;"> 
            <a href="'.$request->resource_url.'" style="background: #00ab91;color: #fff;padding: 10px;border-radius: 5px;text-decoration: none;">View Comment</a>
        </td>
    </tr>
    <tr>
    <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
    <p></p>
    <span style="margin-top:5px;">The Training Block Team</span>
    </td>
    </tr>
    </tbody></table></body></html>');
    $comment_mail->AddAddress($trainer_email , 'Training Block');

    if (!$comment_mail->send()) {
        echo 'Mailer Error: ' . $comment_mail->ErrorInfo;
    } else {
        echo 'Message sent!';
    }

        return redirect('resource-library');
        
    }


    public function ResourceDetailComment(Request $request){
        
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
    $comment_mail = new PHPMailer;
    $comment_mail->IsSMTP();
    $comment_mail->SMTPAuth = true;
    $comment_mail->SMTPSecure = env('MAIL_SECURE');
    $comment_mail->Host = env('MAIL_HOST');
    $comment_mail->Port = env('MAIL_PORT');
    $comment_mail->Username = env('MAIL_USERNAME');
    $comment_mail->Password = env('MAIL_PASSWORD');
    $comment_mail->SetFrom(env('MAIL_FROM'), 'Training Block');
    $comment_mail->Subject = "Training Block - Resource Comments";
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
        <p>Someone just left a comment on one of your resources! Check it out here:</p><br /> </td>
    </tr>
    <tr>
        <td style="padding-bottom:15px;"> 
            <a href="'.$request->resource_url.'" style="background: #00ab91;color: #fff;padding: 10px;border-radius: 5px;text-decoration: none;">View Comment</a>
        </td>
    </tr>
    <tr>
    <td style="background:#555;color:#fff;padding:15px;"> <span>Best,</span><br>
    <p></p>
    <span style="margin-top:5px;">The Training Block Team</span>
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

    public function ResourceDetailss($resourceId){
        $user = \Auth::guard('front_auth')->user();
        
        if(!$user)
        {
            $baseurl = URL::to('/resource-details/'.$resourceId);
            
            session()->put('resource-detail-url',$baseurl);
            return redirect('login');
        }
    }

    public function ResourceDetails($resourceId){
        $user = \Auth::guard('front_auth')->user();

        if($user)
        {
            session()->put('resource-detail-url',false);
        }
        $resourceId = base64_decode($resourceId);
         $resourceDetails = DB::table('resource')->where(["id" => $resourceId])->get();
         session()->put('page', 1);
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

                       
if (!empty( $resourceDetails[0]->title )) {
    $metaTitle = str_limit(strip_tags($resourceDetails[0]->title), $limit = 45);
}else{
    $metaTitle = "";
}

if (!empty( $resourceDetails[0]->description )) {
    $metaDescription = str_limit(strip_tags($resourceDetails[0]->description), $limit = 160, $end = '...');
}else{
    $metaDescription = "";
}

if (!empty( $resourceDetails[0]->name )) {
    $metaPublisher = $resourceDetails[0]->name;
}else{
    $metaPublisher = "";
}

if (!empty( $resourceDetails[0]->created_at )) {
    $metaArticleModifiedTime = $resourceDetails[0]->created_at;
}else{
    $metaArticleModifiedTime = "";
}

if (!empty( $resourceDetails[0]->title )) {
    $metaOgTitle = $resourceDetails[0]->title;
}else{
    $metaOgTitle = "";
}

if (!empty( $resourceDetails[0]->description )) {
    $metaOgDescription = str_limit(strip_tags($resourceDetails[0]->description), $limit = 160, $end = '...');
}else{
    $metaOgDescription = "";
}

if (!empty( $resourceDetails[0]->keyword )) {
    $metaKeywords = $resourceDetails[0]->keyword;
}else{
    $metaKeywords = "";
}

if (!empty( $resourceDetails[0]->image_name )) {
    $metaOgImage = $resourceDetails[0]->image_name;
}else{
    $metaOgImage = "";
}

if (!empty( $resourceDetails[0]->format_name )) {
    $metaOgVideo = $resourceDetails[0]->format_name;
}else{
    $metaOgVideo = "";
}

if (!empty( $resourceDetails[0]->description )) {
    $description = str_limit(strip_tags($resourceDetails[0]->description), $limit = 160, $end = '...');
}else{
    $description = "";
}

if (!empty( $resourceDetails[0]->name )) {
    $name = $resourceDetails[0]->name;
}else{
    $name = "";
}

if (!empty( $resourceDetails[0]->created_at )) {
    $created_at = $resourceDetails[0]->created_at;
}else{
    $created_at = "";
}

if (!empty( $resourceDetails[0]->format_name )) {
    $format_name = $resourceDetails[0]->format_name;
}else{
    $format_name = "";
}

if (!empty( $resourceDetails[0]->image_name )) {
    $image_name = $resourceDetails[0]->image_name;
}else{
    $image_name = "";
}

if (!empty( $resourceDetails[0]->image_name )) {
    $metaTwitterCard = $resourceDetails[0]->image_name;
}else{
    $metaTwitterCard = "";
}

if (!empty( $resourceDetails[0]->title )) {
    $metaTwitterTitle = $resourceDetails[0]->title;
}else{
    $metaTwitterTitle = "";
}

if (!empty( $resourceDetails[0]->description )) {
    $metaTwitterDescription = str_limit(strip_tags($resourceDetails[0]->description), $limit = 160, $end = '...');
}else{
    $metaTwitterDescription = "";
}

if (!empty( $resourceDetails[0]->image_name )) {
    $metaTwitterImage = $resourceDetails[0]->image_name;
}else{
    $metaTwitterImage = "";
}
           
    $metaOgUrl = URL::current();
  
      if ($resourceDetails[0]->keyword != null) {
          $metaKeywords = $resourceDetails[0]->keyword;
      }else{
          $metaKeywords = $resourceDetails[0]->title;
      }
             
      return view('front.resource-detail',compact('metaTitle', 'metaDescription', 'metaKeywords', 'metaPublisher', 'metaArticleModifiedTime', 'metaOgTitle',
      'metaOgDescription', 'metaOgImage', 'description', 'format_name', 'name', 'created_at', 'metaOgVideo','metaOgUrl', 'image_name', 'metaTwitterCard', 'metaTwitterTitle', 'metaTwitterDescription', 'metaTwitterImage'),  ["resourceDetails" => $resourceDetails, "customerId" => $customerId]);
 }

    public function Subcribes($email){
        
        $resourceComments = DB::table('subscribe')->insert([
                'email' => $email,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        $saved = 1;

        if($saved = 1){
            try{
            if(Newsletter::isSubscribed($email)){
                return redirect()->back()->with('error', 'Email Already subscribed');
            }else{
                Newsletter::subscribe($email);
                return redirect()->back()->with('success', 'Email subscribed');
            }
        }catch(Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
        }

         $mail = new PHPMailer;


    //Server settings                   // Enable verbose debug output
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = env('MAIL_SECURE');
    $mail->Host = env('MAIL_HOST');
    $mail->Port = env('MAIL_PORT');
    $mail->Username = env('MAIL_USERNAME');
    $mail->Password = env('MAIL_PASSWORD');
    $mail->SetFrom(env('MAIL_FROM'), 'Training Block Subscription');
    $mail->Subject = "Training Block Subscription";
    $mail->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
<tbody><tr style="
    background: #555;
">
    <td style="padding:10px;border-bottom:solid 1px #555;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
</tr>
<tr>
    <td style="padding-top:20px;"> <h4>Dear Admin,</h4><p>We had received Newsletter subscription request from below email address.</p> </td>
</tr>

<tr>
    <td style="padding-bottom:15px;"> 
        <h4 style="color:#063376;text-align:left;padding-left: 10px;margin: 0;">Email :</h4>
        <label style="padding-left: 35px;margin-top: 5px;">'.$email.'</label> 
    </td>
</tr>
<!-- <tr>
    <td style="text-align:center;padding-bottom:15px;padding-top: 10px;"> <button style="background:#baa762;border:0px; padding:10px 15px;color:#fff; margin:0 auto;">Go to Back</button> 
    </td>
</tr> -->
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
    $mail->AddAddress('support@traningblockusa.com', 'Training Block');

    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message sent!';
    }
        
    }

    public function SubcribesPost(Request $request){
       // echo '<pre>';print_r($request->subscribe_email);exit();

        $resourceComments = DB::table('subscribe')->insert([
                'email' => $request->subscribe_email,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        $saved = 1;

         $mail = new PHPMailer;


        //Server settings                   // Enable verbose debug output
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = env('MAIL_SECURE');
        $mail->Host = env('MAIL_HOST');
        $mail->Port = env('MAIL_PORT');
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->SetFrom(env('MAIL_FROM'), 'Training Block Subscription');
        $mail->Subject = "Training Block Subscription";
        $mail->MsgHTML('<html><body><table style="border-collapse: collapse; background:#fff;border-top:7px #555 solid;width:500px;">
    <tbody><tr style="
        background: #555;
    ">
        <td style="padding:10px;border-bottom:solid 1px #555;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
    </tr>
    <tr>
        <td style="padding-top:20px;"> <h4>Dear Admin,</h4><p>We had received Newsletter subscription request from below email address.</p> </td>
    </tr>

    <tr>
        <td style="padding-bottom:15px;"> 
            <h4 style="color:#063376;text-align:left;padding-left: 10px;margin: 0;">Email :</h4>
            <label style="padding-left: 35px;margin-top: 5px;">'.$request->subscribe_email.'</label> 
        </td>
    </tr>
    <!-- <tr>
        <td style="text-align:center;padding-bottom:15px;padding-top: 10px;"> <button style="background:#baa762;border:0px; padding:10px 15px;color:#fff; margin:0 auto;">Go to Back</button> 
        </td>
    </tr> -->
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
        $mail->AddAddress('support@traningblockusa.com', 'Training Block');

        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message sent!';
        }
        
        //return '<script type="text/javascript">alert("hello!");</script>';
        return redirect()->back()->with('jsAlert', 'asassaas');
       
    }

    public function coupon()
    {
        $trainerId = Auth::guard('front_auth')->user()->id;
        $grtCoupon = DB::table('coupons')->where(["trainer_id" => $trainerId])->first();
        

       
       
        return view('front.trainer.add-coupon',["trainerId" => $trainerId, "grtCoupon" => $grtCoupon]);
    }

    public function update_coupon(Request $request)
    {
        $requestData = $request->all();
       
       
        $errors = Validator::make($requestData, [
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'coupon_code' => 'required',
                    //'unit' => 'required',
                    //'percentage' => 'required',
                   
        ]);
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
           
            $fromdate_str=explode('-',$requestData['start_date']);

            $fromdate = $fromdate_str[2].'-'.$fromdate_str[0].'-'.$fromdate_str[1];
            
            $todate_str=explode('-',$requestData['end_date']);

            $todate = $todate_str[2].'-'.$todate_str[0].'-'.$todate_str[1];

            if(isset($requestData["couponId"]) && !empty($requestData["couponId"])){
                $resource_category = DB::update('update coupons set coupon_code="'.$requestData['coupon_code'].'", unit="'.$requestData['unit'].'", percentage="'.$requestData['percentage'].'", fromdate="'.$fromdate.'", todate="'.$todate.'", updated_at="'.date('Y-m-d H:i:s').'" where id="'.$requestData["couponId"].'"');

                $msg = "Promo code Updated successfully.";
                Session::flash('message', $msg); 
                return redirect('trainer/promo-code');
               
                
               
            }else{
                
               $resource_category = DB::table('coupons')->insert([
                    'trainer_id' => Auth::guard('front_auth')->user()->id,
                    'coupon_code' => $requestData['coupon_code'],
                    'percentage' => $requestData['percentage'],
                    'unit' => $requestData['unit'],
                    'fromdate' => $fromdate,
                    'todate' => $todate,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                   
                $msg = "Promo code created successfully.";
                Session::flash('message', $msg); 
                return redirect('trainer/promo-code');
                
                
            
            }
            
        }
    }


    public function RequestStatusUpdate(Request $request){
        
        $customerId = Auth::guard('front_auth')->user()->id;
       
        if($request->comments !=''){
            $comments = $request->comments;
        } else {
            $comments = '';
        }

        $resourceCommentCountUpdate = DB::update('update order_request set status="'.$request->status.'", comments="'.$comments.'" where id="'.$request->request_id.'"');

        $getServiceDetails = DB::table('order_request')->where(["id" => $request->request_id])->first();

        $services = TrainerServices::where('id', $getServiceDetails->service_id)->first();
        $trainer = FrontUsers::where(["id" => $getServiceDetails->trainer_id])->first();
        $users = FrontUsers::where(["id" => $getServiceDetails->user_id])->first();
        
        if($request->status == '2' || $request->status == '3'){
            if($request->status == '2'){
                $status = 'Approved';

                $mail = new PHPMailer;

        
                //Server settings                   // Enable verbose debug output
                if($services->format == 'In person - Single Appointment' || $services->format == 'In person - Group Appointment' || $services->format == 'Virtual - Single Appointment' || $services->format == 'Virtual - Group Appointment'){

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
                    <td style="padding:10px;border-bottom:solid 1px #555;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
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
                        <td style="padding:10px;border-bottom:solid 1px #555;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
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
                <td style="padding:10px;border-bottom:solid 1px #555;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
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
                    <td style="padding:10px;border-bottom:solid 1px #555;"> <img src="https://trainingblockusa.com/public/images/logo.png"> </td>
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
        
    }
        

        return redirect()->back();
        
    }
}