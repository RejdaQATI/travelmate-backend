<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripDateController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CityController;

Route::get('/cities', [CityController::class, 'index']);       // Lister les villes
Route::get('/cities/{id}', [CityController::class, 'show']);    // Détails d'une ville

// Routes publiques (authentification)
Route::post('/register', [AuthController::class, 'register']); // Inscription
Route::post('/login', [AuthController::class, 'login']);       // Connexion
Route::get('/trips', [TripController::class, 'index']); 
Route::get('/trips/dates/{id}', [TripDateController::class, 'show']); // Récupérer les détails d'une période de voyage spécifique
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); // Déconnexion
Route::get('/trips/{id}', [TripController::class, 'show']);       // Voir un voyage spécifique
Route::get('/trips/{tripId}/dates', [TripDateController::class, 'index']); // Lister les périodes d'un voyage
Route::get('/populartrips', [TripController::class, 'getPopularTrips']);
Route::get('/maldives', [TripController::class, 'getMaldivesTrips']);

// Routes pour l'utilisateur connecté (gestion de son propre profil)
Route::middleware(['auth:sanctum'])->group(function () {
    // Gestion du profil utilisateur
    Route::get('/profile', [UserController::class, 'showProfile']);   // Voir le profil
    Route::put('/profile', [UserController::class, 'updateProfile']); // Mettre à jour le profil

    // Gestion des voyages et périodes de voyage pour les utilisateurs
     // Lister tous les voyages


    // Gestion des réservations pour les utilisateurs
    Route::post('/reservations', [ReservationController::class, 'store']); // Faire une réservation
    Route::get('/my-reservations', [ReservationController::class, 'userReservations']); // Voir mes réservations
    Route::get('/my-reservations/{id}', [ReservationController::class, 'show']); // Voir une réservation spécifique
});

// Routes pour l'administrateur (gestion des utilisateurs, voyages, périodes et réservations)
Route::middleware(['auth:sanctum'])->group(function () {
    // Gestion des utilisateurs (admin)
    Route::get('/users', [UserController::class, 'index']);         // Lister tous les utilisateurs
    Route::get('/users/{id}', [UserController::class, 'show']);     // Voir un utilisateur spécifique
    Route::put('/users/{id}', [UserController::class, 'update']);   // Mettre à jour un utilisateur (changer le rôle, etc.)
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Supprimer un utilisateur

    // Gestion des voyages (admin)
    Route::post('/trips', [TripController::class, 'store']);        // Ajouter un nouveau voyage
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
