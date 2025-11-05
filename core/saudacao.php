<?php
    function saudacao() : string
    {
        $saudacao = "";
        if($_SESSION['usuario']['tipoUsuario'] === "Medico")
        {
            $saudacao .= "Dr. ";
        }

        $saudacao .= "{$_SESSION['usuario']['nome']}!";
        
        return $saudacao;
    }
?>