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
     * Test return notes link to a user with right data in the right format JSON
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
                'category_name'
                ])
            )
        );

        $response->assertJsonFragment(['title' => $note1->title]);
        $response->assertJsonFragment(['title' => $note2->title]);
    }

    /**
     * Test failed getNotesByUser()
     * but user hasn't any note
     */
    public function testFailedGetNoNotesByUser(): void
    {
        $user = User::factory()->create();
        
        $response = $this->getJson("/api/notes/user/{$user->id}");
        $response->assertStatus(200);
        $response->assertJson([]); 
    }

    /**
     * Test getNotesByUserOrderByFavorite()
     * Test return notes link to a user with right data in the right format JSON
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
                'category_name'
                ])
            )
        );
        $response->assertJsonFragment(['title' => $note1->title]);
        $response->assertJsonFragment(['title' => $note2->title]);
        $response->assertJsonFragment(['title' => $note3->title]);
  
        // Check the sort
        $responseJson = $response->json();
        $this->assertTrue($responseJson[0]['isFavorite'] === 1);
        $this->assertTrue($responseJson[1]['isFavorite'] === 1);
        $this->assertTrue($responseJson[2]['isFavorite'] === 0);
    }

    /**
     * Test failed getNotesByUser()
     * Test return notes link to a user with right data in the right format JSON
     * but in a wrong sort 
     */
    public function testFailedGetNotesByUserOrderByFavorite(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $notes = $this->notesFactory($user, $category);
        //list($note1, $note2, $note3) = $notes;

        $response = $this->getJson("/api/notes/favorite/user/{$user->id}");
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $responseJson = $response->json();
        $this->assertFalse($responseJson[0]['isFavorite'] === 0); 
        $this->assertfalse($responseJson[1]['isFavorite'] === 0); 
        $this->assertFalse($responseJson[2]['isFavorite'] === 1); 
    }


    /**
     * Create 3 notes for a user 
     * return $arrayNotes[]
     */
    private function notesFactory($user, $category): array
    {
        $arrayNotes = []; 

        $note1 = Note::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Où sortir ce week-end',
            'content' => 'Lorem ipsum lorem ipsum...',
            'isFavorite' => 0,
        ]);
        $arrayNotes[] = $note1;

        $note2 = Note::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Ne pas oublier de faire...',
            'content' => 'Lorem ipsum lorem ipsum...',
            'isFavorite' => 1,
        ]);
        $arrayNotes[] = $note2;

        $note3 = Note::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Autre note favorite',
            'isFavorite' => 1, 
        ]);
        $arrayNotes[] = $note3;

        return $arrayNotes;
    }
}
