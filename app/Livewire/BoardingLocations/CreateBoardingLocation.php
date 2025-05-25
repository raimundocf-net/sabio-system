<?php

namespace App\Livewire\BoardingLocations;

use App\Models\BoardingLocation;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class CreateBoardingLocation extends Component
{
    public string $name = '';
    public ?string $address = null;
    public bool $is_active = true;

    public string $pageTitle = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:191|unique:boarding_locations,name',
            'address' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ];
    }

    protected array $messages = [
        'name.required' => 'O nome do local de embarque é obrigatório.',
        'name.unique' => 'Este nome de local de embarque já existe.',
        'is_active.required' => 'O status é obrigatório.',
    ];

    public function mount(): void
    {
        $this->pageTitle = __('Novo Local de Embarque');
        $this->is_active = true; // Padrão para novo
    }

    public function save(): void
    {
        $validatedData = $this->validate();
        BoardingLocation::create($validatedData);
        session()->flash('status', __('Local de Embarque cadastrado com sucesso!'));
        $this->redirectRoute('boarding-locations.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.boarding-locations.create-boarding-location');
    }
}
