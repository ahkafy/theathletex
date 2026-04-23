<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormTransaction extends Model
{
    protected $fillable = [
        'form_id',
        'form_response_id',
        'ssl_tran_id',
        'bank_tran_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'amount' => 'decimal:2',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function formResponse()
    {
        return $this->belongsTo(FormResponse::class);
    }
}
