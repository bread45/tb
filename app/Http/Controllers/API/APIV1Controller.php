<?php

namespace App\Http\Controllers\API;

use App\EventTypes;
use App\Http\Controllers\Controller;
use App\QuoteRequestConversation;
use App\QuoteRequestConversationMessage;
use App\QuoteRequests;
use App\QuoteRequestServices;
use App\QuoteRequestsStepValue;
use App\SupplierService;
use App\SupplierServiceCustomization;
use App\SupplierServiceEventType;
use App\SupplierServiceStepAnswers;
use App\QuoteRequestSupplier;
use App\RattingTypes;
use App\ReviewsMaster;
use App\ReviewsMasterBucket;
use App\ReviewRatings;
use App\User;
use App\UserBucket;
use App\UserBusinessDetails;
use App\QuoteRequestConversationBucket;
use App\Notification;
use App\Availability;
use App\SubscriptionPlan;
use App\UsersPlan;
use App\VerifyUser;
use App\Jobs\SendQuoteRequestSupplier;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Mail;
use Modules\Blogs\Entities\BlogCategories;
use Modules\Blogs\Entities\Blogs;
use Modules\Categories\Entities\Categories;
use Modules\CMSPages\Entities\CMSPages;
use Modules\Contactus\Entities\ContactUs;
use Modules\Faq\Entities\Faq;
use Modules\Services\Entities\Services;
use Modules\Sliders\Entities\SliderImage;
use Modules\Sliders\Entities\SliderManager;
use Modules\StepManage\Entities\StepManage;
use Modules\Testimonials\Entities\Testimonials;
use Modules\Users\Entities\FrontUsers;
use Validator;
use Illuminate\Support\Facades\Storage;
use Exception;

use App\Mail\VerifyMail;

use function GuzzleHttp\json_encode;

class APIV1Controller extends Controller
{

    private $apikey;
    protected $request;

    public function __construct(Request $request)
    {

        $this->apikey = Config::get('app.appkey');
        $this->request = $request;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = request(['email', 'password']);

        config()->set('auth.defaults.guard', 'front_users');
        \Config::set('auth.guards.api.provider', 'front_users');

        // dd($credentials);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = $request->user();
//        if (!$user->is_verfied) {
//            Auth::logout();
//            return response()->json([
//                'message' => 'Your email address is not verified.',
//            ], 401);
//            exit;
//        }

//        $additiondetails = UserBusinessDetails::where('supplier_id', $user->id)->first();
//        if (!empty($additiondetails) && !empty($additiondetails->profile_pic)) {
//            $additiondetails->profile_pic = url('sitebucket/userProfile') . '/' . $additiondetails->profile_pic;
//        }
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();

 
        $data = Auth::user();
//        $data['additionaldetails'] = $additiondetails;
//        $data['currentPlan'] = $currentPlan;
	$totalNotificationsBadge = Notification::where('to_user_id', Auth::user()->id)->where('is_read', '0')->count();
        return response()->json(['status' => 200, 'token' => $tokenResult->accessToken, 'data' => $data,'totalNotificationsBadge' => $totalNotificationsBadge]);
    }

