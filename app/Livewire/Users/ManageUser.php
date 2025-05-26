<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class ManageUser extends Component
{
    public ?User $user = null;

    // Campos do Usuário (conforme migration e model User)
    public string $name = '';
    public string $email = '';
    public ?int $unit_id = null;
    public string $role = '';
    public ?string $cns = null;
    public ?string $cbo = null;
    public string $password = '';
    public string $password_confirmation = '';

    public bool $isEditing = false;
    public string $pageTitle = '';
    public array $availableRoles = []; // Será preenchido com ['chave' => 'Label']
    public \Illuminate\Database\Eloquent\Collection $unitsList;


    protected function rules(): array
    {
        $userIdToIgnore = $this->isEditing && $this->user ? $this->user->id : null;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email' . ($userIdToIgnore ? ',' . $userIdToIgnore : ''),
            'unit_id' => 'required|integer|exists:units,id',
            'role' => 'required|string|in:' . implode(',', User::getRoleKeys()), // Usa as chaves das roles
            'cns' => 'nullable|string|max:19|unique:users,cns' . ($userIdToIgnore ? ',' . $userIdToIgnore : ''),
            'cbo' => 'nullable|string|max:255', // Ajuste o max se necessário
        ];

        if (!$this->isEditing) {
            $rules['password'] = ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'];
        } elseif (!empty($this->password)) {
            // 'sometimes' garante que só será validado se presente. 'nullable' permite que seja vazio.
            $rules['password'] = ['sometimes', 'nullable', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'];
        }

        return $rules;
    }

    protected $messages = [
        'password.confirmed' => 'A confirmação da senha não corresponde.',
        'name.required' => 'O nome do usuário é obrigatório.',
        'email.required' => 'O e-mail é obrigatório.',
        'email.email' => 'O e-mail fornecido não é válido.',
        'email.unique' => 'Este e-mail já está em uso.',
        'unit_id.required' => 'A unidade de saúde é obrigatória.',
        'role.required' => 'O papel (cargo) do usuário é obrigatório.',
        'role.in' => 'O papel (cargo) selecionado não é válido.',
        'cns.unique' => 'Este CNS já está cadastrado para outro usuário.',
        'cns.max' => 'O CNS não pode ter mais de 19 caracteres.', // Exemplo
        'cbo.max' => 'O CBO não pode ter mais de 255 caracteres.', // Exemplo
    ];


    public function mount(?int $userId = null): void
    {
        // Carrega as roles formatadas para o select (ex: 'admin' => 'Administrador')
        $this->availableRoles = User::getAvailableRoles(); //
        $this->unitsList = Unit::orderBy('name')->get(['id', 'name']); //

        if ($userId) {
            $this->user = User::findOrFail($userId);
            $this->name = $this->user->name; //
            $this->email = $this->user->email; //
            $this->unit_id = $this->user->unit_id; //
            $this->role = $this->user->role; //
            $this->cns = $this->user->cns; //
            $this->cbo = $this->user->cbo; // Adicionado carregamento do CBO

            $this->isEditing = true; //
            $this->pageTitle = __('Editar Usuário'); //
        } else {
            $this->pageTitle = __('Novo Usuário'); //
            // Pode definir valores padrão para criação se desejar
            // Ex: if ($this->unitsList->isNotEmpty() && is_null($this->unit_id)) {
            //          $this->unit_id = $this->unitsList->first()->id;
            //      }
            // Ex: if (!empty($this->availableRoles) && empty($this->role)) {
            //          $this->role = array_key_first($this->availableRoles); // Pega a primeira chave de role
            //      }
        }
    }

    public function save(): void
    {
        $validatedData = $this->validate();

        $userData = [
            'name' => $validatedData['name'], //
            'email' => $validatedData['email'], //
            'unit_id' => $validatedData['unit_id'], //
            'role' => $validatedData['role'], //
            'cns' => $validatedData['cns'] ?? null, //
            'cbo' => $validatedData['cbo'] ?? null, // Adicionado CBO ao save
        ];

        if (!empty($validatedData['password'])) {
            $userData['password'] = Hash::make($validatedData['password']); //
        }

        if ($this->isEditing && $this->user) {
            $this->user->update($userData); //
            session()->flash('status', __('Usuário atualizado com sucesso!')); //
        } else {
            User::create($userData); //
            session()->flash('status', __('Usuário criado com sucesso!')); //
        }

        $this->dispatch('user-saved'); //
        $this->redirectRoute('users.index', navigate: true); //
    }

    public function cancel(): void
    {
        $this->redirectRoute('users.index', navigate: true); //
    }

    public function render()
    {
        return view('livewire.users.manage-user'); //
    }
}
