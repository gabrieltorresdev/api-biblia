<?php

use App\Http\Controllers\LivrosController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/', fn() => response(["app" => "API-BIBLIA", "version" => "1.0.0"]));

    Route::get('/livros', [LivrosController::class, 'index'])->name('livros.index');
});

