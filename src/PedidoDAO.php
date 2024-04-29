<?php

class PedidoDAO {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function criarPedido($pedido) {
        $sql = "INSERT INTO pedido (cliente_id, endereco_id, valorTotal, valorFrete, desconto, formaPagamento) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $clienteId = $pedido->getCliente()->getId();
        $enderecoId = $pedido->getEndereco()->getId();
        $valorTotal = $pedido->getValorTotal();
        $valorFrete = $pedido->getValorFrete();
        $desconto = $pedido->getDesconto();
        $formaPagamento = $pedido->getFormaPagamento();
        $stmt->bind_param("iiddss", $clienteId, $enderecoId, $valorTotal, $valorFrete, $desconto, $formaPagamento);
        $stmt->execute();
        $pedidoId = $stmt->insert_id;
        $stmt->close();

        foreach ($pedido->getVariacoes() as $variacao) {
            $this->salvarVariacaoPedido($pedidoId, $variacao);
        }
    }

    private function salvarVariacaoPedido($pedidoId, $variacao) {
        $sql = "INSERT INTO pedido_variacao (pedido_id, variacao_id) VALUES (?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ii", $pedidoId, $variacao->getId());
        $stmt->execute();
        $stmt->close();
    }

}



?>