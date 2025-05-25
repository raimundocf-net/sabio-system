<?php

namespace App\Livewire\TravelRequests;

use App\Models\TravelRequest;
use App\Models\Citizen; // Necessário para o type hint da instância do cidadão
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
class EditTravelRequest extends Component
{
    use WithFileUploads;

    // A instância será injetada automaticamente pelo Livewire (Route Model Binding)
    public TravelRequest $travelRequestInstance; // Renomeado para evitar conflito de nome com o Model

    public string $pageTitle = "";

    public array $form = [
        'citizen_id' => null, // Será preenchido mas não editável diretamente aqui
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
        'referral_document_path' => null, // Caminho da imagem existente
        'number_of_passengers' => 1,
        'observations' => '',
        'status' => '', // Status atual, pode ser editável dependendo das permissões
    ];

    public $referralDocumentFile; // Para novo upload de arquivo

    public array $procedureTypeOptions = [];
    public array $stateOptions = []; // Definido no mount
    public array $statusOptionsForUpdate = []; // Status que o usuário pode mudar

    protected function rules(): array
    {
        // As regras de validação são muito similares às de TravelRequestFormStep,
        // mas campos `unique` não precisam ser tratados de forma especial aqui,
        // pois não estamos mudando chaves únicas da TravelRequest em si.
        return [
            'form.needs_companion' => 'required|boolean',
            'form.companion_name' => 'nullable|required_if:form.needs_companion,true|string|max:255',
            'form.companion_cpf' => 'nullable|string|max:14',
            'form.destination_address' => 'required|string|max:255',
            'form.destination_city' => 'required|string|max:150',
            'form.destination_state' => 'required|string|size:2',
            'form.reason' => 'required|string|max:1000',
            'form.procedure_type' => ['required', new EnumRule(ProcedureType::class)],
            'form.departure_location' => 'required|string|max:255',
            'form.appointment_datetime' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now', // Pode ser ajustado se a viagem já passou
            'form.desired_departure_datetime' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:now|before_or_equal:form.appointment_datetime',
            'form.desired_return_datetime' => 'nullable|date_format:Y-m-d\TH:i|after:form.appointment_datetime',
            'referralDocumentFile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'form.number_of_passengers' => 'required|integer|min:1|max:50',
            'form.observations' => 'nullable|string|max:2000',
            'form.status' => ['required', new EnumRule(TravelRequestStatus::class)], // Se o status for editável
        ];
    }

    protected function messages(): array
    {
        // Mensagens similares às de TravelRequestFormStep
        return [
            'form.companion_name.required_if' => __('O nome do acompanhante é obrigatório se "Precisa de Acompanhante" estiver marcado.'),
            // ... outras mensagens ...
        ];
    }

