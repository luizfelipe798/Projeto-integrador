<?php
    include_once "tempo_sessao.php";
    session_start();

    include "conexao.php";

    $acao = $_POST['acao'];

    switch($acao)
    {
        case "Cadastro":
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];
            $dataNascimento = $_POST['dataNascimento'];
            $cpf = $_POST['cpf'];
            $genero = $_POST['genero'];
            $emailFuncionario = $_SESSION['email'];
            $tipoUsuario = "Funcionario";

            $stmtVerifica = $conexao->prepare("SELECT cpf, email FROM Paciente WHERE email = ? OR cpf = ?");
            $stmtVerifica->bind_param("ss", $email, $cpf);
            $stmtVerifica->execute();

            $resultado = $stmtVerifica->get_result();

            $stmtVerifica->close();

            if($resultado->num_rows > 0)
            {
                $_SESSION['erro_cadastro_paciente'] = "Este E-mail ou este cpf já está cadastrado. Tente novamente!";


                header("Location: ../paginas/cadastrar_paciente.php");
                exit;
            }
            
            $stmtInsert = $conexao->prepare("INSERT INTO Paciente(nome, email, telefone, dataNascimento, cpf, genero)
                                       VALUES (?, ?, ?, ?, ?, ?)");

            $stmtInsert->bind_param("ssssss", $nome, $email, $telefone, $dataNascimento, $cpf, $genero);
            $stmtInsert->execute();

            if($stmtInsert->affected_rows != 1)
            {
                $_SESSION['erro_cadastro_paciente'] = "Erro ao cadastrar paciente. Tente novamente!";

                header("Location: ../paginas/cadastrar_paciente.php");
                exit;
            }

            $idPaciente = $conexao->insert_id;
            $stmtInsert->close();

            $stmtIdFuncionario = $conexao->prepare("SELECT id FROM Usuario WHERE email = ? AND tipo = ?");
            $stmtIdFuncionario->bind_param("ss", $emailFuncionario, $tipoUsuario);
            $stmtIdFuncionario->execute();

            $resultado = $stmtIdFuncionario->get_result();

            if($resultado->num_rows != 1)
            {
                $_SESSION['erro_cadastro_paciente'] = "Erro ao armazenar histórico do cadastro. Tente novamente!";

                header("Location: ../paginas/cadastrar_paciente.php");
                exit;
            }

            $idFuncionario = $resultado;

            $stmtIdFuncionario->close();

            $stmtHistorico = $conexao->prepare("INSERT INTO HistFuncPaciente(tipoAcao, idFuncionario, idPaciente)
                                                VALUES (?, ?, ?)");

            $stmtHistorico->bind_param("sii", $acao, $idFuncionario, $idPaciente);
            $stmtHistorico->execute();

            if($stmtHistorico->affected_rows != 1)
            {
                $_SESSION['erro_cadastro_paciente'] = "Erro ao armazenar histórico do cadastro. Tente novamente!";

                header("Location: ../paginas/cadastrar_paciente.php");
                exit;
            }

            $_SESSION['sucesso_paciente'] = $nome . " cadastrado(a) com sucesso!";

            $stmtHistorico->close();
            header("Location: ../paginas/inicio_pacientes.php");
            exit;
        break;

        case "editar":

        break;

        case "deletar":

        break;
    }
?>