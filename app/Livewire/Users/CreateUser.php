<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class CreateUser extends Component
{
    // Campos do Usuário
    public string $name = '';
    public string $email = '';
    public ?int $unit_id = null;
    public string $role = '';
    public ?string $cns = null;
    public ?string $cbo = null;
    public string $password = '';
    public string $password_confirmation = '';

    public string $pageTitle = '';
    public array $availableRoles = [];
    public \Illuminate\Database\Eloquent\Collection $unitsList;

    protected function rules(): array
    {
        // Para criação, as regras de 'unique' não precisam ignorar um ID
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'unit_id' => 'required|integer|exists:units,id',
            'role' => 'required|string|in:' . implode(',', User::getRoleKeys()),
            'cns' => 'nullable|string|max:19|unique:users,cns',
            'cbo' => 'nullable|string|max:255',
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
        ];
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
        'cns.max' => 'O CNS não pode ter mais de 19 caracteres.',
        'cbo.max' => 'O CBO não pode ter mais de 255 caracteres.',
    ];

    public function mount(): void
    {
        $this->pageTitle = __('Novo Usuário');
        $this->availableRoles = User::getAvailableRoles();
        $this->unitsList = Unit::orderBy('name')->get(['id', 'name']);

        // Opcional: Pré-selecionar a primeira unidade ou role, se desejar
        // if ($this->unitsList->isNotEmpty() && is_null($this->unit_id)) {
        //     $this->unit_id = $this->unitsList->first()->id;
        // }
        // if (!empty($this->availableRoles) && empty($this->role)) {
        //     $this->role = array_key_first($this->availableRoles);
        // }
    }

    public function storeUser(): void // Renomeado de save para storeUser para clareza
    {
        $validatedData = $this->validate();

        $userData = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'unit_id' => $validatedData['unit_id'],
            'role' => $validatedData['role'],
            'cns' => $validatedData['cns'] ?? null,
            'cbo' => $validatedData['cbo'] ?? null,
            'password' => Hash::make($validatedData['password']),
        ];

        User::create($userData);

        session()->flash('status', __('Usuário criado com sucesso!'));
        $this->dispatch('user-saved'); // Mesmo evento pode ser usado para atualizar listas, etc.
        $this->redirectRoute('users.index', navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('users.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.users.create-user'); // Novo arquivo Blade
    }
}
