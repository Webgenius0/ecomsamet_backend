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
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
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
