<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class NoteRepository
{
    public function getNotesByUser($userId): Collection
    {
        return DB::table('notes')
            ->join('categories', 'notes.category_id', '=', 'categories.id') 
            ->where('notes.user_id', $userId)
            ->select('notes.id', 'notes.title', 'notes.content', 'notes.created_at', 'notes.updated_at', 'notes.isFavorite', 'categories.name as categories_name') 
            ->orderBy('notes.created_at', 'desc')
            ->get();
    }

    public function getNotesByUserOrderByFavorite($userId): Collection
    {
        return DB::table('notes')
            ->join('categories', 'notes.category_id', '=', 'categories.id') 
            ->where('notes.user_id', $userId)
            ->select('notes.id', 'notes.title', 'notes.content', 'notes.created_at', 'notes.updated_at', 'notes.isFavorite', 'categories.name as categories_name') 
            ->orderBy('notes.isFavorite', 'desc')
            ->orderBy('notes.created_at', 'desc')
            ->get();
    }
}
