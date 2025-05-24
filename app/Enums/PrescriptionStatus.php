<?php

namespace App\Enums;

enum PrescriptionStatus: string
{
    case REQUESTED = 'requested';
    case DRAFT_REQUEST = 'draft_request';
    case UNDER_DOCTOR_REVIEW = 'under_doctor_review';
    case REJECTED_BY_DOCTOR = 'rejected_by_doctor'; // <-- FOCO AQUI
    case APPROVED_FOR_ISSUANCE = 'approved_for_issuance';
    case READY_FOR_PICKUP = 'ready_for_pickup';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::REQUESTED => 'Solicitada',
            self::DRAFT_REQUEST => 'Rascunho',
            self::UNDER_DOCTOR_REVIEW => 'Em Análise Médica',
            self::REJECTED_BY_DOCTOR => 'Rejeitada (Aguardando Correção)', // Label pode ser mais informativo
            self::APPROVED_FOR_ISSUANCE => 'Aprovada',
            self::READY_FOR_PICKUP => 'Pronta para Retirada',
            self::DELIVERED => 'Entregue',
            self::CANCELLED => 'Cancelada',
            default => ucfirst(str_replace('_', ' ', strtolower($this->value))),
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::REQUESTED => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            self::DRAFT_REQUEST => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            self::UNDER_DOCTOR_REVIEW => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-300',
            self::REJECTED_BY_DOCTOR => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-300', // <-- ALTERADO PARA AMARELO
            self::APPROVED_FOR_ISSUANCE => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300',
            self::READY_FOR_PICKUP => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            self::DELIVERED => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            self::CANCELLED => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
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
