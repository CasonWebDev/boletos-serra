<?php


namespace App\Http\Classes;


class Boleto
{
    private $arquivo;
    private $nome;
    private $mes;
    private $ano;
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
        return $this->mes;
    }

    /**
     * @param mixed $mes
     */
    public function setMes($mes): void
    {
        $this->mes = $mes;
    }

    /**
     * @return mixed
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param mixed $ano
     */
    public function setAno($ano): void
    {
        $this->ano = $ano;
    }

    public function getReferencia()
    {
        return "{$this->mes}/{$this->ano}";
    }
}
