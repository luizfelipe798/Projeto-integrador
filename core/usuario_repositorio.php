<?php
    session_start();
    include_once "conexao.php";

    $acao = $_POST['acao'];

    switch($acao)
    {
        case "cadastro":
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $tipoUsuario = $_POST['tipo_user'];
            $telefone = $_POST['telefone'];
            $senhaDigitada = $_POST['senha'];
            $senhaProtegida = password_hash($senhaDigitada, PASSWORD_DEFAULT, ['cost' => 12]);
            $ativo = 1;

            $stmt = $conexao->prepare("SELECT * FROM Usuario WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if($resultado->num_rows > 0)
            {
                $_SESSION['erro_cadastro'] = "Este e-mail já está em uso. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;
                $stmt->close();

                if($tipoUsuario === "Funcionario")
                {
                    header("Location: ../paginas/cadastrofuncionario.php");
                }
                else
                {
                    header("Location: ../paginas/cadastromedico.php");
                }

                exit;
            }

            $stmt->close();
            
            $stmt = $conexao->prepare("INSERT INTO Usuario(nome, email, tipo, telefone, senha, ativo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $nome, $email, $tipoUsuario, $telefone, $senhaProtegida, $ativo);
            $stmt->execute();
            
            $linhasAfetadas = $stmt->affected_rows;
            $stmt->close();

            if($linhasAfetadas != 1)
            {
                $_SESSION['erro_cadastro'] = "Algo deu errado. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                if($tipoUsuario === "Funcionario")
                {
                    header("Location: ../paginas/cadastrofuncionario.php");
                }
                else
                {
                    header("Location: ../paginas/cadastromedico.php");
                }

                exit;
            }
            
            $id = $conexao->insert_id;

            if($tipoUsuario === "Funcionario")
            {
                $dtContratacao = $_POST['dataContratacao'];
                $turno = $_POST['turno'];

                $stmtFuncionario = $conexao->prepare("INSERT INTO Funcionario(id, dataContratacao, turno) VALUES(?, ?, ?)");
                $stmtFuncionario->bind_param("iss", $id, $dtContratacao, $turno);
                $stmtFuncionario->execute();

                $linhasAfetadas = $stmtFuncionario->affected_rows;
                $stmtFuncionario->close();

                if($linhasAfetadas != 1)
                {
                    $stmtDeleta = $conexao->prepare("DELETE FROM Usuario WHERE id = ?");
                    $stmtDeleta->bind_param("i", $id);
                    $stmtDeleta->execute();

                    $_SESSION['erro_cadastro'] = "Algo deu errado. Tente novamente!";
                    $_SESSION['dados_formulario'] = $_POST;

                    $stmtDeleta->close();

                    header("Location: ../paginas/cadastrofuncionario.php");
                    exit;
                }

                $_SESSION['nome'] = $nome;
                $_SESSION['email'] = $email;
                $_SESSION['telefone'] = $telefone;
                $_SESSION['tipo_usuario'] = $tipoUsuario;
                $_SESSION['senha'] = $senhaDigitada;
                $_SESSION['dtcontrata_funcionario'] = $dtContratacao;
                $_SESSION['turno_funcionario'] = $turno;
                $_SESSION['logado'] = "Sim";

                header("Location: ../paginas/home_usuario.php");
                exit;
            }
            else if($tipoUsuario === "Medico")
            {
                $crm = $_POST['crm'];
                $especialidade = $_POST['especialidade'];
                $plantonista = $_POST['plantonista'];

                $stmtVerificar = $conexao->prepare("SELECT crm FROM Medico
                                                    WHERE crm = ?");
                
                $stmtVerificar->bind_param("s", $crm);
                $stmtVerificar->execute();

                $resultado = $stmtVerificar->get_result();
                $stmtVerificar->close();

                if($resultado->num_rows != 0)
                {
                    $stmtDeleta = $conexao->prepare("DELETE FROM Usuario WHERE id = ?");
                    $stmtDeleta->bind_param("i", $id);
                    $stmtDeleta->execute();

                    $_SESSION['erro_cadastro'] = "Este CRM já está em uso. Tente novamente!";
                    $_SESSION['dados_formulario'] = $_POST;

                    $stmtDeleta->close();

                    header("Location: ../paginas/cadastromedico.php");
                    exit;
                }

                $stmtMedico = $conexao->prepare("INSERT INTO Medico(id, crm, especialidade, plantonista) VALUES(?, ?, ?, ?)");
                $stmtMedico->bind_param("isss", $id, $crm, $especialidade, $plantonista);
                $stmtMedico->execute();

                $linhasAfetadas = $stmtMedico->affected_rows;
                $stmtMedico->close();

                if($linhasAfetadas != 1)
                {
                    $stmtDeleta = $conexao->prepare("DELETE FROM Usuario WHERE id = ?");
                    $stmtDeleta->bind_param("i", $id);
                    $stmtDeleta->execute();

                    $_SESSION['erro_cadastro'] = "Algo deu errado. Tente novamente!";
                    $_SESSION['dados_formulario'] = $_POST;

                    $stmtDeleta->close();

                    header("Location: ../paginas/cadastromedico.php");
                    exit;
                }

                $_SESSION['nome'] = $nome;
                $_SESSION['email'] = $email;
                $_SESSION['telefone'] = $telefone;
                $_SESSION['tipo_usuario'] = $tipoUsuario;
                $_SESSION['senha'] = $senhaDigitada;
                $_SESSION['crm_medico'] = $crm;
                $_SESSION['especialidade_medico'] = $especialidade;
                $_SESSION['plantonista_medico'] = $plantonista;
                $_SESSION['logado'] = "Sim";

                header("Location: ../paginas/home_usuario.php");
                exit;
            }
        break;

        case "login":
            $email = $_POST['email'];
            $senhaDigitada = $_POST['senha'];
            $tipoUsuario = $_POST['tipo_user'];
            $ativo = 0;

            $stmt = $conexao->prepare("SELECT * FROM Usuario WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $resultado = $stmt->get_result();
            $stmt->close();

            if($resultado->num_rows != 1)
            {
                $_SESSION['erro_login'] = "E-mail incorreto. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: ../paginas/login.php");
                exit;
            }

            $usuario = $resultado->fetch_assoc();

            if(password_verify($senhaDigitada, $usuario['senha']))
            {
                if($tipoUsuario != $usuario['tipo'])
                {
                    $_SESSION['erro_login'] = "Tipo de usuário incorreto. Tente novamente!";
                    $_SESSION['dados_formulario'] = $_POST;

                    header("Location: ../paginas/login.php");
                    exit;
                }

                if($usuario['ativo'] == 1)
                {
                    header("Location: ../paginas/home_usuario.php");
                    exit;
                }
            
                $id = $usuario['id'];

                if($tipoUsuario === "Funcionario")
                {
                    $stmtFuncionario = $conexao->prepare("SELECT * FROM Funcionario WHERE id = ?");
                    $stmtFuncionario->bind_param("i", $id);
                    $stmtFuncionario->execute();

                    $resultadoFuncionario = $stmtFuncionario->get_result();

                    if($resultadoFuncionario->num_rows != 1)
                    {
                        $_SESSION['erro_login'] = "Tipo de usuário incorreto. Tente novamente!";
                        $_SESSION['dados_formulario'] = $_POST;

                        $stmtFuncionario->close();
                        header("Location: ../paginas/login.php");
                        exit;
                    }
                        
                    $funcionario = $resultadoFuncionario->fetch_assoc();

                    $_SESSION['nome'] = $usuario['nome'];
                    $_SESSION['email'] = $usuario['email'];
                    $_SESSION['telefone'] = $usuario['telefone'];
                    $_SESSION['tipo_usuario'] = $tipoUsuario;
                    $_SESSION['dtcontrata_funcionario'] = $funcionario['dataContratacao'];
                    $_SESSION['turno_funcionario'] = $funcionario['turno'];
                    $_SESSION['logado'] = true;

                    $stmtFuncionario->close();
                }
                else if($tipoUsuario === "Medico")
                {
                    $stmtMedico = $conexao->prepare("SELECT * FROM Medico WHERE id = ?");
                    $stmtMedico->bind_param("i", $id);
                    $stmtMedico->execute();

                    $resultadoMedico = $stmtMedico->get_result();
                    $stmtMedico->close();

                    if($resultadoMedico->num_rows != 1)
                    {
                        $_SESSION['erro_login'] = "Tipo de usuário incorreto. Tente novamente!";
                        $_SESSION['dados_formulario'] = $_POST;

                        header("Location: ../paginas/login.php");
                        exit; 
                    }

                    $medico = $resultadoMedico->fetch_assoc();

                    $_SESSION['nome'] = $usuario['nome'];
                    $_SESSION['email'] = $usuario['email'];
                    $_SESSION['telefone'] = $usuario['telefone'];
                    $_SESSION['tipo_usuario'] = $tipoUsuario;
                    $_SESSION['crm_medico'] = $medico['crm'];
                    $_SESSION['especialidade_medico'] = $medico['especialidade'];
                    $_SESSION['plantonista_medico'] = $medico['plantonista'];
                    $_SESSION['logado'] = true;
                }

                $ativo = 1;

                $stmt = $conexao->prepare("UPDATE Usuario SET ativo = ? WHERE id = ?");
                $stmt->bind_param("ii", $ativo, $id);
                $stmt->execute();
                
                $linhasAfetadas = $stmt->affected_rows;
                $stmt->close();

                if($linhasAfetadas != 1)
                {
                    $_SESSION['erro_login'] = "Erro ao ativar login. Tente novamente!";
                    $_SESSION['dados_formulario'] = $_POST;

                    $stmt->close();
                    header("Location: ../paginas/login.php");
                    exit;   
                }

                header("Location: ../paginas/home_usuario.php");
                exit;
            }
            else
            {
                $_SESSION['erro_login'] = "Senha incorreta. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: ../paginas/login.php");
                exit;
            }
        break;

        case "logout":
            $email = $_POST['email'];
            $tipoUsuario = $_POST['tipo_user'];
            $ativo = 0;

            $stmt = $conexao->prepare("UPDATE Usuario SET ativo = ? WHERE email = ?");
            $stmt->bind_param("is", $ativo, $email);
            $stmt->execute();

            $linhaAfetada = $stmt->affected_rows;
            $stmt->close();

            if($linhaAfetada != 1)
            {
                $_SESSION['erro_logout'] = "Erro ao realizar logout. Tente novamente!";
                
                header("Location: ../paginas/home_usuario.php");
                exit;
            }

            session_unset();
            session_destroy();
            header("Location: ../index.php");
            exit;
        break;

        case "editar":
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $emailAnterior = $_POST['email_anterior'];
            $tipoUsuario = $_POST['tipo_user'];
            $telefone = $_POST['telefone'];
            $senhaDigitada = $_POST['senha'];
            $senhaProtegida = password_hash($senhaDigitada, PASSWORD_DEFAULT, ['cost' => 12]);
            $ativo = 1;

            $stmt = $conexao->prepare("SELECT id FROM Usuario WHERE email = ?");
            $stmt->bind_param("s", $emailAnterior);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if($resultado->num_rows != 1)
            {
                $_SESSION['erro_editar'] = "Erro ao atualizar dados. Tente novamente!";

                $stmt->close();
                header("Location: editar_usuario.php");
                exit;
            }

            $usuario = $resultado->fetch_assoc();
            $id = $usuario['id'];

            $stmt = $conexao->prepare("UPDATE Usuario 
                                       SET nome = ?, email = ?, tipo = ?, telefone = ?, senha = ? 
                                       WHERE id = ?");

            $stmt->bind_param("sssssi", $nome, $email, $tipoUsuario, $telefone, $senha, $id);
            $stmt->execute();

            if($stmt->affected_rows != 1)
            {
                $_SESSION['erro_editar'] = "Erro ao atualizar dados. Tente novamente!";

                $stmt->close();
                header("Location: editar_usuario.php");
                exit;
            }

            $resultado = $stmt->get_result();
            $usuario = $resultado->fetch_assoc();

            $stmt->close();

            if($tipoUsuario === "Funcionario")
            {
                $dtContratacao = $_POST['dtContratacao'];
                $turno = $_POST['turno'];

                $stmtFuncionario = $conexao->prepare("UPDATE Funcionario
                                                      SET dataContratacao = ?, turno = ?
                                                      WHERE id = ?");
                
                $stmtFuncionario->bind_param("ssi", $dtContratacao, $turno, $id);
                $stmtFuncionario->execute();

                if($stmtFuncionario->affected_rows != 1)
                {
                    $_SESSION['erro_editar'] = "Erro ao atualizar dados. Tente novamente!";

                    $stmt->close();
                    header("Location: editar_usuario.php");
                    exit;
                }

                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['telefone'] = $usuario['telefone'];
                $_SESSION['dtcontrata_funcionario'] = $funcionario['dataContratacao'];
                $_SESSION['turno_funcionario'] = $funcionario['turno'];

                $_SESSION['edicao_bem_sucedida'] = "Seus dados foram alterados com sucesso!";

                $stmtFuncionario->close();
                header("Location: ../paginas/home_usuario.php");
                exit;

            }
            else if($tipoUsuario === "Medico")
            {
                $crm = $_POST['crm'];
                $especialidade = $_POST['especialidade'];
                $plantonista = $_POST['plantonista'];

                $stmtMedico = $conexao->prepare("UPDATE Medico
                                                 SET crm = ?, especialidade = ?, plantonista = ?
                                                 WHERE id = ?");

                $stmtMedico->bind_param("sssi", $crm, $especialidade, $plantonista, $id);
                $stmtMedico->execute();

                if($stmtMedico->affected_rows != 1)
                {
                    $_SESSION['erro_editar'] = "Erro ao atualizar dados. Tente novamente!";

                    $stmt->close();
                    header("Location: editar_usuario.php");
                    exit;
                }

                $resultado = $stmtMedico->get_result();
                $medico = $resultado->fetch_assoc();

                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['telefone'] = $usuario['telefone'];
                $_SESSION['crm_medico'] = $medico['crm'];
                $_SESSION['especialidade_medico'] = $medico['especialidade'];
                $_SESSION['plantonista_medico'] = $medico['plantonista'];

                $_SESSION['edicao_bem_sucedida'] = "Seus dados foram alterados com sucesso!";

                $stmtMedico->close();
                header("Location: ../paginas/home_usuario.php");
                exit;
            }
        break;

        case "Reativação":

            $idPaciente = $_POST['id'];

            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];
            $stmtVerifica = $conexao->prepare("SELECT nome FROM Paciente
                                               WHERE 
                                                (
                                                    email = ? AND email != ?
                                                )
                                                OR
                                                (
                                                    cpf = ? AND cpf != ?
                                                )
                                                AND excluido = TRUE");
            $stmtVerifica->bind_param("ssss", $email, $emailAntigo, $cpf, $cpfAntigo);
            $stmtVerifica->execute();

            $resultado = $stmtVerifica->get_result();
            $stmtVerifica->close();

            if($resultado->num_rows > 0)
            {
                $_SESSION['retorno_paciente'] = "Este paciente nunca foi cadastrado no nosso sistema. Tente novamente!";

                header("Location: ../paginas/reativar_paciente.php");
                exit;
            }
        break;
    }
?>