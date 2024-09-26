<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    /**
     * Relation avec le modèle Trip.
     * Une ville peut avoir un voyage.
     */
    public function trip()
    {
        return $this->hasOne(Trip::class);
    }
}
