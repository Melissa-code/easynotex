<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Services\NoteService;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NoteController extends Controller
{
    public function __construct(
        protected NoteService $noteService
    ) {}

    /**
     * Get notes of a user order by updated_at
     *
     * @param string $userId
     * @return JsonResponse
     */
    public function getNotesByUser(string $userId): JsonResponse
    {
        return $this->fetchNotes(
            function () use ($userId) {
                return $this->noteService->getNotesByUser((int)$userId);
            },
            (int)$userId,
            'Erreur dans getNotesByUser'
        );
    }

    /**
     * Get notes of a user order by favorites
     *
     * @param string $userId
     * @return JsonResponse
     */
    public function getNotesByUserOrderByFavorite(string $userId): JsonResponse
    {
        return $this->fetchNotes(
            function () use ($userId) {
                return $this->noteService->getNotesByUserOrderByFavorite((int)$userId);
            },
            (int)$userId,
            'Erreur dans getNotesByUserOrderByFavorite'
        );
    }

    /**
     * Get notes of the user
     *
     * @param callable $getNotes
     * @param int $userId
     * @param string $errorContext
     * @return JsonResponse
     */
    private function fetchNotes(callable $getNotes, int $userId, string $errorContext): JsonResponse
    {
        try {
            if (!is_numeric($userId) || $userId <= 0) {
                Log::warning("L\'identifiant utilisateur est invalide: $userId");
                return response()->json(['message' => 'L\'identifiant utilisateur est invalide'], 400);
            }

            if (!User::where('id', $userId)->exists()) {
                Log::warning("Utilisateur non trouvé: $userId");
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }

            $notes = $getNotes();

            if ($notes->isEmpty()) {
                Log::warning("Aucune note trouvée pour cet utilisateur: $userId");
                return response()->json(['message' => 'Aucune note trouvée pour cet utilisateur'], 404);
            }

            return response()->json($notes, 200);
        } catch (Exception $e) {
            Log::error("Une erreur est survenue lors de la récupération des notes");
            return response()->json([
                'message' => "$errorContext : Une erreur est survenue lors de la récupération des notes",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get note by ID
     *
     * @param int $noteId
     * @param string $errorContext
     * @return JsonResponse
     */
    public function getNoteById($noteId)
    {
        try {
            if (!is_numeric($noteId) || (int)$noteId <= 0) {
                Log::warning("ID de note invalide : $noteId");
                return response()->json(['error' => 'ID de note invalide'], 400);
            }

            $note = $this->noteService->getNoteById((int)$noteId);

            if (!$note) {
                Log::error("Une erreur est survenue lors de la récupération de la note par ID", ['noteId' => $noteId]);
                return response()->json(['error' => 'Note non trouvée'], 404);
            }

            return response()->json($note);
        } catch (Exception $e) {
            Log::error("Erreur inconnue dans getNoteById()", [
                'noteId' => $noteId,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Une erreur s\'est produite'], 500);
        }
    }

    /**
     * DELETE note by ID
     *
     * @param int $noteId
     * @return JsonResponse
     */
    public function deleteNoteById($noteId)
    {
        try {
            if (!is_numeric($noteId) || (int)$noteId <= 0) {
                Log::warning("ID note invalide pour la suppression : $noteId");
                return response()->json(['error' => 'ID note invalide'], 400);
            }

            $this->noteService->deleteNoteById((int)$noteId);

            return response()->json(['message' => 'Suppression de la note réussie'], 200);
            
        } catch (ModelNotFoundException $e) {
            Log::warning("Note non trouvée pour suppression", ['noteId' => $noteId]);
            return response()->json(['error' => 'Note non trouvée'], 404);
        } catch (Exception $e) {
            Log::error("Erreur inconnue dans deleteNoteById()", [
                'noteId' => $noteId,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Une erreur s\'est produite lors de la suppression'], 500);
        }
    }
}
