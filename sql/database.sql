CREATE DATABASE IF NOT EXISTS tarefas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tarefas;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    status ENUM('pendente', 'concluida') DEFAULT 'pendente',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_conclusao TIMESTAMP NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Datos de prueba - 3 usuarios
INSERT INTO usuarios (nome, email, senha) VALUES 
('João Silva', 'joao@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Maria Santos', 'maria@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Pedro Oliveira', 'pedro@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- password para todos: password

-- Tareas para João Silva
INSERT INTO tarefas (usuario_id, titulo, descricao, status) VALUES 
(1, 'Comprar mantimentos', 'Leite, pão, ovos e frutas', 'pendente'),
(1, 'Pagar contas', 'Conta de luz e água', 'concluida'),
(1, 'Estudar PHP', 'Praticar programação orientada a objetos', 'pendente'),
(1, 'Reunião com equipe', 'Preparar apresentação do projeto', 'pendente'),
(1, 'Exercícios físicos', 'Corrida no parque por 30 minutos', 'concluida'),
(1, 'Ler livro', 'Terminar capítulo 5 do livro', 'pendente'),
(1, 'Organizar escritório', 'Limpar mesa e arquivar documentos', 'concluida');

-- Tareas para Maria Santos
INSERT INTO tarefas (usuario_id, titulo, descricao, status) VALUES 
(2, 'Preparar relatório mensal', 'Analisar dados de vendas do mês', 'pendente'),
(2, 'Marcar consulta médica', 'Check-up anual com cardiologista', 'concluida'),
(2, 'Planejar viagem', 'Pesquisar hotéis e passagens', 'pendente'),
(2, 'Curso de inglês', 'Aula sobre tempos verbais', 'concluida'),
(2, 'Comprar presente aniversário', 'Presente para mãe', 'pendente'),
(2, 'Limpar jardim', 'Podar plantas e regar', 'concluida'),
(2, 'Revisar orçamento', 'Analisar gastos do mês', 'pendente');

-- Tareas para Pedro Oliveira
INSERT INTO tarefas (usuario_id, titulo, descricao, status) VALUES 
(3, 'Desenvolver novo feature', 'Implementar sistema de upload', 'pendente'),
(3, 'Testar aplicação', 'Fazer testes de integração', 'concluida'),
(3, 'Revisar código', 'Code review do pull request #45', 'pendente'),
(3, 'Atualizar documentação', 'Documentar novas APIs', 'concluida'),
(3, 'Reunião com cliente', 'Apresentar progresso do projeto', 'pendente'),
(3, 'Backup do sistema', 'Fazer backup do banco de dados', 'concluida'),
(3, 'Estudar novas tecnologias', 'Aprender sobre Docker e Kubernetes', 'pendente');