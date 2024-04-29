<?php 

class Pedido {
    private $id;
    private $cliente; // Objeto Cliente
    private $endereco; // Objeto Endereco
    private $variacoes = array(); // Array de objetos Variacao
    private $valorTotal;
    private $valorFrete;
    private $desconto;
    private $formaPagamento;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCliente() {
        return $this->cliente;
    }

    public function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    public function getEndereco() {
        return $this->endereco;
    }

    public function setEndereco($endereco) {
        $this->endereco = $endereco;
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

    public function getValorTotal() {
        return $this->valorTotal;
    }

    public function setValorTotal($valorTotal) {
        $this->valorTotal = $valorTotal;
    }

    public function getValorFrete() {
        return $this->valorFrete;
    }

    public function setValorFrete($valorFrete) {
        $this->valorFrete = $valorFrete;
    }

    public function getDesconto() {
        return $this->desconto;
    }

    public function setDesconto($desconto) {
        $this->desconto = $desconto;
    }

    public function getFormaPagamento() {
        return $this->formaPagamento;
    }

    public function setFormaPagamento($formaPagamento) {
        $this->formaPagamento = $formaPagamento;
    }

    public function calcularValorTotal() {
        $valorTotal = 0;
        foreach ($this->variacoes as $variacao) {
            $valorTotal += $variacao->getPreco();
        }
        $valorTotal += $this->valorFrete;
        if ($this->formaPagamento === 'PIX') {
            $valorTotal -= $this->desconto;
        }
        $this->valorTotal = $valorTotal;
    }
}




?>