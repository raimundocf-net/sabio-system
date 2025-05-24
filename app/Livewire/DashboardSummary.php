<?php

namespace App\Livewire;

use App\Models\Prescription;
use App\Enums\PrescriptionStatus;
use App\Models\Unit; // Para o filtro do manager
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')] // Define o layout principal do Laravel
class DashboardSummary extends Component
{
    // Propriedades para armazenar as contagens
    public int $solicitadasCount = 0;
    public int $emAnaliseCount = 0;
    public int $rejeitadasCount = 0;
    public int $aprovadasCount = 0;
    public int $prontasRetiradaCount = 0;
    public int $rascunhosCount = 0;
    public int $entreguesHojeCount = 0;
    public int $canceladasHojeCount = 0;
    public int $totalPrescricoesMes = 0;

    // Para o filtro de unidade do Manager
    public $unitsForFilter;
    public $selectedUnitId = 'all'; // 'all' para todas as unidades, ou um ID específico

    // Título da Página
    public string $pageTitle = "Dashboard";
    public string $pageSubtitle = ""; // Para adicionar " - Unidade X" ou " - Minhas Solicitações"

    public function mount()
    {
        $user = Auth::user();
        if ($user->hasRole('manager')) {
            $this->unitsForFilter = Unit::orderBy('name')->get(['id', 'name']);
        } else {
            $this->unitsForFilter = collect(); // Coleção vazia para outros perfis
        }
        $this->loadPrescriptionCounts();
    }

    public function updatedSelectedUnitId()
    {
        // Quando o manager muda a unidade, recarrega as contagens
        $this->loadPrescriptionCounts();
    }

    public function loadPrescriptionCounts()
    {
        $user = Auth::user();
        if (!$user) return; // Segurança

        $this->pageSubtitle = ''; // Reseta o subtítulo

        // Query base para contagens de status específicos
        $statusQuery = Prescription::query();
        // Query base para contagens gerais (ex: total no mês)
        $generalQuery = Prescription::query();

        if ($user->hasRole('manager')) {
            if ($this->selectedUnitId && $this->selectedUnitId !== 'all') {
                $statusQuery->where('unit_id', $this->selectedUnitId);
                $generalQuery->where('unit_id', $this->selectedUnitId);
                $unitName = Unit::find($this->selectedUnitId)?->name;
                $this->pageSubtitle = $unitName ? "Unidade: " . $unitName : '';
            } else {
                $this->pageSubtitle = 'Visão Geral do Sistema';
            }
        } elseif ($user->hasRole('doctor') || $user->hasRole('nurse')) {
            if ($user->unit_id) {
                $statusQuery->where('unit_id', $user->unit_id);
                $generalQuery->where('unit_id', $user->unit_id);
                $this->pageSubtitle = $user->unit?->name ? "Unidade: " . $user->unit->name : '';
            } else {
                $statusQuery->whereRaw('1 = 0'); // Não vê nada se não tiver unidade
                $generalQuery->whereRaw('1 = 0');
                $this->pageSubtitle = 'Nenhuma unidade associada';
            }
        } elseif ($user->hasRole('acs')) {
            $statusQuery->where('user_id', $user->id); // Apenas as dele
            $generalQuery->where('user_id', $user->id);
            $this->pageSubtitle = 'Minhas Solicitações';
        }
        // Admins: Nenhuma restrição adicional na query, veem tudo (Gate::before já cuida)

        // Contagens por status (usando a query já escopada)
        $this->solicitadasCount = (clone $statusQuery)->where('status', PrescriptionStatus::REQUESTED->value)->count();
        $this->emAnaliseCount = (clone $statusQuery)->where('status', PrescriptionStatus::UNDER_DOCTOR_REVIEW->value)->count();
        $this->rejeitadasCount = (clone $statusQuery)->where('status', PrescriptionStatus::REJECTED_BY_DOCTOR->value)->count();
        $this->aprovadasCount = (clone $statusQuery)->where('status', PrescriptionStatus::APPROVED_FOR_ISSUANCE->value)->count();
        $this->prontasRetiradaCount = (clone $statusQuery)->where('status', PrescriptionStatus::READY_FOR_PICKUP->value)->count();
        $this->rascunhosCount = (clone $statusQuery)->where('status', PrescriptionStatus::DRAFT_REQUEST->value)->count();

        // Contagens para um período específico (usando a query já escopada)
        $this->entreguesHojeCount = (clone $statusQuery)->where('status', PrescriptionStatus::DELIVERED->value)
            ->whereDate('completed_at', today())->count();
        $this->canceladasHojeCount = (clone $statusQuery)->where('status', PrescriptionStatus::CANCELLED->value)
            ->whereDate('completed_at', today())->count();

        // Contagem total no mês (usando a generalQuery, que só tem escopo de usuário/unidade, não de status)
        $this->totalPrescricoesMes = (clone $generalQuery)->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
    }

    public function render()
    {
        // O título completo da página pode ser construído aqui para passar ao layout
        $fullPageTitle = $this->pageTitle . ($this->pageSubtitle ? " | " . $this->pageSubtitle : "");

        return view('livewire.dashboard-summary')
            ->layoutData(['title' => $fullPageTitle]); // Passa o título para o layout <x-layouts.app>
    }
}
