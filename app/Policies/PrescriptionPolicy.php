<?php

namespace App\Policies;

use App\Models\Prescription;
use App\Models\User;
use App\Enums\PrescriptionStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrescriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin já tem acesso via Gate::before.
        // Todos os perfis listados podem, em princípio, acessar a página de listagem.
        // A query no componente Livewire filtrará os dados específicos que cada um pode ver.
        return $user->hasRole('manager') ||
            $user->hasRole('doctor') ||
            $user->hasRole('nurse') || // Nurse já estava aqui
            $user->hasRole('acs') ||
            $user->hasRole('receptionist') ||
            $user->hasRole('nursing_technician');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Prescription $prescription): bool
    {
        // Admin já tem acesso.
        if ($user->hasRole('manager')) {
            return true;
        }
        if ($user->hasRole('acs')) {
            return $user->id === $prescription->user_id;
        }

        // Se o usuário (doctor, nurse, receptionist, etc.) for da mesma unidade da prescrição, ele pode ver.
        if ($user->unit_id && $user->unit_id === $prescription->unit_id) {
            return true;
        }

        // Adicionalmente, se o usuário for um médico OU enfermeiro e for o profissional atribuído à receita, ele pode ver.
        if (($user->hasRole('doctor') || $user->hasRole('nurse')) && $user->id === $prescription->doctor_id) {
            // Nota: Se 'nurse' também puder ser o 'doctor_id' (médico responsável), esta lógica está OK.
            // Se 'doctor_id' é estritamente para médicos, então a lógica para 'nurse' ver uma receita específica
            // dependeria apenas da regra de unidade acima, a menos que haja outro campo como 'assigned_nurse_id'.
            // Vou assumir por enquanto que um 'nurse' com as mesmas permissões de 'doctor' poderia, em teoria,
            // ser o 'doctor_id' ou que a regra de unidade é o principal para eles se não forem o 'doctor_id'.
            // Se 'nurse' NUNCA puder ser o 'doctor_id', então a condição para 'nurse' aqui seria redundante
            // e coberta pelo 'unit_id' check.
            // Para dar a 'nurse' o mesmo acesso que 'doctor' para ver uma prescrição específica se estiverem
            // no campo doctor_id (mesmo que semanticamente incorreto para o nome do campo):
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Perfis que podem criar solicitações de receita.
        // Nurse já estava aqui.
        return $user->hasRole('acs') ||
            $user->hasRole('receptionist') ||
            $user->hasRole('nurse') ||
            $user->hasRole('doctor') ||
            $user->hasRole('manager');
    }

    /**
     * Determine whether the user can update the model.
     * (Permissão genérica para editar a prescrição)
     */
    public function update(User $user, Prescription $prescription): bool
    {
        if ($user->hasRole('manager')) {
            return !in_array($prescription->status, [PrescriptionStatus::DELIVERED, PrescriptionStatus::CANCELLED]);
        }

        // Médico OU Enfermeiro podem editar (ex: adicionar notas, mudar status)
        // em receitas da sua unidade ou atribuídas a eles (se 'doctor_id' puder ser um enfermeiro).
        if ($user->hasRole('doctor') || $user->hasRole('nurse')) {
            // Verifica se está relacionado à unidade OU se é o profissional listado em doctor_id
            if (($user->unit_id && $user->unit_id === $prescription->unit_id) || ($user->id === $prescription->doctor_id)) {
                return !in_array($prescription->status, [PrescriptionStatus::DELIVERED, PrescriptionStatus::CANCELLED]);
            }
        }

        if ($user->hasRole('acs') && $user->id === $prescription->user_id) {
            return in_array($prescription->status, [
                PrescriptionStatus::DRAFT_REQUEST,
                PrescriptionStatus::REQUESTED,
                PrescriptionStatus::REJECTED_BY_DOCTOR,
            ]);
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Prescription $prescription): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Prescription $prescription): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Prescription $prescription): bool
    {
        return $user->hasRole('admin');
    }

    // --- MÉTODOS DE POLICY CUSTOMIZADOS PARA AÇÕES ESPECÍFICAS ---

    public function addProcessingNote(User $user, Prescription $prescription): bool
    {
        // Médicos, Enfermeiros, Gestores, e talvez Recepção/Técnicos podem adicionar notas
        if ($user->hasRole('doctor') ||
            $user->hasRole('nurse') || // Enfermeiro adicionado aqui
            $user->hasRole('manager') ||
            $user->hasRole('receptionist') ||
            $user->hasRole('nursing_technician')) {

            if (!$user->hasRole('manager')) { // Managers podem ter acesso mais amplo
                // Para os demais, verifica se são da unidade ou o profissional em doctor_id
                if (!($user->unit_id && $user->unit_id === $prescription->unit_id)) {
                    // Se não for da unidade, verifica se é o doctor_id (que agora pode incluir enfermeiro pela lógica de 'update')
                    if (!($user->id === $prescription->doctor_id && ($user->hasRole('doctor') || $user->hasRole('nurse')))) {
                        return false;
                    }
                }
            }
            return !in_array($prescription->status, [PrescriptionStatus::DELIVERED, PrescriptionStatus::CANCELLED]);
        }
        return false;
    }

    public function cancel(User $user, Prescription $prescription): bool
    {
        $currentStatus = $prescription->status;
        if (in_array($currentStatus, [PrescriptionStatus::DELIVERED, PrescriptionStatus::CANCELLED])) {
            return false;
        }

        if ($user->hasRole('acs') && $user->id === $prescription->user_id) {
            return $currentStatus === PrescriptionStatus::REQUESTED;
        }

        // Médicos, Enfermeiros e Gestores podem cancelar
        if ($user->hasRole('doctor') || $user->hasRole('nurse') || $user->hasRole('manager')) {
            // Para Médicos/Enfermeiros, pode ser necessário restringir à sua unidade ou se forem o 'doctor_id'
            if ($user->hasRole('doctor') || $user->hasRole('nurse')) {
                $isRelatedToPrescription = ($user->unit_id && $user->unit_id === $prescription->unit_id) || ($user->id === $prescription->doctor_id);
                if (!$isRelatedToPrescription) {
                    return false;
                }
            }
            // Se manager, ou médico/enfermeiro relacionado, e o status permite, então pode cancelar.
            return true;
        }

        return false;
    }

    public function changeStatus(User $user, Prescription $prescription, PrescriptionStatus $newStatus): bool
    {
        $currentStatus = $prescription->status;

        if (in_array($currentStatus, [PrescriptionStatus::DELIVERED, PrescriptionStatus::CANCELLED]) && $currentStatus !== $newStatus) {
            return false;
        }

        // Lógica para MÉDICOS e ENFERMEIROS (mesmas permissões de mudança de status)
        if ($user->hasRole('doctor') || $user->hasRole('nurse')) {
            // Devem estar associados à unidade da prescrição ou ser o profissional em doctor_id
            $isRelatedToPrescription = ($user->unit_id && $user->unit_id === $prescription->unit_id) || ($user->id === $prescription->doctor_id);
            if (!$isRelatedToPrescription) {
                return false;
            }

            switch ($currentStatus) {
                case PrescriptionStatus::REQUESTED:
                case PrescriptionStatus::UNDER_DOCTOR_REVIEW:
                case PrescriptionStatus::REJECTED_BY_DOCTOR: // Podem reavaliar uma rejeitada
                    return in_array($newStatus, [
                        PrescriptionStatus::UNDER_DOCTOR_REVIEW,
                        PrescriptionStatus::APPROVED_FOR_ISSUANCE,
                        PrescriptionStatus::REJECTED_BY_DOCTOR,
                    ]);
                // Se Médicos/Enfermeiros também podem marcar como 'Pronta para Retirada'
                // (atualmente delegado para outros perfis, mas pode ser adicionado aqui)
                // case PrescriptionStatus::APPROVED_FOR_ISSUANCE:
                //     return $newStatus === PrescriptionStatus::READY_FOR_PICKUP;
            }
        }

        // Lógica para RECEPÇÃO / TÉCNICOS DE ENFERMAGEM
        // Mantida a mesma, pois não foi pedido para dar a eles as mesmas permissões de médico/enfermeiro
        if ($user->hasRole('receptionist') || $user->hasRole('nursing_technician')) {
            if (!($user->unit_id && $user->unit_id === $prescription->unit_id)) {
                return false; // Apenas da sua unidade
            }
            switch ($currentStatus) {
                case PrescriptionStatus::APPROVED_FOR_ISSUANCE:
                    return $newStatus === PrescriptionStatus::READY_FOR_PICKUP;
                case PrescriptionStatus::READY_FOR_PICKUP:
                    return $newStatus === PrescriptionStatus::DELIVERED;
            }
        }

        // Lógica para ACS
        if ($user->hasRole('acs')) {
            if ($currentStatus === PrescriptionStatus::REJECTED_BY_DOCTOR &&
                $newStatus === PrescriptionStatus::UNDER_DOCTOR_REVIEW &&
                $user->id === $prescription->user_id) { // Só pode fazer isso com as suas próprias
                return true;
            }
            return false; // ACS não pode realizar outras mudanças de status
        }

        // Lógica para MANAGERS
        if ($user->hasRole('manager')) {
            // Managers podem ter mais flexibilidade, mas não em status finais.
            // Esta regra é ampla; refine se necessário para transições específicas que um manager pode fazer.
            return !in_array($currentStatus, [PrescriptionStatus::DELIVERED, PrescriptionStatus::CANCELLED]);
        }

        return false; // Nega por padrão
    }
}
