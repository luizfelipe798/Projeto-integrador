<?php
    include_once "../core/tempo_sessao.php";
    session_start();

    include_once "../core/verifica_login.php";
    verificar_login();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../imagens/logotiposalus.png" type="image/x-icon">

    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/rodape.css">
    <link rel="stylesheet" href="../css/home_usuario.css">

    <title>Início - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        include_once "../includes/menu_home.php";
        include_once "../core/saudacao.php";
    ?>

    <div class="saudacao-container">
        <h1>Olá <?=saudacao()?> O que você gostaria de fazer agora?</h1>
    </div>

    <div class="global-containers">
        <div class="opcoes-container">
            <img src="../imagens/paciente.png" alt="Paciente">
            <a href="inicio_pacientes.php">Pacientes</a>
        </div>
        <div class="opcoes-container">
            <img src="../imagens/consulta.png" alt="Consulta">
            <a href="">Consultas</a>
        </div>
        <?php if($_SESSION['tipo_usuario'] == "Funcionario"): ?>
            <div class="opcoes-container">
                <img src="../imagens/medico.png" alt="Médico">
                <a href="">Médicos</a>
            </div>
        <?php else: ?>
            <div class="opcoes-container">
                <img src="../imagens/medico.png" alt="Médico">
                <a href="">Funcionários</a>
            </div>
        <?php endif; ?>
    </div>

    <?php
        include "../includes/rodape.php";
    ?>
</body>
</html>