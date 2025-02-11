<?php

namespace App\Http\Controllers;

use App\Repositories\NoteRepository;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class NoteController extends Controller
{
    protected $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * Get notes by User order by created_at
     */
    public function getNotesByUser(string $userId): JsonResponse
    {
        return $this->fetchNotes(
            function () use ($userId) {
                return $this->noteRepository->getNotesByUser((int)$userId);
            },
            (int)$userId,
            'Erreur dans getNotesByUser'
        );
    }

    /**
     * Get notes by User order by favorite
     */
    public function getNotesByUserOrderByFavorite(string $userId): JsonResponse
    {
        return $this->fetchNotes(
            function () use ($userId) {
                return $this->noteRepository->getNotesByUserOrderByFavorite((int)$userId);
            },
            (int)$userId,
            'Erreur dans getNotesByUserOrderByFavorite'
        );
    }

    private function fetchNotes(callable $getNotes, int $userId, string $errorContext): JsonResponse
    {
        try {
            if (!is_numeric($userId) || $userId <= 0) {
                Log::error("L\'identifiant utilisateur est invalide: $userId");
                return response()->json(['message' => 'L\'identifiant utilisateur est invalide'], 400);
            }

            $notes = $getNotes();

            if (!$notes) {
                Log::error("Aucune note trouvée pour cet utilisateur: $userId");
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
}
