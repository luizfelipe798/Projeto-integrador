<?php
    session_start();

    require_once "../core/verifica_login.php";
    verificar_login();

    require_once "../core/conexao.php";
    require_once "../core/sql.php";
    require_once "../core/mysql.php";
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

    <title>Histórico de consultas - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        $acesso_perfil = false;

        include_once "../includes/menu_home.php";
    ?>

    <div class="titleList">
        <h1>Histórico de ações entre funcionários e consultas</h1>
    </div>

    <div class="global-list-container">
        <div class="list-container">
            <div class="buscar-e-adicionar-container">
                <?php
                    include_once '../includes/busca.php';
                ?>

                <div class="btn-adicionar-container">
                    <a href="inicio_consultas.php">Voltar</a>
                </div>
            </div>

            <?php
                foreach($_GET as $indice => $dado)
                {
                    $$indice = htmlspecialchars($dado);
                }

                $temBusca = false;
                $criterio = [];

                if(!empty($busca))
                {
                    if(DateTime::createFromFormat('d/m/Y', $busca) !== false) 
                    {
                        $data_string_antiga =  $busca;
                        $timestamp = strtotime(str_replace('/', '-', $data_string_antiga));
                        $data_brasileira = date("Y-m-d", $timestamp);
                       
                        $criterio = [
                            ['dtAcao', 'LIKE', "%$data_brasileira%"]
                        ];
                    }
                    else if(DateTime::createFromFormat('H:i:s', $busca) !== false) 
                    {
                      $criterio = [
                            ['dtAcao', 'LIKE', "%$busca%"]
                      ];
                    }
                    else
                    {
                        $criterio = [
                            ['tipoAcao', 'LIKE', "%$busca%"],
                        ];
                    }

                    $temBusca = true;
                }

                $historicos = buscar('HistFuncConsulta', ['*'], $criterio, 'dtAcao DESC');
            ?>
            
            <div class="tbl-pacientes-container">
                <table>
                    <thead>
                        <tr>
                            <td>Data da ação</td>
                            <td>Data da consulta</td>
                            <td>Funcionário</td>
                            <td>Médico</td>
                            <td>Paciente</td>
                            <td>Tipo da ação</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($historicos)): ?>
                            <?php foreach($historicos as $historico): ?>
                                <?php
                                    $dtAcao = date_create($historico['dtAcao']);
                                    $dtAcao = date_format($dtAcao, 'd/m/Y H:i:s');

                                    $criterio_buscar_funcionario = [
                                        ['id', '=', $historico['idFuncionario']]
                                    ];

                                    $criterio_buscar_consulta = [
                                        ['id', '=', $historico['idConsulta']]
                                    ];

                                    $funcionario = buscar('Usuario', ['nome'], $criterio_buscar_funcionario);
                                    $consulta = buscar('Consulta', ['*'], $criterio_buscar_consulta);

                                    $criterio_buscar_medico = [
                                        ['id', '=', $consulta[0]['idMedico']]
                                    ];

                                    $criterio_buscar_paciente = [
                                        ['id', '=', $consulta[0]['idPaciente']]
                                    ];

                                    $medico = buscar('Usuario', ['nome'], $criterio_buscar_medico);
                                    $paciente = buscar('Paciente', ['nome'], $criterio_buscar_paciente);

                                    $horario = date_create($consulta[0]['horario']);
                                    $horario = date_format($horario, 'd/m/Y H:i:s');
                                ?>

                                <tr>
                                    <td><?=htmlspecialchars($dtAcao)?></td>
                                    <td><?=htmlspecialchars($horario)?></td>
                                    <td><?=htmlspecialchars($funcionario[0]['nome'])?></td>
                                    <td><?=htmlspecialchars($medico[0]['nome'])?></td>
                                    <td><?=htmlspecialchars($paciente[0]['nome'])?></td>
                                    <td><?=htmlspecialchars($historico['tipoAcao'])?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="not-resultado-linha">
                                    <?php if($temBusca == false): ?>
                                        Nenhuma ação com consulta registrada.
                                    <?php else: ?>
                                        Nenhuma ação com consulta encontrada.
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