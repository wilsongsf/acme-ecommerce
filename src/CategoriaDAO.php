<?php

class CategoriaDAO {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function salvar($categoria) {
        $sql = "INSERT INTO categoria (nome, descricao) VALUES (?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ss", $categoria->getNome(), $categoria->getDescricao());
        $stmt->execute();
        $categoriaId = $stmt->insert_id;
        $stmt->close();
        $categoria->setId($categoriaId);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM categoria WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $categoria = null;
        if ($resultado->num_rows > 0) {
            $linha = $resultado->fetch_assoc();
            $categoria = new Categoria();
            $categoria->setId($linha['id']);
            $categoria->setNome($linha['nome']);
            $categoria->setDescricao($linha['descricao']);
        }
        $stmt->close();
        return $categoria;
    }

    public function listar() {
        $sql = "SELECT * FROM categoria";
        $resultado = $this->conexao->query($sql);
        $categorias = array();
        while ($linha = $resultado->fetch_assoc()) {
            $categoria = new Categoria();
            $categoria->setId($linha['id']);
            $categoria->setNome($linha['nome']);
            $categoria->setDescricao($linha['descricao']);
            $categorias[] = $categoria;
        }
        return $categorias;
    }

    public function atualizar($categoria) {
        $sql = "UPDATE categoria SET nome = ?, descricao = ? WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ssi", $categoria->getNome(), $categoria->getDescricao(), $categoria->getId());
        $stmt->execute();
        $stmt->close();
    }

    public function deletar($id) {
        $sql = "DELETE FROM categoria WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}




?>