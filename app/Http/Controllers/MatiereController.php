<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;
class MatiereController extends Controller
{
     public function index(Request $request)
    {
        $matiere = Matiere::all();
return response()->json(['matiere' => $matiere], 200);
    }

    public function store(Request $request)
    {
         $request->validate([
            'libelle' => 'required|string',
            'credits' => 'required|integer',
            'volumeHoraire' => 'required|integer',
            'coefficient' => 'required|integer',
            'classe_id' => 'required|exists:classes,id',
        
        ]);
        $matiere = Matiere::create([
    'libelle' => $request->libelle,
    'credits' => $request->credits,
    'volumeHoraire' => $request->volumeHoraire,
    'coefficient' => $request->coefficient,
    'classe_id' => $request->classe_id,
]);                           

return response()->json([     
    'matiere' => $matiere
], 201);   

    
    }
}
