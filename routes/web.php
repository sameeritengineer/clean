<?php
use App\Country;
use App\City;
use App\State;
use App\Zipcode;
use App\Service;
use App\Servicetype;
use App\User;
use App\Userrole;
use App\Role;
use App\ProviderProfile;
use App\Providerbio;
use App\InstantBooking;
use App\Instant_schedule_job;

//*************************************************************************************************************************************************//

							    	//-------------------------FrontEnd-----------------------------//

//*************************************************************************************************************************************************//


Auth::routes();
Route::get('/', function () {return view('front.front'); });
Route::get('/','Frontend\FrontmainCantroller@home')->name('home');
Route::get('About_us','Frontend\FrontmainCantroller@about_us')->name('about_us');
Route::get('Services','Frontend\FrontmainCantroller@Services')->name('Services');
Route::get('Pricing','Frontend\FrontmainCantroller@Pricing')->name('Pricing');
Route::get('Contact','Frontend\FrontmainCantroller@Contact')->name('Contact');
Route::get('Privacy_Policy','Frontend\FrontmainCantroller@privacypolicy')->name('privacypolicy');
Route::get('terms_conditions','Frontend\FrontmainCantroller@termsconditions')->name('termsconditions');
Route::get('Blog','Frontend\FrontmainCantroller@blog')->name('blog');
Route::get('unauthorized', function () { return view('unauthorized'); });

//-------------------------Registration of Web users----------------------//

Route::get('user/create','Frontend\FrontmainCantroller@create')->name('create');
Route::post('user/create','Frontend\FrontmainCantroller@store');


//-------------------------Login Web users----------------------//

Route::get('Login','Frontend\FrontmainCantroller@Login')->name('Login');

Route::get('stripe', 'StripePaymentController@stripe');
Route::post('stripe', 'StripePaymentController@stripePost')->name('stripe.post');


//*************************************************************************************************************************************************//

									 //-------------------------AdminPanel-----------------------------//

//*************************************************************************************************************************************************//


