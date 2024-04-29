<?php

include_once './src/conexao.php';
include_once './src/Pedido.php';
include_once './src/PedidoDAO.php';
include_once './src/ClienteDAO.php';
include_once './src/EnderecoDAO.php';
include_once './src/VariacaoDAO.php';

$url = $_SERVER[ 'REQUEST_URI' ];
$metodo = $_SERVER[ 'REQUEST_METHOD' ];


if ( $metodo === 'POST' && preg_match( '/^\/pedido\/?$/i', $url )) {
    // Verifica se é uma requisição POST
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // Verifica a ação a ser realizada
        switch ($input['action']) {
            case 'criar_pedido':
                criarPedido($input);
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

// Função para criar um pedido----------------------------------------------------------------------------------------------------------------------------------
function criarPedido($input) {
    // Validação dos dados recebidos
    if (!isset($input['cliente_id']) || !isset($input['endereco_id']) || !isset($input['variacoes'])
        || !isset($input['valor_total']) || !isset($input['valor_frete']) || !isset($input['desconto'])
        || !isset($input['forma_pagamento'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Dados incompletos."));
        return;
    }

    // Criar objeto Cliente
    $clienteDAO = new ClienteDAO(conectar());
    $cliente = $clienteDAO->buscarPorId($input['cliente_id']);
    if (!$cliente) {
        http_response_code(404);
        echo json_encode(array("message" => "Cliente não encontrado."));
        return;
    }

    // Criar objeto Endereco
    $enderecoDAO = new EnderecoDAO(conectar());
    $endereco = $enderecoDAO->buscarPorId($input['endereco_id']);
    if (!$endereco) {
        http_response_code(404);
        echo json_encode(array("message" => "Endereço não encontrado."));
        return;
    }

    // Criar array de objetos Variacao
    $variacoes = [];
    foreach ($input['variacoes'] as $variacao_id) {
        $variacaoDAO = new VariacaoDAO(conectar());
        $variacao = $variacaoDAO->buscarPorId($variacao_id);
        if (!$variacao) {
            http_response_code(404);
            echo json_encode(array("message" => "Variação não encontrada."));
            return;
        }
        $variacoes[] = $variacao;
    }

    // Criar objeto Pedido
    $pedido = new Pedido();
    // Definir atributos do pedido
    $pedido->setCliente($cliente);
    $pedido->setEndereco($endereco);
    $pedido->setVariacoes($variacoes);
    $pedido->setValorTotal($input['valor_total']);
    $pedido->setValorFrete($input['valor_frete']);
    $pedido->setDesconto($input['desconto']);
    $pedido->setFormaPagamento($input['forma_pagamento']);

    // Criar PedidoDAO
    $pedidoDAO = new PedidoDAO(conectar());

    // Salvar pedido
    try {
        $pedidoDAO->criarPedido($pedido);
        // Pedido criado com sucesso
        http_response_code(201);
        echo json_encode(array("message" => "Pedido criado com sucesso."));
    } catch (Exception $e) {
        // Erro ao criar pedido
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao criar pedido: " . $e->getMessage()));
    }
}




?>