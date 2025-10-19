<?php
    session_start();

    include_once "conexao.php";

    $tabela = $_POST['tabela'];
    $busca = $_POST['busca'];
    $termo = "%" . $busca . "%";

    switch($tabela)
    {
        case "Paciente":
            $stmtBusca = $conexao->prepare("SELECT * FROM Paciente
                                    WHERE   id                 LIKE ?
                                    OR      nome               LIKE ?
                                    OR      email              LIKE ?
                                    OR      telefone           LIKE ?
                                    OR      dataNascimento     LIKE ?
                                    OR      cpf                LIKE ?
                                    OR      genero             LIKE ?");
    
            $stmtBusca->bind_param("issssss", $id, $termo, $termo, $termo, $termo, $termo, $termo);
            $stmtBusca->execute();
        break;

        case "HistFuncPaciente":
            $stmtBusca = $conexao->prepare("SELECT * FROM HistFuncPaciente
                                    WHERE   id                 LIKE ?
                                    OR      tipoAcao           LIKE ?
                                    OR      dtAcao             LIKE ?
                                    OR      idFuncionario      LIKE ?
                                    OR      idPaciente         LIKE ?");
    
            $stmtBusca->bind_param("issii", $id, $termo, $termo, $termo, $termo);
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
            exit;
        break;

        case "HistFuncPaciente":
            header("Location: ../paginas/historico_pacientes.php");
            exit;
        break;
    }
?>