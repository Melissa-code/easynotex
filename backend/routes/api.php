<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController; 

Route::get('test', function () {
    return response()->json(['message' => 'API fonctionne'], 200);
});
Route::get('notes/test', [NoteController::class, 'test']);

Route::get('notes/user/{id}', [NoteController::class, 'getNotesByUser']);
