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

    <title>Histórico de Pacientes - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        $acesso_perfil = false;

        include_once "../includes/menu_home.php";
    ?>

    <div class="titleList">
        <h1>Histórico de pacientes</h1>
    </div>

    <div class="global-list-container">
        <div class="list-container">
            <div class="buscar-e-adicionar-container">
                <?php
                    include_once '../includes/busca.php';
                ?>

                <div class="btn-adicionar-container">
                    <a href="inicio_pacientes.php">Voltar</a>
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

                $campos_historico = ['tipoAcao', 'dtAcao', 'idFuncionario', 'idPaciente'];

                $historico = buscar('HistFuncPaciente', $campos_historico, $criterio, 'dtAcao DESC');
            ?>
            
            <div class="tbl-pacientes-container">
                <table>
                    <thead>
                        <tr>
                            <td>Data da ação</td>
                            <td>Funcionário relacionado</td>
                            <td>Paciente relacionado</td>
                            <td>Tipo da ação</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($historico)): ?>
                            <?php foreach($historico as $historico): ?>
                                <?php
                                    $dtAcao = date_create($historico['dtAcao']);
                                    $dtAcao = date_format($dtAcao, 'd/m/Y H:i:s');

                                    $criterio_buscar_funcionario = [
                                        ['id', '=', $historico['idFuncionario']]
                                    ];

                                    $criterio_buscar_paciente = [
                                        ['id', '=', $historico['idPaciente']]
                                    ];

                                    $funcionario = buscar('Usuario', ['nome'], $criterio_buscar_funcionario);
                                    $paciente = buscar('Paciente', ['nome'], $criterio_buscar_paciente);
                                ?>

                                <tr>
                                    <td><?=htmlspecialchars($dtAcao)?></td>
                                    <td><?=htmlspecialchars($funcionario[0]['nome'])?></td>
                                    <td><?=htmlspecialchars($paciente[0]['nome'])?></td>
                                    <td><?=htmlspecialchars($historico['tipoAcao'])?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="not-resultado-linha">
                                    <?php if($temBusca == false): ?>
                                        Nenhum histórico registrado.
                                    <?php else: ?>
                                        Nenhum histórico encontrado.
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