<?php
    $bdNome = "sailus";
    $bdServidor = "localhost";
    $bdUsuario = "root";
    $bdSenha = "815674815";

    $conexao = new mysqli($bdServidor, $bdUsuario, $bdSenha, $bdNome);

    if($conexao->connect_error)
    {
        echo "Erro de conexão: " . $conexao->connect_error;
    }
?>