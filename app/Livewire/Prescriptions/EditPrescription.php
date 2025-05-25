<?php

namespace App\Livewire\Prescriptions;

use App\Models\Prescription;
use App\Enums\PrescriptionStatus;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\Rule as ValidationRule; // Renomeado para evitar conflito
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Arr; // Para ajudar a remover elementos do array

#[Layout('components.layouts.app')]
class EditPrescription extends Component
{
    use WithFileUploads;

    public Prescription $prescription;
    public string $pageTitle = "Detalhes da Solicitação de Receita";

    public string $current_processing_notes = '';
    public string $editablePrescriptionDetails = '';
    public string $editOrCorrectionReason = '';

    // Para upload/gerenciamento de múltiplas imagens
    public array $existingImagePaths = []; // Armazenará os caminhos das imagens já salvas
    public array $newPrescriptionImages = []; // Para novos uploads (array de objetos TemporaryUploadedFile)
    public array $imagesToRemove = []; // Para marcar imagens existentes para remoção

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

        // Calcula o número de imagens que restarão após a remoção e antes de adicionar novas
        $numberOfExistingImagesAfterRemoval = count($this->existingImagePaths) - count($this->imagesToRemove);
        $maxNewImagesAllowed = 3 - $numberOfExistingImagesAfterRemoval;


        $rules = [
            'current_processing_notes' => 'nullable|string|max:2000',
            'editablePrescriptionDetails' => [
                ValidationRule::requiredIf($isAcsCorrectingOrEditing),
                'nullable',
                'string',
                'min:10',
                'max:2000',
            ],
            'editOrCorrectionReason' => 'nullable|string|max:500',
            // Validação para as novas imagens
            'newPrescriptionImages' => [
                'nullable',
                'array',
                // Valida se o total de imagens (existentes não marcadas para remoção + novas) não excede 3
                function ($attribute, $value, $fail) use ($numberOfExistingImagesAfterRemoval) {
                    if ((count($value) + $numberOfExistingImagesAfterRemoval) > 3) {
                        $fail(__('Você pode ter no máximo 3 imagens no total.'));
                    }
                },
            ],
            'newPrescriptionImages.*' => [
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:5120' // 5MB por arquivo
            ],
            'imagesToRemove' => 'nullable|array', // Para os caminhos das imagens a remover
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
        'editablePrescriptionDetails.required' => 'Os detalhes do pedido são obrigatórios.',
        'newPrescriptionImages.max' => 'Você pode anexar no máximo 3 imagens no total (considerando as já existentes).',
        'newPrescriptionImages.*.image' => 'Cada novo arquivo deve ser uma imagem válida.',
        'newPrescriptionImages.*.mimes' => 'Cada nova imagem deve ser do tipo: jpeg, png, jpg, gif, webp.',
        'newPrescriptionImages.*.max' => 'Cada nova imagem não pode ser maior que 5MB.',
        'statusUpdateReason.required' => 'O motivo é obrigatório para esta ação.',
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
        $this->existingImagePaths = $this->prescription->image_paths ?? []; // Carrega os caminhos existentes
        $this->statusOptionsForSelect = $this->getAvailableStatusTransitions();
    }

    // Método para marcar uma imagem existente para remoção
    public function markImageForRemoval(string $imagePath): void
    {
        if (!in_array($imagePath, $this->imagesToRemove)) {
            $this->imagesToRemove[] = $imagePath;
        }
    }

    // Método para desmarcar uma imagem da remoção (caso o usuário mude de ideia antes de salvar)
    public function unmarkImageForRemoval(string $imagePath): void
    {
        $this->imagesToRemove = array_filter($this->imagesToRemove, function ($path) use ($imagePath) {
            return $path !== $imagePath;
        });
    }

    // Método para remover uma imagem nova (ainda não salva) do array de upload
    public function removeNewImage(int $index): void
    {
        if (isset($this->newPrescriptionImages[$index])) {
            array_splice($this->newPrescriptionImages, $index, 1);
            // É importante resetar a validação para este item específico se houver erros associados
            $this->resetValidation('newPrescriptionImages.' . $index);
            // E também para a validação do array 'newPrescriptionImages' se ela dependia da contagem
            $this->resetValidation('newPrescriptionImages');
        }
    }


