<?php
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
        case "Agendamento":
            $data_hora_horario = "$data_horario $hora_horario";
            $horario = new DateTime($data_hora_horario);
            $data_atual = new DateTime();

            if($horario <= $data_atual)
            {
                $_SESSION['mensagem_gerenciamento'] = "Não é possível agendar consultas no passado.";
                $_SESSION['dados_formulario_cadastro'] = $_POST;

                header("Location: ../paginas/agendar_editar_consultas.php");
                exit;
            }

            $criterio1_medico = [
                ['id', '=', $idMedico],
                ['AND', 'tipoUsuario', '=', 'Medico']
            ];

            $criterio2_medico = [
                ['horario', '=', "$data_horario $hora_horario"],
                ['AND', 'idMedico', '=', $idMedico],
            ];

            $criterio1_paciente = [
                ['id', '=', $idPaciente],
                ['AND', 'excluido', '=', 0]
            ];

            $criterio2_paciente = [
                ['horario', '=', "$data_horario $hora_horario"],
                ['AND', 'idPaciente', '=', $idPaciente],
            ];

            $medico_existe = buscar('Usuario', ['nome'], $criterio1_medico);

            if(empty($medico_existe))
            {
                $_SESSION['mensagem_gerenciamento'] = "O médico selecionado não existe.";
                $_SESSION['dados_formulario_cadastro'] = $_POST;

                header("Location: ../paginas/agendar_editar_consultas.php");
                exit;
            }

            $medico_ocupado = buscar('Consulta', ['id'], $criterio2_medico);

            if(!empty($medico_ocupado))
            {
                $_SESSION['mensagem_gerenciamento'] = "O médico selecionado já tem uma consulta agendada neste horário.";
                $_SESSION['dados_formulario_cadastro'] = $_POST;

                header("Location: ../paginas/agendar_editar_consultas.php");
                exit;
            }

            $paciente_existe = buscar('Paciente', ['nome'], $criterio1_paciente);

            if(empty($paciente_existe))
            {
                $_SESSION['mensagem_gerenciamento'] = "O paciente selecionado não existe.";
                $_SESSION['dados_formulario_cadastro'] = $_POST;

                header("Location: ../paginas/agendar_editar_consultas.php");
                exit;
            }

            $paciente_ocupado = buscar('Consulta', ['id'], $criterio2_paciente);

            if(!empty($paciente_ocupado))
            {
                $_SESSION['mensagem_gerenciamento'] = "O paciente selecionado já tem uma consulta agendada neste horário.";
                $_SESSION['dados_formulario_cadastro'] = $_POST;

                header("Location: ../paginas/agendar_editar_consultas.php");
                exit;
            }

            $campos_consulta = [
                'horario' => "$data_horario $hora_horario",
                'valor' => $valor,
                'especialidade' => $especialidade,
                'idMedico' => $idMedico,
                'idPaciente' => $idPaciente
            ];

            $id = insere('Consulta', $campos_consulta);

            $campos_historico = [
                'tipoAcao' => $acao,
                'idFuncionario' => $_SESSION['usuario']['id'],
                'idConsulta' => $id
            ];

            insere('HistFuncConsulta', $campos_historico);

            $_SESSION['mensagem_gerenciamento'] = "Consulta agendada com sucesso!";

            header("Location: ../paginas/inicio_consultas.php");
            exit;
        break;

        case "Edição":
            $data_hora_horario = "$data_horario $hora_horario";
            $horario = new DateTime($data_hora_horario);
            $data_atual = new DateTime();

            if($horario <= $data_atual)
            {
                $_SESSION['mensagem_gerenciamento'] = "Não é possível agendar consultas no passado.";
                $_SESSION['dados_formulario_edicao'] = $_POST;

                header("Location: ../paginas/agendar_editar_consultas.php");
                exit;
            }

            $criterio1_medico = [
                ['id', '=', $idMedico],
                ['AND', 'tipoUsuario', '=', 'Medico'],
                ['AND', 'id', '!=', $id]
            ];

            $criterio2_medico = [
                ['horario', '=', "$data_horario $hora_horario"],
                ['AND', 'idMedico', '=', $idMedico],
                ['AND', 'id', '!=', $id]
            ];

            $criterio1_paciente = [
                ['id', '=', $idPaciente],
                ['AND', 'excluido', '=', 0],
            ];

            $criterio2_paciente = [
                ['horario', '=', "$data_horario $hora_horario"],
                ['AND', 'idPaciente', '=', $idPaciente],
                ['AND', 'id', '!=', $id]
            ];

            $medico_existe = buscar('Usuario', ['nome'], $criterio1_medico);

            if(empty($medico_existe))
            {
                $_SESSION['mensagem_gerenciamento'] = "O médico selecionado não existe.";
                $_SESSION['dados_formulario_edicao'] = $_POST;

                header("Location: ../paginas/agendar_editar_consultas.php");
                exit;
            }

            $criterio2_medico[] = ['AND', 'id', '!=', $id];

            $medico_ocupado = buscar('Consulta', ['id'], $criterio2_medico);

            if(!empty($medico_ocupado))
            {
                $_SESSION['mensagem_gerenciamento'] = "O médico selecionado já tem uma consulta agendada neste horário.";
                $_SESSION['dados_formulario_edicao'] = $_POST;

                header("Location: ../paginas/agendar_editar_consultas.php");
                exit;
            };

            $paciente_existe = buscar('Paciente', ['nome'], $criterio1_paciente);

            if(empty($paciente_existe))
            {
                $_SESSION['mensagem_gerenciamento'] = "O paciente selecionado não existe.";
                $_SESSION['dados_formulario_edicao'] = $_POST;

                header("Location: ../paginas/agendar_editar_consultas.php");
                exit;
            }

            $paciente_ocupado = buscar('Consulta', ['id'], $criterio2_paciente);

            if(!empty($paciente_ocupado))
            {
                $_SESSION['mensagem_gerenciamento'] = "O paciente selecionado já tem uma consulta agendada neste horário.";
                $_SESSION['dados_formulario_edicao'] = $_POST;

                header("Location: ../paginas/agendar_editar_consultas.php");
                exit;
            }

            $campos_consulta = [
                'horario' => "$data_horario $hora_horario",
                'valor' => $valor,
                'especialidade' => $especialidade,
                'idMedico' => $idMedico,
                'idPaciente' => $idPaciente
            ];

            atualiza('Consulta', $campos_consulta, [['id', '=', $id]]);

            $campos_historico = [
                'tipoAcao' => $acao,
                'idFuncionario' => $_SESSION['usuario']['id'],
                'idConsulta' => $id
            ];

            insere('HistFuncConsulta', $campos_historico);

            $_SESSION['mensagem_gerenciamento'] = "Consulta editada com sucesso!";

            header("Location: ../paginas/inicio_consultas.php");
            exit;
        break;

        case "Conclusão":
            $campos_conclusao = [
                'stattus' => 'Concluída'
            ];

            atualiza('Consulta', $campos_conclusao, [['id', '=', $id]]);

            $campos_historico = [
                'tipoAcao' => $acao,
                'idFuncionario' => $_SESSION['usuario']['id'],
                'idConsulta' => $id
            ];

            $_SESSION['mensagem_gerenciamento'] = "Consulta concluída com sucesso!";

            header("Location: ../paginas/acoes_consultas.php");
            exit;
        break;

        case "Exclusão":
            atualiza('Consulta', ['excluida' => 1], [['id', '=', $id]]);

            $campos_historico = [
                'tipoAcao' => $acao,
                'idFuncionario' => $_SESSION['usuario']['id'],
                'idConsulta' => $id
            ];

            insere('HistFuncConsulta', $campos_historico);

            $_SESSION['mensagem_gerenciamento'] = "Consulta excluída com sucesso!";

            header("Location: ../paginas/inicio_consultas.php");
            exit;
        break;
    }
?>