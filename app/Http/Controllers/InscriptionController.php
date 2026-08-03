<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;
class InscriptionController extends Controller
{
     public function index(Request $request)
    {
        $inscription = Inscription::all();
return response()->json(['inscription' => $inscription], 200);
    }
      public function store(Request $request)
    {
         $request->validate([
            'anneeAcademique' => 'required|string',
            'dateInscription' => 'required|date',
            'Groupe' => 'required|integer',
            'etudiant_id' => 'required|exists:etudiants,id',
            'classe_id' => 'required|exists:classes,id',
        
        ]);
    $inscription = Inscription::create([
    'anneeAcademique' => $request->anneeAcademique,
    'dateInscription' => $request->dateInscription,
    'Groupe' => $request->Groupe,
    'etudiant_id' => $request->etudiant_id,
    'classe_id' => $request->classe_id,
]);                           

return response()->json([     
    'inscription' => $inscription
], 201);   

    
    }
}
