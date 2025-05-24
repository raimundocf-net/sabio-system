<?php

namespace Database\Seeders;

use App\Models\Unit; // Embora não usado diretamente aqui, é bom manter se houver lógica futura
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// Removido bcrypt, pois o cast 'hashed' no Model User cuidará disso.
// Se você não tiver o cast 'hashed' no User model, use Illuminate\Support\Facades\Hash; e Hash::make('Password')

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $usersData = [
            [
                'unit_id' => 1, // Certifique-se que Unit com ID 1 existe
                'name' => 'Administrador',
                'email' => 'admin@sabio.test',
                'cns' => '999999999999999',
                'cbo' => '000000 - ADMINISTRADOR',
                'role' => 'admin', // Usando Enum
                'password' => 'Password', // O cast 'hashed' no model cuidará disso
            ],
            [
                'unit_id' => 2, // Certifique-se que Unit com ID 2 existe
                'name' => 'Manager',
                'email' => 'manager@sabio.test',
                'cns' => '888888888888888',
                'cbo' => '000000 - manager',
                'role' => 'manager', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 2,
                'name' => 'MARALUCI DE CASTRO MOREIRA',
                'email' => 'mara@sabio.test',
                'cns' => '706905139970738',
                'cbo' => '411010 - ASSISTENTE ADMINISTRATIVO',
                'role' => 'receptionist', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 3, // Certifique-se que Unit com ID 3 existe
                'name' => 'ANA CAROLINA ALVES MIGUEL',
                'email' => 'anacarolina@sabio.test',
                'cns' => '703405468455000',
                'cbo' => '411010 - ASSISTENTE ADMINISTRATIVO',
                'role' => 'receptionist', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 2,
                'name' => 'BRUNO COELHO GONCALVES DE OLIVEIRA',
                'email' => 'bruno@sabio.test',
                'cns' => '704107174544173',
                'cbo' => '225124 - MEDICO PEDIATRA',
                'role' => 'doctor', // Usando Enum
                'password' => 'Password',
            ],
/*            [
                'unit_id' => 2,
                'name' => 'EDILENE DE OLIVEIRA RODRIGUES',
                'email' => 'edilene@sabio.test',
                'cns' => '700205977385229',
                'cbo' => '322245 - TECNICO DE ENFERMAGEM DA ESTRATEGIA DE SAUDE DA FAMILIA',
                'role' => 'nursing_technician', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 2,
                'name' => 'JHULIANE PAULO SILVA DE SOUZA',
                'email' => 'jhuliane@sabio.test',
                'cns' => '700809978820989',
                'cbo' => '322245 - TECNICO DE ENFERMAGEM DA ESTRATEGIA DE SAUDE DA FAMILIA',
                'role' => 'nursing_technician', // Usando Enum
                'password' => 'Password',
            ],*/
            [
                'unit_id' => 2,
                'name' => 'JULIANA APARECIDA CANESCHI',
                'email' => 'juliana@sabio.test',
                'cns' => '700006081804202',
                'cbo' => '223565 - ENFERMEIRO DA ESTRATEGIA DE SAUDE DA FAMILIA',
                'role' => 'nurse', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 2,
                'name' => 'JULIANA DE MOURA ANTONIOL GUERRA',
                'email' => 'juliana.moura@sabio.test',
                'cns' => '708003899541324',
                'cbo' => '223565 - ENFERMEIRO DA ESTRATEGIA DE SAUDE DA FAMILIA',
                'role' => 'nurse', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 2,
                'name' => 'MARIA APARECIDA GUERRA',
                'email' => 'tida@sabio.test',
                'cns' => '700605474763866',
                'cbo' => '515105 - AGENTE COMUNITARIO DE SAUDE',
                'role' => 'acs', // Usando Enum
                'password' => 'Password',
            ],
/*            [
                'unit_id' => 2,
                'name' => 'NILSA ALVES MIRANDA',
                'email' => 'nilsa@sabio.test',
                'cns' => '705809497058630',
                'cbo' => '322245 - TECNICO DE ENFERMAGEM DA ESTRATEGIA DE SAUDE DA FAMILIA',
                'role' => 'nursing_technician', // Usando Enum
                'password' => 'Password',
            ],*/
            [
                'unit_id' => 2,
                'name' => 'RAIMUNDO COELHO FERREIRA',
                'email' => 'raimundo@sabio.test',
                'cns' => '705409403625491',
                'cbo' => '515105 - AGENTE COMUNITARIO DE SAUDE',
                'role' => 'acs', // Usando Enum
                'password' => 'Password',
            ],
/*            [
                'unit_id' => 2,
                'name' => 'REGIA MARIA GOMES SIQUEIRA DA MATA',
                'email' => 'regia@sabio.test',
                'cns' => '702602752850145',
                'cbo' => '322245 - TECNICO DE ENFERMAGEM DA ESTRATEGIA DE SAUDE DA FAMILIA',
                'role' => 'nursing_technician', // Usando Enum
                'password' => 'Password',
            ],*/
            [
                'unit_id' => 2,
                'name' => 'RODOLPHO RUSSI CARUSO',
                'email' => 'rodolpho@sabio.test',
                'cns' => '700906990086293',
                'cbo' => '225142 - MEDICO DA ESTRATEGIA DE SAUDE DA FAMILIA',
                'role' => 'doctor', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 2,
                'name' => 'ROSANA RODRIGUES CARDOSO',
                'email' => 'rosana@sabio.test',
                'cns' => '704801510619747',
                'cbo' => '515105 - AGENTE COMUNITARIO DE SAUDE',
                'role' => 'acs', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 2,
                'name' => 'ROSELI DA VEIGA PINTO',
                'email' => 'roseli@sabio.test',
                'cns' => '705009003827058',
                'cbo' => '515105 - AGENTE COMUNITARIO DE SAUDE',
                'role' => 'acs', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 2,
                'name' => 'SARA INACIO DUARTE MENDES',
                'email' => 'sara@sabio.test',
                'cns' => '706508315178899',
                'cbo' => '515105 - AGENTE COMUNITARIO DE SAUDE',
                'role' => 'acs', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 2,
                'name' => 'VANESSA DA SILVEIRA FREITAS',
                'email' => 'vanessa@sabio.test',
                'cns' => '703002846826373',
                'cbo' => '225133 - MEDICO PSIQUIATRA',
                'role' => 'doctor', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 2,
                'name' => 'VIVIANE DE OLIVEIRA LIMA',
                'email' => 'viviane@sabio.test',
                'cns' => '704305528665196',
                'cbo' => '515105 - AGENTE COMUNITARIO DE SAUDE',
                'role' => 'acs', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 3,
                'name' => 'GEANE DE ARAUJO COSTA REIS',
                'email' => 'geane@sabio.test',
                'cns' => '708601009173485',
                'cbo' => '223565 - ENFERMEIRO DA ESTRATEGIA DE SAUDE DA FAMILIA',
                'role' => 'nurse', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 3,
                'name' => 'ISABELA LIMA CORTEZ',
                'email' => 'isabela@sabio.test',
                'cns' => '703405501577300',
                'cbo' => '225142 - MEDICO DA ESTRATEGIA DE SAUDE DA FAMILIA',
                'role' => 'doctor', // Usando Enum
                'password' => 'Password',
            ],
  /*          [
                'unit_id' => 3,
                'name' => 'JOAO VITOR DE OLIVEIRA',
                'email' => 'joao@sabio.test',
                'cns' => '700506576176759',
                'cbo' => '322245 - TECNICO DE ENFERMAGEM DA ESTRATEGIA DE SAUDE DA FAMILIA',
                'role' => 'nursing_technician', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 3,
                'name' => 'MICHELE PAIVA GOMES DO CARMO',
                'email' => 'michele@sabio.test',
                'cns' => '700505129078659',
                'cbo' => '322245 - TECNICO DE ENFERMAGEM DA ESTRATEGIA DE SAUDE DA FAMILIA',
                'role' => 'nursing_technician', // Usando Enum
                'password' => 'Password',
            ],*/
            [
                'unit_id' => 3,
                'name' => 'POLIANA FONSECA TORMEN',
                'email' => 'poliana@sabio.test',
                'cns' => '706905172487633',
                'cbo' => '223565 - ENFERMEIRO DA ESTRATEGIA DE SAUDE DA FAMILIA',
                'role' => 'nurse', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 3,
                'name' => 'PRICILA SOARES DE OLIVEIRA AMORIM',
                'email' => 'pricila@sabio.test',
                'cns' => '702800631740663',
                'cbo' => '515105 - AGENTE COMUNITARIO DE SAUDE',
                'role' => 'acs', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 3,
                'name' => 'ROSELI DE FREITAS FERREIRA',
                'email' => 'roseli.freitas@sabio.test',
                'cns' => '700009536948804',
                'cbo' => '515105 - AGENTE COMUNITARIO DE SAUDE',
                'role' => 'acs', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 3,
                'name' => 'ROSELI FREIRE DA PAZ SILVEIRA',
                'email' => 'roseli.freire@sabio.test',
                'cns' => '702308559160920',
                'cbo' => '223565 - ENFERMEIRO DA ESTRATEGIA DE SAUDE DA FAMILIA',
                'role' => 'nurse', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 3,
                'name' => 'TAINARA DA CONCEICAO FERNANDES',
                'email' => 'tainara@sabio.test',
                'cns' => '706805729542123',
                'cbo' => '515105 - AGENTE COMUNITARIO DE SAUDE',
                'role' => 'acs', // Usando Enum
                'password' => 'Password',
            ],
            [
                'unit_id' => 3,
                'name' => 'VALERIA MARIA MARTINS',
                'email' => 'valeria@sabio.test',
                'cns' => '702409510800721',
                'cbo' => '515105 - AGENTE COMUNITARIO DE SAUDE',
                'role' => 'acs', // Usando Enum
                'password' => 'Password',
            ],
        ];

        foreach ($usersData as $userData) {
            // Use updateOrCreate para evitar duplicatas e atualizar se o email já existir.
            // O primeiro array são os atributos para encontrar o registro (chave única).
            // O segundo array são os atributos para criar ou atualizar.
            User::updateOrCreate(
                ['email' => $userData['email']], // Localiza pelo email
                $userData                         // Dados para criar ou atualizar
            );
        }
    }
}
