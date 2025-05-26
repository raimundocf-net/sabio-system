<?php

namespace App\Livewire\TravelRequests;

use App\Models\TravelRequest;
use App\Enums\TravelRequestStatus;
use App\Enums\ProcedureType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection; // Para tipagem

#[Layout('components.layouts.app')]
class IndexTravelRequest extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $pageTitle = "Gerenciar Solicitações de Viagem";

    // Filtros e Busca
    public string $searchTerm = '';
    public ?string $filterStatus = null;
    public ?string $filterProcedureType = null;
    public ?string $filterDateOption = null;
    public ?string $filterStartDate = null;
    public ?string $filterEndDate = null;
    public int $perPage = 10;

    // Modal de Cancelamento
    public bool $showCancelModal = false;
    public ?TravelRequest $cancellingTravelRequest = null;
    public string $cancellationReason = '';
    public string $cancellationNotes = ''; // Adicionado para notas do cancelamento

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
            // Considerar redirecionar ou garantir que a view lide com a ausência de dados
            // $this->redirectRoute('dashboard', navigate: true);
            return;
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
            try {
                // Usamos 'delete' como uma permissão genérica para "pode alterar para um estado final"
                $this->authorize('delete', $this->cancellingTravelRequest);
                $this->cancellationReason = '';
                $this->cancellationNotes = 'Cancelado por: ' . Auth::user()->name . ' em ' . now()->format('d/m/Y H:i') . '. ';
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
        $this->cancellationNotes = '';
        $this->resetErrorBag(['cancellationReason', 'cancellationNotes']);
    }

    public function cancelTravelRequest(): void
    {
        if (!$this->cancellingTravelRequest) {
            return;
        }

        $this->validate([
            'cancellationReason' => 'required|string|min:10|max:500',
            'cancellationNotes' => 'nullable|string|max:500', // Notas são opcionais
        ], [
            'cancellationReason.required' => 'O motivo do cancelamento é obrigatório.',
            'cancellationReason.min' => 'O motivo deve ter pelo menos 10 caracteres.',
        ]);

        try {
            $this->authorize('delete', $this->cancellingTravelRequest);

            // Determinar o status de cancelamento apropriado com base em quem está cancelando
            // Esta lógica pode ser mais elaborada se outros papéis puderem cancelar.
            $newStatus = Auth::id() === $this->cancellingTravelRequest->requester_id && Auth::user()->hasRole('acs') // Exemplo se ACS pode cancelar
                ? TravelRequestStatus::CANCELLED_BY_USER // Ou um status específico para ACS
                : TravelRequestStatus::CANCELLED_BY_ADMIN; // Padrão para admin/manager

            // Verifica se a solicitação já está em um estado final
            if (in_array($this->cancellingTravelRequest->status, [
                TravelRequestStatus::CANCELLED_BY_USER,
                TravelRequestStatus::CANCELLED_BY_ADMIN,
                // Adicionar outros status finais se houver, como SCHEDULED se não puder ser cancelada depois disso
            ])) {
                session()->flash('info_message', __('Esta solicitação já está em um estado final e não pode ser cancelada novamente.'));
                $this->closeCancelModal();
                return;
            }


            $this->cancellingTravelRequest->update([
                'status' => $newStatus->value,
                'cancellation_reason' => $this->cancellationReason,
                'cancellation_notes' => $this->cancellationNotes, // Salva as notas
                'cancelled_at' => now(),
                'approver_id' => $this->cancellingTravelRequest->approver_id ?? Auth::id(), // Se não houver aprovador, quem cancela se torna o "manipulador" final
            ]);

            session()->flash('status', __('Solicitação de viagem cancelada com sucesso!'));
        } catch (AuthorizationException $e) {
            session()->flash('error', __('Você não tem permissão para cancelar esta solicitação.'));
        } catch (\Exception $e) {
            session()->flash('error', __('Erro ao cancelar a solicitação: ') . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Erro ao cancelar TravelRequest: ' . $e->getMessage(), ['request_id' => $this->cancellingTravelRequest->id]);
        }
        $this->closeCancelModal();
    }


    public function render()
    {
        // Inicializa travelRequests como uma paginação vazia para evitar erros na view
        // se a autorização falhar ou não houver dados.
        $travelRequests = TravelRequest::query()->whereRaw('1 = 0')->paginate($this->perPage);

        // A verificação de Gate::allows('viewAny', TravelRequest::class) já deve estar no mount
        // ou ser tratada pela rota/middleware. Se for para restringir dados aqui:
        // if (!Gate::allows('viewAny', TravelRequest::class) && !(Auth::user() && Auth::user()->hasRole('admin'))) {
        //     session()->flash('error', 'Você não tem permissão para ver esta lista.');
        //     return view('livewire.travel-requests.index-travel-request', [
        //         'travelRequests' => $travelRequests, // Paginação vazia
        //     ])->layoutData(['pageTitle' => $this->pageTitle]);
        // }

        // Ajuste na query para usar os campos de CitizenPac
        $query = TravelRequest::query()
            // Ao carregar a relação 'citizen', selecione os campos de CitizenPac
            // O principal é mudar 'name' para 'nome_do_cidadao'
            ->with(['citizen:id,nome_do_cidadao,cpf,cns', 'requester:id,name']) // <<< MUDANÇA AQUI
            ->when($this->searchTerm, function ($query, $term) {
                $query->where(function ($subQuery) use ($term) {
                    $searchTermSQL = '%' . mb_strtolower($term, 'UTF-8') . '%';
                    $subQuery->whereRaw('CAST(travel_requests.id AS TEXT) LIKE ?', [$term . '%'])
                        ->orWhereHas('citizen', function ($qCitizen) use ($searchTermSQL, $term) {
                            // Dentro da relação 'citizen', use os campos de CitizenPac
                            $qCitizen->whereRaw('public.unaccent_lower(nome_do_cidadao) LIKE public.unaccent_lower(?)', [$searchTermSQL]) // <<< MUDANÇA AQUI
                            ->orWhere('cpf', 'like', $term . '%') // Assumindo que 'cpf' existe em CitizenPac
                            ->orWhere('cns', 'like', $term . '%'); // Assumindo que 'cns' existe em CitizenPac
                        })
                        ->orWhereRaw('public.unaccent_lower(destination_city) LIKE public.unaccent_lower(?)', [$searchTermSQL])
                        ->orWhereRaw('public.unaccent_lower(reason) LIKE public.unaccent_lower(?)', [$searchTermSQL]);
                });
            })
            ->when($this->filterStatus, fn ($query, $status) => $query->where('status', $status))
            ->when($this->filterProcedureType, fn ($query, $type) => $query->where('procedure_type', $type))
            ->when($this->filterDateOption && $this->filterStartDate && $this->filterEndDate, function ($query) {
                $startDate = Carbon::parse($this->filterStartDate)->startOfDay();
                $endDate = Carbon::parse($this->filterEndDate)->endOfDay();
                // Garante que a coluna de data seja prefixada com o nome da tabela para evitar ambiguidade
                $dateColumn = $this->filterDateOption === 'created_at' ? 'travel_requests.created_at' : 'travel_requests.appointment_datetime';
                $query->whereBetween($dateColumn, [$startDate, $endDate]);
            })
            ->orderByDesc('travel_requests.created_at'); // Prefixado com nome da tabela

        $travelRequests = $query->paginate($this->perPage);

        // Removida a lógica de autorização duplicada daqui,
        // pois ela geralmente reside no mount ou é tratada por middleware/políticas de rota.
        // A view sempre receberá $travelRequests (que pode ser uma paginação vazia se o usuário não tiver permissão
        // e isso for tratado no início do método ou no mount).

        return view('livewire.travel-requests.index-travel-request', [
            'travelRequests' => $travelRequests,
            // As variáveis para os filtros (statusOptions, procedureTypeOptions, etc.)
            // devem ser preparadas no mount ou diretamente aqui, se forem fixas.
            // Ex: 'statusOptions' => TravelRequestStatus::options(),
            // Ex: 'procedureTypeOptions' => ProcedureType::options(),
        ])->layoutData(['pageTitle' => $this->pageTitle]);
    }
}
