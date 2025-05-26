<?php

namespace App\Models;

use App\Enums\ProcedureType;

use App\Enums\TravelRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(CitizenPac::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function getReferralDocumentUrlAttribute(): ?string
    {
        if ($this->referral_document_path && Storage::disk('public')->exists($this->referral_document_path)) {
            return Storage::disk('public')->url($this->referral_document_path);
        }
        return null;
    }
}
