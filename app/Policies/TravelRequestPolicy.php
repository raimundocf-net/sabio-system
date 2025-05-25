<?php

namespace App\Policies;

use App\Enums\TravelRequestStatus;
use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TravelRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     * Admin já tem acesso via Gate::before.
     */
    public function viewAny(User $user): bool
    {
        // Atendentes, Motoristas (se aplicável no futuro), Gerentes, Médicos, Enfermeiros podem ver a lista.
        // A lógica de quais solicitações eles veem será filtrada nos componentes.
        return $user->hasRole('receptionist') ||
            $user->hasRole('manager') ||
            $user->hasRole('doctor') ||    // Médicos podem precisar ver para aprovar/analisar
            $user->hasRole('nurse') ||     // Enfermeiros podem estar envolvidos
            $user->hasRole('acs');         // ACS podem ter solicitado
    }

    /**
     * Determine whether the user can view the model.
     * Admin já tem acesso.
     */
    public function view(User $user, TravelRequest $travelRequest): bool
    {
        if ($user->hasRole('manager')) {
            return true; // Gerentes podem ver qualquer solicitação.
        }

        // O solicitante (requester_id, geralmente a atendente ou ACS) pode ver sua própria solicitação.
        if ($user->id === $travelRequest->requester_id) {
            return true;
        }

        // Se a solicitação pertence à unidade do usuário (útil para médicos, enfermeiros, outras atendentes da mesma unidade).
        // Isso requer que TravelRequest tenha um 'unit_id' ou que possamos derivar a unidade através do 'requester_id'.
        // Vamos assumir que a unidade da solicitação é a unidade do requisitante.
        $requester = User::find($travelRequest->requester_id);
        if ($requester && $user->unit_id && $user->unit_id === $requester->unit_id) {
            return true;
        }

        // Se o usuário for o aprovador da solicitação.
        if ($travelRequest->approver_id && $user->id === $travelRequest->approver_id) {
            return true;
        }

        // Futuramente, se houver um motorista atribuído:
        // if ($travelRequest->driver_id && $user->id === $travelRequest->driver_id && $user->hasRole('driver')) {
        //     return true;
        // }

        return false;
    }

    /**
     * Determine whether the user can create models.
     * Admin já tem acesso.
     */
    public function create(User $user): bool
    {
        // Atendentes (receptionist) e ACS são os principais a criar solicitações.
        // Gerentes também podem precisar criar.
        return $user->hasRole('receptionist') ||
            $user->hasRole('acs') ||
            $user->hasRole('manager');
    }

    /**
     * Determine whether the user can update the model.
     * Admin já tem acesso.
     */
    public function update(User $user, TravelRequest $travelRequest): bool
    {
        if ($user->hasRole('manager')) {
            // Gerentes podem editar a maioria dos status, exceto os finais.
            return !in_array($travelRequest->status, [
                TravelRequestStatus::COMPLETED, // Supondo que COMPLETED será um status futuro
                TravelRequestStatus::CANCELLED_BY_USER,
                TravelRequestStatus::CANCELLED_BY_ADMIN
            ]);
        }

        // Atendente/ACS que criou pode editar enquanto estiver pendente de agendamento ou se foi rejeitada para agendamento.
        if (($user->hasRole('receptionist') || $user->hasRole('acs')) && $user->id === $travelRequest->requester_id) {
            return in_array($travelRequest->status, [
                TravelRequestStatus::PENDING_ASSIGNMENT,
                TravelRequestStatus::REJECTED // Se houver um status de rejeição que permita reedição
            ]);
        }

        // Outras lógicas (ex: quem pode aprovar/rejeitar, que seria um tipo de "update" no status)
        // serão tratadas por Gates mais específicos ou métodos de policy específicos se necessário.
        return false;
    }

    /**
     * Determine whether the user can delete the model (soft delete).
     * Admin já tem acesso.
     */
    public function delete(User $user, TravelRequest $travelRequest): bool
    {
        // Permitir que Gerentes cancelem (que pode ser um soft delete ou mudança de status)
        // ou o solicitante se ainda estiver em um status inicial.
        if ($user->hasRole('manager')) {
            return true; // Gerentes podem excluir/cancelar a maioria
        }

        if (($user->hasRole('receptionist') || $user->hasRole('acs')) && $user->id === $travelRequest->requester_id) {
            return $travelRequest->status === TravelRequestStatus::PENDING_ASSIGNMENT;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     * Apenas Admin e talvez Manager.
     */
    public function restore(User $user, TravelRequest $travelRequest): bool
    {
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Geralmente apenas Admin.
     */
    public function forceDelete(User $user, TravelRequest $travelRequest): bool
    {
        return false; // Evitar exclusão permanente por padrão por outros além de Admin (que já tem via Gate::before)
    }

    // Você pode adicionar métodos mais específicos para ações como:
    // public function approve(User $user, TravelRequest $travelRequest)
    // public function reject(User $user, TravelRequest $travelRequest)
    // public function assignVehicle(User $user, TravelRequest $travelRequest)
    // public function cancel(User $user, TravelRequest $travelRequest)
}
