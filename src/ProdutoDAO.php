<?php
require_once 'CategoriaDAO.php';

class ProdutoDAO {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function salvar($produto) {
        $sql = "INSERT INTO produto (nome, cor, imagem, preco, descricao, dataCadastro, peso, categoria_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("sssdssdi", $produto->getNome(), $produto->getCor(), $produto->getImagem(), $produto->getPreco(), $produto->getDescricao(), $produto->getDataCadastro(), $produto->getPeso(), $produto->getCategoria()->getId());
        $stmt->execute();
        $produtoId = $stmt->insert_id;
        $stmt->close();

        // Salvar as variações do produto
        foreach ($produto->getVariacoes() as $variacao) {
            $this->salvarVariacaoProduto($produtoId, $variacao);
        }
    }

    private function salvarVariacaoProduto($produtoId, $variacao) {
        $sql = "INSERT INTO variacao (tamanho, quantidadeEstoque, preco, produto_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("sidi", $variacao->getTamanho(), $variacao->getQuantidadeEstoque(), $variacao->getPreco(), $produtoId);
        $stmt->execute();
        $stmt->close();
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM produto WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $produto = null;
        if ($resultado->num_rows > 0) {
            $linha = $resultado->fetch_assoc();
            $produto = new Produto();
            $produto->setId($linha['id']);
            $produto->setNome($linha['nome']);
            $produto->setCor($linha['cor']);
            $produto->setImagem($linha['imagem']);
            $produto->setPreco($linha['preco']);
            $produto->setDescricao($linha['descricao']);
            $produto->setDataCadastro($linha['dataCadastro']);
            $produto->setPeso($linha['peso']);
            // Obter a categoria do produto
            $categoriaDAO = new CategoriaDAO($this->conexao);
            $categoria = $categoriaDAO->buscarPorId($linha['categoria_id']);
            $produto->setCategoria($categoria);
            // Obter as variações do produto
            $produto->setVariacoes($this->buscarVariacoesPorProdutoId($linha['id']));
        }
        $stmt->close();
        return $produto;
    }

    private function buscarVariacoesPorProdutoId($produtoId) {
        $sql = "SELECT * FROM variacao WHERE produto_id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $produtoId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $variacoes = array();
        while ($linha = $resultado->fetch_assoc()) {
            $variacao = new Variacao();
            $variacao->setId($linha['id']);
            $variacao->setTamanho($linha['tamanho']);
            $variacao->setQuantidadeEstoque($linha['quantidadeEstoque']);
            $variacao->setPreco($linha['preco']);
            // Não é necessário definir o produto aqui, pois já está associado ao produto
            $variacoes[] = $variacao;
        }
        $stmt->close();
        return $variacoes;
    }

    public function listar() {
        $sql = "SELECT * FROM produto";
        $resultado = $this->conexao->query($sql);
        $produtos = array();
        while ($linha = $resultado->fetch_assoc()) {
            $produto = $this->buscarPorId($linha['id']);
            $produtos[] = $produto;
        }
        return $produtos;
    }

    public function atualizar($produto) {
        $sql = "UPDATE produto SET nome = ?, cor = ?, imagem = ?, preco = ?, descricao = ?, dataCadastro = ?, peso = ?, categoria_id = ? WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("sssdssdii", $produto->getNome(), $produto->getCor(), $produto->getImagem(), $produto->getPreco(), $produto->getDescricao(), $produto->getDataCadastro(), $produto->getPeso(), $produto->getCategoria()->getId(), $produto->getId());
        $stmt->execute();
        $stmt->close();

        // Atualizar as variações do produto
        foreach ($produto->getVariacoes() as $variacao) {
            $this->atualizarVariacaoProduto($variacao);
        }
    }

    private function atualizarVariacaoProduto($variacao) {
        $sql = "UPDATE variacao SET tamanho = ?, quantidadeEstoque = ?, preco = ? WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("sidi", $variacao->getTamanho(), $variacao->getQuantidadeEstoque(), $variacao->getPreco(), $variacao->getId());
        $stmt->execute();
        $stmt->close();
    }

    public function deletar($id) {
        // Deletar as variações associadas ao produto
        $this->deletarVariacoesDoProduto($id);

        // Deletar o produto
        $sql = "DELETE FROM produto WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    private function deletarVariacoesDoProduto($produtoId) {
        $sql = "DELETE FROM variacao WHERE produto_id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $produtoId);
        $stmt->execute();
        $stmt->close();
    }
}




?>