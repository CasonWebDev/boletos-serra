<?php


namespace App\Http\Services;


use App\Http\Services\LayoutsBoleto\Boleto;
use App\Http\Services\LayoutsBoleto\Itau;
use App\Http\Services\LayoutsBoleto\Santander;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;


class sintetizaPdf
{
    public function sintetizarPdf($file, $page)
    {
        $layoutBoleto = app(Boleto::class);
        $salvaPdf = app(salvaPdf::class);
        $pdftotext_bin_path = app_path('Helpers/pdftotext/pdftotext');
        $filename = $salvaPdf->salvarPdf($file, $page);
        $contentOriginal = trim(Pdf::getText($filename, $pdftotext_bin_path, ['enc UTF-8']));
        $layout = $layoutBoleto->obterLayoutBoleto($contentOriginal);
        $multiplosBoletos = $this->multiplosBoletos($contentOriginal);

        if ($multiplosBoletos) {
            $this->sintetizaCarne($file, $page, $layout);
        } else {
            $this->sintetizaBoleto($contentOriginal, $file, $page);
        }
    }

    private function multiplosBoletos(string $boletoString)
    {
        if (preg_match('/Parcela/i', $boletoString, $match)) {
            return $match;
        }
        return false;
    }

    private function sintetizaCarne($file, $page, $layout)
    {
        $layoutBoleto = app(Boleto::class);
        $pdftotext_bin_path = app_path('Helpers/pdftotext/pdftotext');

        for ($i = 1; $i <= 3; $i++) {
            $y = $this->getY($layout, $i);
            $salvaPdf = app(salvaPdf::class);
            $filename = $salvaPdf->salvarPdf($file, $page, $y);
            $contentOriginal = trim(Pdf::getText($filename, $pdftotext_bin_path, ['enc UTF-8']));

            try {
                $boleto = $layoutBoleto->obterLayoutBoleto($contentOriginal, true);
                $boleto->boleto($contentOriginal, $page, $file);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
    }

    private function getY($layout, $i)
    {
        if ($layout instanceof Itau) {
            return $this->getYItauCarne($i);
        }

        if ($layout instanceof Santander) {
            return $this->getYSantanderCarne($i);
        }
    }

    private function getYItauCarne($i)
    {
        switch ($i) {
            case 1:
                return 14;
            case 2:
                return 107;
            case 3:
                return 200;
        }
    }

    private function getYSantanderCarne($i)
    {
        switch ($i) {
            case 1:
                return 9;
            case 2:
                return 103;
            case 3:
                return 197;
        }
    }

    private function sintetizaBoleto($contentOriginal, $file, $page)
    {
        $layoutBoleto = app(Boleto::class);

        try {
            $boleto = $layoutBoleto->obterLayoutBoleto($contentOriginal);
            $boleto->boleto($contentOriginal, $page, $file);
        } catch (\Exception $e) {
            if($page % 2 == 0){
                Storage::delete("public/pdfs/file{$page}.pdf");
            } else {
                throw new \Exception($e->getMessage());
            }
        }
    }
}
