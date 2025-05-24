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

    public string $name = '';
    public string $email = '';
    public ?int $unit_id = null;
    public string $role = '';
    public string $password = '';
    public string $password_confirmation = '';

    public bool $isEditing = false;
    public string $pageTitle = '';
    public array $availableRoles = [];
    public \Illuminate\Database\Eloquent\Collection $unitsList;

    protected function rules(): array
    {
        $userIdToIgnore = $this->isEditing && $this->user ? $this->user->id : null;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email' . ($userIdToIgnore ? ',' . $userIdToIgnore : ''),
            'unit_id' => 'required|integer|exists:units,id',
            'role' => 'required|string|in:' . implode(',', User::getAvailableRoles()),
        ];

        if (!$this->isEditing) { // Senha obrigatória apenas na criação
            $rules['password'] = ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'];
        } elseif (!empty($this->password)) { // Se a senha for fornecida na edição, valide-a
            $rules['password'] = ['sometimes', 'nullable', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'];
        }


        return $rules;
    }

    // Mensagens de validação personalizadas (opcional)
    protected $messages = [
        'password.confirmed' => 'A confirmação da senha não corresponde.',
        // Adicione outras mensagens personalizadas se desejar
    ];


    public function mount(?int $userId = null): void
    {
        $this->availableRoles = User::getAvailableRoles();
        $this->unitsList = Unit::orderBy('name')->get(['id', 'name']);

        if ($userId) {
            $this->user = User::findOrFail($userId);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->unit_id = $this->user->unit_id;
            $this->role = $this->user->role;
            $this->isEditing = true;
            $this->pageTitle = __('Editar Usuário');
        } else {
            $this->pageTitle = __('Novo Usuário');
            if ($this->unitsList->isNotEmpty() && !$this->unit_id) { // Pré-seleciona a primeira unidade se nenhuma estiver definida
                // $this->unit_id = $this->unitsList->first()->id;
            }
            if (count($this->availableRoles) > 0 && !$this->role) { // Pré-seleciona o primeiro papel
                // $this->role = $this->availableRoles[0];
            }
        }
    }

    public function save(): void
    {
        $validatedData = $this->validate();

        $userData = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'unit_id' => $validatedData['unit_id'],
            'role' => $validatedData['role'],
        ];

        // Lida com a senha apenas se ela foi fornecida e validada
        if (!empty($validatedData['password'])) {
            $userData['password'] = Hash::make($validatedData['password']);
        }


        if ($this->isEditing && $this->user) {
            $this->user->update($userData);
            session()->flash('status', __('Usuário atualizado com sucesso!'));
        } else {
            // Se estiver criando, a senha já foi validada como obrigatória
            // e $userData['password'] já estará com hash se a validação passou
            User::create($userData);
            session()->flash('status', __('Usuário criado com sucesso!'));
        }

        $this->dispatch('user-saved');
        $this->redirectRoute('users.index', navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('users.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.users.manage-user');
    }
}
