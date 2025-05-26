<?php

namespace App\Imports;

use App\Models\CitizenPac; // Certifique-se que este é o seu novo modelo
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings; // Para especificar o delimitador, se necessário
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CitizenPacImport implements ToCollection, WithStartRow //, WithCustomCsvSettings (descomente se precisar)
{
    private int $importedCount = 0;
    private int $skippedCount = 0;
    private int $errorCount = 0;
    private array $errorsDetails = [];

    public function startRow(): int
    {
        return 20; // Os dados reais começam na linha 20
    }

    // Se o seu CSV não usar vírgula como delimitador (ex: ponto e vírgula)
    // public function getCsvSettings(): array
    // {
    //     return [
    //         'delimiter' => ';'
    //     ];
    // }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Verificar se a linha está vazia ou tem poucos dados para evitar erros de offset
            if (count($row) < 19) { // Supondo 19 colunas de $row[0] a $row[18]
                $this->skippedCount++;
                $this->errorsDetails[] = "Linha " . ($index + $this->startRow()) . ": Linha com dados insuficientes, ignorada.";
                continue;
            }

            $data = [
                'nome_do_cidadao'       => $row[0] ?? null,
                'data_de_nascimento'    => $this->toDate($row[1] ?? null),
                'idade'                 => $this->extractYears($row[2] ?? null),
                'sexo'                  => $row[3] ?? null,
                'identidade_de_genero'  => $row[4] ?? null,
                'cpf'                   => $this->sanitizeNumeric($row[5] ?? null),
                'cns'                   => $this->sanitizeNumeric($row[6] ?? null),
                'telefone_celular'      => $row[7] ?? null,
                'telefone_residencial'  => $row[8] ?? null,
                'telefone_de_contato'   => $row[9] ?? null,
                'microarea'             => $row[10] ?? null,
                'rua'                   => $row[11] ?? null,
                'numero'                => $row[12] ?? null,
                'complemento'           => $row[13] ?? null,
                'bairro'                => $row[14] ?? null,
                'municipio'             => $row[15] ?? null,
                'uf'                    => $row[16] ?? null,
                'cep'                   => $row[17] ?? null, // Pode precisar de sanitização se vier com máscara
                'ultimo_atendimento'    => $this->toDate($row[18] ?? null),
            ];

            // Validação dos dados extraídos antes de tentar salvar
            $validator = Validator::make($data, [
                'nome_do_cidadao' => 'nullable|string|max:255',
                'data_de_nascimento' => 'nullable|date_format:Y-m-d',
                'idade' => 'nullable|integer|min:0',
                'sexo' => 'nullable|string|max:50',
                'identidade_de_genero' => 'nullable|string|max:50',
                'cpf' => 'required_without:cns|nullable|string|max:11', // Exige CPF ou CNS
                'cns' => 'required_without:cpf|nullable|string|max:15', // Exige CPF ou CNS
                // Adicione outras regras de validação conforme necessário
            ]);

            if ($validator->fails()) {
                $this->errorCount++;
                $this->errorsDetails[] = "Linha " . ($index + $this->startRow()) . " (" . ($data['nome_do_cidadao'] ?? 'Sem nome') . "): " . $validator->errors()->first();
                Log::warning("Erro de validação ao importar CitizenPac (CSV): " . $validator->errors()->first(), $data);
                continue;
            }

            // Se CPF e CNS estiverem vazios após a sanitização, mesmo passando na validação acima (que permite um ou outro)
            // A sua lógica original era: if (empty($data['cpf']) || empty($data['cns']))
            // Isso significaria que se UM deles fosse vazio, pularia.
            // Se a intenção é pular SOMENTE se AMBOS forem vazios:
            if (empty($data['cpf']) && empty($data['cns'])) {
                $this->skippedCount++;
                $this->errorsDetails[] = "Linha " . ($index + $this->startRow()) . " (" . ($data['nome_do_cidadao'] ?? 'Sem nome') . "): CPF e CNS não fornecidos. Registro ignorado.";
                continue;
            }
            // Se a intenção é pular se QUALQUER UM for vazio (como no seu código original):
            // if (empty($data['cpf']) || empty($data['cns'])) {
            //      $this->skippedCount++;
            //      $this->errorsDetails[] = "Linha " . ($index + $this->startRow()) . " (" . ($data['nome_do_cidadao'] ?? 'Sem nome') . "): CPF ou CNS não fornecido. Registro ignorado.";
            //      continue;
            // }


            try {
                // Identificador para updateOrCreate: prioriza CPF, depois CNS, ou a combinação.
                // Dado que a validação acima garante que pelo menos um existe,
                // e a sua lógica original de updateOrCreate usa ambos.
                $identifier = [];
                if (!empty($data['cpf'])) {
                    $identifier['cpf'] = $data['cpf'];
                }
                if (!empty($data['cns'])) {
                    $identifier['cns'] = $data['cns'];
                }

                CitizenPac::updateOrCreate($identifier, $data);
                $this->importedCount++;
            } catch (\Exception $e) {
                $this->errorCount++;
                $this->errorsDetails[] = "Linha " . ($index + $this->startRow()) . " (" . ($data['nome_do_cidadao'] ?? 'Sem nome') . "): Erro de DB - " . $e->getMessage();
                Log::error("Erro de DB ao importar CitizenPac (CSV) Linha " . ($index + $this->startRow()) .": " . $e->getMessage(), ['data' => $data, 'identifier' => $identifier]);
            }
        }
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }

    public function getErrorCount(): int
    {
        return $this->errorCount;
    }

    public function getErrorsDetails(): array
    {
        return $this->errorsDetails;
    }

    private function toDate($value): ?string
    {
        if (!$value) return null;
        // Tenta formatos comuns de data que podem vir do Excel/CSV
        $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'm/d/Y', 'Y/m/d H:i:s', 'd/m/Y H:i:s', 'd/m/y'];
        foreach ($formats as $format) {
            try {
                // Verifica se $value é um número (timestamp do Excel)
                if (is_numeric($value)) {
                    // \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject converte data do Excel
                    return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
                }
                return Carbon::createFromFormat($format, trim($value))->format('Y-m-d');
            } catch (\Exception $e) {
                continue; // Tenta o próximo formato
            }
        }
        // Se nenhum formato funcionar, tenta um parse genérico
        try {
            return Carbon::parse(trim($value))->format('Y-m-d');
        } catch (\Exception $e) {
            Log::warning("Falha ao converter data '{$value}' para Y-m-d.", ['value' => $value]);
            return null;
        }
    }

    private function extractYears($idade): ?int
    {
        if (is_numeric($idade)) return (int) $idade; // Se já for um número
        if (preg_match('/(\d+)\s*(anos?)?/i', (string) $idade, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    private function sanitizeNumeric($value): ?string
    {
        return $value ? preg_replace('/\D/', '', (string) $value) : null;
    }
}
