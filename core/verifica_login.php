<?php
    function verificar_login()
    {
        if(!isset($_SESSION['logado']))
        {
            $_SESSION['erro_login'] = "Você não pode acessar a página sem estar logado!";
            header("Location: login.php");
            exit;
        }
    }
?>