<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        return response()->json(Classe::with('filiere')->get());
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
        Classe::findOrFail($id)->delete();
        return response()->json(['message' => 'Classe supprimée.']);
    }
}