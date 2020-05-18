<?php

namespace App\Http\Controllers;

use App\Http\Services\obterBoletos;
use App\Http\Services\processaPdf;
use Illuminate\Http\Request;

class SplitPdf extends Controller
{
    private $processaPdf;

    public function __construct(processaPdf $processaPdf)
    {
        $this->processaPdf = $processaPdf;
    }

    public function index() {
        return view('app');
    }

    public function processarPdf (Request $request)
    {
        $this->processaPdf->processarPdf($request);
    }

    public function obterBoletos(Request $request, obterBoletos $obterBoletos)
    {
        return response()->json($obterBoletos->obterBoleto($request->cpf));
    }

}
