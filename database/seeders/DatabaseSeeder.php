<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin'
        ]);
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'rejda.qati95@gmail.com',
            'password' => bcrypt('user123'),
            'role' => 'user'
        ]);


        $this->call(TripSeeder::class);
        $this->call(TripDateSeeder::class);
        $this->call(CitySeeder::class);

    }
}


