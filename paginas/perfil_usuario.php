<?php
    require_once "../core/tempo_sessao.php";
    session_start();

    require_once "../core/verifica_login.php";
    verificar_login();

    require_once "../core/conexao.php";
    require_once "../core/sql.php";
    require_once "../core/mysql.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../imagens/logotiposalus.png" type="image/x-icon">

    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/rodape.css">

    <title>Perfil de <?=$_SESSION['usuario']['nome']?> - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        include_once "../includes/menu_home.php";
    ?>

    <div class="dados-perfil-container">
        <div class="foto-e-nome-container">
            <img src="../imagens/perfil_usuario.png">

            <div class="nome-container">
                <h1><?=$_SESSION['usuario']['nome']?></h1>
                <h4><?=$_SESSION['usuario']['tipoUsuario']?></h4>
                <a href="#">Alterar dados</a>
            </div>
        </div>

        <div class="dados-usuario-container">
            <div class="label-e-input-dados-container">
                <label for="">E-mail</label>
                <input type="email" value="<?=$_SESSION['usuario']['email']?>" disabled>
            </div>

            <div class="label-e-input-dados-container">
                <label for="">Telefone</label>
                <input type="text" value="<?=$_SESSION['usuario']['telefone']?>" disabled>
            </div>

            <div class="label-e-input-dados-container">
                <label for="">Senha</label>
                <input type="password" value="<?=$_SESSION['usuario']['senha']?>" disabled>
            </div>
        </div>

        <?php if($_SESSION['usuario']['tipoUsuario'] == "Medico"): ?>
            <div class="label-e-input-dados-container">
                <label for="">CRM</label>
                <input type="text" value="<?=$_SESSION['usuario']['crm']?>" disabled>
            </div>

            <div class="label-e-input-dados-container">
                <label for="">Especialidade</label>
                <input type="text" value="<?=$_SESSION['usuario']['especialidade']?>" disabled>
            </div>

            <div class="label-e-input-dados-container">
                <label for="">Plantonista</label>
                <input type="text" value="<?=$_SESSION['usuario']['plantonista'] ? 'Sim' : 'Não'?>" disabled>
            </div>
        <?php else: ?>
            <div class="label-e-input-dados-container">
                <label for="">Data de contratação</label>
                <input type="date" value="<?=$_SESSION['usuario']['dataContratacao']?>" disabled>
            </div>

            <div class="label-e-input-dados-container">
                <label for="">Turno</label>
                <input type="text" value="<?=$_SESSION['usuario']['turno']?>" disabled>
            </div>
        <?php endif; ?>
    </div>

    <div class="dados-acoes-container">
        <div class="titulo-dados-acoes-container">
            <?php if($_SESSION['usuario']['tipoUsuario'] == "Funcionario"): ?>
                <h1>Ações no sistema</h1>
            <?php else: ?>
                <h1>Consultas agendadas</h1>
            <?php endif; ?>
        </div>

        <div class="opcoes-acoes-container">
            <a href="pacientes_funcionario.php">Pacientes</a>
            <a href="consultas_funcionario.php">Consultas</a>
        </div>
    </div>
    
    <?php
     include "../includes/rodape.php";
    ?>
</body>
</html>