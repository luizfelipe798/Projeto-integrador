<?php
    session_start();

    require_once "../core/verifica_login.php";
    verificar_login();

    require_once "../core/conexao.php";
    require_once "../core/sql.php";
    require_once '../core/mysql.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../imagens/logotiposalus.png" type="image/x-icon">

    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/rodape.css">
    <link rel="stylesheet" href="../css/gerenciamento_geral.css">

    <title>Consultas - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        $acesso_perfil = false;

        include "../includes/menu_home.php";
    ?>

    <div class="titleList">
        <h1>Gerenciamento de consultas</h1>
    </div>

    <?php if(isset($_SESSION['mensagem_gerenciamento'])): ?>
        <div class="rsltd-acoes-container">
            <p><?=$_SESSION['mensagem_gerenciamento']?></p>
        </div>
    <?php
        unset($_SESSION['mensagem_gerenciamento']);
        endif;
    ?>

    <div class="global-list-container">
        <div class="list-container">
            <div class="buscar-e-adicionar-container">
                <?php
                    include '../includes/busca.php';
                ?>

                <?php
                    foreach($_GET as $indice => $dado)
                    {
                        $$indice = htmlspecialchars($dado);
                    }

                    $temBusca = false;

                    $criterio = [
                        ['stattus', '!=', 'Concluída']
                    ];

                    if(!empty($busca))
                    {
                        $temBusca = true;

                        if(DateTime::createFromFormat('d/m/Y', $busca) !== false)
                        {
                            $data_string_antiga =  $busca;
                            $timestamp = strtotime(str_replace('/', '-', $data_string_antiga));
                            $data_brasileira = date("Y-m-d", $timestamp);
                        
                            $criterio[] = ['AND', 'horario', 'LIKE', "%$data_brasileira%"];
                        }
                        else if(DateTime::createFromFormat('H:i:s', $busca) !== false) 
                        {
                            $criterio[] = ['AND', 'horario', 'LIKE', "%$busca%"];
                        }
                        else
                        {
                            $criterio[] = ['AND', 'especialidade', 'LIKE', "%$busca%"];
                        }
                    }

                    $consultas = buscar('Consulta', ['*'], $criterio, 'horario ASC');
                ?>

                <div class="btn-adicionar-container">
                    <?php if($_SESSION['usuario']['tipoUsuario'] == "Funcionario"):?>
                        <a href="agendar_editar_consultas.php">Agendar</a>
                    <?php endif; ?>
                        <a href="consultas_concluidas.php">Concluídas</a>
                        <a href="historico_consultas.php">Histórico</a>
                        <a href="home_usuario.php">Voltar</a>
                </div>
            </div>

            <div class="tbl-pacientes-container">
                <table>
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Data</td>
                            <td>Paciente</td>
                            <td>Médico</td>
                            <td>Valor</td>
                            <td>Especialidade</td>

                            <?php if($_SESSION['usuario']['tipoUsuario'] == "Funcionario"):?>
                                <td>Ações</td>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($consultas)): ?>
                            <?php foreach($consultas as $consulta): ?>
                                <?php
                                    $data = date_create($consulta['horario']);
                                    $data = date_format($data, 'd/m/Y H:i:s');

                                    $criterio_buscar_paciente = [
                                        ['id', '=', $consulta['idPaciente']],
                                    ];

                                    $criterio_buscar_medico = [
                                        ['id', '=', $consulta['idMedico']],
                                    ];

                                    $paciente = buscar('Paciente', ['nome'], $criterio_buscar_paciente);
                                    $medico = buscar('Usuario', ['nome'], $criterio_buscar_medico);
                                ?>

                                <tr>
                                    <td><?=htmlspecialchars($consulta['id'])?></td>
                                    <td><?=htmlspecialchars($data)?></td>
                                    <td><?=htmlspecialchars($paciente[0]['nome'])?></td>
                                    <td>Dr. <?=htmlspecialchars($medico[0]['nome'])?></td>
                                    <td>R$ <?=number_format(htmlspecialchars($consulta['valor']), 2, ',', '.')?></td>
                                    <td><?=htmlspecialchars($consulta['especialidade'])?></td>

                                    <?php if($_SESSION['usuario']['tipoUsuario'] == "Funcionario"):?>
                                        <td class="acoes-td-tbl">
                                            <a href="agendar_editar_consultas.php?id=<?=urlencode($consulta['id'])?>">
                                                <img src="../imagens/caderno_editar.png">
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="not-resultado-linha">
                                    <?php if($temBusca == false): ?>
                                        Nenhuma consulta agendada.
                                    <?php else: ?>
                                        Nenhuma consulta encontrada.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
        include "../includes/rodape.php";
    ?>
</body>
</html>