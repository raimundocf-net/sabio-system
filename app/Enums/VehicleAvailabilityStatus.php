<?php

namespace App\Enums;

enum VehicleAvailabilityStatus: string
{
    case AVAILABLE = 'available';
    case IN_USE = 'in_use';
    case PREVENTIVE_MAINTENANCE = 'preventive_maintenance';
    case CORRECTIVE_MAINTENANCE = 'corrective_maintenance';
    case TEMPORARILY_INACTIVE = 'temporarily_inactive';
    case DECOMMISSIONED = 'decommissioned';

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => __('Disponível'),
            self::IN_USE => __('Em Uso'),
            self::PREVENTIVE_MAINTENANCE => __('Manutenção Preventiva'),
            self::CORRECTIVE_MAINTENANCE => __('Manutenção Corretiva'),
            self::TEMPORARILY_INACTIVE => __('Inativo Temporariamente'),
            self::DECOMMISSIONED => __('Baixado (Fora de Operação)'),
        };
    }

    public function badgeClasses(): string
    {
        // As classes CSS permanecem as mesmas, pois são para estilo visual
        return match ($this) {
            self::AVAILABLE => 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200',
            self::IN_USE => 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-200',
            self::PREVENTIVE_MAINTENANCE, self::CORRECTIVE_MAINTENANCE => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-100',
            self::TEMPORARILY_INACTIVE => 'bg-orange-100 text-orange-800 dark:bg-orange-600 dark:text-orange-100',
            self::DECOMMISSIONED => 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200',
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
