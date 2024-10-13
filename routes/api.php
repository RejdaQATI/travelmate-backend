<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripDateController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CityController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\PaymentController;



Route::get('/cities', [CityController::class, 'index']);      
Route::get('/cities/{id}', [CityController::class, 'show']); 

Route::post('/register', [AuthController::class, 'register']); 
Route::post('/login', [AuthController::class, 'login']); 
Route::post('/logout', [AuthController::class, 'logout']); 

Route::get('/trips', [TripController::class, 'index']); 
Route::get('/trips/dates/{id}', [TripDateController::class, 'show']); 
Route::get('/trips/{id}', [TripController::class, 'show']);   
Route::get('/trips/{tripId}/dates', [TripDateController::class, 'index']); 
Route::get('/populartrips', [TripController::class, 'getPopularTrips']);
Route::get('/maldives', [TripController::class, 'getMaldivesTrips']);
Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);



Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [UserController::class, 'showProfile']);   
    Route::put('/profile', [UserController::class, 'updateProfile']);

    Route::post('/payment', [PaymentController::class, 'createPayment']);
    Route::post('/trips', [TripController::class, 'store']);  


    Route::post('/reservations', [ReservationController::class, 'store']); 
    Route::get('/my-reservations', [ReservationController::class, 'userReservations']); 
    Route::get('/my-reservations/{id}', [ReservationController::class, 'show']); 
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);         
    Route::get('/users/{id}', [UserController::class, 'show']);     
    Route::put('/users/{id}', [UserController::class, 'update']);  
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/trips/{id}', [TripController::class, 'update']);   
    Route::delete('/trips/{id}', [TripController::class, 'destroy']); 

    Route::post('/trips/{tripId}/dates', [TripDateController::class, 'store']); 
    Route::put('/trips/dates/{id}', [TripDateController::class, 'update']);    
    Route::delete('/trips/dates/{id}', [TripDateController::class, 'destroy']); 
    Route::put('/reservations/{id}', [ReservationController::class, 'updateStatus']);

    Route::get('/reservations', [ReservationController::class, 'allReservations']);
});
