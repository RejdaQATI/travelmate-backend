<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TripDate;
use Illuminate\Support\Facades\Auth;

class TripDateController extends Controller
{
    /**
     * Lister toutes les périodes pour un voyage spécifique (accessible à tous les utilisateurs)
     */
    public function index($tripId)
    {
        $tripDates = TripDate::where('trip_id', $tripId)->get();

        return response()->json([
            'trip_dates' => $tripDates
        ]);
    }

    /**
     * Ajouter une nouvelle période de voyage (admin uniquement)
     */
    public function store(Request $request, $tripId)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        // Valider les données de la requête
        $validatedData = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'price' => 'nullable|numeric',
            'max_participants' => 'nullable|integer',
        ]);

        // Créer la période de voyage
        $tripDate = new TripDate($validatedData);
        $tripDate->trip_id = $tripId;
        $tripDate->save();

        return response()->json([
            'trip_date' => $tripDate
        ], 201);
    }

    /**
     * Voir les détails d'une période spécifique (accessible à tous les utilisateurs)
     */

    public function show($id)
    {
        $tripDate = TripDate::with('trip')->findOrFail($id);
        return response()->json(['trip_date' => $tripDate]);
    }
    
    /**
     * Mettre à jour une période de voyage existante (admin uniquement)
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        // Trouver la période de voyage à mettre à jour
        $tripDate = TripDate::findOrFail($id);

        // Valider les données de la requête
        $validatedData = $request->validate([
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
            'price' => 'numeric',
            'max_participants' => 'integer',
        ]);

        // Mettre à jour la période de voyage
        $tripDate->update($validatedData);

        return response()->json([
            'trip_date' => $tripDate
        ]);
    }

    /**
     * Supprimer une période de voyage (admin uniquement)
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        // Trouver et supprimer la période de voyage
        $tripDate = TripDate::findOrFail($id);
        $tripDate->delete();

        return response()->json([
            'message' => 'Période de voyage supprimée avec succès'
        ]);
    }
}
