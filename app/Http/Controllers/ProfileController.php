<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user =$request->user();
        return response()->json(['user'=>$user],200);
    }
    public function update(Request $request){
        $request->validate([
        'nom'=>'sometimes|string',
        'prenom' => 'sometimes|string',
        'telephone' => 'sometimes|string',

     ]);
    $user =$request->user();
    $user->update([
        'nom'=>$request->nom,
        'prenom'=>$request->prenom,
        'telephone'=>$request->telephone,
    ]);
    return response()->json([
        'message' =>'Profil mis à jour avec succés',
        'user'=>$user
    ],200);
    }
}
