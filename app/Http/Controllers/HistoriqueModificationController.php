<?php

namespace App\Http\Controllers;

use App\Models\HistoriqueModification;
use Illuminate\Http\Request;

class HistoriqueModificationController extends Controller
{
    public function index(Request $request)
    {
        $historiqueModification = HistoriqueModification::all();

        return response()->json(['historiqueModification' => $historiqueModification], 200);
    }
}
