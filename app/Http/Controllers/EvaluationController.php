<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
class EvaluationController extends Controller
{
     public function index(Request $request)
    {
        $evaluation = Evaluation::all();
return response()->json(['evaluation' => $evaluation], 200);
    }

    public function store(Request $request)
    {
         $request->validate([
            'typeEvaluation' => 'required|string',
            'dateEvaluation' => 'required|date',
            'noteMax' => 'required|integer',
            'noteMin' => 'required|integer',
            'coefficient' => 'required|integer',
            'matiere_id' => 'required|exists:matieres,id',
        
        ]);
        $evaluation = Evaluation::create([
    'typeEvaluation' => $request->typeEvaluation,
    'dateEvaluation' => $request->dateEvaluation,
    'noteMax' => $request->noteMax,
    'noteMin' => $request->noteMin,
    'coefficient' => $request->coefficient,
    'matiere_id' => $request->matiere_id,
]);                           

return response()->json([     
    'evaluation' => $evaluation
], 201);   

    
    }
}
