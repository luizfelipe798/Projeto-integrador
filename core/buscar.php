<?php
    session_start();

    include_once "conexao.php";

    $tabela = $_POST['tabela'];
    $busca = $_POST['busca'];

    $termo = "%" . $busca . "%";

    $stmtBusca = $conexao->prepare("SELECT * FROM Paciente
                                    WHERE   id                 LIKE ?
                                    OR      nome               LIKE ?
                                    OR      email              LIKE ?
                                    OR      telefone           LIKE ?
                                    OR      dataNascimento     LIKE ?
                                    OR      cpf                LIKE ?
                                    OR      genero             LIKE ?");
    
    $stmtBusca->bind_param("ssssssi", $termo, $termo, $termo, $termo, $termo, $termo, $id);
    $stmtBusca->execute();

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

    header("Location: ../paginas/inicio_pacientes.php");
    exit;
?>