    // Atualizado para lidar com múltiplas imagens
    public function saveImages(): void
    {
        if (!$this->prescription) return;
        $this->authorize('update', $this->prescription);

        // Valida apenas os campos de imagem se houver novas imagens ou imagens marcadas para remoção
        $this->validateOnly('newPrescriptionImages');
        $this->validateOnly('newPrescriptionImages.*');
        $this->validateOnly('imagesToRemove');


        $currentImagePaths = $this->prescription->image_paths ?? [];
        $pathsToKeep = [];

        // Manter imagens existentes que não foram marcadas para remoção
        foreach ($currentImagePaths as $path) {
            if (!in_array($path, $this->imagesToRemove)) {
                $pathsToKeep[] = $path;
            }
        }

        // Deletar do storage as imagens marcadas para remoção
        foreach ($this->imagesToRemove as $pathToRemove) {
            if (Storage::disk('public')->exists($pathToRemove)) {
                Storage::disk('public')->delete($pathToRemove);
            }
        }

        // Processar e adicionar novas imagens
        $newlyUploadedPaths = [];
        if (!empty($this->newPrescriptionImages)) {
            foreach ($this->newPrescriptionImages as $imageFile) {
                try {
                    $image = Image::read($imageFile->getRealPath());
                    $image->scaleDown(width: 1200);
                    $filename = 'rx_img_' . uniqid() . '_' . time() . '.webp';
                    $directory = 'prescription_images';
                    Storage::disk('public')->put($directory . '/' . $filename, (string) $image->toWebp(75));
                    $newlyUploadedPaths[] = $directory . '/' . $filename;
                } catch (\Exception $e) {
                    $this->dispatch('notify', ['message' => 'Erro ao processar uma nova imagem: ' . $e->getMessage(), 'type' => 'error']);
                    // Se uma imagem falhar, podemos optar por não salvar nenhuma das novas
                    // e limpar $newlyUploadedPaths para não adicionar caminhos parciais.
                    // Ou remover os arquivos já salvos desta leva de uploads.
                    // Por simplicidade, aqui apenas logamos e continuamos, mas não adicionamos a imagem falha.
                    \Illuminate\Support\Facades\Log::error('Erro ao processar imagem em EditPrescription: ' . $e->getMessage());
                }
            }
        }

        // Combinar caminhos mantidos e novos caminhos
        $finalImagePaths = array_merge($pathsToKeep, $newlyUploadedPaths);

        // Limitar ao máximo de 3 imagens no total
        if (count($finalImagePaths) > 3) {
            // Se, após adicionar novas, o total exceder 3, remove as mais antigas das 'novas'
            // Ou exibe um erro. A validação no rules() já deve ter prevenido isso.
            // Para segurança, podemos truncar aqui, embora o ideal seja a validação prévia.
            $this->dispatch('notify', ['message' => 'Limite de 3 imagens excedido. Apenas as primeiras 3 foram mantidas.', 'type' => 'warning']);
            $finalImagePaths = array_slice($finalImagePaths, 0, 3);
        }


        $this->prescription->image_paths = !empty($finalImagePaths) ? $finalImagePaths : null;
        $this->prescription->save();
        $this->prescription->refresh();

        $this->existingImagePaths = $this->prescription->image_paths ?? [];
        $this->newPrescriptionImages = []; // Limpa o array de upload
        $this->imagesToRemove = []; // Limpa o array de remoção

        $this->dispatch('notify', ['message' => 'Imagens da receita atualizadas com sucesso!', 'type' => 'success']);
    }


