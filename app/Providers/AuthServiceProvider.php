<?php

namespace App\Providers;

use App\Models\TravelRequest;
use App\Models\Vehicle;
use App\Policies\TravelRequestPolicy;
use App\Policies\VehiclePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User; // Modelo de Usuário
use App\Models\Prescription; // Modelo de Receita
use App\Policies\PrescriptionPolicy; // Policy de Receita

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Prescription::class => PrescriptionPolicy::class,
        Vehicle::class => VehiclePolicy::class,
        TravelRequest::class => TravelRequestPolicy::class,
        // Adicione aqui a policy para Companion, se criar uma
        // \App\Models\Companion::class => \App\Policies\CompanionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
            return null;
        });

        Gate::define('import-citizens', function (User $user) {
            return $user->hasRole('admin');
        });

        /**
         * Gate para controlar a visibilidade do menu de Transporte e seus sub-itens.
         * Permitido para: manager, receptionist, nurse (admin já tem acesso via Gate::before)
         * Negado para: acs, doctor, nursing_technician (a menos que queira dar acesso a algum subitem específico depois)
         */
        Gate::define('view-transport-menu', function (User $user) {
            // Removido 'doctor' da lista de papéis permitidos
            return $user->hasAnyRole(['manager', 'receptionist', 'nurse']);
            // Se 'nursing_technician' também deve ver, adicione-o ao array.
            // 'admin' já está coberto pelo Gate::before.
            // 'acs' e 'doctor' estão explicitamente fora.
        });
    }
}
