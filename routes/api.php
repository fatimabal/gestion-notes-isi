<?php

use App\Http\Controllers\AnneeAcademiqueController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\DeliberationController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\HistoriqueModificationController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ReclamationController;
use App\Http\Controllers\SemestreController;
use App\Http\Controllers\SituationFinanciereController;
use App\Http\Controllers\UeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ── Groupe tous les rôles connectés (GET communs) ──
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);

    Route::get('/matieres', [MatiereController::class, 'index']);
    Route::get('/classes', [ClasseController::class, 'index']);
    Route::get('/semestres', [SemestreController::class, 'index']);
    Route::get('/departements', [DepartementController::class, 'index']);
    Route::get('/filieres', [FiliereController::class, 'index']);
    Route::get('/ues', [UeController::class, 'index']);
    Route::get('/reclamations', [ReclamationController::class, 'index']);

    Route::get('/notifications', function (Request $request) {
        return response()->json($request->user()->notifications);
    });
    Route::post('/notifications/lues', function (Request $request) {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'Notifications marquées comme lues']);
    });
});

// ── Groupe Admin ──
Route::middleware(['auth:sanctum', 'check.role:admin'])->group(function () {
    Route::get('/utilisateurs', [UserController::class, 'index']);
    Route::put('/utilisateurs/{id}', [UserController::class, 'update']);
    Route::put('/utilisateurs/{id}/bloquer', [UserController::class, 'bloquer']);
    Route::put('/utilisateurs/{id}/toggle-actif', [UserController::class, 'toggleActif']);
    Route::delete('/utilisateurs/{id}', [UserController::class, 'destroy']);
    Route::post('/utilisateurs/{id}/reinitialiser-mot-de-passe', [UserController::class, 'reinitialiserMotDePasse']);

    Route::post('/departements', [DepartementController::class, 'store']);
    Route::put('/departements/{id}', [DepartementController::class, 'update']);
    Route::delete('/departements/{id}', [DepartementController::class, 'destroy']);

    Route::post('/filieres', [FiliereController::class, 'store']);
    Route::put('/filieres/{id}', [FiliereController::class, 'update']);
    Route::delete('/filieres/{id}', [FiliereController::class, 'destroy']);

    Route::post('/classes', [ClasseController::class, 'store']);
    Route::put('/classes/{id}', [ClasseController::class, 'update']);
    Route::delete('/classes/{id}', [ClasseController::class, 'destroy']);

    Route::post('/matieres', [MatiereController::class, 'store']);
    Route::put('/matieres/{id}', [MatiereController::class, 'update']);
    Route::delete('/matieres/{id}', [MatiereController::class, 'destroy']);
    Route::post('/matieres/{id}/affecter-enseignants', [MatiereController::class, 'affecterEnseignants']);
    Route::get('/enseignants', [UserController::class, 'indexEnseignants']);

    Route::post('/ues', [UeController::class, 'store']);
    Route::put('/ues/{id}', [UeController::class, 'update']);
    Route::delete('/ues/{id}', [UeController::class, 'destroy']);

    Route::post('/semestres', [SemestreController::class, 'store']);
    Route::put('/semestres/{id}', [SemestreController::class, 'update']);
    Route::delete('/semestres/{id}', [SemestreController::class, 'destroy']);

    Route::get('/annees-academiques', [AnneeAcademiqueController::class, 'index']);
    Route::post('/annees-academiques', [AnneeAcademiqueController::class, 'store']);

    Route::get('/notes/toutes', [NoteController::class, 'toutes']);

    Route::get('/journal', [HistoriqueModificationController::class, 'index']);
    Route::delete('/journal', [HistoriqueModificationController::class, 'purger']);
});

// ── Groupe Enseignant ──
Route::middleware(['auth:sanctum', 'check.role:enseignant'])->group(function () {
    Route::get('/notes/mes-notes', [NoteController::class, 'mesNotes']);
    Route::get('/etudiants-liste', function () {
        return response()->json(
            \App\Models\Etudiant::with(['user', 'inscription.classe'])->get()->map(function ($e) {
                return [
                    'id'        => $e->id,
                    'matricule' => $e->matricule,
                    'classe_id' => $e->inscription->first()?->classe_id,
                    'user'      => ['nom' => $e->user?->nom, 'prenom' => $e->user?->prenom],
                ];
            }),
            200
        );
    });
    Route::get('/etudiants', [NoteController::class, 'indexEtudiants']);
    Route::post('/notes', [NoteController::class, 'store']);
    Route::put('/notes/{id}', [NoteController::class, 'update']);
    Route::delete('/notes/{id}', [NoteController::class, 'destroy']);
    Route::post('/evaluations/{id}/deposer-sujet', [EvaluationController::class, 'deposerSujet']);
    Route::put('/reclamations/{id}', [ReclamationController::class, 'update']);
});

// ── Groupe Étudiant ──
Route::middleware(['auth:sanctum', 'check.role:etudiant'])->group(function () {
    Route::get('/notes', [NoteController::class, 'index']);
    Route::post('/notes/{id}/reclamer', [NoteController::class, 'reclamer']);
    Route::get('/bulletins', [BulletinController::class, 'consulter']);
    Route::post('/reclamations', [ReclamationController::class, 'store']);
});

// ── Groupe Parent ──
Route::middleware(['auth:sanctum', 'check.role:parent'])->group(function () {
    Route::get('/notes/enfant', [NoteController::class, 'notesEnfant']);
    Route::get('/bulletins/enfant', [BulletinController::class, 'consulterEnfant']);
});

// ── Groupe Chef Département ──
Route::middleware(['auth:sanctum', 'check.role:chef_departement'])->group(function () {
    Route::get('/notes/toutes', [NoteController::class, 'toutes']);
    Route::put('/reclamations/{id}/relancer', [ReclamationController::class, 'relancer']);
    Route::get('/deliberations', [DeliberationController::class, 'index']);
    Route::post('/deliberations/valider', [DeliberationController::class, 'valider']);
});