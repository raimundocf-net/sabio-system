<?php

namespace App\Livewire\TravelRequests;

use App\Models\CitizenPac;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth; // Adicionado para obter o usuário e sua microarea, se necessário no futuro

#[Layout('components.layouts.app')]
class SearchCitizenForTravel extends Component
{
    public string $search = '';
    public ?Collection $results = null;
    public string $pageTitle = "Solicitar Viagem: Buscar Cidadão";

    public ?string $infoMessage = null;

    public function mount(): void
    {
        try {
            $this->authorize('create', \App\Models\TravelRequest::class);
        } catch (AuthorizationException $e) {
            session()->flash('error', __('Você não tem permissão para iniciar uma solicitação de viagem.'));
            $this->redirectRoute('travel-requests.index', navigate: true);
            return;
        }
        $this->results = new Collection();
    }

    // Adicionando regras de validação do Livewire para simplificar
    protected function rules(): array
    {
        return [
            'search' => 'required|string|min:3',
        ];
    }

    protected function messages(): array
    {
        return [
            'search.required' => 'O campo de busca é obrigatório.',
            'search.min' => 'A busca deve ter pelo menos 3 caracteres.',
        ];
    }

    public function updatedSearch(string $value): void
    {
        $this->validateOnly('search');
    }

    public function searchCitizen()
    {
        $this->results = new Collection();
        $this->infoMessage = null;
        $this->resetErrorBag();

        // Validação usando as rules()
        $this->validate();

        $trimmedSearch = trim($this->search);
        $user = Auth::user(); // Para futuras regras de ACS, se aplicável a este módulo também

        $query = CitizenPac::query(); // Continua usando o modelo Citizen


        // Busca pelo campo principal (nome, cpf, cns do cidadão)
        $termForLike = "%" . mb_strtolower($trimmedSearch, 'UTF-8') . "%";
        $query->where(function ($q) use ($termForLike, $trimmedSearch) {
            $q->whereRaw('LOWER(nome_do_cidadao) LIKE LOWER(?)', [$termForLike]) // Assumindo que Citizen tem 'name'
            ->orWhere('cpf', $trimmedSearch) // Busca exata por CPF
            ->orWhere('cns', $trimmedSearch); // Busca exata por CNS
        });

        $this->results = $query->orderBy('nome_do_cidadao')->limit(10)->get();

        if ($this->results->isEmpty()) {
            $this->infoMessage = 'Nenhum cidadão encontrado com os critérios fornecidos.';
        }
    }

    public function clearSearch()
    {
        $this->search = '';
        // $this->searchMother = ''; // REMOVIDO
        $this->results = new Collection();
        $this->resetErrorBag();
        $this->infoMessage = null;
    }

    public function selectCitizenAndProceed(int $citizenId): void
    {
        $this->redirectRoute('travel-requests.create.form', ['citizen' => $citizenId], navigate: true);
    }

    public function render()
    {
        return view('livewire.travel-requests.search-citizen-for-travel');
    }
}
