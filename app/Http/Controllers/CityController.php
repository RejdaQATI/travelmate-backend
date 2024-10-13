<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use OpenApi\Annotations as OA;

class CityController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cities",
     *     summary="Lister toutes les villes",
     *     tags={"Cities"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des villes",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="cities", type="array", 
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Paris"),
     *                     @OA\Property(property="country", type="string", example="France"),
     *                     @OA\Property(property="trip", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Trip to Paris")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $cities = City::with('trip')->get(); 
        return response()->json(['cities' => $cities]);
    }

    /**
     * @OA\Get(
     *     path="/api/cities/{id}",
     *     summary="Afficher les détails d'une ville spécifique",
     *     tags={"Cities"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la ville",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la ville",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="city", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Paris"),
     *                 @OA\Property(property="country", type="string", example="France"),
     *                 @OA\Property(property="trips", type="array", 
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Trip to Paris")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ville non trouvée",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ville non trouvée")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        // Charger la ville avec son voyage associé
        $city = City::with('trip')->find($id);
    
        if (!$city) {
            return response()->json([
                'success' => false,
                'message' => 'Ville non trouvée',
            ], 404);
        }
    
        return response()->json([
            'success' => true,
            'city' => $city,
        ]);
    }
    
}
