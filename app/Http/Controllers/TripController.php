<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TripController extends Controller
{
    /**
     * Lister tous les voyages (accessible à tous les utilisateurs)
     */
    public function index()
    {
        $trips = Trip::all();

        return response()->json([
            'trips' => $trips
        ]);
    }

    /**
     * Ajouter un nouveau voyage (admin uniquement)
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        // Valider les données de la requête
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'pack_type' => 'required|in:standard,premium',
            'destination' => 'required|in:Europe,Amérique,Afrika,Asie,Australie',
            'duration' => 'required|integer',
            'image' => 'nullable|image|max:2048', // Validation de l'image (facultatif)
        ]);

        // Créer un nouveau trip sans l'image d'abord
        $trip = Trip::create($validatedData);

        // Gérer l'upload de l'image si présente
        $this->storeImage($request, $trip);

        return response()->json([
            'trip' => $trip,
        ], 201);
    }

    /**
     * Voir un voyage spécifique (accessible à tous les utilisateurs)
     */
    public function show($id)
    {
        $trip = Trip::findOrFail($id);

        return response()->json([
            'trip' => $trip
        ]);
    }

    /**
     * Mettre à jour un voyage existant (admin uniquement)
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        // Trouver le voyage à mettre à jour
        $trip = Trip::findOrFail($id);

        // Valider les données de la requête
        $validatedData = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'pack_type' => 'in:standard,premium',
            'destination' => 'in:Europe,Amérique,Afrika,Asie,Australie',
            'duration' => 'integer',
            'image' => 'nullable|image|max:2048', // Validation de l'image (facultatif)
        ]);

        // Mettre à jour les autres champs du trip
        $trip->update($validatedData);

        // Gérer l'upload de l'image si présente
        $this->storeImage($request, $trip);

        return response()->json([
            'trip' => $trip
        ]);
    }

    /**
     * Supprimer un voyage (admin uniquement)
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        // Trouver et supprimer le voyage
        $trip = Trip::findOrFail($id);

        // Supprimer l'image associée s'il y en a une
        if ($trip->image) {
            Storage::disk('public')->delete(str_replace('storage/', '', $trip->image));
        }

        $trip->delete();

        return response()->json([
            'message' => 'Voyage supprimé avec succès'
        ]);
    }

    /**
     * Méthode pour gérer l'upload de l'image.
     */
    private function storeImage(Request $request, Trip $trip)
    {
        if ($request->hasFile('image')) {
            if ($trip->image) {
                Storage::disk('public')->delete(str_replace('storage/', '', $trip->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('images/trips', $imageName, 'public');
            $trip->image = 'storage/images/trips/' . $imageName;
            $trip->save();
        }
    }

    public function getPopularTrips()
    {
        $popularTrips = Trip::inRandomOrder()->take(5)->get();
        return response()->json([
            'success' => true,
            'trips' => $popularTrips
        ]);
    }

    public function getMaldivesTrips() {
        $maldivesTrips = Trip::where('title', 'LIKE', '%Maldives%')->get();
        return response()->json(['trips' => $maldivesTrips]);
    }
    
}
