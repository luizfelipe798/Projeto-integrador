<?php
    session_start();

    include_once "conexao.php";

    $tabela = $_POST['tabela'];
    $busca = $_POST['busca'];
    $termo = "%" . $busca . "%";

    $termoData = $termo;

    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $busca)) 
    {
        $dataObjeto = DateTime::createFromFormat('d/m/Y', $busca);

        if ($dataObjeto !== false)
        {
            $dataConvertida = $dataObjeto->format('Y-m-d');
            $termoData = $dataConvertida . "%";
        }
    }

    switch($tabela)
    {
        case "Paciente":
            $stmtBusca = $conexao->prepare("SELECT * FROM Paciente
                                    WHERE   
                                    (           id                 LIKE ?
                                        OR      nome               LIKE ?
                                        OR      email              LIKE ?
                                        OR      telefone           LIKE ?
                                        OR      dataNascimento     LIKE ?
                                        OR      cpf                LIKE ?
                                        OR      genero             LIKE ?
                                        OR      DATE_FORMAT(dataNascimento, '%d/%m/%Y') LIKE ?
                                    )
                                    AND excluido = FALSE
                                    ORDER BY id ASC");
    
            $stmtBusca->bind_param("ssssssss", $termo, $termo, $termo, $termo, $termoData, $termo, $termo, $termo);
            $stmtBusca->execute();
        break;

        case "HistFuncPaciente":
            $idsPacientes = [0];
            $idsFuncionarios = [0];

            $stmtBuscaFuncionario = $conexao->prepare("SELECT id FROM Usuario 
                                                       WHERE nome LIKE ? AND tipo = 'Funcionario'");

            $stmtBuscaFuncionario->bind_param("s", $termo);
            $stmtBuscaFuncionario->execute();
            
            $resultados = $stmtBuscaFuncionario->get_result();

            while($resultado = $resultados->fetch_assoc())
            {
                $idsFuncionarios[] = $resultado['id'];
            }

            $stmtBuscaFuncionario->close();

            $stmtBuscaPaciente = $conexao->prepare("SELECT id FROM Paciente
                                                    WHERE nome LIKE ?");

            $stmtBuscaPaciente->bind_param("s", $termo);
            $stmtBuscaPaciente->execute();
            
            $resultados = $stmtBuscaPaciente->get_result();

            while($resultado = $resultados->fetch_assoc())
            {
                $idsPacientes[] = $resultado['id'];
            }

            $idsFuncionariosEncontrados = implode(',', $idsFuncionarios);
            $idsPacientesEncontrados = implode(',', $idsPacientes);

            $stmtBuscaPaciente->close();

            $stmtBusca = $conexao->prepare("SELECT * FROM HistFuncPaciente
                                    WHERE   id                 LIKE ?
                                    OR      tipoAcao           LIKE ?
                                    OR      dtAcao             LIKE ?
                                    OR      idFuncionario      IN ($idsFuncionariosEncontrados)
                                    OR      idPaciente         IN ($idsPacientesEncontrados)
                                    OR      DATE_FORMAT(dtAcao, '%d/%m/%Y') LIKE ?
                                    ORDER BY dtAcao DESC");
    
            $stmtBusca->bind_param("ssss", $termo, $termo, $termoData, $termo);
            $stmtBusca->execute();
        break;
        
        case "Consulta":
            $stmtBusca = $conexao->prepare("SELECT * FROM Consulta
                                    WHERE   id                 LIKE ?
                                    OR      horario            LIKE ?
                                    OR      observacao         LIKE ?
                                    OR      stattus            LIKE ?
                                    OR      valor              LIKE ?
                                    OR      tipo               LIKE ?
                                    OR      genero             LIKE ?");
    
            $stmtBusca->bind_param("issssss", $id, $termo, $termo, $termo, $termo, $termo, $termo);
            $stmtBusca->execute();
        break;
    }

    $resultados = $stmtBusca->get_result();
    $resultadoBusca = [];

    if($resultados->num_rows > 0)
    {
        while($resultado = $resultados->fetch_assoc())
        {
            $resultadoBusca[] = $resultado;
        }

        $_SESSION['resultados_busca'] = $resultadoBusca;
    }
    else
    {
        $_SESSION['resultados_busca'] = [];
    }

    $_SESSION['termo_busca'] = $busca;

    $stmtBusca->close();

    switch($tabela)
    {
        case "Paciente":
            header("Location: ../paginas/inicio_pacientes.php");
        break;

        case "HistFuncPaciente":
            header("Location: ../paginas/historico_pacientes.php");
        break;
    }

    exit;
?>