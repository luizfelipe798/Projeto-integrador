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

            $stmtVerifica = $conexao->prepare("SELECT id FROM Paciente WHERE email = ? OR cpf = ?");
            $stmtVerifica->bind_param("ss", $email, $cpf);
            $stmtVerifica->execute();

            $resultado = $stmtVerifica->get_result();
            $stmtVerifica->close();

            if($resultado->num_rows > 0)
            {
                $_SESSION['retorno_paciente'] = "Este e-mail ou este cpf já está em uso. Tente novamente!";
                
                header("Location: ../paginas/cadastrar_paciente.php");
                exit;
            }

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

            $stmtIdFuncionario = $conexao->prepare("SELECT id FROM Usuario
                                                        WHERE email = ? AND tipo = 'Funcionario'");

            $stmtIdFuncionario->bind_param("s", $emailFuncionario);
            $stmtIdFuncionario->execute();

            $resultado = $stmtIdFuncionario->get_result();
            $stmtIdFuncionario->close();

            if($resultado->num_rows != 1)
            {
                $_SESSION['retorno_paciente'] = $nome . " cadastrado(a) com sucesso, porém não foi possível registrar a ação no histórico.";

                header("Location: ../paginas/inicio_pacientes.php");
                exit;
            }

            $funcionarioID = $resultado->fetch_assoc();
            $idFuncionario = $funcionarioID['id'];

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

            $_SESSION['retorno_paciente'] = $nome . " cadastrado(a) com sucesso!";

            $stmtHistorico->close();

            header("Location: ../paginas/inicio_pacientes.php");
            exit;
        break;

        case "Edição":
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];
            $dataNascimento = $_POST['dataNascimento'];
            $cpf = $_POST['cpf'];
            $genero = $_POST['genero'];
            $idPaciente = $_POST['idPaciente'];
            $emailFuncionario = $_SESSION['email'];
            $emailAnterior = $_POST['emailGet'];
            $cpfAnterior = $_POST['cpfGet'];

            $stmtVerifica = $conexao->prepare("SELECT id FROM Paciente
                                               WHERE
                                                   (email = ? OR cpf = ?)
                                               AND id != ?");

            $stmtVerifica->bind_param("ssi", $email, $cpf, $idPaciente);
            $stmtVerifica->execute();

            $resultado = $stmtVerifica->get_result();
            $stmtVerifica->close();

            if($resultado->num_rows > 0)
            {
                $_SESSION['retorno_paciente'] = "Este e-mail ou este CPF já está em uso. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: ../paginas/cadastrar_paciente.php");
                exit;
            }

            $stmtObterFuncionario = $conexao->prepare("SELECT id FROM Usuario
                                                       WHERE email = ? AND tipo = 'Funcionario'");
            
            $stmtObterFuncionario->bind_param("s", $emailFuncionario);
            $stmtObterFuncionario->execute();

            $resultado = $stmtObterFuncionario->get_result();
            $stmtObterFuncionario->close();

            if($resultado->num_rows != 1)
            {
                $_SESSION['retorno_paciente'] = "Erro na identificação do funcionário na edição de " . $nome . ". Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: ../paginas/cadastrar_paciente.php");
                exit;
            }

            $funcionario = $resultado->fetch_assoc();
            $idFuncionario = $funcionario['id'];

            $stmtEditar = $conexao->prepare("UPDATE Paciente
                                             SET nome = ?, email = ?, telefone = ?,
                                                 dataNascimento = ?, cpf = ?, genero = ?
                                             WHERE id = ?");
            
            $stmtEditar->bind_param("ssssssi", $nome, $email, $telefone, $dataNascimento, $cpf, $genero, $idPaciente);
            $stmtEditar->execute();

            $linhasAfetadas = $stmtEditar->affected_rows;
            $stmtEditar->close();

            if($linhasAfetadas < 0 || $linhasAfetadas > 1)
            {
                $_SESSION['retorno_paciente'] = "Erro na edição de " . $nome . ". Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: ../paginas/cadastrar_paciente.php");
                exit;
            }

            $_SESSION['retorno_paciente'] = "Dados de " . $nome . " alterados com sucesso";

            $stmtHistorico = $conexao->prepare("INSERT INTO HistFuncPaciente(tipoAcao, idFuncionario, idPaciente)
                                                VALUES (?, ?, ?)");

            $stmtHistorico->bind_param("sii", $acao, $idFuncionario, $idPaciente);
            $stmtHistorico->execute();

            $linhasAfetadas = $stmtHistorico->affected_rows;
            $stmtHistorico->close();

            if($linhasAfetadas != 1)
            {
                $_SESSION['retorno_paciente'] .= ", porém não foi possível registrar no histórico.";
            }
            else
            {
                $_SESSION['retorno_paciente'] .= "!";
            }

            if(isset($_SESSION['dados_formulario']))
            {
                unset($_SESSION['dados_formulario']);
            }

            header("Location: ../paginas/inicio_pacientes.php");
            exit;
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

        case "Reativação":
            $email = $_POST['email'];
            $cpf = $_POST['cpf'];
            $emailFuncionario = $_SESSION['email'];

            $stmtVerificar = $conexao->prepare("SELECT id, nome FROM Paciente
                                                WHERE
                                                email = ? AND cpf = ?
                                                AND excluido = TRUE");
            
            $stmtVerificar->bind_param("ss", $email, $cpf);
            $stmtVerificar->execute();

            $resultado = $stmtVerificar->get_result();
            $stmtVerificar->close();

            if($resultado->num_rows <= 0)
            {
                $_SESSION['retorno_paciente'] = "E-mail ou CPF incorretos. Tente novamente!";

                header("Location: ../paginas/reativar_paciente.php");
                exit;
            }

            $paciente = $resultado->fetch_assoc();
            $idPaciente = $paciente['id'];
            $nomePaciente = $paciente['nome'];

            $stmtReativar = $conexao->prepare("UPDATE Paciente
                                               SET excluido = FALSE
                                               WHERE id = ?");

            $stmtReativar->bind_param("i", $idPaciente);
            $stmtReativar->execute();

            $linhasAfetadas = $stmtReativar->affected_rows;
            $stmtReativar->close();

            if($linhasAfetadas != 1)
            {
                $_SESSION['retorno_paciente'] = "Erro na reativação de paciente excluído. Tente novamente!";

                header("Location: ../paginas/reativar_paciente.php");
                exit;
            }
            
            $_SESSION['retorno_paciente'] = $nomePaciente . " reativado com sucesso";

            $stmtFuncionario = $conexao->prepare("SELECT id FROM Usuario
                                                  WHERE email = ? AND tipo = 'Funcionario'");
            
            $stmtFuncionario->bind_param("s", $emailFuncionario);
            $stmtFuncionario->execute();

            $resultado = $stmtFuncionario->get_result();
            
            if($resultado->num_rows != 1)
            {
                $_SESSION['retorno_paciente'] .= ", porém não foi possível registrar no histórico.";

                header("Location: ../paginas/inicio_pacientes.php");
                exit;
            }

            $funcionario = $resultado->fetch_assoc();
            $idFuncionario = $funcionario['id'];
            
            $stmtHistorico = $conexao->prepare("INSERT INTO HistFuncPaciente(tipoAcao, idFuncionario, idPaciente)
                                                VALUES(?, ?, ?)");

            $stmtHistorico->bind_param("sii", $acao, $idFuncionario, $idPaciente);
            $stmtHistorico->execute();

            $linhasAfetadas = $stmtHistorico->affected_rows;
            $stmtHistorico->close();

            if($linhasAfetadas != 1)
            {
                $_SESSION['retorno_paciente'] .= ", porém não foi possível registrar no histórico.";

                header("Location: ../paginas/inicio_pacientes.php");
                exit;
            }

            $_SESSION['retorno_paciente'] .= "!";

            header("Location: ../paginas/inicio_pacientes.php");
            exit;
        break;
    }
?>