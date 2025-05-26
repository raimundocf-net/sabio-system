<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PrescriptionStatus;
use Illuminate\Support\Facades\Storage;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_id',
        'user_id',
        'unit_id',
        'doctor_id',
        'status',
        'prescription_details',
        // 'image_path', // Removido ou substituído
        'image_paths', // Nova coluna para array de imagens
        'processing_notes',
        'reviewed_at',
        'completed_at',
    ];

    protected $casts = [
        'status' => PrescriptionStatus::class,
        'prescription_details' => 'string',
        'image_paths' => 'array', // Cast para array
        'reviewed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Acessor para obter URLs das imagens
    public function getImageUrlsAttribute(): array
    {
        $urls = [];
        if (!empty($this->image_paths)) {
            foreach ($this->image_paths as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    $urls[] = Storage::disk('public')->url($path);
                }
            }
        }
        return $urls;
    }

    // Relacionamentos (permanecem os mesmos)
    public function citizen()
    {
        return $this->belongsTo(CitizenPac::class, 'citizen_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
