<?php


namespace App\Http\Services;


use App\Http\Classes\Boleto;
use App\Http\Repository\BoletoRepository;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use setasign\Fpdi\Tfpdf;

class sintetizaPdf
{
    public static function sintetizarPdf($file, $mes, $ano, $page)
    {
        $pdftotext_bin_path = app_path('Helpers/pdftotext/pdftotext');

        $subfolder = "{$mes}/{$ano}";

        $boleto = new Boleto();
        $new_pdf = new Tfpdf\Fpdi();
        $new_pdf->AddPage();
        $new_pdf->setSourceFile($file);
        $new_pdf->useTemplate($new_pdf->importPage($page));

        $filename = storage_path('app/public/pdfs')."/file{$page}.pdf";
        $new_pdf->Output($filename, 'F');

        $contentOriginal = trim(Pdf::getText($filename, $pdftotext_bin_path, ['enc UTF-8']));

        $boleto->setCpf(self::getCpf($contentOriginal));
        $boleto->setNome(self::getNome($contentOriginal));
        $boleto->setMes($mes);
        $boleto->setAno($ano);
        $boleto->setArquivo("public/pdfs/{$subfolder}/{$boleto->getCpf()}.pdf");

        if($boleto->getCpf() && !Storage::exists($boleto->getArquivo())) {
            Storage::move("public/pdfs/file{$page}.pdf", $boleto->getArquivo());
            BoletoRepository::adicionarBoleto($boleto);
        } else {
            Storage::delete("public/pdfs/file{$page}.pdf");
        }
    }

    protected static function getCpf($text)
    {
        if (preg_match('/CPF: *([0-9-.]{11,14})/i', $text, $match)) {
            $cpf = self::normalizeText($match[1]);
            $cpf = str_replace('.', '', $cpf);
            return str_replace('-', '', $cpf);
        }
    }

    protected static function getNome($text)
    {
        if(preg_match('/Pagador\n(.*)[(]/', $text, $match)){
            return self::normalizeText($match[1]);
        }
    }

    protected static function normalizeText($text)
    {
        $text = trim($text);
        $text = str_replace("\n", ' ', $text);
        $text = str_replace("\r\n", ' ', $text);
        $text = str_replace("\n\r", ' ', $text);
        $text = str_replace(["\r", "\n"], ' ', $text);
        $text = preg_replace("/\s{2,}/", ' ', $text);
        return trim($text);
    }
}
