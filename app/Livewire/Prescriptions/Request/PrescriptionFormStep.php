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
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage; // Importar Storage
// Importar a facade da Intervention Image (verifique o namespace correto para a versão instalada)
// Para Intervention Image 3.x com o pacote laravel:
use Intervention\Image\Laravel\Facades\Image;
// Ou se estiver usando o ImageManager diretamente:
// use Intervention\Image\ImageManager;
// use Intervention\Image\Drivers\Gd\Driver; // Exemplo para driver GD

#[Layout('components.layouts.app')]
class PrescriptionFormStep extends Component
{
    use WithFileUploads;

    public Citizen $citizen;
    public string $pageTitle = "Solicitar Receita";

    public ?int $unit_id = null;
    public ?int $doctor_id = null;
    public string $prescriptionRequestDetails = '';
    public $prescriptionImage;

    public ?string $currentUserUnitName = null;
    public \Illuminate\Database\Eloquent\Collection $doctorsList;

    protected function rules(): array
    {
        return [
            'unit_id' => 'required|integer|exists:units,id',
            'doctor_id' => [ /* ... suas regras ... */ ],
            'prescriptionRequestDetails' => 'required|string|min:5|max:2000',
            // A validação de 'max' aqui é para o arquivo original enviado pelo usuário.
            // Se for muito grande (ex: 10MB), pode falhar antes mesmo do processamento.
            // Ajuste conforme os limites do seu servidor PHP (upload_max_filesize, post_max_size).
            'prescriptionImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Ex: max 5MB para o upload inicial
        ];
    }

    protected array $messages = [
        // ... suas mensagens ...
        'prescriptionImage.image' => 'O arquivo enviado deve ser uma imagem válida.',
        'prescriptionImage.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg, gif, webp.',
        'prescriptionImage.max' => 'A imagem original não pode ser maior que 5MB.',
    ];

    public function mount(int $citizenId)
    {
        // ... (seu código mount como antes) ...
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
            ->where('unit_id', $this->unit_id)
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

        $imagePath = null;
        if ($this->prescriptionImage) {
            try {
                // Lê a imagem do arquivo temporário carregado pelo Livewire
                $image = Image::read($this->prescriptionImage->getRealPath());

                // 1. Redimensionar (opcional, mas recomendado)
                // Exemplo: Redimensionar para uma largura máxima de 1200px, mantendo a proporção.
                // O método 'cover' preenche as dimensões e corta o excesso.
                // Para apenas redimensionar proporcionalmente: ->scaleDown(width: 1200)
                $image->scaleDown(width: 1200); // Mantém proporção, só reduz se for maior

                // 2. Otimizar e Codificar
                // Gera um nome de arquivo único com a extensão original (ou a desejada)
                // Para WebP (ótima compressão e qualidade):
                $filename = 'rx_img_' . uniqid() . '_' . time() . '.webp';
                $encodedImage = $image->toWebp(75); // Salva como WebP com 75% de qualidade

                // Ou para JPEG:
                // $filename = 'rx_img_' . uniqid() . '_' . time() . '.jpg';
                // $encodedImage = $image->toJpeg(75); // Qualidade 75%

                // Ou para PNG (compressão sem perdas, pode resultar em arquivos maiores que WebP/JPEG para fotos):
                // $filename = 'rx_img_' . uniqid() . '_' . time() . '.png';
                // $encodedImage = $image->toPng()->reduceColors(); // Exemplo de otimização PNG

                // 3. Salvar a Imagem Processada
                $directory = 'prescription_images'; // Diretório dentro de 'storage/app/public/'
                Storage::disk('public')->put($directory . '/' . $filename, (string) $encodedImage);
                $imagePath = $directory . '/' . $filename;

            } catch (\Exception $e) {
                // Lidar com erro no processamento da imagem
                $this->dispatch('notify', ['message' => 'Erro ao processar a imagem anexada: ' . $e->getMessage(), 'type' => 'error']);
                // Você pode optar por continuar sem a imagem ou impedir o envio
                // return; // Impede o envio se a imagem for crucial e falhar
                $imagePath = null; // Garante que imagePath seja nulo se o processamento falhar
            }
        }

        Prescription::create([
            'citizen_id' => $this->citizen->id,
            'user_id' => Auth::id(),
            'unit_id' => $this->unit_id,
            'doctor_id' => $validatedData['doctor_id'],
            'status' => PrescriptionStatus::REQUESTED->value,
            'prescription_details' => $validatedData['prescriptionRequestDetails'],
            'image_path' => $imagePath, // Salva o caminho da imagem processada
        ]);

        $this->dispatch('notify', ['message' => 'Solicitação de receita enviada com sucesso!', 'type' => 'success']);
        $this->prescriptionImage = null; // Limpa o campo de upload
        $this->reset(); // Reseta todos os campos do formulário para o estado inicial
        return $this->redirectRoute('prescriptions.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.prescriptions.request.prescription-form-step');
    }
}
