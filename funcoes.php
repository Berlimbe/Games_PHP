<?php

/**
 * Arquivo de funções auxiliares para o sistema de Cadastro de Jogos.
 */

// --- Funções de Validação de Formulário ---

/**
 * Verifica se o formulário não foi enviado via método POST.
 * Usado para prevenir acesso direto a scripts de processamento.
 *
 * @return bool Retorna true se a requisição não for POST, false caso contrário.
 */
function form_nao_enviado(): bool {
    return $_SERVER["REQUEST_METHOD"] !== "POST";
}

/**
 * Verifica se o campo 'nome' (para jogos) ou 'usuario' (para cadastro) está em branco no formulário POST.
 * Nota: Para validações mais complexas de usuário, use a abordagem de array de erros.
 *
 * @return bool Retorna true se o campo 'nome' ou 'usuario' estiver vazio, false caso contrário.
 */
function campo_nome_em_branco(): bool {
    // Esta função é uma verificação básica. Para o cadastro de usuário,
    // a validação em processa_cadastro.php com o array $erros é mais completa.
    // Aqui verifica-se se o 'nome' do jogo OU o 'usuario' do cadastro está vazio.
    return empty($_POST['nome']) && empty($_POST['usuario']); // Alterado para 'E' (&&) porque se um for usado, o outro não.
}


// --- Função de Exibição de Mensagens (validar_codigo) ---

/**
 * Exibe mensagens de feedback ao usuário com base em um código na URL (GET).
 * Os códigos são usados para indicar sucesso, erros de validação, erros de BD, etc.
 *
 * @param string|null $campo Nome do campo opcional para destacar a mensagem.
 * @return void A função não retorna nenhum valor, apenas imprime a mensagem na tela.
 */
function validar_codigo(): void {
    if (!isset($_GET['codigo'])) {
        return; // Não há código na URL, então não faz nada.
    }

    // Recebemos o valor do código da URL, convertendo-o para inteiro
    $codigo = (int)$_GET['codigo'];
    $msg = ""; // Inicializa a mensagem

    // Estrutura switch para determinar qual mensagem exibir
    switch ($codigo) {
        case 0: // Erro não especificado
            $msg = "<h3 class='text-danger'>Ocorreu um erro com sua requisição. Por favor, tente novamente.</h3>";
            break;

        case 1: // Acesso não autorizado / Requisição inválida (geralmente por acesso GET a POST script)
            $msg = "<h3 class='text-warning'>Você não tem permissão para acessar a página requisitada ou a requisição é inválida.</h3>";
            break;

        case 2: // Formulário não submetido ou campos em branco/inválidos (genérico)
            $msg = "<h3 class='text-danger'>Por favor, preencha todos os campos corretamente.</h3>";
            break;

        case 3: // Usuário ou senha inválidos no login
            $msg = "<h3 class='text-danger'>Nome de usuário ou senha inválidos! Por favor, tente novamente.</h3>";
            break;

        case 4: // Erro de SQL / Banco de Dados (genérico)
            $msg = "<h3 class='text-danger'>Ocorreu um erro ao acessar o banco de dados. Por favor, contate o suporte ou tente novamente mais tarde.</h3>";
            break;

        case 5: // Erro ao excluir tarefa/jogo
            $msg = "<h3 class='text-danger'>Ocorreu um erro ao tentar excluir o item selecionado. Por favor, contate o suporte ou tente novamente mais tarde.</h3>";
            break;

        case 6: // Sucesso na exclusão (novo código de sucesso para exclusão)
            $msg = "<h3 class='text-success'>Item excluído com sucesso!</h3>";
            break;

        case 7: // Usuário/E-mail já cadastrado (do processa_cadastro.php)
            $msg = "<h3 class='text-warning'>Este nome de usuário ou e-mail já está cadastrado. Por favor, tente outro.</h3>";
            break;

        case 99: // Cadastro de usuário bem-sucedido (do processa_cadastro.php)
            $msg = "<h3 class='text-success'>Cadastro realizado com sucesso! Faça seu login.</h3>";
            break;

        case 100: // Cadastro de jogo bem-sucedido (novo código de sucesso para cadastro de jogo)
            $msg = "<h3 class='text-success'>Jogo cadastrado com sucesso!</h3>";
            break;

        default:
            $msg = ""; // Para códigos desconhecidos, não exibe nada.
            break;
    }

    // Exibe a mensagem formatada. Adicionei classes Bootstrap para cores.
    echo $msg;
}
