<?php
//Essa é a página restrita, mas com um nome bom

require_once 'lock.php'; // Garante que somente o usuário logado acesse a página
require_once 'funcoes.php'; // Inclui funções auxiliares como validar_codigo()
require_once 'conexao.php';   // Inclui a função conectar_banco()

// Inicia a sessão (embora 'lock.php' já deva iniciar, boa prática ter aqui também)
session_start();

// Obtém o ID e o nome de usuário da sessão para uso na página e consultas ao BD
$id_usuario_logado = $_SESSION['id'];
$nome_usuario_logado = $_SESSION['usuario'];

// Instancia a conexão com o banco de dados (será fechada no final do script)
$conn = conectar_banco();

// --- Lógica para processar a exclusão de jogo (Pode ser refatorado para um 'processa_exclusao.php' futuramente) ---
// Note: Essa lógica aqui é apenas para demonstração. O ideal é que o deletar_jogo.php processe isso.
// Mas se você quiser uma página única, pode ser assim. Por enquanto, o deletar_jogo.php é separado.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Games - Meus Jogos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            padding-top: 20px;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table img {
            max-width: 50px; /* Para ícones de exclusão/edição, se usar */
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">

        <h1 class="mb-4 text-center">Catálogo de Games</h1>
        <h2 class="mb-4 text-center fs-4">Bem-vindo(a), <?= htmlspecialchars($nome_usuario_logado); ?>!</h2>

        <nav class="mb-4 text-center">
            <a href="index.php" class="btn btn-outline-secondary btn-sm me-2">Home (Login/Cadastro)</a>
            <a href="tigrinho.php" class="btn btn-outline-primary btn-sm me-2">Meus Jogos</a>
            <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
        </nav>

        <?php
            // Exibe mensagens de feedback (sucesso, erro, etc.)
            validar_codigo();
        ?>

        <hr class="my-4">

        <h3>Cadastrar Novo Jogo</h3>
        <form action="cadastrar_jogo.php" method="post" class="mb-5">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Jogo:</label>
                <input type="text" name="nome" id="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea name="descricao" id="descricao" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria:</label>
                <select name="categoria" id="categoria" class="form-select">
                    <option value="">Selecione uma categoria</option>
                    <option value="RPG">RPG</option>
                    <option value="MMORPG">MMORPG</option>
                    <option value="Fantasia">Fantasia</option>
                    <option value="FPS">FPS</option>
                    <option value="Aventura">Aventura</option>
                    <option value="Estrategia">Estratégia</option>
                    <option value="Esporte">Esporte</option>
                    <option value="Simulacao">Simulação</option>
                    <option value="Puzzle">Puzzle</option>
                    <option value="Outros">Outros</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="plataforma" class="form-label">Plataforma:</label>
                <select name="plataforma" id="plataforma" class="form-select">
                    <option value="">Selecione uma plataforma</option>
                    <option value="PC">PC</option>
                    <option value="PlayStation">PlayStation</option>
                    <option value="Xbox">Xbox</option>
                    <option value="Nintendo">Nintendo</option>
                    <option value="Mobile">Mobile</option>
                    <option value="Outras">Outras</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Adicionar Jogo</button>
        </form>

        <hr class="my-4">

        <h3>Seus Jogos Cadastrados</h3>
        <?php
        // Cria SELECT para buscar jogos do usuário logado
        // ATENÇÃO: PARA MÁXIMA SEGURANÇA, ISSO DEVERIA USAR PREPARED STATEMENTS
        // Exemplo seguro com Prepared Statements:
        $sql_select_jogos = "SELECT id_jogo, nome, categoria, plataforma, descricao FROM tb_jogos WHERE usuario_id = ?";
        $stmt_select = mysqli_prepare($conn, $sql_select_jogos);

        if ($stmt_select === false) {
            echo "<p class='text-danger'>Erro ao preparar a consulta de jogos: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
        } else {
            mysqli_stmt_bind_param($stmt_select, "i", $id_usuario_logado);
            mysqli_stmt_execute($stmt_select);
            $resultado = mysqli_stmt_get_result($stmt_select); // Obtém o resultado para poder usar fetch_assoc

            if ($resultado->num_rows > 0) {
                echo '<div class="table-responsive">'; // Ajuda com tabelas em telas pequenas
                echo '<table class="table table-striped table-hover">';
                echo '<thead class="table-dark">';
                echo '<tr>';
                echo '<th>Nome do Jogo</th>';
                echo '<th>Categoria</th>';
                echo '<th>Plataforma</th>';
                // echo '<th>Descrição</th>'; // Opcional: exibir descrição na tabela
                echo '<th class="text-center">Ações</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($jogo_atual = mysqli_fetch_assoc($resultado)) {
                    $id_jogo = $jogo_atual['id_jogo'];
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($jogo_atual['nome']) . '</td>';
                    echo '<td>' . htmlspecialchars($jogo_atual['categoria']) . '</td>';
                    echo '<td>' . htmlspecialchars($jogo_atual['plataforma']) . '</td>';
                    // echo '<td>' . htmlspecialchars($jogo_atual['descricao']) . '</td>';
                    echo '<td class="text-center">';
                    // Link para deletar o jogo
                    echo '<a class="btn btn-danger btn-sm" href="deletar_jogo.php?id_jogo=' . $id_jogo . '">Excluir</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
                echo '</div>'; // Fecha table-responsive
            } else {
                echo "<p class='alert alert-info'>Você não possui jogos cadastrados ainda.</p>";
            }

            mysqli_stmt_close($stmt_select);
        }

        // Fechar a conexão com o banco de dados
        mysqli_close($conn);
        ?>

    </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>