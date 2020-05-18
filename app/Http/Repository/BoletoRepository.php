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
        $boletoModel->nosso_numero = $boleto->getNossoNumero();
        $boletoModel->data_vencimento = $boleto->getDataVencimento();
        $boletoModel->save();
    }

    public static function obterBoletosPorDataVencimento($data)
    {
        return BoletoModel::where('data_vencimento', $data)->get();
    }

    public static function deletarBoleto($id)
    {
        BoletoModel::where('id', $id)->delete();
    }
}
