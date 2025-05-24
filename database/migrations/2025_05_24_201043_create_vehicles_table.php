<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\VehicleType; // Importar o Enum
use App\Enums\VehicleAvailabilityStatus; // Importar o Enum

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique()->comment('Placa do veículo');
            $table->string('brand')->comment('Marca do veículo');
            $table->string('model')->comment('Modelo do veículo');
            $table->year('year_of_manufacture')->comment('Ano de fabricação');
            $table->year('model_year')->nullable()->comment('Ano do modelo');
            $table->string('renavam', 11)->unique()->comment('Registro Nacional de Veículos Automotores');
            $table->string('chassis', 17)->unique()->comment('Número do Chassi');
            $table->string('color')->nullable()->comment('Cor do veículo');
            $table->string('type')->comment('Tipo do veículo, usando VehicleType Enum');
            $table->unsignedSmallInteger('passenger_capacity')->comment('Capacidade de passageiros');
            $table->string('availability_status')
                ->default(VehicleAvailabilityStatus::AVAILABLE->value)
                ->comment('Status de disponibilidade do veículo, usando VehicleAvailabilityStatus Enum');
            $table->date('acquisition_date')->nullable()->comment('Data de aquisição do veículo');
            $table->unsignedInteger('current_mileage')->nullable()->comment('Quilometragem atual');
            $table->date('last_inspection_date')->nullable()->comment('Data da última revisão/inspeção');
            $table->boolean('is_pwd_accessible')->default(false)->comment('Adaptado para Pessoa com Deficiência (PNE)');
            $table->text('notes')->nullable()->comment('Observações gerais sobre o veículo');
            $table->timestamps(); // Cria created_at e updated_at
            $table->softDeletes(); // Adiciona a coluna deleted_at para exclusão suave (opcional, mas recomendado)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
