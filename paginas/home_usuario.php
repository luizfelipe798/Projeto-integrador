<?php
    require_once "../core/tempo_sessao.php";
    session_start();

    require_once "../core/verifica_login.php";
    verificar_login();

    require_once "../core/saudacao.php";
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
        $paginaAtual = "HomeUsuario";
        $acesso_perfil = false;
        
        include "../includes/menu_home.php";
    ?>

    <div class="saudacao-container">
        <h1><?=saudacao()?></h1>
    </div>

    <div class="global-containers">
        <div class="opcoes-container">
                <img src="../imagens/medico.png" alt="Funcionários">
                <a href="">Funcionários</a>
        </div>
        <div class="opcoes-container">
                <img src="../imagens/medico.png" alt="Médico">
                <a href="">Médicos</a>
        </div>
        <div class="opcoes-container">
            <img src="../imagens/paciente.png" alt="Paciente">
            <a href="inicio_pacientes.php">Pacientes</a>
        </div>
        <div class="opcoes-container">
            <img src="../imagens/consulta.png" alt="Consulta">
            <a href="inicio_consultas.php">Consultas</a>
        </div>
        <div class="opcoes-container">
                <img src="../imagens/medico.png" alt="Funcionários">
                <a href="">Atestados</a>
        </div>
    </div>

    <?php
        include "../includes/rodape.php";
    ?>
</body>
</html>