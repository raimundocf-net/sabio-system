<?php

namespace App\Livewire\Components;

use App\Models\Citizen;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.app')]
class CitizenSearchPage extends Component
{
    public string $search = '';
    public string $searchMother = '';
    public ?Collection $results = null;

    // Estas serão configuradas pelas classes filhas
    public string $pageTitle = "Buscar Cidadão";
    public string $successRouteName;
    public array $successRouteParams = [];
    public string $cancelRouteName;

    public ?string $infoMessage = null;

    // Mount pode ser simplificado ou usado para inicializações comuns
    public function mount(): void
    {
        if (is_null($this->results)) { // Garante que results seja sempre uma coleção
            $this->results = new Collection();
        }
        // A autorização específica deve ser feita nos wrappers ou nas rotas que os chamam
    }

    // Métodos searchCitizen, clearSearch, selectCitizenAndProceed permanecem os mesmos...
    public function searchCitizen(): void
    {
        $this->results = new Collection();
        $this->infoMessage = null;
        $this->resetErrorBag();

        $trimmedSearch = trim($this->search);
        $trimmedSearchMother = trim($this->searchMother);

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
            $query->whereRaw('public.unaccent_lower(name_mother) LIKE public.unaccent_lower(?)', [$motherTerm]);
        } else {
            $term = "%" . mb_strtolower($trimmedSearch, 'UTF-8') . "%";
            $query->where(function ($q) use ($term, $trimmedSearch) {
                $q->whereRaw('public.unaccent_lower(name) LIKE public.unaccent_lower(?)', [$term])
                    ->orWhere('cpf', $trimmedSearch)
                    ->orWhere('cns', $trimmedSearch);
            });
        }

        $this->results = $query->orderBy('name')->limit(10)->get();

        if ($this->results->isEmpty()) {
            $this->infoMessage = 'Nenhum cidadão encontrado com os critérios fornecidos.';
        }
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->searchMother = '';
        $this->results = new Collection();
        $this->resetErrorBag();
        $this->infoMessage = null;
    }

    public function selectCitizenAndProceed(int $citizenId): void
    {
        $params = array_merge($this->successRouteParams, ['citizen' => $citizenId]);
        try {
            $this->redirectRoute($this->successRouteName, $params, navigate: true);
        } catch (\Exception $e) {
            Log::error("Erro ao redirecionar para {$this->successRouteName}: " . $e->getMessage());
            session()->flash('error', 'Erro ao tentar prosseguir. Rota de destino inválida ou faltando parâmetros.');
        }
    }
    // FIM - Métodos searchCitizen, clearSearch, selectCitizenAndProceed

    public function render()
    {
        // O componente base renderiza a view reutilizável
        return view('livewire.components.citizen-search-page');
    }
}
