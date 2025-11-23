<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('is-client', function (User $user) {
            return $user->hasRole('Cliente');
        });

        Gate::define('is-receptionist', function (User $user) {
            return $user->hasRole('Rececionista');
        });

        Gate::define('is-veterinarian', function (User $user) {
            return $user->hasRole('Veterinário');
        });
    }
}
