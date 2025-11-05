<?php
    session_start();

    require_once 'conexao.php';
    require_once 'sql.php';
    require_once 'mysql.php';

    foreach($_POST as $indice => $dado)
    {
        $$indice = htmlspecialchars($dado);
    }

    foreach($_GET as $indice => $dado)
    {
        $$indice = htmlspecialchars($dado);
    }

    switch($acao)
    {
        case 'Cadastro':
            $dados_comuns = [
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone,
                'senha' => password_hash($senha, PASSWORD_DEFAULT, ['cost' => 12]),
                'tipoUsuario' => $tipo_user,
            ];

            $criterio_usuario = [['email', '=', $email]];

            $linhas_usuario = buscar('Usuario', ['email'], $criterio_usuario);

            if($tipo_user == "Medico")
            {
                $redirecionamento = "../paginas/cadastromedico.php";

                $criterio_medico = [['crm', '=', $crm]];
                $linhas_medico = buscar('Medico', ['crm'], $criterio_medico);

                if(!empty($linhas_medico))
                {
                    $_SESSION['mensagem_cadastro'] = "Este CRM já está cadastrado. Tente novamente!";
                    $_SESSION['dados_formulario'] = $_POST;

                    header("Location: " . $redirecionamento);
                    exit;
                }
            }
            else
            {
                $redirecionamento = "../paginas/cadastrofuncionario.php";
            }

            if(!empty($linhas_usuario))
            {
                $_SESSION['mensagem_cadastro'] = "Este e-mail já está cadastrado. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: " . $redirecionamento);
                exit;
            }

            $insercao_usuario = insere('Usuario', $dados_comuns);

            if($insercao_usuario == true)
            {
                $criterio_usuario = [['email', '=', $email]];
                $linhas_usuario = buscar('Usuario', ['id'], $criterio_usuario);
                $id_usuario = $linhas_usuario[0]['id'];
            }

            if($tipo_user == "Funcionario")
            {
                $dados_especificos = [
                    'id' => $id_usuario,
                    'dataContratacao' => $dataContratacao,
                    'turno' => $turno,
                ];
            }
            else
            {
                $dados_especificos = [
                    'id' => $id_usuario,
                    'crm' => $crm,
                    'especialidade' => $especialidade,
                    'plantonista' => $plantonista,
                ];
            }

            $insercao_especifica = insere("{$tipo_user}", $dados_especificos);

            if($insercao_especifica == true)
            {
                $_SESSION['mensagem_cadastro'] = "Cadastro realizado com sucesso! Faça login para continuar.";
                header("Location: " . $redirecionamento);
            }
        break;

        case 'Login':
            $criterio_login = [
                ['email', '=', $email],
                ['ativo', '=', 1],
            ];

            $linhas_login = buscar('Usuario', ['id', 'nome', 'email', 'tipoUsuario', 'telefone', 'senha'], $criterio_login);

            if(empty($linhas_login))
            {
                $_SESSION['mensagem_login'] = "E-mail inválido ou usuário inativo. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: ../paginas/login.php");
                exit;
            }
            else if(password_verify($senha, $linhas_login[0]['senha']))
            {
                $criterio_tipo = [['id', '=', $linhas_login[0]['id']]];

                if($linhas_login[0]['tipoUsuario'] == "Medico")
                {
                    $tabela_tipo = "Medico";
                    $campos_tipo = ['crm', 'especialidade', 'plantonista'];
                }
                else
                {
                    $tabela_tipo = "Funcionario";
                    $campos_tipo = ['dataContratacao', 'turno'];
                }

                if($linhas_login[0]['tipoUsuario'] != $tipo_user)
                {
                    $_SESSION['mensagem_login'] = "Tipo de usuário inválido. Tente novamente!";
                    $_SESSION['dados_formulario'] = $_POST;

                    header("Location: ../paginas/login.php");
                    exit;
                }
                
                $linhas_tipo = buscar($tabela_tipo, $campos_tipo, $criterio_tipo);

                $_SESSION['usuario'] = [
                    'id' => $linhas_login[0]['id'],
                    'nome' => $linhas_login[0]['nome'],
                    'email' => $linhas_login[0]['email'],
                    'tipoUsuario' => $linhas_login[0]['tipo'],
                    'telefone' => $linhas_login[0]['telefone'],
                    'senha' => $linhas_login[0]['senha'],
                    'logado' => true,
                ];

                $_SESSION['usuario'] = array_merge($_SESSION['usuario'], $linhas_tipo[0]);

                header("Location: ../paginas/home_usuario.php");
                exit;
            }
            else
            {
                $_SESSION['mensagem_login'] = "Senha inválida. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;
                
                header("Location: ../paginas/login.php");
                exit;
            }
        break;

        case 'Logout':
            session_unset();
            session_destroy();
            header("Location: ../paginas/login.php");
            exit;
        break;
    }
?>