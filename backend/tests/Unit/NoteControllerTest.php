<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Note;
use App\Models\User;
use App\Models\Category;

class NoteControllerTest extends TestCase
{
    use RefreshDatabase; 

    /**
     * Test getNotesByUser()
     * Test return notes of a user with right data in the right format JSON
     * Sort by updated_at
     */
    public function testGetNotesByUser(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $notes = $this->notesFactory($user, $category);
        list($note1, $note2, $note3) = $notes;

        $response = $this->getJson("/api/notes/user/{$user->id}");
        $response->assertStatus(200);
        $response->assertJson(fn ($json) =>
        $json->each(fn ($note) =>
            $note->hasAll([
                'id',
                'title',
                'content',
                'created_at',
                'updated_at',
                'isFavorite',
                'category_id',
                'category_name'
                ])
            )
        );

        $response->assertJsonFragment(['title' => $note1->title]);
        $response->assertJsonFragment(['title' => $note2->title]);
        $response->assertJsonFragment(['title' => $note3->title]);
        $responseJson = $response->json();
        $this->assertTrue($responseJson[0]['updated_at'] >= $responseJson[1]['updated_at']);
        $this->assertTrue($responseJson[1]['updated_at'] >= $responseJson[2]['updated_at']);
    }

    /**
     * Test failed getNotesByUser()
     * but in a wrong sort 
     */
    public function testFailedGetNotesByUser(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->notesFactory($user, $category);

        $response = $this->getJson("/api/notes/favorite/user/{$user->id}");
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $responseJson = $response->json();
        $this->assertTrue($responseJson[0]['updated_at'] >= $responseJson[1]['updated_at']);
        $this->assertTrue($responseJson[1]['updated_at'] <= $responseJson[2]['updated_at']);
    }

    /**
     * Test getNotesByUserOrderByFavorite()
     * Test return notes link to a user with right data in the right format JSON
     * sort by favorite
     */
    public function testGetNotesByUserOrderByFavorite(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $notes = $this->notesFactory($user, $category);
        list($note1, $note2, $note3) = $notes;
        
        $response = $this->getJson("/api/notes/favorite/user/{$user->id}");
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJson(fn ($json) =>
        $json->each(fn ($note) =>
            $note->hasAll([
                'id',
                'title',
                'content',
                'created_at',
                'updated_at',
                'isFavorite',
                'category_id',
                'category_name'
                ])
            )
        );
        $response->assertJsonFragment(['title' => $note1->title]);
        $response->assertJsonFragment(['title' => $note2->title]);
        $response->assertJsonFragment(['title' => $note3->title]);
  
        $responseJson = $response->json();
        $this->assertTrue($responseJson[0]['isFavorite'] === 1);
        $this->assertTrue($responseJson[1]['isFavorite'] === 1);
        $this->assertTrue($responseJson[2]['isFavorite'] === 0);
    }

    /**
     * Test failed getNotesByUserOrderByFavorite()
     * but in a wrong sort 
     */
    public function testFailedGetNotesByUserOrderByFavorite(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->notesFactory($user, $category);

        $response = $this->getJson("/api/notes/favorite/user/{$user->id}");
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $responseJson = $response->json();
        $this->assertFalse($responseJson[0]['isFavorite'] === 0); 
        $this->assertfalse($responseJson[1]['isFavorite'] === 0); 
        $this->assertFalse($responseJson[2]['isFavorite'] === 1); 
    }

    /**
     * Test failed fetchNotes() 
     * $user_id is invalid in the URL 
     */
    public function testFailedFetchNotesWithInvalidUserId(): void
    {
        $invalidUserId = ['abc', -1, 0]; 

        foreach ($invalidUserId as $invalidUserId) {
            $response = $this->getJson("/api/notes/user/{$invalidUserId}");
            $response->assertStatus(400);
            $response->assertJson(['message' => "L'identifiant utilisateur est invalide"]);
        }
    }

    /**
     * Test failed fetchNotes() 
     * $user doesn't exist 
     */
    public function testFailedFetchNotesForNonExistantUser(): void
    {
        $nonExistantUserId = 99999;

        $response = $this->getJson("/api/notes/user/{$nonExistantUserId}");
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Utilisateur non trouvé']);
    }

