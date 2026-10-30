<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // Lister tous les utilisateurs
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    // Modifier un utilisateur
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $request->validate([
            'nom'    => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'role'   => 'nullable|string',
        ]);
        $user->update($request->only(['nom', 'prenom', 'role']));
        return response()->json($user, 200);
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

    // Réinitialiser le mot de passe
    public function reinitialiserMotDePasse($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $nouveauMotDePasse = 'Password123!';
        $user->update(['password' => bcrypt($nouveauMotDePasse)]);
        return response()->json([
            'message'          => 'Mot de passe réinitialisé',
            'nouveau_mot_de_passe' => $nouveauMotDePasse,
        ], 200);
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
    public function indexEtudiants()
{
    $etudiants = User::where('role', 'etudiant')->get();
    return response()->json($etudiants, 200);
}
}