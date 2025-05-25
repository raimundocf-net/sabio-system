<?php

namespace App\Livewire\Prescriptions\Request;

use App\Models\Citizen;
use App\Models\Prescription;
use App\Models\Unit;
use App\Models\User;
use App\Enums\PrescriptionStatus;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule as ValidationRule; // Alias para não conflitar com Rule do Livewire
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Validation\Rules\File; // Para validação mais detalhada de arquivos

#[Layout('components.layouts.app')]
class PrescriptionFormStep extends Component
{
    use WithFileUploads;

    public Citizen $citizen;
    public string $pageTitle = "Solicitar Receita";

    public ?int $unit_id = null;
    public ?int $doctor_id = null;
    public string $prescriptionRequestDetails = '';
    public array $prescriptionImages = []; // Alterado para array para múltiplos uploads

    public ?string $currentUserUnitName = null;
    public \Illuminate\Database\Eloquent\Collection $doctorsList;

    protected function rules(): array
    {
        return [
            'unit_id' => 'required|integer|exists:units,id',
            'doctor_id' => 'nullable|integer|exists:users,id', // Ajustado para nullable
            'prescriptionRequestDetails' => 'required|string|min:5|max:2000',
            'prescriptionImages' => 'nullable|array|max:3', // No máximo 3 arquivos
            'prescriptionImages.*' => [ // Validação para cada arquivo no array
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:5120' // 5MB por arquivo
            ],
        ];
    }

    protected array $messages = [
        'prescriptionRequestDetails.required' => 'Os detalhes da receita são obrigatórios.',
        'prescriptionImages.max' => 'Você pode anexar no máximo 3 imagens.',
        'prescriptionImages.*.image' => 'Cada arquivo deve ser uma imagem válida.',
        'prescriptionImages.*.mimes' => 'Cada imagem deve ser do tipo: jpeg, png, jpg, gif, webp.',
        'prescriptionImages.*.max' => 'Cada imagem não pode ser maior que 5MB.',
    ];

    public function mount(int $citizenId)
    {
        $this->citizen = Citizen::findOrFail($citizenId);
        $citizenName = $this->citizen->name ?? $this->citizen->nome_do_cidadao ?? 'Cidadão';
        $this->pageTitle = __("Solicitar Receita para: ") . $citizenName;
        $loggedInUser = Auth::user();
        if (!$loggedInUser || !$loggedInUser->unit_id) {
            $this->dispatch('notify', ['message' => 'Você precisa estar associado a uma unidade de saúde para solicitar receitas.', 'type' => 'error']);
            $this->doctorsList = collect();
            $this->currentUserUnitName = null;
            return;
        }
        $this->unit_id = $loggedInUser->unit_id;
        $unitModel = Unit::find($this->unit_id);
        $this->currentUserUnitName = $unitModel?->name;
        $this->doctorsList = User::where('role', 'doctor')
            //->where('unit_id', $this->unit_id) // Descomente se médicos são por unidade
            ->orderBy('name')
            ->select(['id', 'name'])
            ->get();
    }

    public function submitPrescriptionRequest()
    {
        if (!$this->unit_id || !$this->currentUserUnitName) {
            $this->dispatch('notify', ['message' => 'Não foi possível determinar sua unidade de saúde. Contate o suporte.', 'type' => 'error']);
            return;
        }

        $this->authorize('create', Prescription::class);
        $validatedData = $this->validate();

        $uploadedImagePaths = [];
        if (!empty($this->prescriptionImages)) {
            foreach ($this->prescriptionImages as $imageFile) {
                try {
                    $image = Image::read($imageFile->getRealPath());
                    $image->scaleDown(width: 1200);
                    $filename = 'rx_img_' . uniqid() . '_' . time() . '.webp';
                    $directory = 'prescription_images';
                    Storage::disk('public')->put($directory . '/' . $filename, (string) $image->toWebp(75));
                    $uploadedImagePaths[] = $directory . '/' . $filename;
                } catch (\Exception $e) {
                    $this->dispatch('notify', ['message' => 'Erro ao processar uma das imagens: ' . $e->getMessage(), 'type' => 'error']);
                    // Considerar se deve parar o processo ou continuar sem a imagem problemática
                    // Se uma imagem falhar, pode ser melhor parar e informar o usuário.
                    return;
                }
            }
        }

        Prescription::create([
            'citizen_id' => $this->citizen->id,
            'user_id' => Auth::id(),
            'unit_id' => $this->unit_id,
            'doctor_id' => $validatedData['doctor_id'] ?? null, // Usa null se não fornecido
            'status' => PrescriptionStatus::REQUESTED->value,
            'prescription_details' => $validatedData['prescriptionRequestDetails'],
            'image_paths' => !empty($uploadedImagePaths) ? $uploadedImagePaths : null, // Salva array de caminhos
        ]);

        $this->dispatch('notify', ['message' => 'Solicitação de receita enviada com sucesso!', 'type' => 'success']);
        $this->resetFormFields();
        return $this->redirectRoute('prescriptions.index', navigate: true);
    }

    private function resetFormFields()
    {
        $this->prescriptionRequestDetails = '';
        $this->prescriptionImages = []; // Reseta o array de imagens
        $this->doctor_id = null; // Reseta o médico selecionado
        // Não resetar unit_id e currentUserUnitName pois são do usuário logado
    }


    public function removeImage(int $index): void
    {
        if (isset($this->prescriptionImages[$index])) {
            // Se for um arquivo temporário do Livewire, ele será "esquecido"
            array_splice($this->prescriptionImages, $index, 1);
            // Para limpar o erro de validação específico deste item se houver:
            $this->resetValidation('prescriptionImages.' . $index);
        }
    }


    public function render()
    {
        return view('livewire.prescriptions.request.prescription-form-step');
    }
}
