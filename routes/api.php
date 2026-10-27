<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SemestreController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\HistoriqueModificationController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\AnneeAcademiqueController;
use App\Http\Controllers\UeController;
use App\Http\Controllers\SituationFinanciereController;
use App\Http\Controllers\ReclamationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeliberationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Groupe Admin
Route::middleware(['auth:sanctum', 'check.role:admin'])->group(function () {
    Route::get('/utilisateurs', [UserController::class, 'index']);
    Route::put('/utilisateurs/{id}/bloquer', [UserController::class, 'bloquer']);
    Route::delete('/utilisateurs/{id}', [UserController::class, 'destroy']);
    Route::get('/historique', [HistoriqueModificationController::class, 'index']);
    Route::get('/departements', [DepartementController::class, 'index']);
    Route::post('/departements', [DepartementController::class, 'store']);
    Route::put('/departements/{id}', [DepartementController::class, 'update']);
    Route::delete('/departements/{id}', [DepartementController::class, 'destroy']);
    Route::get('/filieres', [FiliereController::class, 'index']);
    Route::post('/filieres', [FiliereController::class, 'store']);
    Route::put('/filieres/{id}', [FiliereController::class, 'update']);
    Route::delete('/filieres/{id}', [FiliereController::class, 'destroy']);
    Route::get('/classes', [ClasseController::class, 'index']);
    Route::post('/classes', [ClasseController::class, 'store']);
    Route::put('/classes/{id}', [ClasseController::class, 'update']);
    Route::delete('/classes/{id}', [ClasseController::class, 'destroy']);
    Route::get('/matieres', [MatiereController::class, 'index']);
    Route::post('/matieres', [MatiereController::class, 'store']);
    Route::put('/matieres/{id}', [MatiereController::class, 'update']);
    Route::delete('/matieres/{id}', [MatiereController::class, 'destroy']);
    Route::get('/ues', [UeController::class, 'index']);
    Route::post('/ues', [UeController::class, 'store']);
    Route::put('/ues/{id}', [UeController::class, 'update']);
    Route::delete('/ues/{id}', [UeController::class, 'destroy']);
    Route::get('/semestres', [SemestreController::class, 'index']);
    Route::post('/semestres', [SemestreController::class, 'store']);
    Route::put('/semestres/{id}', [SemestreController::class, 'update']);
    Route::delete('/semestres/{id}', [SemestreController::class, 'destroy']);
    Route::get('/annees-academiques', [AnneeAcademiqueController::class, 'index']);
    Route::post('/annees-academiques', [AnneeAcademiqueController::class, 'store']);
});

// Groupe enseignant
Route::middleware(['auth:sanctum', 'check.role:enseignant'])->group(function () {
    Route::post('/notes', [NoteController::class, 'store']);
    Route::put('/notes/{id}', [NoteController::class, 'update']);
    Route::post('/evaluations/{id}/deposer-sujet', [EvaluationController::class, 'deposerSujet']);
});

// Groupe parent
Route::middleware(['auth:sanctum', 'check.role:parent'])->group(function () {
    Route::get('/notes/enfant', [NoteController::class, 'notesEnfant']);
    Route::get('/bulletins/enfant', [BulletinController::class, 'consulterEnfant']);
});

// Groupe chef_departement
Route::middleware(['auth:sanctum', 'check.role:chef_departement'])->group(function () {
    Route::get('/notes/toutes', [NoteController::class, 'toutesLesNotes']);
    Route::get('/reclamations', [ReclamationController::class, 'index']);
    Route::put('/reclamations/{id}/relancer', [ReclamationController::class, 'relancer']);
    Route::get('/deliberations', [DeliberationController::class, 'index']);
    Route::post('/deliberations/valider', [DeliberationController::class, 'valider']);
});

// Groupe etudiant
Route::middleware(['auth:sanctum', 'check.role:etudiant'])->group(function () {
    Route::get('/notes', [NoteController::class, 'index']);
    Route::post('/notes/{id}/reclamer', [NoteController::class, 'reclamer']);
    Route::get('/bulletins', [BulletinController::class, 'consulter']);
    Route::post('/reclamations', [ReclamationController::class, 'store']);
});

// Groupe Comptable
Route::middleware(['auth:sanctum', 'check.role:comptable'])->group(function () {
    Route::get('/situations-financieres', [SituationFinanciereController::class, 'index']);
    Route::post('/situations-financieres', [SituationFinanciereController::class, 'store']);
});

// Groupe scolarite
Route::middleware(['auth:sanctum', 'check.role:scolarite'])->group(function () {
    Route::get('/evaluations', [EvaluationController::class, 'index']);
    Route::post('/evaluations', [EvaluationController::class, 'store']);
    Route::put('/notes/{id}/valider', [NoteController::class, 'valider']);
    Route::post('/bulletins', [BulletinController::class, 'generer']);
    Route::post('/inscriptions', [InscriptionController::class, 'store']);
    Route::get('/inscriptions', [InscriptionController::class, 'index']);
});

// Groupe tous les rôles connectés
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::get('/notifications', function (Request $request) {
        return response()->json($request->user()->notifications);
    });
    Route::post('/notifications/lues', function (Request $request) {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'Notifications marquées comme lues']);
    });
});