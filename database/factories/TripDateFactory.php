<?php

namespace Database\Factories;

use App\Models\TripDate;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripDateFactory extends Factory
{
    // protected $model = TripDate::class;

    public function definition()
    {
    //     $startDate = $this->faker->dateTimeBetween('+1 week', '+2 months');
    //     $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(5, 14) . ' days');

    //     return [
    //         'trip_id' => Trip::factory(), // Relation avec un voyage
    //         'start_date' => $startDate,
    //         'end_date' => $endDate,
    //         'price' => $this->faker->randomFloat(2, 100, 2000), // Prix entre 100 et 2000$
    //         'max_participants' => $this->faker->numberBetween(5, 15), // Nombre max de participants
    //     ];
    }
}
