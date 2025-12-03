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
    <link rel="stylesheet" href="../css/perfil_usuario.css">

    <title><?= $paciente[0]['nome']?> - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        $acesso_perfil = false;

        include_once "../includes/menu_home.php";
    ?>

    <div class="dados-perfil-container">
        <div class="foto-e-nome-container">
            <img src="../imagens/perfil_usuario.png">

            <div class="nome-container">
                <h1><?= $paciente[0]['nome'] ?></h1>
                <h4>Paciente</h4>
            </div>
        </div>

        <?php if(isset($_SESSION['mensagem_perfil'])): ?>
            <div class="rsltd-acoes-container">
                <p><?=$_SESSION['mensagem_perfil']?></p>
            </div>
        <?php
            unset($_SESSION['mensagem_perfil']);
            endif;
        ?>

        <div class="dados-usuario-container">
            <div class="titulo-dados-usuario">
                <h1>Dados do paciente</h1>
            </div>

            <div class="label-e-input-dados-container">
                <label for="nome">Nome</label>
                <input type="text" name="nome" value="<?=$paciente[0]['nome']?>" disabled>
            </div>

            <div class="label-e-input-dados-container">
                <label for="email">E-mail</label>
                <input type="text" name="email" value="<?=$paciente[0]['email']?>" disabled>
            </div>

            <div class="label-e-input-dados-container">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" value="<?=$paciente[0]['telefone']?>" disabled>
            </div>

            <div class="label-e-input-dados-container">
                <label for="dataNascimento">Data de nascimento</label>
                <input type="text" name="dataNascimento" value="<?=$paciente[0]['dataNascimento']?>" disabled>
            </div>

            <div class="label-e-input-dados-container">
                <label for="cpf">CPF</label>
                <input type="text" name="cpf" value="<?=$paciente[0]['cpf']?>" disabled>
            </div>

            <div class="label-e-input-dados-container">
                <label for="genero">Gênero</label>
                <input type="text" name="genero" value="<?=$paciente[0]['genero']?>" disabled>
            </div>
        </div>
    </div>

    <div class="dados-acoes-container">
        <?php if($_SESSION['usuario']['tipoUsuario'] == "Medico"): ?>
            <div class="titulo-dados-acoes-container">
                <h1>Emitir atestado médico</h1>
            </div>

            <div class="opcoes-acoes-container">
                <a href="emitir_atestado.php?id=<?=$paciente[0]['id']?>">Emitir</a>
            </div>
        <?php endif; ?>
    </div>

    <?php
        include "../includes/rodape.php";
    ?>
</body>
</html>