    public function forgotPassword(Request $request)
    {
        $input = get_api_key($this->request);
        $rules = array(
            'email' => 'required|email',
//            'basepath' => 'required',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "msg" => $validator->errors()->first(), "data" => array());
        } else {
            $user = FrontUsers::where('email', $input["email"])->first();
            if (!empty($user)) {
                $remember_token = str_random(60);
                $userupdate = $user->update([
                    'rememberToken' => $remember_token,
                ]);
                if ($user) {
                    $emails = $user->email;
                    $emails_name = $user->first_name . ' ' . $user->last_name;
                    $subject = "Reset Password! [Oncaveat]";
                    $admin_email = "auto-reply@oncaveat.co.uk";
                    $admin_name = "Oncaveat";
//                    $basepath = $input['basepath'];

                    Mail::send('email.invite', [
                        'mail_content' => $remember_token,
                        'admin_name' => $admin_name,
                        'admin_email' => $admin_email,
                        'emails_name' => $emails_name,
                        'basepath' => '',
                    ], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                        $message->from($admin_email, $admin_name);
                        $message->to($emails, $emails_name)->subject($subject);
                    });
                }
                $arr = array("status" => 200, "msg" => 'We have e-mailed your password reset link!', "data" => (object) array());
            } else {
                $arr = array("status" => 400, "msg" => "email not found in our system.", "data" => []);
            }
        }
        return response($arr);
    }

    public function resetPassword(Request $request)
    {
        $input = get_api_key($this->request);
        $rules = array(
            'rememberToken' => 'required',
            'password' => 'required',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "msg" => $validator->errors()->first(), "data" => array());
        } else {
            $user = FrontUsers::where('rememberToken', $input["rememberToken"])->first();

            if ($user) {
                $userupdate = $user->update([
                    'password' => \Hash::make($input['password']),
                    'rememberToken' => '',
                ]);
                $arr = array("status" => 200, "msg" => 'Password Reset successfully!', "data" => (object) array());
            } else {
                $arr = array("status" => 400, "msg" => "Token is expire or already used.", "data" => []);
            }
        }
        return response($arr);
    }

    /*Registration Function for signup*/
    public function registration(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:front_users,email',
            'password' => 'required',
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $create = FrontUsers::create([
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'password' => \Hash::make($input['password']),
                'business_name' => @$input['business_name'],
//                'business_number' => @$input['businessnumber'],
                'phone_number' => @$input['phone_number'],
                'coverage_area' => @$input['coverage_area'],
                'service_location' => @$input['service_location'],
                'user_role' => @$input['user_role'],
                'website' => @$input['website'],
                'status' => 'active',
            ]);

            if ($create) {

                 
                VerifyUser::create([
                    'user_id' => $create->id,
                    'token' => sha1(time())
                ]);
                $emails = $input['email'];
                $emails_name = $input['first_name'] . ' ' . $input['last_name'];
                $subject = "Verify Mail! [Oncaveat]";
                $admin_email = "auto-reply@oncaveat.co.uk";
                $admin_name = "Oncaveat";
                Mail::send('email.verifyUser', ['user' => $create], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                    $message->from($admin_email, $admin_name);
                    $message->to($emails, $emails_name)->subject($subject);
                });
                return response()->json(['status' => 200, 'msg' => 'Registration successfully completed!', 'data' => $create]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Something was wrong!', 'data' => []]);
            }
        }
    }

    public function verifyUserCheck($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if (isset($verifyUser)) {
            $user = $verifyUser->user;
            if (!$user->is_verfied) {
                return response()->json(['status' => 200, 'msg' => '', 'data' => $user]);
            } else {
                $status = "Your e-mail is already verified. You can now login.";
                return response()->json(['status' => 400, 'msg' => $status, 'data' => []]);
            }
        } else {
            $status = "Sorry your email cannot be identified.";
            return response()->json(['status' => 400, 'msg' => $status, 'data' => []]);
        }
    }

    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if (isset($verifyUser)) {
            $user = $verifyUser->user;
            if (!$user->is_verfied) {
                $verifyUser->user->is_verfied = 1;
                $verifyUser->user->email_verified_at = Carbon::now();
                $verifyUser->user->save();
                $status = "Your e-mail is verified. You can now login.";
                 
            } else {
                $status = "Your e-mail is already verified. You can now login.";
            }
        } else {
            $status = "Sorry your email cannot be identified.";
            return response()->json(['status' => 400, 'msg' => $status, 'data' => []]);
        }
        return response()->json(['status' => 200, 'msg' => $status, 'data' => []]);
    }

    public function sliderFetch(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'slider_name' => 'required',
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $sliderget = SliderManager::select('id')->where('status', 'active')->where('name', $input['slider_name'])->first();
            if (!empty($sliderget)) {
                $sliderImages = SliderImage::select('id', 'slider_id', 'name', 'image', 'description')->where('status', 'active')->where('slider_id', $sliderget->id)->get();
                $data = array();
                $data['img_url'] = url('sitebucket/sliderImage');
                $data['raw_data'] = $sliderImages->toArray();
                return response()->json(['status' => 200, 'msg' => 'Image Found successfully!', 'data' => $data]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Slider not found!', 'data' => array()]);
            }
        }
    }

    public function categoryFetch(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'is_popular' => "nullable|numeric",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $fetchdata = Categories::where('status', 'active');
            if (!empty($input['is_popular']) && $input['is_popular'] == '1') {
                $fetchdata->where('is_popular', 1);
            }
            $lm = 8;
            $fetchdata = $fetchdata->limit($lm)->get();
            $data = array();
            $data['img_url'] = url('sitebucket/categories');
            $data['raw_data'] = $fetchdata->toArray();
            if (!empty($fetchdata)) {
                return response()->json(['status' => 200, 'msg' => 'Records Found successfully!', 'data' => $data]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Records not found!', 'data' => array()]);
            }
        }
    }

    public function serviceFetchByCategoy(Request $request)
    {
        $input = $request->all();
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'category' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $categories = Categories::with('services')->whereIn('id', $input['category'])->where('status', 'active')->get();
            $categories->map(function ($category) {
                $category->services->transform(function ($service) {
                    if ($service->image) {
                        $service->image_url = url('sitebucket/services') . '/' . $service->image;
                    }
                    return $service;
                });
            });
            $categories = $categories->toArray();

            $finalcategories = [];
            $finalcategories['services'] = [];
            $finalcategories['allServices'] = [];
            foreach ($categories as $key => $category) {
                foreach ($category['services'] as $key => $service) {
                    array_push($finalcategories['services'], $service);
                    array_push($finalcategories['allServices'], $service['id']);
                }
            }

            return response()->json(['status' => 200, 'msg' => 'Load all categorys', 'data' => $finalcategories]);
        }
    }

    public function recentServiceFetch(Request $request)
    {
        $recentServices = Services::where('status', 'active')->orderBy('created_at', 'desc')->limit(15)->get();
        return response()->json(['status' => 200, 'msg' => 'Records Found successfully!', 'data' => $recentServices]);
    }

    public function serviceFetch(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'is_popular' => "nullable|numeric",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $fetchdata = Services::where('status', 'active')->withCount('suppliers');
            if (!empty($input['is_popular']) && $input['is_popular'] == '1') {
                $fetchdata->where('is_featured', 1);
            }
            $lm = 8;
            if (!empty($input['category_id'])) {
                $category = Categories::where('status', 'active')->where('slug', $input['category_id'])->first();
                if (isset($category) && isset($category->id)) {
                    $fetchdata = $fetchdata->where('category_id', $category->id)->get();
                }
            } else {
                $fetchdata = $fetchdata->limit($lm)->get();
            }

            $fetchdata->transform(function ($service_data) {
                $service_data->image_url  = '';
                $suplier = SupplierService::with(['supplierdetails' => function ($q) {
                    $q->withCount('reviewmaster')->with(['reviewmaster.ratingfetch']);
                }])->where('service_id', $service_data->id)->get();
                $final_avg_rating = $suplier->map(function ($sup) {
                    if ($sup->supplierdetails && $sup->supplierdetails->reviewmaster) {
                        if (count($sup->supplierdetails->reviewmaster) > 0) {
                            $avg_rating = $sup->supplierdetails->reviewmaster->map(function ($reviewmaster) {
                                return $reviewmaster->ratingfetch->avg('rating');
                            });
                            return number_format((float) $avg_rating->avg(), 2, '.', '');
                        }
                    }
                });
                $service_data->avg_rating = number_format((float) $final_avg_rating->avg(), 2, '.', '');
                return $service_data;
            });

            $data = array();
            $data['img_url'] = url('sitebucket/services');
            $data['raw_data'] = $fetchdata->toArray();

            if (!empty($fetchdata)) {
                return response()->json(['status' => 200, 'msg' => 'Records Found successfully!', 'data' => $data]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Records not found!', 'data' => $data]);
            }
        }
    }

    public function testimonialFetch(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            // 'apikey' => "required|in:$this->apikey",
            // 'is_popular' => "nullable|numeric",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $fetchdata = Testimonials::with(['users.reviewmaster' => function ($q) {
                $q->with(['ratingfetch']);
            }])->where('status', 'active');
            if (!empty($input['order_by'])) {
                if ($input['order_by'] == 'ASC') {
                    $fetchdata->orderBy('order_by', 'ASC');
                } else {
                    $fetchdata->orderBy('order_by', "DESC");
                }
            }
            if (!empty($input['paginate'])) {
                $fetchdata = $fetchdata->paginate($input['paginate']);
            } else {
                $fetchdata = $fetchdata->get();
            }
            $fetchdata->transform(function ($sup) {
                if ($sup->users) {
                    $avg_rating = $sup->users->reviewmaster->map(function ($reviewmaster) {
                        return $reviewmaster->ratingfetch->avg('rating');
                    });
                    $sup['avg_rating'] = number_format((float) $avg_rating->avg(), 2, '.', '');
                } else {
                    $sup['avg_rating'] = 0;
                }
                return $sup;
            });

            if (!empty($fetchdata)) {
                return response()->json(['status' => 200, 'img_src' => url('sitebucket/userProfile'), 'msg' => 'Records Found successfully!', 'data' => $fetchdata->toArray()]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Records not found!', 'data' => array()]);
            }
        }
    }
    public function serviceAll()
    {
        $fetchdata = Categories::with('services')->where('status', 'active')->get();
        $data = array();
        $data['img_url_service'] = url('sitebucket/services');
        $data['img_url_categories'] = url('sitebucket/categories');
        $data['raw_data'] = $fetchdata->toArray();
        return response()->json(['status' => 200, 'msg' => 'Records Found successfully!', 'data' => $data]);
    }
    public function stepFetch()
    {
        $fetchdata = array();
        $fetchdata['organisers'] = StepManage::where('type', 'organiserpage-organisers')->where('status', 'active')->orderBy('order_by', 'ASC')->get();
        $fetchdata['suppliers'] = StepManage::where('type', 'organiserpage-suppliers')->where('status', 'active')->orderBy('order_by', 'ASC')->get();
        $fetchdata['organiserspage'] = StepManage::where('type', 'supplierpage-organisers')->where('status', 'active')->orderBy('order_by', 'ASC')->get();
        $fetchdata['supplierspage'] = StepManage::where('type', 'supplierpage-suppliers')->where('status', 'active')->orderBy('order_by', 'ASC')->get();
        $data = array();
        $data['img_url'] = url('sitebucket/stepmanage');
        // $data['raw_data']=$fetchdata->toArray();
        $data['raw_data']['organisers'] = $fetchdata['organisers']->toArray();
        $data['raw_data']['suppliers'] = $fetchdata['suppliers']->toArray();
        $data['raw_data']['organiserspage'] = $fetchdata['organiserspage']->toArray();
        $data['raw_data']['supplierspage'] = $fetchdata['supplierspage']->toArray();
        return response()->json(['status' => 200, 'msg' => 'Records Found successfully!', 'data' => $data]);
    }
    public function getService(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'id' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $fetchdata = Services::with(['spotlight' => function ($q) {
                $q->select('spot_description', 'id', 'business_name', 'first_name', 'last_name')->with(['additionaldetails' => function ($a) {
                    $a->select('supplier_id', 'id', 'profile_pic');
                }]);
            }])->where('status', 'active')->where('slug', $input['id'])->first();
            if (!empty($fetchdata) && !empty($fetchdata->spotlight) && !empty($fetchdata->spotlight->additionaldetails)) {
                if ($fetchdata->spotlight->additionaldetails->profile_pic) {
                    $fetchdata->spotlight->additionaldetails->profile_pic = url('sitebucket/userProfile') . '/' . $fetchdata->spotlight->additionaldetails->profile_pic;
                }
            }
            $data['img_url_service'] = url('sitebucket/services');
            $data['img_url_supplier'] = url('sitebucket/userProfile');
            $data['raw_data'] = $fetchdata->toArray();
            $suplier = SupplierService::with(['supplierdetails' => function ($q) {
                $q->withCount('reviewmaster')->with(['reviewmaster.ratingfetch']);
            }])->with(['supplierdetails.additionaldetails'])->where('service_id', $fetchdata['id'])->get();

            $final_avg = [];
            $suplier->transform(function ($sup) {
                if ($sup->supplierdetails && isset($sup->supplierdetails->reviewmaster) && count($sup->supplierdetails->reviewmaster) > 0) {
                    $avg_rating = $sup->supplierdetails->reviewmaster->map(function ($reviewmaster) {
                        return $reviewmaster->ratingfetch->avg('rating');
                    });
                    $sup['avg_rating'] = number_format((float) $avg_rating->avg(), 2, '.', '');
                }
                return $sup;
            });
            $final_avg_rating = $suplier->map(function ($row) {
                return $row->avg_rating;
            });
            $data['raw_data']['avg_rating'] = number_format((float) $final_avg_rating->avg(), 2, '.', '');

            $data['raw_data_supplier'] = $suplier->toArray();
            if (!empty($fetchdata)) {
                return response()->json(['status' => 200, 'msg' => 'Records Found successfully!', 'data' => $data]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Records not found!', 'data' => array()]);
            }
        }
    }
    public function getCmsPages(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'slug' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $cmspg = CMSPages::where('slug', $input['slug'])->first();
            if (!empty($cmspg)) {
                if (!empty($cmspg->banner_image)) {
                    $cmspg->banner_image_large = url('sitebucket/cmspages') . '/thumbnail/large_' . $cmspg->banner_image;
                    $cmspg->banner_image = url('sitebucket/cmspages') . '/' . $cmspg->banner_image;
                }
                $data = array();
                $data['img_url'] = url('sitebucket/cmspages');
                $data['raw_data'] = $cmspg->toArray();
                return response()->json(['status' => 200, 'msg' => 'CMS page data fetch successfully!', 'data' => $data]);
            } else {
                return response()->json(['status' => 400, 'msg' => "Page not found please check slug", 'data' => array()]);
            }
        }
    }
    public function storeContact(Request $request)
    {
        $input = get_api_key($this->request);
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
                'type' => @$input['type'],
                'message' => @$input['message'],
            ]);
            return response()->json(['status' => 200, 'msg' => 'contact form submitted successfully!', 'data' => array()]);
        }
    }
    public function updateAccountdetails(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'id' => "required|numeric",
            //  'first_name' => "required",
            // 'email' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $userget = FrontUsers::where('id', $input['id'])->first();
            if (!empty($userget)) {
                if (!empty($input['password'])) {
                    $input['password'] = \Hash::make($input['password']);
                } else {
                    $input['password'] = $userget->password;
                }
                $user = FrontUsers::where('id', $input['id'])->update([
                    'first_name' => $input['first_name'],
                    'last_name' => @$input['last_name'],
                    'business_name' => @$input['business_name'],
                    'email' => $input['email'],
                    'password' => $input['password'],
                ]);
                $userdetailsget = UserBusinessDetails::where('supplier_id', $input['id'])->first();
                if (!empty($userdetailsget)) {
                    if (!empty($input['profile_pic'])) {
                        $image_parts = explode(";base64,", $input['profile_pic']);
                        $image_type_aux = explode("image/", $image_parts[0]);
                        $image_type = $image_type_aux[1];
                        $image_base64 = base64_decode($image_parts[1]);
                        $image_name = rand(99, 999999999) . '-' . time() . '.' . $image_type;
                        $destinationPath = public_path('sitebucket/userProfile/');
                        file_put_contents($destinationPath . $image_name, $image_base64);
                        $input['profile_pic'] = $image_name;
                    }
                    $storedata = UserBusinessDetails::where('supplier_id', $input['id'])->update([
                        'dob' => @$input['dob'],
                        'addressline1' => @$input['addressline1'],
                        'addressline2' => @$input['addressline2'],
                        'profile_pic' => @$input['profile_pic'],
                    ]);
                } else {
                    if (!empty($input['profile_pic'])) {
                        $image_parts = explode(";base64,", $input['profile_pic']);
                        $image_type_aux = explode("image/", $image_parts[0]);
                        $image_type = $image_type_aux[1];
                        $image_base64 = base64_decode($image_parts[1]);
                        $image_name = rand(99, 999999999) . '-' . time() . '.' . $image_type;
                        $destinationPath = public_path('sitebucket/userProfile/');
                        file_put_contents($destinationPath . $image_name, $image_base64);
                        $input['profile_pic'] = $image_name;
                    }
                    $storedata = UserBusinessDetails::create([
                        'dob' => @$input['dob'],
                        'supplier_id' => $input['id'],
                        'addressline1' => @$input['addressline1'],
                        'addressline2' => @$input['addressline2'],
                        'profile_pic' => @$input['profile_pic'],
                    ]);
                }
                $return = FrontUsers::with('additionaldetails')->where('id', $input['id'])->first();
                if (!empty($return->additionaldetails->profile_pic)) {
                    $return->additionaldetails->profile_pic = url('sitebucket/userProfile') . '/' . $return->additionaldetails->profile_pic;
                }
                return response()->json(['status' => 200, 'msg' => 'Records Updated successfully!', 'data' => $return]);
            }
        }
    }
    public function blogList(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            // 'name' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            if (empty($input['paginate'])) {
                $input['paginate'] = 6;
            }
            $input['paginate'] = 6;
            $blog = Blogs::where('status', 'active')->with('tags.tags_list');
            if (!empty($input['tags'])) {
                $blog = $blog->whereHas('tags.tags_list', function ($q) use ($input) {
                    $q->where('name', $input['tags']);
                });
            }
            if (!empty($input['categories'])) {
                $blog = $blog->where('blog_category_id', $input['categories']);
            }
            if (!empty($input['search'])) {
                $blog = $blog->Where('title', 'like', '%' . $input['search'] . '%');
                $blog = $blog->orWhere('sub_title', 'like', '%' . $input['search'] . '%');
                $blog = $blog->orWhere('description', 'like', '%' . $input['search'] . '%');
            }
            $blog = $blog->paginate($input['paginate']);
            $blog->transform(function ($blg) {
                $blg['image'] = url('sitebucket/blog') . '/' . $blg->image;
                return $blg;
            });
            return response()->json(['status' => 200, 'msg' => 'Blog list fetch successfully!', 'data' => $blog->toArray()]);
        }
    }
    public function blogDetail(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'slug' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {

            $blog = Blogs::with('category')->with('tags.tags_list')->where('slug', $input['slug'])->where('status', 'active')->first();
            if (!empty($blog)) {
                if (!empty($blog->image)) {
                    $blog['image'] = url('sitebucket/blog') . '/' . $blog->image;
                }
                $currentOrder = $blog->order_by;
                $perviousOrder = $currentOrder - 1;
                $nextOrder = $currentOrder + 1;
                $blog['perviousblog'] = Blogs::select('title', 'id', 'slug')->where('order_by', $perviousOrder)->where('status', 'active')->first();
                $blog['nextblog'] = Blogs::select('title', 'id', 'slug')->where('order_by', $nextOrder)->where('status', 'active')->first();
                $blog['recentblog'] = Blogs::select('title', 'id', 'slug')
                    ->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])
                    ->where('status', 'active')
                    ->where('id', '!=', $blog->id)
                    ->get();
                $blog['categories'] = BlogCategories::where('status', 'active')->get();
                return response()->json(['status' => 200, 'msg' => 'Blog details found successfully!', 'data' => $blog->toArray()]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'blog not found!', 'data' => array()]);
            }
        }
    }
    public function getFaqfetch(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            //  'slug' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $faqpg = Faq::where('status', 'active')->orderBy('order_by', 'ASC')->get();
            if (!empty($faqpg)) {
                return response()->json(['status' => 200, 'msg' => 'Data fetch successfully!', 'data' => $faqpg->toArray()]);
            } else {
                return response()->json(['status' => 400, 'msg' => "Data not found.", 'data' => array()]);
            }
        }
    }
    public function updateBusinessdetails(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'phone_number' => "required",
            'email' => "required",
            'website' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $input['id'] = Auth::user()->id;
            $userget = FrontUsers::where('id', $input['id'])->first();
            if (!empty($userget)) {
                $user = FrontUsers::where('id', $input['id'])->update([
                    'phone_number' => $input['phone_number'],
                    'email' => @$input['email'],
                    'website' => $input['website'],
                ]);
                $return = $this->commonDatasend(Auth::user()->id);
                return response()->json(['status' => 200, 'msg' => 'Updated successfully!', 'data' => $return]);
            }
        }
    }
    public function updateBusinessdetailsOther(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, []);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $input['id'] = Auth::user()->id;
            $userget = FrontUsers::where('id', $input['id'])->first();
            if (!empty($userget)) {
                if (count($input['payment_accept']) > 0) {
                    $input['payment_accept'] = implode(',', $input['payment_accept']);
                } else {
                    $input['payment_accept'] = '';
                }
                $userdetailsget = UserBusinessDetails::where('supplier_id', $input['id'])->first();
                if (!empty($userdetailsget)) {
                    $storedata = UserBusinessDetails::where('supplier_id', $input['id'])->update([
                        'facebook' => @$input['facebook'],
                        'twitter' => @$input['twitter'],
                        'addressline1' => @$input['addressline1'],
                        'addressline2' => @$input['addressline2'],
                        'instagram' => @$input['instagram'],
                        'business_type' => @$input['business_type'],
                        'vat_registration_no' => @$input['vat_registration_no'],
                        'insurance_provider' => @$input['insurance_provider'],
                        'public_liability_cover' => @$input['public_liability_cover'],
                        'insurance_expire_date' => @$input['insurance_expire_date'],
                        'company_number' => @$input['company_number'],
                        'payment_accept' => @$input['payment_accept'],
                        'postcode' => @$input['postcode'],
                        'city' => @$input['city'],
                        'country' => @$input['country'],
                    ]);
                } else {
                    $storedata = UserBusinessDetails::create([
                        'facebook' => @$input['facebook'],
                        'twitter' => $input['twitter'],
                        'addressline1' => @$input['addressline1'],
                        'addressline2' => @$input['addressline2'],
                        'instagram' => @$input['instagram'],
                        'business_type' => @$input['business_type'],
                        'vat_registration_no' => @$input['vat_registration_no'],
                        'insurance_provider' => @$input['insurance_provider'],
                        'public_liability_cover' => @$input['public_liability_cover'],
                        'insurance_expire_date' => @$input['insurance_expire_date'],
                        'company_number' => @$input['company_number'],
                        'payment_accept' => @$input['payment_accept'],
                        'postcode' => @$input['postcode'],
                        'city' => @$input['city'],
                        'country' => @$input['country'],
                        'supplier_id' => @$input['id'],
                    ]);
                }
                // dd($userdetailsget);
                $return = $this->commonDatasend(Auth::user()->id);
                return response()->json(['status' => 200, 'msg' => 'Updated successfully!', 'data' => $return]);
            }
        }
    }
    public function updateLocationdetails(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'location' => "required",
            'subtitle' => "required",
            'coverage_area' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $input['id'] = Auth::user()->id;
            $userget = FrontUsers::where('id', $input['id'])->first();
            if (!empty($userget)) {
                $user = FrontUsers::where('id', $input['id'])->update([
                    'service_location' => $input['location'],
                    'coverage_area' => $input['coverage_area'],
                ]);
                $additiondetails = UserBusinessDetails::where('supplier_id', Auth::user()->id)->first();
                if (!empty($additiondetails)) {
                    UserBusinessDetails::where('id', $additiondetails->id)->update(['subtitle' => $input['subtitle']]);
                }
                $data = $this->commonDatasend(Auth::user()->id);
                return response()->json(['status' => 200, 'msg' => 'Records Updated successfully!', 'data' => $data]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Record not found.', 'data' => array()]);
            }
        }
    }
    public function updateGeneraldetails(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'description' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $input['id'] = Auth::user()->id;
            $userget = UserBusinessDetails::where('supplier_id', $input['id'])->first();
            if (!empty($userget)) {
                $user = UserBusinessDetails::where('supplier_id', $input['id'])->update([
                    'description' => @$input['description'],
                    'typical_client' => @$input['typical_client'],
                ]);
                $data = $this->commonDatasend(Auth::user()->id);
                return response()->json(['status' => 200, 'msg' => 'Updated successfully!', 'data' => $data]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Record not found.', 'data' => array()]);
            }
        }
    }
    public function updateMediadetails(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            //'subtitle' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $input['id'] = Auth::user()->id;
            if (!empty($input['media'])) {
                foreach ($input['media'] as $key => $value) {
                    if (!empty($value['value'])) {
                        if ($value['type'] == 'logo') {
                            if (!empty($value['value'])) {
                                $image_parts = explode(";base64,", $value['value']);
                                $image_type_aux = explode("image/", $image_parts[0]);
                                $image_type = $image_type_aux[1];
                                $image_base64 = base64_decode($image_parts[1]);
                                $image_name = rand(99, 999999999) . '-' . time() . '.' . $image_type;
                                $destinationPath = public_path('sitebucket/business/');
                                file_put_contents($destinationPath . $image_name, $image_base64);
                                $saveImage = $image_name;
                            }
                            UserBucket::create([
                                'supplier_id' => $input['id'],
                                'media_url' => $saveImage,
                                'type' => 'logo',
                            ]);
                        }
                        if ($value['type'] == 'photos') {
                            if (!empty($value['value'])) {
                                $image_parts = explode(";base64,", $value['value']);
                                $image_type_aux = explode("image/", $image_parts[0]);
                                $image_type = $image_type_aux[1];
                                $image_base64 = base64_decode($image_parts[1]);
                                $image_name = rand(99, 999999999) . '-' . time() . '.' . $image_type;
                                $destinationPath = public_path('sitebucket/business/');
                                file_put_contents($destinationPath . $image_name, $image_base64);
                                $saveImage = $image_name;
                            }
                            UserBucket::create([
                                'supplier_id' => $input['id'],
                                'media_url' => $saveImage,
                                'type' => 'photos',
                            ]);
                        }
                        if ($value['type'] == 'video') {
                            UserBucket::create([
                                'supplier_id' => $input['id'],
                                'media_url' => @$value['value'],
                                'media_description' => @$value['description'],
                                'type' => 'video',
                            ]);
                        }
                    }
                }

                $data = $this->commonDatasend(Auth::user()->id);
                return response()->json(['status' => 200, 'msg' => 'Data Uploaded successfully!', 'data' => $data]);
            }
        }
    }
    public function removeMediadetails(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'media_id' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $input['id'] = Auth::user()->id;
            $getdetails = UserBucket::where('id', $input['media_id'])->first();
            if (!empty($getdetails)) {
                if ($getdetails->type == 'video') {
                    $del = UserBucket::where('id', $input['media_id'])->delete();
                } else {
                    $path = 'sitebucket/business/';
                    if (file_exists(public_path($path . '/' . $getdetails->media_url))) {
                        unlink(public_path($path . '/' . $getdetails->media_url));
                    }
                    $del = UserBucket::where('id', $input['media_id'])->delete();
                }
                return response()->json(['status' => 200, 'msg' => 'Data Removed successfully!', 'data' => $this->commonDatasend(Auth::user()->id)]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Media not found!', 'data' => array()]);
            }
        }
    }
    public function addServiceSupplier(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'service_id' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $input['id'] = Auth::user()->id;
            $getdetails = Services::where('id', $input['service_id'])->first();
            if (!empty($getdetails)) {
                $supplierService = SupplierService::where('supplier_id', $input['id'])->where('service_id', $input['service_id'])->first();
                if (empty($supplierService)) {
                    $addedData = SupplierService::create([
                        'supplier_id' => $input['id'],
                        'service_id' => $input['service_id'],
                    ]);
                }
                return response()->json(['status' => 200, 'msg' => 'Service Added successfully!', 'data' => array()]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Service not found!', 'data' => array()]);
            }
        }
    }
    public function removeServiceSupplier(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'service_id' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $input['id'] = Auth::user()->id;
            $getdetails = SupplierService::where('id', $input['service_id'])->first();
            if (!empty($getdetails)) {
                $getdetails = $getdetails->delete();
                return response()->json(['status' => 200, 'msg' => 'Service Deactivated successfully!', 'data' => array()]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Service not found!', 'data' => array()]);
            }
        }
    }
    public function editServiceSupplier(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'service_id' => "required",
            'coverage_area' => "required",
            'service_location' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $input['id'] = Auth::user()->id;
            $getdetails = SupplierService::where('id', $input['service_id'])->first();
            if (!empty($getdetails)) {
                $getdetails = $getdetails->update([
                    'service_location' => $input['service_location'],
                    'coverage_area' => $input['coverage_area'],
                ]);
                $data = SupplierService::where('id', $input['service_id'])->first();
                return response()->json(['status' => 200, 'msg' => 'Service Updated successfully!', 'data' => $data]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Service not found!', 'data' => array()]);
            }
        }
    }

    public function listServiceSupplierTransform($gt)
    {
        if (!empty($gt->serviceDetails->image)) {
            $gt->serviceDetails->image = url('sitebucket/services') . '/' . $gt->serviceDetails->image;
        }
        if (!empty($gt->serviceDetails)) {
            $steparr = $gt->serviceDetails->dynamicsteps->map(function ($dynamics) {
                return $dynamics->answershort = json_decode($dynamics->stepdata->answers, true);
            });
        }
        $temp = [];
        $main_ar = $gt->selectedAnswers->map(function ($anser) {
            $ch = json_decode($anser->answers);
            foreach ($ch as $e => $b) {
                $temp[] = ['id' => $anser->request_step_id, 'option' => $b];
            }
            return $temp;
        });

        if (!empty($main_ar->toArray())) {
            $gt->selectmain = call_user_func_array('array_merge', $main_ar->toArray());
        } else {
            $gt->selectmain = [];
        }
        return $gt;
    }

    public function listServiceSupplier($id = null, Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, []);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $input['id'] = Auth::user()->id;
            $getdetails = SupplierService::with(['selectedEventtype' => function ($a) {
                $a->select('id', 'supplier_service_id', 'event_type_id');
            }]);
            $getdetails = $getdetails->with(['serviceDetails.dynamicsteps.stepdata' => function ($q) {
                // $q->select('id', 'name', 'sort_description', 'image');
            }]);
            $getdetails = $getdetails->with('serviceContent')->with('selectedAnswers');
            $getdetails = $getdetails->where('supplier_id', $input['id']);
            if ($id) {
                $getdetails = $getdetails->where('id', $id)->first();
                $getdetails = $this->listServiceSupplierTransform($getdetails);
            } else {
                $getdetails = $getdetails->get();
                $getdetails->transform(function ($gt) {
                    $gt = $this->listServiceSupplierTransform($gt);
                    return $gt;
                });
            }
            return response()->json(['status' => 200, 'msg' => 'Service Fetch successfully!', 'data' => $getdetails]);
        }
    }
    public function listEventTypes(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, []);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $getdetails = EventTypes::where('status', 'active')->get();
            if (!empty(Auth::user())) {
                $input['id'] = Auth::user()->id;
                $getdetails->transform(function ($gt) use ($input) {
                    $getId = SupplierService::where('supplier_id', $input['id'])->pluck('id')->toArray();
                    $check = SupplierServiceEventType::whereIn('supplier_service_id', $getId)->where([
                        'event_type_id' => $gt->id,
                    ])->first();
                    if (!empty($check)) {
                        $gt->validate = 'checked';
                    } else {
                        $gt->validate = '';
                    }
                    return $gt;
                });
            }
            return response()->json(['status' => 200, 'msg' => 'Service Fetch successfully!', 'data' => $getdetails]);
        }
    }
    public function listEventTypesSupplier(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, []);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $getdetails = EventTypes::where('status', 'active')->get();
            $input['id'] = Auth::user()->id;
            $getdetails->transform(function ($gt) use ($input) {
                $getId = SupplierService::where('supplier_id', $input['id'])->pluck('id')->toArray();
                $check = SupplierServiceEventType::whereIn('supplier_service_id', $getId)->where([
                    'event_type_id' => $gt->id,
                ])->first();
                if (!empty($check)) {
                    $gt->validate = 'checked';
                } else {
                    $gt->validate = '';
                }
                return $gt;
            });

            return response()->json(['status' => 200, 'msg' => 'Service Fetch successfully!', 'data' => $getdetails]);
        }
    }
    public function ServiceEventTypesUpdate(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'service_id' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $input['id'] = Auth::user()->id;
            $serviceCheck = SupplierService::where('id', $input['service_id'])->first();
            if (!empty($serviceCheck)) {
                if (!empty($input['event_type_id'])) {
                    $checkdata = SupplierServiceEventType::where('supplier_service_id', $input['service_id'])->delete();
                    foreach ($input['event_type_id'] as $key => $value) {
                        SupplierServiceEventType::create([
                            'event_type_id' => $value,
                            'supplier_service_id' => $input['service_id'],
                        ]);
                    }
                }
                return response()->json(['status' => 200, 'msg' => 'Services updated successfully!', 'data' => array()]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Service not found with this supplier.', 'data' => array()]);
            }
        }
    }
    public function SupplierServiceContent(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'description' => "required",
            'service_id' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $saveImage = "";
            $input['id'] = Auth::user()->id;
            $serviceCheck = SupplierServiceCustomization::where('supplier_service_id', $input['service_id'])->first();
            if (!empty($serviceCheck)) {
                $saveImage = $serviceCheck->image;
                if (!empty($value['image'])) {
                    if ($serviceCheck->image) {
                        $path = 'sitebucket/services/';
                        if (file_exists(public_path($path . '/' . $serviceCheck->image))) {
                            unlink(public_path($path . '/' . $serviceCheck->image));
                        }
                    }
                    $image_parts = explode(";base64,", $value['image']);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $image_name = rand(99, 999999999) . '-' . time() . '.' . $image_type;
                    $destinationPath = public_path('sitebucket/services/');
                    file_put_contents($destinationPath . $image_name, $image_base64);
                    $saveImage = $image_name;
                }
                $serviceCheck = $serviceCheck->update([
                    'supplier_service_id' => $input['service_id'],
                    'description' => $input['description'],
                    'image' => $saveImage,
                ]);
            } else {
                if (!empty($value['image'])) {
                    $image_parts = explode(";base64,", $value['image']);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $image_name = rand(99, 999999999) . '-' . time() . '.' . $image_type;
                    $destinationPath = public_path('sitebucket/services/');
                    file_put_contents($destinationPath . $image_name, $image_base64);
                    $saveImage = $image_name;
                }
                $serviceCheck = SupplierServiceCustomization::create([
                    'supplier_service_id' => $input['service_id'],
                    'description' => $input['description'],
                    'image' => $saveImage,
                ]);
            }
            return response()->json(['status' => 200, 'msg' => 'Service Updated successfully!', 'data' => array()]);
        }
    }
    public function getprofileData(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            // 'service_id' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $additiondetails = UserBusinessDetails::where('supplier_id', Auth::user()->id)->first();
            if (!empty($additiondetails) && !empty($additiondetails->profile_pic)) {
                $additiondetails->profile_pic = url('sitebucket/userProfile') . '/' . $additiondetails->profile_pic;
            }
            $userbucket = UserBucket::where('supplier_id', Auth::user()->id)->get();
            $userbucket->transform(function ($usebkt) {
                if ($usebkt->type == 'photos' || $usebkt->type == 'logo') {
                    $usebkt['media_url'] = url('sitebucket/business') . '/' . $usebkt->media_url;
                }
                return $usebkt;
            });
            $data = Auth::user();
            $data['additionaldetails'] = $additiondetails;
            $data['userbucket'] = $userbucket;
            return response()->json(['status' => 200, 'msg' => 'fetch data!', 'data' => $data]);
        }
    }
    public function qouteRequest(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'event_type_id' => "required",
            'location' => "required"
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            if (empty($input['user_id'])) {
                $valid = Validator::make($input, [
                    'email' => "required|email|unique:front_users",
                    'password' => "required",
                    'first_name' => "required",
                    'last_name' => "required",
                    'user_role' => "required",
                ]);
                if ($valid->fails()) {
                    return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
                } else {
                    $user = FrontUsers::create([
                        'first_name' => $input['first_name'],
                        'last_name' => $input['last_name'],
                        'email' => $input['email'],
                        'password' => \Hash::make($input['password']),
                        'phone_number' => $input['contact_no'],
                        'user_role' => $input['user_role'],
                        'status' => 'active',
                        'service_location' => $input['location'],
                    ]);
                    $userId = $user->id;
                    $created = QuoteRequests::create([
                        'user_id' => $userId,
                        'event_type_id' => $input['event_type_id'],
                        'event_type_other' => $input['event_type_other'],
                        'location' => $input['location'],
                        'event_date' => @$input['event_date'],
                        'estimated_time' => @$input['estimated_time'],
                        'estimated_duration' => @$input['estimated_duration'],
                        'message' => @$input['message'],
                        'estimated_guests' => @$input['estimated_guests'],
                        'your_role' => @$input['your_role'],
                        'what_stage' => @$input['what_stage'],
                        'contact_no' => @$input['contact_no'],
                        'event_type_selection' => @$input['event_type_selection'],
                        'event_type_name' => @$input['event_type_name'],
                        'event_type_website' => @$input['event_type_website'],
                        'location_venue_type' => @$input['location_venue_type'],
                        'estimated_duration_type' => @$input['estimated_duration_type'],
                        'your_role_other' => @$input['your_role_other'],
                    ]);
                    $quote_request_id = $created->id;
                    $temp = [];
                    if (!empty($input['service_id']) && count($input['service_id']) > 0) {
                        foreach ($input['service_id'] as $key => $service_id) {
                            QuoteRequestServices::create([
                                'quote_request_id' => $quote_request_id,
                                'service_id' => $service_id,
                            ]);
                        }
                    }

                    if (!empty($input['finalData'])) {
                        foreach ($input['finalData'] as $key => $value) {
                            $temp[$value['id']][] = $value['option'];
                        }
                        if (!empty($temp)) {
                            foreach ($temp as $a => $b) {
                                $stepcreated = QuoteRequestsStepValue::create([
                                    'quote_request_id' => $quote_request_id,
                                    'request_step_id' => $a,
                                    'answer' => json_encode($b),
                                ]);
                            }
                        }
                    }

                    /* Event submited notification send */
                    $event_type = EventTypes::where('id', $input['event_type_id'])->first();
                    $event_message = 'Event Request';
                    $event_title = $event_type->title . ' in ' . $input['location'] . ' event created successfully.';
                    $this->notificationCreate($userId, $userId, 'Event', $event_title, $event_message);

                    VerifyUser::create([
                        'user_id' => $user->id,
                        'token' => sha1(time())
                    ]);
                    Mail::to($user->email)->send(new VerifyMail($user));

                    // $data = [];
                    // $data['quote_id'] = $quote_request_id;
                    // $data['user_id'] = $userId;
                    // dispatch(new SendQuoteRequestSupplier($data));
                    return response()->json(['status' => 200, 'msg' => "Your Event created successfully.", 'data' => array()]);
                }
            } else {
                $userId = $input['user_id'];
                $created = QuoteRequests::create([
                    'user_id' => $userId,
                    'event_type_id' => $input['event_type_id'],
                    'event_type_other' => $input['event_type_other'],
                    'location' => $input['location'],
                    'event_date' => @$input['event_date'],
                    'estimated_time' => @$input['estimated_time'],
                    'estimated_duration' => @$input['estimated_duration'],
                    'message' => @$input['message'],
                    'estimated_guests' => @$input['estimated_guests'],
                    'your_role' => @$input['your_role'],
                    'what_stage' => @$input['what_stage'],
                    'contact_no' => @$input['contact_no'],
                    'event_type_selection' => @$input['event_type_selection'],
                    'event_type_name' => @$input['event_type_name'],
                    'event_type_website' => @$input['event_type_website'],
                    'location_venue_type' => @$input['location_venue_type'],
                    'estimated_duration_type' => @$input['estimated_duration_type'],
                    'your_role_other' => @$input['your_role_other'],
                ]);
                $quote_request_id = $created->id;
                $temp = [];
                if (!empty($input['service_id']) && count($input['service_id']) > 0) {
                    foreach ($input['service_id'] as $key => $service_id) {
                        QuoteRequestServices::create([
                            'quote_request_id' => $quote_request_id,
                            'service_id' => $service_id,
                        ]);
                    }
                }
                // if (!empty($input['service_id'])) {
                //     QuoteRequestServices::create([
                //         'quote_request_id' => $quote_request_id,
                //         'service_id' => $input['service_id'],
                //     ]);
                // }
                if (!empty($input['finalData'])) {
                    foreach ($input['finalData'] as $key => $value) {
                        $temp[$value['id']][] = $value['option'];
                    }
                    if (!empty($temp)) {
                        foreach ($temp as $a => $b) {
                            $stepcreated = QuoteRequestsStepValue::create([
                                'quote_request_id' => $quote_request_id,
                                'request_step_id' => $a,
                                'answer' => json_encode($b),
                            ]);
                        }
                    }
                }

                /* Event submited notification send */
                $event_type = EventTypes::where('id', $input['event_type_id'])->first();
                $event_message = 'Event Request';
                $event_title = $event_type->title . ' in ' . $input['location'] . ' event created successfully.';
                $this->notificationCreate($userId, $userId, 'Event', $event_title, $event_message);

                $data = [];
                $data['quote_id'] = $quote_request_id;
                $data['user_id'] = $userId;
                dispatch(new SendQuoteRequestSupplier($data));
                return response()->json(['status' => 200, 'msg' => "Your Event created successfully.", 'data' => array()]);
            }
        }
    }
    public function profileUpdate(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'email' => "required",
            'first_name' => "required",
            'last_name' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            if (!empty($input['password'])) {
                $password = \Hash::make($input['password']);
            } else {
                $password = Auth::user()->password;
            }
            $update = FrontUsers::where('id', Auth::user()->id)->update([
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'password' => $password,
            ]);
            $additonal_d = UserBusinessDetails::where('supplier_id', Auth::user()->id)->first();
            if (!empty($input['profile_pic']) && $input['profile_pic'] != "undefine") {
                if (isset($additonal_d) && $additonal_d->profile_pic) {
                    $path = 'sitebucket/userProfile/';
                    if (file_exists(public_path($path . '/' . $additonal_d->profile_pic))) {
                        unlink(public_path($path . '/' . $additonal_d->profile_pic));
                    }
                }
                $image_parts = explode(";base64,", $input['profile_pic']);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $image_name = rand(99, 999999999) . '-' . time() . '.' . $image_type;
                //$image_name = time() . '.png';
                $destinationPath = public_path('sitebucket/userProfile/');
                file_put_contents($destinationPath . $image_name, $image_base64);
                $input['profile_pic'] = $image_name;
            } else {
                $input['profile_pic'] = ($additonal_d && $additonal_d->profile_pic) ? $additonal_d->profile_pic : '';
            }
            if (!empty($additonal_d)) {
                $updatead = UserBusinessDetails::where('supplier_id', Auth::user()->id)->update([
                    'dob' => @$input['dob'],
                    'addressline1' => @$input['addressline1'],
                    'addressline2' => @$input['addressline2'],
                    'postcode' => @$input['postcode'],
                    'city' => @$input['city'],
                    'country' => @$input['country'],
                    'profile_pic' => @$input['profile_pic'],
                ]);
            } else {
                $updatead = UserBusinessDetails::create([
                    'supplier_id' => Auth::user()->id,
                    'dob' => @$input['dob'],
                    'addressline1' => @$input['addressline1'],
                    'addressline2' => @$input['addressline2'],
                    'postcode' => @$input['postcode'],
                    'city' => @$input['city'],
                    'country' => @$input['country'],
                    'profile_pic' => @$input['profile_pic'],
                ]);
            }
            $return = FrontUsers::with('additionaldetails')->where('id', Auth::user()->id)->first();
            if (!empty($return->additionaldetails->profile_pic)) {
                $return->additionaldetails->profile_pic = url('sitebucket/userProfile') . '/' . $return->additionaldetails->profile_pic;
            }
            return response()->json(['status' => 200, 'msg' => "Profile updated successfully!", 'data' => $return]);
        }
    }
    public function archiveRequest(Request $request)
    {
        $input = $request->all();
        $valid = Validator::make($input, [
            'id' => "required|exists:quote_requests",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $quoteRequests = QuoteRequests::where('id', $input['id'])->where('user_id', Auth::user()->id)->count();
            if ($quoteRequests > 0) {
                QuoteRequests::where('id', $input['id'])->update(['archived' => '1']);
            } else {
                $quoteRequestSupplier = QuoteRequestSupplier::where('quote_request_id', $input['id'])->where('supplier_id', Auth::user()->id)->count();
                if ($quoteRequestSupplier > 0) {
                    QuoteRequestSupplier::where('quote_request_id', $input['id'])->where('supplier_id', Auth::user()->id)->update(['isArchive' => '1']);
                }
            }
            return response()->json(['status' => 200, 'msg' => "Request Archived Successfully!", 'data' => array()]);
        }
    }

    public function myRequests(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            //  'event_type_id' => "required",
            //  'location' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $list = QuoteRequests::withCount('multirequest')->with('quoteBy')->with('eventtype')->with('servicelist.servicedetails');
            if (Auth::user()->user_role == 'supplier' && !empty($input['myreq']) && $input['myreq'] == '1') {
                $list = $list->where('user_id', Auth::user()->id);
            } else {
                if (Auth::user()->user_role == 'supplier') {
                    if (!empty($input['myreq']) && $input['myreq'] == '1') {
                        $list = $list->where('user_id', Auth::user()->id);
                    } else {
                        $list = $list->with('QuoteRequestSupplier');
                        $list = $list->whereHas('QuoteRequestSupplier', function ($q) use ($input) {
                            if (!empty($input['quotes']) && $input['quotes'] == '1') {
                                $q->where('isQuote', 'quotes');
                            } else if (!empty($input['completed']) && $input['completed'] == '1') {
                                $q->where('isQuote', 'completed');
                            } else if (!empty($input['rejected']) && $input['rejected'] == '1') {
                                $q->where('isQuote', 'rejected');
                            } else if (!empty($input['upcoming']) && $input['upcoming'] == '1') {
                                $q->where('isQuote', 'upcoming');
                            } else {
                                $q->where('isQuote', 'request');
                            }
                            $q->where('supplier_id', Auth::user()->id);
                        });
                    }
                } else {
                    $list = $list->where('user_id', Auth::user()->id);
                }

                if (!empty($input['upcoming']) && $input['upcoming'] == '1') {
                    $list = $list->where('main_type', 'upcoming');
                } else if (!empty($input['completed']) && $input['completed'] == '1') {
                    $list = $list->where('main_type', 'completed');
                    $list = $list->where('event_date', '<', Carbon::now());
                }
            }

            if (!empty($input['archived']) && $input['archived'] == '1') {
                $list = $list->where('archived', '1');
            } else {
                $list = $list->where('archived', '0');
            }

            if (!empty($input['event_date'])) {
                $list = $list->where('event_date', $input['event_date']);
            }
            if (!empty($input['service']) && $input['service'] != '0') {
                $list = $list->whereHas('servicelist', function ($query) use ($input) {
                    $query->where('service_id', $input['service']);
                });
            }
            if (!empty($input['search'])) {
                $list = $list->whereRaw('LOWER(`location`) LIKE ? ', ['%' . trim(strtolower($input['search'])) . '%']);
                $list = $list->orwhereRaw('LOWER(`message`) LIKE ? ', ['%' . trim(strtolower($input['search'])) . '%']);
                $list = $list->orwhereRaw('LOWER(`what_stage`) LIKE ? ', ['%' . trim(strtolower($input['search'])) . '%']);
            }

            $list = $list->orderBy('created_at', 'DESC')->get();
            $list->transform(function ($lists) use ($input) {
                if (!empty($lists->event_date)) {
                    $lists->event_date_human = \Carbon\Carbon::createFromTimeStamp(strtotime($lists->created_at))->diffForHumans();
                } else {
                    $lists->event_date_human = "";
                }
                $lists->user = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                $lists->list_date = \Carbon\Carbon::parse($lists->event_date)->format('D d M y');
                $lists->eventType = $lists->eventtype->title;
                $serlist = [];
                $serviceget = $lists->servicelist->map(function ($sericelist) {
                    return $serlist[] = $sericelist->servicedetails->name;
                });
                $lists->services = $serviceget ? implode(',', $serviceget->toArray()) : '';

                if (Carbon::parse($lists->event_date)->lt(Carbon::now())) {
                    $lists->is_passed = true;
                } else {
                    $lists->is_passed = false;
                }
                return $lists;
            });

            $fetchdata = Services::select('name', 'id', 'category_id')->with('category')->where('status', 'active')->get();
            $fetchdata->transform(function ($service) {
                $service->category_name = $service->category->name;
                return $service;
            });
            return response()->json(['status' => 200, 'msg' => "Request Listing!", 'data' => $list, 'service_list' => $fetchdata]);
        }
    }

    public function onlyMyRequests(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, []);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $list = QuoteRequests::withCount('multirequest')->with('quoteBy')->with('eventtype')->with('servicelist.servicedetails');
            $list = $list->where('user_id', Auth::user()->id);

            if (!empty($input['archived']) && $input['archived'] == '1') {
                $list = $list->where('archived', '1');
            } else {
                $list = $list->where('archived', '0');
            }

            $list = $list->orderBy('created_at', 'DESC')->get();

            $list->transform(function ($lists) use ($input) {
                if (!empty($lists->event_date)) {
                    $lists->event_date_human = \Carbon\Carbon::createFromTimeStamp(strtotime($lists->created_at))->diffForHumans();
                } else {
                    $lists->event_date_human = "";
                }
                $lists->user = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                $lists->list_date = \Carbon\Carbon::parse($lists->event_date)->format('D d M y');
                $lists->eventType = $lists->eventtype->title;
                $serlist = [];
                $serviceget = $lists->servicelist->map(function ($sericelist) {
                    return $serlist[] = $sericelist->servicedetails->name;
                });
                $lists->services = $serviceget ? implode(',', $serviceget->toArray()) : '';
                if (Carbon::parse($lists->event_date)->lt(Carbon::now())) {
                    $lists->is_passed = true;
                } else {
                    $lists->is_passed = false;
                }
                return $lists;
            });
            $fetchdata = Services::select('name', 'id', 'category_id')->with('category')->where('status', 'active')->get();
            $fetchdata->transform(function ($service) {
                $service->category_name = $service->category->name;
                return $service;
            });
            return response()->json(['status' => 200, 'msg' => "Request Listing!", 'data' => $list, 'service_list' => $fetchdata]);
        }
    }

    public function onlyServices()
    {
        $fetchdata = Services::select('name', 'id', 'category_id')->with('dynamicsteps.stepdata')->with('category')->where('status', 'active')->get();
        $fetchdata->transform(function ($service) {
            $service->category_name = $service->category->name;
            return $service;
        });
        return response()->json(['status' => 200, 'msg' => 'Records Found successfully!', 'data' => $fetchdata]);
    }
    public function onlyServicesById(Request $request)
    {
        $ids = $this->request->all();
        if (count($ids) > 0) {
            $fetchdata = Services::select('name', 'id', 'category_id')->with('dynamicsteps.stepdata')->whereIn('id', $ids)->where('status', 'active')->get();
            $fetchdata->transform(function ($ser) {
                $ser->dynamicsteps->transform(function ($lists) {
                    $lists->answershort = json_decode($lists->stepdata->answers, true);
                    $lists->answershort = json_decode(json_encode($lists->answershort));
                    return $lists;
                });
                return $ser;
            });

            $finalfetchdata['dynamicsteps'] = [];
            foreach ($fetchdata as $key => $service) {
                foreach ($service->dynamicsteps as $key => $step) {
                    if (!in_array($step->request_step_id, array_column($finalfetchdata['dynamicsteps'], 'request_step_id'))) {
                        array_push($finalfetchdata['dynamicsteps'], $step);
                    }
                }
            }

            if (count($finalfetchdata) > 0) {
                return response()->json(['status' => 200, 'msg' => 'Records Found successfully!', 'data' => $finalfetchdata, 'maindata' => $fetchdata]);
            } else {
                return response()->json(['status' => 200, 'msg' => 'Records Found successfully!', 'data' => []]);
            }
        }
        return response()->json(['status' => 200, 'msg' => 'Records Found successfully!', 'data' => []]);
    }

    public function commanrequestDetails($id)
    {
        $lists = QuoteRequests::withCount('multirequest')
            ->with('quoteBy.additionaldetails')
            ->with('multirequest.quoteRequestConversationMessages.quoteby.additionaldetails')
            ->with('multirequest.quoteRequestConversationBucket')
            ->with('multirequest.quoteby.reviewmaster.ratingfetch.rattingTypes')
            ->with('eventtype')
            ->with('quoteReviews.responseBy.additionaldetails')
            ->with('quoteReviews.reviewsBy.additionaldetails')
            ->with('quoteReviews.ratingfetch.rattingTypes')
            ->with('servicelist.servicedetails')            
            ->with('QuoteRequestSupplier.quoteby.additionaldetails')
            ->where('id', $id)->first();
 
        if ($lists->quoteReviews && $lists->quoteReviews->ratingfetch) {
            $avg_indi = $lists->quoteReviews->ratingfetch->avg('rating');
            if (!empty($avg_indi)) {
                $final_rating = number_format((float) $avg_indi, 2, '.', '');
            } else {
                $final_rating = 0.0;
            }
            $lists->quoteReviews->individual_ratting = $final_rating;
        }

        $lists->myRequests_count = 0;
        $lists->multirequest->map(function ($v) use ($lists) {
            $v->file_url = '';
            $v->file_name = '';
            if (!empty($v->quoteRequestConversationBucket)) {
                $v->file_name = $v->quoteRequestConversationBucket->attachment;
                $v->file_url = url('sitebucket/quoteRequestsDocuments') . '/' . $v->quoteRequestConversationBucket->attachment;
            }
            if ($v->user_id == Auth::user()->id) {
                $lists->myRequests_count = $lists->myRequests_count + 1;
                $lists->myRequests = $v;
            }
            return $v;
        });

        $lists->QuoteRequestSupplier->map(function ($supplier) use ($lists) {
            if ($supplier->isQuote == 'completed' || $supplier->isQuote == 'upcoming') {
                $lists->supplierSelected = $supplier;
            }
        });

        if (!empty($lists->event_date)) {
            $lists->event_date_human = \Carbon\Carbon::createFromTimeStamp(strtotime($lists->event_date))->diffForHumans();
        } else {
            $lists->event_date_human = "";
        }

        $lists->userProfileBaseUrl = url('sitebucket/userProfile');
        $lists->user = Auth::user()->first_name . ' ' . Auth::user()->last_name;
        $lists->list_date = \Carbon\Carbon::parse($lists->event_date)->format('D d M y');
        $lists->eventType = $lists->eventtype->title;

        $serlist = [];
        $serviceget = $lists->servicelist->map(function ($sericelist) {
            return $serlist[] = $sericelist->servicedetails->name;
        });
        $categorylistdata = [];
        $categoryget = $lists->servicelist->map(function ($categorylist) {
            return $categorylistdata[] = $categorylist->servicedetails->category->name;
        });

        $categoryget = array_unique($categoryget->toArray());
        $lists->services = $serviceget ? implode(',', $serviceget->toArray()) : '';
        $lists->category = $categoryget ? implode(',', $categoryget) : '';
        return $lists;
    }

    public function requestDetails(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'id' => "required|exists:quote_requests",
            //  'location' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            return response()->json(['status' => 200, 'msg' => "Request Datafetch!", 'data' => $this->commanrequestDetails($input['id'])]);
        }
    }
    public function commanrequestQuoteDetails($id)
    {
        $lists = QuoteRequests::with('eventtype')->where('id', $id)->first();
        $lists->eventType = $lists->eventtype->title;
        $conversation = QuoteRequestConversation::with('quoteRequestConversationBucket')
            ->with('quoteRequestConversationMessages.quoteby.additionaldetails')
            ->where('quote_request_id', $id)->orderBy('created_at', 'desc')->groupBy('user_id')->get();
        $conversation->transform(function ($v) {
            if ($v->quoteRequestConversationBucket && $v->quoteRequestConversationBucket->attachment) {
                $v->quoteRequestConversationBucket->attachment_name = $v->quoteRequestConversationBucket->attachment;
                $v->quoteRequestConversationBucket->attachment = url('sitebucket/quoteRequestsDocuments') . '/' . $v->quoteRequestConversationBucket->attachment;
            }
            $v->isQuote =  QuoteRequestSupplier::where('quote_request_id', $v->quote_request_id)->where('supplier_id', $v->user_id)->pluck('isQuote')->first();
            $v->quoteby = $this->commonDatasend($v->user_id)->toArray();
            $v->review_count = ReviewsMaster::where('quote_id', $v->quote_request_id)->where('supplier_id', $v->user_id)->count();
            return $v;
        });
        $lists->request_conversation =  $conversation;
        $lists->baseUrl = url('sitebucket/userProfile');
        if (Carbon::parse($lists->event_date)->lt(Carbon::now())) {
            $lists->is_passed = true;
        } else {
            $lists->is_passed = false;
        }
        return $lists;
    }

    public function requestQuoteDetails(Request $request)
    {
        $input = $request->all();
        $valid = Validator::make($input, [
            'id' => "required|exists:quote_requests",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            return response()->json(['status' => 200, 'msg' => "Request Datafetch!", 'data' => $this->commanrequestQuoteDetails($input['id'])]);
        }
    }
    public function SupplierServiceStepAnswers(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'id' => "required|exists:supplier_services",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $temp = [];
            if (!empty($input['finalData'])) {
                foreach ($input['finalData'] as $key => $value) {
                    $temp[$value['id']][] = $value['option'];
                }
                if (!empty($temp)) {
                    foreach ($temp as $a => $b) {
                        $check = SupplierServiceStepAnswers::where('supplier_service_id', $input['id'])->where('request_step_id', $a)->first();
                        if (!empty($check)) {
                            SupplierServiceStepAnswers::where('id', $check->id)->update([
                                'supplier_service_id' => $input['id'],
                                'request_step_id' => $a,
                                'answers' => json_encode($b),
                            ]);
                        } else {
                            SupplierServiceStepAnswers::create([
                                'supplier_service_id' => $input['id'],
                                'request_step_id' => $a,
                                'answers' => json_encode($b),
                            ]);
                        }
                    }
                }
            }
            return response()->json(['status' => 200, 'msg' => "Service Updated successfully!", 'data' => array()]);
        }
    }
    /* Supplier Details information based on the Login User for public profile*/
    public function SummaryProfileData(Request $request)
    {
        // $input = get_api_key($this->request);

        // $users = FrontUsers::with('userBuckets')->with('additionaldetails')->with('supplierservice.serviceDetails')
        //     ->where('id', Auth::user()->id)
        //     ->with(['reviewmaster.reviewsBy' => function ($q) {
        //         $q->select('id', 'first_name', 'last_name');
        //     }])
        //     ->with('reviewmaster.ratingfetch.rattingTypes')->withCount('reviewmaster')->first();
        //     $serviceIds = $users->supplierservice->map(function ($supplierser) {
        //         return $supplierser->serviceDetails->id;
        //     });

        // $userbucket = $users->userBuckets->map(function ($usbucket) {
        //     if ($usbucket->type == 'photos' || $usbucket->type == 'logo') {
        //         $usbucket->media_url = url('sitebucket/business') . '/' . $usbucket->media_url;
        //     }
        //     return $usbucket;
        // });
        // $users->serviceIds = $serviceIds;
        // $avg_rating = $users->reviewmaster->map(function ($reviewmaster) {
        //     return $reviewmaster->ratingfetch->avg('rating');
        // });
        // $avg_ratingindi = $users->reviewmaster->map(function ($reviewmasterindi) {
        //     $avg_indi = $reviewmasterindi->ratingfetch->avg('rating');
        //     if (!empty($avg_indi)) {
        //         $final_rating = number_format((float) $avg_indi, 2, '.', '');
        //     } else {
        //         $final_rating = 0.0;
        //     }
        //     return $reviewmasterindi->individual_ratting = $final_rating;
        // });
        // if (!empty($avg_rating)) {
        //     $users->avg_rating = number_format((float) $avg_rating->avg(), 2, '.', '');
        // } else {
        //     $users->avg_rating = "0.0";
        // }
        // if (!empty($users->additionaldetails) && !empty($users->additionaldetails->profile_pic)) {
        //     $users->additionaldetails->profile_pic = url('sitebucket/userProfile') . '/' . $users->additionaldetails->profile_pic;
        // }
        // if (!empty($users->additionaldetails) && !empty($users->additionaldetails->payment_accept)) {
        //     $users->additionaldetails->payment_accept = explode(',', $users->additionaldetails->payment_accept);
        // }

        return response()->json(['status' => 200, 'msg' => "Data fetch!", 'data' => $this->commonDatasend(Auth::user()->id), 'currentUser' => Auth::user()]);
    }
    public function genealProfileUpdateData(Request $request)
    {
        $input = get_api_key($this->request);
        if (!empty($input['type'] && $input['type'] == 'generalfrom')) {
            $valid = Validator::make($input, [
                'bussiness_name' => "required",
            ]);
            if ($valid->fails()) {
                return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
            } else {
                $update = FrontUsers::where('id', Auth::user()->id)->update([
                    'business_name' => $input['bussiness_name'],
                ]);
                if (!empty($input['profile_pic'])) {
                    $additonal_d = UserBusinessDetails::where('supplier_id', Auth::user()->id)->first();
                    if (isset($additonal_d) && $additonal_d->profile_pic) {
                        $path = 'sitebucket/userProfile/';
                        if (file_exists(public_path($path . '/' . $additonal_d->profile_pic))) {
                            unlink(public_path($path . '/' . $additonal_d->profile_pic));
                        }
                    }
                    $image_parts = explode(";base64,", $input['profile_pic']);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $image_name = rand(99, 999999999) . '-' . time() . '.' . $image_type;
                    $destinationPath = public_path('sitebucket/userProfile/');
                    file_put_contents($destinationPath . $image_name, $image_base64);
                    $input['profile_pic'] = $image_name;
                    $storedata = UserBusinessDetails::where('supplier_id', Auth::user()->id)->update([
                        'profile_pic' => @$input['profile_pic'],
                    ]);
                }
                return response()->json(['status' => 200, 'msg' => "Profile Updated successfully!", 'data' => $this->commonDatasend(Auth::user()->id)]);
            }
        } else {
            return response()->json(['status' => 400, 'msg' => "Api Type is required!", 'data' => array()]);
        }
    }

    /* Supplier Details information based on the id for public profile*/
    public function SupplierDetails($id)
    {
        return response()->json(['status' => 200, 'msg' => "Data fetch!", 'data' => $this->commonDatasend($id)]);
    }

    /* API : Save Quote requests */

    public function RequestQuoteConversation(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, [
            'id' => "required|exists:quote_requests",
            'include_cost' => "required",
            'cost_estimation' => "required",
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            // dd($request->file('attachment'));
            $uploadfilename = '';
            $destinationPath = public_path('sitebucket/quoteRequestsDocuments/');
            if (!empty($request->file('attachment'))) {
                $current = \Carbon\Carbon::now()->format('YmdHs');
                $file = $request->file('attachment');
                $uploadfilename = $current . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $current . '_' . $file->getClientOriginalName());
            }
            if ($input['conv_id'] && $input['conv_id'] != '') {
                if (!empty($uploadfilename) && !empty($request->file('attachment'))) {
                    $getdata = QuoteRequestConversationBucket::where('qr_c_id', $input['conv_id'])->first();
                    if ($getdata) {
                        if (file_exists($destinationPath . $getdata->attachment)) {
                            unlink($destinationPath . $getdata->attachment);
                        }
                        QuoteRequestConversationBucket::where('qr_c_id', $input['conv_id'])->update([
                            'attachment' => $uploadfilename
                        ]);
                    } else {
                        QuoteRequestConversationBucket::create([
                            'qr_c_id' => $input['conv_id'],
                            'attachment' => $uploadfilename
                        ]);
                    }
                }
                $update = QuoteRequestConversation::where('id', $input['conv_id'])->update([
                    'include_cost' => $input['include_cost'],
                    'cost_estimation' => $input['cost_estimation'],
                    'message' => @$input['message'],
                    'organising_event' => @$input['organising_event'],
                ]);

                $quote_requests = QuoteRequests::where('id', $input['id'])->first();
                if ($quote_requests) {
                    /* START : Event notification send */
                    $event_type = EventTypes::where('id', $quote_requests->event_type_id)->first();
                    $event_message = 'Event quote is updated.';
                    $event_title = $event_type->title . ' in ' . $quote_requests->location . ' event quote is updated.';
                    $this->notificationCreate(Auth::user()->id, $quote_requests->user_id, 'Event', $event_title, $event_message);
                    $this->notificationCreate(Auth::user()->id, Auth::user()->id, 'Event', $event_title, $event_message);
                    /* END : Event notification send */
                }
                return response()->json(['status' => 200, 'msg' => "Quote updated successfully.", 'data' => $this->commanrequestDetails($input['id'])]);
            } else {
                $create = QuoteRequestConversation::create([
                    'quote_request_id' => $input['id'],
                    'include_cost' => $input['include_cost'],
                    'cost_estimation' => $input['cost_estimation'],
                    'message' => @$input['message'],
                    'user_id' => @$input['user_id'],
                    'organiser' => @$input['organiser'],
                    'organising_event' => @$input['organising_event'],
                ]);
                $req = QuoteRequests::where('id', $input['id'])->update([
                    'main_type' => 'quotes',
                ]);
                $req = QuoteRequestSupplier::where('quote_request_id', $input['id'])->where('supplier_id', Auth::user()->id)->update([
                    'isQuote' => 'quotes',
                ]);
                if (!empty($uploadfilename) && !empty($request->file('attachment'))) {
                    $c = QuoteRequestConversationBucket::create([
                        'qr_c_id' => $create->id,
                        'attachment' => $uploadfilename
                    ]);
                }

                $quote_requests = QuoteRequests::where('id', $input['id'])->first();
                if ($quote_requests) {
                    /* START : Event notification send */
                    $event_type = EventTypes::where('id', $quote_requests->event_type_id)->first();
                    $event_message = 'Event quote added.';
                    $event_title = $event_type->title . ' in ' . $quote_requests->location . ' event in quote added.';
                    $this->notificationCreate(Auth::user()->id, $quote_requests->user_id, 'Event', $event_title, $event_message);
                    $this->notificationCreate(Auth::user()->id, Auth::user()->id, 'Event', $event_title, $event_message);
                    /* END : Event notification send */
                }
                return response()->json(['status' => 200, 'msg' => "Quote send successfully.", 'data' => $this->commanrequestDetails($input['id'])]);
            }
        }
    }
    public function SupplierServiceChange(Request $request)
    {
        $input = get_api_key($this->request);
        $valid = Validator::make($input, []);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            if (!empty($input['services'])) {
                $removeCheck = SupplierService::where('supplier_id', Auth::user()->id)->whereNotIn('service_id', $input['services'])->pluck('id')->toArray();
                /* Find */
                $removeData = SupplierService::whereIn('id', $removeCheck)->delete();
                foreach ($input['services'] as $key => $value) {
                    $check = SupplierService::where('supplier_id', Auth::user()->id)->where('service_id', $value)->first();
                    if (empty($check)) {
                        SupplierService::create([
                            'supplier_id' => Auth::user()->id,
                            'service_id' => $value,
                        ]);
                    }
                }
                return response()->json(['status' => 200, 'msg' => "Service Updated successfully!", 'data' => $this->commonDatasend(Auth::user()->id)]);
            } else {
                return response()->json(['status' => 400, 'msg' => "Service not found!", 'data' => array()]);
            }
        }
    }
    /* Common Response For all the API */
    public function commonDatasend($userid)
    {
        $users = FrontUsers::with('userBuckets')->with('additionaldetails')->with('supplierservice.serviceDetails')
            ->where('id', $userid)
            ->with('reviewmaster.responseBy.additionaldetails')
            ->with(['reviewmaster.reviewsBy' => function ($q) {
                $q->select('id', 'first_name', 'last_name');
            }])
            ->with('reviewmaster.ratingfetch.rattingTypes')->withCount('reviewmaster')->first();

        if ($users && $users->userBuckets) {
            $users->userBuckets->map(function ($usbucket) {
                if ($usbucket->type == 'photos' || $usbucket->type == 'logo') {
                    $usbucket->media_url = url('sitebucket/business') . '/' . $usbucket->media_url;
                }
                return $usbucket;
            });
        }
        $users->userProfileBaseUrl = url('sitebucket/userProfile');
        $users->videoUrl = UserBucket::select('media_url', 'media_description')->where('supplier_id', $userid)->where('type', 'video')->first();
        $serviceName = $users->supplierservice->map(function ($supplierser) {
            return $supplierser->serviceDetails->id;
        });
        $users->serviceIds = $serviceName;

        $serviceName = $users->supplierservice->map(function ($supplierser) {
            return $supplierser->serviceDetails->name;
        });
        $users->serviceName = $serviceName;

        $avg_rating = $users->reviewmaster->map(function ($reviewmaster) {
            return $reviewmaster->ratingfetch->avg('rating');
        });


        $avg_ratingindi = $users->reviewmaster->map(function ($reviewmasterindi) {
            $avg_indi = $reviewmasterindi->ratingfetch->avg('rating');
            if (!empty($avg_indi)) {
                $final_rating = number_format((float) $avg_indi, 2, '.', '');
            } else {
                $final_rating = 0.0;
            }
            return $reviewmasterindi->individual_ratting = $final_rating;
        });

        if (!empty($avg_rating)) {
            $users->avg_rating = number_format((float) $avg_rating->avg(), 2, '.', '');
        } else {
            $users->avg_rating = "0.0";
        }
        if (!empty($users->additionaldetails) && !empty($users->additionaldetails->profile_pic)) {
            $users->additionaldetails->profile_pic = url('sitebucket/userProfile') . '/' . $users->additionaldetails->profile_pic;
        }
        if (!empty($users->additionaldetails) && !empty($users->additionaldetails->payment_accept)) {
            $users->additionaldetails->payment_accept = explode(',', $users->additionaldetails->payment_accept);
        }
        $rattingloop = RattingTypes::where('status', 'active')->get();
        $pluckmaster = ReviewsMaster::where('supplier_id', $userid)->pluck('id')->toArray();

        $rtl = $rattingloop->map(function ($ratt) use ($pluckmaster) {
            if (!empty($pluckmaster)) {
                $avg_rat = ReviewRatings::whereIn('review_id', $pluckmaster)->where('rating_type_id', $ratt->id)->avg('rating');
                $ratt->avg_ratting = number_format((float) $avg_rat, 2, '.', '');
            } else {
                $ratt->avg_ratting = "0.0";
            }
            return $ratt;
        });
        $users->mainTypewiseratting = $rtl;

        $totalStar = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0
        ];
        $avg_main_percentage = $users->reviewmaster->map(function ($avg_m) {
            return (int) round($avg_m->individual_ratting);
        });
        foreach ($avg_main_percentage as $k => $v) {
            if (isset($totalStar[$v])) {
                $totalStar[$v] = $totalStar[$v] + 1;
            }
        }
        $users->percetageWise = $totalStar;
        return $users;
    }

    public function quoteRequestConversationMessage(Request $request)
    {
        $input = $request->all();
        $valid = Validator::make($input, [
            'message' => 'required',
            'qr_c_id' => 'required'
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            QuoteRequestConversationMessage::create([
                'qr_c_id' => $input['qr_c_id'],
                'user_id' => Auth::user()->id,
                'message' => $input['message'],
            ]);

            $quote_requests = QuoteRequests::where('id', $input['quote_id'])->first();
            if ($quote_requests) {
                /* START : Event notification send */
                $event_type = EventTypes::where('id', $quote_requests->event_type_id)->first();
                $event_message = 'Event quote message.';
                $event_title = $event_type->title . ' in ' . $quote_requests->location . ' - ' . $input['message'];
                $this->notificationCreate(Auth::user()->id, $quote_requests->user_id, 'Event', $event_title, $event_message);
                /* END : Event notification send */
            }

            return response()->json(['status' => 200, 'msg' => "Successfully send!", 'data' => $this->commanrequestQuoteDetails($input['quote_id']), 'requestDetails' => $this->commanrequestDetails($input['quote_id'])]);
        }
    }

    public function completedEventRequest(Request $request)
    {
        $input = $request->all();
        $valid = Validator::make($input, [
            'quote_id' => 'required'
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $quote_requests = QuoteRequests::where('id', $input['quote_id'])->first();
            if ($quote_requests) {
                QuoteRequests::where('id', $input['quote_id'])->update(['main_type' => 'completed']);
                QuoteRequestSupplier::where('quote_request_id', $input['quote_id'])->where('isQuote', 'upcoming')->update(['isQuote' => 'completed']);

                /* START : Event notification send */
                $event_type = EventTypes::where('id', $quote_requests->event_type_id)->first();
                $quote_request_suppliers_completed = QuoteRequestSupplier::where('quote_request_id', $input['quote_id'])->where('isQuote', 'completed')->get();
                /* Event supplier reject notification send */
                foreach ($quote_request_suppliers_completed as $key => $value) {
                    $event_message = 'Event is completed.';
                    $event_title = $event_type->title . ' in ' . $quote_requests->location . ' event is completed.';
                    $this->notificationCreate(Auth::user()->id, $value->supplier_id, 'Event', $event_title, $event_message);
                }
                /* END : Event notification send */

                return response()->json(['status' => 200, 'msg' => "Successfully send!", 'data' => $this->commanrequestQuoteDetails($input['quote_id']), 'requestDetails' => $this->commanrequestDetails($input['quote_id'])]);
            } else {
                return response()->json(['status' => 400, 'msg' => "Event not found!", 'data' => []]);
            }
        }
    }

    public function chosenSupplier(Request $request)
    {
        $input = $request->all();
        $valid = Validator::make($input, [
            'quote_id' => 'required',
            // 'supplier_id' => 'required'
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $quote_requests = QuoteRequests::where('id', $input['quote_id'])->first();
            if ($quote_requests) {
                if ($input['supplier_id'] && count($input['supplier_id']) > 0) {
                    QuoteRequests::where('id', $input['quote_id'])->update(['main_type' => 'upcoming', 'is_assigned' => '1']);
                    QuoteRequestSupplier::where('quote_request_id', $input['quote_id'])->whereNotIn('supplier_id', $input['supplier_id'])->update(['isQuote' => 'quotes']);
                    QuoteRequestSupplier::where('quote_request_id', $input['quote_id'])->whereIn('supplier_id', $input['supplier_id'])->update(['isQuote' => 'upcoming']);

                    /* START : Event notification send */
                    $event_type = EventTypes::where('id', $quote_requests->event_type_id)->first();

                    /* Event supplier reject and selected notification send */
                    $quote_request_suppliers_selected = QuoteRequestSupplier::where('quote_request_id', $input['quote_id'])->where('isQuote', 'upcoming')->get();
                    foreach ($quote_request_suppliers_selected as $key => $value) {
                        $event_message = 'Event Assigned.';
                        $event_title = $event_type->title . ' in ' . $quote_requests->location . ' event assigned you.';
                        $this->notificationCreate(Auth::user()->id, $value->supplier_id, 'Event', $event_title, $event_message);
                    }

                    /* END : Event notification send */
                    return response()->json(['status' => 200, 'msg' => "Supplier selected successfully.", 'data' => $this->commanrequestQuoteDetails($input['quote_id']), 'requestDetails' => $this->commanrequestDetails($input['quote_id'])]);
                } else {
                    QuoteRequests::where('id', $input['quote_id'])->update(['main_type' => 'quotes', 'is_assigned' => '1']);
                    QuoteRequestSupplier::where('quote_request_id', $input['quote_id'])->update(['isQuote' => 'quotes']);
                    return response()->json(['status' => 200, 'msg' => "No supplier required!", 'data' => $this->commanrequestQuoteDetails($input['quote_id']), 'requestDetails' => $this->commanrequestDetails($input['quote_id'])]);
                }
            } else {
                return response()->json(['status' => 400, 'msg' => "Event not found!", 'data' => []]);
            }
        }
    }

    public function quoteteRuestNoRequireSuppliers(Request $request)
    {
        $input = $request->all();
        $valid = Validator::make($input, [
            'quote_id' => 'required'
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $quote_requests = QuoteRequests::where('id', $input['quote_id'])->first();
            if ($quote_requests) {
                QuoteRequests::where('id', $input['quote_id'])->update(['main_type' => 'rejected', 'is_assigned' => '0']);
                QuoteRequestSupplier::where('quote_request_id', $input['quote_id'])->update(['isQuote' => 'rejected']);


                $quote_request_suppliers = QuoteRequestSupplier::where('quote_request_id', $input['quote_id'])->get();
                /* START : Event notification send */
                $event_type = EventTypes::where('id', $quote_requests->event_type_id)->first();
                $event_message = 'Event not required.';
                $event_title = $event_type->title . ' in ' . $quote_requests->location . ' event no longer required.';

                /* My event notification send */
                $this->notificationCreate(Auth::user()->id, Auth::user()->id, 'Event', $event_title, $event_message);

                /* Event supplier notification send */
                foreach ($quote_request_suppliers as $key => $value) {
                    $this->notificationCreate(Auth::user()->id, $value->supplier_id, 'Event', $event_title, $event_message);
                }
                /* END : Event notification send */


                return response()->json(['status' => 200, 'msg' => "Successfully send!", 'data' => $this->commanrequestQuoteDetails($input['quote_id']), 'requestDetails' => $this->commanrequestDetails($input['quote_id'])]);
            } else {
                return response()->json(['status' => 400, 'msg' => "Event not found!", 'data' => []]);
            }
        }
    }

    public function notificationCreate($from_user_id, $to_user_id, $type, $title, $message)
    {
        Notification::create([
            'from_user_id' => $from_user_id,
            'to_user_id' => $to_user_id,
            'notification_type' => $type,
            'title' => $title,
            'message' => $message,
            'url' => '',
        ]);
    }

    public function commanNotification($limit)
    {
        $notifications = Notification::with('quotebyfrom')->with('quotebyto')->where('to_user_id', Auth::user()->id)->orderBy('created_at', 'desc')->limit($limit)->get();
        $notifications->transform(function ($noti) {
            if (!empty($noti->created_at)) {
                $noti->date_human = \Carbon\Carbon::createFromTimeStamp(strtotime($noti->created_at))->diffForHumans();
            } else {
                $noti->date_human = "";
            }
            return $noti;
        });
        $totalNotifications = Notification::where('to_user_id', Auth::user()->id)->orderBy('created_at', 'desc')->count();
        return ['notifications' => $notifications, 'totalNotifications' => $totalNotifications];
    }

    public function notification(Request $request)
    {
        $input = $request->all();
        if (Auth::user()) {
            $limit = ($input['limit']) ? $input['limit'] : 1;
            return response()->json(['status' => 200, 'msg' => "Successfully load notifications!", 'data' => $this->commanNotification($limit)]);
        } else {
            return response()->json(['status' => 400, 'msg' => "Unauthorized!", 'data' => []]);
        }
    }

    public function notificationDeleted(Request $request)
    {
        $input = $request->all();
        $valid = Validator::make($input, [
            'id' => 'required'
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            if (Auth::user()) {
                Notification::where('id', $input['id'])->delete();
                $limit = ($input['limit']) ? $input['limit'] : 1;
                return response()->json(['status' => 200, 'msg' => "Successfully load notifications!", 'data' => $this->commanNotification($limit)]);
            } else {
                return response()->json(['status' => 400, 'msg' => "Unauthorized!", 'data' => []]);
            }
        }
    }

    public function reviewSend(Request $request)
    {
        $input = $request->all();
        $valid = Validator::make($input, [
            'title' => 'required'
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $review = ReviewsMaster::create([
                'title' => $input['title'],
                'comment' => $input['comment'],
                'supplier_id' => $input['supplier_id'],
                'quote_id' => $input['quote_id'],
                'user_id' => Auth::user()->id,
                'review_date' => \Carbon\Carbon::now()->format('Y-m-d'),
            ]);
            // $review = 1;
            if ($review) {
                if (!empty($input['images'])) {
                    foreach ($input['images'] as $key => $value) {
                        $image_parts = explode(";base64,", $value);
                        $image_type_aux = explode("image/", $image_parts[0]);
                        $image_type = $image_type_aux[1];
                        $image_base64 = base64_decode($image_parts[1]);
                        $image_name = rand(99, 999999999) . '-' . time() . '.' . $image_type;
                        $destinationPath = public_path('sitebucket/reviews/');
                        file_put_contents($destinationPath . $image_name, $image_base64);
                        $saveImage = $image_name;
                        ReviewsMasterBucket::create([
                            'review_id' => $review->id,
                            'attachment' => $saveImage
                        ]);
                    }
                }
                foreach ($input['rattings'] as $key => $value) {
                    if ($value) {
                        ReviewRatings::create([
                            'review_id' => $review->id,
                            'rating_type_id' => $value['id'],
                            'rating' => $value['rating'],
                        ]);
                    }
                }
            }
            // return response()->json(['status' => 200, 'msg' => "Review send successfully!", 'data' => $this->commanrequestDetails($input['quote_id'])]);
            return response()->json(['status' => 200, 'msg' => "Review send successfully!", 'data' => $this->commanrequestQuoteDetails($input['quote_id']), 'requestDetails' => $this->commanrequestDetails($input['quote_id'])]);
        }
    }

    public function supplierReview($id, $byme = false)
    {
        $reviews = ReviewsMaster::with('ratingfetch')->with('responseBy.additionaldetails')->with('reviewsBy.additionaldetails')->with('reviewsBucket');
        $reviews = $reviews->where('supplier_id', $id);
        if ($byme) {
            $reviews = $reviews->where('user_id', Auth::user()->id);
        }
        $reviews = $reviews->get();
        return $reviews;
    }

    public function ratingTypes(Request $request)
    {
        $ratingTypes = RattingTypes::where('status', 'active')->get();
        return response()->json(['status' => 200, 'msg' => "Successfully load notifications!", 'data' => $ratingTypes]);
    }

    public function supplierReviews($id)
    {
        return response()->json(['status' => 200, 'msg' => "Successfully load data!", 'data' => $this->supplierReview($id)]);
    }

    public function supplierReviewsByme($id)
    {
        return response()->json(['status' => 200, 'msg' => "Successfully load data!", 'data' => $this->supplierReview($id, $byme = true)]);
    }

    public function reviewResponse(Request $request)
    {
        $input = $request->all();
        $valid = Validator::make($input, [
            'response' => 'required'
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            ReviewsMaster::where('id', $input['review_id'])->update([
                'response' => $input['response']
            ]);
            return response()->json(['status' => 200, 'msg' => "Responce send Successfully.", 'data' => $this->commanrequestDetails($input['quote_id'])]);
        }
    }
    public function reviewApproved(Request $request)
    {
        $input = $request->all();
        $valid = Validator::make($input, [
            'review_id' => 'required'
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            ReviewsMaster::where('id', $input['review_id'])->update([
                'is_verified' => '1'
            ]);
            return response()->json(['status' => 200, 'msg' => "Responce send Successfully.", 'data' => []]);
        }
    }

    /* START : Availabilities */
    public function loadAvailabilities()
    {
        $availability = Availability::where('user_id', Auth::user()->id)->first();
        if ($availability) {
            if ($availability->working_days) {
                $availability->working_days = json_decode($availability->working_days);
            }
            if ($availability) {
                $availability->available = json_decode($availability->available);
            }
        }
        return $availability ? $availability : [];
    }

    public function availabilities(Request $request)
    {
        return response()->json(['status' => 200, 'msg' => "Load data Successfully.", 'data' => $this->loadAvailabilities()]);
    }

    public function availabilitiesAdd(Request $request)
    {
        $input = $request->all();
        $is_available = Availability::where('user_id', Auth::user()->id)->first();
        if ($is_available) {
            $is_available->update([
                'available' => json_encode($input),
            ]);
        } else {
            Availability::create([
                'user_id' => Auth::user()->id,
                'available' => json_encode($input),
            ]);
        }
        return response()->json(['status' => 200, 'msg' => "Responce send Successfully.", 'data' => $this->loadAvailabilities()]);
    }
    public function updateWorkingDays(Request $request)
    {
        $input = $request->all();
        $is_available = Availability::where('user_id', Auth::user()->id)->first();
        if ($is_available) {
            $is_available->update([
                'working_days' => json_encode($input),
            ]);
        } else {
            Availability::create([
                'user_id' => Auth::user()->id,
                'working_days' => json_encode($input),
            ]);
        }
        return response()->json(['status' => 200, 'msg' => "Responce send Successfully.", 'data' => []]);
    }
    /* END : Availabilities */

    public function qouteRequestSend(Request $request)
    {
        $data = $request->all();
        // dd($data);
        (new SendQuoteRequestSupplier($data))->handle();
        // dispatch(new SendQuoteRequestSupplier($data));
    }


    function getDistance(Request $request)
    {
        $input = $request->all();
        $valid = Validator::make($input, [
            'addressFrom' => 'required',
            'addressTo' => 'required'
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 400, 'msg' => $valid->errors()->first(), 'data' => array()]);
        } else {
            $addressFrom = @$input['addressFrom'];
            $addressTo = @$input['addressTo'];
            $unit = @$input['unit'];

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
            $distance = [];
            if ($unit == "K") {
                $distance = ['dist' => round($miles * 1.609344, 2), 'dist_type' => 'km'];
            } elseif ($unit == "M") {
                $distance = ['dist' => round($miles * 1609.344, 2), 'dist_type' => 'meters'];
            } else {
                $distance = ['dist' => round($miles, 2), 'dist_type' => 'miles'];
            }

            /* Driving Distance get */
            $dist_url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $latitudeFrom . ',' . $longitudeFrom . '&destinations=' . $latitudeTo . ',' . $longitudeTo . '&sensor=false&key=' . $apiKey;
            $distancematrix = file_get_contents($dist_url);
            // $distancematrix = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$latitudeFrom.','.$longitudeFrom.'&destinations='.$latitudeTo.','.$longitudeTo.'&sensor=false&key=AIzaSyCf3_v7t2l6tkIMHdpiGovpEOtK0aKYjJw');
            $driving_distance = json_decode($distancematrix);
            $final_driving_distance = [];
            if (isset($driving_distance) && $driving_distance->status == 'OK') {
                if (isset($driving_distance->rows)) {
                    foreach ($driving_distance->rows as $key => $value) {
                        foreach ($value->elements as $key => $element) {
                            if (isset($element->status) && $element->status == 'OK') {
                                // array_push($final_driving_distance, $element);
                                $final_driving_distance = $element;
                            }
                        }
                    }
                }
            }

            $finalData = [
                'distance' => $distance,
                'driving_distance' => $final_driving_distance
            ];
            return response()->json(['status' => 200, 'msg' => "Get Distance Successfully.", 'data' => $finalData]);
        }
    }


    public function popularEvents(Request $request)
    {
        $quote_requests = QuoteRequests::with('eventtype')->with('servicelist.servicedetails');
        $quote_requests = $quote_requests->whereHas('servicelist', function ($q) {
            return $q->whereHas('servicedetails', function ($s) {
                return $s->whereHas('category', function ($c) {
                    return $c->where('is_popular', 1);
                });
            });
        });
        $quote_requests = $quote_requests->where('main_type', 'completed');
        $quote_requests = $quote_requests->orderBy('created_at', 'DESC')->limit(10)->get();
        if (count($quote_requests) > 0) {
            $quote_requests->transform(function ($event) {
                $event->servicelist->transform(function ($services) use ($event) {
                    $event['image_url'] = '';
                    if ($services->servicedetails->image) {
                        $event['image_url'] = url('sitebucket/services') . '/' . $services->servicedetails->image;
                    }
                    return $services;
                });
                $event->event_title = $event->eventtype->title;
                return $event;
            });
            return response()->json(['status' => 200, 'msg' => "Popular events load successfully.", 'data' => $quote_requests]);
        } else {
            return response()->json(['status' => 200, 'msg' => "Data not found.", 'data' => []]);
        }
    }

    public function subcriptionPlans()
    {
        $subscriptionPlan = SubscriptionPlan::where('is_active', '1')->get();
        if (count($subscriptionPlan) > 0) {
            return response()->json(['status' => 200, 'msg' => "Subscription Plans load successfully.", 'data' => $subscriptionPlan]);
        } else {
            return response()->json(['status' => 200, 'msg' => "Data not found.", 'data' => []]);
        }
    }

    public function currentUserData()
    {
        $additiondetails = UserBusinessDetails::where('supplier_id', Auth::user()->id)->first();
        if (!empty($additiondetails) && !empty($additiondetails->profile_pic)) {
            $additiondetails->profile_pic = url('sitebucket/userProfile') . '/' . $additiondetails->profile_pic;
        }
        $currentPlan = UsersPlan::with('subscriptionPlan')->where('user_id', Auth::user()->id)->where('is_active', '1')->first();
        $data = Auth::user();
        $data['additionaldetails'] = $additiondetails;
        $data['currentPlan'] = $currentPlan;
        return response()->json(['status' => 200, 'data' => $data]);
    }

    public function paymentNotify(Request $request)
    {
        $input = $request->all();

        $file = public_path('sitebucket/' . 'test.txt');
        if (file_exists($file)) {
            file_put_contents($file, PHP_EOL . PHP_EOL . "----------------------   Notify URL call...   ----------------------", FILE_APPEND | LOCK_EX);
            file_put_contents($file, PHP_EOL . json_encode($input, true), FILE_APPEND | LOCK_EX);
            file_put_contents($file, PHP_EOL . $input['payment_status'], FILE_APPEND | LOCK_EX);
        }


        if ($input['payment_status'] == 'COMPLETE') {
            // If complete, update your application
            // $usersPlan = UsersPlan::where('user_id', $input['m_payment_id'])->count();
            // file_put_contents($file, PHP_EOL . PHP_EOL .  json_encode($usersPlan, true), FILE_APPEND | LOCK_EX);
            // if ($usersPlan && $usersPlan > 0) {
            //     file_put_contents($file, PHP_EOL . "Update Plan ...", FILE_APPEND | LOCK_EX);
            //     try {
            //         UsersPlan::where('user_id', $input['m_payment_id'])->update([
            //             'subscription_plan_id' => isset($input['custom_int1']) ? $input['custom_int1'] : '',
            //             'payment_id' => isset($input['pf_payment_id']) ? $input['pf_payment_id'] : '',
            //             'signature' => isset($input['signature']) ? $input['signature'] : '',
            //             'token' => isset($input['token']) ? $input['token'] : '',
            //             'billing_date' => isset($input['billing_date']) ? $input['billing_date'] : '',
            //             'is_active' => '1'
            //         ]);
            //     } catch (Exception $e) {
            //         file_put_contents($file, PHP_EOL . PHP_EOL . json_encode($e, true), FILE_APPEND | LOCK_EX);
            //     }
            // } else {
            file_put_contents($file, PHP_EOL . "Create Plan ...", FILE_APPEND | LOCK_EX);
            try {
                UsersPlan::where('user_id', $input['m_payment_id'])->update([
                    'is_active' => '0'
                ]);

                UsersPlan::create([
                    'user_id' => isset($input['m_payment_id']) ? $input['m_payment_id'] : '',
                    'subscription_plan_id' => isset($input['custom_int1']) ? $input['custom_int1'] : '',
                    'payment_id' => isset($input['pf_payment_id']) ? $input['pf_payment_id'] : '',
                    'signature' => isset($input['signature']) ? $input['signature'] : '',
                    'token' => isset($input['token']) ? $input['token'] : '',
                    'billing_date' => isset($input['billing_date']) ? $input['billing_date'] : '',
                    'is_active' => '1'
                ]);
            } catch (Exception $e) {
                file_put_contents($file, PHP_EOL . PHP_EOL . json_encode($e, true), FILE_APPEND | LOCK_EX);
            }
            // }
        } else if ($input['payment_status'] == 'CANCELLED') {
            // If cancel, then cancel subscription
            UsersPlan::where('payment_id', $input['pf_payment_id'])->update([
                'is_active' => '0'
            ]);
            file_put_contents($file, PHP_EOL . "Cancel Plan ...", FILE_APPEND | LOCK_EX);
        } else {
            // If unknown status, do nothing (which is the safest course of action)
            file_put_contents($file, PHP_EOL . "Unknown status ...", FILE_APPEND | LOCK_EX);
        }
    }

    public function freePlan(Request $request)
    {
        $input = $request->all();
        if ($input && $input['user_id']) {
            UsersPlan::where('user_id', $input['user_id'])->update([
                'is_active' => '0'
            ]);

            UsersPlan::create([
                'user_id' => isset($input['user_id']) ? $input['user_id'] : '',
                'subscription_plan_id' => isset($input['subcription_plan_id']) ? $input['subcription_plan_id'] : '',
                'billing_date' => isset($input['billing_date']) ? $input['billing_date'] : '',
                'is_active' => '1'
            ]);

            return response()->json(['status' => 200, 'data' => []]);
        } else {
            return response()->json(['status' => 400, 'msg' => "User is require.", 'data' => []]);
        }
    }

    public function paymentReturn(Request $request)
    {
        $url = env("FRONT_URL", "http://192.249.121.94/~team2/eventmenow/#/");
        // $url = "http://localhost:4200/#/";
        return redirect()->intended($url . 'subscription-plan?is_success=1');
    }

    public function paymentCancel(Request $request)
    {
        $url = env("FRONT_URL", "http://192.249.121.94/~team2/eventmenow/#/");
        // $url = "http://localhost:4200/#/";
        return redirect()->intended($url . 'subscription-plan?is_cancel=1');
    }

    public function paymentSubcriptionCancel(Request $request)
    {
        $input = $request->all();

        if (!isset($input['token'])) {
            return response()->json(['status' => 200, 'msg' => "Token id is require.", 'data' => []]);
            exit;
        }

        if (!isset($input['merchant-id'])) {
            return response()->json(['status' => 200, 'msg' => "Merchant id is require.", 'data' => []]);
            exit;
        }

        if (!isset($input['amount'])) {
            return response()->json(['status' => 200, 'msg' => "amount is require.", 'data' => []]);
            exit;
        }

        $pfData = $input;

        $timestamp = date('Y-m-d') . 'T' . date('H:i:s');
        $pfData['timestamp'] = $timestamp;

        ksort($pfData);

        $pfParamString = '';
        foreach ($pfData as $key => $val) {
            if (!empty($val) && $key != 'api_action' && $key != 'submit' && $key != 'token') {
                $pfParamString .= $key . '=' . urlencode(trim($val)) . '&';
            }
        }

        $pfParamString = substr($pfParamString, 0, -1);

        $signature = md5($pfParamString);

        $action = '';
        if ($pfData['api_action']) {
            $action = $pfData['api_action'];
        }

        $token = ($pfData['token'] ? $pfData['token'] . '/' : '');

        $payload = '';
        $exclude = array('api_action', 'submit', 'token', 'passphrase', 'version', 'merchant-id', 'timestamp');
        foreach ($pfData as $key => $val) {
            if (!empty($val) && !in_array($key, $exclude)) {
                $payload .= $key . '=' . urlencode(trim($val)) . '&';
            }
        }
        $payload = substr($payload, 0, -1);
        $ch = curl_init('https://api.payfast.co.za/subscriptions/' . $token . $action . '?testing=true');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'version: ' . $pfData['version'],
            'merchant-id: ' . $pfData['merchant-id'],
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        ));
        $response = curl_exec($ch);
        curl_close($ch);

        UsersPlan::where('user_id', $request->current_user_id)->update([
            'is_active' => '0'
        ]);

        return response()->json(['status' => 200, 'msg' => "Subscription Plans load successfully.", 'data' => $response]);
    }
    
    public function updateNotification(Request $request)
    {
        Notification::where('to_user_id', Auth::user()->id)->where('is_read', '0')->update(['is_read' => '1']);
        return response()->json(['status' => 200, 'msg' => "Notification Updates.", 'data' => []]);
    }
}
