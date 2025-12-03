<?php
    session_start();

    require_once "../core/verifica_login.php";
    verificar_login();

    require_once "../core/conexao.php";
    require_once "../core/sql.php";
    require_once "../core/mysql.php";

    if($_SESSION['usuario']['tipoUsuario'] != 'Medico')
    {
        $_SESSION['mensagem_perfil'] = "Acesso negado. Apenas médicos podem emitir atestados.";
        header("Location: inicio_medicos.php");
        exit();
    }

    $dtValidade = '';
    $telefone = '';
    $descricao = '';
    $motivo = '';
    $idPaciente = $_GET['id'];
    $idMedico = $_SESSION['usuario']['id'];

    if(isset($_SESSION['dados_formulario']))
    {
        $atestado = $_SESSION['dados_formulario'];
        unset($_SESSION['dados_formulario']);

        $dtValidade = htmlspecialchars($atestado['dtValidade']);
        $descricao = htmlspecialchars($atestado['descricao']);
        $motivo = htmlspecialchars($atestado['motivo']);
        $idPaciente = htmlspecialchars($atestado['idPaciente']);
        $idMedico = htmlspecialchars($atestado['idMedico']);
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../imagens/logotiposalus.png" type="image/x-icon">

    <link rel="stylesheet" href="../css/cadastro_login.css">

    <title>Emitir atestado para <?=$idPaciente?> - Sailus</title>
</head>
<body>
    <form action="../core/atestado_repositorio.php" method="POST">
        <div class="logoFormulario">
            <a href="perfil_paciente.php?id=<?=$idPaciente?>"><img src="../imagens/logosomentetexto.png" alt="Logotipo Sailus"></a>
        </div>

        <div class="inputsFormulario">
            <div>
                <label for="dtValidade">Data de validade:</label>
            </div>

            <input type="date" name="dtValidade" id="dtValidade" value="<?=$dtValidade?>" required>

            <input type="number" placeholder="ID do médico" name="idMedico" value="<?=$idMedico?>" step="1" required>

            <input type="number" placeholder="ID do paciente" name="idPaciente" value="<?=$idPaciente?>" step="1" required>

            <div>
                <label for="motivo">Motivo:</label>
            </div>

            <select name="motivo" id="motivo">
                <option value="Consulta Médica de Rotina" <?= $motivo == 'Consulta Médica de Rotina' ? 'selected' : '' ?>>Clínica Médica</option>
                <option value="Necessidade de Repouso Domiciliar" <?= $motivo == 'Necessidade de Repouso Domiciliar' ? 'selected' : '' ?>>Necessidade de Repouso Domiciliar</option>
                <option value="Afastamento Temporário por Saúde" <?= $motivo == 'Afastamento Temporário por Saúde' ? 'selected' : '' ?>>Afastamento Temporário por Saúde</option>
                <option value="Realização de Exames Laboratoriais" <?= $motivo == 'Realização de Exames Laboratoriais ' ? 'selected' : '' ?>>Realização de Exames Laboratoriais</option>
                <option value="Período de Observação Clínica" <?= $motivo == 'Período de Observação Clínica' ? 'selected' : '' ?>>Período de Observação Clínica</option>
                <option value="Acompanhamento de Filho(a) Menor" <?= $motivo == 'Acompanhamento de Filho(a) Menor' ? 'selected' : '' ?>>Acompanhamento de Filho(a) Menor</option>
                <option value="Acompanhamento de Familiar/Dependente" <?= $motivo == 'Acompanhamento de Familiar/Dependente' ? 'selected' : '' ?>>Acompanhamento de Familiar/Dependente</option>
                <option value="Reavaliação e Retorno Médico" <?= $motivo == 'Reavaliação e Retorno Médico' ? 'selected' : '' ?>>Reavaliação e Retorno Médico</option>
                <option value="Acidente de Trabalho" <?= $motivo == 'Acidente de Trabalho' ? 'selected' : '' ?>>Acidente de Trabalho</option>
                <option value="Doação Voluntária de Sangue" <?= $motivo == 'Doação Voluntária de Sangue' ? 'selected' : '' ?>>Doação Voluntária de Sangue</option>
                <option value="Atendimento de Urgência/Emergência" <?= $motivo == 'Atendimento de Urgência/Emergência' ? 'selected' : '' ?>>Atendimento de Urgência/Emergência</option>
                <option value="Avaliação para Aptidão Física/Mental" <?= $motivo == 'Avaliação para Aptidão Física/Mental' ? 'selected' : '' ?>>Avaliação para Aptidão Física/Mental</option>
                <option value="Tratamento Ambulatorial Específico" <?= $motivo == 'Tratamento Ambulatorial Específico' ? 'selected' : '' ?>>Tratamento Ambulatorial Específico</option>
                <option value="Licença Médica (Incapacidade Temporária)" <?= $motivo == 'Licença Médica (Incapacidade Temporária)' ? 'selected' : '' ?>>Licença Médica (Incapacidade Temporária)</option>
                <option value="Solicitação Exclusiva do Paciente" <?= $motivo == 'Solicitação Exclusiva do Paciente' ? 'selected' : '' ?>>Solicitação Exclusiva do Paciente</option>
            </select>
            
            <input type="text" placeholder="Descrição" name="descricao" value="<?=$descricao?>" required>
        </div>

        <div class="btnFormulario">
            <button type="submit">Emitir</button>
        </div>
    </form>

    <?php if(isset($_SESSION['mensagem_emissao'])) : ?>
        <div class="errocadastrologin-container">
            <p><?=$_SESSION['mensagem_emissao']?></p>
        </div>
    <?php
        unset($_SESSION['mensagem_emissao']);
        endif;
    ?>
</body>
</html>