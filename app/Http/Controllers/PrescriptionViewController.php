<?php

namespace App\Http\Controllers; // Ou o namespace do seu controller

use App\Models\Prescription;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf; // Ou apenas use \PDF::

class PrescriptionViewController extends Controller
{
    public function showImageAsPdf(Prescription $prescription)
    {
        // Autorização: verificar se o usuário pode ver esta prescrição/imagem
        if (auth()->user()->cannot('view', $prescription)) {
            abort(403);
        }

        if (!$prescription->image_path || !Storage::disk('public')->exists($prescription->image_path)) {
            abort(404, 'Imagem não encontrada.');
        }

        $imagePath = Storage::disk('public')->path($prescription->image_path);
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);

        // Cria um HTML simples com a imagem para o DomPDF renderizar
        // Você pode precisar ajustar o CSS para o tamanho/orientação da imagem no PDF
        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Receita</title></head><body>';
        $html .= '<img src="data:image/' . $imageType . ';base64,' . $imageData . '" style="max-width:100%; max-height:95vh; display:block; margin:auto;">';
        $html .= '</body></html>';

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait'); // Ou 'landscape', ajuste conforme necessário

        // return $pdf->stream('receita-' . $prescription->id . '.pdf'); // Abre no navegador
        return $pdf->inline('receita-' . $prescription->id . '.pdf'); // Nome do arquivo para o navegador
    }
}
