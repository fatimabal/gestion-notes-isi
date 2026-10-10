<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Lister tous les utilisateurs
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

    // Bloquer un utilisateur
    public function bloquer($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $user->update(['bloque' => true]);
        return response()->json(['message' => 'Utilisateur bloqué avec succès'], 200);
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé avec succès'], 200);
    }
}