<?php
    function verificar_login() : void
    {
        if(!isset($_SESSION['usuario']['logado']))
        {
            $_SESSION['mensagem_login'] = "Você não pode acessar a página sem estar logado!";
            header("Location: login.php");
            exit;
        }
    }
?>