<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */


use App\Http\Controllers\DynamicSitemapController;

Route::group(['middleware'=>'HttpsProtocol'], function(){ 

Route::get('/blog-details/sitemap.xml','DynamicSitemapController@blogDetail');
Route::get('sitemap.xml','DynamicSitemapController@index');
Route::get('/resource-details/sitemap.xml','DynamicSitemapController@resourceDetail');
Route::get('/provider/sitemap.xml','DynamicSitemapController@provider');
Route::get('/aboutus/sitemap.xml', 'DynamicSitemapController@singleroutes');
Route::get('/explore/sitemap.xml', 'DynamicSitemapController@explore');

/*Route::group(['middleware'=>'get.expiry'], function(){ */
/*Route::group(['middleware'=>'HtmlMinifier'], function(){ */

Route::get('login/locked', 'Auth\LoginController@locked')->middleware('auth')->name('login.locked');
Route::post('login/locked', 'Auth\LoginController@unlock')->name('login.unlock');
Route::get('/', 'FrontController@index');
Route::post('get/locationdata', 'FrontController@getlocationdata')->name('get.locationdata');
Route::get('stripecreate/geturl', 'PaymentController@getstripeurl')->name('stripecreate.geturl');
Route::get('stripeconnect/store', 'PaymentController@stripestore')->name('stripecreate.store');
Route::get('/stripeconnect', 'PaymentController@stripeconnect')->name('stripecreate');

Route::post('/stripeconnect/provider-payment-intent', 'PaymentController@providerpaymentintent')->name('providerpaymentintent');
Route::post('/stripeconnect/create-payment-intent', 'PaymentController@createpaymentintent')->name('createpaymentintent');
Route::post('/stripeconnect/request-payment-intent', 'PaymentController@requestpaymentintent')->name('requestpaymentintent');
Route::post('/stripeconnect/create-monthly-yearly-payment-intent', 'PaymentController@createmonthlyyearlypaymentintent')->name('createmonthlyyearlrpaymentintent');
Route::post('/stripeconnect/request-monthly-yearly-payment-intent', 'PaymentController@requestmonthlyyearlypaymentintent')->name('requestmonthlyyearlrpaymentintent');
Route::post('/stripeconnect/create-package-deal-payment-intent', 'PaymentController@createpackagedealpaymentintent')->name('createpackagedealpaymentintent');
Route::post('/stripeconnect/request-package-deal-payment-intent', 'PaymentController@requestpackagedealpaymentintent')->name('requestpackagedealpaymentintent');
Route::post('/stripeconnect/request-package-zero-deal-payment-intent', 'PaymentController@requestpackagedealzeropaymentintent')->name('requestpackagedealzeropaymentintent');
Route::post('/stripeconnect/createpaymentsave', 'PaymentController@createpaymentsave')->name('createpaymentsave');
Route::post('/stripeconnect/createzeropaymentsave', 'PaymentController@createzeropaymentsave')->name('createzeropaymentsave');
Route::post('/stripeconnect/requestpaymentsave', 'PaymentController@requestpaymentsave')->name('requestpaymentsave');
Route::post('/stripeconnect/requestzeropaymentsave', 'PaymentController@requestzeropaymentsave')->name('requestzeropaymentsave');
Route::post('/stripeconnect/createmonthlyyearlypaymentsave', 'PaymentController@createmonthlyyearlypaymentsave')->name('createmonthlyyearlypaymentsave');
Route::post('/stripeconnect/createpackagedealpaymentsave', 'PaymentController@createpackagedealpaymentsave')->name('createpackagedealpaymentsave');
Route::post('/stripeconnect/createpackagedealzeropaymentsave', 'PaymentController@createpackagedealzeropaymentsave')->name('createpackagedealzeropaymentsave');
Route::group(['prefix' => 'admin'], function () {
    Auth::routes();
    // Route::group(['middleware' => ['auth', 'auth.lock']], function () {
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/', 'HomeController@index');

        /* Start : Profile Changes  */
        // Route::resource('profile', 'Admin\UserController', array("as" => "admin"));
        Route::get('profile/changepassword', 'Admin\UserController@changePassword')->name('profile.changepassword');
        Route::post('profile/changepassword/save', 'Admin\UserController@changePasswordSave')->name('profile.changepassword.save');
        Route::resource('profile', 'Admin\UserController');
        /* END : Profile Changes  */

        /* Start : Dynamic Questions  */
        Route::get('/question/status/{id}', 'Admin\RequestStepsController@statusChange')->name('question.status');
        Route::get('/question/getall', 'Admin\RequestStepsController@getAll')->name('question.getall');
        Route::resource('/question', 'Admin\RequestStepsController');
        /* END : Dynamic Questions  */

       /* Start : Subcription Plans  */
       Route::get('/subcriptionplan/status/{id}', 'Admin\SubcriptionPlanController@statusChange')->name('subcriptionplan.status');
       Route::get('/subcriptionplan/getall', 'Admin\SubcriptionPlanController@getAll')->name('subcriptionplan.getall');
       Route::get('/subcriptionplan/provider_transaction_history', 'Admin\SubcriptionPlanController@provider_transaction_history')->name('subcriptionplan.providertransactionhistory');
       Route::get('/subcriptionplan/gettransactionall', 'Admin\SubcriptionPlanController@gettransactionall')->name('subcriptionplan.gettransactionall');
       Route::resource('/subcriptionplan', 'Admin\SubcriptionPlanController');
       /* END : Subcription Plans  */

         /* Start : Next Steps   */
         Route::get('/nextsteps/getall', 'NextStepsController@getAll')->name('nextsteps.getall');
         Route::resource('/nextsteps','NextStepsController');
         /* END : Next Steps  */
         /* Start : Next Steps Sections   */
         Route::get('/nextsections/getall', 'NextStepsSectionsController@getAll')->name('nextsections.getall');
         Route::resource('/nextsections','NextStepsSectionsController');
         /* END : Next Steps Sections  */
         
        /* Start : Admin view Newsletter Subscription   */
        Route::get('/newsletters/getall', 'NewsletterSubscriptionController@getAll')->name('newsletters.getall');
        Route::resource('/newsletters','NewsletterSubscriptionController');
        /* END : Admin view Newsletter Subscription  */

        /* Start : Explore Menu Items   */
        Route::get('/exploreItems/getall', 'ExploreMenuController@getAll')->name('exploreItems.getall');
        Route::resource('/exploreItems','ExploreMenuController');
        /* END : Explore Menu Items  */
		/* Start : Explore Keywors   */
        Route::get('/exploreKeywords/getall', 'ExploreKeywordsController@getAll')->name('exploreKeywords.getall');
        Route::resource('/exploreKeywords','ExploreKeywordsController');
        /* END : Explore Keywors  */

        
    });
});


Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => ''], function () {

    Route::get('/login', 'FrontAuthAuth\LoginController@showLoginForm')->name('front.login');
    Route::post('/login', 'FrontAuthAuth\LoginController@login')->name('front.loggedIn');
    Route::post('/logout', 'FrontAuthAuth\LoginController@logout')->name('front.logout');
	Route::get('/logout', 'FrontAuthAuth\LoginController@logout')->name('front.logout');
    Route::get('/auth/redirect/{provider}', 'FrontAuthAuth\LoginController@redirect');
    Route::get('/auth/fbredirect/{provider}', 'FrontAuthAuth\LoginController@providerfbredirect');
    Route::get('/atauth/fbredirect/{provider}', 'FrontAuthAuth\LoginController@atheletefbredirect');

    Route::get('/callback/google', 'FrontAuthAuth\LoginController@callback');
    Route::any('/callback/facebook', 'FrontAuthAuth\LoginController@facebookCallback');

    Route::get('auth/google', 'FrontAuthAuth\LoginController@redirectToGoogle');
    Route::get('atauth/google', 'FrontAuthAuth\LoginController@atredirectToGoogle');
    Route::get('auth/google/callback', 'FrontAuthAuth\LoginController@handleGoogleCallback');
    Route::get('/register', 'FrontAuthAuth\RegisterController@showRegistrationForm')->name('register');
    Route::get('/provider-register', 'FrontAuthAuth\RegisterController@showTrainerRegistrationForm')->name('trainer.register');
    Route::post('/register', 'FrontAuthAuth\RegisterController@register')->name('front.register');
    Route::post('/provider_register', 'FrontAuthAuth\RegisterController@provider_register')->name('front.provider.register');
    Route::get('/provider_register_payment', 'FrontAuthAuth\RegisterController@provider_register')->name('front.provider.register.google');
    Route::get('/provideraccountinformationdetails/{plan_type}', 'FrontAuthAuth\RegisterController@accountinformationdetails');
    Route::post('/createnewproviderpaymentintent', 'FrontAuthAuth\RegisterController@createnewproviderpaymentintent');
    Route::get('/bussiness-name-check/{name}', 'FrontAuthAuth\RegisterController@bussiness_name_check')->name('front.bussiness.name.check');
    Route::get('register/verify/{confirmationCode}', [
        'as'   => 'confirmation_path',
        'uses' => 'FrontAuthAuth\RegisterController@confirm',
    ]);

    Route::post('/password/email', 'FrontAuthAuth\ForgotPasswordController@sendResetLinkEmail')->name('front.password.request');
    Route::post('/password/reset', 'FrontAuthAuth\ResetPasswordController@reset')->name('front.password.email');
    Route::get('/password/reset', 'FrontAuthAuth\ForgotPasswordController@showLinkRequestForm')->name('front.password.reset');
    Route::get('/password/reset/{token}/{email}', 'FrontAuthAuth\ResetPasswordController@showResetForm');
    Route::get('/aboutus', 'FrontController@aboutus')->name('aboutus');

    Route::get('/blog', 'FrontController@blogs')->name('blogs');
    Route::get('/blog-details/{slug}', 'FrontController@blogsDetails')->name('blogs-details');
    Route::get('/blogs-list', 'FrontController@blogsList')->name('blogs-list');
    Route::get('/events', 'TrainerEventController@eventCalendar')->name('event-calendar');
    Route::post('/events', 'TrainerEventController@eventCalendar')->name('search-event');

    Route::get('contactus', 'FrontController@contactus')->name('contactuspage');
    Route::post('/contactus', 'FrontController@contactussave')->name('contactussave');
    Route::get('/terms-conditions', 'FrontController@terms_conditions')->name('terms.conditions');
    Route::match(['get', 'post'], '/explore', [
        'as'   => 'exploreservices',
        'uses' => 'FrontController@exploreservicessearch']);
    Route::get('/login/{booknow}', 'FrontController@bookNowLogin')->name('booknow.login');
    Route::post('/webhookcustom', 'FrontController@webhookcustom')->name('customer.webhookcustom');
    Route::post('/send_provider_email', 'FrontUserCountroller@providerEmailUs')->name('trainer.provider_emailus');
    Route::post('/email_verify', 'FrontUserCountroller@emailVerify')->name('email_verify');
     // Event Register
     Route::group(['middleware' => 'prevent-back-history'],function(){
        Route::get('/event-details/{event}', 'TrainerEventController@showEventDetails')->name('events.details');    
        Route::get('/event-detailss/{event}', 'TrainerEventController@showEventDetailss')->name('events.detailss');  
        Route::get('/event-register/{event}', 'TrainerEventController@showEventRegisterForm')->name('events.register');      
        });
        Route::post('/event-details', 'TrainerEventController@attendeesSaveing')->name('attendeesSave');
        Route::post('/event-register', 'TrainerEventController@eventRegistrationPayment')->name('eventregisterpayment');
        Route::post('/event-payment', 'PaymentController@createeventpaymentsave')->name('createeventpaymentsave');
        Route::post('/event-payment-intent', 'PaymentController@createeventpaymentintent')->name('createeventpaymentintent');
        Route::post('/send_help_email', 'FrontUserCountroller@sendAthleteHelpEmail')->name('athlete.help_email');
    Route::get('/testimonial/{id}', 'FrontController@testimonialDetail')->name('testimonial.detail');
    // Business Page
    Route::get('/business', 'FrontController@businessPage')->name('businessPage');
    Route::post('/business', 'FrontController@provider_login')->name('front.provider.loggedIn');
    
    Route::group(['middleware' => ['front.user']], function () {

        Route::group(['middleware' => ['front.trainer']], function () {
            Route::group(['prefix' => 'trainer'], function () {
                Route::get('/dashboard', 'FrontUserCountroller@dashboard')->name('front.dashboard');
                Route::get('/trainer-image-delete/{service}', 'FrontUserCountroller@trainer_image_delete')->name('trainer.image.delete');

                Route::get('/edit-profile', 'FrontUserCountroller@profile')->name('front.profile');
                Route::post('/edit-profile', 'FrontUserCountroller@update_profile')->name('front.update.profile');
                Route::get('/provider-bussiness-name-check/{name}', 'FrontController@provider_bussiness_name_check')->name('front.provider.bussiness.name.check');
                Route::get('/promo-code', 'ResourceController@coupon')->name('front.coupon');
                Route::post('/promo-code', 'ResourceController@update_coupon')->name('front.update.coupon');
                Route::post('/edit-profile-thumbnail-image', 'FrontUserCountroller@get_cloud_thumbnail')->name('front.update.profile.thumbnail');
                Route::post('/edit-profile-upload-image', 'FrontUserCountroller@update_profile_images')->name('front.update.profile.images');
                Route::post('/edit-resource-upload-image', 'FrontUserCountroller@update_resource_images')->name('front.update.resource.images');
                Route::post('/change_password', 'FrontUserCountroller@change_password')->name('trainer.change_password');
                Route::get('/services', 'ServiceController@index')->name('services.list');
                //Route::post('/services/getall', 'ServiceController@index')->name('services.getall');
                Route::get('/service/featured/{service}', 'ServiceController@featuredChange')->name('service.featured');
                Route::get('/service/status/{service}', 'ServiceController@statusChange')->name('service.status');

                Route::get('/add-service', 'ServiceController@create')->name('service.add');
                Route::post('/add-service', 'ServiceController@store')->name('service.store');
                Route::get('/edit-service/{service}', 'ServiceController@edit')->name('service.edit');
                Route::get('/service-detail/{service}', 'ServiceController@show')->name('service.detail');
                Route::get('/service-delete/{service}', 'ServiceController@destroy')->name('service.delete');

                Route::get('/resource', 'ResourceController@index')->name('resource.list');
                //Route::get('/resource/status/{resource}', 'ResourceController@statusChange')->name('resource.status');

                Route::get('/add-resource', 'ResourceController@create')->name('resource.add');
                Route::post('/add-resources', 'ResourceController@store')->name('resource.store');
                Route::get('/edit-resource/{resource}', 'ResourceController@edit')->name('resource.edit');
                Route::get('/resource-detail/{resource}', 'ResourceController@show')->name('resource.detail');
                Route::get('/resource-delete/{resource}', 'ResourceController@destroy')->name('resource.delete');

                Route::get('/resource-category', 'ResourceController@resource_category')->name('resource_category.list');
                Route::post('/search-resource', 'ResourceController@search_resource')->name('resource_search');
                //Route::get('/resource/status/{resource}', 'ResourceController@statusChange')->name('resource.status');

                Route::get('/add-resource-category', 'ResourceController@category_create')->name('resource_category.add');
                Route::post('/add-resources-category', 'ResourceController@category_store')->name('resource_category.store');
                Route::get('/edit-resource-category/{resource_category}', 'ResourceController@category_edit')->name('resource_category.edit');
                Route::get('/resource-category-delete/{resource_category}', 'ResourceController@category_destroy')->name('resource_category.delete');

                Route::get('/private-messaging', 'FrontUserCountroller@private_messaging_trainer')->name('trainer.private_messaging');
                Route::get('/private-messaging-send', 'FrontUserCountroller@private_messaging_trainer_send')->name('trainer.private_messaging.send');
                Route::get('/private-messaging-view/{id}', 'FrontUserCountroller@private_messaging_view')->name('trainer.private_messaging.view');
                Route::post('/private-messaging-send', 'FrontUserCountroller@private_messaging_trainer_save')->name('trainer.private-messaging-send');
                Route::get('/private-messaging/getall', 'FrontUserCountroller@private_messaging_trainer_get_all')->name('trainer.private-messaging.getall');

                Route::get('/order-history', 'FrontUserCountroller@order_history')->name('trainer.order.history');
                Route::get('/calendar', 'FrontUserCountroller@availability')->name('trainer.scheduling');
                Route::post('/search-calendar', 'FrontUserCountroller@searchavailability')->name('trainer.search.scheduling');
                Route::get('/memberships-packages', 'FrontUserCountroller@month_annual_schedules')->name('trainer.month.annual.schedules');
                Route::post('/search-memberships-packages', 'FrontUserCountroller@search_month_annual_schedules')->name('trainer.search.month.annual.schedules');
                Route::get('/notifications', 'FrontUserCountroller@notifications')->name('trainer.notifications');
                 Route::post('/search-notifications', 'FrontUserCountroller@Searchnotifications')->name('trainer.search.notifications');
                Route::post('/booking-form', 'FrontUserCountroller@bookingform')->name('trainer.book');
                Route::post('/service-booking-form', 'FrontUserCountroller@servicebookingform')->name('trainer.service.book');
                Route::post('/save/comment', 'FrontUserCountroller@savecommant')->name('trainer.save.comment');

                Route::get('/ratings-list', 'FrontUserCountroller@ratings_list')->name('trainer.ratings.list');
                Route::get('/attendees-list', 'TrainerEventController@attendees_list')->name('trainer.attendees.list');
                Route::post('/attendees-list', 'TrainerEventController@find_attendees')->name('trainer.attendees.find');
                Route::get('/ratings-delete/{ratings}', 'FrontUserCountroller@destroyRatings')->name('trainer.ratings.delete');
                Route::get('/changelocation/{id}', 'FrontUserCountroller@changelocation')->name('trainer.location.change');
                //Route::view('/availability', 'front.trainer.comming_soon')->name('trainer.availability');

                Route::post('/addrecommended', 'FrontUserCountroller@addrecommended')->name('trainer.addrecommended');
                Route::get('/account-information', 'FrontUserCountroller@account_information')->name('trainer.account.information');
                Route::post('/providerpaymentsave', 'FrontUserCountroller@providerpaymentsave')->name('trainer.providerpaymentsave');
                Route::post('/providerpaymenteditsave', 'FrontUserCountroller@providerpaymenteditsave')->name('trainer.providerpaymenteditsave');
                Route::get('/provider-cancel-subscription/{order}/{subscriptionid}', 'FrontUserCountroller@provider_cancel_subscription')->name('trainer.provider.cancel.subscription');
                Route::get('/provider-edit-plan-subscription/{planid}', 'FrontUserCountroller@provider_edit_plan_subscription')->name('trainer.provider.edit.plan.subscription');
                Route::post('/createproviderpaymentintent', 'FrontUserCountroller@createproviderpaymentintent')->name('trainer.createproviderpaymentintent');

                 // Trainer Event Routes

                 Route::get('/events', 'TrainerEventController@index')->name('trainer.events.list');
                 Route::get('/add-event', 'TrainerEventController@create')->name('trainer.events.add');
                 Route::post('/add-event', 'TrainerEventController@store')->name('trainer.events.store');
                 Route::get('/edit-event/{event}', 'TrainerEventController@edit')->name('trainer.events.edit');
                 // Route::get('/resource-detail/{resource}', 'ResourceController@show')->name('resource.detail');
                 Route::get('/delete-event/{event}', 'TrainerEventController@destroy')->name('trainer.events.delete');  
                 Route::get('/delete-recurring-event/{event}', 'TrainerEventController@destroy_recurrence')->name('trainer.events.delete.recurrence');
                
            });
        });




        Route::group(['middleware' => ['front.customer']], function () {
            Route::group(['prefix' => 'athlete'], function () {
                Route::get('/find-friends', 'FrontUserCountroller@findfriends')->name('customer.findfriends');

            });
            Route::group(['prefix' => 'customer'], function () {
                Route::get('/edit-profile', 'FrontUserCountroller@customer_profile')->name('customer.profile');
                //Route::get('/profile/{name}', 'FrontUserCountroller@customer_profile_new')->name('customer.newprofile');
                Route::get('/find-group', 'FrontUserCountroller@findgroup')->name('customer.findgroup');
                Route::post('/addfriend', 'FrontUserCountroller@addfriend')->name('customer.addfriend');
                Route::post('/JoinGroup', 'FrontUserCountroller@JoinGroup')->name('customer.JoinGroup');
                Route::post('/find-friend-data', 'FrontUserCountroller@findfriendsdata')->name('customer.findfriendsdata');
                Route::post('/find-group-data', 'FrontUserCountroller@findgroupsdata')->name('customer.findgroupsdata');
                //Route::post('/getfrienddata', 'FrontUserCountroller@getfrienddata')->name('customer.getfrienddata');
                Route::post('/acceptrejectrequest', 'FrontUserCountroller@acceptrejectrequest')->name('customer.acceptrejectrequest');
                Route::post('/acceptrejectrequestindvidual', 'FrontUserCountroller@acceptrejectrequestindvidual')->name('customer.acceptrejectrequestindvidual');
                Route::post('/orderhistory', 'FrontUserCountroller@orderhistory')->name('customer.orderhistory');
                Route::post('/notificationdata', 'FrontUserCountroller@notificationdata')->name('customer.notificationdata');
                //Route::post('/savedresourcedata', 'FrontUserCountroller@savedresourcedata')->name('customer.savedresourcedata');
                //Route::post('/getreviewsdata', 'FrontUserCountroller@getreviewsdata')->name('customer.getreviewsdata');
                Route::post('/edit-profile', 'FrontUserCountroller@update_profile')->name('customer.update.profile');
                Route::post('/change_password', 'FrontUserCountroller@change_password')->name('customer.change_password');
                Route::post('/addordernote', 'FrontUserCountroller@addordernote')->name('customer.order.addnote');
                Route::post('/addrecommended', 'FrontUserCountroller@addrecommended')->name('customer.addrecommended');
                //Route::post('/recommendeddata', 'FrontUserCountroller@recommendeddata')->name('customer.recommendeddata');
                Route::post('/creategroup', 'FrontUserCountroller@creategroup')->name('customer.group.create');
                Route::get('/book-now/{service?}', 'PaymentController@book_now')->name('customer.booknow');
                Route::post('/book-now-service', 'PaymentController@book_now_service')->name('customer.booknow.service');
                Route::get('/book-now-service', 'PaymentController@book_now_service')->name('customer.booknow.service');
                Route::post('/book-now', 'PaymentController@create_order')->name('customer.create.order');
                Route::post('/invite-friend', 'FrontUserCountroller@InviteFriend')->name('customer.friend.invite');
                Route::get('/profile/{name}', 'FrontUserCountroller@customer_profile_new')->name('customer.newprofile');
                Route::get('/order-history', 'FrontUserCountroller@order_history')->name('customer.order.history');
                Route::get('/cancel-order/{order}/{paymentid}', 'PaymentController@cancel_order')->name('customer.cancel.order');
                Route::get('/cancel-subscription/{order}/{subscriptionid}', 'PaymentController@cancel_subscription')->name('customer.cancel.subscription');

                Route::get('/private-messaging', 'FrontUserCountroller@private_messaging')->name('customer.private_messaging');
                Route::get('/private-messaging-send', 'FrontUserCountroller@private_messaging_send')->name('customer.private_messaging.send');
                Route::get('/private-messaging-view/{id}', 'FrontUserCountroller@private_messaging_view')->name('customer.private_messaging.view');
                Route::post('/private-messaging-send', 'FrontUserCountroller@private_messaging_save')->name('private-messaging-send');
                Route::get('/private-messaging/getall', 'FrontUserCountroller@private_messaging_get_all')->name('private-messaging.getall');
                //Route::get('/review-rating/{order}', 'FrontUserCountroller@review_rating')->name('customer.review');
                Route::post('/review-rating', 'FrontUserCountroller@submitReview')->name('customer.review.submit');
                Route::get('/service/details/{service}', 'ServiceController@getServiceDetails')->name('service.deatils');
                Route::post('/gettimeslots', 'PaymentController@getTimeSlots')->name('service.timeslots');

                /*Route::get('/review-rating/{trainer}', 'FrontUserCountroller@review_rating')->name('customer.review');*/

                Route::get('/ratings-list', 'FrontUserCountroller@ratings_list')->name('customer.ratings.list');
                Route::get('/ratings-delete/{ratings}', 'FrontUserCountroller@destroyRatings')->name('customer.ratings.delete');

            });
        });

    });
	
    Route::post('/profileEventChange', 'TrainerEventController@ChangeRsvp');
     Route::post('/getfrienddata', 'FrontUserCountroller@getfrienddata')->name('customer.getfrienddata');
     Route::post('/recommendeddata', 'FrontUserCountroller@recommendeddata')->name('customer.recommendeddata');
     Route::post('/savedresourcedata', 'FrontUserCountroller@savedresourcedata')->name('customer.savedresourcedata');
     Route::post('/getreviewsdata', 'FrontUserCountroller@getreviewsdata')->name('customer.getreviewsdata');
    Route::get('/advertisement/changecounter/{id}', 'FrontController@changecounter')->name('advertisement.changecounter');
    Route::get('/advertisement/get', 'FrontController@getadv')->name('advertisement.getadv');
    Route::get('/leaveareview/{trainer}', 'FrontController@review_rating')->name('customer.review');

    Route::get('/service-book-now/{serviceID} ', 'FrontController@showBookServiceDetail')->name('trainer.book.now');
    Route::get('/servicebookdetails/{event_id}', 'FrontUserCountroller@servicebookdetails')->name('trainer.service.book.details');
    Route::get('/checkeventdetails/{days}/{serviceId}/{appointment_date}', 'FrontUserCountroller@checkeventdetails')->name('trainer.check.event.details');
    
    Route::post('/confirmservicebookdetails', 'FrontUserCountroller@confirmservicebookdetails')->name('trainer.service.book.details');
    /*Route::get('/confirmservicebookdetails/{event_id}/{event_time}/{event_days}', 'FrontUserCountroller@confirmservicebookdetails')->name('trainer.service.book.details');*/
    Route::get('/service-request-book-now/{serviceID} ', 'FrontController@serviceRequestDetail')->name('trainer.request.book.now');
    Route::get('/provider/{name} ', 'FrontController@showTrainerDetail')->name('trainer.detail');
    Route::get('/providers/{name} ', 'FrontController@showTrainerDetails')->name('trainer.details');
    Route::post('/provider/{name} ', 'FrontController@showTrainerDetail')->name('trainer.detail');
    Route::get('/resource-library', 'ResourceController@ResourceLibrary')->name('resource-library');
    Route::get('/resource-librarys', 'ResourceController@ResourceLibrarys')->name('resource-librarys');
    Route::get('/resource-library-search/{resourceId}', 'ResourceController@ResourceLibrarySearch')->name('resource-library-search');
    Route::post('/resource-library', 'ResourceController@SearchResource')->name('search-resource-library');
    Route::get('/provider/resources-like/{resourceId}/{name}', 'FrontController@ResourceLike')->name('resources-like');
    Route::get('/provider/resources-dislike/{resourceId}/{name}', 'FrontController@ResourceDisLike')->name('resources-dislike');
    Route::get('/provider/resources-save/{resourceId}/{name}', 'FrontController@ResourceSave')->name('resources-save');
    Route::post('/resources-comment', 'FrontController@ResourceComment')->name('resources-comment');
    Route::post('/resource-comment', 'ResourceController@ResourceComment')->name('resource-comment');
    Route::post('/resource-detail-comment', 'ResourceController@ResourceDetailComment')->name('resource-detail-comment');
    Route::post('/request-status-update', 'ResourceController@RequestStatusUpdate')->name('request-status-update');
    Route::get('/resource-like/{resourceId}', 'ResourceController@ResourceLike')->name('resource-like');
    Route::get('/resource-dislike/{resourceId}', 'ResourceController@ResourceDisLike')->name('resource-dislike');
    
// event like and cmt //
Route::get('/event-like/{eventId}', 'TrainerEventController@EventLike')->name('event-like');
Route::get('/event-dislike/{eventId}', 'TrainerEventController@EventDisLike')->name('event-dislike');
Route::post('/event-comment', 'TrainerEventController@EventComment')->name('event-comment');
Route::get('/event-save/{eventId}', 'TrainerEventController@EventSave')->name('event-save');
Route::post('/event-detail-comment', 'TrainerEventController@EventDetailComment')->name('event-detail-comment');

    //Route::get('/resource-unsave/{resourceId}', 'ResourceController@ResourceUnsave')->name('resource-unsave');
    Route::get('/resource-save/{resourceId}', 'ResourceController@ResourceSave')->name('resource-save');
    Route::get('/resource-details/{resourceId}', 'ResourceController@ResourceDetails')->name('resource-details');
    Route::get('/resource-detailss/{resourceId}', 'ResourceController@ResourceDetailss')->name('resource-detailss');
    Route::get('/subcribes/{email}', 'ResourceController@Subcribes')->name('subcribes');
    Route::post('/subcribesPost', 'ResourceController@SubcribesPost')->name('subcribesPost');
    Route::get('/accountinformationdetails/{plan_type}', 'FrontUserCountroller@accountinformationdetails')->name('trainer.account.book.details');
});
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('config:clear');

    $exitCode = Artisan::call('cache:clear');

    $exitCode = Artisan::call('view:clear');

    $exitCode = Artisan::call('route:clear');

    $exitCode = Artisan::call('key:generate');

});


/*});*/
/*
});*/

});


Route::permanentRedirect('/provider/www.linkedin.com/in/klutz4', '/');
Route::permanentRedirect('/provider/linkedin.com/in/k-c-padget-8846208', '/');
Route::permanentRedirect('/provider/www.facebook.com/tracybrownrd', '/');
Route::permanentRedirect('/provider/www.instagram.com/tracybrownrd', '/');
Route::permanentRedirect('/provider/www.facebook.com/thriveptandmfr', '/');
Route::permanentRedirect('/provider/instagram.com/upandrunningpt', '/');
Route::permanentRedirect('/provider/facebook.com/upandrunningpt', '/');

Route::permanentRedirect('/provider/www.instagram.com/thriveptmfr/', '/');
Route::permanentRedirect('/provider', '/');
Route::permanentRedirect('/index.html', '/');

//Route::any('/{page?}',function(){
//  return View::make('error.404');
//})->where('page','.*');


