<?php

namespace Database\Seeders;

use App\Models\ConsultationState;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsultationStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConsultationState::factory()->withName('Atribuída')->create();
        ConsultationState::factory()->withName('Completa')->create();
        ConsultationState::factory()->withName('Cancelada')->create();
    }
}