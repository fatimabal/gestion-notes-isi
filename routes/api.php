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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

// Groupe enseignant
Route::middleware(['auth:sanctum','check.role:enseignant'])
->group(function(){
    Route::post('/notes',[NoteController::class,'store']);
    Route::put('/notes/{id}',[NoteController::class,'update']);
});


// Groupe etudiant
Route::middleware(['auth:sanctum','check.role:etudiant'])
->group(function(){
    Route::get('/notes',[NoteController::class,'index']);
    Route::post('/notes/{id}/reclamer',[NoteController::class,'reclamer']);
});

// Groupe scolarite
Route::middleware(['auth:sanctum','check.role:scolarite'])
->group(function(){
    Route::get('/classes',[ClasseController::class,'index']);
    Route::post('/classes',[ClasseController::class,'store']);
    Route::get('/matieres',[MatiereController::class,'index']);
    Route::post('/matieres',[MatiereController::class,'store']);
    Route::get('/semestres',[SemestreController::class,'index']);
    Route::post('/semestres',[SemestreController::class,'store']);
    Route::get('/evaluations',[EvaluationController::class,'index']);
    Route::post('/evaluations',[EvaluationController::class,'store']);
    Route::put('/notes/{id}/valider',[NoteController::class,'valider']);
});

// Groupe tous les rôles connectés
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/profile',[ProfileController::class,'show']);
    Route::put('/profile',[ProfileController::class,'update']);
});