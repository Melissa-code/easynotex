<?php

namespace App\Services;

use Exception;
use App\Models\Note;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Repositories\NoteRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NoteService
{
    public function __construct(
        protected NoteRepository $noteRepository
    ) {
    }

    /**
     * GET notes of the user order by updated_at
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
     * GET notes of the user order by favorites
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
     * GET note by ID of the logged user
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

    /**
     * DELETE note by ID of the logged user
     *
     * @param int $noteId
     * @throws Exception
     */
    public function deleteNoteById($noteId): void
    {
        try {
            $note = Note::findOrFail($noteId);
            $note->delete(); //suppr in DB
        } catch (Exception $e) {
            Log::error("Erreur dans NoteService deleteNoteById(): " . $e->getMessage());
            throw $e;
        }
    }


    /**
     * CREATE note
     *
     * @param array $data
     * @throws Exception
     */
    public function storeNote(array $data): ?Note
    {
        try {
            if (isset($data['image'])) {
                // sauvegarde physiquement le fichier image sur le disque(public) dans le dossier
                $imagePath = $data['image']->store('notes_images', 'public');
                unset($data['image']); //supprime l'objet UploadedFile
                $data['image'] = $imagePath;
            }

            // sauvegarde DB avec chemin du fichier et non l'objet fichier
            return $this->noteRepository->storeNote($data);
        } catch (Exception $e) {
            Log::error("Erreur dans NoteService storeNote(): " . $e->getMessage());
            throw $e;
        }
    }
}
