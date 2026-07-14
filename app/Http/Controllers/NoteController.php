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
      'valeur' => 'required|numeric|min:0|max:20',
            'dateSaisie' => 'required|date',
            'session' => 'required|string',
            'etudiant_id' => 'required|exists:etudiants,id',
            'matiere_id' => 'required|exists:matieres,id',
            'evaluation_id' => 'required|exists:evaluations,id',
            'semestre_id' => 'required|exists:semestres,id',
        
        ]);
//Recuperer l'enseignant connecter

$enseignant = $request->user()->enseignant;
//creer une note
    $note = Note::create([
    'valeur' => $request->valeur,
    'dateSaisie' => $request->dateSaisie,
    'session' => $request->session,
    'etudiant_id' => $request->etudiant_id,
'enseignant_id' => $enseignant->id,
    'matiere_id' => $request->matiere_id,
    'evaluation_id' => $request->evaluation_id,
    'semestre_id' => $request->semestre_id,
]);                           

return response()->json([     
    'note' => $note
], 201);   

    
    }

    //modifier une note 
    public function update(Request $request, $id)
    {
        $note = Note::find($id);
        if(!$note){
            return response()->json(['message'=>'Note non trouvé'],404);
        }
         $request->validate([
            'valeur'=>'required|numeric|min:0|max:20',
        ]);
        $note->update(['valeur'=>$request->valeur]);

        return response()->json(['note'=>$note],200);

       
    }
// valider notes
    public function valider(Request $request, $id)
{
    $note = Note::find($id);
    
    if(!$note){
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


$notes = Note::where('etudiant_id', $etudiant->id)->get();
    return response()->json(['notes'=>$notes
        
    ], 200);
}

}
