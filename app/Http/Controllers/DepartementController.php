<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departement;

class DepartementController extends Controller
{
    public function index()
    {
        return response()->json(['departements' => Departement::all()], 200);
    }
    public function store(Request $request)
    {
        $request->validate(['nom' => 'required|string']);
        $departement = Departement::create(['nom' => $request->nom]);
        return response()->json(['departement' => $departement], 201);
    }
}
