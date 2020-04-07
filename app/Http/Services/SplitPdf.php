<?php


namespace App\Http\Services;


use App\Jobs\processaPdfJob;
use Illuminate\Support\Facades\Storage;

class SplitPdf
{
    public function processarLotes($mes, $ano, $dia_vencimento)
    {
        $folderFiles = collect(Storage::files("public/pdfs/base/{$mes}/{$dia_vencimento}"));

        $folderFiles->map(function($file) use ($mes, $ano) {
            processaPdfJob::dispatch($file, $mes, $ano);
        });
    }
}
