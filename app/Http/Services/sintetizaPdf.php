<?php


namespace App\Http\Services;


use App\Http\Services\LayoutsBoleto\Boleto;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use setasign\Fpdi\Tfpdf;

class sintetizaPdf
{
    public static function sintetizarPdf($file, $page)
    {
        $pdftotext_bin_path = app_path('Helpers/pdftotext/pdftotext');

        $layoutBoleto = app(Boleto::class);
        $new_pdf = new Tfpdf\Fpdi();
        $new_pdf->AddPage();
        $new_pdf->setSourceFile($file);
        $new_pdf->useTemplate($new_pdf->importPage($page));

        $filename = storage_path('app/public/pdfs')."/file{$page}.pdf";
        $new_pdf->Output($filename, 'F');

        $contentOriginal = trim(Pdf::getText($filename, $pdftotext_bin_path, ['enc UTF-8']));

        try {
            $boleto = $layoutBoleto->obterLayoutBoleto($contentOriginal);
            $boleto->boleto($contentOriginal, $page);
        } catch (\Exception $e) {
            if($page % 2 == 0){
                Storage::delete("public/pdfs/file{$page}.pdf");
            } else {
                throw new \Exception($e->getMessage());
            }
        }
    }
}
