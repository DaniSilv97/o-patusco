<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->command->info('Seeding roles, animal types and states.');

        $this->call([
            RoleSeeder::class,
            AnimalTypeSeeder::class,
            ConsultationStateSeeder::class,
            ConsultationTimeframeSeeder::class,
        ]);
        
        $this->command->info('Creating test users...');

        $userCliente = User::factory()->create([
            'name' => 'Test Cliente',
            'email' => 'cliente@example.com',
            'password'=> Hash::make('password'),
        ]);
        $userRececionista = User::factory()->create([
            'name' => 'Test Rececionista',
            'email' => 'rececionista@example.com',
            'password'=> Hash::make('password'),
            
        ]);
        $userVeterinario = User::factory()->create([
            'name' => 'Test Veterinário',
            'email' => 'veterinario@example.com',
            'password'=> Hash::make('password'),
            
        ]);

        $this->command->info('Assigning roles...');
        
        $cliente = Role::where('name','Cliente')->first();
        $rececionista = Role::where( 'name', 'Rececionista')->first();
        $veterinario = Role::where('name','Veterinário')->first();

        UserRole::factory()
            ->forUser($userCliente)
            ->forRole($cliente)
            ->create();
        UserRole::factory()
            ->forUser($userRececionista)
            ->forRole($rececionista)
            ->create();
        UserRole::factory()
            ->forUser($userVeterinario)
            ->forRole($veterinario)
            ->create();

        $this->command->info('Creating and assignig animal...');

        Animal::factory()->forOwner($userCliente)->withName('Patusco')->create();
    }
}
