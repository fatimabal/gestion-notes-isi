<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\Inscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Lister tous les utilisateurs
    public function index()
    {
        $users = User::with([
            'enseignant',
            'etudiant.inscription.classe',
            'parentEtudiant.etudiants.user',
            'chefDepartement',
        ])->get();

        return response()->json($users, 200);
    }

    // Modifier un utilisateur
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $request->validate([
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'role' => 'nullable|string',
        ]);
        $user->update($request->only(['nom', 'prenom', 'role']));

        return response()->json($user, 200);
    }

    // Bloquer un utilisateur
    public function bloquer($id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $user->update(['bloque' => true]);

        return response()->json(['message' => 'Utilisateur bloqué avec succès'], 200);
    }

    // Réinitialiser le mot de passe
    public function reinitialiserMotDePasse($id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $nouveauMotDePasse = 'Password123!';
        $user->update(['password' => bcrypt($nouveauMotDePasse)]);

        return response()->json([
            'message' => 'Mot de passe réinitialisé',
            'nouveau_mot_de_passe' => $nouveauMotDePasse,
        ], 200);
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        DB::beginTransaction();
        try {
            // Supprimer les données liées selon le rôle
            if ($user->role === 'etudiant') {
                $etudiant = $user->etudiant;
                if ($etudiant) {
                    // Supprimer inscriptions, notes, bulletins liés
                    foreach ($etudiant->inscription ?? [] as $insc) {
                        $insc->delete();
                    }
                    $etudiant->delete();
                }
            } elseif ($user->role === 'enseignant') {
                $enseignant = $user->enseignant;
                if ($enseignant) {
                    // Détacher les modules affectés
                    $enseignant->matieres()->detach();
                    $enseignant->delete();
                }
            } elseif ($user->role === 'chef_departement') {
                $user->chefDepartement?->delete();
            } elseif ($user->role === 'scolarite') {
                DB::table('agent_service_examens')->where('user_id', $user->id)->delete();
            } elseif ($user->role === 'comptable') {
                DB::table('comptables')->where('user_id', $user->id)->delete();
            } elseif ($user->role === 'parent') {
                $user->parentEtudiant?->etudiants()->detach();
                $user->parentEtudiant?->delete();
            }

            $user->tokens()->delete();
            $user->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de la suppression : ' . $e->getMessage()], 500);
        }

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
    $inscription = null;

    if ($user->role === 'etudiant') {
        $etudiant = Etudiant::where('user_id', $user->id)->first();
        if ($etudiant) {
            $inscription = Inscription::where('etudiant_id', $etudiant->id)
                ->with('classe')
                ->latest()
                ->first();
        }
    }

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
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
        ]);
        $user->update($request->only(['nom', 'prenom']));

        return response()->json($user, 200);
    }

    private function genererMatricule($user, $etudiant): string
    {
        $classe = Inscription::where('etudiant_id', $etudiant->id)
            ->with('classe')
            ->latest()
            ->first()?->classe?->nomClasse ?? 'CLS';

        $moisNaissance = $etudiant->dateNaissance
            ? Carbon::parse($etudiant->dateNaissance)->format('m')
            : '00';

        $initiales = strtoupper(
            substr($user->prenom ?? 'X', 0, 1).
            substr($user->nom ?? 'X', 0, 1)
        );

        $matricule = strtoupper($classe).'-'.$moisNaissance.'-'.$initiales;

        // Sauvegarder en base
        $etudiant->update(['matricule' => $matricule]);

        return $matricule;
    }

    public function indexEnseignants()
    {
        $enseignants = User::with('enseignant')
            ->where('role', 'enseignant')
            ->get();

        return response()->json($enseignants);
    }

    public function toggleActif($id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $user->update(['actif' => ! $user->actif]);

        return response()->json([
            'message' => $user->actif ? 'Compte activé' : 'Compte désactivé',
            'actif' => $user->actif,
        ], 200);
    }
}