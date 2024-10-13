<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Voir le profil de l'utilisateur connecté",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Détails du profil de l'utilisateur",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="isAdmin", type="boolean", example=false)
     *             )
     *         )
     *     )
     * )
     */
    public function showProfile()
    {
        $user = auth()->user();
    
        return response()->json([
            'user' => $user,
            'isAdmin' => $user->isAdmin()  
        ]);
    }
    
/**
 * @OA\Put(
 *     path="/api/profile",
 *     summary="Update the profile of the logged-in user",
 *     tags={"Users"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *             @OA\Property(property="password", type="string", example="newpassword123"),
 *             @OA\Property(property="birthdate", type="string", format="date", example="1990-01-01"),
 *             @OA\Property(property="phone_number", type="string", example="+1234567890")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Profile updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *                 @OA\Property(property="birthdate", type="string", format="date", example="1990-01-01"),
 *                 @OA\Property(property="phone_number", type="string", example="+1234567890")
 *             )
 *         )
 *     )
 * )
 */
public function updateProfile(Request $request)
{
    $user = auth()->user();

    $validatedData = $request->validate([
        'name' => 'string|max:255',
        'email' => 'string|email|max:255|unique:users,email,' . $user->id,
        'password' => 'string|min:8|nullable',
        'birthdate' => 'nullable|date',
        'phone_number' => 'nullable|string|max:15',
    ]);

    if ($request->input('password')) {
        $validatedData['password'] = Hash::make($request->input('password'));
    }

    $user->update($validatedData);

    return response()->json([
        'user' => $user
    ]);
}
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Lister tous les utilisateurs (admin uniquement)",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des utilisateurs récupérée avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="users", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com")
     *                 )
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
    public function index()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        $users = User::all();

        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Voir un utilisateur spécifique (admin uniquement)",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'utilisateur",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com")
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
    public function show($id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        $user = User::findOrFail($id);

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Mettre à jour un utilisateur (admin uniquement)",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", example="newpassword123"),
     *             @OA\Property(property="role", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur mis à jour avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com")
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
        $userToUpdate = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $userToUpdate->id,
            'password' => 'string|min:8|nullable',
            'birthdate' => 'date|nullable', 
            'phone_number' => 'string|max:15|nullable',
            'role' => 'in:admin,user',
        ]);

        if ($request->input('password')) {
            $validatedData['password'] = Hash::make($request->input('password'));
        }

        $userToUpdate->update($validatedData);

        return response()->json([
            'user' => $userToUpdate
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Supprimer un utilisateur (admin uniquement)",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur supprimé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Utilisateur supprimé avec succès")
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

        $userToDelete = User::findOrFail($id);
        $userToDelete->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    }
}
