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
                ['AND', 'ativo', '=', 1]
            ];

            $linhas_login = buscar('Usuario', ['id', 'nome', 'email', 'tipoUsuario', 'telefone', 'senha', 'adm'], $criterio_login);

            if(empty($linhas_login))
            {
                $_SESSION['mensagem_login'] = "E-mail inválido ou usuário inativo. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: ../paginas/login.php");
                exit;
            }
            else if(password_verify($senha, $linhas_login[0]['senha']))
            {
                $criterio_tipo = [
                    ['id', '=', $linhas_login[0]['id']]
                ];

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
                    'tipoUsuario' => $linhas_login[0]['tipoUsuario'],
                    'telefone' => $linhas_login[0]['telefone'],
                    'senha' => $senha,
                    'logado' => true,
                    'adm' => $linhas_login[0]['adm'],
                ];

                if($linhas_login[0]['tipoUsuario'] == "Medico")
                {
                    $_SESSION['usuario']['crm'] = $linhas_tipo[0]['crm'];
                    $_SESSION['usuario']['especialidade'] = $linhas_tipo[0]['especialidade'];
                    $_SESSION['usuario']['plantonista'] = $linhas_tipo[0]['plantonista'];
                }
                else
                {
                    $_SESSION['usuario']['dataContratacao'] = $linhas_tipo[0]['dataContratacao'];
                    $_SESSION['usuario']['turno'] = $linhas_tipo[0]['turno'];
                }

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

        case 'Edição':
            $criterio_buscar_semelhante = [
                ['id', '!=', $id],
                ['AND', 'email', '=', $email]
            ];

            $criterio_editar = [
                ['id', '=', $id]
            ];

            if($tipoUsuario == 'Medico')
            {
                $criterio_buscar_medico_semelhante = [
                    ['id', '!=', $id],
                    ['AND', 'crm', '=', $crm]
                ]; 

                $usuarioSemelhante = buscar('Medico', ['id'], $criterio_buscar_medico_semelhante);

                if(!empty($usuarioSemelhante))
                {
                    $_SESSION['mensagem_perfil'] = "Estes dados já pertencem a outro usuário. Tente novamente!";

                    header("Location: ../paginas/perfil_usuario.php");
                    exit;
                }
            }

            $usuarioSemelhante = buscar('Usuario', ['nome'], $criterio_buscar_semelhante);

            if(!empty($usuarioSemelhante))
            {
                $_SESSION['mensagem_perfil'] = "Este e-mail já pertence a " . $usuarioSemelhante[0]['nome'] . ". Tente novamente!";

                header("Location: ../paginas/perfil_usuario.php");
                exit;
            }

            $campos_usuario = [
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone,
            ];

            if(!empty($senha) && $senha != $_SESSION['usuario']['senha'])
            {
                $campos_usuario['senha'] = password_hash($senha, PASSWORD_DEFAULT, ['cost' => 12]);
                $_SESSION['usuario']['senha'] = $senha;
            }

            if($tipoUsuario == 'Funcionario')
            {
                $campos_especifico = [
                    'dataContratacao' => $dataContratacao,
                    'turno' => $turno
                ];
            }
            else
            {
                $campos_especifico = [
                    'crm' => $crm,
                    'especialidade' => $especialidade,
                    'plantonista' => $plantonista,
                ];
            }

            $atualizaUsuario = atualiza('Usuario', $campos_usuario, $criterio_editar);
            $atualizaEspecifico = atualiza("{$tipoUsuario}", $campos_especifico, $criterio_editar);

            $_SESSION['usuario']['nome'] = $nome;
            $_SESSION['usuario']['email'] = $email;
            $_SESSION['usuario']['telefone'] = $telefone;
            $_SESSION['usuario']['senha'] = $senha;

            if($tipoUsuario == 'Funcionario')
            {
                $_SESSION['usuario']['dataContratacao'] = $dataContratacao;
                $_SESSION['usuario']['turno'] = $turno;
            }
            else
            {
                $_SESSION['usuario']['crm'] = $crm;
                $_SESSION['usuario']['especialidade'] = $especialidade;
                $_SESSION['usuario']['plantonista'] = $plantonista;
            }

            $_SESSION['mensagem_perfil'] = "Dados editados com sucesso!";

            header("Location: ../paginas/perfil_usuario.php?");
            exit;
        break;
    }
?>