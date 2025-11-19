<?php
    require_once "../core/tempo_sessao.php";
    session_start();

    require_once "../core/verifica_login.php";
    verificar_login();

    require_once "../core/conexao.php";
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

    <title>Pacientes - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        include "../includes/menu_home.php";
    ?>

    <div class="titleList">
        <h1>Gerenciar pacientes</h1>
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
                    require_once '../core/sql.php';
                    require_once '../core/mysql.php';

                    //$busca = '';

                    foreach($_GET as $indice => $dado)
                    {
                        $$indice = htmlspecialchars($dado);
                    }

                    $criterio = [
                        ['excluido', '=', 0],
                    ];

                    if(!empty($busca))
                    {
                        $criterio[] = ['AND', 'nome', 'like', "%$busca%"];
                    }

                    $pacientes = buscar(
                            'Paciente',
                            [
                                'id',
                                'nome',
                                'email',
                                'telefone',
                                'dataNascimento',
                                'cpf',
                                'genero'
                            ],
                            $criterio,
                            'id'
                        );
                ?>

                <div class="btn-adicionar-container">
                    <?php if($_SESSION['usuario']['tipoUsuario'] == "Funcionario"):?>
                        <a href="cadastrar_paciente.php">Adicionar</a>
                    <?php endif; ?>
                        <a href="historico_pacientes.php">Histórico</a>
                </div>
            </div>

            <div class="tbl-pacientes-container">
                <table>
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Nome</td>
                            <td>E-mail</td>
                            <td>Telefone</td>
                            <td>Data de Nascimento</td>
                            <td>Gênero</td>
                            <td>CPF</td>

                            <?php if($_SESSION['usuario']['tipoUsuario'] == "Funcionario"):?>
                                <td>Ações</td>
                            <?php endif; ?>

                            <?php
                                require_once '../core/sql.php';
                                require_once '../core/mysql.php';
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pacientes as $paciente):?>
                            <?php
                                $dataNascimento = date_create($paciente['dataNascimento']);
                                $dataNascimento = date_format($dataNascimento, 'd/m/Y');
                            ?>

                            <tr>
                                <td><?=$paciente['id']?></td>
                                <td><?=$paciente['nome']?></td>
                                <td><?=$paciente['email']?></td>
                                <td><?=$paciente['telefone']?></td>
                                <td><?=$dataNascimento?></td>
                                <td><?=$paciente['genero']?></td>
                                <td><?=$paciente['cpf']?></td>

                                <?php if($_SESSION['usuario']['tipoUsuario'] == "Funcionario"):?>
                                    <td class="acoes-td-tbl">
                                        <a href="visualizar_paciente.php?id=<?=urlencode($paciente['id'])?>">
                                            <img src="../imagens/olho_visualizar.png">
                                        </a>

                                        <a href="cadastrar_paciente.php?id=<?=urlencode($paciente['id'])?>">
                                            <img src="../imagens/caderno_editar.png">
                                        </a>

                                        <form action="../core/paciente_repositorio.php" method="POST">
                                            <input type="hidden" name="acao" value="Exclusão">
                                            <input type="hidden" name="id" value="<?=$paciente['id']?>">

                                            <button type="submit">
                                                <img src="../imagens/lixeira_excluir.png">
                                            </button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
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