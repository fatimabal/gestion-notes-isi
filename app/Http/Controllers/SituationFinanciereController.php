<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SituationFinanciere;

class SituationFinanciereController extends Controller
{
    public function index()
    {
        return response()->json(['situations' => SituationFinanciere::all()], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'estAJour' => 'required|boolean',
            'dateVerification' => 'required|date',
            'etudiant_id' => 'required|exists:etudiants,id'
        ]);
        $situation = SituationFinanciere::create($request->all());
        return response()->json(['situation' => $situation], 201);
    }
}
