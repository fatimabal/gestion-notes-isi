<?php

namespace App\Http\Controllers;

use App\Models\Bulletin;
use App\Models\DetailBulletin;
use App\Models\Etudiant;
use App\Models\Ue;
use App\Models\Note;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class BulletinController extends Controller
{
    // Génère en masse pour tous les étudiants d'un semestre
    public function genererMasse(Request $request)
    {
        $request->validate([
            'semestre_id' => 'required|exists:semestres,id',
        ]);

        $semestreId = $request->semestre_id;
        $etudiants = Etudiant::all();
        $resultats = [];

        foreach ($etudiants as $etudiant) {
            $bulletin = $this->genererPourEtudiant($etudiant->id, $semestreId);
            if ($bulletin) {
                $this->envoyerNotifications($bulletin, $etudiant);
                $resultats[] = [
                    'etudiant' => $etudiant->user->prenom . ' ' . $etudiant->user->nom,
                    'moyenne' => $bulletin->moyenne_generale,
                    'mention' => $bulletin->mention,
                ];
            }
        }

        return response()->json([
            'message' => count($resultats) . ' bulletins générés avec succès',
            'resultats' => $resultats,
        ], 201);
    }

    // Génère pour un seul étudiant (appelé par genererMasse)
    private function genererPourEtudiant($etudiantId, $semestreId)
    {
        $ues = Ue::where('semestre_id', $semestreId)->with('modules.evaluations')->get();
        if ($ues->isEmpty()) return null;

        // Supprimer l'ancien bulletin s'il existe
        $ancien = Bulletin::where('etudiant_id', $etudiantId)
            ->where('semestre_id', $semestreId)->first();
        if ($ancien) {
            $ancien->details()->delete();
            $ancien->delete();
        }

        $totalPondere = 0;
        $totalCredits = 0;
        $details = [];

        foreach ($ues as $ue) {
            $sommeModules = 0;
            $sommeCoefs = 0;

            foreach ($ue->modules as $module) {
                $moyenneModule = $this->calculerMoyenneModule($module, $etudiantId);
                $sommeModules += $moyenneModule * $module->coefficient;
                $sommeCoefs += $module->coefficient;
            }

            $moyenneUe = $sommeCoefs > 0 ? round($sommeModules / $sommeCoefs, 2) : 0;
            $valide = $moyenneUe >= 10;
            $creditsObtenus = $valide ? $ue->credits : 0;

            $details[] = [
                'ue_id' => $ue->id,
                'moyenne_ue' => $moyenneUe,
                'credits_obtenus' => $creditsObtenus,
                'valide' => $valide,
                'appreciation' => $this->appreciation($moyenneUe, $valide),
            ];

            $totalPondere += $moyenneUe * $ue->credits;
            $totalCredits += $ue->credits;
        }

        $moyenneGenerale = $totalCredits > 0
            ? round($totalPondere / $totalCredits, 2) : 0;

        $bulletin = Bulletin::create([
            'etudiant_id' => $etudiantId,
            'semestre_id' => $semestreId,
            'session' => 'normale',
            'moyenne_generale' => $moyenneGenerale,
            'mention' => $this->mention($moyenneGenerale),
            'dateGeneration' => now(),
        ]);

        foreach ($details as $d) {
            DetailBulletin::create(array_merge($d, ['bulletin_id' => $bulletin->id]));
        }

        return $bulletin->load('semestre', 'etudiant.user');
    }

    // Envoie notifications à étudiant + parents + chefs de département
private function envoyerNotifications($bulletin, $etudiant)
{
    $semestre = $bulletin->semestre->libelle ?? 'Semestre';
    $nomEtudiant = $etudiant->user->prenom . ' ' . $etudiant->user->nom;

    // Notification à l'étudiant
    Notification::create([
        'type' => 'bulletin',
        'notifiable_type' => 'App\\Models\\User',
        'notifiable_id' => $etudiant->user_id,
        'data' => json_encode([
            'message' => "Votre bulletin du {$semestre} est disponible. Moyenne : {$bulletin->moyenne_generale}/20 — {$bulletin->mention}",
            'bulletin_id' => $bulletin->id,
        ]),
    ]);

    // Notifications aux parents
    foreach ($etudiant->parents as $parent) {
        Notification::create([
            'type' => 'bulletin',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $parent->user_id,
            'data' => json_encode([
                'message' => "Le bulletin du {$semestre} de {$nomEtudiant} est disponible. Moyenne : {$bulletin->moyenne_generale}/20 — {$bulletin->mention}",
                'bulletin_id' => $bulletin->id,
            ]),
        ]);
    }

    // Notifications aux chefs de département
    $chefs = User::where('role', 'chef_departement')->get();
    foreach ($chefs as $chef) {
        Notification::create([
            'type' => 'bulletin',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $chef->id,
            'data' => json_encode([
                'message' => "Bulletin généré — {$nomEtudiant} — {$semestre} : {$bulletin->moyenne_generale}/20 — {$bulletin->mention}",
                'bulletin_id' => $bulletin->id,
            ]),
        ]);
    }
}

    // Consulter le bulletin d'un étudiant
    public function show(Request $request, $etudiantId, $semestreId)
    {
        $bulletin = Bulletin::with([
            'details.ue.modules.evaluations',
            'etudiant.user',
            'semestre',
        ])
        ->where('etudiant_id', $etudiantId)
        ->where('semestre_id', $semestreId)
        ->first();

        if (!$bulletin) {
            return response()->json(['message' => 'Bulletin non trouvé'], 404);
        }

        $etudiantId = (int)$etudiantId;
        foreach ($bulletin->details as $detail) {
            foreach ($detail->ue->modules as $module) {
                $noteDevoir = Note::where('etudiant_id', $etudiantId)
                    ->where('matiere_id', $module->id)
                    ->where('type', 'Devoir')
                    ->whereIn('statut', ['publiée', 'publie'])
                    ->orderBy('id', 'desc')->first();
                $noteExamen = Note::where('etudiant_id', $etudiantId)
                    ->where('matiere_id', $module->id)
                    ->where('type', 'Examen')
                    ->whereIn('statut', ['publiée', 'publie'])
                    ->orderBy('id', 'desc')->first();

                $module->notes_detail = [
                    ['type' => 'Devoir',  'coefficient' => 40, 'valeur' => $noteDevoir ? $noteDevoir->valeur : 0],
                    ['type' => 'Examen',  'coefficient' => 60, 'valeur' => $noteExamen ? $noteExamen->valeur : 0],
                ];
                $module->moyenne = $this->calculerMoyenneModule($module, $etudiantId);
            }
        }

        return response()->json(['bulletin' => $bulletin], 200);
    }

    // Bulletins de l'enfant (espace parent)
    public function enfant(Request $request)
    {
        $parent = $request->user()->parentEtudiant;
        $etudiantIds = $parent->etudiants()->pluck('etudiants.id');
        $bulletins = Bulletin::with(['details.ue', 'semestre', 'etudiant.user'])
            ->whereIn('etudiant_id', $etudiantIds)
            ->get();
        return response()->json(['bulletins' => $bulletins], 200);
    }

    // Mes bulletins (espace étudiant)
 public function mesBulletins(Request $request)
{
    $user = $request->user();
    $etudiant = $user->etudiant ?? null;

    if (!$etudiant) {
        return response()->json(['bulletins' => []], 200);
    }

    $bulletins = Bulletin::with([
        'semestre',
        'details.ue.modules.evaluations',
        'etudiant.user',
        'etudiant.inscription.classe.filiere',
    ])
        ->where('etudiant_id', $etudiant->id)
        ->get();

    foreach ($bulletins as $bulletin) {
        foreach ($bulletin->details as $detail) {
            foreach ($detail->ue->modules as $module) {
                $noteDevoir = Note::where('etudiant_id', $etudiant->id)
                    ->where('matiere_id', $module->id)
                    ->where('type', 'Devoir')
                    ->whereIn('statut', ['publiée', 'publie'])
                    ->orderBy('id', 'desc')->first();
                $noteExamen = Note::where('etudiant_id', $etudiant->id)
                    ->where('matiere_id', $module->id)
                    ->where('type', 'Examen')
                    ->whereIn('statut', ['publiée', 'publie'])
                    ->orderBy('id', 'desc')->first();

                $mcc    = $noteDevoir ? (float)$noteDevoir->valeur : 0;
                $examen = $noteExamen ? (float)$noteExamen->valeur : 0;
                $module->mcc    = $mcc;
                $module->examen = $examen;
                $module->moyenne_module = round($mcc * 0.4 + $examen * 0.6, 2);
            }
        }

        $inscription = $etudiant->inscription->first();
        $bulletin->infos_etudiant = [
            'matricule'       => $etudiant->matricule,
            'sexe'            => $etudiant->sexe,
            'dateNaissance'   => $etudiant->dateNaissance,
            'lieuNaissance'   => $etudiant->lieuNaissance,
            'classe'          => $inscription?->classe?->nomClasse,
            'filiere'         => $inscription?->classe?->filiere?->nom,
            'diplome'         => $inscription?->classe?->filiere?->diplome,
            'anneeAcademique' => $inscription?->anneeAcademique,
        ];
    }

    return response()->json(['bulletins' => $bulletins]);
}

public function parSemestre(Request $request, $semestreId)
{
    $bulletins = Bulletin::with([
        'etudiant.user',
        'etudiant.inscription.classe.filiere',
        'semestre',
        'details.ue.modules.evaluations',
    ])
        ->where('semestre_id', $semestreId)
        ->get();

    foreach ($bulletins as $bulletin) {
        $etudiant = $bulletin->etudiant;
        foreach ($bulletin->details as $detail) {
            foreach ($detail->ue->modules as $module) {
                $noteDevoir = Note::where('etudiant_id', $etudiant->id)
                    ->where('matiere_id', $module->id)
                    ->where('type', 'Devoir')
                    ->whereIn('statut', ['publiée', 'publie'])
                    ->orderBy('id', 'desc')->first();
                $noteExamen = Note::where('etudiant_id', $etudiant->id)
                    ->where('matiere_id', $module->id)
                    ->where('type', 'Examen')
                    ->whereIn('statut', ['publiée', 'publie'])
                    ->orderBy('id', 'desc')->first();

                $mcc    = $noteDevoir ? (float)$noteDevoir->valeur : 0;
                $examen = $noteExamen ? (float)$noteExamen->valeur : 0;
                $module->mcc    = $mcc;
                $module->examen = $examen;
                $module->moyenne_module = round($mcc * 0.4 + $examen * 0.6, 2);
            }
        }

        $inscription = $etudiant->inscription->first();
        $bulletin->infos_etudiant = [
            'matricule'       => $etudiant->matricule,
            'sexe'            => $etudiant->sexe,
            'dateNaissance'   => $etudiant->dateNaissance,
            'lieuNaissance'   => $etudiant->lieuNaissance,
            'classe'          => $inscription?->classe?->nomClasse,
            'filiere'         => $inscription?->classe?->filiere?->nom,
            'diplome'         => $inscription?->classe?->filiere?->diplome,
            'anneeAcademique' => $inscription?->anneeAcademique,
        ];
    }

    return response()->json(['bulletins' => $bulletins]);
}


    private function calculerMoyenneModule($module, $etudiantId): float
    {
        $somme = 0;
        $totalCoef = 0;
        foreach ($module->evaluations as $eval) {
            $note = Note::where('etudiant_id', $etudiantId)
                ->where('evaluation_id', $eval->id)
                ->first();
            $valeur = $note ? (float)$note->valeur : 0;
            $somme += $valeur * $eval->coefficient;
            $totalCoef += $eval->coefficient;
        }
        return $totalCoef > 0 ? round($somme / $totalCoef, 2) : 0;
    }

    private function appreciation(float $moyenne, bool $valide): string
    {
        if (!$valide) return 'Travail insuffisant — UE à reprendre';
        if ($moyenne >= 18) return 'Excellent';
        if ($moyenne >= 16) return 'Très bien';
        if ($moyenne >= 14) return 'Bon travail';
        if ($moyenne >= 12) return 'Assez bon travail';
        if ($moyenne >= 10) return 'Passable';
        return 'Travail insuffisant — UE à reprendre';
    }

    private function mention(float $moyenne): string
    {
        if ($moyenne >= 18) return 'Excellent';
        if ($moyenne >= 16) return 'Très bien';
        if ($moyenne >= 14) return 'Bon travail';
        if ($moyenne >= 12) return 'Assez bon travail';
        if ($moyenne >= 10) return 'Passable';
        return 'Insuffisant';
    }
  
}