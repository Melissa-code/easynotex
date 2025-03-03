<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Exception;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class DatabaseConnectionTest extends TestCase
{
    /**
     * Test database connection
     */
    public function test_database_connection(): void
    {
        try {
            DB::connection()->getPdo();
            $this->assertTrue(true); 
        } catch (Exception $e) {
            $this->fail("La connexion à la base de données a échoué : " . $e->getMessage());
        }
    }
}
