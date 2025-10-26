Sistema de Gestão de Tarefas (Tarefas)

Descrição Geral

Sistema web para gestão de tarefas pessoais desenvolvido em PHP 8+, MySQL, Bootstrap 5 e jQuery. Permite aos usuários criar, editar, excluir e marcar tarefas como concluídas através de uma interface moderna e responsiva.

Funcionalidades Principais
    Autenticação de usuários (cadastro, login, logout)
    CRUD completo de tarefas (criar, ler, editar, excluir)
    Filtros de tarefas (todas, pendentes, concluídas)
    Interface responsiva com Bootstrap 5
    Operações assíncronas com jQuery/AJAX
    Marcar tarefas como concluídas sem recarregar a página

Guia de Instalação
Pré-requisitos
    PHP 8.0 ou superior
    MySQL 5.7 ou superior
    Apache/Nginx
    Composer

Passo a Passo
1. Clonar/Download do Projeto
cd tarefas

2. Configurar Banco de Dados
Executar o script SQL em: sql/database.sql

3. Configurar Aplicação
Editar app/config/database.php:

return [
    'host' => 'seu servidor',
    'dbname' => 'seu nome do db',
    'username' => 'seu usuário',
    'password' => 'sua senha',
    'charset' => 'utf8mb4'
];

Editar app/config/system.php:

return [
    'base_url' => 'http://dominio.com',
    'site_name' => 'Nome do app',
];

4. Instalar Dependências
composer install
composer dump-autoload

5. Configurar Servidor Web
Apache: Configure o DocumentRoot para apontar para /caminho/para/tarefas/public

6. Acessar a Aplicação
http://dominio.com

Credenciais de Teste
    Email: joao@email.com / maria@email.com / pedro@email.com
    Senha: password

Estrutura do Código

tarefas/
├── app/
│   ├── config/         # Configurações
│   ├── controllers/    # Controladores 
│   ├── models/         # Modelos
│   ├── requests/       # Rules
│   ├── utils/          # Utilitários
│   └── views/          # Views
├── public/             # Arquivos públicos
│   ├── api/            # Endpoints API
│   ├── assets/         # CSS, JS, imagens
│   └── *.php           # Entradas da aplicação
├── docs/               # Documentação
├── sql/                # Scripts do banco
└── vendor/             # Dependências Composer

Arquitetura
Padrão MVC Modificado
    Models (app/models/): Interação com banco de dados
    Controllers (app/controllers/): Lógica de negócio
    Views (app/views/): Apresentação (HTML + PHP)

Fluxo de Requisição
Usuário → public/*.php → Controller → Model → View → Resposta

Banco de Dados
Tabela usuarios
id, nome, email, senha, data_criacao

Tabela tarefas
id, usuario_id, titulo, descricao, status, data_criacao, data_conclusao

Segurança
    Senhas armazenadas com password_hash()
    Proteção contra SQL Injection com PDO
    Validação de dados no servidor
    Sistema de sessões para autenticação

Tecnologias Utilizadas
Backend
    PHP 8+: Linguagem principal
    MySQL: Banco de dados
    PDO: Conexão com banco

Frontend
    Bootstrap 5: Framework CSS
    jQuery: Manipulação DOM e AJAX

Ferramentas
    Composer: Gerenciamento de dependências
    Git: Controle de versão

Desenvolvido com PHP, Bootstrap e jQuery