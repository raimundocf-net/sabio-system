<?php

namespace App\Policies;

use App\Models\TravelRequest;
use App\Models\User;
// use App\Enums\TravelRequestStatus; // Não mais necessário para lógica complexa aqui
use Illuminate\Auth\Access\HandlesAuthorization;

class TravelRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     * Se você tiver um método 'before' que restringe acesso, ele pode precisar ser ajustado ou removido.
     * Para permitir tudo, você pode fazer o 'before' retornar true, ou remover/comentar a lógica restritiva.
     * Se o seu 'before' só concede acesso para 'admin', talvez não precise mudar se quiser que admin continue com superpoderes.
     * Por ora, vamos focar em fazer todos os métodos da policy retornarem true.
     */
    // public function before(User $user, string $ability): ?bool
    // {
    //     // if ($user->hasRole('admin')) {
    //     //     return true;
    //     // }
    //     // return null;
    //     return true; // Permitir tudo no before, ou comentar o método todo
    // }

    public function viewAny(User $user): bool
    {
        return true; // Permitir
    }

    public function view(User $user, TravelRequest $travelRequest): bool
    {
        return true; // Permitir
    }

    public function create(User $user): bool
    {
        return true; // Permitir
    }

    public function update(User $user, TravelRequest $travelRequest): bool
    {
        return true; // Permitir
    }

    public function delete(User $user, TravelRequest $travelRequest): bool
    {
        return true; // Permitir
    }

    public function restore(User $user, TravelRequest $travelRequest): bool
    {
        return true; // Permitir
    }

    public function forceDelete(User $user, TravelRequest $travelRequest): bool
    {
        return true; // Permitir
    }
}
