<?php


namespace App\Http\Classes;


class Boleto
{
    private $arquivo;
    private $nome;
    private $cpf;
    private $nosso_numero;
    private $data_vencimento;

    /**
     * @return mixed
     */
    public function getNossoNumero()
    {
        return $this->nosso_numero;
    }

    /**
     * @param mixed $nosso_numero
     */
    public function setNossoNumero($nosso_numero): void
    {
        $this->nosso_numero = $nosso_numero;
    }

    /**
     * @return mixed
     */
    public function getDataVencimento()
    {
        return $this->data_vencimento;
    }

    /**
     * @param mixed $data_vencimento
     */
    public function setDataVencimento($data_vencimento): void
    {
        $this->data_vencimento = $data_vencimento;
    }

    /**
     * @return mixed
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param mixed $cpf
     */
    public function setCpf($cpf): void
    {
        $this->cpf = $cpf;
    }

    /**
     * @return mixed
     */
    public function getArquivo()
    {
        return $this->arquivo;
    }

    /**
     * @param mixed $arquivo
     */
    public function setArquivo($arquivo): void
    {
        $this->arquivo = $arquivo;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getMes()
    {
        $vencimento = $this->data_vencimento;
        $vencimento = explode('-', $vencimento);
        return $vencimento[1];
    }

    /**
     * @return mixed
     */
    public function getAno()
    {
        $vencimento = $this->data_vencimento;
        $vencimento = explode('-', $vencimento);
        return $vencimento[0];
    }

    public function getReferencia()
    {
        return "{$this->getMes()}/{$this->getAno()}";
    }
}
