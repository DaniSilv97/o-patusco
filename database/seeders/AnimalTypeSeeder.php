<?php

namespace Database\Seeders;

use App\Models\AnimalType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnimalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AnimalType::factory()->withName('Cão')->create();
        AnimalType::factory()->withName('Gato')->create();
        AnimalType::factory()->withName('Periquito')->create();
        AnimalType::factory()->withName('Furão')->create();
        AnimalType::factory()->withName('Outro...')->create();
    }
}
