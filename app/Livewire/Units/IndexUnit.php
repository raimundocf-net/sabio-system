<?php

namespace App\Livewire\Units; // Certifique-se que o namespace está correto

use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;


class IndexUnit extends Component
{
    use WithPagination;

    public bool $showDeleteModal = false;
    public ?Unit $deletingUnit = null;

    // Para proteger contra N+1 issues ao carregar a paginação
    protected string $paginationTheme = 'tailwind';

    /**
     * Abre o modal de confirmação de exclusão.
     * Usamos a injeção de dependência do modelo para obter a instância de Unit.
     */
    public function openDeleteModal(Unit $unit): void
    {
        $this->deletingUnit = $unit;
        $this->showDeleteModal = true;
    }

    /**
     * Fecha o modal de confirmação de exclusão.
     */
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingUnit = null;
    }

    /**
     * Exclui a unidade selecionada.
     */
    public function deleteUnit(): void
    {
        if ($this->deletingUnit) {
            $this->deletingUnit->delete();
            session()->flash('status', __('Unidade excluída com sucesso!'));
            $this->closeDeleteModal();
            // $this->resetPage(); // Descomente se quiser voltar para a primeira página após a exclusão
        }
    }

    /**
     * Escuta o evento 'unit-saved' para atualizar a lista.
     */
    #[On('unit-saved')]
    public function refreshList(): void
    {
        // Apenas re-renderiza. Se estiver usando paginação e quiser ir para a pág 1:
        // $this->resetPage();
        // No entanto, a simples re-renderização geralmente é suficiente para atualizar os dados visíveis.
    }

    /**
     * Renderiza o componente.
     */
    public function render()
    {
        $units = Unit::orderBy('name')->paginate(10);
        return view('livewire.units.index', [
            'units' => $units,
        ]);
    }
}
