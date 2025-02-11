<?php

namespace App\Http\Controllers;

use App\Repositories\NoteRepository;
use Illuminate\Http\JsonResponse;

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
    public function getNotesByUser($userId): JsonResponse
    {
        $notes = $this->noteRepository->getNotesByUser($userId);

        if ($notes->isEmpty()) {
            return response()->json(
                ['message' => 'Aucune note trouvée pour cet utilisateur'], 
                404
            );
        }

        return response()->json($notes, 200);
    }

    /**
     * Get notes by User order by favorite 
     */
    public function getNotesByUserOrderByFavorite($userId): JsonResponse
    {
        $notes = $this->noteRepository->getNotesByUserOrderByFavorite($userId);

        if ($notes->isEmpty()) {
            return response()->json(
                ['message' => 'Aucune note trouvée pour cet utilisateur'], 
                404
            );
        }

        return response()->json($notes, 200);
    }
}
