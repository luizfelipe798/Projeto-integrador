<?php
    include "conexao.php";

    $acao = $_POST['acao'];

    switch($acao)
    {
        case "visualizar":
                $stmt = $conexao->prepare("SELECT * FROM Paciente");
                $stmt->execute();
                
                $resultado = $stmt->get_result();
                $pacientes = $resultado->fetch_assoc();

                if($pacientes->num_rows > 0)
                {
                    foreach($pacientes as $paciente)
                    {
                    }
                }
                else 
                {
                }
        break;
        case "cadastro":
            $
        break;

        case "editar":

        break;

        case "deletar":

        break;
    }
?>