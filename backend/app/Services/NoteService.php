<?php

namespace App\Services;

use App\Models\Note;
use App\Repositories\NoteRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;

class NoteService
{
    public function __construct(
        protected NoteRepository $noteRepository
    ) {
    }

    /**
     * Get notes of the user order by updated_at
     *
     * @param int $userId
     * @return Collection
     * @throws Exception
     */
    public function getNotesByUser(int $userId): Collection
    {
        try {
            return $this->noteRepository->getNotesByUser($userId);
        } catch (Exception $e) {
            Log::error("Erreur dans NoteService getNotesByUser() " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get notes of the user order by favorites
     *
     * @param int $userId
     * @return Collection
     * @throws Exception
     */
    public function getNotesByUserOrderByFavorite(int $userId): Collection
    {
        try {
            return $this->noteRepository->getNotesByUserOrderByFavorite($userId);
        } catch (Exception $e) {
            Log::error("Erreur dans NoteService getNotesByUserOrderByFavorite(): " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get note by ID of the logged user
     *
     * @param int $noteId
     * @return ?Note
     * @throws Exception
     */
    public function getNoteById($noteId): ?Note
    {
        try {
            return $this->noteRepository->getNoteById($noteId);
        } catch (Exception $e) {
            Log::error("Erreur dans NoteService getNotesById(): " . $e->getMessage());
            throw $e;
        }
    }
}
