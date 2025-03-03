<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes on 'user_id' and 'category_id' (table 'notes')
        Schema::table('notes', function (Blueprint $table) {
            $table->index('user_id');    
            $table->index('category_id'); 
        });

        // Add index on col 'id' (table 'categories')
        Schema::table('categories', function (Blueprint $table) {
            $table->index('id');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes if the migration is removed 
        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'category_id']); 
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['id']);
        });
    }
};
