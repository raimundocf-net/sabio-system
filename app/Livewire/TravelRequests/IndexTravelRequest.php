<?php

namespace App\Livewire\TravelRequests;

use App\Models\TravelRequest;
use App\Models\Citizen; // Para busca por nome do cidadão
use App\Enums\TravelRequestStatus;
use App\Enums\ProcedureType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
class IndexTravelRequest extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $pageTitle = "Gerenciar Solicitações de Viagem";

    // Filtros e Busca
    public string $searchTerm = ''; // Para buscar por ID da solicitação, nome do paciente, CPF, CNS, destino
    public ?string $filterStatus = null;
    public ?string $filterProcedureType = null;
    public ?string $filterDateOption = null; // 'appointment_date', 'request_date'
    public ?string $filterStartDate = null;
    public ?string $filterEndDate = null;
    public int $perPage = 10;

    // Modal de Cancelamento/Exclusão
    public bool $showCancelModal = false;
    public ?TravelRequest $cancellingTravelRequest = null;
    public string $cancellationReason = ''; // Para o motivo do cancelamento

    // Opções para os selects de filtro
    public array $statusOptions = [];
    public array $procedureTypeOptions = [];
    public array $dateFilterOptions = [
        'appointment_datetime' => 'Data do Compromisso',
        'created_at' => 'Data da Solicitação',
    ];

    public function mount(): void
    {
        try {
            $this->authorize('viewAny', TravelRequest::class);
        } catch (AuthorizationException $e) {
            session()->flash('error', __('Você não tem permissão para acessar esta página.'));
            // Considerar redirecionar para dashboard ou uma página de acesso negado
            // $this->redirectRoute('dashboard', navigate: true);
            return; // Impede a execução adicional se não autorizado
        }

        $this->statusOptions = TravelRequestStatus::options();
        $this->procedureTypeOptions = ProcedureType::options();
    }

    // Resetar página ao atualizar filtros
    public function updatedSearchTerm(): void { $this->resetPage(); }
    public function updatedFilterStatus(): void { $this->resetPage(); }
    public function updatedFilterProcedureType(): void { $this->resetPage(); }
    public function updatedFilterDateOption(): void { $this->resetPage(); $this->filterStartDate = null; $this->filterEndDate = null; }
    public function updatedFilterStartDate(): void { $this->resetPage(); }
    public function updatedFilterEndDate(): void { $this->resetPage(); }
    public function updatedPerPage(): void { $this->resetPage(); }


    public function openCancelModal(int $travelRequestId): void
    {
        $this->cancellingTravelRequest = TravelRequest::find($travelRequestId);
        if ($this->cancellingTravelRequest) {
            // Usaremos a policy 'delete' como uma verificação genérica de "pode cancelar"
            // A lógica específica de quem pode cancelar qual status estará na policy.
            try {
                $this->authorize('delete', $this->cancellingTravelRequest); // Ou uma action 'cancel' na policy
                $this->cancellationReason = ''; // Limpa o motivo anterior
                $this->showCancelModal = true;
            } catch (AuthorizationException $e) {
                session()->flash('error', __('Você não tem permissão para cancelar esta solicitação.'));
                $this->cancellingTravelRequest = null;
            }
        } else {
            session()->flash('error', __('Solicitação não encontrada.'));
        }
    }

    public function closeCancelModal(): void
    {
        $this->showCancelModal = false;
        $this->cancellingTravelRequest = null;
        $this->cancellationReason = '';
        $this->resetErrorBag('cancellationReason');
    }

    public function cancelTravelRequest(): void
    {
        if ($this->cancellingTravelRequest) {
            $this->validate([
                'cancellationReason' => 'required|string|min:10|max:500',
            ], [
                'cancellationReason.required' => 'O motivo do cancelamento é obrigatório.',
                'cancellationReason.min' => 'O motivo deve ter pelo menos 10 caracteres.',
                'cancellationReason.max' => 'O motivo não pode exceder 500 caracteres.',
            ]);

            try {
                $this->authorize('delete', $this->cancellingTravelRequest); // Re-autoriza

                // Determinar o status de cancelamento apropriado
                $newStatus = Auth::id() === $this->cancellingTravelRequest->requester_id
                    ? TravelRequestStatus::CANCELLED_BY_USER
                    : TravelRequestStatus::CANCELLED_BY_ADMIN;

                $this->cancellingTravelRequest->update([
                    'status' => $newStatus->value,
                    'cancellation_reason' => $this->cancellationReason,
                    'cancellation_notes' => 'Cancelado por: ' . Auth::user()->name . ' em ' . now()->format('d/m/Y H:i'),
                    'cancelled_at' => now(),
                ]);

                // Se você usa SoftDeletes e quer realmente "excluir" da lista principal:
                // $this->cancellingTravelRequest->delete();

                session()->flash('status', __('Solicitação de viagem cancelada com sucesso!'));
            } catch (AuthorizationException $e) {
                session()->flash('error', __('Você não tem permissão para cancelar esta solicitação.'));
            } catch (\Exception $e) {
                session()->flash('error', __('Erro ao cancelar a solicitação: ') . $e->getMessage());
            }
            $this->closeCancelModal();
        }
    }


    public function render()
    {
        if (!Gate::allows('viewAny', TravelRequest::class) && !Auth::user()->hasRole('admin')) {
            return view('livewire.travel-requests.index-travel-request', [
                'travelRequests' => collect()->paginate($this->perPage),
            ])->layoutData(['pageTitle' => $this->pageTitle]);
        }

        $query = TravelRequest::query()
            ->with(['citizen:id,name,cpf', 'requester:id,name']) // Eager load para performance
            ->when($this->searchTerm, function ($query, $term) {
                $query->where(function ($subQuery) use ($term) {
                    $searchTermSQL = '%' . mb_strtolower($term) . '%'; // Convertido para minúsculas
                    $subQuery->whereRaw('CAST(travel_requests.id AS TEXT) LIKE ?', [$term . '%']) // Busca por ID da solicitação
                    ->orWhereHas('citizen', function ($qCitizen) use ($searchTermSQL, $term) {
                        $qCitizen->whereRaw('public.unaccent_lower(name) LIKE public.unaccent_lower(?)', [$searchTermSQL])
                            ->orWhere('cpf', 'like', $term . '%') // Termo original para CPF
                            ->orWhere('cns', 'like', $term . '%'); // Termo original para CNS
                    })
                        ->orWhereRaw('public.unaccent_lower(destination_city) LIKE public.unaccent_lower(?)', [$searchTermSQL])
                        ->orWhereRaw('public.unaccent_lower(destination_address) LIKE public.unaccent_lower(?)', [$searchTermSQL]);
                });
            })
            ->when($this->filterStatus, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($this->filterProcedureType, function ($query, $type) {
                $query->where('procedure_type', $type);
            })
            ->when($this->filterDateOption && $this->filterStartDate && $this->filterEndDate, function ($query) {
                $startDate = Carbon::parse($this->filterStartDate)->startOfDay();
                $endDate = Carbon::parse($this->filterEndDate)->endOfDay();
                $dateColumn = $this->filterDateOption === 'created_at' ? 'travel_requests.created_at' : 'appointment_datetime';
                $query->whereBetween($dateColumn, [$startDate, $endDate]);
            })
            ->orderByDesc('travel_requests.created_at'); // Mais recentes primeiro

        $travelRequests = $query->paginate($this->perPage);

        return view('livewire.travel-requests.index-travel-request', [
            'travelRequests' => $travelRequests,
        ])->layoutData(['pageTitle' => $this->pageTitle]); // Passa o título para o layout
    }
}
