<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
class UserController extends Controller
{
    // Lister tous les utilisateurs
   public function index()
{
    $users = User::with([
    'enseignant',
    'etudiant.inscription.classe',
    'parentEtudiant',
    'chefDepartement',
])->get();
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
// Voir son profil
public function profile(Request $request)
{
    $user = $request->user();
    $etudiant = null;

    if ($user->role === 'etudiant') {
        $etudiant = \App\Models\Etudiant::where('user_id', $user->id)->first();
    }

    $inscription = \App\Models\Inscription::where('etudiant_id', $etudiant->id)
    ->with('classe')
    ->latest()
    ->first();

return response()->json([
    'id'      => $user->id,
    'nom'     => $user->nom,
    'prenom'  => $user->prenom,
    'email'   => $user->email,
    'role'    => $user->role,
    'etudiant' => $etudiant ? [
        'matricule'     => $etudiant->matricule ?? $this->genererMatricule($user, $etudiant),
        'dateNaissance' => $etudiant->dateNaissance,
        'lieuNaissance' => $etudiant->lieuNaissance,
        'filiere'       => $etudiant->filiere,
        'classe'        => $inscription?->classe?->nomClasse ?? '—',
    ] : null,
], 200);
}

// Modifier son profil
public function updateProfile(Request $request)
{
    $user = $request->user();
    $request->validate([
        'nom'    => 'nullable|string|max:255',
        'prenom' => 'nullable|string|max:255',
    ]);
    $user->update($request->only(['nom', 'prenom']));
    return response()->json($user, 200);
}
private function genererMatricule($user, $etudiant): string
{
    $classe = \App\Models\Inscription::where('etudiant_id', $etudiant->id)
        ->with('classe')
        ->latest()
        ->first()?->classe?->nomClasse ?? 'CLS';

    $moisNaissance = $etudiant->dateNaissance
        ? \Carbon\Carbon::parse($etudiant->dateNaissance)->format('m')
        : '00';

    $initiales = strtoupper(
        substr($user->prenom ?? 'X', 0, 1) .
        substr($user->nom ?? 'X', 0, 1)
    );

    $matricule = strtoupper($classe) . '-' . $moisNaissance . '-' . $initiales;

    // Sauvegarder en base
    $etudiant->update(['matricule' => $matricule]);

    return $matricule;
}
}