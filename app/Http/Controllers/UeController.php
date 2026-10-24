<?php
namespace App\Http\Controllers;

use App\Models\Ue;
use Illuminate\Http\Request;

class UeController extends Controller
{
    public function index()
    {
        return response()->json(Ue::with('semestre')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle'     => 'required|string',
            'semestre_id' => 'required|exists:semestres,id',
        ]);
        $ue = Ue::create($request->only('libelle', 'semestre_id'));
        return response()->json($ue, 201);
    }

    public function update(Request $request, $id)
    {
        $ue = Ue::findOrFail($id);
        $request->validate([
            'libelle'     => 'required|string',
            'semestre_id' => 'required|exists:semestres,id',
        ]);
        $ue->update($request->only('libelle', 'semestre_id'));
        return response()->json($ue);
    }

    public function destroy($id)
    {
        Ue::findOrFail($id)->delete();
        return response()->json(['message' => 'UE supprimée.']);
    }
}