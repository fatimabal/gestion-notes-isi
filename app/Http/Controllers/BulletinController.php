<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bulletin;
use App\Models\Note;
class BulletinController extends Controller
{
    public function generer(Request $request)
    {
         $request->validate([
            'session' => 'required|string',
            'mention' => 'required|string',
            'dateGeneration' => 'required|date',
            'etudiant_id' => 'required|exists:etudiants,id',
            'semestre_id' => 'required|exists:semestres,id',
        
        ]);

$moyenne = Note::where('etudiant_id',$request->etudiant_id)
              ->where('semestre_id',$request->semestre_id)
              ->avg('valeur');

    $bulletin = Bulletin::create([
    'session' => $request->session,
    'moyenne_generale' => $moyenne,
    'mention' => $request->mention,
    'dateGeneration' => $request->dateGeneration,
    'etudiant_id' => $request->etudiant_id,
    'semestre_id' => $request->semestre_id,
]);
return response()->json([
    'bulletin' => $bulletin
], 201);
    }
    public function consulter(Request $request)
{
    $etudiant = $request->user()->etudiant;
    $bulletins = Bulletin::where('etudiant_id', $etudiant->id)->get();
    return response()->json(['bulletins' => $bulletins], 200);
}
}
