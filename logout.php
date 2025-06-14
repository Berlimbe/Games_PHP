<?php
// Inicia a sessão PHP. Para que as funções de sessão (unset, destroy)
// possam manipular a sessão atual, ela deve ser iniciada primeiro.
session_start();

// Limpa todas as variáveis de sessão.
// Isso remove os dados do usuário (ID, nome, etc.) da superglobal $_SESSION.
unset($_SESSION);

// Destrói todos os dados registrados na sessão.
// Isso apaga o arquivo de sessão correspondente no servidor, invalidando a sessão.
session_destroy();

// Redireciona o usuário para a página inicial (index.php) após o logout.
// O navegador do usuário será instruído a carregar a index.php.
header('location:index.php');

// É CRÍTICO usar 'exit;' após um redirecionamento com 'header()'.
// Isso garante que o script pare de executar imediatamente e o redirecionamento aconteça.
exit;
