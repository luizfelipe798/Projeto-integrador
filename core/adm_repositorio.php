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
        case "Desativação":
            $dados_funcionario = [
                'ativo' => 0
            ];

            $criterio_desativar = [['id', '=', $id]];

            atualiza('Usuario', $dados_funcionario, $criterio_desativar);

            $_SESSION['mensagem_gerenciamento'] = $nome . " desativado(a) com sucesso!";
            
            header("Location: ../paginas/inicio_funcionarios.php");
            exit;
        break;

        case "Ativação":
            $dados_funcionario = [
                'ativo' => 1
            ];

            $criterio_ativar = [['id', '=', $id]];

            atualiza('Usuario', $dados_funcionario, $criterio_ativar);

            $_SESSION['mensagem_gerenciamento'] = $nome . " ativado(a) com sucesso!";
            
            header("Location: ../paginas/inicio_funcionarios.php");
            exit;
        break;

        case "Promoção":
            $dados_funcionario = [
                'adm' => 1
            ];

            $criterio_promover = [['id', '=', $id]];

            atualiza('Usuario', $dados_funcionario, $criterio_promover);

            $_SESSION['mensagem_gerenciamento'] = $nome . " promovido(a) com sucesso!";
            
            header("Location: ../paginas/inicio_funcionarios.php");
            exit;
        break;

        case "Rebaixamento":
            $dados_funcionario = [
                'adm' => 0
            ];

            $criterio_rebaixar = [['id', '=', $id]];

            atualiza('Usuario', $dados_funcionario, $criterio_rebaixar);

            $_SESSION['mensagem_gerenciamento'] = $nome . " rebaixado(a) com sucesso!";
            
            header("Location: ../paginas/inicio_funcionarios.php");
            exit;
        break;
    }

?>