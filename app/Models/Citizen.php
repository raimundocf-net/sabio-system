<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    use HasFactory;

    // Corresponde à sua nova migration
    protected $fillable = [
        'name',
        'date_of_birth', // string na migration
        'cns',
        'name_mother',
        'cpf',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // Se 'date_of_birth' precisar ser um objeto Carbon,
        // e for armazenado como Y-m-d, você pode adicionar:
        // 'date_of_birth' => 'date:Y-m-d',
        // Mas como a migration tem string, vamos tratar na importação se necessário.
        'timestamps' => 'datetime', // O padrão já faz isso
    ];

    // Se precisar de alguma função helper, pode adicionar aqui.
    // Por exemplo, para limpar CPF/CNS se não vierem limpos do JSON:
    public static function sanitizeNumeric(?string $value): ?string
    {
        if (empty($value)) return null;
        return preg_replace('/\D/', '', $value);
    }

    // ... dentro da classe Citizen ...
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'citizen_id');
    }
}
