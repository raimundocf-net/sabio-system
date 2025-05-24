<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PrescriptionStatus;
use Illuminate\Support\Facades\Storage;

// Importar o Enum

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_id',
        'user_id',              // Usuário que solicitou/registrou
        'unit_id',
        'doctor_id',            // Médico responsável (pode ser nulo)
        'status',
        'prescription_details', // O texto livre da ACS sobre o pedido
        'image_path',
        'processing_notes',     // Consolidado: Notas do médico, motivo de rejeição/cancelamento
        'reviewed_at',          // Data da análise médica
        'completed_at',         // Data de finalização (entregue, rejeitada, cancelada)
        // 'created_at' e 'updated_at' são gerenciados automaticamente e são fillable por padrão se não estiverem em $guarded
    ];

    protected $casts = [
        'status' => PrescriptionStatus::class,
        'prescription_details' => 'string',   // Garantir que seja tratado como string
        'reviewed_at' => 'datetime',
        'completed_at' => 'datetime',
        // created_at e updated_at são automaticamente tratados como datetime pelo Eloquent
    ];

    // Acessor opcional para obter a URL da imagem
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return Storage::url($this->image_path); // Assume que você está usando o disco 'public'
        }
        return null;
    }

    // Relacionamentos (permanecem os mesmos)
    public function citizen()
    {
        return $this->belongsTo(Citizen::class, 'citizen_id');
    }

    public function requester() // Usuário que solicitou
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function doctor() // Médico que analisou/emitiu
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
