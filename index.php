<?php
// Inclua o arquivo de funções se tiver validação de código ou outras funções úteis aqui
// require_once 'functions.php';

// Se for exibir mensagens de erro/sucesso do cadastro, você vai precisar de uma função aqui.
// Por exemplo: validar_codigo(); // Se você usar um sistema de códigos para mensagens
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Games - Cadastro/Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            padding-top: 50px;
        }
        .container {
            max-width: 450px; /* Limita a largura do formulário para melhor visualização */
        }
    </style>
</head>
<body class="bg-light">
    <div class="container bg-white p-4 rounded shadow-sm">
        <h1 class="mb-4 text-center">Bem-vindo ao Catálogo de Games!</h1>
        <h2 class="mb-4 text-center fs-4">Crie sua conta ou faça login</h2>

        <?php
            // Este é o lugar para exibir mensagens de validação ou de erro/sucesso
            // Por exemplo, se o cadastro falhou ou se o login não foi bem-sucedido
            // require_once 'functions.php'; // Se validar_codigo() estiver em functions.php
            // validar_codigo(); // Chamada da função para exibir mensagens
        ?>

        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="cadastro-tab" data-bs-toggle="tab" data-bs-target="#cadastro-pane" type="button" role="tab" aria-controls="cadastro-pane" aria-selected="true">Cadastrar</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-pane" type="button" role="tab" aria-controls="login-pane" aria-selected="false">Login</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="cadastro-pane" role="tabpanel" aria-labelledby="cadastro-tab" tabindex="0">
                <form action="processa_cadastro.php" method="POST">
                    <div class="mb-3">
                        <label for="cadastroNome" class="form-label">Nome de Usuário:</label>
                        <input type="text" class="form-control" id="cadastroNome" name="usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="cadastroEmail" class="form-label">E-mail:</label>
                        <input type="email" class="form-control" id="cadastroEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="cadastroSenha" class="form-label">Senha:</label>
                        <input type="password" class="form-control" id="cadastroSenha" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                </form>
            </div>
            <div class="tab-pane fade" id="login-pane" role="tabpanel" aria-labelledby="login-tab" tabindex="0">
                <form action="processa_login.php" method="POST">
                    <div class="mb-3">
                        <label for="loginUsuario" class="form-label">Nome de Usuário ou E-mail:</label>
                        <input type="text" class="form-control" id="loginUsuario" name="usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginSenha" class="form-label">Senha:</label>
                        <input type="password" class="form-control" id="loginSenha" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>