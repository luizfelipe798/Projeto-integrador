<?php
    function saudacao() : string
    {
        $saudacao = "Olá";

        if($_SESSION['usuario']['tipoUsuario'] == 'Medico') $saudacao .= " Dr.";

        $saudacao .= " " . $_SESSION['usuario']['nome'] . "! O que você gostaria de fazer agora?";

        return $saudacao;
    }

    function verificarEdicao() : void
    {
        if(!isset($_GET['editar'])) echo "disabled";
    }
?>