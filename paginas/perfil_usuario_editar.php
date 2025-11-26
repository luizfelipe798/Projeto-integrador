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
    <link rel="stylesheet" href="../css/perfil_usuario.css">

    <title>Perfil de <?=$_SESSION['usuario']['nome']?> - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        $acesso_perfil = true;

        include_once "../includes/menu_home.php";
    ?>

    <div class="dados-perfil-container">
        <div class="foto-e-nome-container">
            <img src="../imagens/perfil_usuario.png">

            <div class="nome-container">
                <h1><?=$_SESSION['usuario']['nome']?></h1>
                <h4><?=$_SESSION['usuario']['tipoUsuario']?></h4>

                <div class="alterar-container">
                    <a href="perfil_usuario.php">Cancelar</a>
                </div>
            </div>
        </div>

        <form class="dados-usuario-container" action="../core/usuario_repositorio.php" method="POST">
            <input type="hidden" name="tipoUsuario" value="<?=$_SESSION['usuario']['tipoUsuario']?>">
            <input type="hidden" name="acao" value="Edição">

            <div class="titulo-dados-usuario">
                <h1>Dados do usuário</h1>
            </div>

            <div class="label-e-input-dados-container">
                <label for="email">E-mail</label>
                <input type="email" name="email" value="<?=$_SESSION['usuario']['email']?>" disabled>
            </div>

            <div class="label-e-input-dados-container">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" value="<?=$_SESSION['usuario']['telefone']?>" disabled>
            </div>

            <div class="label-e-input-dados-container">
                <label for="senha">Senha</label>
                <input type="password" name="senha" value="<?=$_SESSION['usuario']['senha']?>" disabled>
            </div>

            <?php if($_SESSION['usuario']['tipoUsuario'] == "Medico"): ?>
                <div class="label-e-input-dados-container">
                    <label for="crm">CRM</label>
                    <input type="text" name="crm" value="<?=$_SESSION['usuario']['crm']?>" disabled>
                </div>

                <div class="label-e-input-dados-container">
                    <label for="especialidade">Especialidade</label>
                    <input type="text" name="especialidade" value="<?=$_SESSION['usuario']['especialidade']?>" disabled>
                </div>

                <div class="label-e-input-dados-container">
                    <label for="plantonista">Plantonista</label>
                    <input type="text" name="plantonista" value="<?=$_SESSION['usuario']['plantonista'] ? 'Sim' : 'Não'?>" disabled>
                </div>
            <?php else: ?>
                <div class="label-e-input-dados-container">
                    <label for="dtContratacao">Data de contratação</label>
                    <input type="date" name="dtContratacao" value="<?=$_SESSION['usuario']['dataContratacao']?>" disabled>
                </div>

                <div class="label-e-input-dados-container">
                    <label for="turno">Turno</label>
                    <input type="text" name="turno" value="<?=$_SESSION['usuario']['turno']?>" disabled>
                </div>
            <?php endif; ?>

            <div class="form-btn-container">
                <button type="submit">Alterar</button>
            </div>
        </form>
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
            <?php if($_SESSION['usuario']['tipoUsuario'] == "Funcionario" || $_SESSION['usuario']['tipoUsuario'] == "Adm"): ?>
                <a href="pacientes_funcionario.php">Pacientes</a>
            <?php endif; ?>
                <a href="consultas.php">Acessar</a>
        </div>
    </div>
    
    <?php
        include "../includes/rodape.php";
    ?>
</body>
</html>