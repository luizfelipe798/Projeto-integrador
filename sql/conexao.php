<?php
    $nomeServidor = "localhost";
    $nomeUsuario = "root";
    $porta = "3306";
    $senha = "815674815";
    $nomeBD = "sailus";

    $conexao = new mysqli_connect($nomeServidor, $nomeUsuario, $senha, $nomeBD);

    if($conexao -> connect_error) 
    {
        die("A conexão falhou: " . $conexao -> connect_error);
    }
?>