<?php

namespace App\Http\Controllers;

use App\Http\Services\deletaBoletos;
use App\Http\Services\obterBoletos;
use App\Http\Services\processaPdf;
use Illuminate\Http\Request;

class SplitPdf extends Controller
{
    private $processaPdf;
    private $deletaBoletos;

    public function __construct(processaPdf $processaPdf, deletaBoletos $deletaBoletos)
    {
        $this->processaPdf = $processaPdf;
        $this->deletaBoletos = $deletaBoletos;
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

    public function deletarBoletos (Request $request)
    {
        $this->deletaBoletos->processaBoletosDeletar($request->data);
    }
}
