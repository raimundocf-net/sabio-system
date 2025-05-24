<?php

namespace App\Models;

use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    /** @use HasFactory<UnitFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'municipality',
        'cnes',
        'unit_id',
        'role',
        'description',
    ];

    public function users():HasMany
    {
        return $this->hasMany(User::class);
    }

    // Veículos primariamente alocados a esta unidade
    public function veiculos(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'unidade_responsavel_id');
    }

// Solicitações de transporte originadas ou relacionadas a esta unidade
    public function solicitacoesDeTransporte(): HasMany
    {
        return $this->hasMany(SolicitacaoTransporte::class, 'unidade_saude_id');
    }
}
