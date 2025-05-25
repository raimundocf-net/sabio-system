<?php

namespace App\Livewire\BoardingLocations;

use App\Models\BoardingLocation;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class ManageBoardingLocation extends Component
{
    public ?BoardingLocation $boardingLocationInstance = null;

    public string $name = '';
    public ?string $address = null;
    public bool $is_active = true;

    public bool $isEditing = false;
    public string $pageTitle = '';

    protected function rules(): array
    {
        $locationId = $this->isEditing ? $this->boardingLocationInstance->id : null;
        return [
            'name' => 'required|string|max:191|unique:boarding_locations,name' . ($locationId ? ',' . $locationId : ''),
            'address' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ];
    }

    protected array $messages = [
        'name.required' => 'O nome do local de embarque é obrigatório.',
        'name.unique' => 'Este nome de local de embarque já existe.',
        'is_active.required' => 'O status é obrigatório.',
    ];

    public function mount(?BoardingLocation $boardingLocation = null): void // Route Model Binding
    {
        if ($boardingLocation && $boardingLocation->exists) {
            $this->boardingLocationInstance = $boardingLocation;
            $this->name = $this->boardingLocationInstance->name;
            $this->address = $this->boardingLocationInstance->address;
            $this->is_active = $this->boardingLocationInstance->is_active;
            $this->isEditing = true;
            $this->pageTitle = __('Editar Local de Embarque');
        } else {
            $this->boardingLocationInstance = new BoardingLocation(); // Para o caso de criação
            $this->pageTitle = __('Novo Local de Embarque');
            $this->is_active = true; // Padrão para novo
        }
    }

    public function save(): void
    {
        $validatedData = $this->validate();

        if ($this->isEditing) {
            $this->boardingLocationInstance->update($validatedData);
            session()->flash('status', __('Local de Embarque atualizado com sucesso!'));
        } else {
            BoardingLocation::create($validatedData);
            session()->flash('status', __('Local de Embarque cadastrado com sucesso!'));
        }
        $this->redirectRoute('boarding-locations.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.boarding-locations.manage-boarding-location');
    }
}
