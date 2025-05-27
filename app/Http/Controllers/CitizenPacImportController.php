<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\CitizenPacImport; // Corrigido para CitizenPacImport
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class CitizenPacImportController extends Controller
{
    public function showForm()
    {
        return view('citizens.import-pac'); // Manteremos o caminho da view que você sugeriu
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt', // CSV ou TXT (com conteúdo CSV)
        ]);

        try {
            $import = new CitizenPacImport();
            Excel::import($import, $request->file('file'));

            $imported = $import->getImportedCount();
            $skipped = $import->getSkippedCount();
            $errors = $import->getErrorCount();
            $errorsDetails = $import->getErrorsDetails();

            $feedbackMessage = "Importação PAC concluída. Processados: {$imported}, Ignorados: {$skipped}, Erros: {$errors}.";

            if ($errors > 0 || $skipped > 0) {
                session()->flash('warning_message', $feedbackMessage);
                session()->flash('errorsDetails', $errorsDetails); // Para exibir detalhes na view
            } else {
                session()->flash('message', "Importação PAC concluída com sucesso! {$imported} registros processados.");
            }
            // Para passar os contadores para a view também, caso não queira apenas na mensagem de warning
            session()->flash('importedCount', $imported);
            session()->flash('skippedCount', $skipped);
            session()->flash('errorCount', $errors);


        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Linha " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            Log::error('Erro de validação Maatwebsite durante importação PAC: ', $failures);
            session()->flash('error', 'Erro de validação durante a importação. Verifique os detalhes.');
            session()->flash('errorsDetails', $errorMessages);
        }
        catch (\Exception $e) {
            Log::error('Erro geral durante importação PAC: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            session()->flash('error', 'Erro ao importar o arquivo: ' . $e->getMessage());
        }
        return back();
    }
}
