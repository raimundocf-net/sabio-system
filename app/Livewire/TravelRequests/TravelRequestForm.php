<?php

namespace App\Livewire\TravelRequests;

use App\Models\Citizen;
use App\Models\TravelRequest;
use App\Models\BoardingLocation; // Adicionado
use App\Enums\ProcedureType;
use App\Enums\TravelRequestStatus;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Enum as EnumRule;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Attributes\On;
use Illuminate\Support\Collection; // Adicionado

#[Layout('components.layouts.app')]
class TravelRequestForm extends Component
{
    use WithFileUploads;

    public Citizen $selectedCitizen;
    public string $pageTitle = "";

    public array $form = [
        'citizen_id' => null,
        'needs_companion' => false,
        'companion_name' => '',
        'companion_cpf' => '',
        'destination_address' => '',
        'destination_city' => '',
        'destination_state' => '',
        'reason' => '',
        'procedure_type' => '',
        'departure_location' => '', // Este campo será usado pelo select, armazenando o NOME do local
        'appointment_datetime' => '',
        'desired_departure_datetime' => '',
        'desired_return_datetime' => '',
        'referral_document_path' => null,
        'number_of_passengers' => 1,
        'observations' => '',
    ];

    public $referralDocumentFile;

    public array $procedureTypeOptions = [];
    public array $stateOptions = [
        'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
        'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
        'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
        'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
        'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins',
    ];

    // Novas propriedades para o modal de Local de Embarque
    public Collection $boardingLocations; // Para popular o select
    public bool $showAddBoardingLocationModal = false;
    public string $newBoardingLocationName = '';
    public ?string $newBoardingLocationAddress = null; // Endereço é opcional

    protected function rules(): array
    {
        $rules = [ // Adicionando as regras existentes
            'form.citizen_id' => 'required|integer|exists:citizens,id',
            'form.needs_companion' => 'required|boolean',
            'form.companion_name' => 'nullable|required_if:form.needs_companion,true|string|max:255',
            'form.companion_cpf' => 'nullable|string|max:14',
            'form.destination_address' => 'required|string|max:255',
            'form.destination_city' => 'required|string|max:150',
            'form.destination_state' => 'required|string|size:2',
            'form.reason' => 'required|string|max:1000',
            'form.procedure_type' => ['required', new EnumRule(ProcedureType::class)],
            'form.departure_location' => 'required|string|max:255', // O select agora irá popular este campo
            'form.appointment_datetime' => 'required|date_format:Y-m-d\TH:i|after_or_equal:' . now()->format('Y-m-d\TH:i'),
            'form.desired_departure_datetime' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:now|before_or_equal:form.appointment_datetime',
            'form.desired_return_datetime' => 'nullable|date_format:Y-m-d\TH:i|after:form.appointment_datetime',
            'referralDocumentFile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'form.number_of_passengers' => 'required|integer|min:1|max:50',
            'form.observations' => 'nullable|string|max:2000',
        ];

        // Regras para o modal de novo local de embarque
        if ($this->showAddBoardingLocationModal) {
            $rules['newBoardingLocationName'] = 'required|string|max:191|unique:boarding_locations,name';
            $rules['newBoardingLocationAddress'] = 'nullable|string|max:255';
        }
        return $rules;
    }

    protected function messages(): array
    {
        return [
            'form.citizen_id.required' => __('O cidadão é obrigatório. Se não estiver selecionado, volte à busca.'),
            'form.companion_name.required_if' => __('O nome do acompanhante é obrigatório se "Precisa de Acompanhante" estiver marcado.'),
            'form.appointment_datetime.after_or_equal' => __('A data/hora do compromisso não pode ser no passado.'),
            'form.desired_departure_datetime.before_or_equal' => __('A data/hora desejada de saída deve ser anterior ou igual à data/hora do compromisso.'),
            'form.desired_return_datetime.after' => __('A data/hora desejada de retorno deve ser posterior à data/hora do compromisso.'),
            'referralDocumentFile.image' => __('O arquivo da guia deve ser uma imagem (JPEG, PNG, JPG, GIF, WEBP).'),
            'referralDocumentFile.mimes' => __('Formato de imagem inválido. Use JPEG, PNG, JPG, GIF ou WEBP.'),
            'referralDocumentFile.max' => __('A imagem da guia não pode ser maior que 5MB.'),
            'form.departure_location.required' => __('O local de embarque é obrigatório.'),

            // Mensagens para o modal
            'newBoardingLocationName.required' => 'O nome do novo local de embarque é obrigatório.',
            'newBoardingLocationName.unique' => 'Este nome de local de embarque já existe.',
        ];
    }

