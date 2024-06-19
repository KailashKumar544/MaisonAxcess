<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ServiceProviderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\OTPVerificationController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/show/{order_id}', [DashboardController::class, 'show'])->name('view');
    Route::get('service/{slug}', [ServiceController::class, 'show'])->name('service.show');
    Route::get('/success/{order_id}', [AppointmentController::class, 'order_success'])->name('success.appointment_data');
    Route::get('/', [HomeController::class, 'index'])->name('home');
});

Route::get('/contacter-la-conciergerie', [ContactController::class, 'index'])->name('contact.form');
Route::post('/contacter-la-conciergerie', [ContactController::class, 'submitContactForm'])->name('contact.submit');

Route::get('/verify-otp', [OTPVerificationController::class, 'showVerificationForm'])->name('otp.verify');
Route::post('/verify-otp', [OTPVerificationController::class, 'verifyOTP'])->name('otp.verifi');


Route::get('/service_types/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('register', [RegisterController::class, 'index'])->name('register');

Route::post('/update-service-provider-id', [ServiceProviderController::class, 'updateServiceProviderId'])->name('update.service_provider_id');
Route::get('/admin/service/{id}/show', [RedirectController::class, 'redirectToSlug']);
Route::post('/appointments', [AppointmentController::class, 'insert_appointment'])->name('insert.appointment_data');
Route::get('{slug}', [PageController::class, 'show'])->name('page.show');
Route::get('/admin/page/{id}/show', [RedirectController::class, 'redirectToPageSlug']);

Route::post('/register', [RegisterController::class, 'register']);

Route::get('/visit/{id}', 'Controller@someMethod');








