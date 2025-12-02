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
        case "Desativação":
            $dados = [
                'ativo' => 0
            ];

            atualiza('Usuario', $dados, $criterio);
            
            $campos_historico = [
                'idAdm' => $_SESSION['usuario']['id'],
                'idUsuario' => $id,
                'tipoAcao' => $acao,
            ];

            insere('Administracoes', $campos_historico);

            $_SESSION['mensagem_gerenciamento'] = "$nome desativado(a) com sucesso!";
        break;

        case "Ativação":
            $dados = [
                'ativo' => 1
            ];

            atualiza('Usuario', $dados, $criterio);

            $campos_historico = [
                'idAdm' => $_SESSION['usuario']['id'],
                'idUsuario' => $id,
                'tipoAcao' => $acao,
            ];

            insere('Administracoes', $campos_historico);

            $_SESSION['mensagem_gerenciamento'] = "$nome ativado(a) com sucesso!";
        break;

        case "Promoção":
            $dados = [
                'adm' => 1
            ];

            atualiza('Usuario', $dados, $criterio);

            $campos_historico = [
                'idAdm' => $_SESSION['usuario']['id'],
                'idUsuario' => $id,
                'tipoAcao' => $acao,
            ];

            insere('Administracoes', $campos_historico);

            $_SESSION['mensagem_gerenciamento'] = "$nome promovido(a) com sucesso!";
        break;

        case "Rebaixamento":
            $dados = [
                'adm' => 0
            ];

            atualiza('Usuario', $dados, $criterio);

            $campos_historico = [
                'idAdm' => $_SESSION['usuario']['id'],
                'idUsuario' => $id,
                'tipoAcao' => $acao,
            ];

            insere('Administracoes', $campos_historico);

            $_SESSION['mensagem_gerenciamento'] = "$nome rebaixado(a) com sucesso!";
        break;
    }

    if($tipoUser == "Medico")
    {
        header("Location: ../paginas/inicio_medicos.php");
        exit;
    }
    else
    {
        header("Location: ../paginas/inicio_funcionarios.php");
        exit;
    }
?>