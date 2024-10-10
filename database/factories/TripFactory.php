<?php

namespace Database\Factories;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'pack_type' => $this->faker->randomElement(['standard', 'premium']),
            'destination' => $this->faker->randomElement(['Europe', 'Amérique', 'Afrika', 'Asie', 'Australie']),
            'duration' => $this->faker->numberBetween(3, 15),
            'image' => null, 
        ];
    }
}
