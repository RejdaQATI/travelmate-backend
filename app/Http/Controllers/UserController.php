<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Voir le profil de l'utilisateur connecté (accessible à tous les utilisateurs authentifiés)
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
     * Mettre à jour le profil de l'utilisateur connecté (accessible à tous les utilisateurs authentifiés)
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'string|min:8|nullable',
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
     * Lister tous les utilisateurs (admin uniquement)
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        // Logique pour lister les utilisateurs
        $users = User::all();

        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * Voir un utilisateur spécifique (admin uniquement)
     */
    public function show($id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        // Logique pour voir un utilisateur spécifique
        $user = User::findOrFail($id);

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Mettre à jour un utilisateur (admin uniquement)
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
            'role' => 'in:user,admin',
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
     * Supprimer un utilisateur (admin uniquement)
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Accès refusé. Vous devez être administrateur.'], 403);
        }

        // Logique pour supprimer un utilisateur
        $userToDelete = User::findOrFail($id);
        $userToDelete->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    }
}