    public function mount(TravelRequest $travelRequest): void // Injeção do Modelo
    {
        $this->travelRequestInstance = $travelRequest->load('citizen'); // Carrega o cidadão relacionado

        try {
            $this->authorize('update', $this->travelRequestInstance);
        } catch (AuthorizationException $e) {
            session()->flash('error', __('Você não tem permissão para editar esta solicitação.'));
            $this->redirectRoute('travel-requests.index', navigate: true);
            return;
        }

        $this->procedureTypeOptions = ProcedureType::options();
        $this->stateOptions = (new TravelRequestFormStep())->stateOptions; // Reutiliza as opções de estado
        $this->statusOptionsForUpdate = $this->getAvailableStatusTransitions();


        $this->form['citizen_id'] = $this->travelRequestInstance->citizen_id; // Já está definido
        $this->form['needs_companion'] = (bool) $this->travelRequestInstance->needs_companion;
        $this->form['companion_name'] = $this->travelRequestInstance->companion_name;
        $this->form['companion_cpf'] = $this->travelRequestInstance->companion_cpf;
        $this->form['destination_address'] = $this->travelRequestInstance->destination_address;
        $this->form['destination_city'] = $this->travelRequestInstance->destination_city;
        $this->form['destination_state'] = $this->travelRequestInstance->destination_state;
        $this->form['reason'] = $this->travelRequestInstance->reason;
        $this->form['procedure_type'] = $this->travelRequestInstance->procedure_type instanceof ProcedureType
            ? $this->travelRequestInstance->procedure_type->value
            : $this->travelRequestInstance->procedure_type;
        $this->form['departure_location'] = $this->travelRequestInstance->departure_location;
        $this->form['appointment_datetime'] = $this->travelRequestInstance->appointment_datetime
            ? Carbon::parse($this->travelRequestInstance->appointment_datetime)->format('Y-m-d\TH:i')
            : null;
        $this->form['desired_departure_datetime'] = $this->travelRequestInstance->desired_departure_datetime
            ? Carbon::parse($this->travelRequestInstance->desired_departure_datetime)->format('Y-m-d\TH:i')
            : null;
        $this->form['desired_return_datetime'] = $this->travelRequestInstance->desired_return_datetime
            ? Carbon::parse($this->travelRequestInstance->desired_return_datetime)->format('Y-m-d\TH:i')
            : null;
        $this->form['referral_document_path'] = $this->travelRequestInstance->referral_document_path; // Apenas o caminho
        $this->form['number_of_passengers'] = $this->travelRequestInstance->number_of_passengers;
        $this->form['observations'] = $this->travelRequestInstance->observations;
        $this->form['status'] = $this->travelRequestInstance->status instanceof TravelRequestStatus
            ? $this->travelRequestInstance->status->value
            : $this->travelRequestInstance->status;


        $this->pageTitle = __('Editar Solicitação de Viagem #') . $this->travelRequestInstance->id .
            ($this->travelRequestInstance->citizen ? ' - ' . $this->travelRequestInstance->citizen->name : '');

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
        $this->authorize('update', $this->travelRequestInstance);
        $validatedData = $this->validate();
        $formDataToSave = $validatedData['form'];

        // Lida com upload de novo arquivo, se houver
        if ($this->referralDocumentFile) {
            // Deleta o arquivo antigo, se existir
            if ($this->travelRequestInstance->referral_document_path && Storage::disk('public')->exists($this->travelRequestInstance->referral_document_path)) {
                Storage::disk('public')->delete($this->travelRequestInstance->referral_document_path);
            }
            try {
                $image = Image::read($this->referralDocumentFile->getRealPath());
                $image->scaleDown(width: 1200);
                $filename = 'referral_doc_' . $this->form['citizen_id'] . '_' . uniqid() . '.webp';
                $directory = 'travel_request_referrals';
                Storage::disk('public')->put($directory . '/' . $filename, (string) $image->toWebp(75));
                $formDataToSave['referral_document_path'] = $directory . '/' . $filename;
            } catch (\Exception $e) {
                session()->flash('error', __('Erro ao processar o novo arquivo da guia: ') . $e->getMessage());
                return;
            }
        } else {
            // Se não houver novo arquivo, mantém o caminho existente (já está em $formDataToSave['referral_document_path'] do mount)
            // ou define como null se foi removido.
            $formDataToSave['referral_document_path'] = $this->form['referral_document_path'];
        }


        $formDataToSave['is_pwd_accessible'] = filter_var($formDataToSave['is_pwd_accessible'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $formDataToSave['appointment_datetime'] = Carbon::parse($formDataToSave['appointment_datetime'])->toDateTimeString();
        $formDataToSave['desired_departure_datetime'] = !empty($formDataToSave['desired_departure_datetime']) ? Carbon::parse($formDataToSave['desired_departure_datetime'])->toDateTimeString() : null;
        $formDataToSave['desired_return_datetime'] = !empty($formDataToSave['desired_return_datetime']) ? Carbon::parse($formDataToSave['desired_return_datetime'])->toDateTimeString() : null;


        $this->travelRequestInstance->update($formDataToSave);
        session()->flash('status', __('Solicitação de viagem atualizada com sucesso!'));
        $this->redirectRoute('travel-requests.index', navigate: true);
    }

    public function removeReferralDocument()
    {
        $this->authorize('update', $this->travelRequestInstance); // Ou uma permissão mais específica
        if ($this->travelRequestInstance->referral_document_path && Storage::disk('public')->exists($this->travelRequestInstance->referral_document_path)) {
            Storage::disk('public')->delete($this->travelRequestInstance->referral_document_path);
        }
        $this->travelRequestInstance->referral_document_path = null;
        $this->form['referral_document_path'] = null; // Atualiza o form também
        $this->travelRequestInstance->save(); // Salva a alteração no banco
        $this->referralDocumentFile = null; // Limpa qualquer upload pendente
        session()->flash('status', __('Imagem da guia removida.'));
    }


    private function getAvailableStatusTransitions(): array
    {
        $options = [];
        $user = Auth::user();
        if (!$user || !$this->travelRequestInstance) return [];

        // Exemplo: Se o usuário for manager, ele pode mudar para certos status
        // A lógica real dependerá das suas regras de negócio e permissões da policy
        // if ($user->hasRole('manager')) {
        //     if ($this->travelRequestInstance->status === TravelRequestStatus::PENDING_ASSIGNMENT) {
        //         $options[TravelRequestStatus::SCHEDULED->value] = TravelRequestStatus::SCHEDULED->label();
        //         $options[TravelRequestStatus::REJECTED->value] = TravelRequestStatus::REJECTED->label();
        //     }
        // }
        // Por enquanto, vamos permitir que o status atual seja mantido e, se for diferente,
        // a policy 'update' cobrirá se a mudança é permitida.
        // Para um dropdown mais granular de mudança de status, a lógica da policy 'changeStatus' seria adaptada aqui.
        return TravelRequestStatus::options(); // Retorna todas as opções por enquanto, a policy decidirá o save.
    }


    public function cancel(): void
    {
        $this->redirectRoute('travel-requests.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.travel-requests.edit-travel-request');
    }
}
