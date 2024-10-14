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
            $table->id(); 
            $table->string('title'); 
            $table->text('description'); 
            $table->string('destination'); 
            $table->integer('duration'); 
            $table->string('image')->nullable(); 
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->text('activities')->nullable();  
            $table->text('included')->nullable(); 
            $table->timestamps(); 
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







