<?php


namespace App\Http\Services\LayoutsBoleto\Contract;


interface LayoutBoletoInterface extends BoletoInterface
{
    public function getCpf($text): string;
    public function getNossoNumero($text): string;
    public function getNome($text): string;
    public function getDataVencimento($text): string;
}
