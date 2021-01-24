<?php


namespace App\Http\Services\LayoutsBoleto;

use App\Http\Services\LayoutsBoleto\Boleto as LayoutBoleto;
use App\Http\Services\LayoutsBoleto\Contract\LayoutBoletoInterface;
use Carbon\Carbon;

class Santander extends LayoutBoleto implements LayoutBoletoInterface
{
    public function getCpf($text): string
    {
        if (preg_match('/([0-9]{3}[\.][0-9]{3}[\.][0-9]{3}[\-][0-9]{2})/i', $text, $match)) {
            $cpf = $this->normalizeText($match[1]);
            $cpf = str_replace('.', '', $cpf);
            return str_replace('-', '', $cpf);
        }
    }

    public function getNossoNumero($text): string
    {
        if (preg_match('/([0-9]{12}[\ ][0-9]{1})/i', $text, $match)) {
            $nossoNumero = $this->normalizeText($match[1]);
            return str_replace(' ', '', $nossoNumero);
        }
    }

    public function getNome($text): string
    {
        if(preg_match('/Pagador\n(.*)[(]/', $text, $match)){
            return $this->normalizeText($match[1]);
        }
    }

    public function getDataVencimento($text): string
    {
        if(preg_match('/(?<=Vencimento\n\n)((.|)*)(?=\n\n)/', $text, $match)){
            $data = $this->normalizeText($match[1]);
            return Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
        }
    }
}
