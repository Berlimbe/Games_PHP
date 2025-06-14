# Games PHP

---

### Sistema de Cadastro de Jogos

Este é um sistema web simples para gerenciamento de um catálogo pessoal de jogos, desenvolvido em **PHP** com **MySQL**. Ele permite que usuários se **cadastrem**, façam **login** e gerenciem seus próprios jogos, com funcionalidades de **adição, visualização e exclusão**.

---

### Tema: Cadastro de Jogos

O projeto foca em um **Sistema de Cadastro de Jogos**. Cada jogo registrado pode ter as seguintes informações:

* **Nome:** Título do jogo (obrigatório).
* **Categoria:** Tipo de jogo, selecionado a partir de categorias pré-definidas como RPG, MMORPG, Fantasia, FPS, entre outras.
* **Descrição:** Detalhes sobre o jogo (obrigatório).
* **Plataforma:** Onde o jogo pode ser jogado (ex: PC, PlayStation, Xbox, Mobile).

---

### Funcionamento

Este sistema web permite que os usuários **registrem e gerenciem seus jogos** de forma intuitiva.

Primeiro, você deve **fazer login** com uma conta existente ou **criar uma nova conta**. Uma vez autenticado, você acessa uma área onde pode **cadastrar novos jogos**, informando o nome e a descrição (ambos obrigatórios), além de escolher a categoria e a plataforma.

Todos os jogos que você cadastrar serão **listados na mesma tela**, e você pode **excluí-los** a qualquer momento. O sistema valida os dados para garantir que tudo seja preenchido corretamente e oferece uma opção de **logout seguro** para encerrar sua sessão.

---

### Usuário de Teste

Para facilitar os testes rápidos do sistema após a instalação:

* **Usuário:** `teste`
* **Senha:** `123456`
* **E-mail:** `teste@exemplo.com`

---

### Instalação

Para configurar e rodar este sistema em seu ambiente local, siga os passos abaixo:

#### Pré-requisitos

* **XAMPP** (ou qualquer ambiente LAMP/WAMP que inclua Apache e MySQL). Certifique-se de que os módulos **Apache** e **MySQL** estejam em execução no painel de controle do XAMPP.

#### Configuração do Banco de Dados

1.  Acesse o **phpMyAdmin** através do painel de controle do XAMPP (geralmente em `http://localhost/phpmyadmin`).
2.  Crie um novo banco de dados chamado **`bd_login`**.
3.  Dentro do banco de dados `bd_login`, execute o seguinte script SQL para criar as tabelas necessárias (`tb_usuarios` e `tb_jogos`):

    ```sql
    -- Criação do Banco de Dados (se ainda não existir)
    CREATE DATABASE IF NOT EXISTS bd_login;

    -- Usar o banco de dados recém-criado/existente
    USE bd_login;

    -- Tabela de Usuários
    CREATE TABLE IF NOT EXISTS tb_usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(255) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL, -- IMPORTANTE: Em sistemas reais, a senha deve ser HASHED!
        email VARCHAR(255) NOT NULL UNIQUE,
        data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    -- Tabela de Jogos (equivalente a tb_tarefas do código base)
    CREATE TABLE IF NOT EXISTS tb_jogos (
        id_jogo INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        descricao TEXT NOT NULL,
        categoria VARCHAR(100), -- Para categorias pré-definidas (RPG, MMORPG, etc.)
        plataforma VARCHAR(100), -- Para plataformas (PC, PlayStation, Xbox, etc.)
        usuario_id INT NOT NULL,
        data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES tb_usuarios(id) ON DELETE CASCADE
    );

    -- Inserir usuário de teste (senha '123456')
    -- IMPORTANTE: Em sistemas reais, a senha '123456' DEVE ser um hash gerado por password_hash()
    INSERT INTO tb_usuarios (usuario, senha, email) VALUES ('teste', '123456', 'teste@example.com');
    ```

#### Copia dos Arquivos do Projeto

1.  Localize a pasta `htdocs` dentro da sua instalação do XAMPP (ex: `C:\xampp\htdocs\` no Windows).
2.  Crie uma nova pasta para o seu projeto (sugestão: `cadastro-jogos`) dentro de `htdocs`.
3.  Copie **todos os arquivos do seu projeto PHP** para esta nova pasta.

#### Ajuste da Porta do MySQL (se necessário)

* Se o seu MySQL no XAMPP estiver usando a porta `3307` (ou outra que não seja a padrão `3306`), certifique-se de que a linha `$servidor = 'localhost:3307';` no arquivo `conexao.php` reflita a porta correta.

#### Acessar o Sistema

* Abra seu navegador web e acesse: `http://localhost/cadastro-jogos/` (substitua `cadastro-jogos` pelo nome da pasta que você criou).
