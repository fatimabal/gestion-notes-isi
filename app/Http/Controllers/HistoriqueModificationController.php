<?php

namespace App\Http\Controllers;

use App\Models\HistoriqueModification;
use Illuminate\Http\Request;

class HistoriqueModificationController extends Controller
{
    public function index(Request $request)
    {
        $historique = HistoriqueModification::with([
            'note.matiere',
            'note.etudiant.user',
            'user',
        ])->orderBy('dateAction', 'desc')->get();

        return response()->json($historique);
    }

    public function purger(Request $request)
    {
        HistoriqueModification::truncate();
        return response()->json(['message' => 'Journal purgé avec succès']);
    }
}