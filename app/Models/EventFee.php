<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventFee extends Model
{
    protected $fillable = [
        'event_id',
        'fee_type', // e.g., 'registration', 'ticket', etc.
        'fee_amount',
        'is_active', // Indicates if the fee is currently active
    ];

    public function event()
    {
        // Define the relationship with the Event model
        return $this->belongsTo(Event::class);
    }

    public function getStatusAttribute($value)
    {
        // Return the status as a human-readable string
        return ucfirst($value);
    }
}
