<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Inscription;
use App\Models\Matiere;
use App\Models\Ue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    // ── Helper : lire le fichier Excel → tableau de lignes ──
    private function lireFichier(Request $request): array
    {
        $request->validate(['fichier' => 'required|file|mimes:xlsx,xls,csv']);
        $spreadsheet = IOFactory::load($request->file('fichier')->getRealPath());
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
        $headers = array_map('trim', array_shift($rows)); // première ligne = entêtes
        $data = [];
        foreach ($rows as $row) {
            if (array_filter($row)) { // ignorer lignes vides
                $data[] = array_combine($headers, array_map('trim', $row));
            }
        }
        return $data;
    }

    // ── POST /api/import/etudiants ──
    // Colonnes : prenom, nom, email, matricule, dateNaissance, lieuNaissance, sexe, filiere, classe
    public function etudiants(Request $request)
    {
        $rows = $this->lireFichier($request);
        $importes = 0;
        $erreurs = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $i => $row) {
                $ligne = $i + 2;

                // Créer ou retrouver le User
                $user = User::firstOrCreate(
                    ['email' => $row['email']],
                    [
                        'nom'      => $row['nom'],
                        'prenom'   => $row['prenom'],
                        'password' => Hash::make('Password123!'),
                        'role'     => 'etudiant',
                    ]
                );

                // Créer ou mettre à jour l'Etudiant
                $etudiant = Etudiant::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'matricule'     => $row['matricule'],
                        'dateNaissance' => $row['dateNaissance'] ?? null,
                        'lieuNaissance' => $row['lieuNaissance'] ?? null,
                        'sexe'          => $row['sexe'] ?? null,
                        'filiere'       => $row['filiere'] ?? null,
                    ]
                );

                $importes++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur : ' . $e->getMessage()], 422);
        }

        return response()->json([
            'message'  => "$importes étudiant(s) importé(s) avec succès.",
            'importes' => $importes,
            'erreurs'  => $erreurs,
        ]);
    }

    // ── POST /api/import/classes ──
    // Colonnes : nomClasse, niveau, filiere
    public function classes(Request $request)
    {
        $rows = $this->lireFichier($request);
        $importes = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                // Retrouver la filière par son nom
                $filiere = Filiere::where('nom', $row['filiere'])->first();

                Classe::updateOrCreate(
                    ['nomClasse' => $row['nomClasse']],
                    [
                        'niveau'      => $row['niveau'] ?? null,
                        'filiere_id'  => $filiere?->id,
                    ]
                );
                $importes++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur : ' . $e->getMessage()], 422);
        }

        return response()->json([
            'message'  => "$importes classe(s) importée(s) avec succès.",
            'importes' => $importes,
        ]);
    }

    // ── POST /api/import/modules ──
    // Colonnes : libelle, coefficient, volumeHoraire, UE
    public function modules(Request $request)
    {
        $rows = $this->lireFichier($request);
        $importes = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                // Retrouver l'UE par son libellé
                $ue = Ue::where('libelle', $row['UE'])->first();

                Matiere::updateOrCreate(
                    ['libelle' => $row['libelle']],
                    [
                        'coefficient'   => $row['coefficient'] ?? 1,
                        'volumeHoraire' => $row['volumeHoraire'] ?? null,
                        'ue_id'         => $ue?->id,
                    ]
                );
                $importes++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur : ' . $e->getMessage()], 422);
        }

        return response()->json([
            'message'  => "$importes module(s) importé(s) avec succès.",
            'importes' => $importes,
        ]);
    }

    // ── POST /api/import/inscriptions ──
    // Colonnes : matricule, classe, anneeAcademique
    public function inscriptions(Request $request)
    {
        $rows = $this->lireFichier($request);
        $importes = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $etudiant = Etudiant::where('matricule', $row['matricule'])->first();
                $classe   = Classe::where('nomClasse', $row['classe'])->first();

                if (! $etudiant || ! $classe) {
                    continue; // ignorer si matricule ou classe introuvable
                }

                Inscription::updateOrCreate(
                    [
                        'etudiant_id'     => $etudiant->id,
                        'anneeAcademique' => $row['anneeAcademique'],
                    ],
                    [
                        'classe_id'       => $classe->id,
                        'dateInscription' => now()->toDateString(),
                        'Groupe'          => 1,
                    ]
                );
                $importes++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur : ' . $e->getMessage()], 422);
        }

        return response()->json([
            'message'  => "$importes inscription(s) importée(s) avec succès.",
            'importes' => $importes,
        ]);
    }

    // ── POST /api/import/ue ──
    // Colonnes : libelle, credits
    public function ue(Request $request)
    {
        $rows = $this->lireFichier($request);
        $importes = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                Ue::updateOrCreate(
                    ['libelle' => $row['libelle']],
                    ['credits' => $row['credits'] ?? null]
                );
                $importes++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur : ' . $e->getMessage()], 422);
        }

        return response()->json([
            'message'  => "$importes UE importée(s) avec succès.",
            'importes' => $importes,
        ]);
    }
}