    /**
     * Test failed fetchNotes() 
     * any notes for the user 
     */
    public function testFailedFetchNotesNoNote(): void
    {
        $user = User::factory()->create();
        
        $response = $this->getJson("/api/notes/user/{$user->id}");
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Aucune note trouvée pour cet utilisateur']); 
    }

    /**
     * Test getNoteById
     * ID is valid & note exists 
     */
    public function testGetNoteById(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $notes = $this->notesFactory($user, $category);
        $note = $notes[0];

        $response = $this->getJson("/api/notes/{$note->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'title',
            'content',
            'created_at',
            'updated_at',
            'isFavorite',
            'category_id',
            'category_name',
            'user_id',
            'image', 
        ]);

        $response->assertJson([
            'id' => $note->id,
            'title' => $note->title,
            'content' => $note->content,
            'created_at' => $note->created_at->toJSON(),
            'updated_at' => $note->updated_at->toJSON(),
            'isFavorite' => $note->isFavorite,
            'category_id' => $note->category_id,
            'category_name' => $category->name,
            'user_id' => $note->user_id,
        ]);
    }

    /**
     * Test failed get note by id
     * id invalid
     */
    public function testFailedGetNoteByInvalidId(): void 
    {
        $response = $this->getJson("/api/notes/abdhd");
        $response->assertStatus(400);
        $response->assertJson(['error' => 'ID de note invalide']);

        $response = $this->getJson("/api/notes/-1"); 
        $response->assertStatus(400);
        $response->assertJson(['error' => 'ID de note invalide']);
    }

    /**
     * Test failed get note by id
     * note doesn't exist 
     */
    public function testGetNoteByIdNotFound(): void
    {
        $response = $this->getJson("/api/notes/9999"); 
        $response->assertStatus(404);
        $response->assertJson(['error' => 'Note non trouvée']);
    }

    /**
     * Test delete note by id
     * 
     */
    public function testDeleteNoteById(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $notes = $this->notesFactory($user, $category);
        $note = $notes[0];

        $response = $this->deleteJson("/api/notes/supprime_note/{$note->id}");//deleteJson()
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Suppression de la note réussie']);
        
        // noted deleted in DB
        $this->assertDatabaseMissing('notes', ['id' => $note->id]); 
    }

    /**
     * Test delete note by id
     * invalid ID
     */
    public function testFailedDeleteNoteByInvalidId(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $notes = $this->notesFactory($user, $category);
        $note = $notes[0];
        // ID string
        $response = $this->deleteJson("/api/notes/supprime_note/ahfdgdsfdhsf");
        $response->assertStatus(400);
        $response->assertJson(['error' => 'ID note invalide']);
        // ID negatif 
        $response = $this->deleteJson("/api/notes/supprime_note/-1");
        $response->assertStatus(400);
        $response->assertJson(['error' => 'ID note invalide']);
        // ID ==0
        $response = $this->deleteJson("/api/notes/supprime_note/0");
        $response->assertStatus(400);
        $response->assertJson(['error' => 'ID note invalide']);
    }

    /**
     * Create 3 notes for a user 
     * return $arrayNotes[]
     */
    private function notesFactory($user, $category, $count = 3): array
    {
        $notesData = [
            [
                'title' => 'Où sortir ce week-end',
                'content' => 'Lorem ipsum lorem ipsum...',
                'isFavorite' => 0,
                "created_at" => "2025-02-07 14:50:25",
                "updated_at" => "2025-02-07 14:50:25",
            ],
            [
                'title' => 'Ne pas oublier de faire...',
                'content' => 'Lorem ipsum lorem ipsum...',
                'isFavorite' => 1,
                "created_at" => "2025-02-08 14:50:25",
                "updated_at" => "2025-02-08 14:50:25",
            ],
            [
                'title' => 'Autre note favorite',
                'content' => 'Lorem ipsum lorem ipsum...',
                'isFavorite' => 1,
                "created_at" => "2025-02-05 14:50:25",
                "updated_at" => "2025-02-05 14:50:25",
            ],
        ];
    
        $arrayNotes = [];
        for ($i = 0; $i < $count; $i++) {
            //index reste toujours entre 0 et 2 pour que le code soit utilisé avec plus de 3 notes
            $data = $notesData[$i % count($notesData)]; 
            $data['user_id'] = $user->id;
            $data['category_id'] = $category->id;
            $arrayNotes[] = Note::factory()->create($data);
        }
    
        return $arrayNotes;
    }

}
