<?php

namespace App\Livewire\TravelRequests;

use App\Models\Citizen;
use App\Models\TravelRequest;
use App\Enums\ProcedureType;
use App\Enums\TravelRequestStatus;
// use App\Models\User; // Já importado via Auth::id()
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Enum as EnumRule;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection; // Para tipar $citizenSearchResults

#[Layout('components.layouts.app')]
class TravelRequestFormStep extends Component
{
    use WithFileUploads;

    public string $pageTitle = "Nova Solicitação de Viagem";

    // Para busca de cidadão
    public string $citizenSearchTerm = '';
    public ?Citizen $selectedCitizen = null;
    public ?Collection $citizenSearchResults = null; // Coleção de resultados
    public bool $showCitizenSearchResults = false; // Controla visibilidade da lista de resultados

    // Formulário principal
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
    public array $stateOptions = [
        'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
        'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
        'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
        'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
        'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins',
    ];

    protected function rules(): array
    {
        // As regras são condicionais com base em selectedCitizen
        $rules = [];
        if (!$this->selectedCitizen) {
            $rules['citizenSearchTerm'] = 'required|string|min:3';
        } else {
            $rules = array_merge($rules, [
                'form.citizen_id' => 'required|integer|exists:citizens,id',
                'form.needs_companion' => 'required|boolean',
                'form.companion_name' => 'nullable|required_if:form.needs_companion,true|string|max:255',
                'form.companion_cpf' => 'nullable|string|max:14', // Adicionar validação de CPF se necessário
                'form.destination_address' => 'required|string|max:255',
                'form.destination_city' => 'required|string|max:150',
                'form.destination_state' => 'required|string|size:2',
                'form.reason' => 'required|string|max:1000',
                'form.procedure_type' => ['required', new EnumRule(ProcedureType::class)],
                'form.departure_location' => 'required|string|max:255',
                'form.appointment_datetime' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
                'form.desired_departure_datetime' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:now|before_or_equal:form.appointment_datetime',
                'form.desired_return_datetime' => 'nullable|date_format:Y-m-d\TH:i|after:form.appointment_datetime',
                'referralDocumentFile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
                'form.number_of_passengers' => 'required|integer|min:1|max:50',
                'form.observations' => 'nullable|string|max:2000',
            ]);
        }
        return $rules;
    }

    protected function messages(): array
    {
        return [
            'citizenSearchTerm.required' => __('Informe o nome, CPF ou CNS do cidadão para buscar.'),
            'citizenSearchTerm.min' => __('A busca por cidadão deve ter pelo menos 3 caracteres.'),
            'form.citizen_id.required' => __('É necessário selecionar um cidadão para a solicitação.'),
            'form.companion_name.required_if' => __('O nome do acompanhante é obrigatório se "Precisa de Acompanhante" estiver marcado.'),
            'form.appointment_datetime.after_or_equal' => __('A data e hora do compromisso não pode ser no passado.'),
            'form.desired_departure_datetime.before_or_equal' => __('A data desejada de saída deve ser anterior ou igual à data do compromisso.'),
            'form.desired_return_datetime.after' => __('A data desejada de retorno deve ser posterior à data do compromisso.'),
            'referralDocumentFile.image' => __('O arquivo da guia deve ser uma imagem.'),
            'referralDocumentFile.mimes' => __('A imagem da guia deve ser do tipo: jpeg, png, jpg, gif, webp.'),
            'referralDocumentFile.max' => __('A imagem da guia não pode ser maior que 5MB.'),
        ];
    }

    public function mount(): void
    {
        try {
            $this->authorize('create', TravelRequest::class);
        } catch (AuthorizationException $e) {
            session()->flash('error', __('Você não tem permissão para criar solicitações de viagem.'));
            $this->redirectRoute('dashboard', navigate: true);
            return;
        }
        $this->procedureTypeOptions = ProcedureType::options();
        $this->citizenSearchResults = new Collection(); // Inicializa como coleção vazia
        $this->updatedFormNeedsCompanion($this->form['needs_companion']);
    }

    public function updatedCitizenSearchTerm(): void
    {
        $term = trim($this->citizenSearchTerm);
        if (strlen($term) < 3) {
            $this->citizenSearchResults = new Collection();
            $this->showCitizenSearchResults = false;
            $this->selectedCitizen = null; // Limpa seleção se o termo for muito curto
            $this->form['citizen_id'] = null;
            return;
        }

        $this->citizenSearchResults = Citizen::query()
            ->where(function ($query) use ($term) {
                $searchTermSQL = '%' . $term . '%';
                // Uso de unaccent_lower no banco de dados é crucial aqui
                // Se não tiver a extensão unaccent no Postgres, precisará de outra estratégia
                // ou depender que o usuário não use acentos na busca.
                // Para MySQL, LOWER() pode ser suficiente se o collation for case-insensitive.
                $query->whereRaw('LOWER(name) LIKE LOWER(?)', [$searchTermSQL]) // Exemplo básico para MySQL
                // ->whereRaw('public.unaccent_lower(name) LIKE public.unaccent_lower(?)', [$searchTermSQL]) // Para Postgres com unaccent
                ->orWhere('cpf', 'like', $searchTermSQL)
                    ->orWhere('cns', 'like', $searchTermSQL);
            })
            ->orderBy('name')
            ->limit(10)
            ->get();

        $this->showCitizenSearchResults = true;
        $this->selectedCitizen = null; // Limpa seleção anterior ao iniciar nova busca
        $this->form['citizen_id'] = null;

        if ($this->citizenSearchResults->isEmpty()) {
            session()->flash('info_message_citizen_search', __('Nenhum cidadão encontrado.'));
        } else {
            session()->forget('info_message_citizen_search');
        }
    }

    public function selectCitizen(int $citizenId): void
    {
        $this->selectedCitizen = Citizen::find($citizenId);
        if ($this->selectedCitizen) {
            $this->form['citizen_id'] = $this->selectedCitizen->id;
            // Atualiza o campo de busca para mostrar o nome selecionado, mas não continua a busca
            $this->citizenSearchTerm = $this->selectedCitizen->name;
            $this->showCitizenSearchResults = false; // Esconde a lista de resultados
            $this->citizenSearchResults = new Collection(); // Limpa os resultados
            session()->forget('info_message_citizen_search');
            $this->resetErrorBag('citizenSearchTerm');
        }
    }

    public function clearSelectedCitizen(): void
    {
        $this->selectedCitizen = null;
        $this->form['citizen_id'] = null;
        $this->citizenSearchTerm = '';
        $this->citizenSearchResults = new Collection();
        $this->showCitizenSearchResults = false;
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
        $this->validate(); // Valida com base nas regras condicionais

        if(!$this->selectedCitizen || !$this->form['citizen_id']){
            $this->addError('citizenSearchTerm', __('Por favor, busque e selecione um cidadão válido.'));
            return;
        }

        $this->authorize('create', TravelRequest::class);
        $formDataToSave = $this->form; // Pega os dados do array $form

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
        // Conversão de datas para o formato do banco
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
        return view('livewire.travel-requests.create-travel-request');
    }
}
