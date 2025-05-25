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

    // Campos do Citizen que estavam faltando no formulário, mas presentes na view
    public ?string $nome_do_cidadao = null; // Ou apenas 'name' se for o mesmo que o User->name
    public ?string $data_de_nascimento = null;
    public ?int $idade = null;
    public ?string $sexo = null;
    public ?string $identidade_de_genero = null;
    public ?string $cpf = null;
    public ?string $cns = null; // Já existia no User model
    public ?string $telefone_celular = null;
    public ?string $telefone_residencial = null;
    public ?string $telefone_de_contato = null;
    public ?string $microarea = null;
    public ?string $rua = null;
    public ?string $numero = null;
    public ?string $complemento = null;
    public ?string $bairro = null;
    public ?string $municipio = null;
    public ?string $uf = null;
    public ?string $cep = null;
    public ?string $ultimo_atendimento = null;


    public bool $isEditing = false;
    public string $pageTitle = '';
    public array $availableRoles = [];
    public \Illuminate\Database\Eloquent\Collection $unitsList;

    // Novas propriedades para as opções dos selects
    public array $sexOptions = [];
    public array $genderIdentityOptions = [];


    protected function rules(): array
    {
        $userIdToIgnore = $this->isEditing && $this->user ? $this->user->id : null;

        $rules = [
            // User fields
            'name' => 'required|string|max:255', // Este 'name' é o User->name
            'email' => 'required|string|email|max:255|unique:users,email' . ($userIdToIgnore ? ',' . $userIdToIgnore : ''),
            'unit_id' => 'required|integer|exists:units,id',
            'role' => 'required|string|in:' . implode(',', User::getAvailableRoles()),
            'cns' => 'nullable|string|max:19|unique:users,cns' . ($userIdToIgnore ? ',' . $userIdToIgnore : ''), // Adicionado cns para User

            // Campos que parecem ser do Citizen (adaptar conforme sua lógica de salvar)
            'nome_do_cidadao' => 'required|string|max:255', // Se for diferente do User->name
            'data_de_nascimento' => 'nullable|date_format:Y-m-d',
            'idade' => 'nullable|integer|min:0',
            'sexo' => 'nullable|string|in:Masculino,Feminino,Outro,Não Declarado', // Adicionar as opções corretas
            'identidade_de_genero' => 'nullable|string|in:Homem,Mulher,Homem Trans,Mulher Trans,Não-binário,Outro,Não Declarado', // Adicionar as opções corretas
            'cpf' => 'nullable|string|max:14', // Adicionar validação de formato e unicidade se necessário
            'telefone_celular' => 'nullable|string|max:20',
            'telefone_residencial' => 'nullable|string|max:20',
            'telefone_de_contato' => 'nullable|string|max:20',
            'microarea' => 'nullable|string|max:50',
            'rua' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'complemento' => 'nullable|string|max:100',
            'bairro' => 'nullable|string|max:100',
            'municipio' => 'nullable|string|max:100',
            'uf' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:9',
            'ultimo_atendimento' => 'nullable|date_format:Y-m-d',
        ];

        if (!$this->isEditing) {
            $rules['password'] = ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'];
        } elseif (!empty($this->password)) {
            $rules['password'] = ['sometimes', 'nullable', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'];
        }

        return $rules;
    }

    protected $messages = [
        'password.confirmed' => 'A confirmação da senha não corresponde.',
        'name.required' => 'O nome do usuário é obrigatório.',
        'nome_do_cidadao.required' => 'O nome do cidadão é obrigatório.',
        'email.required' => 'O e-mail é obrigatório.',
        'email.email' => 'O e-mail fornecido não é válido.',
        'email.unique' => 'Este e-mail já está em uso.',
        'unit_id.required' => 'A unidade de saúde é obrigatória.',
        'role.required' => 'O papel do usuário é obrigatório.',
        'cns.unique' => 'Este CNS já está cadastrado para outro usuário.',
        // Adicione outras mensagens conforme necessário
    ];


    public function mount(?int $userId = null): void
    {
        $this->availableRoles = User::getAvailableRoles();
        $this->unitsList = Unit::orderBy('name')->get(['id', 'name']);

        // Definindo as opções para os selects
        $this->sexOptions = ['Masculino', 'Feminino', 'Outro', 'Não Declarado']; // Adapte conforme suas necessidades
        $this->genderIdentityOptions = ['Homem', 'Mulher', 'Homem Trans', 'Mulher Trans', 'Não-binário', 'Outro', 'Não Declarado']; // Adapte

        if ($userId) {
            $this->user = User::findOrFail($userId);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->unit_id = $this->user->unit_id;
            $this->role = $this->user->role;
            $this->cns = $this->user->cns; // Carrega CNS do usuário

            // Campos do "Cidadão" - Como você está gerenciando usuários,
            // estes campos podem ser os mesmos do usuário ou de um modelo Citizen relacionado.
            // Vou assumir que nome_do_cidadao é o mesmo que o nome do usuário para simplificar,
            // ajuste se a lógica for diferente.
            $this->nome_do_cidadao = $this->user->name; // Ou $this->user->citizen->name se houver relação

            // Para os outros campos (data_nascimento, idade, etc.), você precisaria de uma lógica
            // para carregá-los se eles vierem de outro modelo ou se forem colunas adicionais
            // na tabela 'users'. Por enquanto, vou deixá-los como nulos para edição.
            // Ex:
            // $this->data_de_nascimento = $this->user->data_de_nascimento; // se existir na tabela users
            // $this->idade = $this->user->idade; // se existir na tabela users
            // ...e assim por diante para os outros campos da view

            $this->isEditing = true;
            $this->pageTitle = __('Editar Usuário');
        } else {
            $this->pageTitle = __('Novo Usuário');
            // Valores padrão para criação
            if ($this->unitsList->isNotEmpty() && !$this->unit_id) {
                // $this->unit_id = $this->unitsList->first()->id; // Opcional: pré-selecionar
            }
            if (count($this->availableRoles) > 0 && !$this->role) {
                // $this->role = $this->availableRoles[0]; // Opcional: pré-selecionar
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
            'cns' => $validatedData['cns'] ?? null, // Adiciona CNS
            // Adicione aqui os outros campos da tabela 'users' se estiverem no $validatedData
            // Ex: 'data_de_nascimento' => $validatedData['data_de_nascimento'] ?? null,
        ];

        if (!empty($validatedData['password'])) {
            $userData['password'] = Hash::make($validatedData['password']);
        }

        if ($this->isEditing && $this->user) {
            $this->user->update($userData);
            session()->flash('status', __('Usuário atualizado com sucesso!'));
        } else {
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
