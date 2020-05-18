<?php


namespace App\Http\Services;


use App\Http\Repository\BoletoRepository;
use App\Jobs\DeletaBoletoJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class deletaBoletos
{
    public function processaBoletosDeletar($data)
    {
        $data = Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
        $boletos = BoletoRepository::obterBoletosPorDataVencimento($data);

        $boletos->map(function($boleto){
           DeletaBoletoJob::dispatch($boleto);
        });
    }

    public static function deletaBoleto($boleto)
    {
        BoletoRepository::deletarBoleto($boleto->id);
        Storage::delete($boleto->arquivo);
    }
}
