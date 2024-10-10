<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

class TripController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/trips",
     *     summary="Lister tous les voyages",
     *     tags={"Trips"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste de tous les voyages",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="trips", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Voyage à Paris"),
     *                     @OA\Property(property="destination", type="string", example="Europe")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $trips = Trip::all();
        return response()->json([
            'trips' => $trips
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/trips",
     *     summary="Créer un nouveau voyage (admin seulement)",
     *     tags={"Trips"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"title", "description", "pack_type", "destination", "duration"},
     *             @OA\Property(property="title", type="string", example="Voyage à Paris"),
     *             @OA\Property(property="description", type="string", example="Une belle aventure en Europe."),
     *             @OA\Property(property="pack_type", type="string", enum={"standard", "premium"}, example="standard"),
     *             @OA\Property(property="destination", type="string", enum={"Europe", "Amérique", "Afrika", "Asie", "Australie"}, example="Europe"),
     *             @OA\Property(property="duration", type="integer", example=7),
     *             @OA\Property(property="image", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Voyage créé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="trip", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Voyage à Paris"),
     *                 @OA\Property(property="destination", type="string", example="Europe")
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

    /**
     * @OA\Get(
     *     path="/api/trips/{id}",
     *     summary="Afficher les détails d'un voyage spécifique",
     *     tags={"Trips"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du voyage",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du voyage",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="trip", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Voyage à Paris"),
     *                 @OA\Property(property="destination", type="string", example="Europe")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Voyage non trouvé",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Voyage non trouvé")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $trip = Trip::findOrFail($id);
        return response()->json([
            'trip' => $trip
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/trips/{id}",
     *     summary="Mettre à jour un voyage (admin seulement)",
     *     tags={"Trips"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du voyage",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="Voyage à Paris"),
     *             @OA\Property(property="description", type="string", example="Mise à jour de la description du voyage."),
     *             @OA\Property(property="pack_type", type="string", enum={"standard", "premium"}, example="premium"),
     *             @OA\Property(property="destination", type="string", enum={"Europe", "Amérique", "Afrika", "Asie", "Australie"}, example="Europe"),
     *             @OA\Property(property="duration", type="integer", example=7),
     *             @OA\Property(property="image", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Voyage mis à jour avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="trip", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Voyage à Paris"),
     *                 @OA\Property(property="destination", type="string", example="Europe")
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

    /**
     * @OA\Delete(
     *     path="/api/trips/{id}",
     *     summary="Supprimer un voyage (admin seulement)",
     *     tags={"Trips"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du voyage",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Voyage supprimé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Voyage supprimé avec succès")
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
    
        if (request()->hasFile('image')) {
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => [
                    'secure' => true 
                ]
            ]);

            $filePath = request()->file('image')->getRealPath();

            $uploadResult = (new UploadApi())->upload($filePath, [
                'folder' => 'trips/' . $trip->id, 
            ]);

            $user->update(['image' => $uploadResult['secure_url']]);
        }
    }
    

    /**
     * @OA\Get(
     *     path="/api/trips/popular",
     *     summary="Obtenir les voyages populaires",
     *     tags={"Trips"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des voyages populaires",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="trips", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Voyage à Paris"),
     *                     @OA\Property(property="destination", type="string", example="Europe")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getPopularTrips()
    {
        $popularTrips = Trip::inRandomOrder()->take(5)->get();
        return response()->json([
            'success' => true,
            'trips' => $popularTrips
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/trips/maldives",
     *     summary="Obtenir les voyages aux Maldives",
     *     tags={"Trips"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des voyages aux Maldives",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="trips", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Voyage aux Maldives"),
     *                     @OA\Property(property="destination", type="string", example="Asie")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getMaldivesTrips()
    {
        $maldivesTrips = Trip::where('title', 'LIKE', '%Maldives%')->get();
        return response()->json(['trips' => $maldivesTrips]);
    }
}
