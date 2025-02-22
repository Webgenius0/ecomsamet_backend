<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\Web\Backen\FavoriteController;
use App\Http\Controllers\Web\Backend\BookingController;
use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\EducationInsigtsController;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\FavoriteController as BackendFavoriteController;
use App\Http\Controllers\Web\Backend\OnboardController;
use App\Http\Controllers\Web\Backend\Settings\DynamicPageController;
use App\Http\Controllers\Web\Backend\Settings\MailSettingController;
use App\Http\Controllers\Web\Backend\Settings\ProfileController;
use App\Http\Controllers\Web\Backend\Settings\StripeSettingController;
use App\Http\Controllers\Web\Backend\Settings\SystemSettingController;
use App\Http\Controllers\Web\Backend\ServicesController;
use App\Http\Controllers\Web\Backend\Usercontroller as BackendUsercontroller;
use Illuminate\Support\Facades\Route;





//! Route for Reset Database and Optimize Clear

//! Route for Dashboard
Route::middleware(['auth:web'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//! Route for Profile Settings
Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'index')->name('profile.setting');
    Route::patch('/update-profile', 'UpdateProfile')->name('update.profile');
    Route::put('/update-profile-password', 'UpdatePassword')->name('update.Password');
    Route::post('/update-profile-picture', 'UpdateProfilePicture')->name('update.profile.picture');
});

//! Route for System Settings
Route::controller(SystemSettingController::class)->group(function () {
    Route::get('/system-setting', 'index')->name('system.index');
    Route::patch('/system-setting', 'update')->name('system.update');
});

//! Route for Mail Settings
Route::controller(MailSettingController::class)->group(function () {
    Route::get('/mail-setting', 'index')->name('mail.setting');
    Route::patch('/mail-setting', 'update')->name('mail.update');
});

//! Route for Stripe Settings
Route::controller(StripeSettingController::class)->group(function () {
    Route::get('/stripe-setting', 'index')->name('stripe.index');
    Route::patch('/stripe-setting', 'update')->name('stripe.update');
});

//! Route for Dynamic Page Settings
Route::controller(DynamicPageController::class)->group(function () {
    Route::get('/dynamic-page', 'index')->name('dynamic_page.index');
    Route::get('/dynamic-page/create', 'create')->name('dynamic_page.create');
    Route::post('/dynamic-page/store', 'store')->name('dynamic_page.store');
    Route::get('/dynamic-page/edit/{id}', 'edit')->name('dynamic_page.edit');
    Route::patch('/dynamic-page/update/{id}', 'update')->name('dynamic_page.update');
    Route::get('/dynamic-page/status/{id}', 'status')->name('dynamic_page.status');
    Route::delete('/dynamic-page/delete/{id}', 'destroy')->name('dynamic_page.destroy');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/category', 'index')->name('category.index');
    Route::post('/category', 'store')->name('category.store');
    Route::get('/category/create', 'create')->name('category.create');
    Route::put('/category/update/{id}', 'update')->name('category.update');
    Route::get('/category/edit/{id}', 'edit')->name('category.edit');
    Route::get('/category/destory/{id}', 'destory')->name('category.destory');

});

Route::middleware(['auth:web'])->group(function () {
    Route::get('/allusers', [BackendUsercontroller::class, 'index'])->name('user.index');
    Route::delete('/allusers/{id}', [BackendUsercontroller::class, 'destroy'])->name('user.destroy');
});

//
