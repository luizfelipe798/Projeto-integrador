<?php
    include_once "../core/tempo_sessao.php";
    session_start();
    
    if(isset($_SESSION['usuario']['logado']))
    {
        header("Location: home_usuario.php");
        exit;
    }

    $email = '';
    $senha = '';
    $tipoUsuario = '';

    if(isset($_SESSION['dados_formulario']))
    {
        $usuario = $_SESSION['dados_formulario'];
        unset($_SESSION['dados_formulario']);

        $email = htmlspecialchars($usuario['email']);
        $senha = htmlspecialchars($usuario['senha']);
        $tipoUsuario = htmlspecialchars($usuario['tipo_user']);
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../imagens/logotiposalus.png" type="image/x-icon">

    <link rel="stylesheet" href="../css/cadastro_login.css">

    <title>Login - Sailus</title>
</head>
<body>
    <form action="../core/usuario_repositorio.php" method="POST">
        <div class="logoFormulario">
            <a href="../index.php"><img src="../imagens/logosomentetexto.png" alt="Logotipo Sailus"></a>
        </div>
        <div class="inputsFormulario">
            <input type="hidden" name="acao" value="Login">

            <input type="email" placeholder="E-mail" name="email" value="<?=$email?>" required>

            <input type="password" placeholder="Senha" value="<?=$senha?>" required name="senha">

            <div>
                <label for="tipoUsuario">Tipo de usuário:</label>
            </div>

            <select name="tipo_user" id="tipoUsuario">
                <option value="Funcionario" <?= $tipoUsuario == 'Funcionario' ? 'selected' : ''?>>Funcionário</option>
                <option value="Medico" <?= $tipoUsuario == 'Medico' ? 'selected' : ''?>>Médico</option>
            </select>
        </div>
        <div class="btnFormulario">
            <button type="submit">Login</button>
        </div>
    </form>
    <div class="naoPossuiFormulario">
        <p>Ainda não é cadastrado?</p>
        <a href="escolha.html">Cadastrar-se</a>
    </div>

    <?php if(isset($_SESSION['mensagem_login'])) : ?>
        <div class="errocadastrologin-container">
            <p><?=$_SESSION['mensagem_login'];?></p>
        </div>
    <?php
        unset($_SESSION['mensagem_login']);
        endif;
    ?>
</body>
</html>