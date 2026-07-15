<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\Scolarite;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'étudiant
        $etudiant_user = User::create([
            'nom' => 'Diop',
            'prenom' => 'Makhou',
            'email' => 'etudiant@test.com',
            'password' => Hash::make('123456'),
            'role' => 'etudiant',
            'telephone' => '779081356',
        ]);
        Etudiant::create([
            'user_id' => $etudiant_user->id,
            'matricule' => 'ETU001',
            'dateNaissance' => '2003-11-22',
            'lieuNaissance' => 'Dakar',
            'filiere' => 'Génie Logiciel',
            'classe_id' => 1
        ]);

        // Créer l'enseignant
        $enseignant_user = User::create([
            'nom' => 'Diop',
            'prenom' => 'Elhadji Mar',
            'email' => 'enseignant@test.com',
            'password' => Hash::make('123456'),
            'role' => 'enseignant',
            'telephone' => '770099009',
        ]);
        Enseignant::create([
            'user_id' => $enseignant_user->id,
            'specialite' => 'Génie Logiciel',
            'grade' => 'Docteur'
        ]);

        // Créer la scolarité
        $scolarite_user = User::create([
            'nom' => 'Thiam',
            'prenom' => 'Anta',
            'email' => 'scolarite@test.com',
            'password' => Hash::make('123456'),
            'role' => 'scolarite',
            'telephone' => '767891221',
        ]);
        Scolarite::create([
            'user_id' => $scolarite_user->id,
            'fonction' => 'Responsable Scolarité',
            'bureau' => 'Bureau 1'
        ]);

        // Créer le parent
        User::create([
            'nom' => 'Bal',
            'prenom' => 'Mohamed',
            'email' => 'parent@test.com',
            'password' => Hash::make('123456'),
            'role' => 'parent',
            'telephone' => '781023456',
        ]);
    }
}