<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resort extends Model
{
    protected $fillable = [
        'name',
        'location',
        'region',
        'description',
        'price_per_night',
    ];
}
