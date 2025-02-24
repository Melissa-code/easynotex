<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Repositories\NoteRepository;

class NoteController extends Controller
{

    public function __construct(
        protected NoteRepository $noteRepository
    ) {}

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

            if (!User::find($userId)) {
                Log::error("Utilisateur non trouvé: $userId");
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }

            $notes = $getNotes();

            if ($notes->isEmpty()) {
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
