<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $tokyoId = DB::table('cities')->insertGetId([
            'name' => 'Tokyo',
            'description' => 'La capitale animée du Japon, connue pour ses gratte-ciels, son shopping et sa cuisine.',
            'image' => 'storage/images/cities/tokyo.jpg'
        ]);

        $newYorkId = DB::table('cities')->insertGetId([
            'name' => 'New York',
            'description' => 'La ville qui ne dort jamais, célèbre pour Times Square, Central Park et la Statue de la Liberté.',
            'image' => 'storage/images/cities/newyork.jpeg'
        ]);

        $dubaiId = DB::table('cities')->insertGetId([
            'name' => 'Dubai',
            'description' => 'La ville du futur, connue pour ses gratte-ciels ultra modernes et son luxe.',
            'image' => 'storage/images/cities/dubai.webp'
        ]);

        $londonId = DB::table('cities')->insertGetId([
            'name' => 'London',
            'description' => 'La capitale historique de l\'Angleterre, célèbre pour Big Ben, le London Eye et Buckingham Palace.',
            'image' => 'storage/images/cities/london.jpg'
        ]);

        $delhiId = DB::table('cities')->insertGetId([
            'name' => 'Delhi',
            'description' => 'La capitale de l\'Inde, mélange de modernité et d\'histoire avec ses monuments anciens et sa culture vibrante.',
            'image' => 'storage/images/cities/delhi.jpg'
        ]);

        $losAngelesId = DB::table('cities')->insertGetId([
            'name' => 'Los Angeles',
            'description' => 'La capitale du divertissement, célèbre pour Hollywood, Venice Beach et ses paysages ensoleillés.',
            'image' => 'storage/images/cities/losangeles.jpg'
        ]);

        $sydneyId = DB::table('cities')->insertGetId([
            'name' => 'Sydney',
            'description' => 'La ville emblématique d\'Australie, connue pour son Opéra, ses plages et son port spectaculaire.',
            'image' => 'storage/images/cities/sydney.jpg'
        ]);

    }
}
