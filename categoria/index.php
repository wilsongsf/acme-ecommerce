<?php

include_once './src/conexao.php';
include_once './src/Categoria.php';
include_once './src/CategoriaDAO.php';

$url = $_SERVER[ 'REQUEST_URI' ];
$metodo = $_SERVER[ 'REQUEST_METHOD' ];


if ( $metodo === 'POST' && preg_match( '/^\/categoria\/?$/i', $url )) {
    // Verifica se é uma requisição POST
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // Verifica a ação a ser realizada
        switch ($input['action']) {
            case 'criar_categoria':
                criarCategoria($input);
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
} elseif ($metodo === 'GET' && preg_match( '/^\/categoria\/?$/i', $url)) {
    // Verifica se é uma requisição GET
    if (isset($_GET['action'])) {
        // Verifica a ação a ser realizada
        switch ($_GET['action']) {
            case 'listar_categorias':
                listarCategorias();
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
} elseif ( $metodo === 'PUT' && preg_match( '/^\/categoria\/?$/i', $url )) {
    // Verifica se é uma requisição PUT
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // Verifica a ação a ser realizada
        switch ($input['action']) {
            case 'atualizar_categoria':
                atualizarCategoria($input);
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
} elseif ( $metodo === 'DELETE' && preg_match( '/^\/categoria\/?$/i', $url )) {
    // Verifica se é uma requisição DELETE
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // Verifica a ação a ser realizada
        switch ($input['action']) {
            case 'deletar_categoria':
                deletarCategoria($input);
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



// Função para criar uma categoria-----------------------------------------------------------------------------------------------------------
function criarCategoria($input) {
    // Validação dos dados recebidos
    if (!isset($input['nome']) || !isset($input['descricao'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Dados incompletos."));
        return;
    }

    // Criar objeto Categoria
    $categoria = new Categoria();
    // Definir atributos da categoria
    $categoria->setNome($input['nome']);
    $categoria->setDescricao($input['descricao']);

    // Criar CategoriaDAO
    $categoriaDAO = new CategoriaDAO(conectar());

    // Salvar categoria
    try {
        $categoriaDAO->salvar($categoria);
        // Categoria criada com sucesso
        http_response_code(201);
        echo json_encode(array("message" => "Categoria criada com sucesso."));
    } catch (Exception $e) {
        // Erro ao criar categoria
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao criar categoria: " . $e->getMessage()));
    }
}

// Função para listar todas as categorias-------------------------------------------------------------------------------------------------------
function listarCategorias() {
    // Criar CategoriaDAO
    $categoriaDAO = new CategoriaDAO(conectar());

    // Listar categorias
    try {
        $categorias = $categoriaDAO->listar();
        // Retornar categorias em formato JSON
        http_response_code(200);
        echo json_encode($categorias);
    } catch (Exception $e) {
        // Erro ao listar categorias
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao listar categorias: " . $e->getMessage()));
    }
}

// Função para atualizar uma categoria---------------------------------------------------------------------------------------------------------------
function atualizarCategoria($input) {
    // Validação dos dados recebidos
    if (!isset($input['id']) || !isset($input['nome']) || !isset($input['descricao'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Dados incompletos."));
        return;
    }

    // Criar objeto Categoria
    $categoria = new Categoria($input['id'], $input['nome'], $input['descricao']);

    // Criar CategoriaDAO
    $categoriaDAO = new CategoriaDAO(conectar());

    // Atualizar categoria
    try {
        $categoriaDAO->atualizar($categoria);
        // Categoria atualizada com sucesso
        http_response_code(200);
        echo json_encode(array("message" => "Categoria atualizada com sucesso."));
    } catch (Exception $e) {
        // Erro ao atualizar categoria
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao atualizar categoria: " . $e->getMessage()));
    }
}

// Função para deletar uma categoria-------------------------------------------------------------------------------------------------------------------
function deletarCategoria($input) {
    // Validação dos dados recebidos
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "ID da categoria não fornecido."));
        return;
    }

    // Criar CategoriaDAO
    $categoriaDAO = new CategoriaDAO(conectar());

    // Deletar categoria
    try {
        $categoriaDAO->deletar($input['id']);
        // Categoria deletada com sucesso
        http_response_code(200);
        echo json_encode(array("message" => "Categoria deletada com sucesso."));
    } catch (Exception $e) {
        // Erro ao deletar categoria
        http_response_code(500);
        echo json_encode(array("message" => "Erro ao deletar categoria: " . $e->getMessage()));
    }
}

?>