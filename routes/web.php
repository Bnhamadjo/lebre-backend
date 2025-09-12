<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\AlunoController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/professores', [ProfessorController::class, 'index']);
    Route::get('/professores/{id}', [ProfessorController::class, 'show']);
    Route::post('/professores', [ProfessorController::class, 'store']);
    Route::put('/professores/{id}', [ProfessorController::class, 'update']);
    Route::delete('/professores/{id}', [ProfessorController::class, 'destroy']);
    Route::post('/alunos', [AlunoController::class, 'store']);
});
