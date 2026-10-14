<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Notifications\ReclamationRelancee;
use Illuminate\Http\Request;


class ReclamationController extends Controller
{
    public function index()
    {
        return response()->json(
            Reclamation::with(['etudiant.user', 'note.evaluation.matiere'])->get()
        );
    }

    public function store(Request $request)
    { $user = auth()->user();

    $reclamation = Reclamation::create([
        'etudiant_id' => $user->etudiant->id,
        'note_id'     => $request->note_id,
        'motif'       => $request->motif,
        'statut'      => 'en_attente',
    ]);
    return response()->json($reclamation, 201);
    }

   public function relancer($id)
{
    $reclamation = Reclamation::with([
        'etudiant.user',
        'note.evaluation.matiere',
        'note.evaluation.enseignant.user'   // ← correction ici
    ])->findOrFail($id);

    $reclamation->update(['statut' => 'relancee']);

    $enseignant = $reclamation->note->evaluation->enseignant ?? null;  // ← correction ici
    if ($enseignant && $enseignant->user) {
        $enseignant->user->notify(new ReclamationRelancee(
            matiere: $reclamation->note->evaluation->matiere->nom ?? 'Matière inconnue',
            etudiantNom: $reclamation->etudiant->user->nom ?? 'Etudiant'
        ));
    }

    return response()->json([
        'message'      => 'Réclamation relancée, enseignant notifié.',
        'reclamation'  => $reclamation
    ]);
}
}