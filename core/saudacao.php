<?php
    function saudacao() : string
    {
        $saudacao = "";

        if($_SESSION['tipo_usuario'] === "Medico")
        {
            $saudacao .= "Dr. ";
        }

        $saudacao .= "{$_SESSION['nome']}!";
        
        return $saudacao;
    }
?>