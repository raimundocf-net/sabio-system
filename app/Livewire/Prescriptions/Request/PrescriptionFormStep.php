<?php

namespace App\Livewire\Prescriptions\Request;

// use App\Models\Citizen; // REMOVER ESTA LINHA
use App\Models\CitizenPac; // ADICIONAR ESTA LINHA (ou garantir que está usando CitizenPac consistentemente)
use App\Models\Prescription;
use App\Models\Unit;
use App\Models\User;
use App\Enums\PrescriptionStatus;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Validation\Rules\File;

#[Layout('components.layouts.app')]
class PrescriptionFormStep extends Component
{
    use WithFileUploads;

    // public Citizen $citizen; // ALTERAR
    public CitizenPac $citizen; // <<< MUDANÇA AQUI: Usar CitizenPac
    public string $pageTitle = "Solicitar Receita";

    public ?int $unit_id = null;
    public ?int $doctor_id = null;
    public string $prescriptionRequestDetails = '';
    public array $prescriptionImages = [];

    public ?string $currentUserUnitName = null;
    public \Illuminate\Database\Eloquent\Collection $doctorsList;

    protected function rules(): array
    {
        return [
            'unit_id' => 'required|integer|exists:units,id',
            'doctor_id' => 'nullable|integer|exists:users,id',
            'prescriptionRequestDetails' => 'required|string|min:5|max:2000',
            'prescriptionImages' => 'nullable|array|max:3',
            'prescriptionImages.*' => [
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:5120'
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
        // $this->citizen = Citizen::findOrFail($citizenId); // ALTERAR
        $this->citizen = CitizenPac::findOrFail($citizenId); // <<< MUDANÇA AQUI: Buscar em CitizenPac

        // O modelo CitizenPac usa 'nome_do_cidadao'
        $citizenName = $this->citizen->nome_do_cidadao ?? 'Cidadão'; // <<< AJUSTE AQUI

        $this->pageTitle = __("Solicitar Receita para: ") . $citizenName;
        $loggedInUser = Auth::user();

        if (!$loggedInUser || !$loggedInUser->unit_id) {
            $this->dispatch('notify', ['message' => 'Você precisa estar associado a uma unidade de saúde para solicitar receitas.', 'type' => 'error']);
            $this->doctorsList = collect(); // Inicializa como coleção vazia
            $this->currentUserUnitName = null;
            return; // Interrompe a execução se o usuário não tiver unidade
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
                    $image->scaleDown(width: 1200); // Redimensiona mantendo a proporção, largura máxima 1200px
                    $filename = 'rx_img_' . uniqid() . '_' . time() . '.webp'; // Nome único para o arquivo
                    $directory = 'prescription_images'; // Diretório de armazenamento
                    Storage::disk('public')->put($directory . '/' . $filename, (string) $image->toWebp(75)); // Salva como WebP com qualidade 75
                    $uploadedImagePaths[] = $directory . '/' . $filename;
                } catch (\Exception $e) {
                    $this->dispatch('notify', ['message' => 'Erro ao processar uma das imagens: ' . $e->getMessage(), 'type' => 'error']);
                    return; // Interrompe se houver erro com imagem
                }
            }
        }

        Prescription::create([
            'citizen_id' => $this->citizen->id, // citizen_id continua o mesmo
            'user_id' => Auth::id(),
            'unit_id' => $this->unit_id,
            'doctor_id' => $validatedData['doctor_id'] ?? null,
            'status' => PrescriptionStatus::REQUESTED->value,
            'prescription_details' => $validatedData['prescriptionRequestDetails'],
            'image_paths' => !empty($uploadedImagePaths) ? $uploadedImagePaths : null,
        ]);

        $this->dispatch('notify', ['message' => 'Solicitação de receita enviada com sucesso!', 'type' => 'success']);
        $this->resetFormFields();
        return $this->redirectRoute('prescriptions.index', navigate: true);
    }

    private function resetFormFields()
    {
        $this->prescriptionRequestDetails = '';
        $this->prescriptionImages = [];
        $this->doctor_id = null;
    }

    public function removeImage(int $index): void
    {
        if (isset($this->prescriptionImages[$index])) {
            array_splice($this->prescriptionImages, $index, 1);
            $this->resetValidation('prescriptionImages.' . $index);
        }
    }

    public function render()
    {
        return view('livewire.prescriptions.request.prescription-form-step');
    }
}
