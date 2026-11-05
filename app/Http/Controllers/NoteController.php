<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    // Saisir une note (enseignant)
    public function store(Request $request)
    {
        $request->validate([
            'valeur'      => 'required|numeric|min:0|max:20',
            'etudiant_id' => 'required|exists:etudiants,id',
            'matiere_id'  => 'required|exists:modules,id',
            'semestre_id' => 'required|exists:semestres,id',
            'coefficient' => 'nullable|integer|min:1',
            'type'        => 'nullable|string',
            'statut'      => 'nullable|string',
        ]);

        $enseignant = $request->user()->enseignant;

        $note = Note::create([
            'valeur'        => $request->valeur,
            'dateSaisie'    => now(),
            'session'       => 'normale',
            'etudiant_id'   => $request->etudiant_id,
            'enseignant_id' => $enseignant->id,
            'matiere_id'    => $request->matiere_id,
            'semestre_id'   => $request->semestre_id,
            'coefficient'   => $request->coefficient ?? 1,
            'type'          => $request->type,
            'statut'        => $request->statut ?? 'brouillon',
        ]);

        return response()->json(['note' => $note], 201);
    }

    // Consulter ses notes (étudiant)
    public function index(Request $request)
    {
        $etudiant = $request->user()->etudiant;
        $notes = Note::with(['matiere', 'semestre', 'evaluation'])
            ->where('etudiant_id', $etudiant->id)
            ->get();
        return response()->json($notes, 200);
    }

    // Notes de l'enseignant connecté
    public function mesNotes(Request $request)
    {
        $enseignant = $request->user()->enseignant;
        $notes = Note::with(['matiere', 'etudiant.user', 'semestre'])
            ->where('enseignant_id', $enseignant->id)
            ->get();
        return response()->json($notes);
    }

    // Toutes les notes (chef département, scolarité)
    public function toutes(Request $request)
    {
        $notes = Note::with([
            'etudiant.user',
            'etudiant.inscription.classe',
            'matiere',
            'semestre',
            'evaluation.matiere',
            'evaluation.semestre',
        ])->get();

        $notes->each(function ($note) {
            if ($note->etudiant) {
                $note->etudiant->classe = $note->etudiant->inscription->first()?->classe;
            }
        });

        return response()->json($notes);
    }

    // Modifier une note
    public function update(Request $request, $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Note non trouvée'], 404);
        }
        $request->validate([
            'valeur' => 'required|numeric|min:0|max:20',
        ]);
        $note->update(['valeur' => $request->valeur]);
        return response()->json(['note' => $note], 200);
    }

    // Valider une note (scolarité)
    public function valider(Request $request, $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Note non trouvée'], 404);
        }
        $note->update(['statut' => 'valide']);
        return response()->json(['message' => 'Note validée avec succès', 'note' => $note], 200);
    }

    // Réclamer une note (étudiant)
    public function reclamer(Request $request, $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Note non trouvée'], 404);
        }
        $request->validate([
            'reclamation' => 'required|string'
        ]);
        $note->update(['reclamation' => $request->reclamation]);
        return response()->json(['message' => 'Réclamation soumise avec succès', 'note' => $note], 200);
    }

    // Liste des étudiants (enseignant)
    public function indexEtudiants(Request $request)
    {
        $etudiants = \App\Models\Etudiant::with('user')->get();
        return response()->json($etudiants);
    }

    // Notes de l'enfant (parent)
    public function notesEnfant(Request $request)
    {
        $parent = $request->user()->parentEtudiant;
        $notes = Note::with(['matiere', 'semestre'])
            ->where('etudiant_id', $parent->etudiant_id)
            ->where('statut', 'publie')
            ->get();
        return response()->json(['notes' => $notes], 200);
    }
}