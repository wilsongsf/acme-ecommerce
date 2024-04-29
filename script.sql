CREATE DATABASE acme;

USE acme;

CREATE TABLE cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomeCompleto VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    dataNascimento DATE NOT NULL
);

CREATE TABLE endereco (
    id INT AUTO_INCREMENT PRIMARY KEY,
    logradouro VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    cep VARCHAR(9) NOT NULL,
    complemento VARCHAR(100)
);

CREATE TABLE categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descricao TEXT
);

CREATE TABLE produto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cor VARCHAR(50),
    imagem VARCHAR(255),
    preco DECIMAL(10, 2) NOT NULL,
    descricao TEXT,
    dataCadastro DATE NOT NULL,
    peso DECIMAL(10, 2) NOT NULL,
    categoria_id INT NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES categoria(id)
);

CREATE TABLE variacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tamanho VARCHAR(20) NOT NULL,
    quantidadeEstoque INT NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    produto_id INT NOT NULL,
    FOREIGN KEY (produto_id) REFERENCES produto(id)
);

CREATE TABLE pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enderecoEntrega_id INT NOT NULL,
    cliente_id INT NOT NULL,
    valorTotal DECIMAL(10, 2) NOT NULL,
    valorFrete DECIMAL(10, 2) NOT NULL,
    desconto DECIMAL(10, 2) NOT NULL,
    formaPagamento ENUM('PIX', 'Boleto', 'Cartao') NOT NULL,
    FOREIGN KEY (enderecoEntrega_id) REFERENCES endereco(id),
    FOREIGN KEY (cliente_id) REFERENCES cliente(id)
);

CREATE TABLE pedido_variacao (
    pedido_id INT,
    variacao_id INT,
    FOREIGN KEY (pedido_id) REFERENCES pedido(id),
    FOREIGN KEY (variacao_id) REFERENCES variacao(id),
    PRIMARY KEY (pedido_id, variacao_id)
);




-- Inserir clientes
INSERT INTO cliente (nomeCompleto, cpf, dataNascimento) VALUES
('João Silva', '123.456.789-00', '1990-05-15'),
('Maria Souza', '987.654.321-00', '1985-08-20'),
('Pedro Oliveira', '456.789.123-00', '1998-02-10');

-- Inserir endereços
INSERT INTO endereco (logradouro, cidade, bairro, numero, cep, complemento) VALUES
('Rua A', 'São Paulo', 'Centro', '123', '01000-000', NULL),
('Avenida B', 'Rio de Janeiro', 'Copacabana', '456', '22000-000', 'Apartamento 101'),
('Rua C', 'Porto Alegre', 'Moinhos de Vento', '789', '90000-000', NULL);

-- Inserir categorias
INSERT INTO categoria (nome, descricao) VALUES
('Roupas', 'Roupas masculinas, femininas e infantis.'),
('Calçados', 'Calçados para todas as idades.'),
('Acessórios', 'Acessórios como bolsas, cintos, chapéus, etc.');

-- Inserir produtos
INSERT INTO produto (nome, cor, imagem, preco, descricao, dataCadastro, peso, categoria_id) VALUES
('Camiseta Masculina', 'Azul', 'camiseta.jpg', 49.99, 'Camiseta de algodão confortável.', '2024-04-01', 0.3, 1),
('Vestido Floral', 'Rosa', 'vestido.jpg', 89.99, 'Vestido floral elegante para ocasiões especiais.', '2024-04-02', 0.5, 1),
('Tênis Esportivo', 'Preto', 'tenis.jpg', 129.99, 'Tênis esportivo para corrida.', '2024-04-03', 0.7, 2),
('Bolsa de Couro', 'Marrom', 'bolsa.jpg', 199.99, 'Bolsa de couro legítimo com alças ajustáveis.', '2024-04-04', 1.0, 3);

-- Inserir variações de produtos
INSERT INTO variacao (tamanho, quantidadeEstoque, preco, produto_id) VALUES
('P', 50, 49.99, 1),
('M', 30, 49.99, 1),
('G', 20, 49.99, 1),
('PP', 40, 89.99, 2),
('P', 30, 89.99, 2),
('M', 20, 89.99, 2),
('40', 25, 129.99, 3),
('41', 20, 129.99, 3),
('42', 15, 129.99, 3),
('Único', 10, 199.99, 4);

-- Inserir pedidos
INSERT INTO pedido (enderecoEntrega_id, cliente_id, valorTotal, valorFrete, desconto, formaPagamento) VALUES
(1, 1, 149.99, 10.00, 0.00, 'Cartao'),
(2, 2, 99.99, 10.00, 0.00, 'Boleto'),
(3, 3, 189.99, 10.00, 0.00, 'PIX');

-- Inserir variações de pedidos
INSERT INTO pedido_variacao (pedido_id, variacao_id) VALUES
(1, 1),
(2, 4),
(3, 7),
(3, 10);

