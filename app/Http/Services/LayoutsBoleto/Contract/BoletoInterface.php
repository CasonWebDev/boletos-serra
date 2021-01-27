<?php


namespace App\Http\Services\LayoutsBoleto\Contract;


interface BoletoInterface
{
    public function boleto(string $boletoString, int $page, string $file);
}
