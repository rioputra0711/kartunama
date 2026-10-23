<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BusinessCardController;

Route::get('/', [BusinessCardController::class, 'index'])->name('cards.index');
Route::post('/cards', [BusinessCardController::class, 'store'])->name('cards.store');
Route::delete('/cards/{card}', [BusinessCardController::class, 'destroy'])->name('cards.destroy');

