<?php

namespace App\Http\Controllers;

use App\boletos;
use App\Http\Services\SplitPdf as SplitService;
use Illuminate\Http\Request;

class SplitPdf extends Controller
{
    public function processarPdf (Request $request, SplitService $splitPdf)
    {
        $splitPdf->processarLotes($request->mes, $request->ano, $request->dia_vencimento);
    }

    public function obterBoletos(Request $request)
    {
        $data = boletos::where('cpf', $request->cpf)->get();

        return response()->json($data);
    }

}
