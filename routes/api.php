<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MenuapiController;
use App\Http\Controllers\Api\PageApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ServicesApiController;
use App\Http\Controllers\Api\ServicetypesApiController;
use App\Http\Controllers\Api\AppointmentApiController;
use App\Http\Controllers\Api\ForgotPasswordController;

Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/register', [UserApiController::class, 'createUser']);
Route::post('/verifyOtp', [UserApiController::class, 'verifyUserOtp']);
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/users', [UserApiController::class, 'index']);
    Route::get('/users/{userId}', [UserApiController::class, 'getUser']);
    Route::get('/users/category/{categoryId}', [UserApiController::class, 'getUserByCategory']);
    Route::put('/users/{userId}', [UserApiController::class, 'updateUser']);
    Route::get('/menus', [MenuapiController::class, 'index']);
    Route::get('/pages', [PageApiController::class, 'index']);
    Route::get('/appointments', [AppointmentApiController::class, 'index']);
    Route::get('/appointments/{service_provider_id}', [AppointmentApiController::class, 'get_appointment']);
    Route::get('/orders', [OrderApiController::class, 'index']);
    Route::post('/orders', [OrderApiController::class, 'saveOrder']);
    Route::get('/orders/user/{user_id}', [OrderApiController::class, 'getOrdersByUser']);
    Route::get('/orders/{order_id}', [OrderApiController::class, 'getOrderById']);
    Route::get('/services', [ServicesApiController::class, 'index']);
    Route::get('/services/{category_id}', [ServicesApiController::class, 'getServiceByCategory']);
    Route::get('/servicetypes', [ServicetypesApiController::class, 'index']);
    Route::get('/servicetypes/{parentId}', [ServicetypesApiController::class, 'getServiceTypesByParent']);
    Route::post('/logout', [AuthController::class, 'logout']);
});



// Add a fallback route for unauthorized access
Route::fallback(function () {
    return response()->json(['error' => 'Unauthorized. Please log in first.'], 401);
});