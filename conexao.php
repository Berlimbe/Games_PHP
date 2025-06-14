<?php
function conectar_banco() {

    $servidor   = 'localhost:3307'; // Certifique-se que a porta está correta para seu XAMPP
    $usuario    = 'root';
    $senha      = '';
    $banco      = 'bd_logingames'; // << MUDAR ESTA LINHA PARA O NOVO NOME DO BANCO!

    $conn = mysqli_connect($servidor, $usuario, $senha, $banco);

    if (!$conn) {
        exit("Erro na conexão: " . mysqli_connect_error());
    }

    return $conn;
}

