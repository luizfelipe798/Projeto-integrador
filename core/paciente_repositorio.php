<?php
    require_once "tempo_sessao.php";
    session_start();

    require_once "conexao.php";
    require_once "sql.php";
    require_once "mysql.php";

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
        case "Cadastro":
            $dados_paciente = [
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone,
                'dataNascimento' => $dataNascimento,
                'cpf' => $cpf,
                'genero' => $genero
            ];

            $condicoes_buscar_paciente = [
                ['email', '=', $email],
                ['AND', 'cpf', '=', $cpf]
            ];

            $verificar_paciente = buscar('Paciente', ['nome'], $condicoes_buscar_paciente);

            if(!empty($verificar_paciente))
            {
                $_SESSION['mensagem_gerenciamento'] = "Este paciente já está cadastrado. Tente novamente!";
                $_SESSION['dados_formulario_cadastro'] = $_POST;

                header("Location: ../paginas/cadastrar_paciente.php");
                exit;
            }

            insere("Paciente", $dados_paciente);

            $id_paciente = buscar('Paciente', ['id'], $condicoes_buscar_paciente);
            
            $id_funcionario = $_SESSION['usuario']['id'];

            $dados_historico = [
                'tipoAcao' => $acao,
                'idFuncionario' => $id_funcionario,
                'idPaciente' => $id_paciente[0]['id']
            ];

            insere('HistFuncPaciente', $dados_historico);
            
            $_SESSION['mensagem_gerenciamento'] = "$nome cadastrado(a) com sucesso!";

            header("Location: ../paginas/inicio_pacientes.php");
            exit;
        break;

        case "Edição":
            $criterio1_buscar_paciente = [
                ['id', '!=', $idPaciente],
                ['AND', 'email', '=', $email],
            ];

            $criterio2_buscar_paciente = [
                ['id', '!=', $idPaciente],
                ['AND', 'cpf', '=', $cpf],
            ];

            $verificar1_paciente = buscar('Paciente', ['nome'], $criterio1_buscar_paciente);
            $verificar2_paciente = buscar('Paciente', ['nome'], $criterio2_buscar_paciente);

            if(!empty($verificar1_paciente) || !empty($verificar2_paciente))
            {
                $_SESSION['mensagem_gerenciamento'] = "Este paciente já está cadastrado. Tente novamente!";
                $_SESSION['dados_formulario_edicao'] = $_POST;

                header("Location: ../paginas/cadastrar_paciente.php?id=$idPaciente");
                exit;
            }

            $dados_paciente = [
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone,
                'dataNascimento' => $dataNascimento,
                'cpf' => $cpf,
                'genero' => $genero
            ];

            $criterio_atualizar_paciente = [
                ['id', '=', $idPaciente]
            ];

            atualiza('Paciente', $dados_paciente, $criterio_atualizar_paciente);
            
            $criterio_funcionario = [
                ['email', '=', $_SESSION['usuario']['email']],
                ['AND', 'tipoUsuario', '=', $_SESSION['usuario']['tipoUsuario']]
            ];

            $id_funcionario = buscar('Usuario', ['id'], $criterio_funcionario);

            $dados_historico = [
                'tipoAcao' => $acao,
                'idFuncionario' => $id_funcionario[0]['id'],
                'idPaciente' => $idPaciente
            ];

            insere('HistFuncPaciente', $dados_historico);

            $_SESSION['mensagem_gerenciamento'] = "$nome editado(a) com sucesso!";

            header("Location: ../paginas/inicio_pacientes.php");
            exit;
        break;

        case "Exclusão":
            $dados_paciente = [
                'excluido' => 1
            ];

            $criterio = [
                ['id', '=', $id]
            ];

            atualiza('Paciente', $dados_paciente, $criterio);

            $criterio_funcionario = [
                ['email', '=', $_SESSION['usuario']['email']],
                ['AND', 'tipoUsuario', '=', $_SESSION['usuario']['tipoUsuario']]
            ];

            $id_funcionario = buscar('Usuario', ['id'], $criterio_funcionario);

            $dados_historico = [
                'tipoAcao' => $acao,
                'idFuncionario' => $id_funcionario[0]['id'],
                'idPaciente' => $id
            ];

            insere('HistFuncPaciente', $dados_historico);

            $criterio_paciente = [
                ['id', '=', $id],
                ['AND', 'excluido', '=', 1]
            ];

            $nome_paciente = buscar('Paciente', ['nome'], $criterio_paciente);

            $_SESSION['mensagem_gerenciamento'] = "{$nome_paciente[0]['nome']} excluído(a) com sucesso!";

            header("Location: ../paginas/inicio_pacientes.php");
            exit;
        break;
    }
?>