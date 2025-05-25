<?php

namespace App\Livewire\Companions;

use App\Models\Companion;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule; // Certifique-se que Rule está importado se for usar para unique avançado

#[Layout('components.layouts.app')]
class ManageCompanion extends Component
{
    public ?Companion $companionInstance = null;

    // Propriedades do formulário
    public string $full_name = '';
    public ?string $cpf = null;
    public ?string $identity_document = null;
    public ?string $contact_phone = null;
    public ?string $notes = null;

    public bool $isEditing = false;
    public string $pageTitle = '';

    protected function rules(): array
    {
        $cpfBaseRules = ['nullable', 'string', 'max:14', 'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/'];
        $cpfUniqueRule = Rule::unique('companions', 'cpf');

        if ($this->isEditing && $this->companionInstance) {
            $cpfUniqueRule->ignore($this->companionInstance->id);
        }

        // Adiciona a regra unique apenas se o CPF for fornecido
        // e não estivermos editando o mesmo CPF sem alterá-lo.
        // No entanto, a forma mais simples é sempre aplicar a regra unique (ignorando o atual na edição)
        // e a validação de 'nullable' cuidará do caso de o CPF não ser preenchido.
        $cpfValidationRules = array_merge($cpfBaseRules, [$cpfUniqueRule]);


        return [
            'full_name' => 'required|string|min:3|max:255',
            'cpf' => $cpfValidationRules, // <--- CORRIGIDO AQUI
            'identity_document' => 'nullable|string|max:30',
            'contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:5000',
        ];
    }

    protected array $messages = [
        'full_name.required' => 'O nome completo do acompanhante é obrigatório.',
        'full_name.min' => 'O nome completo deve ter pelo menos 3 caracteres.',
        'cpf.unique' => 'Este CPF já está cadastrado para outro acompanhante.',
        'cpf.regex' => 'O formato do CPF é inválido. Use XXX.XXX.XXX-XX.',
    ];

    public function mount(?int $companionId = null): void
    {
        if ($companionId) {
            $this->companionInstance = Companion::findOrFail($companionId);
            $this->full_name = $this->companionInstance->full_name;
            $this->cpf = $this->companionInstance->cpf;
            $this->identity_document = $this->companionInstance->identity_document;
            $this->contact_phone = $this->companionInstance->contact_phone;
            $this->notes = $this->companionInstance->notes;
            $this->isEditing = true;
            $this->pageTitle = __('Editar Acompanhante');
        } else {
            $this->pageTitle = __('Novo Acompanhante');
        }
    }

    public function save(): void
    {
        $validatedData = $this->validate();

        // Sanitizar o CPF para remover a máscara antes de salvar, se necessário,
        // mas como estamos salvando com máscara, não precisa aqui.
        // Apenas garanta que o banco de dados e o modelo esperem a máscara.

        if ($this->isEditing && $this->companionInstance) {
            $this->companionInstance->update($validatedData);
            session()->flash('status', __('Acompanhante atualizado com sucesso!'));
        } else {
            Companion::create($validatedData);
            session()->flash('status', __('Acompanhante cadastrado com sucesso!'));
        }

        $this->redirectRoute('companions.index', navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('companions.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.companions.manage-companion')
            ->layoutData(['pageTitle' => $this->pageTitle . ($this->isEditing && $this->companionInstance ? ' - ' . $this->companionInstance->full_name : '')]);
    }
}
