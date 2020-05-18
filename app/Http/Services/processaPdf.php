<?php


namespace App\Http\Services;

use Illuminate\Http\Request;
use App\Jobs\processaPdfJob;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tfpdf;

class processaPdf
{
    public function processarPdf(Request $request)
    {
        $storaged = Storage::put('public/pdfs/tmp', $request->pdf);
        $file = storage_path('app/'.$storaged);
        $pdf = new Tfpdf\Fpdi();
        $pagecount = $pdf->setSourceFile($file);

        for ($i = 1; $i <= $pagecount; $i++) {
            processaPdfJob::dispatch($file, $i);
        }
    }
}
