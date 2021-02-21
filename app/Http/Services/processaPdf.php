<?php


namespace App\Http\Services;

use Illuminate\Http\Request;
use App\Jobs\processaPdfJob;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tfpdf;
use ZanySoft\Zip\Zip;

class processaPdf
{
    public function prepararPdf(Request $request)
    {
        $files = $request->file('pdf');
        $folder = now()->timestamp;
        $zip = Zip::open($files);
        $filesList = $zip->listFiles();

        $is_valid = Zip::check($files);

        if ($is_valid) {
            $zip->extract(storage_path('app/tmp/'.$folder));
        }

        foreach ($filesList as $file) {
            $this->processarPdf($file, $folder);
        }
    }

    private function processarPdf($file, $folder)
    {
        $file = storage_path('app/tmp/'.$folder.'/'.$file);
        $pdf = new Tfpdf\Fpdi();
        $pagecount = $pdf->setSourceFile($file);

        for ($i = 1; $i <= $pagecount; $i++) {
            processaPdfJob::dispatch($file, $i);
        }
    }
}
