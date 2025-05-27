<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citizen_pacs', function (Blueprint $table) {
            $table->id();
            $table->string('nome_do_cidadao')->nullable();
            $table->date('data_de_nascimento')->nullable();
            $table->integer('idade')->nullable();
            $table->string('sexo')->nullable();
            $table->string('identidade_de_genero')->nullable();
            $table->string('cpf', 14)->nullable(); // Sanitizado para 11, mas mantendo 14 para flexibilidade
            $table->string('cns', 15)->nullable(); // Sanitizado
            $table->string('telefone_celular')->nullable();
            $table->string('telefone_residencial')->nullable();
            $table->string('telefone_de_contato')->nullable();
            $table->string('microarea')->nullable();
            $table->string('rua')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('municipio')->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('cep', 9)->nullable(); // Formato com hífen
            $table->date('ultimo_atendimento')->nullable();
            $table->timestamps(); // created_at e updated_at

            // Adicionar índices se necessário, por exemplo em CPF e CNS
            // Se a combinação de CPF e CNS deve ser única:
            // $table->unique(['cpf', 'cns']);
            // Ou índices individuais para busca rápida:
            $table->index('cpf');
            $table->index('cns');
            $table->index('nome_do_cidadao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citizen_pacs');
    }
};
