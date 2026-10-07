<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\AgentServiceExamen;
use App\Models\ParentEtudiant;
use App\Models\Comptable;
use App\Models\ChefDepartement;
use App\Models\Inscription;
use Illuminate\Support\Facades\Hash;
use App\Models\Departement;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un département d'abord
        Departement::create([
            'nom' => 'Département Informatique'
        ]);
        // Etudiant
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
        ]);

        // Enseignant
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

        // Scolarité
        $scolarite_user = User::create([
            'nom' => 'Thiam',
            'prenom' => 'Anta',
            'email' => 'scolarite@test.com',
            'password' => Hash::make('123456'),
            'role' => 'scolarite',
            'telephone' => '767891221',
        ]);
        AgentServiceExamen::create([
            'user_id' => $scolarite_user->id,
            'fonction' => 'Responsable Scolarité',
            'bureau' => 'examens'
        ]);

        // Parent
        User::create([
            'nom' => 'Bal',
            'prenom' => 'Mohamed',
            'email' => 'parent@test.com',
            'password' => Hash::make('123456'),
            'role' => 'parent',
            'telephone' => '781023456',
        ]);

        // Comptable
        $comptable_user = User::create([
            'nom' => 'Ndiaye',
            'prenom' => 'Ibrahima',
            'email' => 'comptable@test.com',
            'password' => Hash::make('123456'),
            'role' => 'comptable',
            'telephone' => '771122334',
        ]);
        Comptable::create([
            'user_id' => $comptable_user->id,
        ]);

        // Chef Département
        $chef_user = User::create([
            'nom' => 'Fall',
            'prenom' => 'Mamadou',
            'email' => 'chef@test.com',
            'password' => Hash::make('123456'),
            'role' => 'chef_departement',
            'telephone' => '772233445',
        ]);
        ChefDepartement::create([
            'user_id' => $chef_user->id,
            'mandat' => '2024-2026',
            'dateDebut' => '2024-01-01',
            'dateFin' => '2026-12-31',
            'departement_id' => 1
        ]);

        // Admin
        User::create([
            'nom' => 'Admin',
            'prenom' => 'Système',
            'email' => 'admin@test.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'telephone' => '773344556',
        ]);

        // Inscription étudiant
        Inscription::create([
            'etudiant_id' => 1,
            'classe_id' => 1,
            'anneeAcademique' => '2025/2026',
            'dateInscription' => '2025-10-01',
            'Groupe' => 1
        ]);
    }
}
