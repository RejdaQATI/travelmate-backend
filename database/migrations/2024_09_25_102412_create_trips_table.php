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
        Schema::create('trips', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title'); // Title of the trip
            $table->text('description'); // Description of the trip
            $table->string('destination'); // Destination name or region
            $table->integer('duration'); // Duration of the trip in days
            $table->string('image')->nullable(); // Path to an image for the trip
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null'); // Foreign key to cities (optional)
            $table->text('activities')->nullable();  // Colonne pour les activités
            $table->text('included')->nullable(); 
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};







