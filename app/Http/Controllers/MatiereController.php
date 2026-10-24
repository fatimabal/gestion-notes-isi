<?php
namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index()
    {
        return response()->json(Matiere::with('ue')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle'     => 'required|string',
            'credit'      => 'required|integer',
            'coefficient' => 'required|integer',
            'ue_id'       => 'required|exists:ues,id',
        ]);
        $matiere = Matiere::create($request->only('libelle', 'credit', 'coefficient', 'ue_id'));
        return response()->json($matiere, 201);
    }

    public function update(Request $request, $id)
    {
        $matiere = Matiere::findOrFail($id);
        $request->validate([
            'libelle'     => 'required|string',
            'credit'      => 'required|integer',
            'coefficient' => 'required|integer',
            'ue_id'       => 'required|exists:ues,id',
        ]);
        $matiere->update($request->only('libelle', 'credit', 'coefficient', 'ue_id'));
        return response()->json($matiere);
    }

    public function destroy($id)
    {
        Matiere::findOrFail($id)->delete();
        return response()->json(['message' => 'Module supprimé.']);
    }
}