<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    protected $fillable = [
        'form_id',
        'respondent_name',
        'respondent_email',
        'respondent_phone',
        'response_data',
        'payment_status',
    ];

    protected $casts = [
        'response_data' => 'array',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function transaction()
    {
        return $this->hasOne(FormTransaction::class);
    }
}
