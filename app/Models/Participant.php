<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'event_id',
        'event_category_id',
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
        'dynamic_fields',
    ];

    protected $casts = [
        'dynamic_fields' => 'array',
        'dob' => 'date',
        'terms_agreed' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function eventCategory()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
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
