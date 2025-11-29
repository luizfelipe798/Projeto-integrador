<?php
    session_start();

    require_once "../core/verifica_login.php";
    verificar_login();

    require_once "../core/conexao.php";
    require_once "../core/sql.php";
    require_once "../core/mysql.php";

    $modoEdicao = false;
    $idPaciente = '';
    $nome = '';
    $email = '';
    $telefone = '';
    $dataNascimento = '';
    $genero = '';
    $cpf = '';
    $dadosGeral = [];

    $titulo = "Cadastrar Paciente";
    $txtBotao = "Cadastrar";
    $acao = "Cadastro";

    if(isset($_SESSION['dados_formulario_cadastro']))
    {
        $dadosPaciente = $_SESSION['dados_formulario_cadastro'];
        unset($_SESSION['dados_formulario_cadastro']);
    }
    else if(isset($_SESSION['dados_formulario_edicao']))
    {
        $dadosPaciente = $_SESSION['dados_formulario_edicao'];
        unset($_SESSION['dados_formulario_edicao']);

        $modoEdicao = true;

        $idPaciente = htmlspecialchars($dadosPaciente['idPaciente']);
        $nome = htmlspecialchars($dadosPaciente['nome']);
        $email = htmlspecialchars($dadosPaciente['email']);
        $telefone = htmlspecialchars($dadosPaciente['telefone']);
        $dataNascimento = htmlspecialchars($dadosPaciente['dataNascimento']);

        $dataNascimento = date_create($dataNascimento);
        $dataNascimento = date_format($dataNascimento, 'Y-m-d');

        $genero = htmlspecialchars($dadosPaciente['genero']);
        $cpf = htmlspecialchars($dadosPaciente['cpf']);
    }
    else if(isset($_GET['id']))
    {
        $dadosGeral = $_GET;
        $modoEdicao = true;

        $condicao_busca_paciente = [
            ['id', '=', $_GET['id']]
        ];

        $dadosPaciente = buscar(
            'Paciente',
            [
                'id',
                'nome',
                'email',
                'telefone',
                'dataNascimento',
                'cpf',
                'genero'
            ],
            $condicao_busca_paciente
        );

        $idPaciente = htmlspecialchars($dadosGeral['id']);
        $nome = htmlspecialchars($dadosPaciente[0]['nome']);
        $email = htmlspecialchars($dadosPaciente[0]['email']);
        $telefone = htmlspecialchars($dadosPaciente[0]['telefone']);
        $dataNascimento = htmlspecialchars($dadosPaciente[0]['dataNascimento']);

        $dataNascimento = date_create($dataNascimento);
        $dataNascimento = date_format($dataNascimento, 'Y-m-d');

        $genero = htmlspecialchars($dadosPaciente[0]['genero']);
        $cpf = htmlspecialchars($dadosPaciente[0]['cpf']);
    }

    if($modoEdicao == true)
    {
        $titulo = "Editar Paciente";
        $txtBotao = "Editar";
        $acao = "Edição";
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

            <input type="hidden" name="idPaciente" value="<?=$idPaciente?>">

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

    <?php if(isset($_SESSION['mensagem_gerenciamento'])) : ?>
        <div class="errocadastrologin-container">
            <p><?=$_SESSION['mensagem_gerenciamento'];?></p>
        </div>
    <?php 
        unset($_SESSION['mensagem_gerenciamento']);
        endif;
    ?>
</body>
</html>