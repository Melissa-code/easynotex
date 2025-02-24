<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class NoteRepository
{
    public function getNotesByUser(int $userId): Collection
    {
        try {
            return DB::table('notes')
                ->join('categories', 'notes.category_id', '=', 'categories.id')
                ->where('notes.user_id', $userId)
                ->select(
                    'notes.id',
                    'notes.title',
                    'notes.content',
                    'notes.created_at',
                    'notes.updated_at',
                    'notes.isFavorite',
                    'notes.category_id',
                    'categories.name as category_name'
                )
                ->orderBy('notes.updated_at', 'desc')
                ->get();
        } catch (QueryException $e) {
            Log::error("Erreur SQL dans getNotesByUser pour userId: $userId", [
                'error' => $e->getMessage()
            ]);
            return collect([]);
        }
    }

    public function getNotesByUserOrderByFavorite(int $userId): Collection
    {
        try {
            return DB::table('notes')
            ->join('categories', 'notes.category_id', '=', 'categories.id')
            ->where('notes.user_id', $userId)
            ->select(
                'notes.id',
                'notes.title',
                'notes.content',
                'notes.created_at',
                'notes.updated_at',
                'notes.isFavorite',
                'notes.category_id',
                'categories.name as category_name'
            )
            ->orderBy('notes.isFavorite', 'desc')
            ->orderBy('notes.updated_at', 'desc')
            ->get();
        } catch (QueryException $e) {
            Log::error("Erreur SQL dans getNotesByUserOrderByFavorite pour userId: $userId", [
                'error' => $e->getMessage()
            ]);
            return collect([]);
        }
    }
}
