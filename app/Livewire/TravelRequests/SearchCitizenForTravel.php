<?php

namespace App\Livewire\TravelRequests; // Namespace alterado

use App\Models\Citizen;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Collection; // Para tipar $results
use Illuminate\Auth\Access\AuthorizationException; // Para a autorização no mount

#[Layout('components.layouts.app')]
class SearchCitizenForTravel extends Component // Nome da classe alterado
{
    public string $search = '';
    public string $searchMother = '';
    public ?Collection $results = null;
    public string $pageTitle = "Solicitar Viagem: Buscar Cidadão"; // Título alterado

    public ?string $infoMessage = null; // Para mensagens como "Nenhum cidadão encontrado"

    public function mount(): void
    {
        try {
            // Verifica se o usuário pode criar uma TravelRequest antes mesmo de buscar o cidadão
            $this->authorize('create', \App\Models\TravelRequest::class);
        } catch (AuthorizationException $e) {
            session()->flash('error', __('Você não tem permissão para iniciar uma solicitação de viagem.'));
            // Idealmente, redirecionar ou impedir a renderização da view de busca.
            // A rota de cancelamento aqui seria para o índice de solicitações de viagem.
            $this->redirectRoute('travel-requests.index', navigate: true);
            return; // Importante para parar a execução do mount
        }
        $this->results = new Collection(); // Inicializa como uma coleção vazia
    }

    public function searchCitizen()
    {
        $this->results = new Collection(); // Limpa resultados anteriores
        $this->infoMessage = null; // Limpa mensagens anteriores
        $this->resetErrorBag(); // Limpa erros de validação anteriores

        $trimmedSearch = trim($this->search);
        $trimmedSearchMother = trim($this->searchMother);

        // Validação manual idêntica à sua
        if (!empty($trimmedSearchMother)) {
            if (strlen($trimmedSearchMother) < 3) {
                $this->addError('searchMother', 'O nome da mãe deve ter pelo menos 3 caracteres.');
                return;
            }
        } elseif (!empty($trimmedSearch)) {
            if (strlen($trimmedSearch) < 3) {
                $this->addError('search', 'A busca por cidadão deve ter pelo menos 3 caracteres.');
                return;
            }
        } else {
            $this->addError('search', 'Preencha o campo de busca principal ou o nome da mãe.');
            return;
        }

        $query = Citizen::query();

        if (!empty($trimmedSearchMother)) {
            $motherTerm = "%" . mb_strtolower($trimmedSearchMother, 'UTF-8') . "%";
            // Adapte para public.unaccent_lower se estiver usando PostgreSQL e a extensão unaccent
            $query->whereRaw('LOWER(name_mother) LIKE LOWER(?)', [$motherTerm]);
        } else {
            $termForLike = "%" . mb_strtolower($trimmedSearch, 'UTF-8') . "%";
            // Para CPF/CNS, uma busca exata ou 'LIKE val%' é geralmente melhor.
            // A query original para prescrições usava LIKE com % em ambos os lados.
            // Vamos ajustar para ser mais preciso para CPF/CNS.
            $query->where(function ($q) use ($termForLike, $trimmedSearch) {
                // Adapte para public.unaccent_lower se estiver usando PostgreSQL
                $q->whereRaw('LOWER(name) LIKE LOWER(?)', [$termForLike])
                    ->orWhere('cpf', $trimmedSearch) // Busca exata por CPF
                    ->orWhere('cns', $trimmedSearch); // Busca exata por CNS
            });
        }

        $this->results = $query->orderBy('name')->limit(10)->get();

        if ($this->results->isEmpty()) {
            // Usaremos a propriedade $infoMessage em vez de session()->flash aqui
            // para que a mensagem seja exibida diretamente na mesma renderização da busca.
            $this->infoMessage = 'Nenhum cidadão encontrado com os critérios fornecidos.';
        }
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->searchMother = '';
        $this->results = new Collection();
        $this->resetErrorBag();
        $this->infoMessage = null;
    }

    /**
     * Ação para quando um cidadão é selecionado.
     * Redireciona para o formulário de solicitação de viagem.
     */
    public function selectCitizenAndProceed(int $citizenId): void
    {
        // O parâmetro chave para o ID do cidadão na rota de destino será 'citizen'.
        // Isso facilitará o Route Model Binding no componente TravelRequestForm.
        $this->redirectRoute('travel-requests.create.form', ['citizen' => $citizenId], navigate: true);
    }

    public function render()
    {
        return view('livewire.travel-requests.search-citizen-for-travel');
    }
}
