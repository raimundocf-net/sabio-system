<?php

namespace App\Imports;

use App\Models\Citizen; // Alterado de CitizenPac para Citizen
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings; // Para lidar com diferentes delimitadores, se necessário
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; // Para validação por linha

class CitizenImport implements ToCollection, WithStartRow //, WithCustomCsvSettings // Descomente se precisar de settings CSV
{
    private int $importedCount = 0;
    private int $skippedCount = 0;
    private int $errorCount = 0;
    private array $errorsDetails = [];


    public function startRow(): int
    {
        return 20; // Os dados reais começam na linha 20, conforme seu exemplo
    }

    // Se seu CSV não usar vírgula como delimitador, ou tiver outro encoding:
    // public function getCsvSettings(): array
    // {
    //     return [
    //         'delimiter' => ';',
    //         'input_encoding' => 'UTF-8', // ou 'ISO-8859-1', etc.
    //     ];
    // }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Verifica se a linha está vazia ou tem poucos dados para evitar processar linhas em branco no final do arquivo
            if (empty(array_filter($row->toArray()))) {
                continue;
            }

            $data = [
                'nome_do_cidadao'       => $row[0] ?? null,
                'data_de_nascimento'    => $this->toDate($row[1]),
                'idade'                 => $this->extractYears($row[2]),
                'sexo'                  => $row[3] ?? null,
                'identidade_de_genero'  => $row[4] ?? null,
                'cpf'                   => $this->sanitizeNumeric($row[5]),
                'cns'                   => $this->sanitizeNumeric($row[6]),
                'telefone_celular'      => $row[7] ?? null,
                'telefone_residencial'  => $row[8] ?? null,
                'telefone_de_contato'   => $row[9] ?? null,
                'microarea'             => $row[10] ?? null,
                'rua'                   => $row[11] ?? null,
                'numero'                => (string) ($row[12] ?? null), // Garante que seja string
                'complemento'           => $row[13] ?? null,
                'bairro'                => $row[14] ?? null,
                'municipio'             => $row[15] ?? null,
                'uf'                    => $row[16] ?? null,
                'cep'                   => $row[17] ?? null,
                'ultimo_atendimento'    => $this->toDate($row[18]),
            ];

            // Validação básica por linha
            if (empty($data['nome_do_cidadao']) && empty($data['cpf']) && empty($data['cns'])) {
                $this->skippedCount++;
                $this->addErrorDetail($index + $this->startRow(), "Linha ignorada por falta de dados essenciais (Nome, CPF ou CNS).");
                continue;
            }

            // Validação mais robusta por linha usando o Validator do Laravel (opcional, mas recomendado)
            $validator = Validator::make($data, [
                'nome_do_cidadao'       => 'nullable|string|max:255',
                'data_de_nascimento'    => 'nullable|date_format:Y-m-d',
                // Se 'idade' for obrigatório e não calculado: 'idade' => 'required|integer|min:0',
                'idade'                 => 'nullable|integer|min:0',
                'cpf'                   => 'nullable|string|max:14|unique:citizens,cpf' . (isset($data['cpf']) && Citizen::where('cpf', $data['cpf'])->first() ? ',' . Citizen::where('cpf', $data['cpf'])->first()->id : ''), // Lógica para unique em updateOrCreate
                'cns'                   => 'nullable|string|max:15|unique:citizens,cns' . (isset($data['cns']) && Citizen::where('cns', $data['cns'])->first() ? ',' . Citizen::where('cns', $data['cns'])->first()->id : ''),
                // ... outras regras de validação para cada campo se desejar
            ]);

            if ($validator->fails()) {
                $this->errorCount++;
                $this->addErrorDetail($index + $this->startRow(), $validator->errors()->first());
                Log::error("Erro de validação ao importar cidadão na linha " . ($index + $this->startRow()) . ": " . $validator->errors()->first(), $data);
                continue;
            }


            try {
                // Se CPF ou CNS forem campos chave para updateOrCreate
                $keyData = [];
                if (!empty($data['cpf'])) {
                    $keyData['cpf'] = $data['cpf'];
                } elseif (!empty($data['cns'])) { // Alternativa se CPF estiver vazio mas CNS existir
                    $keyData['cns'] = $data['cns'];
                } else { // Se ambos estiverem vazios após a validação (embora a validação acima deva pegar isso)
                    $this->skippedCount++;
                    $this->addErrorDetail($index + $this->startRow(), "CPF e CNS não podem ser ambos vazios para criar/atualizar.");
                    continue;
                }


                Citizen::updateOrCreate(
                    $keyData, // Chaves para encontrar o registro
                    $data     // Dados para atualizar ou criar
                );
                $this->importedCount++;
            } catch (\Exception $e) {
                $this->errorCount++;
                $this->addErrorDetail($index + $this->startRow(), $e->getMessage());
                Log::error("Erro de banco de dados ao importar cidadão na linha " . ($index + $this->startRow()) . ": " . $e->getMessage(), $data);
            }
        }
    }

    private function toDate($value): ?string
    {
        if (empty($value)) return null;
        try {
            // Tenta detectar se é um número serial do Excel
            if (is_numeric($value) && $value > 25569) { // 25569 é o serial para 1/1/1970
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
            }
            // Tenta formato d/m/Y
            return Carbon::createFromFormat('d/m/Y', trim($value))->format('Y-m-d');
        } catch (\Exception $e) {
            // Tenta outros formatos comuns ou retorna null
            try {
                return Carbon::parse(trim($value))->format('Y-m-d');
            } catch (\Exception $e2) {
                Log::warning("Falha ao converter data: {$value}. Erro: " . $e->getMessage() . " | " . $e2->getMessage());
                return null;
            }
        }
    }

    private function extractYears($value): ?int
    {
        if (empty($value)) return null;
        if (is_numeric($value)) return (int) $value; // Se já for um número
        if (preg_match('/(\d+)\s*(anos|ano|a)?/i', (string) $value, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    private function sanitizeNumeric($value): ?string
    {
        if (empty($value)) return null;
        return preg_replace('/\D/', '', (string) $value);
    }

    private function addErrorDetail(int $rowNumber, string $message): void
    {
        $this->errorsDetails[] = "Linha {$rowNumber}: {$message}";
    }

    // Métodos para obter os contadores após a importação
    public function getImportedCount(): int { return $this->importedCount; }
    public function getSkippedCount(): int { return $this->skippedCount; }
    public function getErrorCount(): int { return $this->errorCount; }
    public function getErrorsDetails(): array { return $this->errorsDetails; }
}
