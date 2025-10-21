<?php
    session_start();

    $medico = [];

    $nome = '';
    $email = '';
    $telefone = '';
    $senha = '';
    $crm = '';
    $especialidade = '';
    $plantonista = '';

    if(isset($_SESSION['dados_formulario']))
    {
        $medico = $_SESSION['dados_formulario'];
        unset($_SESSION['dados_formulario']);

        $nome = htmlspecialchars($medico['nome']);
        $email = htmlspecialchars($medico['email']);
        $telefone = htmlspecialchars($medico['telefone']);
        $senha = htmlspecialchars($medico['senha']);
        $crm = htmlspecialchars($medico['crm']);
        $especialidade = htmlspecialchars($medico['especialidade']);
        $plantonista = htmlspecialchars($medico['plantonista']);
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../imagens/logotiposalus.png" type="image/x-icon">

    <link rel="stylesheet" href="../css/cadastro_login.css">

    <title>Cadastro de médico - Sailus</title>
</head>
<body>
    <form action="../core/usuario_repositorio.php" method="POST">
        <div class="logoFormulario">
            <a href="../index.php"><img src="../imagens/logosomentetexto.png" alt="Logotipo Sailus"></a>
        </div>
        <div class="inputsFormulario">
            <input type="hidden" name="tipo_user" value="Medico">
            <input type="hidden" name="acao" value="cadastro">

            <input type="text" placeholder="Nome" name="nome" value="<?=$nome?>" required>

            <input type="email" placeholder="E-mail" name="email" value="<?=$email?>" required>

            <input type="tel" placeholder="Telefone: (XX) XXXXX-XXXX" name="telefone" value="<?=$telefone?>" pattern="\([0-9]{2}\)\s[0-9]{4,5}-[0-9]{4}" required>

            <input type="password" placeholder="Senha" name="senha" value="<?=$senha?>" required>

            <input type="text" placeholder="CRM: XXXXXX/UF" name="crm" value="<?=$crm?>" pattern="^\d{4,6}\/[A-Z]{2}$" required>

            <input type="text" placeholder="Especialidade" name="especialidade" value="<?=$especialidade?>" required>

            <div>
                <label for="plantonista">Plantonista?</label>
            </div>

            <select name="plantonista" id="plantonista">
                <option value="Sim" <?= $plantonista == 'Sim' ? 'selected' : '' ?>>Sim</option>
                <option value="Não" <?= $plantonista == 'Não' ? 'selected' : '' ?>>Não</option>
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