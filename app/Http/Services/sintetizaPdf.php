<?php


namespace App\Http\Services;


use App\Http\Classes\Boleto;
use App\Http\Repository\BoletoRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use setasign\Fpdi\Tfpdf;

class sintetizaPdf
{
    public static function sintetizarPdf($file, $page)
    {
        $pdftotext_bin_path = app_path('Helpers/pdftotext/pdftotext');

        $boleto = new Boleto();
        $new_pdf = new Tfpdf\Fpdi();
        $new_pdf->AddPage();
        $new_pdf->setSourceFile($file);
        $new_pdf->useTemplate($new_pdf->importPage($page));

        $filename = storage_path('app/public/pdfs')."/file{$page}.pdf";
        $new_pdf->Output($filename, 'F');

        $contentOriginal = trim(Pdf::getText($filename, $pdftotext_bin_path, ['enc UTF-8']));

        try {
            $boleto->setCpf(self::getCpf($contentOriginal));
            $boleto->setNome(self::getNome($contentOriginal));
            $boleto->setNossoNumero(self::getNossoNumero($contentOriginal));
            $boleto->setDataVencimento(self::getDataVencimento($contentOriginal));
            $boleto->setArquivo("public/pdfs/{$boleto->getReferencia()}/{$boleto->getNossoNumero()}.pdf");
        } catch (\Exception $e) {
            if($page % 2 == 0){
                Storage::delete("public/pdfs/file{$page}.pdf");
            } else {
                throw new \Exception($e->getMessage());
            }
        }

        if($boleto->getNossoNumero() && Storage::exists($boleto->getArquivo()) === false) {
            Storage::move("public/pdfs/file{$page}.pdf", $boleto->getArquivo());
            BoletoRepository::adicionarBoleto($boleto);
        }
    }

    protected static function getCpf($text)
    {
        if (preg_match('/([0-9]{3}[\.][0-9]{3}[\.][0-9]{3}[\-][0-9]{2})/i', $text, $match)) {
            $cpf = self::normalizeText($match[1]);
            $cpf = str_replace('.', '', $cpf);
            return str_replace('-', '', $cpf);
        }
    }

    protected static function getNossoNumero($text)
    {
        if (preg_match('/([0-9]{12}[\-][0-9]{1})/i', $text, $match)) {
            $cpf = self::normalizeText($match[1]);
            return str_replace('-', '', $cpf);
        }
    }

    protected static function getNome($text)
    {
        if(preg_match('/Pagador\n(.*)[(]/', $text, $match)){
            return self::normalizeText($match[1]);
        }
    }

    protected static function getDataVencimento($text)
    {
        if(preg_match('/BENEFICIÁRIO[)]\n\n(.*)/', $text, $match)){
            $data = self::normalizeText($match[1]);
            return Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
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
