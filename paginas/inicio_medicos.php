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

    <title>Médicos - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        $acesso_perfil = false;

        include "../includes/menu_home.php";
    ?>

    <div class="titleList">
        <h1><?=$_SESSION['usuario']['adm'] == true ? "Gerenciamento" : "Visualização" ?> de médicos</h1>
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
                        ['tipoUsuario', '=', 'Medico'],
                        ['AND', 'id', '!=', $_SESSION['usuario']['id']],
                    ];

                    if(!empty($busca))
                    {
                        $criterio[] = ['AND', 'nome', 'like', "%$busca%"];
                        $temBusca = true;
                    }

                    $medicos = buscar(
                            'Usuario',
                            [
                                'id',
                                'nome',
                                'email',
                                'telefone',
                                'adm',
                                'ativo'
                            ],
                            $criterio,
                            'nome ASC'
                        );

                        $criterio_medico = [
                            ['id', '!=', $_SESSION['usuario']['id']]
                        ];

                    $especifico_medico = buscar('Medico', ['especialidade', 'plantonista'], $criterio_medico);
                ?>

                <div class="btn-adicionar-container">
                        <a href="home_usuario.php">Voltar</a>
                </div>
            </div>

            <div class="tbl-pacientes-container">
                <table>
                    <thead>
                        <tr>
                            <td>Nome</td>
                            <td>E-mail</td>
                            <td>Telefone</td>
                            <td>Especialidade</td>
                            <td>Plantonista</td>
                        
                            <?php if($_SESSION['usuario']['adm'] == true): ?>
                                <td>Administrador</td>
                                <td>Status</td>
                                <td>Ações</td>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($medicos)): ?>
                            <?php foreach($medicos as $medico): ?>
                                <tr>
                                    <td><?=htmlspecialchars($medico['nome'])?></td>
                                    <td><?=htmlspecialchars($medico['email'])?></td>
                                    <td><?=htmlspecialchars($medico['telefone'])?></td>
                                    <td><?=htmlspecialchars($especifico_medico[array_search($medico, $medicos)]['especialidade'])?></td>
                                    <td><?=htmlspecialchars($especifico_medico[array_search($medico, $medicos)]['plantonista'])?></td>

                                    <?php if($_SESSION['usuario']['adm'] == true): ?>
                                        <td><?=$medico['adm'] == 1 ? 'Sim' : 'Não' ?></td>
                                        <td><?=$medico['ativo'] == 1 ? 'Ativado' : 'Desativado' ?></td>
                                        <td class="acoes-td-tbl">
                                            <form class="form-btns-texto" action="../core/adm_repositorio.php" class="acoes-adm-form">
                                                <input type="hidden" name="id" value="<?=$medico['id']?>">
                                                <input type="hidden" name="acao" value="<?=$medico['adm'] == 1 ? 'Rebaixamento' : 'Promoção' ?>">
                                                <input type="hidden" name="nome" value="<?=$medico['nome']?>">
                                                <input type="hidden" name="tipoUser" value="Medico">

                                                <button class="btns-texto" type="submit"><?=$medico['adm'] == 1 ? 'Rebaixar' : 'Promover' ?></button>
                                            </form>

                                            <form class="form-btns-texto" action="../core/adm_repositorio.php" class="acoes-adm-form">
                                                <input type="hidden" name="id" value="<?=$medico['id']?>">
                                                <input type="hidden" name="acao" value="<?=$medico['ativo'] == 1 ? 'Desativação' : 'Ativação' ?>">
                                                <input type="hidden" name="nome" value="<?=$medico['nome']?>">
                                                <input type="hidden" name="tipoUser" value="Medico">
                                                
                                                <button class="btns-texto" type="submit"><?=$medico['ativo'] == 1 ? 'Desativar' : 'Ativar' ?></button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="not-resultado-linha">
                                    <?php if($temBusca == false): ?>
                                        Nenhum médico cadastrado.
                                    <?php else: ?>
                                        Nenhum médico encontrado.
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