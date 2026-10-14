<?php

namespace App\Http\Controllers;

use App\Models\Deliberation;
use Illuminate\Http\Request;

class DeliberationController extends Controller
{
    public function index()
    {
        return response()->json(
            Deliberation::with(['classe', 'semestre', 'validateur'])->get()
        );
    }

    public function valider(Request $request)
    {
        $request->validate([
            'classe_id'   => 'required|exists:classes,id',
            'semestre_id' => 'required|exists:semestres,id',
        ]);

        $deliberation = Deliberation::updateOrCreate(
            ['classe_id' => $request->classe_id, 'semestre_id' => $request->semestre_id],
            [
                'validee_par'     => auth()->id(),
                'statut'          => 'validee',
                'date_validation' => now(),
            ]
        );

        return response()->json([
            'message'       => 'Délibération validée avec succès.',
            'deliberation'  => $deliberation
        ]);
    }
}