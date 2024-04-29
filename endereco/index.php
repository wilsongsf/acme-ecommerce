<?php

include_once './src/conexao.php';
include_once './src/Endereco.php';
include_once './src/EnderecoDAO.php';

$url = $_SERVER[ 'REQUEST_URI' ];
$metodo = $_SERVER[ 'REQUEST_METHOD' ];


if ( $metodo === 'POST' && preg_match( '/^\/endereco\/?$/i', $url )) {
    // Verifica se é uma requisição POST
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // Verifica a ação a ser realizada
        switch ($input['action']) {
            case 'criar_endereco':
                criarEndereco($input);
                break;
            default:
                // Ação não reconhecida
                http_response_code(400);
                echo json_encode(array("message" => "Ação não reconhecida."));
        }
    } else {
        // Ação não especificada
        http_response_code(400);
        echo json_encode(array("message" => "Ação não especificada."));
    }
} elseif ( $metodo === 'GET' && preg_match( '/^\/endereco\/?$/i', $url )) {
    // Verifica se é uma requisição GET
    if (isset($_GET['action'])) {
        // Verifica a ação a ser realizada
        switch ($_GET['action']) {
            case 'listar_enderecos':
                listarEnderecos();
                break;
            default:
                // Ação não reconhecida
                http_response_code(400);
                echo json_encode(array("message" => "Ação não reconhecida."));
        }
    } else {
        // Ação não especificada
        http_response_code(400);
        echo json_encode(array("message" => "Ação não especificada."));
    }
} elseif ( $metodo === 'PUT' && preg_match( '/^\/endereco\/?$/i', $url )) {
    // Verifica se é uma requisição PUT
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // Verifica a ação a ser realizada
        switch ($input['action']) {
            case 'atualizar_endereco':
                atualizarEndereco($input);
                break;
            default:
                // Ação não reconhecida
                http_response_code(400);
                echo json_encode(array("message" => "Ação não reconhecida."));
        }
    } else {
        // Ação não especificada
        http_response_code(400);
        echo json_encode(array("message" => "Ação não especificada."));
    }
} elseif ( $metodo === 'DELETE' && preg_match( '/^\/endereco\/?$/i', $url )) {
    // Verifica se é uma requisição DELETE
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // Verifica a ação a ser realizada
        switch ($input['action']) {
            case 'deletar_endereco':
                deletarEndereco($input);
                break;
            default:
                // Ação não reconhecida
                http_response_code(400);
                echo json_encode(array("message" => "Ação não reconhecida."));
        }
    } else {
        // Ação não especificada
        http_response_code(400);
        echo json_encode(array("message" => "Ação não especificada."));
    }
} else {
    // Método de requisição não suportado
    http_response_code(405);
    echo json_encode(array("message" => "Método de requisição não suportado."));
}

// Função para criar um endereço------------------------------------------------------------------------------------------------------------------------------
function criarEndereco($input) {
    // Validação dos dados recebidos
    if (!isset($input['logradouro']) || !isset($input['cidade']) || !isset($input['bairro']) || !isset($input['numero']) || !isset($input['cep']) || !isset($input['complemento'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Dados incompletos."));
        return;
    }

    // Criar objeto Endereco
    $endereco = new Endereco();
    // Definir atributos do endereço
    $endereco->setLogradouro($input['logradouro']);
    $endereco->setCidade($input['cidade']);
    $endereco->setBairro($input['bairro']);
    $endereco->setNumero($input['numero']);
    $endereco->setCep($input['cep']);
    $endereco->setComplemento($input['complemento']);

    // Criar EnderecoDAO
    $enderecoDAO = new EnderecoDAO(conectar());

    // Salvar endereço
    try {
        $enderecoDAO->salvar($endereco);
        // Endereço criado com sucesso
        http_response_code(201);
        echo json_encode(array("message" => "Endereço criado com sucesso."));
    } catch (Exception $e) {
        // Erro ao criar endereço
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao criar endereço: " . $e->getMessage()));
    }
}

// Função para listar todos os endereços-------------------------------------------------------------------------------------------------------------------------
function listarEnderecos() {
    // Criar EnderecoDAO
    $enderecoDAO = new EnderecoDAO(conectar());

    // Listar endereços
    try {
        $enderecos = $enderecoDAO->listar();
        // Retornar endereços em formato JSON
        http_response_code(200);
        echo json_encode($enderecos);
    } catch (Exception $e) {
        // Erro ao listar endereços
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao listar endereços: " . $e->getMessage()));
    }
}

// Função para atualizar um endereço--------------------------------------------------------------------------------------------------------------------------
function atualizarEndereco($input) {
    // Validação dos dados recebidos
    if (!isset($input['id']) || !isset($input['logradouro']) || !isset($input['cidade']) || !isset($input['bairro']) || !isset($input['numero']) || !isset($input['cep']) || !isset($input['complemento'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Dados incompletos."));
        return;
    }

    // Criar objeto Endereco
    $endereco = new Endereco($input['id'], $input['logradouro'], $input['cidade'], $input['bairro'], $input['numero'], $input['cep'], $input['complemento']);

    // Criar EnderecoDAO
    $enderecoDAO = new EnderecoDAO(conectar());

    // Atualizar endereço
    try {
        $enderecoDAO->atualizar($endereco);
        // Endereço atualizado com sucesso
        http_response_code(200);
        echo json_encode(array("message" => "Endereço atualizado com sucesso."));
    } catch (Exception $e) {
        // Erro ao atualizar endereço
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao atualizar endereço: " . $e->getMessage()));
    }
}

// Função para deletar um endereço------------------------------------------------------------------------------------------------------------------
function deletarEndereco($input) {
    // Validação dos dados recebidos
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "ID do endereço não fornecido."));
        return;
    }

    // Criar EnderecoDAO
    $enderecoDAO = new EnderecoDAO(conectar());

    // Deletar endereço
    try {
        $enderecoDAO->deletar($input['id']);
        // Endereço deletado com sucesso
        http_response_code(200);
        echo json_encode(array("message" => "Endereço deletado com sucesso."));
    } catch (Exception $e) {
        // Erro ao deletar endereço
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao deletar endereço: " . $e->getMessage()));
    }
}


?>