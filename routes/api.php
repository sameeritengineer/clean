<?php

use Illuminate\Http\Request;

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
Route::post('stripePayment', 'Api\StripeController@createPaymentStripe');
Route::prefix('customer')->group(function ()
{
	// Customer Registration and Login API's //
	// 1.signup -> Request:- {"first_name":"manpreet","last_name":"kaur","email":"harpi@gmail.com","password":"mani@12345","password_cofirmation":"mani@12345","mobile":"956482315"} //

    Route::post('userRegister', 'Api\userController@userRegister');

    // 2.login -> Request:-{"email":"harpi@gmail.com","password":"mani@12345",} //

    Route::post('userLogin', 'Api\userController@userLogin');
  
    // 3.Forgot password -> Request:-{"email":"harpi@gmail.com"} //

    Route::post('forgotPassword', 'Api\userController@forgotPassword');

    // 4.check otp -> Request:-{"userId":"6","otp":""} //

    Route::post('matchOTP','Api\userController@matchOtp');

    // 5.Pssword Reset -> Request:-{"userId":"6","otp":""} //

    Route::post('resetPassword','Api\userController@resetPassword');

    // 6.changePassword -> Request:-{'id' =>'56','old_password'=> 'old_@123','new_password'=>'new@123',} //

    Route::post('changePassword', 'Api\userController@changePassword');

    // 11.Update Customer full Profile Details //

    Route::post('updateCustomerProfile', 'Api\userController@updateCustomerProfile');

    // 12.Display User Full Details// 

    Route::post('customerfullDetails', 'Api\userController@customerfullDetails');

    //13.User Profile Picture//

    Route::post('userProfilePic', 'Api\userController@userProfilePic');

    //14.Show Referral code//

    Route::post('showReferralcode', 'Api\userController@showReferralcode');


    Route::post('logout', 'Api\userController@logout');

    //------------------CountryController----------------------//

    // 7.Get State List -> Request:-{"countryId":"6"} //  

    Route::get('getStateList', 'Api\countryController@getStateList');

    // 8.Get city List -> Request:-{"stateid":"6"} //  

    Route::post('getCityList', 'Api\countryController@getCityList');

    // 9.Get zipcode List -> Request:-{"cityid":"6"} //  

    Route::post('getZipcodeList', 'Api\countryController@getZipcodeList');

    // 10.Get service list ->// 

    Route::get('getServiceList/{lang}', 'Api\ServiceController@getServiceList');

    // 15. all Zipcodes//

    Route::get('getAllZipcodes', 'Api\countryController@getAllZipcodes');

    //--------------------------ServiceController---------------------------//

    // 11.Get servicetype list -> Request:-{"serviceId":"6"} // 

    Route::post('getServiceTypeList/{lang}', 'Api\ServiceController@getServiceTypeList');

    // 12.Get All servicetype list -> Request:-{"serviceId":"6"} // 

    Route::get('getAllServiceTypeList/{lang}', 'Api\ServiceController@getAllServiceTypeList');

    //---------------------intant Booking-----------------------------//

    Route::post('InstantBooking', 'Api\ServiceController@InstantBooking');

    //---------------------Show Provider profile(who accepted the job)-----------------------------//

    Route::post('showserviceproviderprofile', 'Api\ServiceController@showserviceproviderprofile');  //with notification.

    
    Route::post('displayproviderprofile', 'Api\ServiceController@displayproviderprofile');  //without notification.

    //---------------------List of  Upcoming Appointments for customer-----------------------------//

    Route::post('ListOfCurrentJobsForCustomer/{lang}', 'Api\ServiceController@ListOfCurrentJobsForCustomer'); 

    //---------------------List of  All Completed Appointments-----------------------------//

    Route::post('ListOfCompletedJobsForCustomer/{lang}', 'Api\ServiceController@ListOfCompletedJobsForCustomer'); 

    //---------------------List of  All Canceled Appointments-----------------------------//

    Route::post('ListOfCanceledJobsForCustomer/{lang}', 'Api\ServiceController@ListOfCanceledJobsForCustomer'); 

    //--------------------Cancel Current Job("Here job will Canceled by Customer")---------------------------------------//

    Route::post('CanceljobByCustomer', 'Api\ServiceController@CanceljobByCustomer');

    //-----------------------FAQController--------------------//

    // 1.Get customer Faq ->// 

    Route::get('getcustomerFaQ/{lang}', 'Api\service_provider\FaQController@getcustomerFaQ');

    //--------Special Request To Cleaner-------------//

    Route::post('SpecialRequestToCleaner', 'Api\ServiceController@SpecialRequestToCleaner');

    //--------Customer to Provider Review-------------//

    Route::post('providerReviews', 'Api\ServiceController@providerReviews');

    // Working Days For Scheduled Booking

    Route::get('allworkingdays','Api\SchedulBookingController@allworkingdays')->name('allworkingdays');

    //Daily Scheduled Jobs

    Route::post('DailyScheduleingJob','Api\SchedulBookingController@DailyScheduleingJob')->name('DailyScheduleingJob');

    //Weekly Scheduled Jobs

    Route::post('WeeklyScheduleingJob','Api\SchedulBookingController@WeeklyScheduleingJob')->name('WeeklyScheduleingJob');

    //Monthly Scheduled Jobs

    Route::post('MonthlyScheduleingJob','Api\SchedulBookingController@MonthlyScheduleingJob')->name('MonthlyScheduleingJob');

    //List Of previous Jobs 

    Route::post('AllPreviousJobs/{lang}','Api\ServiceController@AllPreviousJobs')->name('AllPreviousJobs');

    //All PreviousJob Details

    Route::post('FullPreviousJobDetails/{lang}','Api\ServiceController@FullPreviousJobDetails')->name('FullPreviousJobDetails');

    //Rebook Previous Cleaner

    Route::post('ReebookPreviousCleaner','Api\ServiceController@ReebookPreviousCleaner')->name('ReebookPreviousCleaner');

});

