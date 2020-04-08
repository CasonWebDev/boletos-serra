<?php

namespace App\Http\Controllers;

use App\boletos;
use App\Http\Services\obterBoletos;
use App\Http\Services\SplitPdf as SplitService;
use Illuminate\Http\Request;

class SplitPdf extends Controller
{
    public function index() {
        return view('app');
    }

    public function processarPdf (Request $request, SplitService $splitPdf)
    {
        $splitPdf->processarLotes($request->mes, $request->ano, $request->dia_vencimento);
    }

    public function obterBoletos(Request $request, obterBoletos $obterBoletos)
    {
        return response()->json($obterBoletos->obterBoleto($request->cpf));
    }

}
