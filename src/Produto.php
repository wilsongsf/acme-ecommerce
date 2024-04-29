<?php

class Produto {
    private $id;
    private $nome;
    private $cor;
    private $imagem;
    private $preco;
    private $descricao;
    private $dataCadastro;
    private $peso;
    private $categoria;
    private $variacoes = array();

    public function __construct(
        $id = 0,
        $nome = '',
        $cor = '',
        $imagem = '',
        $preco = '',
        $descricao = '',
        $dataCadastro = '',
        $peso = 0,
        $categoria = null,
        $variacoes = null
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->cor = $cor;
        $this->imagem = $imagem;
        $this->preco = $preco;
        $this->descricao = $descricao;
        $this->dataCadastro = $dataCadastro;
        $this->peso = $peso;
        $this->categoria = $categoria;
        $this->variacoes = $variacoes;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getCor() {
        return $this->cor;
    }

    public function setCor($cor) {
        $this->cor = $cor;
    }

    public function getImagem() {
        return $this->imagem;
    }

    public function setImagem($imagem) {
        $this->imagem = $imagem;
    }

    public function getPreco() {
        return $this->preco;
    }

    public function setPreco($preco) {
        $this->preco = $preco;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function getPeso() {
        return $this->peso;
    }

    public function setPeso($peso) {
        $this->peso = $peso;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    public function adicionarVariacao($variacao) {
        $this->variacoes[] = $variacao;
    }

    public function getVariacoes() {
        return $this->variacoes;
    }

    public function setVariacoes($variacoes) {
        $this->variacoes = $variacoes;
    }
}

?>
