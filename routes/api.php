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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

// Groupe enseignant
Route::middleware(['auth:sanctum', 'check.role:enseignant'])
    ->group(function () {
        Route::post('/notes', [NoteController::class, 'store']);
        Route::put('/notes/{id}', [NoteController::class, 'update']);
    });

// Groupe etudiant
Route::middleware(['auth:sanctum', 'check.role:etudiant'])
    ->group(function () {
        Route::get('/notes', [NoteController::class, 'index']);
        Route::post('/notes/{id}/reclamer', [NoteController::class, 'reclamer']);
        Route::get('/bulletins', [BulletinController::class, 'consulter']);
        Route::post('/reclamations', [ReclamationController::class, 'store']);
    });

// Groupe scolarite
Route::middleware(['auth:sanctum', 'check.role:scolarite'])
    ->group(function () {
        Route::get('/classes', [ClasseController::class, 'index']);
        Route::post('/classes', [ClasseController::class, 'store']);
        Route::get('/matieres', [MatiereController::class, 'index']);
        Route::post('/matieres', [MatiereController::class, 'store']);
        Route::get('/semestres', [SemestreController::class, 'index']);
        Route::post('/semestres', [SemestreController::class, 'store']);
        Route::get('/evaluations', [EvaluationController::class, 'index']);
        Route::post('/evaluations', [EvaluationController::class, 'store']);
        Route::put('/notes/{id}/valider', [NoteController::class, 'valider']);
        Route::post('/bulletins', [BulletinController::class, 'generer']);
        Route::get('/historique', [HistoriqueModificationController::class, 'index']);
        Route::post('/inscriptions', [InscriptionController::class, 'store']);
        Route::get('/inscriptions', [InscriptionController::class, 'index']);
        Route::get('/reclamations', [ReclamationController::class, 'index']);
        Route::get('/situations-financieres', [SituationFinanciereController::class, 'index']);
        Route::post('/situations-financieres', [SituationFinanciereController::class, 'store']);
        Route::get('/departements', [DepartementController::class, 'index']);
        Route::post('/departements', [DepartementController::class, 'store']);
        Route::get('/filieres', [FiliereController::class, 'index']);
        Route::post('/filieres', [FiliereController::class, 'store']);
        Route::get('/annees-academiques', [AnneeAcademiqueController::class, 'index']);
        Route::post('/annees-academiques', [AnneeAcademiqueController::class, 'store']);
        Route::get('/ues', [UeController::class, 'index']);
        Route::post('/ues', [UeController::class, 'store']);
    });

// Groupe tous les rôles connectés
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
});
