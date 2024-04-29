<?php

class Variacao {
    private $id;
    private $tamanho;
    private $quantidadeEstoque;
    private $preco;
    private $produto; // Objeto Produto

    public function __construct(
        $id = 0,
        $tamanho = '',
        $quantidadeEstoque = '',
        $preco = '',
        $descricao = '',
        $produto = null
    ) {
        $this->id = $id;
        $this->tamanho = $tamanho;
        $this->quantidadeEstoque = $quantidadeEstoque;
        $this->preco = $preco;
        $this->produto = $produto;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTamanho() {
        return $this->tamanho;
    }

    public function setTamanho($tamanho) {
        $this->tamanho = $tamanho;
    }

    public function getQuantidadeEstoque() {
        return $this->quantidadeEstoque;
    }

    public function setQuantidadeEstoque($quantidadeEstoque) {
        $this->quantidadeEstoque = $quantidadeEstoque;
    }

    public function getPreco() {
        return $this->preco;
    }

    public function setPreco($preco) {
        $this->preco = $preco;
    }

    public function getProduto() {
        return $this->produto;
    }

    public function setProduto($produto) {
        $this->produto = $produto;
    }
}


?>