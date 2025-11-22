<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsultationTimeframe extends Model
{
    /** @use HasFactory<\Database\Factories\ConsultationTimeframeFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
    ];

    public function consultationRequests(): HasMany
    {
        return $this->hasMany(ConsultationRequest::class);
    }
}
