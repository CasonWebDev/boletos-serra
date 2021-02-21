<?php


namespace App\Http\Services\LayoutsBoleto;


use App\Http\Services\LayoutsBoleto\Boleto as LayoutBoleto;
use App\Http\Services\LayoutsBoleto\Contract\LayoutBoletoInterface;
use Carbon\Carbon;

class ItauCarne extends LayoutBoleto implements LayoutBoletoInterface
{
    public function getCpf($text): string
    {
        if (preg_match_all('/(?<=CNPJ )((.|)*)(?=\n\n)/i', $text, $match)) {
            $cpf = $this->normalizeText($match[0][1]);
            $cpf = str_replace('.', '', $cpf);
            return str_replace('-', '', $cpf);
        }
        return '0';
    }

    public function getNossoNumero($text): string
    {
        if (preg_match('/([0-9]{8}[\-][0-9]{1})/i', $text, $match)) {
            $nossoNumero = $this->normalizeText($match[1]);
            $nossoNumero = str_replace('-', '', $nossoNumero);
            $nossoNumero = str_replace('/', '', $nossoNumero);
            return $nossoNumero;
        }
        return '0';
    }

    public function getNome($text): string
    {
        if(preg_match('/(?<=Pagador: )((.|)*)(?=[(])/', $text, $match)){
            return $this->normalizeText($match[1]);
        }
        return '0';
    }

    public function getDataVencimento($text): string
    {
        if(preg_match_all('/(?<=Vencimento\n\n)((.|)*)(?=\n\n)/', $text, $match)){
            $data = $this->normalizeText($match[0][1]);
            return Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
        }
        return '0';
    }
}
