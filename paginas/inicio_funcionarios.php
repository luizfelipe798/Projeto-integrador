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

    <title>Funcionários - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        $acesso_perfil = false;

        include "../includes/menu_home.php";
    ?>

    <div class="titleList">
        <h1><?=$_SESSION['usuario']['adm'] == true ? "Gerenciamento" : "Visualização" ?> de funcionários</h1>
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

                    $criterio_turno = [
                        ['id', '!=', $_SESSION['usuario']['id']],
                    ];

                    $criterio = [
                        ['tipoUsuario', '=', 'Funcionario'],
                        ['AND', 'id', '!=', $_SESSION['usuario']['id']],
                    ];

                    if(!empty($busca))
                    {
                        $criterio[] = ['AND', 'nome', 'like', "%$busca%"];
                        $temBusca = true;
                    }

                    $funcionarios = buscar(
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

                    $turno = buscar('Funcionario', ['turno'], $criterio_turno);
                ?>

                <div class="btn-adicionar-container">
                    <?php if($_SESSION['usuario']['adm'] == true): ?>
                        <a href="historico_funcionarios.php">Histórico</a>
                    <?php endif; ?>
                    
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
                            <td>Turno</td>
                        
                            <?php if($_SESSION['usuario']['adm'] == true): ?>
                                <td>Administrador</td>
                                <td>Status</td>
                                <td>Ações</td>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($funcionarios)): ?>
                            <?php foreach($funcionarios as $funcionario): ?>
                                <tr>
                                    <td><?=htmlspecialchars($funcionario['nome'])?></td>
                                    <td><?=htmlspecialchars($funcionario['email'])?></td>
                                    <td><?=htmlspecialchars($funcionario['telefone'])?></td>
                                    <td><?=htmlspecialchars($turno[array_search($funcionario, $funcionarios)]['turno'])?></td>

                                    <?php if($_SESSION['usuario']['adm'] == true): ?>
                                        <td><?=$funcionario['adm'] == 1 ? 'Sim' : 'Não' ?></td>
                                        <td><?=$funcionario['ativo'] == 1 ? 'Ativado' : 'Desativado' ?></td>
                                        <td class="acoes-td-tbl">
                                            <form class="form-btns-texto" action="../core/adm_repositorio.php" class="acoes-adm-form">
                                                <input type="hidden" name="id" value="<?=$funcionario['id']?>">
                                                <input type="hidden" name="acao" value="<?=$funcionario['adm'] == 1 ? 'Rebaixamento' : 'Promoção' ?>">
                                                <input type="hidden" name="nome" value="<?=$funcionario['nome']?>">
                                                <input type="hidden" name="tipoUser" value="Funcionario">

                                                <button class="btns-texto" type="submit"><?=$funcionario['adm'] == 1 ? 'Rebaixar' : 'Promover' ?></button>
                                            </form>

                                            <form class="form-btns-texto" action="../core/adm_repositorio.php" class="acoes-adm-form">
                                                <input type="hidden" name="id" value="<?=$funcionario['id']?>">
                                                <input type="hidden" name="acao" value="<?=$funcionario['ativo'] == 1 ? 'Desativação' : 'Ativação' ?>">
                                                <input type="hidden" name="nome" value="<?=$funcionario['nome']?>">
                                                <input type="hidden" name="tipoUser" value="Funcionario">
                                                
                                                <button class="btns-texto" type="submit"><?=$funcionario['ativo'] == 1 ? 'Desativar' : 'Ativar' ?></button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="not-resultado-linha">
                                    <?php if($temBusca == false): ?>
                                        Nenhum funcionário cadastrado.
                                    <?php else: ?>
                                        Nenhum funcionário encontrado.
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