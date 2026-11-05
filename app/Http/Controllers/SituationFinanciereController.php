<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SituationFinanciere;

class SituationFinanciereController extends Controller
{
    public function index()
    {
        $situations = SituationFinanciere::with('etudiant.user')->get();
        return response()->json($situations, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'estAJour' => 'required|boolean',
            'dateVerification' => 'required|date',
            'etudiant_id' => 'required|exists:etudiants,id'
        ]);
        $situation = SituationFinanciere::create($request->all());
        return response()->json($situation, 201);
    }

    public function regulariser($id)
    {
        $situation = SituationFinanciere::find($id);
        if (!$situation) return response()->json(['message' => 'Non trouvé'], 404);
        $situation->update(['estAJour' => true, 'dateVerification' => now()->toDateString()]);
        return response()->json($situation, 200);
    }
}