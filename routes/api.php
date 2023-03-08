<?php

use Illuminate\Http\Request;

header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function () {
    Route::group(['middleware' => 'cors'], function () {
    });
    Route::post('categoryfetch', 'API\APIV1Controller@categoryFetch');
    Route::post('sliderfetch', 'API\APIV1Controller@sliderFetch');
    Route::post('login', 'API\APIV1Controller@login');
    Route::post('register', 'API\APIV1Controller@register');
    Route::get('user/verify-check/{token}', 'API\APIV1Controller@verifyUserCheck');
    Route::get('user/verify/{token}', 'API\APIV1Controller@verifyUser');

    Route::post('servicefetch', 'API\APIV1Controller@serviceFetch');
    Route::get('recentservicefetch', 'API\APIV1Controller@recentServiceFetch');
    Route::post('testimonialfetch', 'API\APIV1Controller@testimonialFetch');
    Route::get('serviceall', 'API\APIV1Controller@serviceAll');
    Route::post('servicefetchbycategoy', 'API\APIV1Controller@serviceFetchByCategoy');
    Route::get('onlyservices', 'API\APIV1Controller@onlyServices');
    Route::post('onlyservicesbyid', 'API\APIV1Controller@onlyServicesById');
    Route::get('stepfetch', 'API\APIV1Controller@stepFetch');
    Route::post('register', 'API\APIV1Controller@registration');
    Route::post('getservice', 'API\APIV1Controller@getService');
    Route::post('updateaccountdetails', 'API\APIV1Controller@updateAccountdetails');
    Route::post('getcmspages', 'API\APIV1Controller@getCmsPages');
    Route::post('storecontact', 'API\APIV1Controller@storeContact');
    Route::post('forgotpassword', 'API\APIV1Controller@forgotPassword');
    Route::post('resetpassword', 'API\APIV1Controller@resetPassword');
    Route::post('bloglist', 'API\APIV1Controller@blogList');
    Route::post('blogdetail', 'API\APIV1Controller@blogDetail');
    Route::post('faqfetch', 'API\APIV1Controller@getFaqfetch');
    Route::get('listeventtypes', 'API\APIV1Controller@listEventTypes');
    Route::post('qouterequestsend', 'API\APIV1Controller@qouteRequestSend');
    Route::get('ratingtypes', 'API\APIV1Controller@ratingTypes');
    Route::get('popularevents', 'API\APIV1Controller@popularEvents');

    Route::group(['middleware' => ['auth:api', 'verified']], function () {
        Route::post('updatebusinessdetails', 'API\APIV1Controller@updateBusinessdetails');
        Route::post('updatebusinessdetailsother', 'API\APIV1Controller@updateBusinessdetailsOther');
        Route::post('updatelocationdetails', 'API\APIV1Controller@updateLocationdetails');
        Route::post('updategeneraldetails', 'API\APIV1Controller@updateGeneraldetails');
        Route::post('updatemediadetails', 'API\APIV1Controller@updateMediadetails');
        Route::post('removemediadetails', 'API\APIV1Controller@removeMediadetails');
        Route::post('addservicesupplier', 'API\APIV1Controller@addServiceSupplier');
        Route::post('removeservicesupplier', 'API\APIV1Controller@removeServiceSupplier');
        Route::post('editservicesupplier', 'API\APIV1Controller@editServiceSupplier');
        Route::get('listservicesupplier', 'API\APIV1Controller@listServiceSupplier');
        Route::get('listservicesupplier/{id}', 'API\APIV1Controller@listServiceSupplier');
        Route::post('serviceeventtypesupdate', 'API\APIV1Controller@ServiceEventTypesUpdate');
        Route::post('supplierservicecontent', 'API\APIV1Controller@SupplierServiceContent');
        Route::post('profileupdate', 'API\APIV1Controller@profileUpdate');
        Route::get('getprofiledata', 'API\APIV1Controller@getprofileData');
        Route::get('myrequests', 'API\APIV1Controller@myRequests');
        Route::get('onlymyrequests', 'API\APIV1Controller@onlyMyRequests');
        Route::post('archiverequest', 'API\APIV1Controller@archiveRequest');
        Route::post('requestdetails', 'API\APIV1Controller@requestDetails');
        Route::post('requestquotedetails', 'API\APIV1Controller@requestQuoteDetails');
        Route::get('listeventtypessupplier', 'API\APIV1Controller@listEventTypesSupplier');
        Route::post('supplierservicestepanswer', 'API\APIV1Controller@SupplierServiceStepAnswers');
        Route::get('summaryprofiledata', 'API\APIV1Controller@SummaryProfileData');
        Route::post('genealprofileupdatedata', 'API\APIV1Controller@genealProfileUpdateData');
        Route::post('requestquoteconversation', 'API\APIV1Controller@RequestQuoteConversation');
        Route::post('quoterequestconversationmessage', 'API\APIV1Controller@quoteRequestConversationMessage');
        Route::post('supplierservicechange', 'API\APIV1Controller@SupplierServiceChange');
        Route::post('quotetequestnorequiresupplier', 'API\APIV1Controller@quoteteRuestNoRequireSuppliers');
        Route::post('chosensupplier', 'API\APIV1Controller@chosenSupplier');
        Route::post('completedeventrequest', 'API\APIV1Controller@completedEventRequest');
        Route::post('notification', 'API\APIV1Controller@notification');
        Route::post('notificationdelete', 'API\APIV1Controller@notificationDeleted');
        Route::post('reviewapproved', 'API\APIV1Controller@reviewApproved');
        Route::post('reviewsend', 'API\APIV1Controller@reviewSend');
        Route::post('reviewresponse', 'API\APIV1Controller@reviewResponse');
        Route::get('supplierreviews/{supplier_id}', 'API\APIV1Controller@supplierReviews');
        Route::get('supplierreviews/byme/{supplier_id}', 'API\APIV1Controller@supplierReviewsByme');
        Route::get('availabilities', 'API\APIV1Controller@availabilities');
        Route::post('availabilitiesadd', 'API\APIV1Controller@availabilitiesAdd');
        Route::post('updateworkingdays', 'API\APIV1Controller@updateWorkingDays');
        Route::post('updatenotification', 'API\APIV1Controller@updateNotification');
        Route::get('currentuserdata', 'API\APIV1Controller@currentUserData');
    });

    Route::get('subcriptionplans', 'API\APIV1Controller@subcriptionPlans');
    Route::post('freeplan', 'API\APIV1Controller@freePlan');
    Route::post('payment/notify', 'API\APIV1Controller@paymentNotify');
    Route::get('payment/return', 'API\APIV1Controller@paymentReturn');
    Route::get('payment/cancel', 'API\APIV1Controller@paymentCancel');
    Route::post('payment/subcription/cancel', 'API\APIV1Controller@paymentSubcriptionCancel');

    Route::post('getdistance', 'API\APIV1Controller@getDistance');
    Route::get('supplierdetails/{id}', 'API\APIV1Controller@SupplierDetails');
    Route::post('quote_request', 'API\APIV1Controller@qouteRequest');
});