Route::prefix('provider')->group(function ()
{
    // 1.signup -> Request:- {"first_name":"manpreet","last_name":"kaur","email":"harpi@gmail.com","password":"mani@12345","password_cofirmation":"mani@12345","mobile":"956482315"}//
  
    Route::post('providerRegister', 'Api\service_provider\providerController@providerRegister');

    // 2.login -> Request:-{"email":"harpi@gmail.com","password":"mani@12345",} //

    Route::post('providerLogin', 'Api\service_provider\providerController@providerLogin');

    // 3.Forgot password -> Request:-{"email":"harpi@gmail.com"} //

    Route::post('forgotPassword', 'Api\service_provider\providerController@forgotPassword');

    // 4.check otp -> Request:-{"userId":"6","otp":""} //

    Route::post('matchOTP','Api\service_provider\providerController@matchOtp');

    // 5.Pssword Reset -> Request:-{"userId":"6","otp":""} //

    Route::post('resetPassword','Api\service_provider\providerController@resetPassword');

    // 6.Get State List -> Request:-{"countryId":"6"} //  

    Route::get('getStateList', 'Api\service_provider\providerController@getStateList');

    // 7.Get city List -> Request:-{"stateid":"6"} //  

    Route::post('getCityList', 'Api\service_provider\providerController@getCityList');

    // 8.Get zipcode List -> Request:-{"cityid":"6"} //  

    Route::post('getZipcodeList', 'Api\service_provider\providerController@getZipcodeList');

    // 9.Add/Update provider Profile Picture//

    Route::post('insertUpdateProfilePic', 'Api\service_provider\providerController@insertUpdateProfilePic');    

    // 10.Display Approved Profile pic Of provider //

    Route::post('DisplayProvideProfilePicture', 'Api\service_provider\providerController@DisplayProvideProfilePicture');

    // 11.Update provider full Profile Details //

    Route::post('updateProviderProfile', 'Api\service_provider\providerController@updateProviderProfile');

    // 12.Add/Edit provider Bio//

    Route::post('addUpdateProviderBio', 'Api\service_provider\providerController@addUpdateProviderBio');

    // 13.Display Approved Provider Bio//

    Route::post('displayApprovedProviderBio', 'Api\service_provider\providerController@displayApprovedProviderBio');

    // 14.Active/Deactive Status of service provider//

    Route::post('activedeactiveseriveprovider', 'Api\service_provider\providerController@activedeactiveseriveprovider');

    // 15.Provider Full Detail's//

    Route::post('ProviderfullDetails', 'Api\service_provider\providerController@ProviderfullDetails');

    //14.Show Referral code//

    Route::post('showproviderReferralcode', 'Api\service_provider\providerController@showproviderReferralcode');

    //-----------------Data Entry Api-----------------------//

    Route::post('addallstate', 'Api\service_provider\providerController@addallstate');

    // 14.changePassword -> Request:-{'id' =>'56','old_password'=> 'old_@123','new_password'=>'new@123',} //

    Route::post('changePassword', 'Api\service_provider\providerController@changePassword');

    // 15. all Zipcodes//

    Route::get('getAllZipcodes', 'Api\service_provider\providerController@getAllZipcodes');


    Route::post('provider_logout', 'Api\service_provider\providerController@provider_logout');

    //--------------------ProviderServiceController----------------//
    
    // 14.Get service list ->// 

    Route::get('getServiceList/{lang}', 'Api\service_provider\ProviderServiceController@getServiceList');

    // 15.Get servicetype list -> Request:-{"serviceId":"6"} // 

    Route::post('getServiceTypeList/{lang}', 'Api\service_provider\ProviderServiceController@getServiceTypeList');

    // 16.Get All Service Type List/

    Route::get('getAllServiceTypeList/{lang}', 'Api\service_provider\ProviderServiceController@getAllServiceTypeList');

    //17.Get servicetype Name -> Request:-{"serviceId":"6"} // 

    Route::post('getServiceTypeName/{lang}', 'Api\service_provider\ProviderServiceController@getServiceTypeName');

    //-----------------------FAQController------------------//

    // 1.Get provider Faq ->// 

    Route::get('getProviderFaQ/{lang}', 'Api\service_provider\FaQController@getProviderFaQ');  

    //---------------Job accept from provider ---------------------//

    Route::post('acceptInstantJob', 'Api\service_provider\providerController@acceptInstantJob');
    Route::get('callculatePrice', 'Api\service_provider\providerController@callculatePrice');

    //----------------List Of Current Jobs of a particular Service Provider-------------------------//

    Route::post('ListOfCurrentJobsForProvider/{lang}', 'Api\service_provider\providerController@ListOfCurrentJobsForProvider');  
    Route::post('checkInForJob', 'Api\service_provider\providerController@checkInForJob');
    Route::post('checkOutFromJob', 'Api\service_provider\providerController@checkOutFromJob');  

    //--------------List Of Canceled Jobs which canceled by provider----------------------//

    Route::post('ListOfCanceledJobsFromProvider/{lang}', 'Api\service_provider\providerController@ListOfCanceledJobsFromProvider'); 

    //--------------List Of Completed Jobs-------------------//

    Route::post('ListOfCompletedJobsForProvider/{lang}', 'Api\service_provider\providerController@ListOfCompletedJobsForProvider');

    //--------------Cancel Current Job("Here job will Canceled by service Provider")----------------------//

    Route::post('CanceljobByProvider', 'Api\service_provider\providerController@CanceljobByProvider');

    Route::post('add_provider_location','Api\userController@add_provider_location');

});
