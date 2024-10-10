<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

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
            'image' => 'https://res.cloudinary.com/dl83ujoxi/image/upload/v1728565742/trips/27/iybev1d0rmpebxcii8nr.jpg'
        ]);

        $newYorkId = DB::table('cities')->insertGetId([
            'name' => 'New York',
            'description' => 'La ville qui ne dort jamais, célèbre pour Times Square, Central Park et la Statue de la Liberté.',
            'image' => 'https://res.cloudinary.com/dl83ujoxi/image/upload/v1728565984/trips/30/kupl2ggxqhmprzzesrzy.jpg'
        ]);

        $dubaiId = DB::table('cities')->insertGetId([
            'name' => 'Dubai',
            'description' => 'La ville du futur, connue pour ses gratte-ciels ultra modernes et son luxe.',
            'image' => 'https://res.cloudinary.com/dl83ujoxi/image/upload/v1728566018/trips/31/xempq0lvosamybcmwr6y.webp'
        ]);

        $londonId = DB::table('cities')->insertGetId([
            'name' => 'London',
            'description' => 'La capitale historique de l\'Angleterre, célèbre pour Big Ben, le London Eye et Buckingham Palace.',
            'image' => 'https://res.cloudinary.com/dl83ujoxi/image/upload/v1728565519/trips/22/kpzxneth1ue3zydk06g3.jpg'
        ]);

        $delhiId = DB::table('cities')->insertGetId([
            'name' => 'Delhi',
            'description' => 'La capitale de l\'Inde, mélange de modernité et d\'histoire avec ses monuments anciens et sa culture vibrante.',
            'image' => 'https://res.cloudinary.com/dl83ujoxi/image/upload/v1728565459/trips/21/uf3zblrgj6vgjburajgm.jpg'
        ]);

        $losAngelesId = DB::table('cities')->insertGetId([
            'name' => 'Los Angeles',
            'description' => 'La capitale du divertissement, célèbre pour Hollywood, Venice Beach et ses paysages ensoleillés.',
            'image' => 'https://res.cloudinary.com/dl83ujoxi/image/upload/v1728565254/trips/20/xdxesogyjcfy0eaxvhe1.jpg'
        ]);

        $sydneyId = DB::table('cities')->insertGetId([
            'name' => 'Sydney',
            'description' => 'La ville emblématique d\'Australie, connue pour son Opéra, ses plages et son port spectaculaire.',
            'image' => 'https://res.cloudinary.com/dl83ujoxi/image/upload/v1728566052/trips/32/sfrxataaqwo3cmn8hbt3.jpg'
        ]);

    }
}
