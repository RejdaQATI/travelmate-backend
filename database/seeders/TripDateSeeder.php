<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

class TripDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Récupérer tous les voyages pour leur associer des dates
        $trips = DB::table('trips')->get();

        foreach ($trips as $trip) {
            // Pour chaque voyage, ajouter plusieurs dates avec des prix différents
            DB::table('trip_dates')->insert([
                [
                    'trip_id' => $trip->id,
                    'start_date' => Carbon::now()->addDays(rand(5, 15)), // Date de début aléatoire
                    'end_date' => Carbon::now()->addDays(rand(20, 30)), // Date de fin aléatoire
                    'price' => rand(500, 3000), // Prix aléatoire entre 1000 et 5000
                    'max_participants' => rand(10, 20), // Nombre maximum de participants aléatoire
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'trip_id' => $trip->id,
                    'start_date' => Carbon::now()->addMonths(2)->addDays(rand(1, 10)), // Une autre date plus éloignée
                    'end_date' => Carbon::now()->addMonths(2)->addDays(rand(15, 25)),
                    'price' => rand(500, 3000), // Prix aléatoire entre 1000 et 5000
                    'max_participants' => rand(10, 20), // Nombre maximum de participants aléatoire
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'trip_id' => $trip->id,
                    'start_date' => Carbon::now()->addMonths(4)->addDays(rand(1, 10)), // Encore une autre période
                    'end_date' => Carbon::now()->addMonths(4)->addDays(rand(15, 25)),
                    'price' => rand(500, 3000),
                    'max_participants' => rand(10, 20),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'trip_id' => $trip->id,
                    'start_date' => Carbon::now()->addMonths(6)->addDays(rand(1, 10)), // Une nouvelle période encore plus éloignée
                    'end_date' => Carbon::now()->addMonths(6)->addDays(rand(15, 25)),
                    'price' => rand(500, 3000),
                    'max_participants' => rand(10, 20),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'trip_id' => $trip->id,
                    'start_date' => Carbon::now()->addMonths(8)->addDays(rand(1, 10)), // Une dernière période éloignée
                    'end_date' => Carbon::now()->addMonths(8)->addDays(rand(15, 25)),
                    'price' => rand(500, 3000),
                    'max_participants' => rand(10, 20),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }
    }
}
