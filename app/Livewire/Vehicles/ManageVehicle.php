<?php

namespace App\Livewire\Vehicles;

use App\Models\Vehicle;
use App\Enums\VehicleType;
use App\Enums\VehicleAvailabilityStatus;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Arr; // Não estritamente necessário com a abordagem atual, mas pode ser útil
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\Carbon; // Para manipulação de datas se necessário

#[Layout('components.layouts.app')]
class ManageVehicle extends Component
{
    public ?Vehicle $vehicleInstance = null;

    // Usaremos o array $form para os dados do formulário
    public array $form = [
        'plate_number' => '',
        'brand' => '',
        'model' => '',
        'year_of_manufacture' => null,
        'model_year' => null,
        'renavam' => '',
        'chassis' => '',
        'color' => '',
        'type' => '', // Armazenará o valor string do Enum
        'passenger_capacity' => null,
        'availability_status' => '', // Armazenará o valor string do Enum
        'acquisition_date' => null,  // Espera-se Y-m-d para o input date
        'current_mileage' => null,
        'last_inspection_date' => null, // Espera-se Y-m-d para o input date
        'is_pwd_accessible' => false,
        'notes' => '',
    ];

    public bool $isEditing = false;
    public string $pageTitle = '';

    public array $vehicleTypeOptions = [];
    public array $availabilityStatusOptions = [];

    protected function rules(): array
    {
        $vehicleIdToIgnore = $this->isEditing && $this->vehicleInstance ? $this->vehicleInstance->id : null;
        return [
            'form.plate_number' => 'required|string|max:10|unique:vehicles,plate_number' . ($vehicleIdToIgnore ? ',' . $vehicleIdToIgnore : ''),
            'form.brand' => 'required|string|max:100',
            'form.model' => 'required|string|max:100',
            'form.year_of_manufacture' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'form.model_year' => 'nullable|integer|digits:4|min:1900|max:' . (date('Y') + 2) . '|gte:form.year_of_manufacture',
            'form.renavam' => 'required|string|digits_between:9,11|unique:vehicles,renavam' . ($vehicleIdToIgnore ? ',' . $vehicleIdToIgnore : ''),
            'form.chassis' => 'required|string|size:17|unique:vehicles,chassis' . ($vehicleIdToIgnore ? ',' . $vehicleIdToIgnore : ''),
            'form.color' => 'nullable|string|max:50',
            'form.type' => ['required', new Enum(VehicleType::class)], // Valida contra os valores do Enum
            'form.passenger_capacity' => 'required|integer|min:1|max:255',
            'form.availability_status' => ['required', new Enum(VehicleAvailabilityStatus::class)], // Valida contra os valores do Enum
            'form.acquisition_date' => 'nullable|date_format:Y-m-d|before_or_equal:today',
            'form.current_mileage' => 'nullable|integer|min:0',
            'form.last_inspection_date' => 'nullable|date_format:Y-m-d|before_or_equal:today',
            'form.is_pwd_accessible' => 'nullable|boolean',
            'form.notes' => 'nullable|string|max:5000',
        ];
    }

    protected function messages(): array
    {
        // Suas mensagens de validação aqui...
        return [
            'form.plate_number.required' => __('O campo placa é obrigatório.'),
            'form.plate_number.unique' => __('Esta placa já está cadastrada.'),
            'form.brand.required' => __('O campo marca é obrigatório.'),
            'form.model.required' => __('O campo modelo é obrigatório.'),
            'form.year_of_manufacture.required' => __('O ano de fabricação é obrigatório.'),
            'form.year_of_manufacture.digits' => __('O ano de fabricação deve ter 4 dígitos.'),
            'form.model_year.gte' => __('O ano do modelo deve ser igual ou maior que o ano de fabricação.'),
            'form.renavam.required' => __('O campo RENAVAM é obrigatório.'),
            'form.renavam.unique' => __('Este RENAVAM já está cadastrado.'),
            'form.renavam.digits_between' => __('RENAVAM deve ter 9 ou 11 dígitos.'),
            'form.chassis.required' => __('O campo chassi é obrigatório.'),
            'form.chassis.unique' => __('Este chassi já está cadastrado.'),
            'form.chassis.size' => __('O chassi deve ter 17 caracteres.'),
            'form.type.required' => __('O tipo do veículo é obrigatório.'),
            'form.type.enum' => __('Selecione um tipo de veículo válido.'),
            'form.passenger_capacity.required' => __('A capacidade de passageiros é obrigatória.'),
            'form.passenger_capacity.min' => __('A capacidade deve ser de pelo menos 1.'),
            'form.availability_status.required' => __('O status de disponibilidade é obrigatório.'),
            'form.availability_status.enum' => __('Selecione um status de disponibilidade válido.'),
            'form.acquisition_date.date_format' => __('Formato de data inválido para aquisição (AAAA-MM-DD).'),
            'form.acquisition_date.before_or_equal' => __('A data de aquisição não pode ser futura.'),
            'form.last_inspection_date.date_format' => __('Formato de data inválido para última inspeção (AAAA-MM-DD).'),
            'form.last_inspection_date.before_or_equal' => __('A data da última inspeção não pode ser futura.'),
        ];
    }