    // Os métodos abaixo (getAvailableStatusTransitions, saveProcessingNotes, savePrescriptionContentChanges, etc.)
    // permanecem os mesmos da sua última versão, pois a lógica deles não é diretamente afetada
    // pela forma como as imagens são gerenciadas, exceto que `savePrescriptionContentChanges`
    // não deve mais se preocupar com o campo `image_path`.
    // ... (cole os outros métodos aqui) ...
    private function getAvailableStatusTransitions(): array {
        $options = [];
        $user = Auth::user();
        if (!$user) return [];

        foreach (PrescriptionStatus::cases() as $nextStatusEnum) {
            if ($nextStatusEnum === $this->prescription->status || $nextStatusEnum === PrescriptionStatus::CANCELLED) {
                continue;
            }
            // ACS só pode reenviar se foi rejeitado, não pode escolher outros status diretamente.
            if ($user->hasRole('acs') &&
                $this->prescription->status === PrescriptionStatus::REJECTED_BY_DOCTOR &&
                $nextStatusEnum === PrescriptionStatus::UNDER_DOCTOR_REVIEW) {
                // Esta transição é feita pelo savePrescriptionContentChanges, não pelo seletor de status.
                continue;
            }
            if ($user->can('changeStatus', [$this->prescription, $nextStatusEnum])) {
                $options[$nextStatusEnum->value] = ($nextStatusEnum === PrescriptionStatus::REJECTED_BY_DOCTOR) ?
                    'Rejeitar Solicitação' : $nextStatusEnum->label();
            }
        }
        return $options;
    }

    public function savePrescriptionContentChanges() {
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
            $this->prescription->completed_at = null; // Reseta data de conclusão se estava rejeitada
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
        $this->editOrCorrectionReason = ''; // Limpa o campo após salvar
        $this->statusOptionsForSelect = $this->getAvailableStatusTransitions(); // Atualiza opções de status
        $message = $this->prescription->status->value === PrescriptionStatus::UNDER_DOCTOR_REVIEW->value ?
            'Solicitação corrigida e reenviada para análise!' :
            'Conteúdo da solicitação atualizado com sucesso!';
        $this->dispatch('notify', ['message' => $message, 'type' => 'success']);
    }

    public function saveProcessingNotes(): void
    {
        if (!$this->prescription) return;
        $this->authorize('addProcessingNote', $this->prescription); // Usando a nova action da policy

        $this->validate(['current_processing_notes' => 'required|string|min:5|max:2000']);

        $userPerformingAction = Auth::user()?->name ?? 'Sistema';
        $newNoteEntry = "(" . now()->format('d/m/Y H:i') . ") Nota de {$userPerformingAction}: " . trim($this->current_processing_notes);
        $existingNotes = $this->prescription->processing_notes ?? '';
        $this->prescription->processing_notes = !empty($existingNotes) ? $existingNotes . "\n---\n" . $newNoteEntry : $newNoteEntry;
        $this->prescription->save();
        $this->prescription->refresh(); // Recarrega para exibir a nota
        $this->current_processing_notes = ''; // Limpa o campo
        $this->dispatch('notify', ['message' => 'Nota de processamento adicionada com sucesso!', 'type' => 'success']);
    }

