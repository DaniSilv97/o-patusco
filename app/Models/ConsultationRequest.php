<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConsultationRequest extends Model
{
    /** @use HasFactory<\Database\Factories\ConsultationRequestFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'date',
        'client_note',
        'consultation_timeframe_id',
        'animal_id',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function timeframe(): BelongsTo
    {
        return $this->belongsTo(ConsultationTimeframe::class, 'consultation_timeframe_id');
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function consultation(): HasOne
    {
        return $this->hasOne(Consultation::class);
    }
}
