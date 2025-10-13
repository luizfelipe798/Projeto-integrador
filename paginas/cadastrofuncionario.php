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

    <title>Cadastro de funcionário - Sailus</title>
</head>
<body>
    <form action="../core/usuario_repositorio.php" method="POST">
        <div class="logoFormulario">
            <a href="../index.php"><img src="../imagens/logosomentetexto.png" alt="Logotipo Sailus"></a>
        </div>
        <div class="inputsFormulario">
            <input type="hidden" name="tipo_user" value="Funcionario">
            <input type="hidden" name="acao" value="cadastro">

            <input type="text" placeholder="Nome" name="nome" required>

            <input type="email" placeholder="E-mail" name="email" required>

            <input type="tel" placeholder="Telefone: (XX) XXXXX-XXXX" name="telefone" pattern="\([0-9]{2}\)\s[0-9]{4,5}-[0-9]{4}" required>

            <input type="password" placeholder="Senha" name="senha" required>

            <input type="date" placeholder="Data de contratação" name="dtContratacao" required>

            <div>
                <label for="turno">Turno:</label>
            </div>

            <select name="turno" id="turno">
                <option value="Manhã">Manhã</option>
                <option value="Tarde">Tarde</option>
                <option value="Noite">Noite</option>
            </select>
        </div>
        <div class="btnFormulario">
            <button type="submit">Cadastrar-se</button>
        </div>
    </form>

    <div class="naoPossuiFormulario">
        <p>Já é cadastrado?</p>
        <a href="login.php">logar-se</a>
    </div>

    <?php if(isset($_SESSION['erro_cadastro'])) : ?>
        <div class="errocadastrologin-container">
            <p><?=$_SESSION['erro_cadastro'];?></p>
        </div>
    <?php 
        unset($_SESSION['erro_cadastro']);
        endif;
    ?>
</body>
</html>