<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'description',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
