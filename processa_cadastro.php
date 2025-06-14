<?php
require_once 'funcoes.php'; // Inclui funções de validação (se houver)
require_once 'conexao.php';   // Inclui a função de conexão com o banco de dados

// 1. Verificar se a requisição é POST (se o formulário foi realmente enviado)
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    // Se não for POST, redireciona de volta para a página de cadastro com um código de erro
    header('location:index.php?codigo=1'); // Código 1 pode ser "requisição inválida"
    exit;
}

// 2. Coletar os dados do formulário
// Usamos o operador de coalescência nula (?? '') para evitar avisos se a chave não existir
$usuario = $_POST['usuario'] ?? '';
$email   = $_POST['email'] ?? '';
$senha   = $_POST['senha'] ?? '';

// 3. Validação dos Dados
$erros = []; // Array para armazenar mensagens de erro

// Validação do Nome de Usuário
if (empty($usuario)) {
    $erros[] = "Nome de usuário é obrigatório.";
} else {
    // Opcional: Adicionar mais validações para o nome de usuário (ex: min/max caracteres, caracteres permitidos)
    // Exemplo: if (strlen($usuario) < 3) { $erros[] = "Nome de usuário muito curto."; }
}

// Validação do E-mail
if (empty($email)) {
    $erros[] = "E-mail é obrigatório.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = "Formato de e-mail inválido.";
}

// Validação da Senha
if (empty($senha)) {
    $erros[] = "Senha é obrigatória.";
} elseif (strlen($senha) < 6) { // Exemplo: senha mínima de 6 caracteres
    $erros[] = "A senha deve ter no mínimo 6 caracteres.";
}

// 4. Se houver erros, redirecionar com mensagens
if (!empty($erros)) {
    // Idealmente, você passaria os erros de volta para a index.php para exibição.
    // Uma forma simples para agora é usar um código genérico ou sessões.
    // Para simplificar, vamos usar um código genérico por enquanto.
    header('location:index.php?codigo=2'); // Código 2 pode ser "campos em branco/inválidos"
    exit;
}

// 5. Hash da Senha (CRÍTICO para segurança!)
// NUNCA armazene senhas em texto puro no banco de dados.
// password_hash() cria um hash seguro da senha.
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// 6. Conectar ao Banco de Dados
$conn = conectar_banco();

// 7. Preparar e Executar a Inserção (usando Prepared Statements para segurança)
$sql = "INSERT INTO tb_usuarios (usuario, email, senha) VALUES (?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

// Verificar se a preparação da consulta falhou
if ($stmt === false) {
    // Redireciona com erro de banco de dados
    header('location:index.php?codigo=4'); // Código 4 para erro de SQL
    exit;
}

// Associar os parâmetros à consulta preparada
// 'sss' indica que os três parâmetros são strings
if (!mysqli_stmt_bind_param($stmt, "sss", $usuario, $email, $senha_hash)) {
    // Se a associação dos parâmetros falhar, é um erro interno
    header('location:index.php?codigo=4');
    exit;
}

// Executar a consulta
if (mysqli_stmt_execute($stmt)) {
    // Sucesso no cadastro, redirecionar para a página de login/home
    // Podemos redirecionar para o login.php diretamente, ou para index.php com msg de sucesso
    header('location:index.php?codigo=99'); // Código 99 para "cadastro bem-sucedido"
} else {
    // Se a execução falhar (ex: e-mail ou usuário já existem, devido ao UNIQUE no DB)
    // mysqli_errno() e mysqli_error() podem dar mais detalhes
    if (mysqli_errno($conn) == 1062) { // Código de erro para entrada duplicada
        header('location:index.php?codigo=7'); // Código 7 pode ser "usuário/e-mail já cadastrado"
    } else {
        header('location:index.php?codigo=4'); // Erro genérico de banco de dados
    }
}

// 8. Fechar o statement e a conexão com o banco de dados
mysqli_stmt_close($stmt);
mysqli_close($conn);

exit; // Garantir que o script para após o redirecionamento
