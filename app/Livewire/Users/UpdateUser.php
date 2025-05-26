<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class UpdateUser extends Component
{
    public User $user; // Usuário será injetado ou carregado

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
        $userIdToIgnore = $this->user->id;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userIdToIgnore,
            'unit_id' => 'required|integer|exists:units,id',
            'role' => 'required|string|in:' . implode(',', User::getRoleKeys()),
            'cns' => 'nullable|string|max:19|unique:users,cns,' . $userIdToIgnore,
            'cbo' => 'nullable|string|max:255',
            'password' => ['sometimes', 'nullable', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
        ];
    }

    // Mensagens podem ser as mesmas da classe CreateUser ou personalizadas se necessário
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

    public function mount(User $user): void // Injeção de rota-modelo
    {
        $this->user = $user;
        $this->pageTitle = __('Editar Usuário: ') . $this->user->name;
        $this->availableRoles = User::getAvailableRoles();
        $this->unitsList = Unit::orderBy('name')->get(['id', 'name']);

        // Preencher os campos do formulário com os dados do usuário
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->unit_id = $this->user->unit_id;
        $this->role = $this->user->role;
        $this->cns = $this->user->cns;
        $this->cbo = $this->user->cbo;
    }

    public function updateUser(): void // Renomeado de save para updateUser para clareza
    {
        $validatedData = $this->validate();

        $userData = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'unit_id' => $validatedData['unit_id'],
            'role' => $validatedData['role'],
            'cns' => $validatedData['cns'] ?? null,
            'cbo' => $validatedData['cbo'] ?? null,
        ];

        if (!empty($validatedData['password'])) {
            $userData['password'] = Hash::make($validatedData['password']);
        }

        $this->user->update($userData);

        session()->flash('status', __('Usuário atualizado com sucesso!'));
        $this->dispatch('user-saved');
        $this->redirectRoute('users.index', navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('users.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.users.update-user'); // Novo arquivo Blade
    }
}
