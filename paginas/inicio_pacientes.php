<?php
    include_once "../core/tempo_sessao.php";
    session_start();

    include_once "../core/conexao.php";

    $lista = [];
    $temBusca = false;
    $termoBusca = "";

    if(isset($_SESSION['resultados_busca']))
    {
        $lista = $_SESSION['resultados_busca'];
        $temBusca = true;
        $termoBusca = $_SESSION['termo_busca'];

        unset($_SESSION['resultados_busca']);
        unset($_SESSION['termo_busca']);
    }
    else
    {
        $stmtBuscarTodos = $conexao->prepare("SELECT * FROM Paciente ORDER BY nome ASC");
        $stmtBuscarTodos->execute();

        $resultadosBuscarTodos = $stmtBuscarTodos->get_result();

        if($resultadosBuscarTodos->num_rows > 0)
        {       
            while($resultado = $resultadosBuscarTodos->fetch_assoc())
            {
                $lista[] = $resultado;
            }
        }

        $stmtBuscarTodos->close();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../imagens/logotiposalus.png" type="image/x-icon">

    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/rodape.css">
    <link rel="stylesheet" href="../css/inicio_pacientes.css">

    <title>Pacientes - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        include_once "../includes/menu_home.php";
    ?>

    <div class="titleList">
        <h1>Gerenciar pacientes</h1>
    </div>

    <div class="list-container">
        <form action="../core/buscar.php" method="POST">
            <input type="hidden" name="tabela" value="Paciente">
            <input type="text" name="busca" placeholder="Busque por pacientes..." required>
            <button type="submit">Buscar</button>
        </form>

        <?php if($temBusca) :?>
            <div class="verResultado-container">
                <?php if(count($lista) !== 0) :?>
                    <p>Resultado da busca por: <strong><?=htmlspecialchars($termoBusca)?></strong></strong></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
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
                                        <a href="">👁️</a>
                                        <a href="editar_paciente.php?id=<?=urlencode($paciente['id'])?>
                                            &nome=<?=urlencode($paciente['nome'])?>
                                            &email=<?=urlencode($paciente['email'])?>
                                            &telefone=<?=urlencode($paciente['telefone'])?>
                                            &dataNascimento=<?=urlencode($dataNascimento)?>
                                            &genero=<?=urlencode($paciente['genero'])?>
                                            &cpf=<?=urlencode($paciente['cpf'])?>"
                                        >
                                            📗
                                        </a>
                                        <a href="../core/paciente_repositorio.php?id=<?=$paciente['id']?>">🗑️</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="not-resultado-linha">
                                <?php if($temBusca): ?>
                                    Nenhum resultado encontrado para a busca: <?=$termoBusca?>
                                <?php else: ?>
                                    Nenhum paciente cadastrado.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
        include "../includes/rodape.php";
    ?>
</body>
</html>