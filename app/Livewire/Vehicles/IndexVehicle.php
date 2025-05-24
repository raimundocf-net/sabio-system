<?php

namespace App\Livewire\Vehicles;

use App\Models\Vehicle;
use App\Enums\VehicleType;
use App\Enums\VehicleAvailabilityStatus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Gate; // Importar Gate para autorização
use Illuminate\Auth\Access\AuthorizationException;

#[Layout('components.layouts.app')]
class IndexVehicle extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind'; // Define o tema da paginação

    public string $pageTitle = "Gerenciar Frota de Veículos";

    // Filtros e Busca
    public string $searchTerm = '';
    public ?string $filterVehicleType = null;
    public ?string $filterAvailabilityStatus = null;
    public int $perPage = 10; // Valor padrão para itens por página

    // Modal de Exclusão
    public bool $showDeleteModal = false;
    public ?Vehicle $deletingVehicle = null;

    // Opções para os selects de filtro (serão preenchidas no mount/render)
    public array $vehicleTypeOptions = [];
    public array $availabilityStatusOptions = [];

    /**
     * Monta o componente, inicializando opções de filtro.
     * Verifica a permissão para visualizar a lista de veículos.
     */
    public function mount(): void
    {
        try {
            $this->authorize('viewAny', Vehicle::class);
        } catch (AuthorizationException $e) {
            session()->flash('error', __('Você não tem permissão para acessar esta página.'));
            // Redireciona se o usuário não tiver permissão.
            // É importante ter uma rota 'dashboard' ou ajustar para uma rota padrão.
            // Esta linha estava comentada, mas é uma boa prática descomentá-la
            // e garantir que o redirecionamento funcione.
            // $this->redirectRoute('dashboard', navigate: true);
            // Para evitar erro fatal se o redirect não for possível no mount inicial,
            // podemos apenas impedir a renderização de dados e mostrar a mensagem de erro.
            // A view já mostrará a mensagem de erro se a sessão for preenchida.
        }

        $this->vehicleTypeOptions = VehicleType::options();
        $this->availabilityStatusOptions = VehicleAvailabilityStatus::options();
    }

    /**
     * Reseta a página de paginação quando qualquer filtro ou termo de busca é atualizado.
     */
    public function updatedSearchTerm(): void
    {
        $this->resetPage();
    }

    public function updatedFilterVehicleType(): void
    {
        $this->resetPage();
    }

    public function updatedFilterAvailabilityStatus(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Abre o modal de confirmação para exclusão de um veículo.
     */
    public function openDeleteModal(int $vehicleId): void
    {
        $this->deletingVehicle = Vehicle::find($vehicleId);
        if ($this->deletingVehicle) {
            try {
                $this->authorize('delete', $this->deletingVehicle);
                $this->showDeleteModal = true;
            } catch (AuthorizationException $e) {
                session()->flash('error', __('Você não tem permissão para excluir este veículo.'));
                $this->deletingVehicle = null;
            }
        } else {
            session()->flash('error', __('Veículo não encontrado.'));
        }
    }

    /**
     * Fecha o modal de exclusão.
     */
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingVehicle = null;
    }

    /**
     * Exclui o veículo selecionado.
     */
    public function deleteVehicle(): void
    {
        if ($this->deletingVehicle) {
            try {
                $this->authorize('delete', $this->deletingVehicle);
                $this->deletingVehicle->delete(); // Usará SoftDelete se o model estiver configurado
                session()->flash('status', __('Veículo excluído com sucesso!'));
            } catch (AuthorizationException $e) {
                session()->flash('error', __('Você não tem permissão para excluir este veículo.'));
            } catch (\Exception $e) {
                session()->flash('error', __('Erro ao excluir o veículo: ') . $e->getMessage());
            }
            $this->closeDeleteModal();
            // A atualização da lista é feita automaticamente pelo Livewire ao re-renderizar.
            // $this->resetPage(); // Opcional, se quiser voltar para a primeira página.
        }
    }

    /**
     * Renderiza o componente.
     */
    public function render()
    {
        // Verifica a permissão antes de tentar buscar os dados.
        // Se a permissão foi negada no mount, $this->vehicles será uma coleção vazia
        // e a view já deve lidar com isso (exibindo a mensagem de erro da sessão).
        if (!Gate::allows('viewAny', Vehicle::class) && !auth()->user()->hasRole('admin')) {
            // Se o usuário não é admin e não tem viewAny, retorna uma view vazia ou com mensagem.
            // A flash message já foi setada no mount se a autorização falhou.
            return view('livewire.vehicles.index-vehicle', [
                'vehicles' => collect()->paginate($this->perPage), // Paginação vazia
                'vehicleTypeOptions' => $this->vehicleTypeOptions,
                'availabilityStatusOptions' => $this->availabilityStatusOptions,
            ]);
        }


        $query = Vehicle::query()
            ->when($this->searchTerm, function ($query, $term) {
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('plate_number', 'like', '%' . $term . '%')
                        ->orWhere('brand', 'like', '%' . $term . '%')
                        ->orWhere('model', 'like', '%' . $term . '%')
                        ->orWhere('renavam', 'like', '%' . $term . '%');
                });
            })
            ->when($this->filterVehicleType, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($this->filterAvailabilityStatus, function ($query, $status) {
                $query->where('availability_status', $status);
            })
            ->orderBy('brand') // Ou 'created_at', 'plate_number', etc.
            ->orderBy('model');

        $vehicles = $query->paginate($this->perPage);

        return view('livewire.vehicles.index-vehicle', [
            'vehicles' => $vehicles,
            'vehicleTypeOptions' => $this->vehicleTypeOptions, // Passa para a view
            'availabilityStatusOptions' => $this->availabilityStatusOptions, // Passa para a view
        ]);
    }
}
