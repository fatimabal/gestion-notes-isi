<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semestre;
class SemestreController extends Controller
{
    public function index(Request $request)
    {
        $semestres = Semestre::all();
return response()->json(['semestres' => $semestres], 200);
    }

    public function store(Request $request)
    {
         $request->validate([
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date',
        
        ]);
        $semestre = Semestre::create([
    'dateDebut' => $request->dateDebut,
    'dateFin' => $request->dateFin,
]);                           

return response()->json([     
    'semestre' => $semestre
], 201);   

    
    }
}
