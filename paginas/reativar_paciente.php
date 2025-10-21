<?php
    include_once "../core/tempo_sessao.php";
    session_start();
    
    include_once "../core/verifica_login.php";
    verificar_login();

    $dadosGeral = [];
    $email = '';
    $cpf = '';

    if(isset($_SESSION['dados_formulario']))
    {
        $dadosGeral = $_SESSION['dados_formulario'];
        unset($_SESSION['dados_formulario']);

        $email = $dadosGeral['email'] ?? '';
        $cpf = $dadosGeral['cpf'] ?? '';
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../imagens/logotiposalus.png" type="image/x-icon">

    <link rel="stylesheet" href="../css/cadastro_login.css">

    <title>Reativar Paciente - Sailus</title>
</head>
<body>
    <div class="titulo-form-container">
        <h1>Reativar Paciente</h1>
    </div>

    <form action="../core/paciente_repositorio.php" method="POST">
        <div class="logoFormulario">
            <a href="inicio_pacientes.php"><img src="../imagens/logosomentetexto.png" alt="Logotipo Sailus"></a>
        </div>
        <div class="inputsFormulario">
            <input type="hidden" name="acao" value="Reativação">

            <input type="email" placeholder="E-mail" name="email" value="<?=$email?>" required>

            <input type="text" placeholder="CPF: XXX.XXX.XXX-XX" name="cpf" value="<?=$cpf?>" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" required>
        </div>
        <div class="btnFormulario">
            <button type="submit">Reativar</button>
        </div>
    </form>

    <?php if(isset($_SESSION['retorno_paciente'])) : ?>
        <div class="errocadastrologin-container">
            <p><?=$_SESSION['retorno_paciente'];?></p>
        </div>
    <?php
        unset($_SESSION['retorno_paciente']);
        endif;
    ?>
</body>
</html>