<?php

namespace App\Livewire\Vehicles;

use App\Models\Vehicle;
use App\Enums\VehicleType;
use App\Enums\VehicleAvailabilityStatus;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

#[Layout('components.layouts.app')]
class ManageVehicle extends Component
{
    public ?Vehicle $vehicleInstance = null;

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

    public bool $isEditing = false;
    public string $pageTitle = '';

    public array $vehicleTypeOptions = [];
    public array $availabilityStatusOptions = [];

    protected function rules(): array
    {
        $vehicleIdToIgnore = $this->isEditing && $this->vehicleInstance ? $this->vehicleInstance->id : null;
        // ... (regras como definidas anteriormente)
        return [
            'form.plate_number' => 'required|string|max:10|unique:vehicles,plate_number' . ($vehicleIdToIgnore ? ',' . $vehicleIdToIgnore : ''),
            'form.brand' => 'required|string|max:100',
            'form.model' => 'required|string|max:100',
            'form.year_of_manufacture' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'form.model_year' => 'nullable|integer|digits:4|min:1900|max:' . (date('Y') + 2) . '|gte:form.year_of_manufacture',
            'form.renavam' => 'required|string|digits_between:9,11|unique:vehicles,renavam' . ($vehicleIdToIgnore ? ',' . $vehicleIdToIgnore : ''),
            'form.chassis' => 'required|string|size:17|unique:vehicles,chassis' . ($vehicleIdToIgnore ? ',' . $vehicleIdToIgnore : ''),
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
        return [
            'form.plate_number.required' => __('O campo placa é obrigatório.'),
            'form.plate_number.unique' => __('Esta placa já está cadastrada.'),
            'form.brand.required' => __('O campo marca é obrigatório.'),
            'form.model.required' => __('O campo modelo é obrigatório.'),
            'form.year_of_manufacture.required' => __('O ano de fabricação é obrigatório.'),
            'form.year_of_manufacture.digits' => __('O ano de fabricação deve ter 4 dígitos.'),
            'form.year_of_manufacture.min' => __('Ano de fabricação inválido.'),
            'form.year_of_manufacture.max' => __('Ano de fabricação inválido.'),
            'form.model_year.digits' => __('O ano do modelo deve ter 4 dígitos.'),
            'form.model_year.min' => __('Ano do modelo inválido.'),
            'form.model_year.max' => __('Ano do modelo inválido.'),
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
            'form.passenger_capacity.integer' => __('A capacidade deve ser um número.'),
            'form.passenger_capacity.min' => __('A capacidade deve ser de pelo menos 1.'),
            'form.availability_status.required' => __('O status de disponibilidade é obrigatório.'),
            'form.availability_status.enum' => __('Selecione um status de disponibilidade válido.'),
            'form.acquisition_date.date_format' => __('Formato de data inválido para aquisição (AAAA-MM-DD).'),
            'form.acquisition_date.before_or_equal' => __('A data de aquisição não pode ser futura.'),
            'form.current_mileage.integer' => __('A quilometragem deve ser um número.'),
            'form.current_mileage.min' => __('A quilometragem não pode ser negativa.'),
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
            $this->authorize('update', $this->vehicleInstance);

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
                'acquisition_date'      => $this->vehicleInstance->acquisition_date ? $this->vehicleInstance->acquisition_date->format('Y-m-d') : null,
                'current_mileage'       => $this->vehicleInstance->current_mileage,
                'last_inspection_date'  => $this->vehicleInstance->last_inspection_date ? $this->vehicleInstance->last_inspection_date->format('Y-m-d') : null,
                'is_pwd_accessible'     => (bool) $this->vehicleInstance->is_pwd_accessible, // Já estava correto
                'notes'                 => $this->vehicleInstance->notes,
            ];

            $this->isEditing = true;
            // A linha abaixo ESTAVA CORRETA, mas vamos confirmar se $this->vehicleInstance não é null
            $this->pageTitle = $this->vehicleInstance ? (__('Editar Veículo') . ' - ' . $this->vehicleInstance->plate_number) : __('Editar Veículo');
        } else {
            $this->authorize('create', Vehicle::class);
            $this->pageTitle = __('Novo Veículo');
            $this->form['availability_status'] = VehicleAvailabilityStatus::AVAILABLE->value;
            $this->form['is_pwd_accessible'] = false; // Garantir que o default seja booleano
        }
    }

    public function save(): void
    {
        $validatedData = $this->validate();
        $formData = $validatedData['form'];

        $formData['is_pwd_accessible'] = filter_var($formData['is_pwd_accessible'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $formData['acquisition_date'] = !empty($formData['acquisition_date']) ? $formData['acquisition_date'] : null;
        $formData['last_inspection_date'] = !empty($formData['last_inspection_date']) ? $formData['last_inspection_date'] : null;
        $formData['model_year'] = !empty($formData['model_year']) ? (int) $formData['model_year'] : null; // Garante que seja int ou null
        $formData['year_of_manufacture'] = (int) $formData['year_of_manufacture']; // Garante que seja int
        $formData['passenger_capacity'] = (int) $formData['passenger_capacity']; // Garante que seja int
        $formData['current_mileage'] = !empty($formData['current_mileage']) ? (int) $formData['current_mileage'] : null; // Garante que seja int ou null


        if ($this->isEditing && $this->vehicleInstance) {
            $this->authorize('update', $this->vehicleInstance);
            $this->vehicleInstance->update($formData);
            session()->flash('status', __('Veículo atualizado com sucesso!'));
        } else {
            $this->authorize('create', Vehicle::class);
            Vehicle::create($formData);
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
        return view('livewire.vehicles.manage-vehicle', [
            'vehicleTypeOptions' => $this->vehicleTypeOptions,
            'availabilityStatusOptions' => $this->availabilityStatusOptions,
        ]);
    }
}
