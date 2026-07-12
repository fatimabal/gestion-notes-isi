<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;
class ClasseController extends Controller

{
    public function index(Request $request)
    {
        $classe = Classe::all();
return response()->json(['classe' => $classe], 200);
    }

    public function store(Request $request)
    {
         $request->validate([
            'nomClasse' => 'required|string',
            'niveau' => 'required|string',
            'anneeAcademique' => 'required|string',
        
        ]);
        $classe = Classe::create([
    'nomClasse' => $request->nomClasse,
    'niveau' => $request->niveau,
    'anneeAcademique' => $request->anneeAcademique,
]);                           

return response()->json([     
    'classe' => $classe
], 201);   

    
    }
}


