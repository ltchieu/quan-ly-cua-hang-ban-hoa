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
        // Update image paths from images/flower_*.jpg to flower_*.jpg
        DB::statement("UPDATE products SET image = REPLACE(image, 'images/', '') WHERE image LIKE 'images/flower_%'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert image paths back to images/flower_*.jpg
        DB::statement("UPDATE products SET image = CONCAT('images/', image) WHERE image LIKE 'flower_%'");
    }
};
