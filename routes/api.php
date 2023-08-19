<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\AlertTemplateController;
use App\Http\Controllers\FlightScheduleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;


Route::group(['middleware' => 'api', 'prefix' => 'auth',], function($router) {
    Route::post('/login', [JWTController::class, 'login']);
    Route::get('/logout', [JWTController::class, 'logout']);
    Route::get('/profile', [JWTController::class, 'profile']);
    Route::post('/validate-account', [JWTController::class, 'validateEmail']);
    Route::post('/check-otp', [JWTController::class, 'check_otp']);
    Route::post('/add-user', [JWTController::class, 'addUserDetails']);
    Route::get('/all-users', [JWTController::class, 'allUserDetails']);
    Route::get('/show-user/{id}', [JWTController::class, 'showUserDetailsById']);
    Route::delete('/delete-user/{id}', [JWTController::class, 'destroyUserDetailsById']);

});



Route::group(['middleware' => 'api', 'prefix' => 'user',], function($router) {
    Route::post('/add-Agent', [UserController::class, 'createAgent']);
    Route::put('/edit-Agent/{id}', [UserController::class, 'editAgent']);
    Route::delete('/delete-Agent/{id}', [UserController::class, 'deleteAgent']);
    Route::get('/all-Agents', [UserController::class, 'getAllAgents']);

});



Route::group(['middleware' => 'api', 'prefix' => 'dashboard',], function($router) {
    Route::get('/statistics/{dateTimeRange?}', [DashboardController::class, 'fetchStats']);

});

Route::group(['middleware' => 'api', 'prefix' => 'constant'], function () {
    // Airplane routes
    Route::post('/add-airplane', [ConstantsController::class, 'createAirplane']);
    Route::put('/edit-airplane/{id}', [ConstantsController::class, 'editAirplane']);
    Route::delete('/delete-airplane/{id}', [ConstantsController::class, 'deleteAirplane']);
    Route::get('/all-airplanes', [ConstantsController::class, 'getAllAirPlane']);

    // Call Type routes
    Route::post('/add-call-type', [ConstantsController::class, 'createCallType']);
    Route::put('/edit-call-type/{id}', [ConstantsController::class, 'editCallType']);
    Route::delete('/delete-call-type/{id}', [ConstantsController::class, 'deleteCallType']);
    Route::get('/all-call-types', [ConstantsController::class, 'getAllCallTypes']);
});



Route::group(['middleware' => 'api', 'prefix' => 'template'], function ($router) {
    
    Route::post('/add-alert-template', [AlertTemplateController::class, 'create']);
    Route::put('/edit-alert-template/{id}', [AlertTemplateController::class, 'edit']);
    Route::delete('/delete-alert-template/{id}', [AlertTemplateController::class, 'delete']);
    Route::get('/all-alert-templates', [AlertTemplateController::class, 'getAll']);
});


Route::group(['middleware' => 'api', 'prefix' => 'flight-schedule'], function ($router) {
    Route::post('/add', [FlightScheduleController::class, 'create']);
    Route::put('/edit/{id}', [FlightScheduleController::class, 'edit']);
    Route::delete('/delete/{id}', [FlightScheduleController::class, 'delete']);
    Route::get('/all', [FlightScheduleController::class, 'getAll']);
});


Route::group(['middleware' => 'api', 'prefix' => 'notification'], function ($router) {
  
    // Routes for NotificationController
    Route::post('/send', [NotificationController::class, 'push']);
    Route::get('/list', [NotificationController::class, 'index']);
    Route::get('/show/{id}', [NotificationController::class, 'show']);
    Route::put('/edit/{notification}', [NotificationController::class, 'update']);
    Route::delete('/delete/{notification}', [NotificationController::class, 'destroy']);
});







