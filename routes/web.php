<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResetController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
});

Route::get('/user-dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('user.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/reset', [ResetController::class, 'RunMigrations'])->name('reset');
Route::get('/stroagelink', [ResetController::class, 'stroagelink']);


Route::get('/test-email', function () {
    Mail::raw('This is a test email sent via Mailtrap!', function ($message) {
        $message->to('test@domain.com')
            ->subject('Test Email');
    });
    return 'Test email sent!';
});

require __DIR__ . '/auth.php';
require __DIR__ . '/backend.php';
