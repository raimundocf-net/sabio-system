<?php

namespace App\Livewire\Prescriptions\Request;

use App\Models\CitizenPac;
use App\Models\User; // Adicionado para type hinting e verificação de role
use Illuminate\Support\Facades\Auth; // Adicionado para obter o usuário autenticado
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class SearchCitizenStep extends Component
{
    public string $search = '';
    public $results = null;
    public string $pageTitle = "Solicitar Receita: Buscar Cidadão";

    protected function rules(): array
    {
        return [
            'search' => 'required|string|min:3',
        ];
    }

    protected $messages = [
        'search.required' => 'O campo de busca é obrigatório.',
        'search.min' => 'A busca deve ter pelo menos 3 caracteres.',
    ];

    public function updatedSearch(string $value): void
    {
        $this->validateOnly('search');
    }

    public function searchCitizen()
    {
        $this->results = null;
        session()->forget('info_message');
        $this->resetErrorBag();
        $this->validate();

        $trimmedSearch = trim($this->search);
        $query = CitizenPac::query();
        $user = Auth::user(); // Obter o usuário autenticado

        // Aplicar filtro de microárea para ACS
        if ($user && $user->hasRole('acs')) { //
            // Assumindo que o modelo User tem um atributo 'microarea'
            // Certifique-se de que este campo existe na sua tabela 'users' e no modelo User.
            if (!empty($user->microarea)) {
                $query->where('microarea', $user->microarea);
            } else {
                // Se o ACS não tiver microárea definida, não retorna nenhum cidadão,
                // ou você pode optar por não aplicar filtro algum (removendo este else).
                // Retornar vazio é mais seguro para garantir que ele só veja sua área.
                $this->results = collect(); // Retorna uma coleção vazia
                session()->flash('info_message', 'Sua microárea não está definida. Não é possível buscar cidadãos.');
                return;
            }
        }

        $term = "%{$trimmedSearch}%";
        $query->where(function ($q) use ($term) {
            $q->whereRaw('public.unaccent_lower(nome_do_cidadao) LIKE public.unaccent_lower(?)', [$term])
                ->orWhereRaw('public.unaccent_lower(cpf) LIKE public.unaccent_lower(?)', [$term])
                ->orWhereRaw('public.unaccent_lower(cns) LIKE public.unaccent_lower(?)', [$term]);
        });

        $this->results = $query->orderBy('nome_do_cidadao')->limit(10)->get();

        if ($this->results->isEmpty() && !session()->has('info_message')) {
            session()->flash('info_message', 'Nenhum cidadão encontrado com os critérios fornecidos.');
        }
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->results = null;
        $this->resetErrorBag();
        session()->forget('info_message');
    }

    public function render()
    {
        return view('livewire.prescriptions.request.search-citizen-step');
    }
}
