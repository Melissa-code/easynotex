<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::get('notes/user/{id}', [NoteController::class, 'getNotesByUser']);
Route::get('notes/favorite/user/{id}', [NoteController::class, 'getNotesByUserOrderByFavorite']);
