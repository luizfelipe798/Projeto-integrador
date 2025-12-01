<?php
    session_start();

    require_once "../core/verifica_login.php";
    verificar_login();

    require_once "../core/conexao.php";
    require_once "../core/sql.php";
    require_once "../core/mysql.php";

    date_default_timezone_set('America/Sao_Paulo');

    $dadosGeral = [];
    $modoEdicao = false;
    $id = '';
    $data_horario = '';
    $hora_horario = '';
    $valor = '';
    $especialidade = '';
    $idMedico = '';
    $idPaciente = '';

    $titulo = "Agendar consulta";
    $txtBotao = "Agendar";
    $acao = "Agendamento";

    if(isset($_SESSION['dados_formulario_cadastro']))
    {
        $dadosConsulta = $_SESSION['dados_formulario_cadastro'];
        unset($_SESSION['dados_formulario_cadastro']);
        
        $data_horario = htmlspecialchars($dadosConsulta['data_horario']);
        $hora_horario = htmlspecialchars($dadosConsulta['hora_horario']);
        $valor = htmlspecialchars($dadosConsulta['valor']);
        $especialidade = htmlspecialchars($dadosConsulta['especialidade']);
        $idMedico = htmlspecialchars($dadosConsulta['idMedico']);
        $idPaciente = htmlspecialchars($dadosConsulta['idPaciente']);
    }
    else if(isset($_SESSION['dados_formulario_edicao']))
    {
        $dadosConsulta = $_SESSION['dados_formulario_edicao'];
        unset($_SESSION['dados_formulario_edicao']);

        $modoEdicao = true;

        $id = htmlspecialchars($dadosConsulta['id']);
        
        $data_horario = htmlspecialchars($dadosConsulta['data_horario']); 
        $hora_horario = htmlspecialchars($dadosConsulta['hora_horario']);

        $valor = htmlspecialchars($dadosConsulta['valor']);
        $especialidade = htmlspecialchars($dadosConsulta['especialidade']);
        $idMedico = htmlspecialchars($dadosConsulta['idMedico']);
        $idPaciente = htmlspecialchars($dadosConsulta['idPaciente']);
    }
    else if(isset($_GET['id']))
    {
        $modoEdicao = true;

        $condicao_busca_consulta = [
            ['id', '=', $_GET['id']]
        ];

        $dadosConsulta = buscar('Consulta', ['*'], $condicao_busca_consulta);

        $id = htmlspecialchars($dadosConsulta[0]['id']);
        $horario = htmlspecialchars($dadosConsulta[0]['horario']);
        $valor = htmlspecialchars($dadosConsulta[0]['valor']);
        $especialidade = htmlspecialchars($dadosConsulta[0]['especialidade']);
        $idMedico = htmlspecialchars($dadosConsulta[0]['idMedico']);
        $idPaciente = htmlspecialchars($dadosConsulta[0]['idPaciente']);

        $horario_obj = date_create($horario);
        $data_horario = date_format($horario_obj, 'Y-m-d');
        $hora_horario = date_format($horario_obj, 'H:i');
    }

    if($modoEdicao == true)
    {
        $titulo = "Editar consulta";
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
    
    <form action="../core/consultas_repositorio.php" method="POST">
        <div class="logoFormulario">
            <a href="inicio_consultas.php"><img src="../imagens/logosomentetexto.png" alt="Logotipo Sailus"></a>
        </div>
        <div class="inputsFormulario">
            <input type="hidden" name="acao" value="<?=$acao?>">
            <input type="hidden" name="id" value="<?=$id?>">

            <div>
                <label for="data_horario">Data:</label>
            </div>

            <input type="date" name="data_horario" id="data_horario" value="<?=$data_horario?>" required>

            <div>
                <label for="hora_horario">Horário:</label>
            </div>

            <input type="time" name="hora_horario" id="hora_horario" value="<?=$hora_horario?>" required>

            <input type="number" placeholder="Valor em R$: XXXXX.XX" name="valor" value="<?=$valor?>" step="0.01" min="0.00" max="99999.99" required>

            <div>
                <label for="especialidade">Especialidade:</label>
            </div>

            <select name="especialidade" id="especialidade">
                <option value="Clínica Médica" <?= $especialidade == 'Clínica Médica' ? 'selected' : '' ?>>Clínica Médica</option>
                <option value="Pediatria" <?= $especialidade == 'Pediatria' ? 'selected' : '' ?>>Pediatria</option>
                <option value="Ginecologia" <?= $especialidade == 'Ginecologia' ? 'selected' : '' ?>>Ginecologia</option>
                <option value="Dermatologia" <?= $especialidade == 'Dermatologia' ? 'selected' : '' ?>>Dermatologia</option>
                <option value="Ortopedia" <?= $especialidade == 'Ortopedia' ? 'selected' : '' ?>>Ortopedia</option>
                <option value="Otorrinolaringologia" <?= $especialidade == 'Otorrinolaringologia' ? 'selected' : '' ?>>Otorrinolaringologia</option>
                <option value="Neurologia" <?= $especialidade == 'Neurologia' ? 'selected' : '' ?>>Neurologia</option>
                <option value="Endocrinologia" <?= $especialidade == 'Endocrinologia' ? 'selected' : '' ?>>Endocrinologia</option>
                <option value="Gastroenterologia" <?= $especialidade == 'Gastroenterologia' ? 'selected' : '' ?>>Gastroenterologia</option>
                <option value="Urologia" <?= $especialidade == 'Urologia' ? 'selected' : '' ?>>Urologia</option>
                <option value="Infectologia" <?= $especialidade == 'Infectologia' ? 'selected' : '' ?>>Infectologia</option>
                <option value="Reumatologia" <?= $especialidade == 'Reumatologia' ? 'selected' : '' ?>>Reumatologia</option>
            </select>

            <input type="number" placeholder="ID do médico" name="idMedico" id="idMedico" value="<?=$idMedico?>" step="1" required>

            <input type="number" placeholder="ID do paciente" name="idPaciente" id="idPaciente" value="<?=$idPaciente?>" step="1" required>
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