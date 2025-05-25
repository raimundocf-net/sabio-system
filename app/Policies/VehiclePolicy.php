<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Auth\Access\HandlesAuthorization;

class VehiclePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     * Admin já tem acesso via Gate::before.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('manager'); // Managers podem ver a lista
    }

    /**
     * Determine whether the user can view the model.
     * Admin já tem acesso.
     */
    public function view(User $user, Vehicle $vehicle): bool
    {
        // Managers podem ver detalhes de qualquer veículo
        if ($user->hasRole('manager')) {
            return true;
        }
        // Outras regras podem ser adicionadas aqui, se necessário.
        // Por exemplo, se um motorista pudesse ver apenas veículos a ele atribuídos (não é o caso agora).
        return false;
    }

    /**
     * Determine whether the user can create models.
     * Admin já tem acesso.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can update the model.
     * Admin já tem acesso.
     */
    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can delete the model.
     * Admin já tem acesso.
     */
    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can restore the model.
     * Admin já tem acesso.
     */
    public function restore(User $user, Vehicle $vehicle): bool
    {
        return $user->hasRole('manager'); // Ou apenas admin, dependendo da regra de negócio
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Admin já tem acesso.
     */
    public function forceDelete(User $user, Vehicle $vehicle): bool
    {
        return $user->hasRole('manager'); // Ou apenas admin
    }
}
