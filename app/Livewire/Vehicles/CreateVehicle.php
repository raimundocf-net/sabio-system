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
class CreateVehicle extends Component
{
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
        'availability_status' => '', // Será definido no mount
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
        // Regras para criação (sem ignorar ID)
        return [
            'form.plate_number' => 'required|string|max:10|unique:vehicles,plate_number',
            'form.brand' => 'required|string|max:100',
            'form.model' => 'required|string|max:100',
            'form.year_of_manufacture' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'form.model_year' => 'nullable|integer|digits:4|min:1900|max:' . (date('Y') + 2) . '|gte:form.year_of_manufacture',
            'form.renavam' => 'required|string|digits_between:9,11|unique:vehicles,renavam',
            'form.chassis' => 'required|string|size:17|unique:vehicles,chassis',
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

    public function mount(): void
    {
        try {
            $this->authorize('create', Vehicle::class);
        } catch (AuthorizationException $e) {
            session()->flash('error', __('Você não tem permissão para criar veículos.'));
            $this->redirectRoute('vehicles.index', navigate: true);
            return;
        }

        $this->vehicleTypeOptions = VehicleType::options();
        $this->availabilityStatusOptions = VehicleAvailabilityStatus::options();
        $this->pageTitle = __('Novo Veículo');
        $this->form['availability_status'] = VehicleAvailabilityStatus::AVAILABLE->value;
        $this->form['is_pwd_accessible'] = false;
    }

    public function save(): void
    {
        $this->authorize('create', Vehicle::class);
        $validatedData = $this->validate();
        $formDataToSave = $validatedData['form'];

        $formDataToSave['is_pwd_accessible'] = filter_var($formDataToSave['is_pwd_accessible'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $formDataToSave['acquisition_date'] = !empty($formDataToSave['acquisition_date']) ? $formDataToSave['acquisition_date'] : null;
        $formDataToSave['last_inspection_date'] = !empty($formDataToSave['last_inspection_date']) ? $formDataToSave['last_inspection_date'] : null;
        $formDataToSave['model_year'] = !empty($formDataToSave['model_year']) ? (int) $formDataToSave['model_year'] : null;
        $formDataToSave['year_of_manufacture'] = (int) $formDataToSave['year_of_manufacture'];
        $formDataToSave['passenger_capacity'] = (int) $formDataToSave['passenger_capacity'];
        $formDataToSave['current_mileage'] = !empty($formDataToSave['current_mileage']) ? (int) $formDataToSave['current_mileage'] : null;

        Vehicle::create($formDataToSave);
        session()->flash('status', __('Veículo cadastrado com sucesso!'));
        $this->redirectRoute('vehicles.index', navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('vehicles.index', navigate: true);
    }

    public function render()
    {
        // Para o formulário de criação, não precisamos passar a instância do veículo
        return view('livewire.vehicles.create-vehicle');
    }
}
