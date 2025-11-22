<?php

namespace Database\Seeders;

use App\Models\ConsultationTimeframe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsultationTimeframeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConsultationTimeframe::factory()->withName('Manhã')->create();
        ConsultationTimeframe::factory()->withName('Tarde')->create();
        ConsultationTimeframe::factory()->withName(name: 'Indiferente')->create();
    }
}
