<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nom'      => 'required|string',
            'prenom'   => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role'     => 'required|string',
        ]);

        $user = \App\Models\User::create([
            'nom'      => $request->nom,
            'prenom'   => $request->prenom,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
        ]);

        if ($request->role === 'enseignant') {
            \App\Models\Enseignant::create([
                'user_id'    => $user->id,
                'specialite' => $request->specialite ?? 'Non renseignée',
                'grade'      => $request->grade ?? 'Non renseigné',
            ]);
        } elseif ($request->role === 'etudiant') {
    $prenom = strtoupper(substr($request->prenom, 0, 2));
    $nom    = strtoupper(substr($request->nom, 0, 2));
    $date   = $request->dateNaissance ?? '0000-00-00';
    $parts  = explode('-', $date); // [2005, 11, 22]
    $mois   = isset($parts[1]) ? $parts[1] : '00';
    $jour   = isset($parts[2]) ? $parts[2] : '00';
    $matricule = $prenom . $nom . $mois . $jour; // ex: FASO1122

    \App\Models\Etudiant::create([
        'user_id'       => $user->id,
        'matricule'     => $matricule,
        'dateNaissance' => $request->dateNaissance ?? null,
        'lieuNaissance' => $request->lieuNaissance ?? null,
        'filiere'       => $request->filiere ?? null,
    ]);

        } elseif ($request->role === 'parent') {
            \App\Models\ParentEtudiant::create([
                'user_id'     => $user->id,
                'telephone'   => $request->telephone ?? null,
                'profession'  => $request->profession ?? null,
                'adresse'     => $request->adresse ?? null,
                'lienParents' => $request->lienParents ?? null,
            ]);
        } elseif ($request->role === 'chef_departement') {
            \App\Models\ChefDepartement::create([
                'user_id'    => $user->id,
                'specialite' => $request->specialite ?? 'Non renseignée',
                'grade'      => $request->grade ?? 'Non renseigné',
                'mandat'     => $request->mandat ?? null,
                'dateDebut'  => $request->dateDebut ?? null,
                'dateFin'    => $request->dateFin ?? null,
            ]);
        } elseif ($request->role === 'comptable') {
            \App\Models\Comptable::create([
                'user_id' => $user->id,
            ]);
        } elseif ($request->role === 'agent_service_examen') {
            \App\Models\AgentServiceExamen::create([
                'user_id' => $user->id,
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'   => $user,
            'token'  => $token,
            'nom'    => $user->nom,
            'prenom' => $user->prenom,
            'role'   => $user->role,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ], 200);
    }
}