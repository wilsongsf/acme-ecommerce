<?php

class Cliente {
    private $id;
    private $nomeCompleto;
    private $cpf;
    private $dataNascimento;


    public function __construct(
        $id = 0,
        $nomeCompleto = '',
        $cpf = '',
        $dataNascimento = ''
    ) {
        $this->id = $id;
        $this->nomeCompleto = $nomeCompleto;
        $this->cpf = $cpf;
        $this->dataNascimento = $dataNascimento;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNomeCompleto() {
        return $this->nomeCompleto;
    }

    public function setNomeCompleto($nomeCompleto) {
        $this->nomeCompleto = $nomeCompleto;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }

}

?>