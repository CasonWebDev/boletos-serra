<?php


namespace App\Http\Repository;


use App\Http\Classes\Boleto;
use App\boletos as BoletoModel;

class BoletoRepository
{
    public static function adicionarBoleto(Boleto $boleto)
    {
        $boletoModel = new BoletoModel();
        $boletoModel->cpf = $boleto->getCpf();
        $boletoModel->nome = $boleto->getNome();
        $boletoModel->arquivo = $boleto->getArquivo();
        $boletoModel->referencia = $boleto->getReferencia();
        $boletoModel->save();
    }
}
