<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ue;

class UeController extends Controller
{
    public function index()
    {
        return response()->json(['ues' => Ue::all()], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string',
            'credits' => 'required|integer',
            'semestre_id' => 'required|exists:semestres,id'
        ]);
        $ue = Ue::create($request->all());
        return response()->json(['ue' => $ue], 201);
    }
}
