<?php

namespace App\Livewire\TravelRequests;

use App\Models\TravelRequest;
use App\Models\CitizenPac; // Usaremos CitizenPac consistentemente
use App\Models\BoardingLocation;
use App\Models\User; // Para carregar motoristas, se necessário
use App\Enums\ProcedureType;
use App\Enums\TravelRequestStatus; // Para o select de status
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
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule; // Para a regra unique no modal de local de embarque

#[Layout('components.layouts.app')]
class EditTravelRequest extends Component
{
    use WithFileUploads;

    public TravelRequest $travelRequest; // A solicitação de viagem a ser editada
    public CitizenPac $selectedCitizen; // O cidadão associado
    public string $pageTitle = "";

    // Array do formulário, espelhando o TravelRequestForm
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
        'referral_document_path' => null, // Caminho do documento existente
        'number_of_passengers' => 1,
        'observations' => '',
        'status' => '', // Campo adicional para status
        // Adicione aqui outros campos que podem ser editados:
        // 'driver_id' => null,
        // 'vehicle_id' => null,
        // 'actual_departure_datetime' => null,
        // 'actual_return_datetime' => null,
        // 'scheduling_notes' => '',
    ];

    public $referralDocumentFile; // Para um novo upload de arquivo

    // Opções para selects
    public array $procedureTypeOptions = [];
    public array $stateOptions = []; // Definido como no TravelRequestForm
    public Collection $boardingLocations;
    public array $statusOptions = []; // Para o select de status
    // public Collection $availableDrivers; // Exemplo se você tiver motoristas
    // public Collection $availableVehicles; // Exemplo se você tiver veículos

    // Para o modal de Local de Embarque
    public bool $showAddBoardingLocationModal = false;
    public string $newBoardingLocationName = '';
    public ?string $newBoardingLocationAddress = null;

    protected function rules(): array
    {
        // Regras baseadas no TravelRequestForm, mas citizen_id não é alterado aqui.
        // A validação de 'exists:citizen_pacs,id' para citizen_id não é estritamente necessária
        // na atualização se você não permite mudar o cidadão, mas é bom manter para consistência.
        $rules = [
            'form.citizen_id' => 'required|integer|exists:citizen_pacs,id',
            'form.needs_companion' => 'required|boolean',
            'form.companion_name' => 'nullable|required_if:form.needs_companion,true|string|max:255',
            'form.companion_cpf' => 'nullable|string|max:14', // Adicione validação de CPF se tiver
            'form.destination_address' => 'required|string|max:255',
            'form.destination_city' => 'required|string|max:150',
            'form.destination_state' => 'required|string|size:2',
            'form.reason' => 'required|string|max:1000',
            'form.procedure_type' => ['required', new EnumRule(ProcedureType::class)],
            'form.departure_location' => 'required|string|max:255',
            'form.appointment_datetime' => 'required|date_format:Y-m-d\TH:i', // A validação 'after_or_equal:now' pode ser relaxada na edição, dependendo das regras de negócio
            'form.desired_departure_datetime' => 'nullable|date_format:Y-m-d\TH:i|before_or_equal:form.appointment_datetime',
            'form.desired_return_datetime' => 'nullable|date_format:Y-m-d\TH:i|after:form.appointment_datetime',
            'referralDocumentFile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Novo arquivo
            'form.number_of_passengers' => 'required|integer|min:1|max:50',
            'form.observations' => 'nullable|string|max:2000',
            'form.status' => ['required', new EnumRule(TravelRequestStatus::class)], // Validação para o status
            // Adicione regras para outros campos de edição (driver_id, vehicle_id, etc.)
            // 'form.driver_id' => 'nullable|integer|exists:users,id', // Exemplo
            // 'form.vehicle_id' => 'nullable|integer|exists:vehicles,id', // Exemplo
        ];

        if ($this->showAddBoardingLocationModal) {
            $rules['newBoardingLocationName'] = [
                'required',
                'string',
                'max:191',
                Rule::unique('boarding_locations', 'name')->ignore($this->newBoardingLocationName, 'name') // Ignora o próprio nome se estiver editando um local (não aplicável aqui, mas boa prática para modais de edição)
            ];
            $rules['newBoardingLocationAddress'] = 'nullable|string|max:255';
        }
        return $rules;
    }

    protected function messages(): array // Pode herdar ou redefinir mensagens
    {
        return [
            'form.citizen_id.required' => __('O cidadão é obrigatório.'),
            'form.citizen_id.exists' => __('O cidadão associado é inválido.'),
            'form.companion_name.required_if' => __('O nome do acompanhante é obrigatório se "Precisa de Acompanhante" estiver marcado.'),
            'form.appointment_datetime.required' => __('A data e hora do compromisso são obrigatórias.'),
            'form.desired_departure_datetime.before_or_equal' => __('A data/hora desejada de saída deve ser anterior ou igual à data/hora do compromisso.'),
            'form.desired_return_datetime.after' => __('A data/hora desejada de retorno deve ser posterior à data/hora do compromisso.'),
            'referralDocumentFile.image' => __('O arquivo da guia deve ser uma imagem (JPEG, PNG, JPG, GIF, WEBP).'),
            'referralDocumentFile.mimes' => __('Formato de imagem inválido. Use JPEG, PNG, JPG, GIF ou WEBP.'),
            'referralDocumentFile.max' => __('A imagem da guia não pode ser maior que 5MB.'),
            'form.departure_location.required' => __('O local de embarque é obrigatório.'),
            'form.status.required' => __('O status da solicitação é obrigatório.'),
            'newBoardingLocationName.required' => 'O nome do novo local de embarque é obrigatório.',
            'newBoardingLocationName.unique' => 'Este nome de local de embarque já existe.',
        ];
    }

    public function mount(TravelRequest $travelRequest): void
    {
        // Como as policies foram removidas das rotas, a verificação aqui seria uma segunda camada,
        // mas se você removeu completamente, pode remover esta também.
        // $this->authorize('update', $travelRequest);

        $this->travelRequest = $travelRequest->load('citizen'); // Eager load citizen
        $this->selectedCitizen = $this->travelRequest->citizen;

        // Popular o array $form com os dados da $travelRequest
        // Certifique-se que os nomes das chaves em $form correspondem às colunas/atributos de TravelRequest
        $this->form = [
            'citizen_id' => $this->travelRequest->citizen_id,
            'needs_companion' => (bool) $this->travelRequest->needs_companion,
            'companion_name' => $this->travelRequest->companion_name ?? '',
            'companion_cpf' => $this->travelRequest->companion_cpf ?? '',
            'destination_address' => $this->travelRequest->destination_address,
            'destination_city' => $this->travelRequest->destination_city,
            'destination_state' => $this->travelRequest->destination_state,
            'reason' => $this->travelRequest->reason,
            'procedure_type' => $this->travelRequest->procedure_type instanceof ProcedureType ? $this->travelRequest->procedure_type->value : $this->travelRequest->procedure_type,
            'departure_location' => $this->travelRequest->departure_location,
            'appointment_datetime' => $this->travelRequest->appointment_datetime ? Carbon::parse($this->travelRequest->appointment_datetime)->format('Y-m-d\TH:i') : '',
            'desired_departure_datetime' => $this->travelRequest->desired_departure_datetime ? Carbon::parse($this->travelRequest->desired_departure_datetime)->format('Y-m-d\TH:i') : '',
            'desired_return_datetime' => $this->travelRequest->desired_return_datetime ? Carbon::parse($this->travelRequest->desired_return_datetime)->format('Y-m-d\TH:i') : '',
            'referral_document_path' => $this->travelRequest->referral_document_path, // Apenas para exibir o link do atual
            'number_of_passengers' => $this->travelRequest->number_of_passengers,
            'observations' => $this->travelRequest->observations ?? '',
            'status' => $this->travelRequest->status instanceof TravelRequestStatus ? $this->travelRequest->status->value : $this->travelRequest->status,
            // Preencha outros campos de edição aqui
            // 'driver_id' => $this->travelRequest->driver_id,
            // 'vehicle_id' => $this->travelRequest->vehicle_id,
            // 'scheduling_notes' => $this->travelRequest->scheduling_notes ?? '',
        ];

        $citizenName = $this->selectedCitizen?->nome_do_cidadao ?? 'Desconhecido';
        $this->pageTitle = __("Editar Solicitação #") . $this->travelRequest->id . " - " . $citizenName;

        // Carregar opções para selects
        $this->procedureTypeOptions = ProcedureType::options();
        $this->stateOptions = $this->getStateOptions(); // Reutilizando o método se você o tiver
        $this->loadBoardingLocations();
        $this->statusOptions = TravelRequestStatus::options();
        // $this->loadAvailableDrivers();
        // $this->loadAvailableVehicles();

        $this->updatedFormNeedsCompanion($this->form['needs_companion']); // Atualiza a contagem de passageiros
    }

    // Copiado de TravelRequestForm para consistência
    public function getStateOptions(): array
    {
        return [
            'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
            'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
            'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
            'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
            'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins',
        ];
    }

    public function loadBoardingLocations(): void
    {
        $this->boardingLocations = BoardingLocation::where('is_active', true)->orderBy('name')->get(['id', 'name']);
    }

    #[On('updated-form-needs-companion')] // Reutilizando o listener
    public function updatedFormNeedsCompanion($value): void
    {
        $this->form['needs_companion'] = (bool) $value;
        if ($this->form['needs_companion']) {
            $this->form['number_of_passengers'] = max(2, (int) ($this->form['number_of_passengers'] ?? 1));
        } else {
            $this->form['number_of_passengers'] = 1;
            $this->form['companion_name'] = '';
            $this->form['companion_cpf'] = '';
        }
    }

    // Métodos para modal de Local de Embarque (reutilizados de TravelRequestForm)
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
    }

    public function saveNewBoardingLocation(): void
    {
        $this->validate([
            'newBoardingLocationName' => ['required', 'string', 'max:191', Rule::unique('boarding_locations', 'name')],
            'newBoardingLocationAddress' => 'nullable|string|max:255',
        ]);

        $newLocation = BoardingLocation::create([
            'name' => $this->newBoardingLocationName,
            'address' => $this->newBoardingLocationAddress,
            'is_active' => true,
        ]);

        $this->loadBoardingLocations();
        $this->form['departure_location'] = $newLocation->name; // Auto-seleciona
        $this->closeAddBoardingLocationModal();
        $this->dispatch('notify', ['message' => __('Novo local de embarque adicionado!'), 'type' => 'success']);
    }


    public function updateTravelRequest(): void // Nome do método alterado
    {
        // Se as policies foram removidas das rotas, esta autorização também pode ser removida.
        // $this->authorize('update', $this->travelRequest);

        $validatedData = $this->validate();
        $formDataToSave = $validatedData['form'];

        // Lógica de upload de novo arquivo, substituindo o antigo se houver
        if ($this->referralDocumentFile) {
            try {
                // Apagar o arquivo antigo se existir
                if ($this->travelRequest->referral_document_path) {
                    Storage::disk('public')->delete($this->travelRequest->referral_document_path);
                }

                $image = Image::read($this->referralDocumentFile->getRealPath());
                $image->scaleDown(width: 1200);
                $filename = 'referral_doc_' . $formDataToSave['citizen_id'] . '_' . uniqid() . '_' . time() . '.webp';
                $directory = 'travel_request_referrals';
                Storage::disk('public')->put($directory . '/' . $filename, (string) $image->toWebp(75));
                $formDataToSave['referral_document_path'] = $directory . '/' . $filename;
            } catch (\Exception $e) {
                session()->flash('error', __('Erro ao processar o novo arquivo da guia: ') . $e->getMessage());
                \Illuminate\Support\Facades\Log::error('Erro upload (update) guia viagem: ' . $e->getMessage());
                return;
            }
        } else {
            // Se nenhum novo arquivo foi enviado, manter o caminho do documento existente
            $formDataToSave['referral_document_path'] = $this->travelRequest->referral_document_path;
        }

        // Formatar datas antes de salvar
        $formDataToSave['appointment_datetime'] = Carbon::parse($formDataToSave['appointment_datetime'])->toDateTimeString();
        $formDataToSave['desired_departure_datetime'] = !empty($formDataToSave['desired_departure_datetime'])
            ? Carbon::parse($formDataToSave['desired_departure_datetime'])->toDateTimeString()
            : null;
        $formDataToSave['desired_return_datetime'] = !empty($formDataToSave['desired_return_datetime'])
            ? Carbon::parse($formDataToSave['desired_return_datetime'])->toDateTimeString()
            : null;

        // Remover 'citizen_id' do array $formDataToSave se você não quer permitir que ele seja alterado.
        // O citizen_id já está associado à $travelRequest e não deve mudar na edição.
        unset($formDataToSave['citizen_id']);


        $this->travelRequest->update($formDataToSave);

        session()->flash('status', __('Solicitação de viagem atualizada com sucesso!'));
        $this->redirectRoute('travel-requests.index', navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('travel-requests.index', navigate: true); // Redireciona para o índice
    }

    public function render()
    {
        return view('livewire.travel-requests.edit-travel-request');
    }
}
