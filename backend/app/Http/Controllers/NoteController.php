<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Services\NoteService;
use Illuminate\Http\Response;

class NoteController extends Controller
{
    public function __construct(
        protected NoteService $noteService
    ) {
    }

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
    * Get note by ID of the user
    *
    * @param int $noteId
    * @param string $errorContext
    * @return JsonResponse
     */
    public function getNoteById($noteId)
    {
        if (!is_numeric($noteId) || (int)$noteId <= 0) {
            Log::warning("ID de note invalide : $noteId");
            return response()->json(['error' => 'ID de note invalide'], 400);
        }
    
        $note = $this->noteService->getNoteById((int)$noteId); 
      
        if (!$note) {
            Log::error("Une erreur est survenue lors de la récupération de la note par ID", $noteId);
            return response()->json(['error' => 'Note non trouvée'], 404); 
        }

        return response()->json($note);
    }
}
