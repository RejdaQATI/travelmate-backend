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
        $trips = DB::table('trips')->get();

        foreach ($trips as $trip) {
            DB::table('trip_dates')->insert([
                [
                    'trip_id' => $trip->id,
                    'start_date' => Carbon::now()->addDays(rand(5, 15)), 
                    'end_date' => Carbon::now()->addDays(rand(20, 30)), 
                    'price' => rand(500, 3000), 
                    'max_participants' => rand(10, 20), 
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'trip_id' => $trip->id,
                    'start_date' => Carbon::now()->addMonths(2)->addDays(rand(1, 10)), 
                    'end_date' => Carbon::now()->addMonths(2)->addDays(rand(15, 25)),
                    'price' => rand(500, 3000), 
                    'max_participants' => rand(10, 20), 
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'trip_id' => $trip->id,
                    'start_date' => Carbon::now()->addMonths(4)->addDays(rand(1, 10)),
                    'end_date' => Carbon::now()->addMonths(4)->addDays(rand(15, 25)),
                    'price' => rand(500, 3000),
                    'max_participants' => rand(10, 20),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'trip_id' => $trip->id,
                    'start_date' => Carbon::now()->addMonths(6)->addDays(rand(1, 10)), 
                    'end_date' => Carbon::now()->addMonths(6)->addDays(rand(15, 25)),
                    'price' => rand(500, 3000),
                    'max_participants' => rand(10, 20),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'trip_id' => $trip->id,
                    'start_date' => Carbon::now()->addMonths(8)->addDays(rand(1, 10)),
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
