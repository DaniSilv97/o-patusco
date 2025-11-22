<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnimalType extends Model
{
    /** @use HasFactory<\Database\Factories\AnimalTypeFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
    ];

    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }
}
