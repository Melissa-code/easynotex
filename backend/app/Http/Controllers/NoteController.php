<?php

namespace App\Http\Controllers;

use App\Repositories\NoteRepository;

class NoteController extends Controller
{
    protected $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function test()
    {
        return response()->json(['message' => 'NoteController fonctionne !'], 200);
    }

    /**
     * Get notes by User
     */
    public function getNotesByUser($id)
    {
        $notes = $this->noteRepository->getNotesByUser($id);

        if ($notes->isEmpty()) {
            return response()->json(['message' => 'Aucune note trouvée pour cet utilisateur'], 404);
        }

        return response()->json($notes, 200);
    }
}
