<?php

namespace App\Livewire\BoardingLocations;

use App\Models\BoardingLocation;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class UpdateBoardingLocation extends Component
{
    public BoardingLocation $boardingLocationInstance; // Injetado via Route Model Binding

    public string $name = '';
    public ?string $address = null;
    public bool $is_active = true;

    public string $pageTitle = '';

    protected function rules(): array
    {
        // A regra unique precisa ignorar o ID atual
        return [
            'name' => 'required|string|max:191|unique:boarding_locations,name,' . $this->boardingLocationInstance->id,
            'address' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ];
    }

    protected array $messages = [
        'name.required' => 'O nome do local de embarque é obrigatório.',
        'name.unique' => 'Este nome de local de embarque já existe.',
        'is_active.required' => 'O status é obrigatório.',
    ];

    public function mount(BoardingLocation $boardingLocation): void // Route Model Binding
    {
        $this->boardingLocationInstance = $boardingLocation;
        $this->name = $this->boardingLocationInstance->name;
        $this->address = $this->boardingLocationInstance->address;
        $this->is_active = $this->boardingLocationInstance->is_active;
        $this->pageTitle = __('Editar Local de Embarque');
    }

    public function save(): void
    {
        $validatedData = $this->validate();
        $this->boardingLocationInstance->update($validatedData);
        session()->flash('status', __('Local de Embarque atualizado com sucesso!'));
        $this->redirectRoute('boarding-locations.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.boarding-locations.update-boarding-location');
    }
}
