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

    <title>Histórico de administrações com médicos - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        $acesso_perfil = false;

        include_once "../includes/menu_home.php";
    ?>

    <div class="titleList">
        <h1>Histórico de ações de administradores com médicos</h1>
    </div>

    <div class="global-list-container">
        <div class="list-container">
            <div class="buscar-e-adicionar-container">
                <?php
                    include_once '../includes/busca.php';
                ?>

                <div class="btn-adicionar-container">
                    <a href="inicio_medicos.php">Voltar</a>
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
                    $temBusca = true;

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
                }

                $historicos = buscar('Administracoes', ['*'], $criterio, 'dtAcao DESC');
            ?>
            
            <div class="tbl-pacientes-container">
                <table>
                    <thead>
                        <tr>
                            <td>Data da ação</td>
                            <td>Administrador</td>
                            <td>Médico</td>
                            <td>Tipo da ação</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($historicos)): ?>
                            <?php foreach($historicos as $historico): ?>
                                <?php
                                    $dtAcao = date_create($historico['dtAcao']);
                                    $dtAcao = date_format($dtAcao, 'd/m/Y H:i:s');

                                    $criterio_buscar_medico = [
                                        ['id', '=', $historico['idUsuario']],
                                    ];

                                    $medico = buscar('Usuario', ['nome', 'tipoUsuario'], $criterio_buscar_medico);

                                    if($medico[0]['tipoUsuario'] == 'Medico')
                                    {
                                        $tipo_medico = true;

                                        $criterio_buscar_adm = [
                                            ['id', '=', $historico['idAdm']]
                                        ];

                                        $adm = buscar('Usuario', ['nome'], $criterio_buscar_adm);
                                    }
                                    else
                                    {
                                        $tipo_medico = false;
                                    }
                                ?>
                                
                                <?php if($tipo_medico == true): ?>
                                    <tr>
                                        <td><?=htmlspecialchars($dtAcao)?></td>
                                        <td><?=htmlspecialchars($adm[0]['nome'])?></td>
                                        <td><?=htmlspecialchars($medico[0]['nome'])?></td>
                                        <td><?=htmlspecialchars($historico['tipoAcao'])?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="not-resultado-linha">
                                    <?php if($temBusca == false): ?>
                                        Nenhum histórico de administração registrado.
                                    <?php else: ?>
                                        Nenhum histórico de administração encontrado.
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