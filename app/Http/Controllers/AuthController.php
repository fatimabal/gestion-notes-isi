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
    'token' => $token
],   200);
 }
}