<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'image',
        'destination', 
        'duration',
        'city_id',
        'activities',
        'included'
        ];

    public function tripDates()
    {
        return $this->hasMany(TripDate::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class)->nullable();
    }

}
