<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Adicionar se usou softDeletes na migration

class Companion extends Model
{
    use HasFactory;
    // use SoftDeletes; // Descomente se você adicionou softDeletes na migration

    protected $fillable = [
        'full_name',
        'cpf',
        'identity_document',
        'contact_phone',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Não há casts especiais necessários para os campos atuais,
        // mas você pode adicionar se, por exemplo, quisesse formatar datas específicas
    ];

    // Exemplo de um acessor para obter apenas os números do CPF, se necessário
    public function getCpfDigitsAttribute(): ?string
    {
        return $this->cpf ? preg_replace('/\D/', '', $this->cpf) : null;
    }

    // Se precisar de relacionamentos futuros, como a quais TravelRequests um acompanhante está vinculado
    // public function travelRequests()
    // {
    //     // Isso dependeria de como você armazenaria essa relação.
    //     // Por exemplo, se TravelRequest tivesse um companion_id:
    //     // return $this->hasMany(TravelRequest::class);
    //     // Ou através de uma tabela pivot se um acompanhante puder estar em várias viagens e uma viagem ter múltiplos acompanhantes.
    // }
}
