<?php

class EnderecoDAO {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function salvar($endereco) {
        $sql = "INSERT INTO endereco (logradouro, cidade, bairro, numero, cep, complemento) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ssssss", $endereco->getLogradouro(), $endereco->getCidade(), $endereco->getBairro(), $endereco->getNumero(), $endereco->getCep(), $endereco->getComplemento());
        $stmt->execute();
        $endereco->setId($stmt->insert_id);
        $stmt->close();
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM endereco WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $endereco = null;
        if ($resultado->num_rows > 0) {
            $linha = $resultado->fetch_assoc();
            $endereco = new Endereco();
            $endereco->setId($linha['id']);
            $endereco->setLogradouro($linha['logradouro']);
            $endereco->setCidade($linha['cidade']);
            $endereco->setBairro($linha['bairro']);
            $endereco->setNumero($linha['numero']);
            $endereco->setCep($linha['cep']);
            $endereco->setComplemento($linha['complemento']);
        }
        $stmt->close();
        return $endereco;
    }

    public function atualizar($endereco) {
        $sql = "UPDATE endereco SET logradouro = ?, cidade = ?, bairro = ?, numero = ?, cep = ?, complemento = ? WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ssssssi", $endereco->getLogradouro(), $endereco->getCidade(), $endereco->getBairro(), $endereco->getNumero(), $endereco->getCep(), $endereco->getComplemento(), $endereco->getId());
        $stmt->execute();
        $stmt->close();
    }

    public function deletar($id) {
        $sql = "DELETE FROM endereco WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function listar() {
        $sql = "SELECT * FROM endereco";
        $resultado = $this->conexao->query($sql);
        $enderecos = array();
        while ($linha = $resultado->fetch_assoc()) {
            $endereco = new Endereco();
            $endereco->setId($linha['id']);
            $endereco->setLogradouro($linha['logradouro']);
            $endereco->setCidade($linha['cidade']);
            $endereco->setBairro($linha['bairro']);
            $endereco->setNumero($linha['numero']);
            $endereco->setCep($linha['cep']);
            $endereco->setComplemento($linha['complemento']);
            $enderecos[] = $endereco;
        }
        return $enderecos;
    }
}


?>