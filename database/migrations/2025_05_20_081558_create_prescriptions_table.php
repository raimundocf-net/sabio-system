<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PrescriptionStatus; // Importar o Enum

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();

            // Relacionamentos Essenciais
            $table->foreignId('citizen_id')->constrained('citizen_pacs')->onDelete('cascade');
            $table->foreignId('user_id')->comment('Usuário que solicitou/registrou a receita')->constrained('users')->onDelete('restrict');
            $table->foreignId('unit_id')->comment('Unidade de saúde solicitante')->constrained('units')->onDelete('restrict');
            $table->foreignId('doctor_id')->nullable()->comment('Médico atribuído ou que processou')->constrained('users')->onDelete('set null');

            // Status e Conteúdo Principal da Solicitação
            $table->string('status')->default(PrescriptionStatus::REQUESTED->value)->comment('Status atual da solicitação');
            $table->text('prescription_details')->comment('Texto da solicitação da receita (entrada da ACS)'); // Este campo recebe o texto livre

            $table->string('image_path')->nullable()->after('prescription_details')->comment('Caminho para a imagem da receita anexada');

            // Notas de Processamento (Consolidadas)
            $table->text('processing_notes')->nullable()->comment('Observações do médico, motivo de rejeição ou cancelamento');

            // Timestamps Chave do Fluxo de Trabalho
            $table->timestamp('reviewed_at')->nullable()->comment('Data em que o médico analisou/respondeu');
            $table->timestamp('completed_at')->nullable()->comment('Data em que a solicitação foi finalizada (entregue, rejeitada, cancelada)');

            $table->timestamps(); // created_at (data da solicitação), updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
