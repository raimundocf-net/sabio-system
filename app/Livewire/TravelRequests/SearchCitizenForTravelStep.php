<?php

namespace App\Livewire\TravelRequests;

use App\Models\Citizen;
use App\Models\TravelRequest;
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

#[Layout('components.layouts.app')]
class SearchCitizenForTravelStep extends Component // Nome da classe alterado
{
    use WithFileUploads;

    public Citizen $selectedCitizen; // Propriedade para receber o cidadão
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
        'departure_location' => '',
        'appointment_datetime' => '',
        'desired_departure_datetime' => '',
        'desired_return_datetime' => '',
        'referral_document_path' => null,
        'number_of_passengers' => 1,
        'observations' => '',
    ];

    public $referralDocumentFile;
    public array $procedureTypeOptions = [];
    public array $stateOptions = [ /* ... seus estados ... */
        'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
        'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
        'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
        'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
        'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins',
    ];


    // Rules permanecem as mesmas, mas citizen_id não será mais parte da busca aqui
    protected function rules(): array
    {
        return [
            // 'form.citizen_id' não é mais validado aqui, pois é injetado
            'form.needs_companion' => 'required|boolean',
            'form.companion_name' => 'nullable|required_if:form.needs_companion,true|string|max:255',
            'form.companion_cpf' => 'nullable|string|max:14',
            'form.destination_address' => 'required|string|max:255',
            'form.destination_city' => 'required|string|max:150',
            'form.destination_state' => 'required|string|size:2',
            'form.reason' => 'required|string|max:1000',
            'form.procedure_type' => ['required', new EnumRule(ProcedureType::class)],
            'form.departure_location' => 'required|string|max:255',
            'form.appointment_datetime' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'form.desired_departure_datetime' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:now|before_or_equal:form.appointment_datetime',
            'form.desired_return_datetime' => 'nullable|date_format:Y-m-d\TH:i|after:form.appointment_datetime',
            'referralDocumentFile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'form.number_of_passengers' => 'required|integer|min:1|max:50',
            'form.observations' => 'nullable|string|max:2000',
        ];
    }

    protected function messages(): array
    {
        // As mensagens de validação permanecem as mesmas
        return [
            'form.companion_name.required_if' => __('O nome do acompanhante é obrigatório se "Precisa de Acompanhante" estiver marcado.'),
            // ...
        ];
    }

    public function mount(Citizen $citizen): void // Recebe Citizen por Route Model Binding
    {
        try {
            $this->authorize('create', TravelRequest::class);
        } catch (AuthorizationException $e) {
            session()->flash('error', __('Você não tem permissão para criar solicitações de viagem.'));
            $this->redirectRoute('dashboard', navigate: true);
            return;
        }

        $this->selectedCitizen = $citizen;
        $this->form['citizen_id'] = $this->selectedCitizen->id;
        $this->pageTitle = "Nova Solicitação para: " . $this->selectedCitizen->name;
        $this->procedureTypeOptions = ProcedureType::options();
        $this->form['availability_status'] = TravelRequestStatus::PENDING_ASSIGNMENT->value; // Default status
        $this->updatedFormNeedsCompanion($this->form['needs_companion']);
    }

    #[On('updated-form-needs-companion')]
    public function updatedFormNeedsCompanion($value): void
    {
        $this->form['needs_companion'] = (bool) $value;
        if ($this->form['needs_companion']) {
            if ($this->form['number_of_passengers'] < 2) {
                $this->form['number_of_passengers'] = 2;
            }
        } else {
            $this->form['number_of_passengers'] = 1;
            $this->form['companion_name'] = '';
            $this->form['companion_cpf'] = '';
        }
    }


    public function save(): void
    {
        $this->authorize('create', TravelRequest::class);
        $this->validate();
        $formDataToSave = $this->form;

        if ($this->referralDocumentFile) {
            try {
                $image = Image::read($this->referralDocumentFile->getRealPath());
                $image->scaleDown(width: 1200);
                $filename = 'referral_doc_' . $this->form['citizen_id'] . '_' . uniqid() . '.webp';
                $directory = 'travel_request_referrals';
                Storage::disk('public')->put($directory . '/' . $filename, (string) $image->toWebp(75));
                $formDataToSave['referral_document_path'] = $directory . '/' . $filename;
            } catch (\Exception $e) {
                session()->flash('error', __('Erro ao processar o arquivo da guia: ') . $e->getMessage());
                return;
            }
        }

        $formDataToSave['requester_id'] = Auth::id();
        $formDataToSave['status'] = TravelRequestStatus::PENDING_ASSIGNMENT->value;
        $formDataToSave['appointment_datetime'] = Carbon::parse($formDataToSave['appointment_datetime'])->toDateTimeString();
        $formDataToSave['desired_departure_datetime'] = !empty($formDataToSave['desired_departure_datetime']) ? Carbon::parse($formDataToSave['desired_departure_datetime'])->toDateTimeString() : null;
        $formDataToSave['desired_return_datetime'] = !empty($formDataToSave['desired_return_datetime']) ? Carbon::parse($formDataToSave['desired_return_datetime'])->toDateTimeString() : null;


        TravelRequest::create($formDataToSave);
        session()->flash('status', __('Solicitação de viagem registrada com sucesso!'));
        $this->redirectRoute('travel-requests.index', navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('travel-requests.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.travel-requests.travel-request-form-step'); // View alterada
    }
}
