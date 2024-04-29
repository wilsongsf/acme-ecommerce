<?php
require_once 'Cliente.php';

class ClienteDAO {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function salvar($cliente) {
        $sql = "INSERT INTO cliente (nomeCompleto, cpf, dataNascimento) VALUES (?, ?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("sss", $cliente->getNomeCompleto(), $cliente->getCpf(), $cliente->getDataNascimento());
        $stmt->execute();
        $cliente->setId($stmt->insert_id);
        $stmt->close();
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM cliente WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $cliente = null;
        if ($resultado->num_rows > 0) {
            $linha = $resultado->fetch_assoc();
            $cliente = new Cliente();
            $cliente->setId($linha['id']);
            $cliente->setNomeCompleto($linha['nomeCompleto']);
            $cliente->setCpf($linha['cpf']);
            $cliente->setDataNascimento($linha['dataNascimento']);
        }
        $stmt->close();
        return $cliente;
    }

    public function atualizar($cliente) {
        $sql = "UPDATE cliente SET nomeCompleto = ?, cpf = ?, dataNascimento = ? WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("sssi", $cliente->getNomeCompleto(), $cliente->getCpf(), $cliente->getDataNascimento(), $cliente->getId());
        $stmt->execute();
        $stmt->close();
    }

    public function deletar($id) {
        $sql = "DELETE FROM cliente WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function listar() {
        $sql = "SELECT * FROM cliente";
        $resultado = $this->conexao->query($sql);
        $clientes = array();
        while ($linha = $resultado->fetch_assoc()) {
            $cliente = new Cliente();
            $cliente->setId($linha['id']);
            $cliente->setNomeCompleto($linha['nomeCompleto']);
            $cliente->setCpf($linha['cpf']);
            $cliente->setDataNascimento($linha['dataNascimento']);
            $clientes[] = $cliente;
        }
        return $clientes;
    }
}
?>