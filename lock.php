<?php
// Inicia a sessão PHP. Essencial para acessar e manipular a superglobal $_SESSION.
// Deve ser a primeira coisa a ser feita em qualquer script que use sessões.
session_start();

// Verifica se as variáveis de sessão essenciais para o login NÃO ESTÃO DEFINIDAS.
// Isso significa que o usuário não está logado ou a sessão expirou/foi corrompida.
if (!isset($_SESSION['id']) ||
    !isset($_SESSION['usuario'])) { // 'senha' removido da checagem de sessão para boa prática de não armazená-la

    // Se alguma das variáveis de sessão necessárias não estiver definida,
    // redireciona o usuário para a página inicial (login/cadastro) com um código de erro.
    // O 'codigo=1' geralmente indica "acesso não autorizado" ou "sessão inválida".
    header('location:index.php?codigo=1');

    // É CRÍTICO usar 'exit;' após um redirecionamento com 'header()'.
    // Isso impede que o restante do script seja executado, garantindo que o redirecionamento
    // ocorra imediatamente e prevenindo que qualquer conteúdo da página restrita seja enviado ao navegador.
    exit;
}

// Se o script chegar até aqui, significa que as variáveis de sessão estão definidas,
// e o usuário é considerado logado, podendo acessar o conteúdo da página que incluiu 'lock.php'.

// Opcional: Para segurança extra contra "Session Fixation", você pode regenerar o ID da sessão
// após um login bem-sucedido ou a cada certo número de requisições. No entanto,
// para simplicidade, não é obrigatório para este projeto inicial.
// session_regenerate_id(true);
