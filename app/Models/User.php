<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'unit_id', //
        'name', //
        'microarea', //
        'email', //
        'password', //
        'role', //
        'cns', //
        'cbo', //
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Get the unit that the user belongs to.
     */
    public function unit():BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get all available roles.
     * Poderia estar em um Enum no PHP 8.1+ ou em um arquivo de config.
     */
    public static function getAvailableRoles(): array
    {
        return [
            'admin' => 'Administrador',
            'manager' => 'Gerente',
            'doctor' => 'Médico(a)',
            'nurse' => 'Enfermeiro(a)',
            'nursing_technician' => 'Técnico(a) de Enfermagem',
            'receptionist' => 'Recepcionista',
            'acs' => 'Agente Comunitário de Saúde (ACS)',
        ];
    }

    public static function getRoleKeys(): array
    {
        return array_keys(self::getAvailableRoles());
    }

    /**
     * Helper para verificar se o usuário tem um papel específico.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Verifica se o usuário possui qualquer um dos papéis fornecidos.
     *
     * @param array<string> $roles
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    // ... dentro da classe User ...
    public function requestedPrescriptions() // Receitas que este usuário solicitou
    {
        return $this->hasMany(Prescription::class, 'user_id');
    }

    public function reviewedPrescriptions() // Receitas que este usuário (se médico) analisou/emitiu
    {
        return $this->hasMany(Prescription::class, 'doctor_id');
    }
}
