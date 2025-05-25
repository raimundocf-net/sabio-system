<?php

namespace App\Livewire\Vehicles;

use App\Models\Vehicle;
use App\Enums\VehicleType;
use App\Enums\VehicleAvailabilityStatus;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
class UpdateVehicle extends Component
{
    public Vehicle $vehicleInstance; // Injetado automaticamente pelo Livewire com Route Model Binding

    public array $form = [
        'plate_number' => '',
        'brand' => '',
        'model' => '',
        'year_of_manufacture' => null,
        'model_year' => null,
        'renavam' => '',
        'chassis' => '',
        'color' => '',
        'type' => '',
        'passenger_capacity' => null,
        'availability_status' => '',
        'acquisition_date' => null,
        'current_mileage' => null,
        'last_inspection_date' => null,
        'is_pwd_accessible' => false,
        'notes' => '',
    ];

    public string $pageTitle = '';
    public array $vehicleTypeOptions = [];
    public array $availabilityStatusOptions = [];

    protected function rules(): array
    {
        // Regras para atualização (ignorar ID atual para campos unique)
        $vehicleIdToIgnore = $this->vehicleInstance->id;
        return [
            'form.plate_number' => 'required|string|max:10|unique:vehicles,plate_number,' . $vehicleIdToIgnore,
            'form.brand' => 'required|string|max:100',
            'form.model' => 'required|string|max:100',
            'form.year_of_manufacture' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'form.model_year' => 'nullable|integer|digits:4|min:1900|max:' . (date('Y') + 2) . '|gte:form.year_of_manufacture',
            'form.renavam' => 'required|string|digits_between:9,11|unique:vehicles,renavam,' . $vehicleIdToIgnore,
            'form.chassis' => 'required|string|size:17|unique:vehicles,chassis,' . $vehicleIdToIgnore,
            'form.color' => 'nullable|string|max:50',
            'form.type' => ['required', new Enum(VehicleType::class)],
            'form.passenger_capacity' => 'required|integer|min:1|max:255',
            'form.availability_status' => ['required', new Enum(VehicleAvailabilityStatus::class)],
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
            // ... (resto das suas mensagens)
        ];
    }

    // O Livewire fará a injeção do modelo Vehicle automaticamente por causa do parâmetro {vehicle} na rota
    // e do type-hinting na propriedade pública $vehicleInstance.
    public function mount(Vehicle $vehicle): void
    {
        $this->vehicleInstance = $vehicle; // O Livewire já injeta o modelo aqui

        try {
            $this->authorize('update', $this->vehicleInstance);
        } catch (AuthorizationException $e) {
            session()->flash('error', __('Você não tem permissão para editar este veículo.'));
            $this->redirectRoute('vehicles.index', navigate: true);
            return;
        }

        $this->vehicleTypeOptions = VehicleType::options();
        $this->availabilityStatusOptions = VehicleAvailabilityStatus::options();

        $this->form = [
            'plate_number'          => $this->vehicleInstance->plate_number,
            'brand'                 => $this->vehicleInstance->brand,
            'model'                 => $this->vehicleInstance->model,
            'year_of_manufacture'   => $this->vehicleInstance->year_of_manufacture,
            'model_year'            => $this->vehicleInstance->model_year,
            'renavam'               => $this->vehicleInstance->renavam,
            'chassis'               => $this->vehicleInstance->chassis,
            'color'                 => $this->vehicleInstance->color,
            'type'                  => $this->vehicleInstance->type instanceof VehicleType ? $this->vehicleInstance->type->value : $this->vehicleInstance->type,
            'passenger_capacity'    => $this->vehicleInstance->passenger_capacity,
            'availability_status'   => $this->vehicleInstance->availability_status instanceof VehicleAvailabilityStatus ? $this->vehicleInstance->availability_status->value : $this->vehicleInstance->availability_status,
            'acquisition_date'      => $this->vehicleInstance->acquisition_date ? Carbon::parse($this->vehicleInstance->acquisition_date)->format('Y-m-d') : null,
            'current_mileage'       => $this->vehicleInstance->current_mileage,
            'last_inspection_date'  => $this->vehicleInstance->last_inspection_date ? Carbon::parse($this->vehicleInstance->last_inspection_date)->format('Y-m-d') : null,
            'is_pwd_accessible'     => (bool) $this->vehicleInstance->is_pwd_accessible,
            'notes'                 => $this->vehicleInstance->notes,
        ];
        $this->pageTitle = __('Editar Veículo') . ' - ' . $this->vehicleInstance->plate_number;
    }

    public function save(): void
    {
        $this->authorize('update', $this->vehicleInstance);
        $validatedData = $this->validate();
        $formDataToSave = $validatedData['form'];

        $formDataToSave['is_pwd_accessible'] = filter_var($formDataToSave['is_pwd_accessible'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $formDataToSave['acquisition_date'] = !empty($formDataToSave['acquisition_date']) ? $formDataToSave['acquisition_date'] : null;
        $formDataToSave['last_inspection_date'] = !empty($formDataToSave['last_inspection_date']) ? $formDataToSave['last_inspection_date'] : null;
        $formDataToSave['model_year'] = !empty($formDataToSave['model_year']) ? (int) $formDataToSave['model_year'] : null;
        $formDataToSave['year_of_manufacture'] = (int) $formDataToSave['year_of_manufacture'];
        $formDataToSave['passenger_capacity'] = (int) $formDataToSave['passenger_capacity'];
        $formDataToSave['current_mileage'] = !empty($formDataToSave['current_mileage']) ? (int) $formDataToSave['current_mileage'] : null;


        $this->vehicleInstance->update($formDataToSave);
        session()->flash('status', __('Veículo atualizado com sucesso!'));
        $this->redirectRoute('vehicles.index', navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('vehicles.index', navigate: true);
    }

    public function render()
    {
        // Para o formulário de edição, a instância $vehicleInstance já está disponível
        return view('livewire.vehicles.update-vehicle');
    }
}
