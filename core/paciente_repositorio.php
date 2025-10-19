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

            $stmtVerifica = $conexao->prepare("SELECT id, excluido FROM Paciente WHERE email = ? OR cpf = ?");
            $stmtVerifica->bind_param("ss", $email, $cpf);
            $stmtVerifica->execute();

            $resultado = $stmtVerifica->get_result();

            $stmtVerifica->close();

            if($resultado->num_rows > 0)
            {   
                $pacienteEncontrado = $resultado->fetch_assoc();
                
                if($pacienteEncontrado['excluido'] == FALSE)
                {
                    $_SESSION['retorno_paciente'] = "Este E-mail ou este cpf já está cadastrado em um paciente ativo. Tente novamente!";

                    header("Location: ../paginas/cadastrar_paciente.php");
                    exit;
                }
                else
                {
                    $idPaciente = $pacienteEncontrado['id'];

                    $stmtReativar = $conexao->prepare("UPDATE Paciente
                                                       SET nome = ?, email = ?, telefone = ?,
                                                          dataNascimento = ?, cpf = ?, genero = ?,
                                                          excluido = FALSE
                                                      WHERE id = ?");
                    
                    $stmtReativar->bind_param("ssssssi", $nome, $email, $telefone, $dataNascimento, $cpf, $genero, $idPaciente);
                    $stmtReativar->execute();

                    if($stmtReativar->affected_rows < 0)
                    {
                        $_SESSION['retorno_paciente'] = "Erro ao reativar " . $nome . ". Tente novamente!";

                        $stmtReativar->close();
                        header("Location: ../paginas/cadastrar_paciente.php");
                        exit;
                    }

                    $stmtReativar->close();

                    $tipoAcao = "Reativação";
                }
            }
            else
            {
                $stmtInsert = $conexao->prepare("INSERT INTO Paciente(nome, email, telefone, dataNascimento, cpf, genero)
                                       VALUES (?, ?, ?, ?, ?, ?)");

                $stmtInsert->bind_param("ssssss", $nome, $email, $telefone, $dataNascimento, $cpf, $genero);
                $stmtInsert->execute();

                if($stmtInsert->affected_rows != 1)
                {
                    $_SESSION['retorno_paciente'] = "Erro ao cadastrar paciente. Tente novamente!";

                    header("Location: ../paginas/cadastrar_paciente.php");
                    exit;
                }

                $idPaciente = $conexao->insert_id;
                $tipoAcao = "Cadastro";

                $stmtInsert->close();
            }

            $stmtIdFuncionario = $conexao->prepare("SELECT id FROM Usuario
                                                        WHERE email = ? AND tipo = 'Funcionario'");
                                    
            $stmtIdFuncionario->bind_param("s", $emailFuncionario);
            $stmtIdFuncionario->execute();

            $resultado = $stmtIdFuncionario->get_result();

            if($resultado->num_rows != 1)
            {
                $_SESSION['retorno_paciente'] = $nome . " cadastrado(a) com sucesso, porém não foi possível registrar a ação no histórico.";

                header("Location: ../paginas/inicio_pacientes.php");
                exit;
            }

            $funcionarioID = $resultado->fetch_assoc();
            $idFuncionario = $funcionarioID['id'];

            $stmtIdFuncionario->close();

            $stmtHistorico = $conexao->prepare("INSERT INTO HistFuncPaciente(tipoAcao, idFuncionario, idPaciente)
                                                VALUES (?, ?, ?)");

            $stmtHistorico->bind_param("sii", $tipoAcao, $idFuncionario, $idPaciente);
            $stmtHistorico->execute();

            if($stmtHistorico->affected_rows != 1)
            {
                $_SESSION['retorno_paciente'] = $nome . " cadastrado(a) com sucesso, porém não foi possível registrar a ação no histórico.";

                header("Location: ../paginas/inicio_pacientes.php");
                exit;
            }

            if($tipoAcao == "Reativação")
            {
                $_SESSION['retorno_paciente'] = $nome . " reativado(a) com sucesso!";
            }
            else
            {
                $_SESSION['retorno_paciente'] = $nome . " cadastrado(a) com sucesso!";
            }

            $stmtHistorico->close();
            header("Location: ../paginas/inicio_pacientes.php");
            exit;
        break;

        case "editar":
            
        break;

        case "Exclusão":
            $idPaciente = $_POST['id'];
            $nome = $_POST['nome'];
            $emailFuncionario = $_SESSION['email'];

            $stmtObterFuncionario = $conexao->prepare("SELECT id FROM Usuario
                                                       WHERE email = ? AND tipo = 'Funcionario'");
            
            $stmtObterFuncionario->bind_param("s", $emailFuncionario);
            $stmtObterFuncionario->execute();

            $resultado = $stmtObterFuncionario->get_result();

            $stmtObterFuncionario->close();

            if($resultado->num_rows != 1)
            {
                $_SESSION['retorno_paciente'] = "Erro na identificação do funcionário na exclusão de " . $nome . ". Tente novamente!";

                header("Location: ../paginas/inicio_pacientes.php");
                exit;
            }

            $funcionario = $resultado->fetch_assoc();
            $idFuncionario = $funcionario['id'];

            $stmtHistorico = $conexao->prepare("INSERT INTO HistFuncPaciente(tipoAcao, idFuncionario, idPaciente)
                                                VALUES(?, ?, ?)");

            $stmtHistorico->bind_param("sii", $acao, $idFuncionario, $idPaciente);
            $stmtHistorico->execute();

            if($stmtHistorico->affected_rows != 1)
            {
                $_SESSION['retorno_paciente'] = "Erro no registro da exclusão de " . $nome . ". Tente novamente!";

                $stmtHistorico->close();
                header("Location: ../paginas/inicio_pacientes.php");
                exit;
            }

            $stmtHistorico->close();

            $stmtExcluir = $conexao->prepare("UPDATE Paciente
                                              SET excluido = TRUE
                                              WHERE id = ?");

            $stmtExcluir->bind_param("i", $idPaciente);
            $stmtExcluir->execute();

            if($stmtExcluir->affected_rows != 1)
            {
                $_SESSION['retorno_paciente'] = "Erro na exclusão de " . $nome . ". Tente novamente!";
            }
            else
            {
                $_SESSION['retorno_paciente'] = $nome . " excluído com sucesso!";
            }

            $stmtExcluir->close();

            header("Location: ../paginas/inicio_pacientes.php");
            exit;
        break;
    }
?>