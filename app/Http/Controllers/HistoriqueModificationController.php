<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoriqueModification;
class HistoriqueModificationController extends Controller
{
      public function index(Request $request)
    {
        $historiqueModification = HistoriqueModification::all();
return response()->json(['historiqueModification' => $historiqueModification], 200);
    }
}
