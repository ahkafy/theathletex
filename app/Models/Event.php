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
        'status',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function fees()
    {
        // Define the relationship with EventFee model for each event
        return $this->hasMany(EventFee::class);
    }
}
