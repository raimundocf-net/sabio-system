<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Enums\PrescriptionStatus; // Certifique-se que o caminho para seu Enum está correto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Para usar DB::raw se necessário em contagens mais complexas

class DashboardController extends Controller
{
    public function index()
    {
        // Contagens de receitas por status
        $solicitadasCount = Prescription::where('status', PrescriptionStatus::REQUESTED->value)->count();
        $emAnaliseCount = Prescription::where('status', PrescriptionStatus::UNDER_DOCTOR_REVIEW->value)->count();
        $rejeitadasCount = Prescription::where('status', PrescriptionStatus::REJECTED_BY_DOCTOR->value)->count();
        $aprovadasCount = Prescription::where('status', PrescriptionStatus::APPROVED_FOR_ISSUANCE->value)->count();
        $prontasRetiradaCount = Prescription::where('status', PrescriptionStatus::READY_FOR_PICKUP->value)->count();
        $rascunhosCount = Prescription::where('status', PrescriptionStatus::DRAFT_REQUEST->value)->count(); // Adicionando Rascunhos

        // Contagens para um período específico (exemplo)
        $entreguesHojeCount = Prescription::where('status', PrescriptionStatus::DELIVERED->value)
            ->whereDate('completed_at', today())
            ->count();
        $canceladasHojeCount = Prescription::where('status', PrescriptionStatus::CANCELLED->value)
            ->whereDate('completed_at', today()) // Ou 'updated_at' se 'completed_at' não for sempre preenchido para canceladas
            ->count();
        $totalPrescricoesMes = Prescription::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('dashboard', [
            'pageTitle' => __('Dashboard'), // Passando o título da página
            'solicitadasCount' => $solicitadasCount,
            'emAnaliseCount' => $emAnaliseCount,
            'rejeitadasCount' => $rejeitadasCount,
            'aprovadasCount' => $aprovadasCount,
            'prontasRetiradaCount' => $prontasRetiradaCount,
            'rascunhosCount' => $rascunhosCount,
            'entreguesHojeCount' => $entreguesHojeCount,
            'canceladasHojeCount' => $canceladasHojeCount,
            'totalPrescricoesMes' => $totalPrescricoesMes,
        ]);
    }
}
