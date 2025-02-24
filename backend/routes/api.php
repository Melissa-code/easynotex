<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\categoryController;
use Illuminate\Support\Facades\Route;

Route::get('notes/user/{id}', [NoteController::class, 'getNotesByUser']);
Route::get('notes/favorite/user/{id}', [NoteController::class, 'getNotesByUserOrderByFavorite']);
Route::get('categories', [CategoryController::class, 'getCategories']);