    public function prepareStatusUpdate(string $newStatusValue) {
        if (!$this->prescription) return;
        $newStatusEnum = PrescriptionStatus::from($newStatusValue); // Converte string para Enum
        $this->authorize('changeStatus', [$this->prescription, $newStatusEnum]);
        $this->targetStatus = $newStatusValue;
        $this->statusUpdateReason = ''; // Limpa o motivo anterior
        $this->resetErrorBag('statusUpdateReason'); // Limpa erros de validação do motivo
        $this->modalTitle = __('Alterar Status para ') . $newStatusEnum->label();
        $this->modalConfirmationButtonText = __('Confirmar Mudança');
        // Se o novo status requer um motivo (Rejeitada ou Cancelada)
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

    public function prepareCancellation() {
        if (!$this->prescription) return;
        $this->authorize('cancel', $this->prescription);
        $this->targetStatus = PrescriptionStatus::CANCELLED->value;
        $this->statusUpdateReason = '';
        $this->resetErrorBag('statusUpdateReason');
        $this->modalTitle = __('Cancelar Solicitação de Receita');
        $this->modalConfirmationButtonText = __('Confirmar Cancelamento');
        $this->showStatusUpdateModal = true;
    }

    public function confirmStatusUpdate() {
        if (!$this->prescription || !$this->targetStatus) return;
        $newStatusEnum = PrescriptionStatus::from($this->targetStatus);
        // Autorização específica para cancelamento
        if ($newStatusEnum === PrescriptionStatus::CANCELLED) {
            $this->authorize('cancel', $this->prescription);
        } else {
            $this->authorize('changeStatus', [$this->prescription, $newStatusEnum]);
        }
        // Valida o motivo apenas se for necessário para o status alvo
        if (in_array($this->targetStatus, [
            PrescriptionStatus::REJECTED_BY_DOCTOR->value,
            PrescriptionStatus::CANCELLED->value
        ])) {
            $this->validateOnly('statusUpdateReason'); // Valida apenas o motivo
        }
        $this->updateStatusInternal($newStatusEnum, trim($this->statusUpdateReason) ?: null);
        $this->closeStatusUpdateModal();
    }

    private function updateStatusInternal(PrescriptionStatus $newStatus, ?string $reasonForStatusChange) {
        // Segurança: Verifica se a prescrição já está num estado final
        if (in_array($this->prescription->status, [PrescriptionStatus::DELIVERED, PrescriptionStatus::CANCELLED]) &&
            $this->prescription->status !== $newStatus) { // Permite "cancelar" uma entregue se a lógica de negócio mudar
            $this->dispatch('notify', ['message' => 'Esta solicitação já foi entregue ou cancelada e não pode ter seu status principal alterado.', 'type' => 'error']);
            return;
        }

        $currentUser = Auth::user();

        // Atualiza reviewed_at e doctor_id se um médico estiver atuando pela primeira vez
        if (!$this->prescription->reviewed_at && $currentUser && $currentUser->hasRole('doctor') &&
            in_array($newStatus, [
                PrescriptionStatus::UNDER_DOCTOR_REVIEW,
                PrescriptionStatus::APPROVED_FOR_ISSUANCE,
                PrescriptionStatus::REJECTED_BY_DOCTOR,
            ])) {
            $this->prescription->reviewed_at = now();
            if(!$this->prescription->doctor_id) { // Só define o médico se não houver um já atribuído
                $this->prescription->doctor_id = $currentUser->id;
            }
        }

        // Adiciona o motivo/nota ao histórico de notas de processamento
        if ($reasonForStatusChange) {
            $userPerformingAction = $currentUser?->name ?? 'Sistema';
            $prefix = ""; // Para contextualizar a nota
            if ($newStatus === PrescriptionStatus::REJECTED_BY_DOCTOR) {
                $prefix = "Rejeitada por {$userPerformingAction} (" . now()->format('d/m/Y H:i') . "): ";
            } elseif ($newStatus === PrescriptionStatus::CANCELLED) {
                $prefix = "Cancelada por {$userPerformingAction} (" . now()->format('d/m/Y H:i') . "): ";
            }
            // Outros status podem adicionar notas mais genéricas se $reasonForStatusChange for usado para eles
            // Se não, o prefixo será vazio e apenas a $reasonForStatusChange será adicionada.
            $newNoteEntry = $prefix . $reasonForStatusChange;

            $existingNotes = $this->prescription->processing_notes ?? '';
            $this->prescription->processing_notes = !empty($existingNotes) ? $existingNotes . "\n---\n" . $newNoteEntry : $newNoteEntry;
        }

        $this->prescription->status = $newStatus; // Eloquent lida com ->value

        // Define ou limpa completed_at
        if (in_array($newStatus, [PrescriptionStatus::DELIVERED, PrescriptionStatus::CANCELLED])) {
            if (!$this->prescription->completed_at) { // Só define se não estiver já preenchido
                $this->prescription->completed_at = now();
            }
        } else {
            $this->prescription->completed_at = null; // Limpa se não for um status final
        }

        $this->prescription->save();
        $this->prescription->refresh()->load(['citizen', 'requester', 'unit', 'doctor']); // Recarrega relações
        $this->statusOptionsForSelect = $this->getAvailableStatusTransitions(); // Atualiza opções do select
        $this->dispatch('notify', ['message' => 'Status da solicitação atualizado para: ' . $newStatus->label(), 'type' => 'success']);
    }

    public function closeStatusUpdateModal() {
        $this->showStatusUpdateModal = false;
        $this->targetStatus = null;
        $this->statusUpdateReason = '';
        $this->resetErrorBag('statusUpdateReason'); // Limpa apenas o erro do motivo
    }


    public function render()
    {
        return view('livewire.prescriptions.edit-prescription');
    }
}
