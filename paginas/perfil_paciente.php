<?php
    session_start();

    require_once "../core/verifica_login.php";
    verificar_login();

    require_once "../core/conexao.php";
    require_once '../core/sql.php';
    require_once '../core/mysql.php';

    $id = $_GET['id'];

    $paciente = buscar('Paciente', ['*'], [['id', '=', $id]]);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../imagens/logotiposalus.png" type="image/x-icon">

    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/rodape.css">

    <title><?= $paciente[0]['nome']?> - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        $acesso_perfil = false;

        include_once "../includes/menu_home.php";
    ?>
</body>
</html>