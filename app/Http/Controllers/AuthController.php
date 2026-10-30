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
            'nom' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'prenom' => 'required|string',
            'role' => 'required|in:etudiant,enseignant,scolarite,parent',
            'telephone' => 'required|string'
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'telephone' => $request->telephone
        ]);
        // Créer le profil lié selon le rôle
        if ($request->role === 'etudiant') {
            \App\Models\Etudiant::create(['user_id' => $user->id]);
        } elseif ($request->role === 'enseignant') {
            \App\Models\Enseignant::create(['user_id' => $user->id]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }
    public function login(Request $request)
    {
     $request->validate([
        'email'=>'required|email',
        'password' => 'required|min:6'

     ]);
     if(!Auth::attempt(['email'=> $request->email,'password'=>$request->password])){
        return response()->json(['message'=>'Identifiants incorrects'],401);
     }
     $user=Auth::user();
     $token=$user->createToken('auth_token')->plainTextToken;
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
    'message' => 'Déconnexion réussie'
], 200);
 }
}