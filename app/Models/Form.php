<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Form extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_photo',
        'payment_required',
        'payment_amount',
        'payment_currency',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'payment_required' => 'boolean',
        'payment_amount' => 'decimal:2',
    ];

    public function fields()
    {
        return $this->hasMany(FormField::class)->orderBy('sort_order');
    }

    public function responses()
    {
        return $this->hasMany(FormResponse::class);
    }
}
