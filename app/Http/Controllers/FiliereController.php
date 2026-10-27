<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filiere;

class FiliereController extends Controller
{
    public function index()
    {
        return response()->json(Filiere::with('departement')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'            => 'required|string',
            'diplome'        => 'nullable|string',
            'departement_id' => 'required|exists:departements,id',
        ]);
        $filiere = Filiere::create($request->only('nom', 'diplome', 'departement_id'));
        return response()->json($filiere, 201);
    }

    public function update(Request $request, $id)
    {
        $filiere = Filiere::findOrFail($id);
        $request->validate([
            'nom'            => 'required|string',
            'diplome'        => 'nullable|string',
            'departement_id' => 'required|exists:departements,id',
        ]);
        $filiere->update($request->only('nom', 'diplome', 'departement_id'));
        return response()->json($filiere);
    }

    public function destroy($id)
    {
        Filiere::findOrFail($id)->delete();
        return response()->json(['message' => 'Filière supprimée.']);
    }
}