    public function mount(?int $vehicleId = null): void
    {
        $this->vehicleTypeOptions = VehicleType::options();
        $this->availabilityStatusOptions = VehicleAvailabilityStatus::options();

        if ($vehicleId) {
            $this->vehicleInstance = Vehicle::findOrFail($vehicleId);

            // --- PONTO DE DEPURAÇÃO 1 ---
            // Descomente a linha abaixo para verificar se o veículo é carregado
            // e quais são seus atributos ANTES de tentar preencher o formulário.
            dd('PONTO 1: Vehicle Instance Loaded', $this->vehicleInstance->toArray());

            try {
                $this->authorize('update', $this->vehicleInstance);
            } catch (AuthorizationException $e) {
                session()->flash('error', __('Você não tem permissão para editar este veículo.'));
                $this->redirectRoute('vehicles.index', navigate: true);
                return; // Importante retornar para evitar execução adicional
            }

            // Preenche o array $form
            $this->form['plate_number'] = $this->vehicleInstance->plate_number;
            $this->form['brand'] = $this->vehicleInstance->brand;
            $this->form['model'] = $this->vehicleInstance->model;
            $this->form['year_of_manufacture'] = $this->vehicleInstance->year_of_manufacture;
            $this->form['model_year'] = $this->vehicleInstance->model_year;
            $this->form['renavam'] = $this->vehicleInstance->renavam;
            $this->form['chassis'] = $this->vehicleInstance->chassis;
            $this->form['color'] = $this->vehicleInstance->color;
            // Para Enums, usamos o valor string
            $this->form['type'] = $this->vehicleInstance->type instanceof VehicleType
                ? $this->vehicleInstance->type->value
                : $this->vehicleInstance->type;
            $this->form['passenger_capacity'] = $this->vehicleInstance->passenger_capacity;
            $this->form['availability_status'] = $this->vehicleInstance->availability_status instanceof VehicleAvailabilityStatus
                ? $this->vehicleInstance->availability_status->value
                : $this->vehicleInstance->availability_status;
            // Para datas, formatamos para Y-m-d para o input HTML
            $this->form['acquisition_date'] = $this->vehicleInstance->acquisition_date
                ? Carbon::parse($this->vehicleInstance->acquisition_date)->format('Y-m-d')
                : null;
            $this->form['current_mileage'] = $this->vehicleInstance->current_mileage;
            $this->form['last_inspection_date'] = $this->vehicleInstance->last_inspection_date
                ? Carbon::parse($this->vehicleInstance->last_inspection_date)->format('Y-m-d')
                : null;
            $this->form['is_pwd_accessible'] = (bool) $this->vehicleInstance->is_pwd_accessible;
            $this->form['notes'] = $this->vehicleInstance->notes;

            $this->isEditing = true;
            $this->pageTitle = $this->vehicleInstance->plate_number
                ? (__('Editar Veículo') . ' - ' . $this->vehicleInstance->plate_number)
                : __('Editar Veículo');

            // --- PONTO DE DEPURAÇÃO 2 ---
            // Descomente a linha abaixo para verificar o conteúdo do array $form
            // e as variáveis de controle após o preenchimento.
            //dd('PONTO 2: Form Data After Population', $this->form, $this->isEditing, $this->pageTitle);

        } else {
            try {
                $this->authorize('create', Vehicle::class);
            } catch (AuthorizationException $e) {
                session()->flash('error', __('Você não tem permissão para criar veículos.'));
                $this->redirectRoute('vehicles.index', navigate: true);
                return;
            }
            $this->pageTitle = __('Novo Veículo');
            $this->form['availability_status'] = VehicleAvailabilityStatus::AVAILABLE->value; // Default
            $this->form['is_pwd_accessible'] = false; // Default
        }
    }

    public function save(): void
    {
        $validatedData = $this->validate();
        $formDataToSave = $validatedData['form'];

        // Assegurar que os tipos de dados estão corretos antes de salvar/atualizar
        $formDataToSave['is_pwd_accessible'] = filter_var($formDataToSave['is_pwd_accessible'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $formDataToSave['acquisition_date'] = !empty($formDataToSave['acquisition_date']) ? $formDataToSave['acquisition_date'] : null;
        $formDataToSave['last_inspection_date'] = !empty($formDataToSave['last_inspection_date']) ? $formDataToSave['last_inspection_date'] : null;
        $formDataToSave['model_year'] = !empty($formDataToSave['model_year']) ? (int) $formDataToSave['model_year'] : null;
        $formDataToSave['year_of_manufacture'] = (int) $formDataToSave['year_of_manufacture'];
        $formDataToSave['passenger_capacity'] = (int) $formDataToSave['passenger_capacity'];
        $formDataToSave['current_mileage'] = !empty($formDataToSave['current_mileage']) ? (int) $formDataToSave['current_mileage'] : null;

        if ($this->isEditing && $this->vehicleInstance) {
            $this->authorize('update', $this->vehicleInstance);
            $this->vehicleInstance->update($formDataToSave);
            session()->flash('status', __('Veículo atualizado com sucesso!'));
        } else {
            $this->authorize('create', Vehicle::class);
            Vehicle::create($formDataToSave);
            session()->flash('status', __('Veículo cadastrado com sucesso!'));
        }
        $this->redirectRoute('vehicles.index', navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('vehicles.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.vehicles.manage-vehicle');
    }
}
