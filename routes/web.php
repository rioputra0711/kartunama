<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BusinessCardController;

Route::get('/', [BusinessCardController::class, 'index'])->name('cards.index');
Route::get('/all-contacts', [BusinessCardController::class, 'allContacts'])->name('cards.allContacts');
Route::get('/recently-added', [BusinessCardController::class, 'recentlyAdded'])->name('cards.recentlyAdded');
Route::get('/oldest-added', [BusinessCardController::class, 'oldestAdded'])->name('cards.oldestAdded');
Route::get('/search', [BusinessCardController::class, 'search'])->name('cards.search');
Route::post('/cards/preview', [BusinessCardController::class, 'preview'])->name('cards.preview');
Route::post('/cards', [BusinessCardController::class, 'store'])->name('cards.store');
Route::put('/cards/{card}', [BusinessCardController::class, 'update'])->name('cards.update');
Route::delete('/cards/{card}', [BusinessCardController::class, 'destroy'])->name('cards.destroy');

