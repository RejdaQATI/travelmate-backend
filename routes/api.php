<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripDateController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CityController;
use Illuminate\Support\Facades\Mail;


Route::post('/trips', [TripController::class, 'store']);  
Route::get('/cities', [CityController::class, 'index']);      
Route::get('/cities/{id}', [CityController::class, 'show']); 

Route::post('/register', [AuthController::class, 'register']); 
Route::post('/login', [AuthController::class, 'login']); 
Route::post('/logout', [AuthController::class, 'logout']); 


Route::get('/trips', [TripController::class, 'index']); 
Route::get('/trips/dates/{id}', [TripDateController::class, 'show']); 
Route::get('/trips/{id}', [TripController::class, 'show']);       // Voir un voyage spécifique
Route::get('/trips/{tripId}/dates', [TripDateController::class, 'index']); // Lister les périodes d'un voyage
Route::get('/populartrips', [TripController::class, 'getPopularTrips']);
Route::get('/maldives', [TripController::class, 'getMaldivesTrips']);
Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);



Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [UserController::class, 'showProfile']);   
    Route::put('/profile', [UserController::class, 'updateProfile']);





    Route::post('/reservations', [ReservationController::class, 'store']); // Faire une réservation
    Route::get('/my-reservations', [ReservationController::class, 'userReservations']); // Voir mes réservations
    Route::get('/my-reservations/{id}', [ReservationController::class, 'show']); // Voir une réservation spécifique
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);         
    Route::get('/users/{id}', [UserController::class, 'show']);     
    Route::put('/users/{id}', [UserController::class, 'update']);  
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    
    



    // Gestion des voyages (admin)
      // Ajouter un nouveau voyage
    Route::put('/trips/{id}', [TripController::class, 'update']);   // Mettre à jour un voyage existant
    Route::delete('/trips/{id}', [TripController::class, 'destroy']); // Supprimer un voyage

    // Gestion des périodes de voyage (admin)
    Route::post('/trips/{tripId}/dates', [TripDateController::class, 'store']); // Ajouter une nouvelle période de voyage
    Route::put('/trips/dates/{id}', [TripDateController::class, 'update']);     // Mettre à jour une période de voyage
    Route::delete('/trips/dates/{id}', [TripDateController::class, 'destroy']); // Supprimer une période de voyage

    // Gestion des réservations (admin)
    Route::put('/reservations/{id}', [ReservationController::class, 'updateStatus']);
    // Confirmer ou annuler une réservation
    Route::get('/reservations', [ReservationController::class, 'allReservations']);
});
