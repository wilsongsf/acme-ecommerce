<?php

include_once './src/conexao.php';
include_once './src/Variacao.php';
include_once './src/VariacaoDAO.php';
include_once './src/ProdutoDAO.php';

$url = $_SERVER[ 'REQUEST_URI' ];
$metodo = $_SERVER[ 'REQUEST_METHOD' ];


if ( $metodo === 'POST' && preg_match( '/^\/variacao\/?$/i', $url )) {
    // Verifica se é uma requisição POST
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // Verifica a ação a ser realizada
        switch ($input['action']) {
            case 'criar_variacao':
                criarVariacao($input);
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
} elseif ( $metodo === 'GET' && preg_match( '/^\/variacao\/?$/i', $url )) {
    // Verifica se é uma requisição GET
    if (isset($_GET['action'])) {
        // Verifica a ação a ser realizada
        switch ($_GET['action']) {
            case 'listar_variacoes':
                listarVariacoes();
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
} elseif ( $metodo === 'PUT' && preg_match( '/^\/variacao\/?$/i', $url )) {
    // Verifica se é uma requisição PUT
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // Verifica a ação a ser realizada
        switch ($input['action']) {
            case 'atualizar_variacao':
                atualizarVariacao($input);
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
} elseif ( $metodo === 'DELETE' && preg_match( '/^\/variacao\/?$/i', $url )) {
    // Verifica se é uma requisição DELETE
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // Verifica a ação a ser realizada
        switch ($input['action']) {
            case 'deletar_variacao':
                deletarVariacao($input);
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

// Função para criar uma variação-----------------------------------------------------------------------------------------------------------------------
function criarVariacao($input) {
    // Validação dos dados recebidos
    if (!isset($input['produto_id']) || !isset($input['tamanho']) || !isset($input['estoque'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Dados incompletos."));
        return;
    }

    // Criar objeto Produto
    $produtoDAO = new ProdutoDAO(conectar());
    $produto = $produtoDAO->buscarPorId($input['produto_id']);
    if (!$produto) {
        http_response_code(404);
        echo json_encode(array("message" => "Produto não encontrado."));
        return;
    }

    // Criar objeto Variacao
    $variacao = new Variacao();
    // Definir atributos da variação
    $variacao->setProduto($produto);
    $variacao->setTamanho($input['tamanho']);
    $variacao->setQuantidadeEstoque($input['estoque']);

    // Criar VariacaoDAO
    $variacaoDAO = new VariacaoDAO(conectar());

    // Salvar variação
    try {
        $variacaoDAO->salvar($variacao);
        // Variação criada com sucesso
        http_response_code(201);
        echo json_encode(array("message" => "Variação criada com sucesso."));
    } catch (Exception $e) {
        // Erro ao criar variação
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao criar variação: " . $e->getMessage()));
    }
}

// Função para listar todas as variações------------------------------------------------------------------------------------------------------------------
function listarVariacoes() {
    // Criar VariacaoDAO
    $variacaoDAO = new VariacaoDAO(conectar());

    // Listar variações
    try {
        $variacoes = $variacaoDAO->listar();
        // Retornar variações em formato JSON
        http_response_code(200);
        echo json_encode($variacoes);
    } catch (Exception $e) {
        // Erro ao listar variações
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao listar variações: " . $e->getMessage()));
    }
}

// Função para atualizar uma variação---------------------------------------------------------------------------------------------------------------------------
function atualizarVariacao($input) {
    // Validação dos dados recebidos
    if (!isset($input['id']) || !isset($input['produto_id']) || !isset($input['tamanho']) || !isset($input['estoque'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Dados incompletos."));
        return;
    }

    // Criar objeto Produto
    $produtoDAO = new ProdutoDAO(conectar());
    $produto = $produtoDAO->buscarPorId($input['produto_id']);
    if (!$produto) {
        http_response_code(404);
        echo json_encode(array("message" => "Produto não encontrado."));
        return;
    }

    // Criar objeto Variacao
    $variacao = new Variacao();
    // Definir atributos da variação
    $variacao->setId($input['id']);
    $variacao->setProduto($produto);
    $variacao->setTamanho($input['tamanho']);
    $variacao->setQuantidadeEstoque($input['estoque']);

    // Criar VariacaoDAO
    $variacaoDAO = new VariacaoDAO(conectar());

    // Atualizar variação
    try {
        $variacaoDAO->atualizar($variacao);
        // Variação atualizada com sucesso
        http_response_code(200);
        echo json_encode(array("message" => "Variação atualizada com sucesso."));
    } catch (Exception $e) {
        // Erro ao atualizar variação
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao atualizar variação: " . $e->getMessage()));
    }
}

// Função para deletar uma variação-----------------------------------------------------------------------------------------------------------------------
function deletarVariacao($input) {
    // Validação dos dados recebidos
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "ID da variação não fornecido."));
        return;
    }

    // Criar VariacaoDAO
    $variacaoDAO = new VariacaoDAO(conectar());

    // Deletar variação
    try {
        $variacaoDAO->deletar($input['id']);
        // Variação deletada com sucesso
        http_response_code(200);
        echo json_encode(array("message" => "Variação deletada com sucesso."));
    } catch (Exception $e) {
        // Erro ao deletar variação
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao deletar variação: " . $e->getMessage()));
    }
}

?>