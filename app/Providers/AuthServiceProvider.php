<?php

namespace App\Providers;

use App\Models\Animal;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\User;
use App\Policies\AnimalPolicy;
use App\Policies\ConsultationPolicy;
use App\Policies\ConsultationRequestPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Animal::class => AnimalPolicy::class,
        Consultation::class => ConsultationPolicy::class,
        ConsultationRequest::class => ConsultationRequestPolicy::class,
    ];

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
        $this->registerPolicies();

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
