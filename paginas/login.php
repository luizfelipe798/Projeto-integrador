<?php
    session_start();
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
            <input type="hidden" name="acao" value="login">
            <input type="email" placeholder="E-mail" required name="email">

            <input type="password" placeholder="Senha" required name="senha">

            <div>
                <label for="tipoUsuario">Tipo de usuário:</label>
            </div>

            <select name="tipo_user" id="tipoUsuario">
                <option value="Funcionario">Funcionário</option>
                <option value="Medico">Médico</option>
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

    <?php if(isset($_SESSION['erro_login'])) : ?>
        <div class="errocadastrologin-container">
            <p><?=$_SESSION['erro_login'];?></p>
        </div>
    <?php
        unset($_SESSION['erro_login']);
        endif;
    ?>
</body>
</html>