<?php

namespace App\Livewire\Citizens;

use App\Models\Citizen; // Certifique-se que o modelo Citizen está correto
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; // Para validação de cada item do JSON
use Carbon\Carbon; // Para tratamento de datas, se necessário

#[Layout('components.layouts.app')]
class ImportCitizen extends Component
{
    use WithFileUploads;

    public $file;

    public ?int $importedCount = null;
    public ?int $skippedCount = null;
    public ?int $errorCount = null;
    public array $errorsDetails = [];

    public string $pageTitle = "Importar Cidadãos (JSON)";

    // Atualizando as regras de validação para o arquivo JSON
    protected function rules(): array
    {
        return [
            // Aumentei o max para 10MB (10240 KB), ajuste conforme necessário
            'file' => 'required|file|mimetypes:application/json,text/plain|max:10240',
        ];
    }

    protected $messages = [
        'file.required' => 'Por favor, selecione um arquivo JSON.',
        'file.mimetypes' => 'O arquivo deve ser do tipo JSON (application/json ou text/plain com extensão .json).',
        'file.max' => 'O arquivo não pode ser maior que 10MB.',
    ];

    public function updatedFile()
    {
        $this->resetFeedback();
        $this->validateOnly('file');
    }

    public function import()
    {
        $this->validate(); // Valida o arquivo conforme as rules()
        $this->resetFeedback();

        // Verificação adicional da extensão do arquivo
        if ($this->file->getClientOriginalExtension() !== 'json') {
            session()->flash('error', 'O arquivo deve ter a extensão .json');
            $this->file = null;
            $this->dispatch('file-input-reset');
            return;
        }

        try {
            $jsonContents = file_get_contents($this->file->getRealPath());
            $payload = json_decode($jsonContents, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Arquivo JSON inválido: ' . json_last_error_msg());
            }

            if (!isset($payload['data']) || !is_array($payload['data'])) {
                throw new \Exception('Estrutura do JSON inválida. A chave principal "data" contendo um array de cidadãos é esperada.');
            }

            $currentImported = 0;
            $currentSkipped = 0;
            $currentErrors = 0;
            $currentErrorsDetails = [];

            foreach ($payload['data'] as $index => $item) {
                // Mapeamento e sanitização básica dos campos do JSON
                $cpfRaw = $item['fisicas_cpf'] ?? null;
                $cnsRaw = $item['cns'] ?? null;

                $cpf = Citizen::sanitizeNumeric($cpfRaw); // Usando o helper do modelo
                $cns = Citizen::sanitizeNumeric($cnsRaw);

                // Se CPF e CNS estão vazios, pula
                if (empty($cpf) && empty($cns)) {
                    $currentSkipped++;
                    $currentErrorsDetails[] = "Linha de dados " . ($index + 1) . ": CPF e CNS não fornecidos. Registro ignorado.";
                    continue;
                }

                // Define chave de busca (CPF se houver, senão CNS)
                $identifier = !empty($cpf) ? ['cpf' => $cpf] : ['cns' => $cns];

                $dataToSave = [
                    'name' => $item['pessoas_nome'] ?? null,
                    // A data de nascimento na sua nova migration é string.
                    // Se o JSON vier como d/m/Y e você quiser armazenar como Y-m-d:
                    // 'date_of_birth' => $this->formatDateString($item['fisicas_data_nascimento'] ?? null),
                    // Ou se o JSON já vier no formato Y-m-d ou se a string original for aceitável:
                    'date_of_birth' => $item['fisicas_data_nascimento'] ?? null,
                    'cpf' => $cpf,
                    'cns' => $cns,
                    'name_mother' => $item['fisicas_nome_mae'] ?? null,
                ];

                // Validação por item (opcional, mas recomendado)
                $validator = Validator::make($dataToSave, [
                    'name' => 'nullable|string|max:255',
                    'date_of_birth' => 'nullable|string', // Ou 'date_format:Y-m-d' se você formatar
                    'cpf' => 'nullable|string|max:14', // Removido unique daqui para ser tratado pelo updateOrCreate
                    'cns' => 'nullable|string|max:15', // Removido unique daqui
                    'name_mother' => 'nullable|string|max:255',
                ]);

                if ($validator->fails()) {
                    $currentErrors++;
                    $currentErrorsDetails[] = "Linha de dados " . ($index + 1) . " (" . ($dataToSave['name'] ?? 'Sem nome') . "): " . $validator->errors()->first();
                    Log::warning("Erro de validação ao importar cidadão (JSON): " . $validator->errors()->first(), $dataToSave);
                    continue;
                }

                try {
                    Citizen::updateOrCreate($identifier, $dataToSave);
                    $currentImported++;
                } catch (\Exception $e) {
                    $currentErrors++;
                    $currentErrorsDetails[] = "Linha de dados " . ($index + 1) . " (" . ($dataToSave['name'] ?? 'Sem nome') . "): Erro de banco de dados - " . $e->getMessage();
                    Log::error("Erro de DB ao importar cidadão (JSON): " . $e->getMessage(), ['data' => $dataToSave, 'identifier' => $identifier]);
                }
            }

            $this->importedCount = $currentImported;
            $this->skippedCount = $currentSkipped;
            $this->errorCount = $currentErrors;
            $this->errorsDetails = $currentErrorsDetails;

            if ($this->errorCount > 0 || $this->skippedCount > 0) {
                session()->flash('warning_message', 'Importação concluída com alguns alertas/erros. Verifique os detalhes abaixo.');
            } else {
                session()->flash('status', "Importação concluída com sucesso! {$this->importedCount} registros processados.");
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao processar o arquivo JSON: ' . $e->getMessage());
            Log::error('Erro na importação de cidadãos JSON: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        } finally {
            $this->file = null;
            $this->dispatch('file-input-reset');
        }
    }

    /**
     * Helper para formatar string de data de d/m/Y para Y-m-d.
     * Retorna null se a data for inválida ou vazia.
     */
    private function formatDateString(?string $dateString, string $fromFormat = 'd/m/Y', string $toFormat = 'Y-m-d'): ?string
    {
        if (empty($dateString)) {
            return null;
        }
        try {
            return Carbon::createFromFormat($fromFormat, trim($dateString))->format($toFormat);
        } catch (\Exception $e) {
            // Tenta um parse mais genérico se o formato falhar
            try {
                return Carbon::parse(trim($dateString))->format($toFormat);
            } catch (\Exception $e2){
                Log::warning("Falha ao formatar data '{$dateString}' de '{$fromFormat}' para '{$toFormat}'. Erro: " . $e2->getMessage());
                return null; // Ou $dateString se quiser manter a string original em caso de erro
            }
        }
    }

    private function resetFeedback()
    {
        $this->importedCount = null;
        $this->skippedCount = null;
        $this->errorCount = null;
        $this->errorsDetails = [];
        session()->forget(['status', 'error', 'warning_message']);
    }

    public function render()
    {
        return view('livewire.citizens.import-citizen');
    }
}
