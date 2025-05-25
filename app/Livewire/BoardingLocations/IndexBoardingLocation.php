<?php

namespace App\Livewire\BoardingLocations;

use App\Models\BoardingLocation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class IndexBoardingLocation extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';
    public string $pageTitle = "Locais de Embarque";

    public string $searchTerm = '';
    public int $perPage = 10;

    // Modal de Exclusão
    public bool $showDeleteModal = false;
    public ?BoardingLocation $deletingLocation = null;

    public function updatedSearchTerm(): void { $this->resetPage(); }
    public function updatedPerPage(): void { $this->resetPage(); }

    public function openDeleteModal(int $locationId): void
    {
        $this->deletingLocation = BoardingLocation::find($locationId);
        $this->showDeleteModal = (bool) $this->deletingLocation;
        if (!$this->deletingLocation) {
            session()->flash('error', 'Local de embarque não encontrado.');
        }
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingLocation = null;
    }

    public function deleteLocation(): void
    {
        if ($this->deletingLocation) {
            // Aqui, vamos apenas desativar em vez de soft delete, pois é um CRUD simples.
            // Se quiser soft delete, adicione o Trait SoftDeletes ao Model BoardingLocation
            // e chame $this->deletingLocation->delete();
            $this->deletingLocation->update(['is_active' => false]);
            session()->flash('status', 'Local de Embarque desativado com sucesso!');
            $this->closeDeleteModal();
        }
    }

    public function render()
    {
        $query = BoardingLocation::query()
            ->when($this->searchTerm, function ($q, $term) {
                $q->where('name', 'like', '%' . $term . '%')
                    ->orWhere('address', 'like', '%' . $term . '%');
            })
            ->orderBy('name');

        $locations = $query->paginate($this->perPage);

        return view('livewire.boarding-locations.index-boarding-location', [
            'locations' => $locations,
        ]);
    }
}
