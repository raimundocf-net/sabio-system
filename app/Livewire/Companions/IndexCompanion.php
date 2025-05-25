<?php

namespace App\Livewire\Companions;

use App\Models\Companion;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class IndexCompanion extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';
    public string $pageTitle = "Gerenciar Acompanhantes";

    public string $searchTerm = '';
    public int $perPage = 10;

    public bool $showDeleteModal = false;
    public ?Companion $deletingCompanion = null;

    public function updatedSearchTerm(): void { $this->resetPage(); }
    public function updatedPerPage(): void { $this->resetPage(); }

    public function openDeleteModal(int $companionId): void
    {
        $this->deletingCompanion = Companion::find($companionId);
        $this->showDeleteModal = (bool) $this->deletingCompanion;
        if (!$this->deletingCompanion) {
            session()->flash('error', 'Acompanhante não encontrado.');
        }
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingCompanion = null;
    }

    public function deleteCompanion(): void
    {
        if ($this->deletingCompanion) {
            // Adicionar soft delete se o modelo estiver configurado
            // $this->deletingCompanion->delete();
            // Por enquanto, exclusão física (ou você pode adicionar softDeletes ao modelo)
            try {
                $this->deletingCompanion->delete(); // Se usar SoftDeletes, isso irá "desativar"
                session()->flash('status', __('Acompanhante excluído com sucesso!'));
            } catch (\Exception $e) {
                // Lidar com erros, por exemplo, se o acompanhante estiver vinculado a algo que impede a exclusão
                session()->flash('error', __('Erro ao excluir o acompanhante. Verifique se ele não está vinculado a alguma viagem.'));
            }
            $this->closeDeleteModal();
        }
    }

    public function render()
    {
        $query = Companion::query()
            ->when($this->searchTerm, function ($q, $term) {
                $searchTermSQL = '%' . mb_strtolower($term, 'UTF-8') . '%';
                $q->where(function ($subQuery) use ($searchTermSQL, $term) {
                    $subQuery->whereRaw('public.unaccent_lower(full_name) LIKE public.unaccent_lower(?)', [$searchTermSQL])
                        ->orWhere('cpf', 'like', $term . '%') // Busca CPF exato ou começando com
                        ->orWhere('identity_document', 'like', '%' . $term . '%');
                });
            })
            ->orderBy('full_name');

        $companions = $query->paginate($this->perPage);

        return view('livewire.companions.index-companion', [
            'companions' => $companions,
        ])->layoutData(['pageTitle' => $this->pageTitle]);
    }
}
