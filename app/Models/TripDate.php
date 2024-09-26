<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'start_date',
        'end_date',
        'price',
        'max_participants',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
    
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
