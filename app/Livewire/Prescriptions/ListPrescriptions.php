<?php

namespace App\Livewire\Prescriptions;

use App\Models\Prescription;
use App\Enums\PrescriptionStatus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

#[Layout('components.layouts.app')]
class ListPrescriptions extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';
    public string $pageTitle = "Solicitações de Receitas";

    public ?string $filterStatus = null;
    public string $searchTerm = '';

    public ?Prescription $cancellingPrescription = null;
    public bool $showCancelModal = false;
    public string $cancellationReason = '';

    protected $rules = [
        'cancellationReason' => 'required|string|min:10|max:255',
    ];

    protected $messages = [
        'cancellationReason.required' => 'O motivo do cancelamento é obrigatório.',
        'cancellationReason.min' => 'O motivo deve ter pelo menos 10 caracteres.',
        'cancellationReason.max' => 'O motivo do cancelamento não pode exceder 255 caracteres.',
    ];

    public function mount()
    {
        // Opcional: Verificar se o usuário pode ver a lista de qualquer prescrição.
        // A policy 'viewAny' é verificada automaticamente pela rota ou pode ser chamada aqui.
        // try {
        //     $this->authorize('viewAny', Prescription::class);
        // } catch (AuthorizationException $e) {
        //     $this->dispatch('notify', ['message' => 'Você não tem permissão para acessar esta página.', 'type' => 'error']);
        //     return $this->redirectRoute('dashboard', navigate: true); // ou outra rota apropriada
        // }
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function openCancelModal(Prescription $prescription)
    {
        try {
            $this->authorize('cancel', $prescription);
        } catch (AuthorizationException $e) {
            $this->dispatch('notify', ['message' => 'Você não tem permissão para iniciar o cancelamento desta solicitação.', 'type' => 'error']);
            return;
        }

        if (in_array($prescription->status, [PrescriptionStatus::CANCELLED, PrescriptionStatus::DELIVERED])) {
            $this->dispatch('notify', ['message' => 'Esta receita não pode mais ser cancelada pois já foi processada ou entregue.', 'type' => 'warning']);
            return;
        }

        $this->cancellingPrescription = $prescription;
        $this->cancellationReason = '';
        $this->showCancelModal = true;
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
        $this->cancellingPrescription = null;
        $this->cancellationReason = '';
        $this->resetErrorBag('cancellationReason');
    }

    public function cancelPrescription()
    {
        if (!$this->cancellingPrescription) {
            return;
        }

        $this->authorize('cancel', $this->cancellingPrescription);
        $this->validate();

        // A policy 'cancel' já deve ter feito a verificação de status, mas uma dupla checagem aqui é aceitável.
        if (in_array($this->cancellingPrescription->status, [PrescriptionStatus::CANCELLED, PrescriptionStatus::DELIVERED])) {
            $this->dispatch('notify', ['message' => 'Esta receita não pode ser cancelada neste status.', 'type' => 'error']);
            $this->closeCancelModal();
            return;
        }

        $userPerformingAction = Auth::user()?->name ?? 'Sistema';
        $reasonText = "Cancelada por {$userPerformingAction} (" . now()->format('d/m/Y H:i') . "): " . $this->cancellationReason;
        $existingNotes = $this->cancellingPrescription->processing_notes ?? '';

        $this->cancellingPrescription->update([
            'status' => PrescriptionStatus::CANCELLED, // Eloquent handles ->value with casting
            'completed_at' => now(),
            'processing_notes' => !empty($existingNotes) ? $existingNotes . "\n---\n" . $reasonText : $reasonText,
        ]);

        $this->dispatch('notify', ['message' => 'Solicitação de receita cancelada com sucesso.', 'type' => 'success']);
        $this->closeCancelModal();
        $this->resetPage(); // Atualiza a lista
    }

    public function render()
    {
        $user = Auth::user();
        if (!$user) {
            // Se não houver usuário logado, o middleware 'auth' já deveria ter bloqueado.
            // Retornar uma view vazia ou de erro é uma contingência.
            return view('livewire.prescriptions.list-prescriptions', [
                'prescriptions' => collect()->paginate(12),
                'statusOptions' => PrescriptionStatus::options(),
            ])->layoutData(['pageTitle' => $this->pageTitle]);
        }

        // A autorização 'viewAny' pode ser feita aqui se não estiver na rota.
        // try {
        //     $this->authorize('viewAny', Prescription::class);
        // } catch (AuthorizationException $e) {
        //     return view('livewire.forbidden-access'); // Exemplo de view de acesso negado
        // }

        $query = Prescription::with(['citizen', 'requester', 'doctor', 'unit']);

        // Ordenação primária pela data de criação (mais recentes primeiro)
        $query->latest('created_at');

        // Ordenação customizada por status e depois por data de atualização
        $statusOrderValues = [
            PrescriptionStatus::REQUESTED->value,
            PrescriptionStatus::UNDER_DOCTOR_REVIEW->value,
            PrescriptionStatus::APPROVED_FOR_ISSUANCE->value,
            PrescriptionStatus::REJECTED_BY_DOCTOR->value, // Rejeitadas aparecem antes de prontas/entregues
            PrescriptionStatus::READY_FOR_PICKUP->value,
            PrescriptionStatus::DELIVERED->value,
            PrescriptionStatus::CANCELLED->value,
            PrescriptionStatus::DRAFT_REQUEST->value, // Rascunhos por último
        ];
        $statusOrderExpressionParts = [];
        foreach ($statusOrderValues as $index => $statusValue) {
            $escapedStatusValue = str_replace("'", "''", $statusValue); // Simples escape para aspas
            $statusOrderExpressionParts[] = "WHEN '{$escapedStatusValue}' THEN " . ($index + 1);
        }
        $statusOrderExpression = "CASE status " . implode(" ", $statusOrderExpressionParts) . " ELSE " . (count($statusOrderValues) + 1) . " END";
        $query->orderByRaw("{$statusOrderExpression} ASC, updated_at DESC");

        // Aplicar filtro de status
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        // Aplicar filtro por termo de busca
        if (!empty(trim($this->searchTerm))) {
            $searchTermSQL = '%' . trim($this->searchTerm) . '%';
            $query->where(function ($subQuery) use ($searchTermSQL) {
                $subQuery->whereHas('citizen', function ($q) use ($searchTermSQL) {
                    $q->whereRaw('public.unaccent_lower(name) LIKE public.unaccent_lower(?)', [$searchTermSQL])
                        ->orWhereRaw('public.unaccent_lower(cpf) LIKE public.unaccent_lower(?)', [$searchTermSQL])
                        ->orWhereRaw('public.unaccent_lower(cns) LIKE public.unaccent_lower(?)', [$searchTermSQL]);
                })->orWhereHas('requester', function ($q) use ($searchTermSQL) {
                    $q->whereRaw('public.unaccent_lower(name) LIKE public.unaccent_lower(?)', [$searchTermSQL]);
                });
            });
        }

        // Aplicar filtro de escopo baseado no perfil do usuário
        if ($user->hasRole('acs')) {
            // ACS só visualiza as próprias receitas
            $query->where('user_id', $user->id);
        } elseif (!$user->hasRole('admin') && !$user->hasRole('manager')) {
            // Outros perfis (doctor, nurse, receptionist, etc.) que não são admin ou manager,
            // veem apenas as de sua unidade (se tiverem uma unidade associada).
            if ($user->unit_id) {
                $query->where('unit_id', $user->unit_id);
            } else {
                // Se não tem unidade e não é admin/manager/acs, não deve ver nenhuma receita.
                $query->whereRaw('1 = 0'); // Condição que sempre será falsa
            }
        }
        // Admins e Managers: Nenhuma restrição de escopo adicional é aplicada aqui,
        // eles verão todas as receitas (respeitando os filtros de status e busca).
        // O Gate::before já garante acesso total ao admin.
        // A PrescriptionPolicy::viewAny para manager retorna true, permitindo ver a lista.

        return view('livewire.prescriptions.list-prescriptions', [
            'prescriptions' => $query->paginate(12), // Ajuste a paginação conforme necessário
            'statusOptions' => PrescriptionStatus::options(),
        ]);
    }
}
