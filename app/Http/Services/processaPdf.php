<?php


namespace App\Http\Services;

use Illuminate\Http\Request;
use App\Jobs\processaPdfJob;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tfpdf;

class processaPdf
{
    public function prepararPdf(Request $request)
    {
        $files = $request->file('pdf');

        foreach ($files as $file) {
            $this->processarPdf($file);
        }
    }

    private function processarPdf($file)
    {
        $storaged = Storage::put('public/pdfs/tmp', $file);
        $file = storage_path('app/'.$storaged);
        $pdf = new Tfpdf\Fpdi();
        $pagecount = $pdf->setSourceFile($file);

        for ($i = 1; $i <= $pagecount; $i++) {
            processaPdfJob::dispatch($file, $i);
        }
    }
}
