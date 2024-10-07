<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\TripDate;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    // protected $model = Reservation::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Relation avec un utilisateur
            'trip_date_id' => TripDate::factory(), // Relation avec une période de voyage
            'number_of_participants' => $this->faker->numberBetween(1, 5), // Nombre aléatoire de participants
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'payment_status' => $this->faker->randomElement(['pending', 'paid', 'failed']),
        ];
    }
}
