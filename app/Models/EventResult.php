<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'participant_id',
        'position',
        'bib_number',
        'sx',
        'category',
        'category_position',
        'laps',
        'finish_time',
        'gap',
        'distance',
        'chip_time',
        'speed',
        'best_lap',
        'dnf',
        'dsq',
        'notes'
    ];

    protected $casts = [
        'dnf' => 'boolean',
        'dsq' => 'boolean',
        'distance' => 'decimal:2',
        'speed' => 'decimal:2',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    // Scopes
    public function scopeByPosition($query)
    {
        return $query->orderBy('position');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category)->orderBy('category_position');
    }

    public function scopeFinished($query)
    {
        return $query->where('dnf', false)->where('dsq', false);
    }

    // Accessors
    public function getFormattedTimeAttribute()
    {
        if (!$this->finish_time) return 'DNF';
        return $this->finish_time;
    }

    public function getStatusAttribute()
    {
        if ($this->dsq) return 'DSQ';
        if ($this->dnf) return 'DNF';
        return 'Finished';
    }
}
