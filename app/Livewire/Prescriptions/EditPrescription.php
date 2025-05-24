<?php

namespace App\Livewire\Prescriptions;

use App\Models\Prescription;
// use App\Models\User; // Não mais necessário aqui se a lista de médicos foi removida da edição
use App\Enums\PrescriptionStatus;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads; // <<< ADICIONAR TRAIT
use Illuminate\Support\Facades\Storage; // <<< ADICIONAR STORAGE
use Intervention\Image\Laravel\Facades\Image; // <<< ADICIONAR INTERVENTION IMAGE

#[Layout('components.layouts.app')]
class EditPrescription extends Component
{
    use WithFileUploads; // <<< USAR O TRAIT

    public Prescription $prescription;
    public string $pageTitle = "Detalhes da Solicitação de Receita";

    public string $current_processing_notes = '';
    public string $editablePrescriptionDetails = '';
    public string $editOrCorrectionReason = '';

    // Para upload/gerenciamento da imagem da receita
    public $newPrescriptionImage; // Para o novo upload
    //public ?string $currentPrescriptionImageUrl = null; // Para exibir a imagem atual

    public bool $showStatusUpdateModal = false;
    public ?string $targetStatus = null;
    public string $statusUpdateReason = '';
    public string $modalTitle = '';
    public string $modalConfirmationButtonText = 'Confirmar';

    public array $statusOptionsForSelect = [];

