<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'participant_id',
        'event_id',
        'transaction_id',
        'amount',
        'payment_method',
        'status',
        'description',
        'transaction_date',
        'currency',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }
}
