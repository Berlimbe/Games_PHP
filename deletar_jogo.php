<?php
require_once 'lock.php';      // Garante que somente o usuário logado acesse a página
require_once 'functions.php'; // Inclui funções auxiliares, como validar_codigo()
require_once 'conexao.php';   // Inclui a função conectar_banco()

// Inicia a sessão (embora 'lock.php' já deva iniciar, boa prática ter aqui também)
session_start();

// 1. Verifica se o ID do jogo foi fornecido via GET
if (!isset($_GET['id_jogo'])) {
    // Se o ID não foi fornecido, redireciona para a página principal com erro de requisição
    header('location:tigrinho.php?codigo=1'); // Código 1: Requisição inválida
    exit;
}

// 2. Coleta e valida o ID do jogo (convertendo para inteiro)
$id_jogo_para_deletar = (int)$_GET['id_jogo'];

// 3. Verifica se o ID do jogo é um número válido (maior que zero, por exemplo)
if ($id_jogo_para_deletar <= 0) {
    // ID inválido, redireciona com erro
    header('location:tigrinho.php?codigo=2'); // Código 2: Parâmetro inválido (pode ser adaptado um código mais específico)
    exit;
}

// 4. Obtém o ID do usuário logado da sessão
// Isso é CRÍTICO para garantir que um usuário só exclua seus próprios jogos!
$id_usuario_logado = $_SESSION['id'];

// 5. Conecta ao Banco de Dados
$conn = conectar_banco();

// 6. Prepara a Consulta SQL para Deleção (usando Prepared Statements para segurança)
// A condição WHERE inclui tanto o id_jogo quanto o usuario_id para garantir a propriedade
$sql = "DELETE FROM tb_jogos WHERE id_jogo = ? AND usuario_id = ?";

$stmt = mysqli_prepare($conn, $sql);

// Verifica se a preparação da consulta falhou
if ($stmt === false) {
    // Redireciona com erro de banco de dados
    header('location:tigrinho.php?codigo=4'); // Código 4: Erro de SQL
    exit;
}

// 7. Associa os parâmetros à consulta preparada
// 'ii' indica que ambos os parâmetros são inteiros (id_jogo e usuario_id)
if (!mysqli_stmt_bind_param($stmt, "ii", $id_jogo_para_deletar, $id_usuario_logado)) {
    // Se a associação dos parâmetros falhar, é um erro interno
    header('location:tigrinho.php?codigo=4');
    exit;
}

// 8. Executa a consulta
if (!mysqli_stmt_execute($stmt)) {
    // Se a execução falhar (erro no BD)
    header('location:tigrinho.php?codigo=5'); // Código 5: Erro ao excluir item
    exit;
}

// 9. Verifica se alguma linha foi realmente afetada pela exclusão
$linhas_afetadas = mysqli_stmt_affected_rows($stmt);

if ($linhas_afetadas > 0) {
    // Sucesso na exclusão, redirecionar para a página tigrinho.php com mensagem de sucesso
    header('location:tigrinho.php?codigo=6'); // Código 6: Item excluído com sucesso
} else {
    // Nenhuma linha foi afetada. Isso pode significar que:
    // - O id_jogo não existe
    // - O id_jogo existe, mas não pertence ao usuario_id logado (o que é o esperado se um usuário tentar deletar o jogo de outro)
    // Em ambos os casos, consideramos como um erro do ponto de vista da operação que o usuário tentou.
    header('location:tigrinho.php?codigo=5'); // Código 5: Erro ao excluir (ou item não encontrado/não pertencente)
}

// 10. Fecha o statement e a conexão com o banco de dados
mysqli_stmt_close($stmt);
mysqli_close($conn);

exit; // Garantir que o script para após o redirecionamento
