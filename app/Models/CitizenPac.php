<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CitizenPac extends Model
{
    use HasFactory;

    protected $table = 'citizen_pacs';

    protected $fillable = [
        'nome_do_cidadao',
        'data_de_nascimento',
        'idade',
        'sexo',
        'identidade_de_genero',
        'cpf',
        'cns',
        'telefone_celular',
        'telefone_residencial',
        'telefone_de_contato',
        'microarea',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'municipio',
        'uf',
        'cep',
        'ultimo_atendimento',
    ];

    protected $casts = [
        'data_de_nascimento' => 'date:Y-m-d',
        'ultimo_atendimento' => 'date:Y-m-d',
        'idade' => 'integer',
    ];

    // Helper para sanitizar CPF (se quiser manter no modelo)
    public static function sanitizeCpf(?string $cpf): ?string
    {
        return $cpf ? preg_replace('/\D/', '', $cpf) : null;
    }

    // Helper para sanitizar CNS (se quiser manter no modelo)
    public static function sanitizeCns(?string $cns): ?string
    {
        return $cns ? preg_replace('/\D/', '', $cns) : null;
    }

    // Helper para converter data (se quiser manter no modelo)
    public static function formatDateString(?string $dateString, string $fromFormat = 'd/m/Y', string $toFormat = 'Y-m-d'): ?string
    {
        if (!$dateString) {
            return null;
        }
        try {
            return Carbon::createFromFormat($fromFormat, trim($dateString))->format($toFormat);
        } catch (\Exception $e) {
            // Tentar um parse mais genérico
            try {
                return Carbon::parse(trim($dateString))->format($toFormat);
            } catch (\Exception $e2) {
                \Illuminate\Support\Facades\Log::warning("Falha ao formatar data '{$dateString}' de '{$fromFormat}' para '{$toFormat}'. Erro: " . $e2->getMessage());
                return null;
            }
        }
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'citizen_id');
    }


}
