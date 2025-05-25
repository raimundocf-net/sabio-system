<?php

namespace App\Policies;

use App\Models\TravelRequest;
use App\Models\User;
use App\Enums\TravelRequestStatus; // Importar para usar no update/delete
use Illuminate\Auth\Access\HandlesAuthorization; // Adicionado se estiver faltando

class TravelRequestPolicy
{
    use HandlesAuthorization; // Adicionado se estiver faltando

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin (via Gate::before) já tem acesso.
        // Permitir que 'receptionist', 'manager', 'doctor', 'nurse', 'acs' vejam a lista.
        return $user->hasAnyRole(['receptionist', 'manager', 'doctor', 'nurse', 'acs']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TravelRequest $travelRequest): bool
    {
        if ($user->hasRole('manager')) {
            return true;
        }
        // Solicitante pode ver sua solicitação.
        if ($user->id === $travelRequest->requester_id) {
            return true;
        }
        // Se o usuário for o aprovador.
        if ($travelRequest->approver_id && $user->id === $travelRequest->approver_id) {
            return true;
        }
        // Regra de unidade (exemplo, precisaria adaptar à sua lógica de unidade se aplicável)
        // $requester = User::find($travelRequest->requester_id);
        // if ($requester && $user->unit_id && $user->unit_id === $requester->unit_id) {
        //     return true;
        // }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['receptionist', 'manager', 'acs']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TravelRequest $travelRequest): bool
    {
        if ($user->hasRole('manager')) {
            // Gerentes podem editar enquanto não estiver cancelada.
            return !in_array($travelRequest->status, [
                TravelRequestStatus::CANCELLED_BY_USER,
                TravelRequestStatus::CANCELLED_BY_ADMIN
            ]);
        }

        // Solicitante pode editar se estiver pendente de agendamento.
        if ($user->id === $travelRequest->requester_id) {
            return $travelRequest->status === TravelRequestStatus::PENDING_ASSIGNMENT;
        }
        return false;
    }

    /**
     * Determine whether the user can delete (cancel) the model.
     */
    public function delete(User $user, TravelRequest $travelRequest): bool
    {
        if ($user->hasRole('manager')) {
            // Gerentes podem cancelar se não estiver já cancelada.
            return !in_array($travelRequest->status, [
                TravelRequestStatus::CANCELLED_BY_USER,
                TravelRequestStatus::CANCELLED_BY_ADMIN
            ]);
        }
        // Solicitante pode cancelar se estiver pendente.
        if ($user->id === $travelRequest->requester_id && $travelRequest->status === TravelRequestStatus::PENDING_ASSIGNMENT) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TravelRequest $travelRequest): bool
    {
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TravelRequest $travelRequest): bool
    {
        // Geralmente, apenas admins (cobertos pelo Gate::before)
        return false;
    }
}
