<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController; 

Route::get('notes/user/{id}', [NoteController::class, 'getNotesByUser']);
Route::get('notes/favorite/user/{id}', [NoteController::class, 'getNotesByUserOrderByFavorite']);