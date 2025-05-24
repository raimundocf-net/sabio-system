<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')] // Verifique se este é o caminho correto do seu layout
class IndexUser extends Component
{
    use WithPagination;

    public bool $showDeleteModal = false;
    public ?User $deletingUser = null;
    public string $searchTerm = '';
    public ?string $filterRole = null;
    public ?int $filterUnitId = null;


    protected string $paginationTheme = 'tailwind';

    public function openDeleteModal(User $user): void
    {
        $this->deletingUser = $user;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingUser = null;
    }

    public function deleteUser(): void
    {
        if ($this->deletingUser) {
            // Adicionar verificação para não excluir o próprio usuário logado, ou o último admin, etc. (opcional)
            if (auth()->id() === $this->deletingUser->id) {
                session()->flash('error', 'Você não pode excluir seu próprio usuário.');
                $this->closeDeleteModal();
                return;
            }
            $this->deletingUser->delete();
            session()->flash('status', __('Usuário excluído com sucesso!'));
            $this->closeDeleteModal();
        }
    }

    #[On('user-saved')]
    public function refreshList(): void
    {
        // Apenas re-renderiza
    }

    public function updatedSearchTerm() { $this->resetPage(); }
    public function updatedFilterRole() { $this->resetPage(); }
    public function updatedFilterUnitId() { $this->resetPage(); }


    public function render()
    {
        $query = User::with('unit') // Eager load a unidade
        ->orderBy('name');

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->searchTerm.'%')
                    ->orWhere('email', 'like', '%'.$this->searchTerm.'%');
            });
        }

        if ($this->filterRole) {
            $query->where('role', $this->filterRole);
        }

        if ($this->filterUnitId) {
            $query->where('unit_id', $this->filterUnitId);
        }

        $users = $query->paginate(10);

        return view('livewire.users.index-user', [
            'users' => $users,
            'availableRoles' => User::getAvailableRoles(),
            'unitsList' => \App\Models\Unit::orderBy('name')->get(['id', 'name']), // Para o filtro
        ]);
    }
}
