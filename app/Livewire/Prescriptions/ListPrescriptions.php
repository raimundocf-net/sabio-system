<?php

namespace App\Livewire\Prescriptions;

use App\Models\Prescription;
use App\Enums\PrescriptionStatus;
use App\Models\User; // Adicionado para consultar utilizadores ACS
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
    public ?string $filterAcsId = null; // Novo filtro para ACS
    public string $searchTerm = '';

    // Modal de Cancelamento
    public ?Prescription $cancellingPrescription = null;
    public bool $showCancelModal = false;
    public string $cancellationReason = '';

    // Modal de "Pronta para Retirada"
    public bool $showReadyForPickupModal = false;
    public ?int $confirmingReadyForPickupPrescriptionId = null;

    // Modal de "Entregue"
    public bool $showDeliveryModal = false;
    public ?int $deliveringPrescriptionId = null;
    public string $retrieved_by_name = '';
    public string $retrieved_by_document = '';


    protected function rules(): array
    {
        $rules = [
            'cancellationReason' => 'required_if:showCancelModal,true|string|min:10|max:255',
            'retrieved_by_name' => 'required_if:showDeliveryModal,true|string|min:3|max:255',
            'retrieved_by_document' => 'nullable|string|max:50',
        ];
        return $rules;
    }

    protected array $messages = [
        'cancellationReason.required_if' => 'O motivo do cancelamento é obrigatório.',
        'cancellationReason.min' => 'O motivo deve ter pelo menos 10 caracteres.',
        'retrieved_by_name.required_if' => 'O nome de quem retirou é obrigatório.',
        'retrieved_by_name.min' => 'O nome de quem retirou deve ter pelo menos 3 caracteres.',
    ];

    public function mount()
    {
        // Opcional: Pode inicializar $filterAcsId aqui se necessário
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterAcsId() // Método para resetar página ao mudar filtro ACS
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    // --- Lógica para Cancelamento ---
    public function openCancelModal(Prescription $prescription)
    {
        try {
            $this->authorize('cancel', $prescription);
        } catch (AuthorizationException $e) {
            $this->dispatch('notify', ['message' => 'Você não tem permissão para iniciar o cancelamento desta solicitação.', 'type' => 'error']);
            return;
        }
        if (in_array($prescription->status, [PrescriptionStatus::CANCELLED, PrescriptionStatus::DELIVERED])) {
            $this->dispatch('notify', ['message' => 'Esta receita não pode mais ser cancelada.', 'type' => 'warning']);
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
        $this->resetErrorBag(['cancellationReason']);
    }

    public function cancelPrescription()
    {
        if (!$this->cancellingPrescription) return;
        $this->authorize('cancel', $this->cancellingPrescription);
        $this->validateOnly('cancellationReason');

        $userPerformingAction = Auth::user()?->name ?? 'Sistema';
        $reasonText = "Cancelada por {$userPerformingAction} (" . now()->format('d/m/Y H:i') . "): " . $this->cancellationReason;
        $existingNotes = $this->cancellingPrescription->processing_notes ?? '';

        $this->cancellingPrescription->update([
            'status' => PrescriptionStatus::CANCELLED,
            'completed_at' => now(),
            'processing_notes' => !empty($existingNotes) ? $existingNotes . "\n---\n" . $reasonText : $reasonText,
        ]);

        $this->dispatch('notify', ['message' => 'Solicitação de receita cancelada com sucesso.', 'type' => 'success']);
        $this->closeCancelModal();
    }

    // --- Lógica para "Pronta para Retirada" ---
    public function openReadyForPickupModal(int $prescriptionId): void
    {
        $prescription = Prescription::find($prescriptionId);
        if (!$prescription) {
            $this->dispatch('notify', ['message' => 'Solicitação não encontrada.', 'type' => 'error']);
            return;
        }
        try {
            $this->authorize('changeStatus', [$prescription, PrescriptionStatus::READY_FOR_PICKUP]);
        } catch (AuthorizationException $e) {
            $this->dispatch('notify', ['message' => 'Você não tem permissão para esta ação.', 'type' => 'error']);
            return;
        }
        if ($prescription->status !== PrescriptionStatus::APPROVED_FOR_ISSUANCE) {
            $this->dispatch('notify', ['message' => 'Ação permitida apenas para receitas Aprovadas para Emissão.', 'type' => 'warning']);
            return;
        }
        $this->confirmingReadyForPickupPrescriptionId = $prescriptionId;
        $this->showReadyForPickupModal = true;
    }

    public function closeReadyForPickupModal(): void
    {
        $this->showReadyForPickupModal = false;
        $this->confirmingReadyForPickupPrescriptionId = null;
    }

    public function confirmReadyForPickup(): void
    {
        if (!$this->confirmingReadyForPickupPrescriptionId) return;

        $prescription = Prescription::find($this->confirmingReadyForPickupPrescriptionId);
        if (!$prescription) {
            $this->dispatch('notify', ['message' => 'Solicitação não encontrada.', 'type' => 'error']);
            $this->closeReadyForPickupModal();
            return;
        }

        $this->authorize('changeStatus', [$prescription, PrescriptionStatus::READY_FOR_PICKUP]);

        $userPerformingAction = Auth::user()?->name ?? 'Sistema';
        $note = "Marcada como 'Pronta para Retirada' por {$userPerformingAction} em " . now()->format('d/m/Y H:i') . ".";
        $existingNotes = $prescription->processing_notes ?? '';

        $prescription->update([
            'status' => PrescriptionStatus::READY_FOR_PICKUP,
            'processing_notes' => !empty($existingNotes) ? $existingNotes . "\n---\n" . $note : $note,
        ]);

        $this->dispatch('notify', ['message' => 'Status atualizado para Pronta para Retirada!', 'type' => 'success']);
        $this->closeReadyForPickupModal();
    }


    // --- Lógica para "Entregue" ---
    public function openDeliveryModal(int $prescriptionId): void
    {
        $prescription = Prescription::find($prescriptionId);
        if (!$prescription) {
            $this->dispatch('notify', ['message' => 'Solicitação não encontrada.', 'type' => 'error']);
            return;
        }
        try {
            $this->authorize('changeStatus', [$prescription, PrescriptionStatus::DELIVERED]);
        } catch (AuthorizationException $e) {
            $this->dispatch('notify', ['message' => 'Você não tem permissão para esta ação.', 'type' => 'error']);
            return;
        }

        if ($prescription->status !== PrescriptionStatus::READY_FOR_PICKUP) {
            $this->dispatch('notify', ['message' => 'Ação permitida apenas para receitas Prontas para Retirada.', 'type' => 'warning']);
            return;
        }

        $this->deliveringPrescriptionId = $prescriptionId;
        $this->retrieved_by_name = '';
        $this->retrieved_by_document = '';
        $this->resetErrorBag(['retrieved_by_name', 'retrieved_by_document']);
        $this->showDeliveryModal = true;
    }

    public function closeDeliveryModal(): void
    {
        $this->showDeliveryModal = false;
        $this->deliveringPrescriptionId = null;
        $this->retrieved_by_name = '';
        $this->retrieved_by_document = '';
        $this->resetErrorBag(['retrieved_by_name', 'retrieved_by_document']);
    }

    public function confirmDelivery(): void
    {
        if (!$this->deliveringPrescriptionId) return;

        $this->validateOnly('retrieved_by_name');
        if (!empty($this->retrieved_by_document)) {
            $this->validateOnly('retrieved_by_document');
        }

        $prescription = Prescription::find($this->deliveringPrescriptionId);
        if (!$prescription) {
            $this->dispatch('notify', ['message' => 'Solicitação não encontrada.', 'type' => 'error']);
            $this->closeDeliveryModal();
            return;
        }

        $this->authorize('changeStatus', [$prescription, PrescriptionStatus::DELIVERED]);

        $userPerformingAction = Auth::user()?->name ?? 'Sistema';
        $deliveryNote = "Entregue por {$userPerformingAction} em " . now()->format('d/m/Y H:i') . ".";
        $deliveryNote .= "\nRetirado por: " . trim($this->retrieved_by_name);
        if (!empty(trim($this->retrieved_by_document))) {
            $deliveryNote .= " (Doc: " . trim($this->retrieved_by_document) . ")";
        }
        $deliveryNote .= ".";

        $existingNotes = $prescription->processing_notes ?? '';

        $prescription->update([
            'status' => PrescriptionStatus::DELIVERED,
            'completed_at' => now(),
            'processing_notes' => !empty($existingNotes) ? $existingNotes . "\n---\n" . $deliveryNote : $deliveryNote,
        ]);

        $this->dispatch('notify', ['message' => 'Receita marcada como Entregue!', 'type' => 'success']);
        $this->closeDeliveryModal();
    }


    public function render()
    {
        $user = Auth::user();
        if (!$user) {
            return view('livewire.prescriptions.list-prescriptions', [
                'prescriptions' => collect()->paginate(12),
                'statusOptions' => PrescriptionStatus::options(),
                'acsUsers' => [], // Adicionado para evitar erro se user for nulo
            ])->layoutData(['pageTitle' => $this->pageTitle]);
        }

        $query = Prescription::with(['citizen', 'requester', 'doctor', 'unit']);
        $query->latest('created_at');

        $statusOrderValues = [
            PrescriptionStatus::REQUESTED->value,
            PrescriptionStatus::UNDER_DOCTOR_REVIEW->value,
            PrescriptionStatus::APPROVED_FOR_ISSUANCE->value,
            PrescriptionStatus::REJECTED_BY_DOCTOR->value,
            PrescriptionStatus::READY_FOR_PICKUP->value,
            PrescriptionStatus::DELIVERED->value,
            PrescriptionStatus::CANCELLED->value,
            PrescriptionStatus::DRAFT_REQUEST->value,
        ];
        $statusOrderExpressionParts = [];
        foreach ($statusOrderValues as $index => $statusValue) {
            $escapedStatusValue = str_replace("'", "''", $statusValue);
            $statusOrderExpressionParts[] = "WHEN '{$escapedStatusValue}' THEN " . ($index + 1);
        }
        $statusOrderExpression = "CASE status " . implode(" ", $statusOrderExpressionParts) . " ELSE " . (count($statusOrderValues) + 1) . " END";
        $query->orderByRaw("{$statusOrderExpression} ASC, updated_at DESC");

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if (!empty(trim($this->searchTerm))) {
            $searchTermSQL = '%' . trim($this->searchTerm) . '%';
            $query->where(function ($subQuery) use ($searchTermSQL) {
                $subQuery->whereHas('citizen', function ($q) use ($searchTermSQL) {
                    $q->whereRaw('public.unaccent_lower(nome_do_cidadao) LIKE public.unaccent_lower(?)', [$searchTermSQL])
                        ->orWhereRaw('public.unaccent_lower(cpf) LIKE public.unaccent_lower(?)', [$searchTermSQL])
                        ->orWhereRaw('public.unaccent_lower(cns) LIKE public.unaccent_lower(?)', [$searchTermSQL]);
                })->orWhereHas('requester', function ($q) use ($searchTermSQL) {
                    $q->whereRaw('public.unaccent_lower(name) LIKE public.unaccent_lower(?)', [$searchTermSQL]);
                });
            });
        }

        // Filtro de ACS e lógica de permissão existente
        if ($user->hasRole('acs')) {
            $query->where('user_id', $user->id);
            // Se o utilizador logado é um ACS, o filtro $filterAcsId é ignorado ou só mostraria ele mesmo.
            // Para simplificar, se um ACS está logado, ele só vê as suas próprias prescrições e o filtro $filterAcsId não se aplicará para outros ACS.
        } elseif ($this->filterAcsId) { // Aplicar filtro ACS se não for um ACS e se um ID de ACS for selecionado
            $query->where('user_id', $this->filterAcsId);
        } elseif (!$user->hasRole('admin') && !$user->hasRole('manager')) {
            // Utilizadores que não são admin/manager e não são ACS (ex: doctor, nurse)
            if ($user->unit_id) {
                $query->where('unit_id', $user->unit_id);
            } else {
                // Se não tiver unit_id e não for admin/manager/acs, não mostra nada
                $query->whereRaw('1 = 0');
            }
        }
        // Se for admin ou manager e $filterAcsId não estiver definido, vê todas as prescrições (ou as da sua unidade se a lógica futura o exigir).


        $acsUsersList = [];
        // Apenas admin e manager podem usar o filtro ACS livremente.
        // Outros papéis (exceto ACS) não verão este filtro populado ou ele não terá efeito se forçado.
        // ACS já têm a sua visão filtrada.
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            $acsUsersList = User::where('role', 'acs')->orderBy('name')->pluck('name', 'id')->toArray();
        } elseif ($user->hasRole('acs')) {
            // Um ACS pode ver a si mesmo no filtro, mas não terá efeito prático.
            // Poderia ser uma lista vazia ou apenas o próprio ACS. Para consistência:
            $acsUsersList = User::where('id', $user->id)->pluck('name', 'id')->toArray();
        }


        return view('livewire.prescriptions.list-prescriptions', [
            'prescriptions' => $query->paginate(12),
            'statusOptions' => PrescriptionStatus::options(),
            'acsUsers' => $acsUsersList, // Passa a lista de ACS para a view
        ]);
    }
}
