<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TripDate;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class TripDateController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/trips/{tripId}/dates",
     *     summary="Lister toutes les périodes pour un voyage spécifique",
     *     tags={"Trip Dates"},
     *     @OA\Parameter(
     *         name="tripId",
     *         in="path",
     *         required=true,
     *         description="ID du voyage",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des périodes de voyage récupérée avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="trip_dates", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="start_date", type="string", example="2024-01-01"),
     *                     @OA\Property(property="end_date", type="string", example="2024-01-07"),
     *                     @OA\Property(property="price", type="number", example=500),
     *                     @OA\Property(property="max_participants", type="integer", example=20)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index($tripId)
    {
        $tripDates = TripDate::where('trip_id', $tripId)->get();

        return response()->json([
            'trip_dates' => $tripDates
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/trips/{tripId}/dates",
     *     summary="Ajouter une nouvelle période de voyage",
     *     tags={"Trip Dates"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="tripId",
     *         in="path",
     *         required=true,
     *         description="ID du voyage",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"start_date", "end_date", "price", "max_participants"},
     *             @OA\Property(property="start_date", type="string", format="date", example="2024-01-01"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2024-01-07"),
     *             @OA\Property(property="price", type="number", example=500),
     *             @OA\Property(property="max_participants", type="integer", example=20)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Période de voyage créée avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="trip_date", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="start_date", type="string", example="2024-01-01"),
     *                 @OA\Property(property="end_date", type="string", example="2024-01-07"),
     *                 @OA\Property(property="price", type="number", example=500),
     *                 @OA\Property(property="max_participants", type="integer", example=20)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé. Vous devez être administrateur.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Accès refusé. Vous devez être administrateur.")
     *         )
     *     )
     * )
     */
    public function store(Request $request, $tripId)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        $validatedData = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'price' => 'nullable|numeric',
            'max_participants' => 'nullable|integer',
        ]);

        $tripDate = new TripDate($validatedData);
        $tripDate->trip_id = $tripId;
        $tripDate->save();

        return response()->json([
            'trip_date' => $tripDate
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/trips/dates/{id}",
     *     summary="Voir les détails d'une période de voyage",
     *     tags={"Trip Dates"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la période de voyage",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la période de voyage récupérés avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="trip_date", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="start_date", type="string", example="2024-01-01"),
     *                 @OA\Property(property="end_date", type="string", example="2024-01-07"),
     *                 @OA\Property(property="price", type="number", example=500),
     *                 @OA\Property(property="max_participants", type="integer", example=20)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Période de voyage non trouvée",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Période de voyage non trouvée")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $tripDate = TripDate::with('trip')->findOrFail($id);
        return response()->json(['trip_date' => $tripDate]);
    }

    /**
     * @OA\Put(
     *     path="/api/trips/dates/{id}",
     *     summary="Mettre à jour une période de voyage existante",
     *     tags={"Trip Dates"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la période de voyage",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="start_date", type="string", format="date", example="2024-01-01"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2024-01-07"),
     *             @OA\Property(property="price", type="number", example=500),
     *             @OA\Property(property="max_participants", type="integer", example=20)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Période de voyage mise à jour avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="trip_date", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="start_date", type="string", example="2024-01-01"),
     *                 @OA\Property(property="end_date", type="string", example="2024-01-07"),
     *                 @OA\Property(property="price", type="number", example=500),
     *                 @OA\Property(property="max_participants", type="integer", example=20)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé. Vous devez être administrateur.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Accès refusé. Vous devez être administrateur.")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        $tripDate = TripDate::findOrFail($id);

        $validatedData = $request->validate([
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
            'price' => 'numeric',
            'max_participants' => 'integer',
        ]);

        $tripDate->update($validatedData);

        return response()->json([
            'trip_date' => $tripDate
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/trips/dates/{id}",
     *     summary="Supprimer une période de voyage",
     *     tags={"Trip Dates"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la période de voyage",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Période de voyage supprimée avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Période de voyage supprimée avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé. Vous devez être administrateur.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Accès refusé. Vous devez être administrateur.")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        $tripDate = TripDate::findOrFail($id);
        $tripDate->delete();

        return response()->json([
            'message' => 'Période de voyage supprimée avec succès'
        ]);
    }
}
