<?php

namespace App\Http\Controllers;

use App\Models\AgentServiceExamen;
use App\Models\ChefDepartement;
use App\Models\Comptable;
use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\Inscription;
use App\Models\ParentEtudiant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|string',
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'actif' => false,
        ]);

        if ($request->role === 'enseignant') {
            Enseignant::create([
                'user_id' => $user->id,
                'specialite' => $request->specialite ?? 'Non renseignée',
                'grade' => $request->grade ?? 'Non renseigné',
            ]);
        } elseif ($request->role === 'etudiant') {
            $prenom = strtoupper(substr($request->prenom, 0, 2));
            $nom = strtoupper(substr($request->nom, 0, 2));
            $date = $request->dateNaissance ?? '0000-00-00';
            $parts = explode('-', $date);
            $mois = isset($parts[1]) ? $parts[1] : '00';
            $jour = isset($parts[2]) ? $parts[2] : '00';
            $matricule = $prenom.$nom.$mois.$jour;

            $etudiant = Etudiant::create([
                'user_id' => $user->id,
                'matricule' => $matricule,
                'dateNaissance' => $request->dateNaissance ?? null,
                'lieuNaissance' => $request->lieuNaissance ?? null,
                'filiere' => $request->filiere ?? null,
                'sexe' => $request->sexe ?? null,
            ]);

            if ($request->classeId) {
                Inscription::create([
                    'etudiant_id' => $etudiant->id,
                    'classe_id' => $request->classeId,
                    'annee' => date('Y'),
                ]);
            }
            if ($request->classeId) {
                Inscription::create([
                    'etudiant_id' => $etudiant->id,
                    'classe_id' => $request->classeId,
                    'annee' => date('Y'),
                ]);
            }
        } elseif ($request->role === 'parent') {
            $parent = ParentEtudiant::create([
                'user_id' => $user->id,
                'telephone' => $request->telephone ?? null,
                'profession' => $request->profession ?? null,
                'adresse' => $request->adresse ?? null,
                'lienParents' => $request->lienParents ?? null,
            ]);
            if ($request->etudiantIds && is_array($request->etudiantIds)) {
                $etudiantsValides = [];
                foreach ($request->etudiantIds as $etudiantId) {
                    $dejaLie = \DB::table('etudiant_parent')
                        ->where('etudiant_id', $etudiantId)
                        ->exists();
                    if (! $dejaLie) {
                        $etudiantsValides[] = $etudiantId;
                    }
                }
                if (! empty($etudiantsValides)) {
                    $parent->etudiants()->attach($etudiantsValides);
                }
            }
        } elseif ($request->role === 'chef_departement') {
            ChefDepartement::create([
                'user_id' => $user->id,
                'specialite' => $request->specialite ?? 'Non renseignée',
                'grade' => $request->grade ?? 'Non renseigné',
                'mandat' => $request->mandat ?? null,
                'dateDebut' => $request->dateDebut ?? null,
                'dateFin' => $request->dateFin ?? null,
            ]);
        } elseif ($request->role === 'comptable') {
            Comptable::create([
                'user_id' => $user->id,
            ]);
        } elseif ($request->role === 'agent_service_examen') {
            AgentServiceExamen::create([
                'user_id' => $user->id,
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (! Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        $user = Auth::user();

        if (! $user->actif) {
            Auth::logout();

            return response()->json(['message' => 'Compte non activé. Contactez l\'administrateur.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'role' => $user->role,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ], 200);
    }
}
