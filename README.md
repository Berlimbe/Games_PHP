# Games_PHP

#Sistema de Cadastro de Jogos
Este é um sistema web simples para gerenciamento de um catálogo pessoal de jogos, desenvolvido em PHP com MySQL. Ele permite que usuários cadastrem-se, façam login e gerenciem seus próprios jogos, com funcionalidades de adição, visualização e exclusão.

#Tema

O projeto foca no Cadastro de Jogos. Cada jogo registrado no sistema pode ter as seguintes informações:

-Nome: Título do jogo (obrigatório).
-Categoria: Tipo de jogo, selecionado a partir de categorias pré-definidas como RPG, MMORPG, Fantasia, FPS, entre outras.
-Descrição: Detalhes sobre o jogo (obrigatório).
-Plataforma: Onde o jogo pode ser jogado (ex: PC, PlayStation, Xbox, Mobile).

#Funcionamento
Este sistema web permite que usuários registrem e gerenciem seus jogos. 
Primeiro, você faz login (ou cria uma nova conta). 
Uma vez autenticado, você acessa uma área onde pode cadastrar novos jogos, informando o nome e descrição (obrigatórios), além de escolher a categoria (RPG, MMORPG, etc.) e a plataforma. 
Todos os jogos que você cadastrar são listados na mesma tela, e você pode excluí-los a qualquer momento. 
O sistema valida os dados para garantir que tudo seja preenchido corretamente e oferece uma opção de logout seguro.

#Usuário / Teste
Para facilitar os testes rápidos do sistema:
-Usuário: teste
-Senha: 123456
-E-mail: teste@exemplo.com

#Instalação 
Para configurar e rodar este sistema em seu ambiente local, siga os passos abaixo:

Pré-requisitos:
XAMPP (ou qualquer ambiente LAMP/WAMP que inclua Apache e MySQL). Certifique-se de que os módulos Apache e MySQL estejam em execução no painel de controle do XAMPP.

Configuração do Banco de Dados:
Acesse o phpMyAdmin através do painel de controle do XAMPP (geralmente em http://localhost/phpmyadmin).
Crie um novo banco de dados chamado bd_login.
