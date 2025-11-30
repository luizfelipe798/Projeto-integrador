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

    <link rel="stylesheet" href="../css/cadastro_login.css">

    <title> Paciente - Sailus</title>
</head>
<body>
    <div class="titulo-dados-acoes-container">
        <h1>Ações no sistema</h1>
    </div>

    <div class="tipo-acoes-container">
        <h3>Pacientes</h3>
    </div>

    <div class="buscar-e-adicionar-container">
        <?php
            include '../includes/busca.php';
        ?>
    </div>

    <?php
        foreach($_GET as $indice => $dado)
        {
            $$indice = htmlspecialchars($dado);
        }

        $temBusca = false;

        $criterio = [
            ['idFuncionario', '=', $_SESSION['usuario']['id']]
        ];

        if(!empty($busca))
        {
            $temBusca = true;

            if(DateTime::createFromFormat('d/m/Y', $busca) !== false)
            {
                $data_string_antiga =  $busca;
                $timestamp = strtotime(str_replace('/', '-', $data_string_antiga));
                $data_brasileira = date("Y-m-d", $timestamp);
                
                $criterio[] = [
                    ['dtAcao', 'LIKE', "%$data_brasileira%"]
                ];
            }
            else if(DateTime::createFromFormat('H:i:s', $busca) !== false) 
            {                       
                $criterio[] = [
                    ['dtAcao', 'LIKE', "%$busca%"]
                ];
            }
            else
            {               
                $criterio[] = [                       
                    ['tipoAcao', 'LIKE', "%$busca%"]
                ];
            }
        }

        $campos_historico = ['dtAcao', 'idPaciente', 'tipoAcao'];

        $acoes = buscar('HistFuncPaciente', $campos_historico, $criterio, 'dtAcao DESC');
    ?>

    <div class="tbl-acoes-container">
        <table>
            <thead>
                <tr>
                    <td>Data da ação</td>
                    <td>Paciente relacionado</td>
                    <td>Tipo da ação</td>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($acoes)):?>
                    <?php foreach($acoes as $acao):?>
                        <?php
                            $dtAcao = date_create($acao['dtAcao']);
                            $dtAcao = date_format($dtAcao, 'd/m/Y H:i:s');

                            $criterio_buscar_paciente = [
                                ['id', '=', $acao['idPaciente']]
                            ];

                            $paciente = buscar('Paciente', ['nome'], $criterio_buscar_paciente);
                        ?>
                        <tr>
                            <td><?=$dtAcao?></td>
                            <td><?=$paciente[0]['nome']?></td>
                            <td><?=$acao['tipoAcao']?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="not-resultado-linha">
                            <?php if($temBusca == false): ?>
                                Nenhuma ação registrada.
                            <?php else: ?>
                                Nenhum ação encontrada.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>