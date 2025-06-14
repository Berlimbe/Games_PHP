<?php
require_once 'funcoes.php'; // Inclui funções auxiliares, como validar_codigo()
require_once 'conexao.php';   // Inclui a função conectar_banco()

// 1. Inicia a sessão (necessário para registrar dados da sessão após o login)
session_start();

// 2. Verifica se a requisição é POST (se o formulário foi realmente enviado)
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    // Se não for POST, redireciona de volta para a página de login/cadastro com um código de erro
    header('location:index.php?codigo=1'); // Código 1: Requisição inválida
    exit;
}

// 3. Coleta os dados do formulário de login
$usuario_ou_email = $_POST['usuario'] ?? ''; // Campo 'usuario' no form pode ser nome de usuário ou e-mail
$senha_digitada   = $_POST['senha'] ?? '';

// 4. Validação dos Dados do Formulário (básica: campos vazios)
if (empty($usuario_ou_email) || empty($senha_digitada)) {
    // Redireciona de volta para a index.php com um código de erro
    header('location:index.php?codigo=2'); // Código 2: Campos em branco
    exit;
}

// 5. Conecta ao Banco de Dados
$conn = conectar_banco();

// 6. Prepara a Consulta SQL para buscar o usuário (usando Prepared Statements)
// Buscamos pelo campo 'usuario' OU pelo campo 'email'
$sql = "SELECT id, usuario, senha, email FROM tb_usuarios WHERE usuario = ? OR email = ?";

$stmt = mysqli_prepare($conn, $sql);

// Verifica se a preparação da consulta falhou
if ($stmt === false) {
    header('location:index.php?codigo=4'); // Código 4: Erro de SQL
    exit;
}

// Associa os parâmetros à consulta preparada (ambos são strings 'ss')
if (!mysqli_stmt_bind_param($stmt, "ss", $usuario_ou_email, $usuario_ou_email)) {
    header('location:index.php?codigo=4'); // Erro ao associar parâmetros
    exit;
}

// Executa a consulta
if (!mysqli_stmt_execute($stmt)) {
    header('location:index.php?codigo=4'); // Erro ao executar consulta
    exit;
}

// Armazena o resultado da consulta para buscar os dados
mysqli_stmt_store_result($stmt);

// 7. Verifica se o usuário foi encontrado
if (mysqli_stmt_num_rows($stmt) == 0) {
    // Usuário não encontrado, redireciona com erro de credenciais inválidas
    header('location:index.php?codigo=3'); // Código 3: Usuário/senha inválidos
    exit;
}

// 8. Associa os resultados do banco de dados a variáveis PHP
// Importante: a ordem das variáveis deve corresponder à ordem das colunas no SELECT
mysqli_stmt_bind_result($stmt, $id_db, $usuario_db, $senha_hash_db, $email_db);

// Obtém a linha (o resultado da consulta)
mysqli_stmt_fetch($stmt);

// 9. Verifica a Senha (CRÍTICO: usando password_verify())
// Compara a senha digitada pelo usuário com o HASH armazenado no banco de dados
if (password_verify($senha_digitada, $senha_hash_db)) {
    // Senha CORRETA! Usuário autenticado com sucesso.

    // 10. Iniciar/Regenerar a Sessão (boa prática de segurança para prevenir Session Fixation)
    // Se a sessão já foi iniciada por session_start() no topo, não haverá problema.
    // session_regenerate_id(true); // Descomente para segurança extra, gera novo ID de sessão.

    // 11. Registrar dados do usuário na sessão
    $_SESSION['id'] = $id_db;
    $_SESSION['usuario'] = $usuario_db;
    $_SESSION['email'] = $email_db; // Armazena o e-mail também, se necessário

    // 12. Redirecionar para a página restrita
    header('location:restrita.php');
    exit;

} else {
    // Senha INCORRETA!
    header('location:index.php?codigo=3'); // Código 3: Usuário/senha inválidos
    exit;
}

// 13. Fecha o statement e a conexão com o banco de dados
mysqli_stmt_close($stmt);
mysqli_close($conn);

// O 'exit;' final já está coberto pelos 'exit;' dentro dos ifs de redirecionamento.
