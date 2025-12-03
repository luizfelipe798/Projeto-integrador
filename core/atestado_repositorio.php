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

    $dataAtestado = "$dtValidade 23:59:59";
    $dataAtestado = new DateTime($dataAtestado);
    $data_atual = new DateTime();

    if($dataAtestado <= $data_atual)
    {
        $_SESSION['mensagem_emissao'] = "Não é possível emitir atestados no passado.";
        $_SESSION['dados_formulario'] = $_POST;

        header("Location: ../paginas/emitir_atestado.php?id=$idPaciente");
        exit;
    }

    $campos_atestado = [
        'dtValidade' => $dtValidade,
        'descricao' => $descricao,
        'motivo' => $motivo,
        'idPaciente' => $idPaciente,
        'idMedico' => $idMedico
    ];

    insere('Atestado', $campos_atestado);

    $_SESSION['mensagem_perfil'] = "Atestado emitido com sucesso! Veja-o na sessão de atestados.";
    header("Location: ../paginas/perfil_paciente.php?id=$idPaciente");
    exit;
?>