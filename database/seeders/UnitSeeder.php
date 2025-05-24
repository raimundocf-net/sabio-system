<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Esta linha é para Laravel 9+. Se não usar, pode remover.
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::firstOrCreate(
            ['cnes' => '00000'], // Usando CNES como chave única para buscar/criar
            [
                'name' => 'CENTRO DE ADMINISTRAÇÃO',
                'municipality' => 'Prefeitura Municipal de Divinésia',
                'description' => 'Unidade administrativa central.' // Adicionando uma descrição exemplo
            ]
        );

        Unit::firstOrCreate(
            ['cnes' => '2161818'], // Usando CNES como chave única
            [
                'name' => 'ESF ORÀDIA MENDES CASTRO',
                'municipality' =>  'Prefeitura Municipal de Divinésia',
                'description' => 'Equipe de Saúde da Família.' // Adicionando uma descrição exemplo
            ]
        );

        Unit::firstOrCreate(
            ['cnes' => '7510217'], // Usando CNES como chave única
            [
                'name' => 'ESF MARIA DO CARMO ALVES',
                'municipality' =>  'Prefeitura Municipal de Divinésia',
                'description' => 'Equipe de Saúde da Família.' // Adicionando uma descrição exemplo
            ]
        );

    }
}