Route::middleware(['admin','auth'])->group(function ()
{
	Route::get('serviceadmin','Admin\PageController@dashboard')->name('dashboard');
	Route::prefix('serviceadmin')->group(function ()
	{
		Route::get('dashboard','Admin\PageController@dashboard')->name('dashboard');

		// country
		Route::get('country','Admin\CountryController@country')->name('country');
		Route::post('country','Admin\CountryController@storeCountry')->name('addCountry');
		Route::post('edit-country','Admin\CountryController@editCountry')->name('editCountry');
		Route::post('update-country','Admin\CountryController@updateCountry')->name('updateCountry');
		Route::post('destroy-country','Admin\CountryController@destroyCountry')->name('destroyCountry');

		// state
		Route::get('state','Admin\CountryController@state')->name('state');
		Route::post('state','Admin\CountryController@storeState')->name('addState');
		Route::post('edit-state','Admin\CountryController@editState')->name('editState');
		Route::post('update-state','Admin\CountryController@updateState')->name('updateState');
		Route::post('destroy-state','Admin\CountryController@destroyState')->name('destroyState');

		// city
		Route::get('city','Admin\CountryController@city')->name('city');
		Route::post('city','Admin\CountryController@storeCity')->name('addCity');
		Route::post('edit-city','Admin\CountryController@editCity')->name('editCity');
		Route::post('update-city','Admin\CountryController@updateCity')->name('updateCity');
		Route::post('destroy-city','Admin\CountryController@destroyCity')->name('destroyCity');
		Route::get('get-state-list','Admin\CountryController@getStateList');

		// zipcode
		Route::get('get-city-list','Admin\CountryController@getCityList');
		Route::get('zipcode','Admin\CountryController@zipcode')->name('zipcode');
		Route::post('zipcode','Admin\CountryController@storeZipcode')->name('addZipcode');
		Route::post('edit-zip','Admin\CountryController@editZipcode')->name('editZipcode');
		Route::post('update-zip','Admin\CountryController@updateZipcode')->name('updateZipcode');
		Route::post('destroy-zipcode','Admin\CountryController@destroyZipcode')->name('destroyZipcode');
		Route::get('get-zipcode-list','Admin\CountryController@getZipcodeList');

		// Service
		Route::get('service','Admin\ServiceController@service')->name('service');
		Route::post('service','Admin\ServiceController@storeService')->name('addService');
		Route::post('edit-service','Admin\ServiceController@editService')->name('editService');
		Route::post('update-service','Admin\ServiceController@updateService')->name('updateService');
		Route::post('destroy-service','Admin\ServiceController@destroyService')->name('destroyService');
		Route::post('service-status','Admin\ServiceController@serviceStatus')->name('serviceStatus');
		Route::get('get-zipcode-list','Admin\CountryController@getZipcodeList');

		// Service Types 
		Route::get('service-type','Admin\ServiceController@serviceType')->name('serviceType');
		Route::post('service-type','Admin\ServiceController@storeServiceType')->name('addServiceType');
		Route::post('edit-service-type','Admin\ServiceController@editServiceType')->name('editServiceType');
		Route::post('update-service-type','Admin\ServiceController@updateServiceType')->name('updateServiceType');
		Route::post('destroy-service-type','Admin\ServiceController@destroyServiceType')->name('destroyServiceType');
		Route::post('service-type-status','Admin\ServiceController@serviceTypeStatus')->name('serviceTypeStatus');

		// promo
		Route::get('promo','Admin\PromoController@index')->name('promo.index');
		Route::post('promo','Admin\PromoController@create')->name('promo.create');
		Route::post('promo/{id}','Admin\PromoController@show')->name('promo.show');
		Route::post('promo_update','Admin\PromoController@update')->name('promo.edit');
		Route::post('promo_destroy','Admin\PromoController@destroy')->name('promo.destroy');

		// Service Provider 
		Route::get('add-service-provider', function ()
		{
		   $countries = Country::all();
		   $services  = Service::all();
		   return view('admin.serviceProvider.addserviceprovider',compact('countries','services'));
		})->name('addServiceProvider');

		Route::post('save-service-provider','Admin\ServiceproviderController@storeServiceProvider')->name('saveServiceProvider');
		Route::get('all-service-provider','Admin\ServiceproviderController@allServiceProvider')->name('allServiceProvider');
		Route::post('show_all_provider_comment','Admin\ServiceproviderController@show_cmments')->name('show_cmments');
		Route::get('forgot_check_out','Admin\ServiceproviderController@ForgotCheckout')->name('ForgotCheckout');

		Route::get('show-service-provider/{id}','Admin\ServiceproviderController@showServiceProvider')->name('showServiceProvider');
		Route::get('show-service-provider-jobs-{id}','Admin\ServiceproviderController@view_jobs')->name('view_jobs');
		Route::post('admin_comments','Admin\ServiceproviderController@admin_comments')->name('admin_comments');
		Route::get('show-user-{id}','Admin\UserController@showUser')->name('showUser');

		Route::get('provider_location','Admin\ServiceproviderController@provider_location')->name('provider_location');

		Route::get('view-all-service-provider','Admin\ServiceproviderController@viewAllServiceProvider')->name('viewAllServiceProvider');
		Route::post('view-full-provider-profile','Admin\ServiceproviderController@viewfullprofiledetails')->name('viewfullprofiledetails');
		Route::post('destroy-service-provider','Admin\ServiceproviderController@destroyServiceProvider')->name('destroyServiceProvider');
		Route::get('edit-service-provider/{id}','Admin\ServiceproviderController@editServiceProvider')->name('editServiceProvider');
		Route::post('update-service-provider','Admin\ServiceproviderController@updateServiceProvider')->name('updateServiceProvider');
		Route::post('get-servicetype-list','Admin\ServiceproviderController@getServicetype')->name('getServicetype');
		Route::post('updateWorkingStatus','Admin\ServiceproviderController@updateWorkingStatus')->name('updateWorkingStatus');
		Route::post('updateUserStatus','Admin\UserController@updateStatus')->name('updateUserStatus');

		//Unapproved Registration of Service Provider
		Route::get('get-Unapproved-providers-list','Admin\ServiceproviderController@unApprovedServiceProvider')->name('unApprovedServiceProvider');
		Route::post('updateStatus','Admin\ServiceproviderController@updateStatus')->name('updateStatus');
		Route::post('viewProviderProfile','Admin\ServiceproviderController@viewProviderProfile')->name('viewProviderProfile');
		Route::post('Decline-Unapproved-provider','Admin\ServiceproviderController@DeclineunApprovedProvider')->name('DeclineunApprovedProvider');	

		//Unapproved Profile Picture of Service Provider
		Route::get('get-Unapproved-profile-pic-list','Admin\ServiceproviderController@unApprovedProfileProvider')->name('unApprovedProfileProvider');
		Route::post('updateProfileStatus','Admin\ServiceproviderController@updateProfileStatus')->name('updateProfileStatus');
		Route::post('destroy-Unapproved-profile-pic','Admin\ServiceproviderController@destroyunapproveprofilepic')->name('destroyunapproveprofilepic');

		//Unapproved Bio of Service Provider
		Route::get('get-Unapproved-bio-list','Admin\ServiceproviderController@unApprovedBioProvider')->name('unApprovedBioProvider');
		Route::post('updatebioStatus','Admin\ServiceproviderController@updatebioStatus')->name('updatebioStatus');
		Route::post('destroy-Unapproved-bio-pic','Admin\ServiceproviderController@destroyunapprovedBio')->name('destroyunapprovedBio');

		//All Decline Service Provider
		Route::get('viewAllDeclineProvider','Admin\ServiceproviderController@viewAllDeclineProvider')->name('viewAllDeclineProvider');
		Route::post('destroy-Unapproved-provider','Admin\ServiceproviderController@destroyunApprovedProvider')->name('destroyunApprovedProvider');	

		//User Section
		Route::get('add-user', function () {return view('admin.user.adduser');})->name('addUser');
		Route::post('save-user','Admin\UserController@saveUser')->name('saveUser');
		Route::get('edit-user/{id}','Admin\UserController@showEditUser')->name('showEditUser');
		Route::post('update-user','Admin\UserController@updateUser')->name('updateUser');
		Route::get('all-user','Admin\UserController@showAllUser')->name('allUser');
		Route::post('destroy-user','Admin\UserController@destroyUser')->name('destroyUser');
		Route::post('ban_ip','Admin\UserController@ban_ip')->name('ban_ip');

		//Role
		Route::get('role','Admin\UserController@role')->name('role');
		Route::post('role','Admin\UserController@storeRole')->name('addRole');
		Route::post('edit-role','Admin\UserController@editRole')->name('editRole');
		Route::post('update-role','Admin\UserController@updateRole')->name('updateRole');
		Route::post('destroy-role','Admin\UserController@destroyRole')->name('destroyRole');

		//Block provider From Customer 
		Route::get('Block-Provider','Admin\UserController@ViewblockProvider')->name('ViewblockProvider');
		Route::Post('Block-Provider','Admin\UserController@blockprovider')->name('blockprovider');
		Route::Post('UnBlock-Provider','Admin\UserController@updateBlockStatus')->name('updateBlockStatus');

		//Block Customer From Provider 
		Route::get('Block-Customer','Admin\UserController@ViewblockCustomer')->name('ViewblockCustomer');
		Route::Post('Block-Customer','Admin\UserController@blockcustomer')->name('blockcustomer');
		Route::Post('UnBlock-Customer','Admin\UserController@updateCBlockStatus')->name('updateCBlockStatus');
		Route::get('block_list','Admin\UserController@block')->name('block_list');
		Route::post('block_list','Admin\UserController@add_block')->name('add_block');
		Route::post('unblock_list','Admin\UserController@unblock_list')->name('unblock_list');
		//Discount
		Route::get('discount','Admin\UserController@discount')->name('discount');
		Route::post('discount','Admin\UserController@storeDiscount')->name('addDiscount');
		Route::post('edit-discount','Admin\UserController@editDiscount')->name('editDiscount');
		Route::post('update-discount','Admin\UserController@updateDiscount')->name('updateDiscount');
		Route::post('destroy-discount','Admin\UserController@destroyDiscount')->name('destroyDiscount');
		Route::post('discount-status','Admin\UserController@updateDiscountStatus')->name('updateDiscountStatus');

		//Notification
		Route::get('Notification','Admin\UserController@notification')->name('notification');
		Route::get('worker_notification','Admin\UserController@worker_notification')->name('worker_notification');
		Route::Post('Notification','Admin\UserController@SendNotification')->name('SendNotification');
		Route::Post('sendNotificationToWorker','Admin\UserController@sendNotificationToWorker')->name('sendNotificationToWorker');

		//fAQ for Provider
		Route::get('provider_faq','Admin\UserController@provider_faq')->name('provider_faq');
		Route::post('add-provider-faq','Admin\UserController@addproviderfaq')->name('addproviderfaq');
		Route::post('edit-Provider-Faq','Admin\UserController@editProviderFaq')->name('editProviderFaq');
		Route::post('update-Provider-Faq','Admin\UserController@updateProviderFaq')->name('updateProviderFaq');
		Route::post('destroy-Provider-Faq','Admin\UserController@destroyProviderfaq')->name('destroyProviderfaq');

		//fAQ for Customer
		Route::get('customer_faq','Admin\UserController@customer_faq')->name('customer_faq');
		Route::post('add-customer-faq','Admin\UserController@addcustomerfaq')->name('addcustomerfaq');
		Route::post('edit-customer-Faq','Admin\UserController@editcustomerFaq')->name('editcustomerFaq');
		Route::post('update-customer-Faq','Admin\UserController@updatecustomerFaq')->name('updatecustomerFaq');
		Route::post('destroy-customer-Faq','Admin\UserController@destroycustomerfaq')->name('destroycustomerfaq');

		//Instant Booking Section
		Route::get('view-All-opportunity','Admin\InstantBookingCantroller@OpenOpportunity')->name('OpenOpportunity');
		Route::get('view-opportunity/{id}','Admin\InstantBookingCantroller@show_opportunity')->name('show_opportunity');
		Route::post('send_notofication_to_provider','Admin\InstantBookingCantroller@SendNotificationToProvider')->name('provider_notification');
		Route::get('view-All-Current-Running-Jobs','Admin\InstantBookingCantroller@viewAllCurrentRunningJobs')->name('viewAllCurrentRunningJobs');
		Route::post('cancelthisjob','Admin\InstantBookingCantroller@cancelthisjob')->name('cancelthisjob');
		Route::get('view-All-Completed-Jobs','Admin\InstantBookingCantroller@viewAllCompletedJobs')->name('viewAllCompletedJobs');
		Route::get('view-All-ProviderCanceled-Jobs','Admin\InstantBookingCantroller@viewAllProviderCanceledJobs')->name('viewAllProviderCanceledJobs');
		Route::get('view-All-CustomerCanceled-Jobs','Admin\InstantBookingCantroller@viewAllCustomerCanceledJobs')->name('viewAllCustomerCanceledJobs');

		//All Rebooked jobs 
		Route::get('view-All-Rebooked-Jobs','Admin\InstantBookingCantroller@Rebookedjobs')->name('Rebookedjobs');

		//Working Days For Scheduled Booking
		Route::get('view-All-Working-days','Admin\ScheduledBookingController@workingdays')->name('workingdays');
		Route::post('workingday','Admin\ScheduledBookingController@addworkingday')->name('addworkingday');
		Route::post('Update-WorkingDay-Status','Admin\ScheduledBookingController@updateWorkingStatus')->name('updateWorkingStatus');
		Route::post('deleteWorkingday','Admin\ScheduledBookingController@deleteWorkingday')->name('deleteWorkingday');

		//Daily Scheduled Jobs
		Route::get('view-All-Daily-Scheduled-Jobs','Admin\ScheduledBookingController@DailyScheduledJobs')->name('DailyScheduledJobs');

		//Weekly Scheduled Jobs
		Route::get('view-All-Weekly-Scheduled-Jobs','Admin\ScheduledBookingController@WeeklyScheduledJobs')->name('WeeklyScheduledJobs');
		
		//Monthly Scheduled Jobs
		Route::get('view-All-Monthly-Scheduled-Jobs','Admin\ScheduledBookingController@MonthlyScheduledJobs')->name('MonthlyScheduledJobs');
		
		Route::get('prevent_notification','Admin\NotificationController@prevent_notification')->name('prevent_notification');

		Route::get('get_completed_jobs_by_city','Admin\InstantBookingCantroller@get_completed_jobs_by_city')->name('get_completed_jobs_by_city');

		Route::get('Completed_jobs_by_id/{id}','Admin\InstantBookingCantroller@Completed_jobs_by_id')->name('Completed_jobs_by_id');

		Route::get('unfilled_jobs','Admin\InstantBookingCantroller@unfilled_jobs')->name('unfilled_jobs');

		Route::get('get_unfilled_jobs_city/{id}','Admin\InstantBookingCantroller@get_unfilled_jobs_city')->name('get_unfilled_jobs_city');

		Route::post('admin_session','Admin\NotificationController@admin_session')->name('admin_session');
	});
});





