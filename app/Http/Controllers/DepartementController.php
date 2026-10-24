<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departement;

class DepartementController extends Controller
{
    public function index()
    {
        return response()->json(Departement::all());
    }
    public function store(Request $request)
    {
         $request->validate(['nom' => 'required|string']);
        $departement = Departement::create(['nom' => $request->nom]);
        return response()->json($departement, 201);
    }
    public function destroy($id)
    {
        Departement::findOrFail($id)->delete();
        return response()->json(['message' => 'Département supprimé.']);
    }
    public function update(Request $request, $id)
{
    $departement = Departement::findOrFail($id);
    $request->validate(['nom' => 'required|string']);
    $departement->update(['nom' => $request->nom]);
    return response()->json($departement);
}
}
