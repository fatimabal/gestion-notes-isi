<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filiere;

class FiliereController extends Controller
{
    public function index()
    {
        return response()->json(['filieres' => Filiere::all()], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'diplome' => 'required|string',
            'departement_id' => 'required|exists:departements,id'
        ]);
        $filiere = Filiere::create($request->all());
        return response()->json(['filiere' => $filiere], 201);
    }
}
