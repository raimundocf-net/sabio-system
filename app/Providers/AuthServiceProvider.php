<?php

namespace App\Providers;

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
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies(); // Registra as policies definidas no array $policies

        /**
         * Concede todas as permissões para usuários com o perfil 'admin'.
         * Este callback é executado ANTES de qualquer outra verificação de Gate ou Policy.
         * Se retornar true, a permissão é concedida.
         * Se retornar false, a permissão é negada.
         * Se retornar null, a verificação prossegue para outros Gates ou Policies.
         */
        Gate::before(function (User $user, string $ability) {
            // Adapte '$user->hasRole('admin')' para a forma como você verifica perfis no seu sistema.
            // Por exemplo, poderia ser '$user->role === 'admin'' se 'role' for uma coluna.
            if ($user->hasRole('admin')) {
                return true; // Admin pode fazer tudo
            }
            return null; // Deixa outras verificações prosseguirem
        });

        /**
         * Define um Gate específico para a ação de importar cidadãos.
         * Apenas usuários com o perfil 'admin' terão essa permissão.
         */
        Gate::define('import-citizens', function (User $user) {
            // Adapte '$user->hasRole('admin')' conforme sua implementação.
            return $user->hasRole('admin');
        });

        /**
         * Você pode definir outros Gates para ações mais genéricas ou que não se encaixam
         * diretamente em uma Policy de modelo aqui.
         *
         * Exemplo de outro Gate:
         * Gate::define('view-admin-dashboard', function (User $user) {
         * return $user->hasRole('admin') || $user->hasRole('manager');
         * });
         */
    }
}
