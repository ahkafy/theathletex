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
        'additional_fields',
    ];

    protected $casts = [
        'additional_fields' => 'array',
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

    public function categories()
    {
        // Define the relationship with EventCategory model for each event
        return $this->hasMany(EventCategory::class);
    }

    public function participants()
    {
        // Define the relationship with Participant model for each event
        return $this->hasMany(Participant::class);
    }

    public function transactions()
    {
        // Define the relationship with Transaction model for each event
        return $this->hasMany(Transaction::class);
    }

    public function results()
    {
        // Define the relationship with EventResult model for each event
        return $this->hasMany(EventResult::class);
    }
}
