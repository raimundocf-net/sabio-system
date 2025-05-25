<?php

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
        Schema::create('companions', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('cpf', 14)->unique()->nullable()->comment('CPF com máscara xxx.xxx.xxx-xx'); // Armazenar com máscara
            $table->string('identity_document', 30)->nullable()->comment('Documento de Identidade (RG ou similar)');
            $table->string('contact_phone', 20)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps(); // created_at e updated_at
            $table->softDeletes(); // Para exclusão suave, se desejar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companions');
    }
};
