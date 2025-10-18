<?php
    include "conexao.php";

    $acao = $_POST['acao'];
    $id = $_POST['id'];

    switch($acao)
    {
        case "cadastro":
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];
            $dataNascimento = $_POST['dataNascimento'];
            $cpf = $_POST['cpf'];
            $genero = $_POST['genero'];
            
            $stmt = $conexao->prepare("INSERT INTO Paciente(nome, email, telefone, dataNascimento, cpf, genero)
                                       VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nome, $email, $telefone, $dataNascimento, $cpf, $genero);
            $stmt->execute();

            if($stmt->affected_rows != 1)
            {
                $_SESSION['erro_cadastro_paciente'] = "Erro ao cadastrar paciente. Tente novamente!";

                header("Location: ../paginas/cadastrar_paciente.php");
                exit;
            }
            
            

        break;

        case "editar":

        break;

        case "deletar":

        break;
    }
?>