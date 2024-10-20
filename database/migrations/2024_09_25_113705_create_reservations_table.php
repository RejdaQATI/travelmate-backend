
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('trip_date_id')->constrained('trip_dates')->onDelete('cascade'); 
            $table->integer('number_of_participants');
            $table->enum('status', ['en attente', 'confirmé', 'annulé'])->default('en attente'); 
            $table->enum('payment_status', ['en attente', 'payé', 'échoué'])->default('en attente'); 
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
