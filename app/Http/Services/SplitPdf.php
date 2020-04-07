<?php


namespace App\Http\Services;


use Illuminate\Support\Facades\Storage;

class SplitPdf
{
    public function processarLotes($mes, $ano, $dia_vencimento)
    {
        $folderFiles = collect(Storage::files("public/pdfs/base/{$mes}/{$dia_vencimento}"));

        $folderFiles->map(function($file) use ($mes, $ano) {
            processaPdf::processarPdf($file, $mes, $ano);
        });
    }
}