    public function mount(Citizen $citizen): void
    {
        try {
            $this->authorize('create', TravelRequest::class);
        } catch (AuthorizationException $e) {
            session()->flash('error', __('Você não tem permissão para criar solicitações de viagem.'));
            $this->redirectRoute('travel-requests.index', navigate: true);
            return;
        }

        $this->selectedCitizen = $citizen;
        $this->form['citizen_id'] = $this->selectedCitizen->id;
        $this->pageTitle = "Nova Solicitação para: " . $this->selectedCitizen->name;
        $this->procedureTypeOptions = ProcedureType::options();
        $this->form['appointment_datetime'] = now()->addDay()->setHour(8)->setMinute(0)->format('Y-m-d\TH:i');
        $this->updatedFormNeedsCompanion($this->form['needs_companion']);

        $this->loadBoardingLocations(); // Carrega os locais de embarque
    }

    public function loadBoardingLocations(): void
    {
        $this->boardingLocations = BoardingLocation::where('is_active', true)->orderBy('name')->get(['id', 'name']);
    }

    #[On('updated-form-needs-companion')]
    public function updatedFormNeedsCompanion($value): void
    {
        $this->form['needs_companion'] = (bool) $value;
        if ($this->form['needs_companion']) {
            $this->form['number_of_passengers'] = max(2, (int) $this->form['number_of_passengers']);
        } else {
            $this->form['number_of_passengers'] = 1;
            $this->form['companion_name'] = '';
            $this->form['companion_cpf'] = '';
        }
    }

    public function openAddBoardingLocationModal(): void
    {
        $this->resetErrorBag(['newBoardingLocationName', 'newBoardingLocationAddress']);
        $this->newBoardingLocationName = '';
        $this->newBoardingLocationAddress = null;
        $this->showAddBoardingLocationModal = true;
    }

    public function closeAddBoardingLocationModal(): void
    {
        $this->showAddBoardingLocationModal = false;
        $this->resetErrorBag(['newBoardingLocationName', 'newBoardingLocationAddress']);
        $this->newBoardingLocationName = '';
        $this->newBoardingLocationAddress = null;
    }

    public function saveNewBoardingLocation(): void
    {
        $this->validate([
            'newBoardingLocationName' => 'required|string|max:191|unique:boarding_locations,name',
            'newBoardingLocationAddress' => 'nullable|string|max:255',
        ]);

        $newLocation = BoardingLocation::create([
            'name' => $this->newBoardingLocationName,
            'address' => $this->newBoardingLocationAddress,
            'is_active' => true, // Por padrão, ativo
        ]);

        $this->loadBoardingLocations(); // Recarrega a lista
        $this->form['departure_location'] = $newLocation->name; // Seleciona o nome do novo local

        $this->closeAddBoardingLocationModal();
        $this->dispatch('notify', ['message' => __('Novo local de embarque adicionado com sucesso!'), 'type' => 'success']);
    }


    public function save(): void
    {
        $this->authorize('create', TravelRequest::class);
        $validatedData = $this->validate();
        $formDataToSave = $validatedData['form'];

        if ($this->referralDocumentFile) {
            try {
                $image = Image::read($this->referralDocumentFile->getRealPath());
                $image->scaleDown(width: 1200);
                $filename = 'referral_doc_' . $formDataToSave['citizen_id'] . '_' . uniqid() . '.webp';
                $directory = 'travel_request_referrals';
                Storage::disk('public')->put($directory . '/' . $filename, (string) $image->toWebp(75));
                $formDataToSave['referral_document_path'] = $directory . '/' . $filename;
            } catch (\Exception $e) {
                session()->flash('error', __('Erro ao processar o arquivo da guia: ') . $e->getMessage());
                \Illuminate\Support\Facades\Log::error('Erro upload guia viagem: ' . $e->getMessage());
                return;
            }
        }

        $formDataToSave['requester_id'] = Auth::id();
        $formDataToSave['status'] = TravelRequestStatus::PENDING_ASSIGNMENT->value;
        $formDataToSave['appointment_datetime'] = Carbon::parse($formDataToSave['appointment_datetime'])->toDateTimeString();
        $formDataToSave['desired_departure_datetime'] = !empty($formDataToSave['desired_departure_datetime'])
            ? Carbon::parse($formDataToSave['desired_departure_datetime'])->toDateTimeString()
            : null;
        $formDataToSave['desired_return_datetime'] = !empty($formDataToSave['desired_return_datetime'])
            ? Carbon::parse($formDataToSave['desired_return_datetime'])->toDateTimeString()
            : null;

        TravelRequest::create($formDataToSave);
        session()->flash('status', __('Solicitação de viagem registrada com sucesso!'));
        $this->redirectRoute('travel-requests.index', navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('travel-requests.create.search-citizen', navigate: true);
    }

    public function render()
    {
        return view('livewire.travel-requests.travel-request-form');
    }
}
