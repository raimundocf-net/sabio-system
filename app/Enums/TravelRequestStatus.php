<?php

namespace App\Enums;

enum TravelRequestStatus: string
{
    case PENDING_ASSIGNMENT = 'pending_assignment'; // Aguardando logística/montagem da viagem
    case SCHEDULED = 'scheduled';                 // Viagem montada e agendada (quando linkar com TripSchedule futuro)
    case REJECTED = 'rejected';                   // Solicitação rejeitada (ex: falta doc, inviável)
    case CANCELLED_BY_USER = 'cancelled_by_user';   // Cancelada pelo paciente/solicitante
    case CANCELLED_BY_ADMIN = 'cancelled_by_admin'; // Cancelada pela administração
    // Status como EM_VIAGEM, CONCLUIDA, etc., virão com o módulo de "Montagem de Viagem"

    public function label(): string
    {
        return match ($this) {
            self::PENDING_ASSIGNMENT => __('Pendente de Agendamento'),
            self::SCHEDULED => __('Agendada'),
            self::REJECTED => __('Rejeitada'),
            self::CANCELLED_BY_USER => __('Cancelada pelo Usuário'),
            self::CANCELLED_BY_ADMIN => __('Cancelada pela Administração'),
        };
    }

    public function badgeClasses(): string
    {
        // Cores para os badges na listagem
        return match ($this) {
            self::PENDING_ASSIGNMENT => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-300',
            self::SCHEDULED => 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-300',
            self::REJECTED => 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200',
            self::CANCELLED_BY_USER, self::CANCELLED_BY_ADMIN => 'bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200',
        };
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}
