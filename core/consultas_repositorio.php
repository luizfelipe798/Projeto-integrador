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

            $campos_consulta = [
                'horario' => "$data_horario $hora_horario",
                'stattus' => "Agendada",
                'valor' => $valor,
                'especialidade' => $especialidade,
                'idMedico' => $idMedico,
                'idPaciente' => $idPaciente
            ];

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

            $id_nova_consulta = insere('Consulta', $campos_consulta);

            $campos_historico = [
                'tipoAcao' => 'Agendamento',
                'idFuncionario' => $_SESSION['usuario']['id'],
                'idConsulta' => $id_nova_consulta,
            ];

            insere('HistFuncConsulta', $campos_historico);

            $_SESSION['mensagem_gerenciamento'] = "Consulta agendada com sucesso!";

            header("Location: ../paginas/inicio_consultas.php");
            exit;
        break;
    }
?>