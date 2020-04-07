<?php


namespace App\Http\Services;


use App\Jobs\processaPdfJob;
use setasign\Fpdi\Tfpdf;

class processaPdf
{
    public static function processarPdf($file, $mes, $ano)
    {
        $pdf = new Tfpdf\Fpdi();
        $pagecount = $pdf->setSourceFile($file);

        for ($i = 1; $i <= $pagecount; $i++) {
            processaPdfJob::dispatch($file, $mes, $ano, $i);
        }
    }
}
