<?php

use App\Enums\TravelRequestStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->id();

            // Paciente e Acompanhante
            $table->foreignId('citizen_id')->constrained('citizen_pacs')->onDelete('cascade');
            $table->boolean('needs_companion')->default(false)->comment('Necessita de acompanhante?');
            $table->string('companion_name')->nullable()->comment('Nome do acompanhante');
            $table->string('companion_cpf', 14)->nullable()->comment('CPF do acompanhante (com máscara)'); // Permite máscara

            // Detalhes da Viagem (Solicitação)
            $table->string('destination_address')->comment('Endereço completo do destino');
            $table->string('destination_city')->comment('Cidade de destino');
            $table->string('destination_state', 2)->comment('UF de destino'); // Sigla do estado
            $table->text('reason')->comment('Motivo/Propósito da viagem');
            $table->string('procedure_type')->comment('Tipo de Procedimento, usando ProcedureType Enum');
            $table->string('departure_location')->comment('Local de embarque');
            $table->dateTime('appointment_datetime')->comment('Data e Hora do compromisso no destino');
            $table->dateTime('desired_departure_datetime')->nullable()->comment('Data e Hora desejada de saída da origem');
            $table->dateTime('desired_return_datetime')->nullable()->comment('Data e Hora desejada de retorno');

            // Documentação e Status da Solicitação
            $table->string('referral_document_path')->nullable()->comment('Caminho da foto da guia/encaminhamento');
            $table->string('status')->default(TravelRequestStatus::PENDING_ASSIGNMENT->value)->comment('Status da solicitação, usando TravelRequestStatus Enum');
            $table->foreignId('requester_id')->constrained('users')->comment('ID do usuário atendente que registrou');
            $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('set null')->comment('ID do usuário que aprovou/rejeitou');
            $table->text('approval_notes')->nullable()->comment('Notas da aprovação/rejeição');
            $table->text('cancellation_reason')->nullable()->comment('Motivo do cancelamento');
            $table->text('cancellation_notes')->nullable()->comment('Detalhes do cancelamento');
            $table->timestamp('cancelled_at')->nullable()->comment('Data do cancelamento');
            // completed_at será gerenciado na entidade de "Montagem de Viagem"

            // Outros
            $table->unsignedTinyInteger('number_of_passengers')->default(1)->comment('Número total de passageiros (paciente + acompanhantes)');
            $table->text('observations')->nullable()->comment('Observações gerais da atendente');

            $table->timestamps(); // created_at e updated_at
            $table->softDeletes(); // Para exclusão suave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_requests');
    }
};
