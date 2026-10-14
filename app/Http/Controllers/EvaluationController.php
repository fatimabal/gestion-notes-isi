<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Inscription;
use App\Models\Matiere;
use App\Models\Classe;
use App\Notifications\ExamenProgramme;
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
            'noteMax'        => 'required|integer',
            'noteMin'        => 'required|integer',
            'coefficient'    => 'required|integer',
            'matiere_id'     => 'required|exists:modules,id',
            'classe_id'      => 'required|exists:classes,id',
        ]);

        $evaluation = Evaluation::create([
            'typeEvaluation' => $request->typeEvaluation,
            'dateEvaluation' => $request->dateEvaluation,
            'noteMax'        => $request->noteMax,
            'noteMin'        => $request->noteMin,
            'coefficient'    => $request->coefficient,
            'matiere_id'     => $request->matiere_id,
        ]);

        // Récupérer la matière et la classe pour le message
        $matiere = Matiere::find($request->matiere_id);
        $classe  = Classe::find($request->classe_id);

        // Notifier tous les étudiants inscrits dans cette classe
        $inscriptions = Inscription::with('etudiant.user')
            ->where('classe_id', $request->classe_id)
            ->get();

        foreach ($inscriptions as $inscription) {
            if ($inscription->etudiant && $inscription->etudiant->user) {
                $inscription->etudiant->user->notify(new ExamenProgramme(
                    matiere: $matiere->libelle ?? 'Matière',
                    type: $request->typeEvaluation,
                    date: $request->dateEvaluation,
                    classe: $classe->nomClasse ?? 'Classe',
                ));
            }
        }

        return response()->json(['evaluation' => $evaluation], 201);
    }
}
