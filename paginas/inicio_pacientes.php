<?php
    include_once "../core/tempo_sessao.php";
    session_start();

    include_once "../core/verifica_login.php";
    verificar_login();

    include_once "../core/conexao.php";
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

    <div class="global-list-container">
        <div class="list-container">
            <div class="buscar-e-adicionar-container">
                <?php
                    include '../includes/busca.php';
                ?>

                <div class="btn-adicionar-container">
                    <?php if($_SESSION['usuario']['tipoUsuario'] == "Funcionario"):?>
                        <a href="reativar_paciente.php">Reativar</a>
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

                            <?php if($_SESSION['tipo_usuario'] === "Funcionario"): ?>
                                <td>Ações</td>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($lista) > 0): ?>
                            <?php foreach($lista as $paciente):
                                $dataNascimento = date('d/m/Y', strtotime($paciente['dataNascimento']));
                            ?>
                                <tr>
                                    <td><?=htmlspecialchars($paciente['id'])?></td>
                                    <td><?=htmlspecialchars($paciente['nome'])?></td>
                                    <td><?=htmlspecialchars($paciente['email'])?></td>
                                    <td><?=htmlspecialchars($paciente['telefone'])?></td>
                                    <td><?=htmlspecialchars($dataNascimento)?></td>
                                    <td><?=htmlspecialchars($paciente['genero'])?></td>
                                    <td><?=htmlspecialchars($paciente['cpf'])?></td>

                                    <?php if($_SESSION['tipo_usuario'] === "Funcionario"): ?>
                                        <td class="acoes-td-tbl">
                                            <a href="visualizar_paciente.php?id=<?=urlencode($paciente['id'])?>">
                                                <img src="../imagens/olho_visualizar.png" alt="Botão de visualizar">
                                            </a>

                                            <a href="cadastrar_paciente.php?id=<?=urlencode($paciente['id'])?>&nome=<?=urlencode($paciente['nome'])?>&email=<?=urlencode($paciente['email'])?>&telefone=<?=urlencode($paciente['telefone'])?>&dataNascimento=<?=urlencode($dataNascimento)?>&genero=<?=urlencode($paciente['genero'])?>&cpf=<?=urlencode($paciente['cpf'])?>"
                                            >
                                                <img src="../imagens/caderno_editar.png" alt="Botão de editar"">
                                            </a>
                                            
                                            <form action="../core/paciente_repositorio.php" method="POST">
                                                <input type="hidden" name="acao" value="Exclusão">
                                                <input type="hidden" name="id" value="<?=$paciente['id']?>">
                                                <input type="hidden" name="nome" value="<?=$paciente['nome']?>">

                                                <button type="submit">
                                                    <img src="../imagens/lixeira_excluir.png" alt="Botão de excluir">
                                                </button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="not-resultado-linha">
                                    <?php if(!$temBusca): ?>
                                        Nenhum paciente cadastrado
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