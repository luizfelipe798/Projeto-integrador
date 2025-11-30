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

    $criterio = [['id', '=', $id]];

    switch($acao)
    {
        case "Concluir":
            $campo = [
                'stattus' => 'Concluída'
            ];

            atualiza('Consulta', $campo, $criterio);

            $_SESSION['mensagem_gerenciamento'] = "Consulta concluída com sucesso!";

            header("Location: ../paginas/acoes_consultas.php");
            exit;
        break;
    }
?>