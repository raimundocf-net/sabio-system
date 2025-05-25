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
        Schema::table('prescriptions', function (Blueprint $table) {
            // Adiciona a nova coluna para armazenar um array de caminhos de imagem
            $table->json('image_paths')->nullable()->after('prescription_details')->comment('Array de caminhos para as imagens da receita anexadas');

            // Decide o que fazer com a coluna antiga 'image_path'.
            // Opção 1: Renomear para backup (recomendado se houver dados existentes a serem migrados)
            if (Schema::hasColumn('prescriptions', 'image_path')) {
                $table->renameColumn('image_path', 'image_path_old');
                // Posteriormente, você pode criar um script para migrar os dados de image_path_old para image_paths
                // como um array com um único elemento.
            }
            // Opção 2: Simplesmente remover (CUIDADO: PERDA DE DADOS se a coluna já existir com dados)
            // if (Schema::hasColumn('prescriptions', 'image_path')) {
            //     $table->dropColumn('image_path');
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            if (Schema::hasColumn('prescriptions', 'image_paths')) {
                $table->dropColumn('image_paths');
            }

            // Se você renomeou na subida, reverta aqui
            if (Schema::hasColumn('prescriptions', 'image_path_old')) {
                $table->renameColumn('image_path_old', 'image_path');
            }
        });
    }
};
