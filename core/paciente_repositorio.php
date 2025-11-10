<?php
    include_once "tempo_sessao.php";
    session_start();

    include "conexao.php";

    foreach($_POST as $indice => $dado)
    {
        $$indice = htmlspecialchars($dado);
    }

    foreach($_GET as $indice => $dado)
    {
        $$indice = htmlspecialchars($dado);
    }

    switch($acao)
    {
        case "Cadastro":

        break;
    }
?>