<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class NoteRepository
{
    public function getNotesByUser($userId)
    {
        return DB::table('notes')
            ->where('user_id', $userId)
            ->select('id', 'title', 'content', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
