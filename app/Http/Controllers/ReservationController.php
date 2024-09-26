<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\TripDate;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Lister les réservations de l'utilisateur connecté (accessible à tous les utilisateurs)
     */
    public function userReservations()
    {
        $reservations = Reservation::where('user_id', auth()->id())->get();

        return response()->json([
            'reservations' => $reservations
        ]);
    }

    public function allReservations()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        // Récupérer toutes les réservations
        $reservations = Reservation::all();

        return response()->json([
            'reservations' => $reservations
        ]);
    }
    /**
     * Faire une nouvelle réservation (accessible à tous les utilisateurs)
     */
    public function store(Request $request)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'trip_date_id' => 'required|exists:trip_dates,id',
            'number_of_participants' => 'required|integer',
        ]);

        $tripDate = TripDate::findOrFail($validatedData['trip_date_id']);
        $currentParticipants = Reservation::where('trip_date_id', $tripDate->id)
            ->where('status', 'confirmed')
            ->sum('number_of_participants');

        // Vérifier s'il y a assez de places disponibles
        if ($currentParticipants + $validatedData['number_of_participants'] > $tripDate->max_participants) {
            return response()->json(['error' => 'Le nombre maximum de participants est atteint.'], 400);
        }

        // Créer la réservation
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'trip_date_id' => $validatedData['trip_date_id'],
            'number_of_participants' => $validatedData['number_of_participants'],
            'status' => 'pending',
        ]);

        return response()->json([
            'reservation' => $reservation
        ], 201);
    }

    /**
     * Voir les détails d'une réservation spécifique (accessible à tous les utilisateurs)
     */
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);

        return response()->json([
            'reservation' => $reservation
        ]);
    }

    /**
     * Confirmer ou annuler une réservation (admin uniquement)
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        // Valider les données de la requête
        $validatedData = $request->validate([
            'status' => 'required|in:confirmed,cancelled,pending',
        ]);

        // Mettre à jour le statut de la réservation
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => $validatedData['status']]);

        return response()->json([
            'reservation' => $reservation
        ]);
    }
}
