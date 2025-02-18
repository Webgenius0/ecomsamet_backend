<?php

use App\Http\Controllers\Api\ApiBookingController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgetPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\Api\ApiCategorieController;
use App\Http\Controllers\APi\ApiRatingController;
use App\Http\Controllers\Api\ApiServiceController;
use App\Http\Controllers\Api\ApiFavoriteServiceController;
use App\Http\Controllers\Api\HairDressBookingController;
use App\Http\Controllers\Api\HairDresserNotificationController;
use App\Http\Controllers\Api\HairDressServicesController;
use App\Http\Controllers\Api\HairHomeController;
use App\Http\Controllers\Api\UserBookingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserHomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/verify', [RegisterController::class, 'verifyOtp']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/reset', [ForgetPasswordController::class, 'requestReset']);
Route::post('/reset-password', [ForgetPasswordController::class, 'resetPassword']);
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:api');

// Route::post('/request-reset', [ForgetPasswordController::class, 'requestReset']);
// Route::post('/reset-password', [ForgetPasswordController::class, 'resetPassword']);

Route::post('/social-login', [SocialLoginController::class, 'socialLogin']);
Route::get('auth/{provider}', [SocialLoginController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [SocialLoginController::class, 'handleProviderCallback']);

Route::get('/categories', [ApiCategorieController::class, 'index']);
Route::post('/categories', [ApiCategorieController::class, 'store']);
Route::get('/categories/{id}', [ApiCategorieController::class, 'getServicesByCategory']);


// user info routes with role and permissions base
Route::middleware(['auth:api'])->group(function () {
    Route::get('/user-info', [UserController::class, 'index']);
    Route::post('/update-user/{id}', [UserController::class,'update']);

});

//For Hairdresser Controller

//For Hairdress Home Controller
Route::middleware(['auth:api', 'role:hairdresser'])->group(function () {
    Route::get('/hairdressers/weekly-payment', [HairHomeController::class, 'weeklyPayment']);
    Route::get(('/hairdressers/monthly-payment'), [HairHomeController::class, 'monthlyPayment']);
    Route::get(('/hairdressers/yearly-payment'), [HairHomeController::class, 'yearlyPayment']);

});
//For Hairdresser Service Controller
Route::middleware(['auth:api', 'role:hairdresser'])->group(function () {
    Route::get('/hairdressers/service', [HairDressServicesController::class, 'index']);
    Route::post('/hairdressers/service', [HairDressServicesController::class, 'store']);
    Route::get('/hairdressers/service/{id}', [HairDressServicesController::class, 'show']);
    Route::get('/hairdressers/additional-service', [HairDressServicesController::class, 'additionalServices']);
});

//For Hairdresser Booking Controller
Route::middleware(['auth:api', 'role:hairdresser'])->group(function () {
    Route::get('/booking/pendding', [HairDressBookingController::class, 'penddingdata']);
    Route::post('/booking/pendding/{id}/{action}', [HairDressBookingController::class, 'acceptbooking']);
    Route::get('/booking/booked', [HairDressBookingController::class, 'booked']);
    Route::get('/booking/comepleted', [HairDressBookingController::class, 'completed']);

});

//For Hairdresser Notification Controller
Route::middleware(['auth:api', 'role:hairdresser'])->group(function () {
    Route::get('/notification', [HairDresserNotificationController::class, 'notification']);
});

//For User Controller

//For user Home Controller
Route::middleware(['auth:api', 'role:user'])->group(function () {
    Route::get('/users/home/{id}/', [UserHomeController::class, 'show']);
    Route::get('/users/home/service/{id}', [UserHomeController::class, 'serviceShow']);
    Route::get('/service/search', [UserHomeController::class, 'search']);
    Route::get('top-rating', [UserHomeController::class, 'topRating']);
});



//For user Booking Controller
Route::middleware(['auth:api', 'role:user'])->group(function () {
    Route::post('/bookings', [UserBookingController::class, 'bookService']);
    Route::get('/bookings/confirm', [UserBookingController::class, 'getconfirmBookings']);
    Route::get('/bookings/completed', [UserBookingController::class, 'getcompeletedBookings']);
    Route::get('/bookings/cancelled', [UserBookingController::class, 'getconcelledBookings']);
});


//For User Rating
Route::middleware(['auth:api', 'role:user'])->group(function () {
    Route::post('/rating', [ApiRatingController::class, 'rateService']);
    Route::get('/getall-rating', [ApiRatingController::class, 'index']);
});
//For User Favorite
Route::middleware(['auth:api', 'role:user'])->group(function () {
    Route::post('/favorites/{id}', [ApiFavoriteServiceController::class, 'toggle']);
    Route::get('/favorites/{service}/check', [ApiFavoriteServiceController::class, 'check']);
});

//For user Notification Controller
