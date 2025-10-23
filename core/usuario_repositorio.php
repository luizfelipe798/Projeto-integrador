<?php
    session_start();

    require_once 'conexao.php';
    require_once 'instrucoes_sql.php';
    require_once 'consultas_sql.php';

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
                'tipo' => $tipo,
            ];

            $criterio_usuario = [
                ['email', '=', $email],
            ];

            $linhas_usuario = buscar('Usuario', ['email'], $criterio_usuario);

            if($tipo == "Medico")
            {
                $criterio_medico = [['crm', '=', $crm]];
            
                $linhas_medico = buscar('Medico', ['crm'], $criterio_medico);
            }

            if(!empty($linhas_usuario) || !empty($linhas_medico))
            {
                $_SESSION['erro_cadastro'] = "Erro ao cadastrar. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: " . $redirecionamento);
                exit;
            }

            $id_usuario = insere('Usuario', $dados_comuns);

            if($id_usuario == 0)
            {
                $_SESSION['erro_cadastro'] = "Erro ao cadastrar. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: " . $redirecionamento);
                exit;
            }

            if($tipo == "Funcionario")
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

            $id_especifico = insere("{$tipo}", $dados_especificos);

            if($id_especifico == 0)
            {
                $_SESSION['erro_cadastro'] = "Erro ao cadastrar. Tente novamente!";
                $_SESSION['dados_formulario'] = $_POST;

                header("Location: " . $redirecionamento);
                exit;
            }
            else
            {
                $_SESSION['nome'] = $nome;
                $_SESSION['email'] = $email;
                $_SESSION['tipo'] = $tipo;
                $_SESSION['telefone'] = $telefone;
                $_SESSION['senha'] = $senha;
                $_SESSION['logado'] = true;

                if($tipo == "Funcionario")
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