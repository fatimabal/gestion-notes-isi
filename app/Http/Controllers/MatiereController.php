<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index()
    {
        return response()->json(Matiere::with(['ue', 'enseignants.user'])->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string',
            'coefficient' => 'nullable|integer',
            'ue_id' => 'required|exists:ues,id',
            'volumeHoraire' => 'nullable|integer',
        ]);
        $matiere = Matiere::create($request->only('libelle', 'coefficient', 'volumeHoraire', 'ue_id'));

        return response()->json($matiere, 201);
    }

    public function update(Request $request, $id)
    {
        $matiere = Matiere::findOrFail($id);
        $request->validate([
            'libelle' => 'required|string',
            'coefficient' => 'nullable|integer',
            'ue_id' => 'required|exists:ues,id',
            'volumeHoraire' => 'nullable|integer',
        ]);
        $matiere->update($request->only('libelle', 'coefficient', 'volumeHoraire', 'ue_id'));

        return response()->json($matiere);
    }

    public function destroy($id)
    {
        Matiere::findOrFail($id)->delete();

        return response()->json(['message' => 'Module supprimé.']);
    }

    public function affecterEnseignants(Request $request, $id)
    {
        $matiere = Matiere::findOrFail($id);
        $request->validate([
            'enseignantIds' => 'required|array',
            'enseignantIds.*' => 'integer',
        ]);
        // Les IDs peuvent être des enseignant.id ou user.id
        $enseignantIds = collect($request->enseignantIds)->map(function ($eid) {
            $ens = Enseignant::find($eid);
            if ($ens) {
                return $ens->id;
            }
            // Sinon chercher par user_id
            $ens2 = Enseignant::where('user_id', $eid)->first();

            return $ens2?->id;
        })->filter()->values()->toArray();

        $matiere->enseignants()->sync($enseignantIds);

        return response()->json(['message' => 'Enseignants affectés.']);
    }
}
