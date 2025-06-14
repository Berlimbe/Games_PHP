<?php
require_once 'lock.php';      // Garante que somente o usuário logado acesse a página
require_once 'functions.php'; // Inclui funções auxiliares, como form_nao_enviado() e validar_codigo()
require_once 'conexao.php';   // Inclui a função conectar_banco()

// Inicia a sessão (embora 'lock.php' já deva iniciar, boa prática ter aqui também)
session_start();

// 1. Verifica se a requisição é POST (se o formulário foi realmente enviado)
if (form_nao_enviado()) { // Usa a função do functions.php
    header('location:tigrinho.php?codigo=1'); // Código 1: Requisição inválida
    exit;
}

// 2. Coleta os dados do formulário de cadastro de jogo
// Usamos o operador de coalescência nula (?? '') para evitar avisos se a chave não existir
$nome_jogo     = $_POST['nome'] ?? '';
$descricao     = $_POST['descricao'] ?? '';
$categoria     = $_POST['categoria'] ?? '';
$plataforma    = $_POST['plataforma'] ?? '';

// 3. Validação dos Dados
$erros = []; // Array para armazenar mensagens de erro

// Validação do Nome do Jogo (obrigatório)
if (empty($nome_jogo)) {
    $erros[] = "O nome do jogo é obrigatório.";
}

// Validação da Descrição (obrigatório)
if (empty($descricao)) {
    $erros[] = "A descrição do jogo é obrigatória.";
}

// Validação da Categoria (opcional, mas pode-se validar se está na lista pré-definida se for mais restrito)
// Exemplo: if (!in_array($categoria, ['RPG', 'MMORPG', 'Fantasia', 'FPS', 'Aventura', 'Estrategia', 'Esporte', 'Simulacao', 'Puzzle', 'Outros']) && !empty($categoria)) {
//     $erros[] = "Categoria inválida selecionada.";
// }

// Validação da Plataforma (opcional, mas pode-se validar se está na lista pré-definida)
// Exemplo: if (!in_array($plataforma, ['PC', 'PlayStation', 'Xbox', 'Nintendo', 'Mobile', 'Outras']) && !empty($plataforma)) {
//     $erros[] = "Plataforma inválida selecionada.";
// }


// 4. Se houver erros de validação, redirecionar com mensagem
if (!empty($erros)) {
    // Para simplificar, vamos usar um código genérico por enquanto.
    // Em um sistema mais avançado, você passaria os erros para tigrinho.php via sessão.
    header('location:tigrinho.php?codigo=2'); // Código 2: Campos em branco/inválidos
    exit;
}

// 5. Obtém o ID do usuário logado da sessão
$id_usuario_logado = $_SESSION['id'];

// 6. Conecta ao Banco de Dados
$conn = conectar_banco();

// 7. Prepara a Consulta SQL para Inserção (usando Prepared Statements para segurança)
$sql = "INSERT INTO tb_jogos (nome, descricao, categoria, plataforma, usuario_id)
        VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

// Verifica se a preparação da consulta falhou
if ($stmt === false) {
    // Redireciona com erro de banco de dados
    header('location:tigrinho.php?codigo=4'); // Código 4: Erro de SQL
    exit;
}

// 8. Associa os parâmetros à consulta preparada
// 'ssssi' indica que são 4 strings (nome, descricao, categoria, plataforma) e 1 inteiro (usuario_id)
if (!mysqli_stmt_bind_param($stmt, "ssssi", $nome_jogo, $descricao, $categoria, $plataforma, $id_usuario_logado)) {
    // Se a associação dos parâmetros falhar, é um erro interno
    header('location:tigrinho.php?codigo=4');
    exit;
}

// 9. Executa a consulta
if (mysqli_stmt_execute($stmt)) {
    // Sucesso no cadastro do jogo, redirecionar para a página tigrinho.php com mensagem de sucesso
    header('location:tigrinho.php?codigo=100'); // Código 100: Jogo cadastrado com sucesso
} else {
    // Se a execução falhar (erro no BD, etc.)
    header('location:tigrinho.php?codigo=4'); // Erro genérico de banco de dados
}

// 10. Fecha o statement e a conexão com o banco de dados
mysqli_stmt_close($stmt);
mysqli_close($conn);

exit; // Garantir que o script para após o redirecionamento
