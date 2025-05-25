<?php

namespace App\Models;

use App\Enums\ProcedureType;
use App\Enums\TravelRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'citizen_id',
        'needs_companion',
        'companion_name',
        'companion_cpf',
        'destination_address',
        'destination_city',
        'destination_state',
        'reason',
        'procedure_type',
        'departure_location',
        'appointment_datetime',
        'desired_departure_datetime',
        'desired_return_datetime',
        'referral_document_path',
        'status',
        'requester_id',
        'approver_id',
        'approval_notes',
        'cancellation_reason',
        'cancellation_notes',
        'cancelled_at',
        'number_of_passengers',
        'observations',
        // vehicle_id e driver_id não estão aqui por enquanto
    ];

    protected $casts = [
        'needs_companion' => 'boolean',
        'appointment_datetime' => 'datetime',
        'desired_departure_datetime' => 'datetime',
        'desired_return_datetime' => 'datetime',
        'cancelled_at' => 'datetime',
        'status' => TravelRequestStatus::class,
        'procedure_type' => ProcedureType::class,
        'number_of_passengers' => 'integer',
    ];

    /**
     * O cidadão (paciente) principal desta solicitação de viagem.
     */
    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class);
    }

    /**
     * O usuário (atendente) que registrou esta solicitação.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * O usuário (gerente/admin) que aprovou ou rejeitou esta solicitação.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    // Se, no futuro, você adicionar vehicle_id e driver_id diretamente aqui:
    // public function vehicle(): BelongsTo
    // {
    //     return $this->belongsTo(Vehicle::class);
    // }

    // public function driver(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'driver_id');
    // }

    // Acessor para a URL da imagem da guia (se você usar o storage link)
    public function getReferralDocumentUrlAttribute(): ?string
    {
        if ($this->referral_document_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->referral_document_path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->referral_document_path);
        }
        return null;
    }
}
