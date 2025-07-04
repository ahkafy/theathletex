<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'start_time',
        'end_time',
        'capacity',
        'cover_photo',
        'venue',
        'is_open',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
