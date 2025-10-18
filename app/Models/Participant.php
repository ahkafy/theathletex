<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'event_id',
        'participant_id',
        'category',
        'reg_type',
        'fee',
        'name',
        'email',
        'phone',
        'address',
        'district',
        'thana',
        'emergency_phone',
        'gender',
        'dob',
        'nationality',
        'tshirt_size',
        'kit_option',
        'terms_agreed',
        'payment_method',
    ];

    /**
     * Generate a unique participant ID with format: EventID + 8-digit serial
     */
    public static function generateParticipantId($eventId)
    {
        // Get the count of participants for this event
        $participantCount = self::where('event_id', $eventId)->count();

        // Generate 8-digit serial number (starting from 00000001)
        $serialNumber = str_pad($participantCount + 1, 8, '0', STR_PAD_LEFT);

        // Combine event ID with serial number
        $participantId = $eventId . $serialNumber;

        // Check if this ID already exists (unlikely but for safety)
        while (self::where('participant_id', $participantId)->exists()) {
            $participantCount++;
            $serialNumber = str_pad($participantCount + 1, 8, '0', STR_PAD_LEFT);
            $participantId = $eventId . $serialNumber;
        }

        return $participantId;
    }

    /**
     * Boot method to auto-generate participant_id when creating
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($participant) {
            if (empty($participant->participant_id) && !empty($participant->event_id)) {
                $participant->participant_id = self::generateParticipantId($participant->event_id);
            }
        });
    }


    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category', 'name');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


}
