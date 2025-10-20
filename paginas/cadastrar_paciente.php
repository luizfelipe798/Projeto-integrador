<?php
    include_once "../core/tempo_sessao.php";
    session_start();

    include_once "../core/verifica_login.php";
    verificar_login();

    $modoEdicao = false;
    $idPaciente = '';
    $nome = '';
    $email = '';
    $telefone = '';
    $dataNascimento = '';
    $genero = '';
    $cpf = '';

    $titulo = "Cadastrar Paciente";
    $txtBotao = "Cadastrar";
    $acao = "Cadastro";
    $dadosGeral = [];

    if(isset($_SESSION['dados_formulario']))
    {
        $dadosGeral = $_SESSION['dados_formulario'];
        unset($_SESSION['dados_formulario']);
    }
    else if(isset($_GET['id']))
    {
        $dadosGeral = $_GET;

        $modoEdicao = true;
        $titulo = "Editar Paciente";
        $txtBotao = "Editar";
        $acao = "Edição";
    }

    if(!empty($dadosGeral))
    {
        $idPaciente = htmlspecialchars($dadosGeral['id'] ?? $idPaciente);
        $nome = htmlspecialchars($dadosGeral['nome'] ?? '');
        $email = htmlspecialchars($dadosGeral['email'] ?? '');
        $telefone = htmlspecialchars($dadosGeral['telefone'] ?? '');
        $dataNascimento = htmlspecialchars($dadosGeral['dataNascimento'] ?? '');

        if($modoEdicao == true)
        {
            $dataFormatada = DateTime::createFromFormat('d/m/Y', $dataNascimento);
            
            if($dataFormatada !== false) 
            {
                $dataNascimento = $dataFormatada->format('Y-m-d');
            }
        }

        $genero = htmlspecialchars($dadosGeral['genero'] ?? '');
        $cpf = htmlspecialchars($dadosGeral['cpf'] ?? '');
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../imagens/logotiposalus.png" type="image/x-icon">

    <link rel="stylesheet" href="../css/cadastro_login.css">

    <title><?=$titulo?> - Sailus</title>
</head>
<body>

    <div class="titulo-form-container">
        <h1><?=$titulo?></h1>
    </div>
    
    <form action="../core/paciente_repositorio.php" method="POST">
        <div class="logoFormulario">
            <a href="inicio_pacientes.php"><img src="../imagens/logosomentetexto.png" alt="Logotipo Sailus"></a>
        </div>
        <div class="inputsFormulario">
            <input type="hidden" name="acao" value="<?=$acao?>">
            
            <?php if(isset($_GET['id'])): ?>
                <input type="hidden" name="emailGet" value="<?=$email?>">
                <input type="hidden" name="cpfGet" value="<?=$cpf?>">
            <?php endif; ?>
            
            <?php if($modoEdicao): ?>
                <input type="hidden" name="idPaciente" value="<?=$idPaciente?>">
            <?php endif; ?>

            <input type="text" placeholder="Nome..." name="nome" value="<?=$nome?>" required>

            <input type="email" placeholder="E-mail..." name="email" value="<?=$email?>" required>

            <input type="tel" placeholder="Telefone: (XX) XXXXX-XXXX" name="telefone" value="<?=$telefone?>" pattern="\([0-9]{2}\)\s[0-9]{4,5}-[0-9]{4}" required>

            <input type="date" placeholder="Data de nascimento..." name="dataNascimento" value="<?=$dataNascimento?>" required>

            <input type="text" placeholder="CPF: XXX.XXX.XXX-XX" name="cpf" value="<?=$cpf?>" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" required>

            <div>
                <label for="genero">Gênero:</label>
            </div>

            <select name="genero" id="genero">
                <option value="Masculino" <?=$genero == 'Masculino' ? 'selected' : ''?>>Masculino</option>
                <option value="Feminino" <?=$genero == 'Feminino' ? 'selected' : ''?>>Feminino</option>
            </select>
        </div>
        <div class="btnFormulario">
            <button type="submit"><?=$txtBotao?></button>
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