<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryControllerTest extends TestCase
{
  use RefreshDatabase; 

  /**
   * Test getCategories()
   * Test return 6 categories with a right structure in the right format JSON
   */
  public function testGetCategories(): void
  {
    Cache::flush(); 
    $this->categoriesFactory();

    $response = $this->getJson("/api/categories");
    $response->assertStatus(200);
    $response->assertJsonStructure([
      'categories' => [
          '*' => [
              'id',
              'name',
              'created_at',
              'updated_at',
          ],
      ],
    ]);
    $response->assertJsonCount(6, 'categories');
    // transform to array[] 
    $categories = $response->json('categories');
    $names = array_column($categories, 'name');
    $sortedNames = $names;
    sort($sortedNames);
    $this->assertEquals($sortedNames, $names);
  }

  /**
 * Test failed getCategories()
 * Test wrong alphabetical sort
 */
public function testFailedGetCategoriesWrongAlphabeticalSorted(): void
  {
    Cache::flush(); 
    $this->categoriesFactory();

    $response = $this->getJson("/api/categories");
    $response->assertStatus(200);
    $response->assertJsonStructure([
      'categories' => [
          '*' => [
              'id',
              'name',
              'created_at',
              'updated_at',
          ],
      ],
    ]);
    $response->assertJsonCount(6, 'categories');
    $categories = $response->json('categories');
    $names = array_column($categories, 'name');
    $sortedNames = $names;
    sort($sortedNames);
    $reversedNames = array_reverse($names); 
    $this->assertNotEquals($reversedNames, $sortedNames);
  }

  /**
   * Test failed getCategories()
   * Test error 404 (empty categories[])
   */
  public function testFailedGetCategoriesEmpty(): void
  {
    Cache::flush();

    $response = $this->getJson("/api/categories");
    $response->assertStatus(404);
    $response->assertJson([
        'message' => 'Aucune catégorie trouvée.',
    ]);
  }

  /**
   * Test failed getCategories()
   * Test error 404 (wrong url)
   */
  public function testFailedGetCategoriesPage404(): void
  {
    Cache::flush(); 
    Category::factory()->count(6)->create();

    $response = $this->getJson("/api/categorie");
    $response->assertStatus(404);
  }

  /**
   * Test failed getCategories()
   * Test error 405 method not allowed
   */
  public function testGetCategoriesWrongMethod(): void
  {
    Cache::flush();
    $this->categoriesFactory();

    $response = $this->postJson("/api/categories");
    $response->assertStatus(405); 
  }

  /**
   * Create 6 categories 
   * return $arrayCategories[]
   */
  private function categoriesFactory(): array
  {
    $arrayCategories = []; 

    $category1 = Category::factory()->create([
        "name" => "Travail-Études",
        "created_at" => "2025-03-03 14:50:25",
        "updated_at" => "2025-03-03 14:50:25",
    ]);
    $arrayCategories[] = $category1;

    $category2 =  Category::factory()->create([
      "name" => "Notes personnelles",
      "created_at" => "2025-03-03 15:50:25",
      "updated_at" => "2025-03-03 15:50:25",
    ]);
    $arrayCategories[] = $category2;

    $category3 =  Category::factory()->create([
      "name" => "Voyages-Sorties",
      "created_at" => "2025-03-03 16:50:25",
      "updated_at" => "2025-03-03 16:50:25",
    ]);
    $arrayCategories[] = $category3;

    $category4 =  Category::factory()->create([
      "name" => "Créativité-Loisirs",
      "created_at" => "2025-03-03 17:50:25",
      "updated_at" => "2025-03-03 17:50:25",
    ]);
    $arrayCategories[] = $category4;

    $category5 =  Category::factory()->create([
      "name" => "Santé-Bien-être",
      "created_at" => "2025-03-03 18:50:25",
      "updated_at" => "2025-03-03 18:50:25",
    ]);
    $arrayCategories[] = $category5;

    $category6 =  Category::factory()->create([
      "name" => "Finances-Administratif",
      "created_at" => "2025-03-03 19:50:25",
      "updated_at" => "2025-03-03 19:50:25",
    ]);
    $arrayCategories[] = $category6;

    return $arrayCategories; 
  }
}