<?php 

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseMigrationTest extends TestCase
{
  use RefreshDatabase;  

  /**
   * Test if the database has these 3 tables 
   */
  public function test_database_has_all_table(): void
  {
      $this->assertTrue(Schema::hasTable('users'));
      $this->assertTrue(Schema::hasTable('categories'));
      $this->assertTrue(Schema::hasTable('notes'));
  }

  /**
   * Test columns in users table 
   */
  public function test_users_table_has_right_columns(): void
  {
    $this->assertTrue(Schema::hasColumn('users', 'last_name'));
    $this->assertTrue(Schema::hasColumn('users', 'first_name'));
    $this->assertTrue(Schema::hasColumn('users', 'email'));
    $this->assertTrue(Schema::hasColumn('users', 'password'));
    $this->assertTrue(Schema::hasColumn('users', 'role'));
  }

  /**
   * Test columns in categories table 
   */
  public function test_categories_table_has_right_columns(): void
  {
    $this->assertTrue(Schema::hasColumn('categories', 'name'));
  }

  /**
   * Test columns in notes table 
   */
  public function test_notes_table_has_right_columns(): void
  {
    $this->assertTrue(Schema::hasColumn('notes', 'title'));
    $this->assertTrue(Schema::hasColumn('notes', 'content'));
    $this->assertTrue(Schema::hasColumn('notes', 'image'));
    $this->assertTrue(Schema::hasColumn('notes', 'isFavorite'));
    $this->assertTrue(Schema::hasColumn('notes', 'category_id'));
    $this->assertTrue(Schema::hasColumn('notes', 'user_id'));
  }
}
