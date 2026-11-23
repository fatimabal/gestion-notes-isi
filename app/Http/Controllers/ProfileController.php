<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $etudiant = $user->etudiant;
        $inscription = $etudiant?->inscription?->first();
        $chef = $user->chefDepartement;

        return response()->json([
            'id'        => $user->id,
            'nom'       => $user->nom,
            'prenom'    => $user->prenom,
            'email'     => $user->email,
            'role'      => $user->role,
            'telephone' => $user->telephone,
            'etudiant'  => $etudiant ? [
                'matricule'      => $etudiant->matricule,
                'sexe'           => $etudiant->sexe,
                'dateNaissance'  => $etudiant->dateNaissance,
                'lieuNaissance'  => $etudiant->lieuNaissance,
                'classe'         => $inscription?->classe?->nomClasse,
                'filiere'        => $inscription?->classe?->filiere?->nom,
                'diplome'        => $inscription?->classe?->filiere?->diplome,
                'anneeAcademique'=> $inscription?->anneeAcademique,
            ] : null,
            'chefDepartement' => $chef ? [
                'specialite'  => $chef->specialite,
                'grade'       => $chef->grade,
                'mandat'      => $chef->mandat,
                'dateDebut'   => $chef->dateDebut,
                'dateFin'     => $chef->dateFin,
                'departement' => $chef->departement?->nom,
            ] : null,
        ], 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nom'           => 'sometimes|string|max:255',
            'prenom'        => 'sometimes|string|max:255',
            'telephone'     => 'sometimes|nullable|string|max:20',
            'email'         => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'password'      => 'sometimes|string|min:8|confirmed',
            'dateNaissance' => 'sometimes|nullable|string',
            'lieuNaissance' => 'sometimes|nullable|string|max:255',
            'sexe'          => 'sometimes|nullable|in:M,F',
            'specialite'    => 'sometimes|nullable|string|max:255',
            'grade'         => 'sometimes|nullable|string|max:255',
            'mandat'        => 'sometimes|nullable|string|max:255',
            'dateDebut'     => 'sometimes|nullable|string',
            'dateFin'       => 'sometimes|nullable|string',
        ]);

        $user = $request->user();

        // Mise à jour champs user
        $champsUser = [];
        if ($request->filled('nom'))      $champsUser['nom']       = $request->nom;
        if ($request->filled('prenom'))   $champsUser['prenom']    = $request->prenom;
        if ($request->has('telephone'))   $champsUser['telephone'] = $request->telephone;
        if ($request->filled('email'))    $champsUser['email']     = $request->email;
        if ($request->filled('password')) $champsUser['password']  = Hash::make($request->password);

        if (!empty($champsUser)) {
            $user->update($champsUser);
        }

        // Mise à jour champs étudiant
        $etudiant = $user->etudiant;
        if ($etudiant) {
            $champsEtudiant = [];
            if ($request->has('dateNaissance')) $champsEtudiant['dateNaissance'] = $request->dateNaissance;
            if ($request->has('lieuNaissance')) $champsEtudiant['lieuNaissance'] = $request->lieuNaissance;
            if ($request->has('sexe'))          $champsEtudiant['sexe']          = $request->sexe;

            if (!empty($champsEtudiant)) {
                $etudiant->update($champsEtudiant);
            }
        }

        // Mise à jour champs chef de département
        $chef = $user->chefDepartement;
        if ($chef) {
            $champsChef = [];
            if ($request->has('specialite')) $champsChef['specialite'] = $request->specialite;
            if ($request->has('grade'))      $champsChef['grade']      = $request->grade;
            if ($request->has('mandat'))     $champsChef['mandat']     = $request->mandat;
            if ($request->has('dateDebut'))  $champsChef['dateDebut']  = $request->dateDebut;
            if ($request->has('dateFin'))    $champsChef['dateFin']    = $request->dateFin;

            if (!empty($champsChef)) {
                $chef->update($champsChef);
            }
        }

        return response()->json(['message' => 'Profil mis à jour avec succès'], 200);
    }
}