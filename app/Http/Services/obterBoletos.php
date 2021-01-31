<?php


namespace App\Http\Services;


use App\boletos;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class obterBoletos
{
    public function obterBoleto($cpf)
    {
        $data = boletos::where('cpf', $cpf)->orderBy('data_vencimento', 'ASC')->get();

        $data->map(function($boleto) {
           $boleto->referencia = Carbon::createFromFormat('m/Y', $boleto->referencia)->translatedFormat('F/Y');
           $boleto->arquivo = Storage::url($boleto->arquivo);
        });

        return $data;

    }
}
