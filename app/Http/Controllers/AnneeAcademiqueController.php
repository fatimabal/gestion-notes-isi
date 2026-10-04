<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnneeAcademique;

class AnneeAcademiqueController extends Controller
{
    public function index()
    {
        return response()->json(['annees' => AnneeAcademique::all()], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date'
        ]);
        $annee = AnneeAcademique::create($request->all());
        return response()->json(['annee' => $annee], 201);
    }
}
