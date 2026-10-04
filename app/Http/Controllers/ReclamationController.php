<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reclamation;

class ReclamationController extends Controller
{
    public function index(){ 
    return response()->json(['reclamations' => Reclamation::all()], 200); 
}
public function store(Request $request){
    $request->validate([
        'motif' => 'required|string',
        'etudiant_id' => 'required|exists:etudiants,id',
        'note_id' => 'required|exists:notes,id'
    ]);
    $reclamation = Reclamation::create([
        'motif' => $request->motif,
        'statut' => 'en_attente',
        'dateCreation' => now(),
        'etudiant_id' => $request->etudiant_id,
        'note_id' => $request->note_id
    ]);
    return response()->json(['reclamation' => $reclamation], 201);
}
}
