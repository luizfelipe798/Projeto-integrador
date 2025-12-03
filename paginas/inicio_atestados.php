<?php
    session_start();

    require_once "../core/verifica_login.php";
    verificar_login();

    require_once "../core/conexao.php";
    require_once '../core/sql.php';
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

    <title>Atestados - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        $acesso_perfil = false;

        include "../includes/menu_home.php";
    ?>

    <div class="titleList">
        <h1>Gerenciamento de atestados</h1>
    </div>

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
                    $criterio = [];

                    if(!empty($busca))
                    {
                        $temBusca = true;

                        if(DateTime::createFromFormat('d/m/Y', $busca) !== false) 
                        {
                            $data_string_antiga =  $busca;
                            $timestamp = strtotime(str_replace('/', '-', $data_string_antiga));
                            $data_brasileira = date("Y-m-d", $timestamp);
                        
                            $criterio = [
                                ['dtEmissao', 'LIKE', "%$data_brasileira%"],
                                ['OR', 'dtValidade', 'LIKE', "%$data_brasileira%"],
                            ];
                        }
                        else
                        {
                            $criterio = [
                                ['motivo', 'LIKE', "%$busca%"],
                            ];
                        }
                    }

                    $atestados = buscar('Atestado', ['*'], $criterio);
                ?>

                <div class="btn-adicionar-container">
                    <a href="home_usuario.php">Voltar</a>
                </div>
            </div>

            <div class="tbl-pacientes-container">
                <table>
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Data de emissão</td>
                            <td>Médico</td>
                            <td>Paciente</td>
                            <td>Data de validade</td>
                            <td>Descrição</td>
                            <td>Motivo</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($atestados)): ?>
                            <?php foreach($atestados as $atestado): ?>
                                <?php
                                    $dataEmissao = date_create($atestado['dtEmissao']);
                                    $dataEmissao = date_format($dataEmissao, 'd/m/Y');

                                    $dataValidade = date_create($atestado['dtValidade']);
                                    $dataValidade = date_format($dataValidade, 'd/m/Y');

                                    $medico = buscar('Usuario', ['nome'], [['id', '=', $atestado['idMedico']]]);

                                    $paciente = buscar('Paciente', ['nome'], [['id', '=', $atestado['idPaciente']]]);
                                ?>

                                <tr>
                                    <td><?=htmlspecialchars($atestado['id'])?></td>
                                    <td><?=htmlspecialchars($dataEmissao)?></td>
                                    <td><?=htmlspecialchars($medico[0]['nome'])?></td>
                                    <td><?=htmlspecialchars($paciente[0]['nome'])?></td>
                                    <td><?=htmlspecialchars($dataValidade)?></td>
                                    <td><?=htmlspecialchars($atestado['descricao'])?></td>
                                    <td><?=htmlspecialchars($atestado['motivo'])?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="not-resultado-linha">
                                    <?php if($temBusca == false): ?>
                                        Nenhum atestado emitido.
                                    <?php else: ?>
                                        Nenhum atestado encontrado.
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