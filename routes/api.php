<?php

use App\Http\Controllers\Api\PessoaControlador;
use App\Http\Controllers\Api\AutenticacaoControlador;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AutenticacaoControlador::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AutenticacaoControlador::class, 'logout']);
    Route::get('/usuario-autenticado', [AutenticacaoControlador::class, 'usuarioAutenticado']);

    Route::apiResource('pessoas', PessoaControlador::class);
});