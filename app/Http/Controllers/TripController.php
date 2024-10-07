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


    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'pack_type' => 'required|in:standard,premium',
            'destination' => 'required|in:Europe,Amérique,Afrika,Asie,Australie',
            'duration' => 'required|integer',
            'image' => 'nullable|image|max:2048', 
        ]);
        $trip = Trip::create($validatedData);
        $this->storeImage($request, $trip);
        return response()->json([
            'trip' => $trip,
        ], 201);
    }

    public function show($id)
    {
        $trip = Trip::findOrFail($id);

        return response()->json([
            'trip' => $trip
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }
        $trip = Trip::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'pack_type' => 'in:standard,premium',
            'destination' => 'in:Europe,Amérique,Afrika,Asie,Australie',
            'duration' => 'integer',
            'image' => 'nullable|image|max:2048', 
        ]);

        $trip->update($validatedData);
        $this->storeImage($request, $trip);
        return response()->json([
            'trip' => $trip
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }
        $trip = Trip::findOrFail($id);
        if ($trip->image) {
            Storage::disk('public')->delete(str_replace('storage/', '', $trip->image));
        }
        $trip->delete();
        return response()->json([
            'message' => 'Voyage supprimé avec succès'
        ]);
    }


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
