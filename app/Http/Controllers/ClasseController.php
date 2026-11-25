<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index() {
    return Classe::with('filiere')->get();
}

    public function store(Request $request)
    {
        $request->validate([
            'nomClasse' => 'required|string',
            'niveau'    => 'required|string',
            'filiere_id' => 'required|exists:filieres,id',
        ]);

        $classe = Classe::create($request->only('nomClasse', 'niveau', 'filiere_id'));

        return response()->json($classe, 201);
    }

    public function update(Request $request, $id)
    {
        $classe = Classe::findOrFail($id);
        $request->validate([
            'nomClasse'  => 'required|string',
            'niveau'     => 'required|string',
            'filiere_id' => 'required|exists:filieres,id',
        ]);
        $classe->update($request->only('nomClasse', 'niveau', 'filiere_id'));

        return response()->json($classe);
    }

    public function destroy($id)
    {
        $classe = Classe::findOrFail($id);

        // Vérifier s'il y a des inscriptions liées
        if ($classe->inscriptions()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer cette classe : elle contient des inscriptions.'
            ], 422);
        }

        $classe->delete();

        return response()->json(['message' => 'Classe supprimée.']);
    }
}