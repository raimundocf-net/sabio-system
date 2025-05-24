<?php

namespace App\Models;

use App\Enums\VehicleType;
use App\Enums\VehicleAvailabilityStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Para usar Soft Deletes

class Vehicle extends Model
{
    use HasFactory, SoftDeletes; // Adicionar SoftDeletes se usou na migration

    protected $fillable = [
        'plate_number',
        'brand',
        'model',
        'year_of_manufacture',
        'model_year',
        'renavam',
        'chassis',
        'color',
        'type',
        'passenger_capacity',
        'availability_status',
        'acquisition_date',
        'current_mileage',
        'last_inspection_date',
        'is_pwd_accessible',
        'notes',
        // Não inclua unit_id aqui, pois removemos o relacionamento por agora
    ];

    protected $casts = [
        'year_of_manufacture' => 'integer', // Pode ser útil se for usar como número diretamente
        'model_year' => 'integer',
        'passenger_capacity' => 'integer',
        'current_mileage' => 'integer',
        'is_pwd_accessible' => 'boolean',
        'acquisition_date' => 'date:Y-m-d', // Formato de data para o banco
        'last_inspection_date' => 'date:Y-m-d',
        'type' => VehicleType::class, // Cast para o Enum
        'availability_status' => VehicleAvailabilityStatus::class, // Cast para o Enum
    ];

    /**
     * Regras de validação padrão para o modelo.
     * Útil para centralizar validações, especialmente se for usar fora do Livewire também.
     * No Livewire, geralmente definimos as rules no próprio componente.
     */
    public static function validationRules(?int $vehicleId = null): array
    {
        $plateRule = 'required|string|max:10|unique:vehicles,plate_number';
        $renavamRule = 'required|string|digits_between:9,11|unique:vehicles,renavam'; // RENAVAM pode ter 9 ou 11 dígitos
        $chassisRule = 'required|string|size:17|unique:vehicles,chassis';

        if ($vehicleId) {
            $plateRule .= ',' . $vehicleId;
            $renavamRule .= ',' . $vehicleId;
            $chassisRule .= ',' . $vehicleId;
        }

        return [
            'plate_number' => $plateRule,
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year_of_manufacture' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'model_year' => 'nullable|integer|digits:4|min:1900|max:' . (date('Y') + 2) . '|gte:year_of_manufacture',
            'renavam' => $renavamRule,
            'chassis' => $chassisRule,
            'color' => 'nullable|string|max:50',
            'type' => ['required', new \Illuminate\Validation\Rules\Enum(VehicleType::class)],
            'passenger_capacity' => 'required|integer|min:1|max:255', // 255 é o limite do smallInteger
            'availability_status' => ['required', new \Illuminate\Validation\Rules\Enum(VehicleAvailabilityStatus::class)],
            'acquisition_date' => 'nullable|date_format:Y-m-d|before_or_equal:today',
            'current_mileage' => 'nullable|integer|min:0',
            'last_inspection_date' => 'nullable|date_format:Y-m-d|before_or_equal:today',
            'is_pwd_accessible' => 'nullable|boolean',
            'notes' => 'nullable|string|max:5000',
        ];
    }

    // Se futuramente você re-adicionar o relacionamento com Unit:
    // public function unit()
    // {
    //     return $this->belongsTo(Unit::class);
    // }
}
