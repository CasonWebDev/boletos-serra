<?php


namespace App\Http\Services\LayoutsBoleto;

use App\Http\Classes\Boleto as BoletoClass;
use App\Http\Repository\BoletoRepository;
use App\Http\Services\LayoutsBoleto\Contract\BoletoInterface;
use App\Http\Services\LayoutsBoleto\Contract\LayoutBoletoInterface;
use Illuminate\Support\Facades\Storage;

class Boleto implements BoletoInterface
{
    /** @var BoletoClass $boleto */
    private $boleto;

    public function __construct(BoletoClass $boleto)
    {
        $this->boleto = $boleto;
    }

    public function obterLayoutBoleto(string $boletoString, $carne = false): LayoutBoletoInterface
    {
        $layout = $this->numeroBanco($boletoString);
        switch ($layout) {
            case "341-7":
                if ($carne) {
                    return app(ItauCarne::class);
                }
                return app(Itau::class);
            case "033-7":
                if ($carne) {
                    return app(SantanderCarne::class);
                }
                return app(Santander::class);
            default:
                return app(Remessa::class);
        }
    }

    public function numeroBanco(string $boletoString)
    {
        if (preg_match('/(?<=\n\n)([0-9]{3}[\-][0-9]{1})/i', $boletoString, $match)) {
            $numeroBanco = $this->normalizeText($match[1]);
            return $numeroBanco;
        }
        return false;
    }

    public function boleto(string $boletoString, int $page, string $file)
    {
        $this->boleto->setCpf($this->getCpf($boletoString));
        $this->boleto->setNome($this->getNome($boletoString));
        $this->boleto->setNossoNumero($this->getNossoNumero($boletoString));
        $this->boleto->setDataVencimento($this->getDataVencimento($boletoString));
        $this->boleto->setArquivo("public/pdfs/{$this->boleto->getReferencia()}/{$this->boleto->getCpf()}.pdf");

        $this->salvarBoleto($page);
    }

    public function salvarBoleto($page)
    {
        if($this->boleto->getCpf()) {
            if(Storage::exists($this->boleto->getArquivo()) === false) {
                Storage::move("public/pdfs/file{$page}.pdf", $this->boleto->getArquivo());
            }
            BoletoRepository::adicionarBoleto($this->boleto);
        }
    }

    public function normalizeText($text)
    {
        $text = trim($text);
        $text = str_replace("\n", ' ', $text);
        $text = str_replace("\r\n", ' ', $text);
        $text = str_replace("\n\r", ' ', $text);
        $text = str_replace(["\r", "\n"], ' ', $text);
        $text = preg_replace("/\s{2,}/", ' ', $text);
        return trim($text);
    }
}
