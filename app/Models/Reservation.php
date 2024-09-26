<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'trip_date_id', 
        'number_of_participants', 
        'status', 
        'payment_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tripDate()
    {
        return $this->belongsTo(TripDate::class);
    }
}
