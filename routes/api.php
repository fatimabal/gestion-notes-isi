<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::middleware(['auth:sanctum','check.role:enseignant'])
->group(function(){

});

Route::middleware(['auth:sanctum','check.role:etudiant'])
->group(function(){

});

Route::middleware(['auth:sanctum','check.role:scolarite'])
->group(function(){

});


Route::middleware('auth:sanctum')->group(function(){
    Route::get('/profile',[ProfileController::class,'show']);
    Route::put('/profile',[ProfileController::class,'update']);
});