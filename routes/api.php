<?php

use App\Http\Controllers\Api\ApiBookingController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgetPasswordController;
use App\Http\Controllers\Api\Auth\Logincontroller;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\Api\ApiCategorieController;
use App\Http\Controllers\APi\ApiRatingController;
use App\Http\Controllers\Api\ApiServiceController;
use App\Http\Controllers\Api\ApiFavoriteServiceController;
use App\Http\Controllers\Api\HairDressServicesController;
use App\Http\Controllers\Api\HairHomeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/verify', [RegisterController::class, 'verify']);
Route::post('/login', [Logincontroller::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:api');


Route::post('/request-reset', [ForgetPasswordController::class, 'requestReset']);
Route::post('/reset-password', [ForgetPasswordController::class, 'resetPassword']);

Route::post('/social-login', [SocialLoginController::class, 'socialLogin']);
Route::get('auth/{provider}', [SocialLoginController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [SocialLoginController::class, 'handleProviderCallback']);

Route::get('/categories', [ApiCategorieController::class, 'index']);
Route::post('/categories', [ApiCategorieController::class, 'store']);
Route::get('/categories/{id}', [ApiCategorieController::class, 'getServicesByCategory']);


Route::get('/services', [ApiServiceController::class, 'index']);
Route::post('/services', [ApiServiceController::class, 'store']);
Route::get('/search', [ApiServiceController::class, 'search']);
Route::get('/services/{id}', [ApiServiceController::class, 'show']);



Route::middleware(['auth:api'])->group(function () {
    Route::get('/bookings', [ApiBookingController::class, 'index']);  // View own bookings
    Route::get('/bookings/{id}', [ApiBookingController::class, 'show']);  // View own bookings
    Route::post('/bookings', [ApiBookingController::class, 'store']); // Create booking
    Route::delete('/bookings/{id}', [ApiBookingController::class, 'destroy']); // Delete own booking
});
// Route::get('/bookings', [ApiBookingController::class, 'index']);
// Route::post('/bookings', [ApiBookingController::class, 'store']);
// Route::delete('/bookings/{id}', [ApiBookingController::class, 'destroy']);

Route::get('/rating', [ApiRatingController::class, 'index']);
Route::post('/rating', [ApiRatingController::class, 'store']);


// routes/api.php
Route::middleware('auth:api')->group(function () {
    Route::get('favorites', [ApiFavoriteServiceController::class, 'index']);
    Route::post('favorites/{service}', [ApiFavoriteServiceController::class, 'toggle']);
    Route::get('favorites/{service}/check', [ApiFavoriteServiceController::class, 'show']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('user-update/{id}', [UserController::class, 'update']);
});

// user info routes with role and permissions base
Route::middleware(['auth:api'])->group(function () {
    Route::get('/user-info', [UserController::class, 'index']);
    Route::post('/update-user/{id}', [UserController::class,'update']);

});

//For Hairdresser Controller

//For Hairdress Home Controller
Route::middleware(['auth:api', 'role:hairdresser'])->group(function () {
    Route::get('/hairdressers/home', [HairHomeController::class, 'index']);
    Route::post('/users', [HairHomeController::class,'store']);
    Route::put('/users/{id}', [HairHomeController::class, 'update']);
    Route::delete('/users/{id}', [HairHomeController::class, 'destroy']);
});
//For Hairdresser Service Controller
Route::middleware(['auth:api', 'role:hairdresser'])->group(function () {
    Route::get('/hairdressers/service', [HairDressServicesController::class, 'index']);
    Route::post('/hairdressers/service', [HairDressServicesController::class, 'store']);
    Route::get('/hairdressers/service/{id}', [HairDressServicesController::class, 'show']);
    Route::get('/hairdressers/additional-service', [HairDressServicesController::class, 'additionalServices']);
});

//For Hairdresser Bootstrap Controller

//For Hairdresser Notification Controller


//For User Controller

//For user Home Controller
Route::middleware(['auth:api', 'role:user'])->group(function () {
    Route::get('/users/home/{id}', [ApiServiceController::class, 'show']);
    Route::get('/service/search', [ApiServiceController::class, 'search']);
});

//For user Service Controller

//For user Booking Controller

//For user Notification Controller
