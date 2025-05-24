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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Adicionando unit_id
            $table->foreignId('unit_id') // Campo para a chave estrangeira
            ->constrained('units') // Define a restrição para a tabela 'units'
            ->onUpdate('cascade') // Opcional: o que acontece se o id da unit for atualizado
            ->onDelete('restrict'); // Opcional: impede a exclusão de uma unit se ela tiver usuários. Pode ser 'cascade' para deletar usuários ou 'set null' se unit_id for nullable. 'restrict' é mais seguro inicialmente.

            // Adicionando role
            $table->string('role')->comment("Roles: 'acs', 'nurse', 'doctor', 'receptionist', 'nursing_technician', 'admin', 'manager'");

            $table->string('cns')->unique()->nullable();
            $table->string('cbo')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
