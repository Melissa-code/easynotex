<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Note;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


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
        Storage::fake('public');

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
     * Test store note (create a note with an image file) 
     * 
     * This test verifies that a note can be successfully created via the API
     * including uploading an image file & checks that the note is stored in the DB
     * with the correct attributes and that the image is saved to the storage disk
     */
    public function testStoreNote(): void{
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $note = [
            'title' => 'Note de test 1',
            'content' => 'Contenu de la note de test 1', 
            'isFavorite' => 0,
            'image' => UploadedFile::fake()->image('testimage.jpg'),
            'created_at' => '2025-07-10 17:18:00',
            'updated_at' => '2025-07-10 17:18:00',
            'category_id' => $category->id, 
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/notes/store_note', $note);
        $response->assertStatus(201)
            ->assertJsonFragment ([
                'title' => 'Note de test 1',
                'content' => 'Contenu de la note de test 1',
                'isFavorite' => 0,
            ]);

        $this->assertDatabaseHas ('notes', [
            'title' => 'Note de test 1',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue (
            Storage::disk('public')->exists('notes_images/' . $note['image']->hashName())
        );
    }

    /**
     * Test store note (create a note without an image file) 
     * 
     * This test verifies that a note can be successfully created via the API
     * without uploading an image file & checks that the note is stored in the DB
     */
    public function testStoreNoteWithoutImage(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $note = [
            'title' => 'Note de test sans img',
            'content' => 'Contenu de la Note de test sans img', 
            'isFavorite' => 0,
            'category_id' => $category->id, 
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/notes/store_note', $note);
        
        $response->assertStatus(201)
            ->assertJsonFragment ([
                'title' => 'Note de test sans img',
                'content' => 'Contenu de la Note de test sans img',
                'isFavorite' => 0,
            ]);

        $this->assertDatabaseHas ('notes', [
            'title' => 'Note de test sans img',
            'category_id' => $category->id,
            'user_id' => $user->id,
            'image' => null,
        ]);
    }

    /**
     * Test store note with invalid image format
     * 
     * This test verifies that an error is returned when trying to upload a file
     * that is not an image (ex: a text file) and checks that the note is not stored in the DB
     * and no image is saved to the storage disk          
    */
    public function testStoreNoteWithInvalidFormatImage(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $note = [
            'title' => 'Note de test avec format image invalide',
            'content' => 'Contenu de la note de test avec format image invalide',
            'isFavorite' => 0,
            'image' => UploadedFile::fake()->create('testfile.txt', 100), // txt file
            'category_id' => $category->id, 
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/notes/store_note', $note);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);

        $this->assertDatabaseMissing('notes', [
            'title' => 'Note de test avec format image invalide',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test store note with image too large
     * 
     * This test verifies that an error is returned when trying to upload an image
     * that exceeds the maximum allowed size (5MB)   
     */
    public function testStoreNoteWithImageTooLarge(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $note = [
            'title' => 'Note avec image trop grande',
            'content' => 'Contenu de la note',
            'image' => UploadedFile::fake()->image('large_image.jpg')->size(5000), // 5MB
            'category_id' => $category->id,
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/notes/store_note', $note);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    /**
     * Test store note with validation errors
     * 
     * This test verifies that the API correctly handles validation errors
     * when required fields are missing or invalid 
     */
    public function testStoreNoteWithValidationErrors(): void
    {
        $user = User::factory()->create();
       
        $note = [
            // no title
            'content' => 'Contenu sans titre',
            'category_id' => 999, // category does not exist
        ];

        $response = $this->actingAs($user)->postJson('/api/notes/store_note', $note);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'category_id']);
    }

    /**
     * Test store note with empty title
     * 
     * This test verifies that the API returns a validation error
     * when trying to create a note with an empty title
     */
    public function testStoreNoteWithEmptyTitle(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $note = [
            'title' => '',
            'content' => 'Contenu de la note',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/notes/store_note', $note);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /**
     * Test store note with a title too long
     * 
     * This test verifies that the API returns a validation error
     * when trying to create a note with a title that exceeds the maximum length
     */
    public function testStoreNoteWithTitleTooLong(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $note = [
            'title' => str_repeat('A', 256), //title 256 chars
            'content' => 'Contenu de la note',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/notes/store_note', $note);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /**
     * Test store note with favorite flag
     * 
     * This test verifies that a note can be created with the favorite flag set to true
     * and checks that the note is stored in the DB with the correct favorite status
     */
    public function testStoreNoteWithFavorite(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $note = [
            'title' => 'Note favorite',
            'content' => 'Contenu de la note favorite',
            'isFavorite' => 1,
            'category_id' => $category->id,
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/notes/store_note', $note);
        
        $response->assertStatus(201)
            ->assertJsonFragment([
                'isFavorite' => 1,
            ]);

        $this->assertDatabaseHas('notes', [
            'title' => 'Note favorite',
            'isFavorite' => 1,
        ]);
    }

    /**
     * Test store note with storage failure
     * 
     * This test simulates a failure in the storage system
     * and verifies that the API returns an appropriate error response
     */
    public function testStoreNoteWithStorageFailure(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        // Désactiver le storage fake pour simuler une erreur
        Storage::shouldReceive('disk')
            ->with('public')
            ->andThrow(new \Exception('Erreur de stockage'));

        $note = [
            'title' => 'Note avec erreur storage',
            'content' => 'Contenu de la note',
            'image' => UploadedFile::fake()->image('test.jpg'),
            'category_id' => $category->id,
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/notes/store_note', $note);
        
        $response->assertStatus(500);
    }

     /**
     * Test store note with missing category
     * 
     * This test verifies that the API returns a validation error
     * when trying to create a note with a category that does not exist
     */
    public function testStoreNoteWithMissingCategory(): void
    {
        $user = User::factory()->create();

        $note = [
            'title' => 'Note sans catégorie',
            'content' => 'Contenu de la note',
            'category_id' => 999, // ID does not exist
            'isFavorite' => 0,              
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/notes/store_note', $note);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category_id']);
    }

    /**
     * Test store note with forbidden special characters
     * 
     * This test verifies that the API returns a validation error
     * when trying to create a note with content that contains forbidden special characters
     */
    public function testStoreNoteWithForbiddenSpecialCharacters(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $note = [
            'title' => 'Note avec caractères interdits',
            'content' => 'Contenu avec émojis 🎉 et caractères spéciaux <>"\&',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/notes/store_note', $note);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content'])
            ->assertJsonFragment([
                'message' => 'Erreur de validation',
                'errors' => [
                    'content' => [
                        'Le contenu contient des caractères non autorisés.'
                    ]
                ]
            ]);
    }

    /**
     * Test store note response structure 
     * 
     * This test verifies that the API returns the correct JSON structure
     * when a note is successfully created
     */
    public function testStoreNoteResponseStructureAlternative(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $note = [
            'title' => 'Test structure réponse',
            'content' => 'Contenu pour tester la structure',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/notes/store_note', $note);
        
        $response->assertStatus(201);
        
        $response->assertJsonStructure([
            'message',
            'note' => [
                'id',
                'title',
                'content',
                'isFavorite',
                'image',
                'category_id',
                'user_id',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    /**
     * Test store note rejects HTML content
     * 
     * This test verifies that the API returns a validation error
     * when trying to create a note with HTML content
     */
    public function testStoreNoteRejectsHtmlContent(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $note = [
            'title' => 'Note avec HTML',
            'content' => '<p>Contenu avec <strong>HTML</strong></p>',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/notes/store_note', $note);
        
        $response->assertStatus(422);
        
        $response->assertJsonValidationErrors(['content']);
        
        $response->assertJson([
            'errors' => [
                'content' => ['Le contenu contient des caractères non autorisés.']
            ]
        ]);
    }

    /**
     * Create 3 notes for a user 
     * 
     * This method creates 3 notes for a given user and category
     * and returns an array of created Note models
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
