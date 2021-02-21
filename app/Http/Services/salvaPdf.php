<?php


namespace App\Http\Services;

use setasign\Fpdi\Tfpdf;

class salvaPdf
{
    /** @var Tfpdf\Fpdi $newPdf */
    private $newPdf;

    public function __construct()
    {
        $this->newPdf = new Tfpdf\Fpdi();
    }

    public function salvarPdf($file, $page, $y = null)
    {
        $this->newPdf->AddPage();
        $this->newPdf->setSourceFile($file);
        $tpl = $this->newPdf->importPage($page);

        if ($y) {
            $this->cropPdf($tpl, $y);
        } else {
            $this->newPdf->useTemplate($tpl);
        }

        $filename = storage_path('app/public/pdfs')."/file{$page}.pdf";
        $this->newPdf->Output($filename, 'F');

        return $filename;
    }

    private function cropPdf($tpl, $y)
    {
        $this->newPdf->useTemplate($tpl, 0, $y);
        $this->newPdf->setPageFormat([210, 94], 'L');
    }
}
