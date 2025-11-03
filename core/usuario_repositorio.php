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
                'ativo' => 0,
            ];

            $criterio_usuario = [
                ['email', '=', $email],
            ];

            $linhas_usuario = buscar('Usuario', ['email'], $criterio_usuario);

            if($tipo_user == "Medico")
            {
                $criterio_medico = [['crm', '=', $crm]];
            
                $linhas_medico = buscar('Medico', ['crm'], $criterio_medico);

                if(!empty($linhas_medico))
                {
                    $_SESSION['erro_cadastro'] = "Erro ao cadastrar. CRM já cadastrado!";
                    $_SESSION['dados_formulario'] = $_POST;

                    header("Location: " . $redirecionamento);
                    exit;
                }
            }

            if(!empty($linhas_usuario))
            {
                $_SESSION['erro_cadastro'] = "Erro ao cadastrar, e-mail já cadastrado. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: " . $redirecionamento);
                exit;
            }
            print_r($dados_comuns);
            $id_usuario = insere('Usuario', $dados_comuns);

            if($id_usuario == 0)
            {
                $_SESSION['erro_cadastro'] = "Erro ao cadastrar usuario. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: " . $redirecionamento);
                exit;
            }

            if($tipo_user == "Funcionario")
            {
                $dados_especificos = [
                    'id' => $id_usuario,
                    'dataContratacao' => $dataContratacao,
                    'turno' => $turno,
                ];

                $redirecionamento = "../paginas/cadastrofuncionario.php";
            }
            else
            {
                $dados_especificos = [
                    'id' => $id_usuario,
                    'crm' => $crm,
                    'especialidade' => $especialidade,
                    'plantonista' => $plantonista,
                ];

                $redirecionamento = "../paginas/cadastromedico.php";
            }
          
            print_r($dados_especificos);
            $id_especifico = insere("{$tipo_user}", $dados_especificos);

            if($id_especifico == 0)
            {
                $_SESSION['erro_cadastro'] = "Erro ao cadastrar funcionário. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: " . $redirecionamento);
                exit;
            }
            else
            {
                $_SESSION['nome'] = $nome;
                $_SESSION['email'] = $email;
                $_SESSION['tipoUsuario'] = $tipo_user;
                $_SESSION['telefone'] = $telefone;
                $_SESSION['senha'] = $senha;
                $_SESSION['logado'] = true;

                if($tipo_user  == "Funcionario")
                {
                    $_SESSION['dataContratacao'] = $dataContratacao;
                    $_SESSION['turno'] = $turno;
                }
                else
                {
                    $_SESSION['crm'] = $crm;
                    $_SESSION['especialidade'] = $especialidade;
                    $_SESSION['plantonista'] = $plantonista;
                }
            }

            header("Location: ../index.php");
            exit;
        break;
    }
?>