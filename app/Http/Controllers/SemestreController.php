<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semestre;
class SemestreController extends Controller
{
    public function index(Request $request)
    {        return response()->json(Semestre::all());

    }

    public function store(Request $request)
    {
          $request->validate([
            'libelle' => 'required|string',
        ]);
        $semestre = Semestre::create($request->only('libelle'));
        return response()->json($semestre, 201);
    
    }
    public function update(Request $request, $id)
    {
        $semestre = Semestre::findOrFail($id);
        $request->validate([
            'libelle' => 'required|string',
        ]);
        $semestre->update($request->only('libelle'));
        return response()->json($semestre);
    }

    public function destroy($id)
    {
        Semestre::findOrFail($id)->delete();
        return response()->json(['message' => 'Semestre supprimé.']);
    }
}
