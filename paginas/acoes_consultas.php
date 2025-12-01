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

    <title><?=$_SESSION['usuario']['tipoUsuario'] == 'Funcionario' ? 'Ações com consultas' : 'Consultas agendadas' ?> - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        $acesso_perfil = false;

        include_once "../includes/menu_home.php";
    ?>

    <div class="titleList">
        <h1><?=$_SESSION['usuario']['tipoUsuario'] == 'Funcionario' ? 'Suas ações com consultas' : 'Suas consultas agendadas' ?></h1>
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
                    include_once '../includes/busca.php';
                ?>
    
                <div class="btn-adicionar-container">
                    <?php if($_SESSION['usuario']['tipoUsuario'] == 'Medico'): ?>
                        <a href="consultas_concluidas.php?medico">Concluídas</a>
                    <?php endif; ?>
                    <a href="perfil_usuario.php">Voltar</a>
                </div>
            </div>

            <?php
                $temBusca = false;

                foreach($_GET as $indice => $dado)
                {
                    $$indice = htmlspecialchars($dado);
                }
                
                if($_SESSION['usuario']['tipoUsuario'] == 'Medico')
                {
                    $criterio = [
                        ['idMedico', '=', $_SESSION['usuario']['id']],
                        ['AND', 'stattus', '!=', 'Concluída']
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

                    $dados = buscar('Consulta', ['*'], $criterio, 'horario ASC');
                }
                else
                {
                    $criterio = [
                        ['idFuncionario', '=', $_SESSION['usuario']['id']],
                    ];

                    if(!empty($busca))
                    {
                        if(DateTime::createFromFormat('d/m/Y', $busca) !== false) 
                        {
                            $data_string_antiga =  $busca;
                            $timestamp = strtotime(str_replace('/', '-', $data_string_antiga));
                            $data_brasileira = date("Y-m-d", $timestamp);
                            
                            $criterio[] = ['AND', 'dtAcao', 'LIKE', "%$data_brasileira%"];
                        }
                        else if(DateTime::createFromFormat('H:i:s', $busca) !== false) 
                        {
                            $criterio[] = ['AND', 'dtAcao', 'LIKE', "%$busca%"];
                        }
                        else
                        {
                            $criterio[] = ['AND', 'tipoAcao', 'LIKE', "%$busca%"];
                        }

                        $temBusca = true;
                    }

                    $criterio = [['idFuncionario', '=', $_SESSION['usuario']['id']]];

                    $dados = buscar('HistFuncConsulta', ['*'], $criterio, 'dtAcao DESC');
                }
            ?>
            
            <div class="tbl-pacientes-container">
                <table>
                    <thead>
                        <tr>
                            <td>Data</td>
                            <td>Paciente</td>
                            <td>Valor</td>
                            <td>Especialidade</td>

                            <?php if($_SESSION['usuario']['tipoUsuario'] == 'Medico'): ?>
                                <td>Ações</td>
                            <?php else: ?>
                                <td>Tipo da ação</td>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($dados)): ?>
                            <?php foreach($dados as $dado): ?>
                                <?php
                                    if($_SESSION['usuario']['tipoUsuario'] == 'Medico')
                                    {
                                        $horario = date_create($dado['horario']);
                                        $horario = date_format($horario, 'd/m/Y H:i:s');

                                        $criterio_buscar_paciente = [
                                            ['id', '=', $dado['idPaciente']]
                                        ];

                                        $paciente = buscar('Paciente', ['nome'], $criterio_buscar_paciente);
                                    }
                                    else
                                    {
                                        $dtAcao = date_create($dado['dtAcao']);
                                        $dtAcao = date_format($dtAcao, 'd/m/Y H:i:s');

                                        $criterio_buscar_consulta = [
                                            ['id', '=', $dado['idConsulta']]
                                        ];

                                        $consulta = buscar('Consulta', ['*'], $criterio_buscar_consulta);

                                        $criterio_buscar_paciente = [
                                            ['id', '=', $consulta[0]['idPaciente']]
                                        ];

                                        $paciente = buscar('Paciente', ['nome'], $criterio_buscar_paciente);
                                    }
                                ?>

                                <tr>
                                    <td><?=htmlspecialchars($horario ?? $dtAcao)?></td>
                                    <td><?=htmlspecialchars($paciente[0]['nome'])?></td>
                                    <td>R$ <?=number_format(htmlspecialchars($dado['valor'] ?? $consulta[0]['valor']), 2, ',', '.')?></td>
                                    <td><?=htmlspecialchars($dado['especialidade'] ?? $consulta[0]['especialidade'])?></td>

                                    <?php if($_SESSION['usuario']['tipoUsuario'] == 'Medico'): ?>
                                        <td class="acoes-td-tbl">
                                            <form action="../core/consultas_repositorio.php" method="POST" class="form-btns-texto">
                                                <input type="hidden" name="id" value="<?=htmlspecialchars($dado['id'])?>">
                                                <input type="hidden" name="acao" value="Concluir">

                                                <button type="submit" class="btns-texto">Concluir</button>
                                            </form>
                                        </td>
                                    <?php else: ?>
                                        <td><?=htmlspecialchars($dado['tipoAcao'])?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="not-resultado-linha">
                                    <?php if($temBusca == false): ?>
                                        <?=$_SESSION['usuario']['tipoUsuario'] == 'Funcionario' ? 'Nenhum histórico de consultas registrado.' : 'Nenhuma consulta agendada.' ?>
                                    <?php else: ?>
                                        <?=$_SESSION['usuario']['tipoUsuario'] == 'Funcionario' ? 'Nenhum histórico de consultas encontrado.' : 'Nenhuma consulta encontrada.' ?>
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