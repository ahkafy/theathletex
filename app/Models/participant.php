<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'event_id',
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


    public function event()
    {
        return $this->belongsTo(Event::class);
    }


}
