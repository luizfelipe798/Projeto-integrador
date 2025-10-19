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

    <link rel="stylesheet" href="../css/cadastro_login.css">

    <title>Cadastrar paciente - Sailus</title>
</head>
<body>

    <div class="titulo-form-container">
        <h1>Cadastro de paciente</h1>
    </div>
    
    <form action="../core/paciente_repositorio.php" method="POST">
        <div class="logoFormulario">
            <a href="inicio_pacientes.php"><img src="../imagens/logosomentetexto.png" alt="Logotipo Sailus"></a>
        </div>
        <div class="inputsFormulario">
            <input type="hidden" name="acao" value="Cadastro">

            <input type="text" placeholder="Nome..." name="nome" required>

            <input type="email" placeholder="E-mail..." name="email" required>

            <input type="tel" placeholder="Telefone: (XX) XXXXX-XXXX" name="telefone" pattern="\([0-9]{2}\)\s[0-9]{4,5}-[0-9]{4}" required>

            <input type="date" placeholder="Data de nascimento..." name="dataNascimento" required>

            <input type="text" placeholder="CPF: XXX.XXX.XXX-XX" name="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" required>

            <div>
                <label for="genero">Gênero:</label>
            </div>

            <select name="genero" id="genero">
                <option value="Masculino">Masculino</option>
                <option value="Feminino">Feminino</option>
            </select>
        </div>
        <div class="btnFormulario">
            <button type="submit">Cadastrar</button>
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