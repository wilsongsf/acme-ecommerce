<?php

class VariacaoDAO {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function salvar($variacao) {
        $sql = "INSERT INTO variacao (tamanho, quantidadeEstoque, preco, produto_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("sidi", $variacao->getTamanho(), $variacao->getQuantidadeEstoque(), $variacao->getPreco(), $variacao->getProduto()->getId());
        $stmt->execute();
        $stmt->close();
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM variacao WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $variacao = null;
        if ($resultado->num_rows > 0) {
            $linha = $resultado->fetch_assoc();
            $variacao = new Variacao();
            $variacao->setId($linha['id']);
            $variacao->setTamanho($linha['tamanho']);
            $variacao->setQuantidadeEstoque($linha['quantidadeEstoque']);
            $variacao->setPreco($linha['preco']);
            // Obter o produto da variação
            $produtoDAO = new ProdutoDAO($this->conexao);
            $produto = $produtoDAO->buscarPorId($linha['produto_id']);
            $variacao->setProduto($produto);
        }
        $stmt->close();
        return $variacao;
    }

    public function listar() {
        $sql = "SELECT * FROM variacao";
        $resultado = $this->conexao->query($sql);
        $variacoes = array();
        while ($linha = $resultado->fetch_assoc()) {
            $variacao = new Variacao();
            $variacao->setId($linha['id']);
            $variacao->setTamanho($linha['tamanho']);
            $variacao->setQuantidadeEstoque($linha['quantidadeEstoque']);
            $variacao->setPreco($linha['preco']);
            // Obter o produto da variação
            $produtoDAO = new ProdutoDAO($this->conexao);
            $produto = $produtoDAO->buscarPorId($linha['produto_id']);
            $variacao->setProduto($produto);
            $variacoes[] = $variacao;
        }
        return $variacoes;
    }

    public function atualizar($variacao) {
        $sql = "UPDATE variacao SET tamanho = ?, quantidadeEstoque = ?, preco = ?, produto_id = ? WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("sidii", $variacao->getTamanho(), $variacao->getQuantidadeEstoque(), $variacao->getPreco(), $variacao->getProduto()->getId(), $variacao->getId());
        $stmt->execute();
        $stmt->close();
    }

    public function deletar($id) {
        $sql = "DELETE FROM variacao WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}




?>