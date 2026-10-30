<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    //saisir une note
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
        'valeur'       => $request->valeur,
        'dateSaisie'   => now(),
        'session'      => 'normale',
        'etudiant_id'  => $request->etudiant_id,
        'enseignant_id'=> $enseignant->id,
        'matiere_id'   => $request->matiere_id,
        'semestre_id'  => $request->semestre_id,
        'coefficient'  => $request->coefficient ?? 1,
        'type'         => $request->type,
        'statut'       => $request->statut ?? 'brouillon',
    ]);

    return response()->json(['note' => $note], 201);
}
    // Pour le parent
    public function notesEnfant(Request $request)
    {
        $parent = $request->user()->parentEtudiant;
        $notes = Note::where('etudiant_id', $parent->etudiant_id)
            ->where('validee', true)
            ->get();
        return response()->json(['notes' => $notes], 200);
    }

    // Pour le chef département
  public function toutesLesNotes(Request $request)
{
$notes = Note::with(['semestre', 'etudiant.user', 'etudiant.inscription.classe', 'evaluation'])->get();
  return response()->json($notes, 200);
}
    //modifier une note 
    public function update(Request $request, $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Note non trouvé'], 404);
        }
        $request->validate([
            'valeur' => 'required|numeric|min:0|max:20',
        ]);
        $note->update(['valeur' => $request->valeur]);

        return response()->json(['note' => $note], 200);
    }
    // valider notes
    public function valider(Request $request, $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Note non trouvée'], 404);
        }

        $note->update(['validee' => true]);

        return response()->json([
            'message' => 'Note validée avec succès',
            'note' => $note
        ], 200);
    }
    //  Consulter ses notes (Etudiant)
   public function index(Request $request)
{
    $etudiant = $request->user()->etudiant;
    $notes = Note::with(['matiere', 'semestre'])
        ->where('etudiant_id', $etudiant->id)
        ->get();
    return response()->json($notes, 200);
}
    //reclamer une note
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

        return response()->json([
            'message' => 'Réclamation soumise avec succès',
            'note' => $note
        ], 200);
    }
   public function mesNotes(Request $request)
{
    $enseignant = $request->user()->enseignant;
    $notes = Note::with(['matiere', 'etudiant.user', 'semestre'])
        ->where('enseignant_id', $enseignant->id)
        ->get();
    return response()->json($notes);
}
public function indexEtudiants(Request $request)
{
    $etudiants = \App\Models\Etudiant::with('user')->get();
    return response()->json($etudiants);
}
}
