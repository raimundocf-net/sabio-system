<?php

namespace App\Enums;

enum VehicleType: string
{
    case AMBULANCE_A = 'ambulance_a'; // Valor armazenado no BD
    case AMBULANCE_B = 'ambulance_b';
    case AMBULANCE_C = 'ambulance_c';
    case AMBULANCE_D = 'ambulance_d';
    case ADMINISTRATIVE_CAR = 'administrative_car';
    case SANITARY_TRANSPORT_SIMPLE = 'sanitary_transport_simple';
    case SANITARY_TRANSPORT_ADVANCED = 'sanitary_transport_advanced';
    case PASSENGER_VAN = 'passenger_van';
    case SMALL_BUS = 'small_bus';
    case PATROL_MOTORCYCLE = 'patrol_motorcycle';
    case OTHER = 'other';

    // Método para retornar o label em Português para exibição
    public function label(): string
    {
        return match ($this) {
            self::AMBULANCE_A => __('Ambulância Tipo A (Simples Remoção)'),
            self::AMBULANCE_B => __('Ambulância Tipo B (Suporte Básico)'),
            self::AMBULANCE_C => __('Ambulância Tipo C (UTI Móvel)'),
            self::AMBULANCE_D => __('Ambulância Tipo D (Neonatal)'),
            self::ADMINISTRATIVE_CAR => __('Carro Administrativo'),
            self::SANITARY_TRANSPORT_SIMPLE => __('Transporte Sanitário Simples'),
            self::SANITARY_TRANSPORT_ADVANCED => __('Transporte Sanitário Avançado'),
            self::PASSENGER_VAN => __('Van de Passageiros'),
            self::SMALL_BUS => __('Ônibus Pequeno (Micro-ônibus)'),
            self::PATROL_MOTORCYCLE => __('Motocicleta (Patrulhamento/Agilidade)'),
            self::OTHER => __('Outro Tipo'),
        };
    }

    // Método para obter todas as opções para selects, etc.
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label(); // O valor é em inglês, o label em pt-BR
        }
        return $options;
    }
}