    protected function rules(): array
    {
        $isAcsCorrectingOrEditing = Auth::check() &&
            Auth::user()->hasRole('acs') &&
            Auth::id() === $this->prescription->user_id &&
            in_array($this->prescription->status, [PrescriptionStatus::REQUESTED, PrescriptionStatus::REJECTED_BY_DOCTOR]);

        $rules = [
            'current_processing_notes' => 'nullable|string|max:2000',
            'editablePrescriptionDetails' => [
                Rule::requiredIf($isAcsCorrectingOrEditing),
                'nullable', // Permite ser nulo se não for o cenário acima
                'string',
                'min:10',
                'max:2000',
            ],
            'editOrCorrectionReason' => 'nullable|string|max:500',
            // Validação para a nova imagem (opcional, imagem, max 5MB)
            'newPrescriptionImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];

        if ($this->showStatusUpdateModal &&
            in_array($this->targetStatus, [
                PrescriptionStatus::REJECTED_BY_DOCTOR->value,
                PrescriptionStatus::CANCELLED->value
            ])) {
            $rules['statusUpdateReason'] = 'required|string|min:10|max:500';
        }
        return $rules;
    }

    protected array $messages = [
        // ... mensagens existentes ...
        'editablePrescriptionDetails.required' => 'Os detalhes do pedido são obrigatórios.', // Ajustado de required_if
        'newPrescriptionImage.image' => 'O arquivo enviado deve ser uma imagem válida.',
        'newPrescriptionImage.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg, gif, webp.',
        'newPrescriptionImage.max' => 'A nova imagem não pode ser maior que 5MB.',
    ];

    public function mount(Prescription $prescription)
    {
        $this->prescription = $prescription->load(['citizen', 'requester', 'unit', 'doctor']);
        try {
            $this->authorize('view', $this->prescription);
        } catch (AuthorizationException $e) {
            $this->dispatch('notify', ['message' => 'Você não tem permissão para ver esta solicitação.', 'type' => 'error']);
            return $this->redirectRoute('prescriptions.index', navigate:true);
        }

        $this->pageTitle = __("Solicitação de Receita #") . $this->prescription->id;
        $this->editablePrescriptionDetails = $this->prescription->prescription_details ?? '';
        //$this->currentPrescriptionImageUrl = $this->prescription->image_url; // Usa o acessor do modelo
        $this->statusOptionsForSelect = $this->getAvailableStatusTransitions();
    }

    // Método para atualizar/substituir a imagem anexada
    public function updateAttachedImage()
    {
        if (!$this->prescription) return;
        $this->authorize('update', $this->prescription); // Requer permissão de update na prescrição

        $this->validate(['newPrescriptionImage' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120']);

        if ($this->newPrescriptionImage) {
            // 1. Deleta a imagem antiga, se existir
            if ($this->prescription->image_path && Storage::disk('public')->exists($this->prescription->image_path)) {
                Storage::disk('public')->delete($this->prescription->image_path);
            }

            // 2. Processa e armazena a nova imagem
            try {
                $image = Image::read($this->newPrescriptionImage->getRealPath());
                $image->scaleDown(width: 1200); // Redimensiona mantendo proporção
                $filename = 'rx_img_' . uniqid() . '_' . time() . '.webp';
                $encodedImage = $image->toWebp(75);
                $directory = 'prescription_images';
                Storage::disk('public')->put($directory . '/' . $filename, (string) $encodedImage);
                $this->prescription->image_path = $directory . '/' . $filename;

            } catch (\Exception $e) {
                $this->dispatch('notify', ['message' => 'Erro ao processar a nova imagem: ' . $e->getMessage(), 'type' => 'error']);
                $this->newPrescriptionImage = null; // Limpa o upload falho
                return;
            }

            $this->prescription->save();
            $this->prescription->refresh();
            $this->currentPrescriptionImageUrl = $this->prescription->image_url; // Atualiza a URL da imagem exibida
            $this->newPrescriptionImage = null; // Limpa o campo de upload
            $this->dispatch('notify', ['message' => 'Imagem da receita atualizada com sucesso!', 'type' => 'success']);
        }
    }

    // Método para remover a imagem anexada
    public function removeAttachedImage()
    {
        if (!$this->prescription) return;
        $this->authorize('update', $this->prescription); // Requer permissão de update

        if ($this->prescription->image_path && Storage::disk('public')->exists($this->prescription->image_path)) {
            Storage::disk('public')->delete($this->prescription->image_path);
            $this->prescription->image_path = null;
            $this->prescription->save();
            $this->prescription->refresh();
            $this->currentPrescriptionImageUrl = null;
            $this->dispatch('notify', ['message' => 'Imagem da receita removida com sucesso!', 'type' => 'success']);
        } else {
            $this->dispatch('notify', ['message' => 'Nenhuma imagem para remover ou arquivo não encontrado.', 'type' => 'info']);
        }
    }


    // ... (métodos getAvailableStatusTransitions, saveProcessingNotes, savePrescriptionContentChanges,
    //      prepareStatusUpdate, prepareCancellation, confirmStatusUpdate, updateStatusInternal,
    //      closeStatusUpdateModal, render) permanecem como na sua última versão ou como ajustamos.
    // Certifique-se que o método savePrescriptionContentChanges para ACS
    // NÃO tente lidar com o upload de imagem, pois agora temos métodos dedicados para isso.
    // O ACS edita o texto, e a imagem é gerenciada separadamente.

    // O método savePrescriptionContentChanges da ACS deve focar apenas em:
    // - prescription_details
    // - editOrCorrectionReason (que vai para processing_notes)
    // - mudança de status para UNDER_DOCTOR_REVIEW se veio de REJECTED_BY_DOCTOR
    // A imagem, se precisasse ser alterada, usaria updateAttachedImage() ou removeAttachedImage().

    // COPIE AQUI OS MÉTODOS RESTANTES (getAvailableStatusTransitions, saveProcessingNotes, etc.)
    // DA VERSÃO ANTERIOR QUE VOCÊ ME MOSTROU E ESTAVA FUNCIONANDO BEM,
    // pois a lógica deles não muda fundamentalmente com a adição do gerenciamento de imagem.
    // Apenas certifique-se de que eles não estão tentando manipular $this->newPrescriptionImage diretamente.
    // A interação é: ACS edita texto, salva. Se precisar mudar imagem, usa os botões de imagem.

    // Vou colar aqui os métodos que você já tinha e que permanecem relevantes,
    // com a certeza de que eles não conflitam com a nova lógica de imagem.
    private function getAvailableStatusTransitions(): array { /* ... como antes ... */
        $options = [];
        $user = Auth::user();
        if (!$user) return [];

        foreach (PrescriptionStatus::cases() as $nextStatusEnum) {
            if ($nextStatusEnum === $this->prescription->status || $nextStatusEnum === PrescriptionStatus::CANCELLED) {
                continue;
            }
            if ($user->hasRole('acs') &&
                $this->prescription->status === PrescriptionStatus::REJECTED_BY_DOCTOR &&
                $nextStatusEnum === PrescriptionStatus::UNDER_DOCTOR_REVIEW) {
                continue;
            }
            if ($user->can('changeStatus', [$this->prescription, $nextStatusEnum])) {
                $options[$nextStatusEnum->value] = ($nextStatusEnum === PrescriptionStatus::REJECTED_BY_DOCTOR) ?
                    'Rejeitar Solicitação' : $nextStatusEnum->label();
            }
        }
        return $options;
    }

    public function savePrescriptionContentChanges() { /* ... como antes, mas sem tocar em imagem ... */
        if (!$this->prescription) return;
        $this->authorize('update', $this->prescription);
        $user = Auth::user();
        if (!$user || !$user->hasRole('acs') || $user->id !== $this->prescription->user_id ||
            !in_array($this->prescription->status, [PrescriptionStatus::REQUESTED, PrescriptionStatus::REJECTED_BY_DOCTOR])) {
            $this->dispatch('notify', ['message' => 'Ação não permitida ou status inválido para editar o conteúdo.', 'type' => 'error']);
            return;
        }
        $validatedData = $this->validate([
            'editablePrescriptionDetails' => 'required|string|min:10|max:2000',
            'editOrCorrectionReason' => 'nullable|string|max:500',
        ]);
        $this->prescription->prescription_details = $validatedData['editablePrescriptionDetails'];
        $noteForLog = '';
        if ($this->prescription->status === PrescriptionStatus::REQUESTED) {
            $noteForLog = "Conteúdo da solicitação editado pelo solicitante ({$user->name}) enquanto status era 'Solicitada'.";
            if (!empty(trim($validatedData['editOrCorrectionReason']))) {
                $noteForLog .= " Nota da edição: " . trim($validatedData['editOrCorrectionReason']);
            }
        } elseif ($this->prescription->status === PrescriptionStatus::REJECTED_BY_DOCTOR) {
            $this->prescription->status = PrescriptionStatus::UNDER_DOCTOR_REVIEW;
            $this->prescription->completed_at = null;
            $noteForLog = "Solicitação corrigida e reenviada para análise por {$user->name}.";
            if (!empty(trim($validatedData['editOrCorrectionReason']))) {
                $noteForLog .= " Nota da correção: " . trim($validatedData['editOrCorrectionReason']);
            }
        }
        if(!empty($noteForLog)) {
            $logPrefix = "(" . now()->format('d/m/Y H:i') . "): ";
            $existingNotes = $this->prescription->processing_notes ?? '';
            $this->prescription->processing_notes = !empty($existingNotes) ? $existingNotes . "\n---\n" . $logPrefix . $noteForLog : $logPrefix . $noteForLog;
        }
        $this->prescription->save();
        $this->prescription->refresh()->load(['citizen', 'requester', 'unit', 'doctor']);
        $this->editablePrescriptionDetails = $this->prescription->prescription_details;
        $this->editOrCorrectionReason = '';
        $this->statusOptionsForSelect = $this->getAvailableStatusTransitions();
        $message = $this->prescription->status->value === PrescriptionStatus::UNDER_DOCTOR_REVIEW->value ?
            'Solicitação corrigida e reenviada para análise!' :
            'Conteúdo da solicitação atualizado com sucesso!';
        $this->dispatch('notify', ['message' => $message, 'type' => 'success']);
    }

    public function prepareStatusUpdate(string $newStatusValue) { /* ... como antes ... */
        if (!$this->prescription) return;
        $newStatusEnum = PrescriptionStatus::from($newStatusValue);
        $this->authorize('changeStatus', [$this->prescription, $newStatusEnum]);
        $this->targetStatus = $newStatusValue;
        $this->statusUpdateReason = '';
        $this->resetErrorBag('statusUpdateReason');
        $this->modalTitle = __('Alterar Status para ') . $newStatusEnum->label();
        $this->modalConfirmationButtonText = __('Confirmar Mudança');
        if (in_array($newStatusEnum, [PrescriptionStatus::REJECTED_BY_DOCTOR, PrescriptionStatus::CANCELLED])) {
            $this->modalTitle = ($newStatusEnum === PrescriptionStatus::REJECTED_BY_DOCTOR) ?
                __('Rejeitar Solicitação de Receita') :
                __('Cancelar Solicitação de Receita');
            $this->modalConfirmationButtonText = ($newStatusEnum === PrescriptionStatus::REJECTED_BY_DOCTOR) ?
                __('Confirmar Rejeição') :
                __('Confirmar Cancelamento');
        }
        $this->showStatusUpdateModal = true;
    }
    public function prepareCancellation() { /* ... como antes ... */
        if (!$this->prescription) return;
        $this->authorize('cancel', $this->prescription);
        $this->targetStatus = PrescriptionStatus::CANCELLED->value;
        $this->statusUpdateReason = '';
        $this->resetErrorBag('statusUpdateReason');
        $this->modalTitle = __('Cancelar Solicitação de Receita');
        $this->modalConfirmationButtonText = __('Confirmar Cancelamento');
        $this->showStatusUpdateModal = true;
    }
    public function confirmStatusUpdate() { /* ... como antes ... */
        if (!$this->prescription || !$this->targetStatus) return;
        $newStatusEnum = PrescriptionStatus::from($this->targetStatus);
        if ($newStatusEnum === PrescriptionStatus::CANCELLED) {
            $this->authorize('cancel', $this->prescription);
        } else {
            $this->authorize('changeStatus', [$this->prescription, $newStatusEnum]);
        }
        if (in_array($this->targetStatus, [
            PrescriptionStatus::REJECTED_BY_DOCTOR->value,
            PrescriptionStatus::CANCELLED->value
        ])) {
            $this->validateOnly('statusUpdateReason');
        }
        $this->updateStatusInternal($newStatusEnum, trim($this->statusUpdateReason) ?: null);
        $this->closeStatusUpdateModal();
    }
    private function updateStatusInternal(PrescriptionStatus $newStatus, ?string $reasonForStatusChange) { /* ... como antes ... */
        if (in_array($this->prescription->status, [PrescriptionStatus::DELIVERED, PrescriptionStatus::CANCELLED]) &&
            $this->prescription->status !== $newStatus) {
            $this->dispatch('notify', ['message' => 'Esta solicitação já foi entregue ou cancelada e não pode ter seu status principal alterado.', 'type' => 'error']);
            return;
        }
        $currentUser = Auth::user();
        if (!$this->prescription->reviewed_at && $currentUser && $currentUser->hasRole('doctor') &&
            in_array($newStatus, [
                PrescriptionStatus::UNDER_DOCTOR_REVIEW,
                PrescriptionStatus::APPROVED_FOR_ISSUANCE,
                PrescriptionStatus::REJECTED_BY_DOCTOR,
            ])) {
            $this->prescription->reviewed_at = now();
            if(!$this->prescription->doctor_id) {
                $this->prescription->doctor_id = $currentUser->id;
            }
        }
        if ($reasonForStatusChange) {
            $userPerformingAction = $currentUser?->name ?? 'Sistema';
            $prefix = "";
            if ($newStatus === PrescriptionStatus::REJECTED_BY_DOCTOR) {
                $prefix = "Rejeitada por {$userPerformingAction} (" . now()->format('d/m/Y H:i') . "): ";
            } elseif ($newStatus === PrescriptionStatus::CANCELLED) {
                $prefix = "Cancelada por {$userPerformingAction} (" . now()->format('d/m/Y H:i') . "): ";
            }
            $newNoteEntry = $prefix . $reasonForStatusChange;
            $this->prescription->processing_notes = ($this->prescription->processing_notes ? $this->prescription->processing_notes . "\n---\n" : "") . $newNoteEntry;
        }
        $this->prescription->status = $newStatus;
        if (in_array($newStatus, [PrescriptionStatus::DELIVERED, PrescriptionStatus::CANCELLED])) {
            if (!$this->prescription->completed_at) {
                $this->prescription->completed_at = now();
            }
        } else {
            $this->prescription->completed_at = null;
        }
        $this->prescription->save();
        $this->prescription->refresh()->load(['citizen', 'requester', 'unit', 'doctor']);
        $this->statusOptionsForSelect = $this->getAvailableStatusTransitions();
        $this->dispatch('notify', ['message' => 'Status da solicitação atualizado para: ' . $newStatus->label(), 'type' => 'success']);
    }
    public function closeStatusUpdateModal() { /* ... como antes ... */
        $this->showStatusUpdateModal = false;
        $this->targetStatus = null;
        $this->statusUpdateReason = '';
        $this->resetErrorBag('statusUpdateReason');
    }

    public function render()
    {
        return view('livewire.prescriptions.edit-prescription');
    }
}
