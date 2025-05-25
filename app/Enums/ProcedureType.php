<?php

namespace App\Enums;

enum ProcedureType: string
{
    case CONSULTATION = 'consultation';
    case EXAM = 'exam';
    case RETURN_APPOINTMENT = 'return_appointment';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::CONSULTATION => __('Consulta'),
            self::EXAM => __('Exame'),
            self::RETURN_APPOINTMENT => __('Retorno'),
            self::OTHER => __('Outro'),
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
