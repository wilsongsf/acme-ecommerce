<?php

include_once './src/conexao.php';
include_once './src/Cliente.php';
include_once './src/ClienteDAO.php';

$url = $_SERVER[ 'REQUEST_URI' ];
$metodo = $_SERVER[ 'REQUEST_METHOD' ];


if ( $metodo === 'POST' && preg_match( '/^\/cliente\/?$/i', $url )) {
     // Verifica se é uma requisição POST
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // Verifica a ação a ser realizada
        switch ($input['action']) {
            case 'criar_cliente':
                criarCliente($input);
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
} elseif ($metodo === 'GET' && preg_match( '/^\/cliente\/?$/i', $url )) {
   // Verifica se é uma requisição GET
    if (isset($_GET['action'])) {
       // Verifica a ação a ser realizada
        switch ($_GET['action']) {
            case 'listar_clientes':
                listarClientes();
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
} elseif ( $metodo === 'PUT' && preg_match( '/^\/cliente\/?$/i', $url )) {
    // Verifica se é uma requisição PUT
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // Verifica a ação a ser realizada
        switch ($input['action']) {
            case 'atualizar_cliente':
                atualizarCliente($input);
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
} elseif ( $metodo === 'DELETE' && preg_match( '/^\/cliente\/?$/i', $url )) {
    // Verifica se é uma requisição DELETE
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // Verifica a ação a ser realizada
        switch ($input['action']) {
            case 'deletar_cliente':
                deletarCliente($input);
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



// Função para criar um cliente------------------------------------------------------------------------------------------------------------------------
function criarCliente($input) {
    // Validação dos dados recebidos
    if (!isset($input['nome']) || !isset($input['cpf']) || !isset($input['dataNascimento'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Dados incompletos."));
        return;
    }

    // Criar objeto Cliente
    $cliente = new Cliente($input['nome'], $input['cpf'], $input['dataNascimento']);

    // Criar ClienteDAO
    $clienteDAO = new ClienteDAO(conectar());

    // Salvar cliente
    try {
        $clienteDAO->salvar($cliente);
        // Cliente criado com sucesso
        http_response_code(201);
        echo json_encode(array("message" => "Cliente criado com sucesso."));
    } catch (Exception $e) {
        // Erro ao criar cliente
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao criar cliente: " . $e->getMessage()));
    }
}

// Função para listar todos os clientes----------------------------------------------------------------------------------------------------------------------
function listarClientes() {
    // Criar ClienteDAO
    $clienteDAO = new ClienteDAO(conectar());

    // Listar clientes
    try {
        $clientes = $clienteDAO->listar();
        // Retornar clientes em formato JSON
        http_response_code(200);
        echo json_encode($clientes);
    } catch (Exception $e) {
        // Erro ao listar clientes
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao listar clientes: " . $e->getMessage()));
    }
}

// Função para atualizar um cliente-------------------------------------------------------------------------------------------------------------------
function atualizarCliente($input) {
    // Validação dos dados recebidos
    if (!isset($input['id']) || !isset($input['nome']) || !isset($input['cpf']) || !isset($input['dataNascimento'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Dados incompletos."));
        return;
    }

    // Criar objeto Cliente
    $cliente = new Cliente($input['id'], $input['nome'], $input['cpf'],$input['dataNascimento']);

    // Criar ClienteDAO
    $clienteDAO = new ClienteDAO(conectar());

    // Atualizar cliente
    try {
        $clienteDAO->atualizar($cliente);
        // Cliente atualizado com sucesso
        http_response_code(200);
        echo json_encode(array("message" => "Cliente atualizado com sucesso."));
    } catch (Exception $e) {
        // Erro ao atualizar cliente
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao atualizar cliente: " . $e->getMessage()));
    }
}

// Função para deletar um cliente----------------------------------------------------------------------------------------------------------------------------
function deletarCliente($input) {
    // Validação dos dados recebidos
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "ID do cliente não fornecido."));
        return;
    } 

    // Criar ClienteDAO
    $clienteDAO = new ClienteDAO(conectar());

    // Deletar cliente
    try {
        $clienteDAO->deletar($input['id']);
        // Cliente deletado com sucesso
        http_response_code(200);
        echo json_encode(array("message" => "Cliente deletado com sucesso."));
    } catch (Exception $e) {
        // Erro ao deletar cliente
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao deletar cliente: " . $e->getMessage()));
    }
}

?>