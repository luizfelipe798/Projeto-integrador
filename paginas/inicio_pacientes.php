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
        include_once "../core/tempo_sessao.php";
        include_once "../includes/menu_home.php";
        include_once "../core/conexao.php";
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

        <?php if(isset($_SESSION['termo_busca'])) :?>
            <div class="verResultado-container">
                <?php if($_SESSION['resultados_busca'] === []) :?>
                    <p>Nenhum resultado encontrado para: <?=$_SESSION['termo_busca']?></p>
                <?php else: ?>
                    <p>Resultado da busca por: <?=$_SESSION['termo_busca']?></p>
                <?php endif; ?>
            </div>
        <?php
            unset($_SESSION['resultados_busca']);
            unset($_SESSION['termo_busca']);
            endif
            ?>
        
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
                    <tr>
                        <td>1</td>
                        <td>Luiz Felipe Vignoto</td>
                        <td>luizvignoto@gmail.com</td>
                        <td>(18) 99695-2348</td>
                        <td>29/03/2008</td>
                        <td>Masculino</td>
                        <td>111.111.111-11</td>
                    
                        <?php if($_SESSION['tipo_usuario'] === "Funcionario"): ?>
                            <td class="acoes-td-tbl">
                                <a href="../core/paciente_repositorio.php">👁️</a>
                                <a href="../core/paciente_repositorio.php">📗</a>
                                <a href="../core/paciente_repositorio.php">🗑️</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php
        $paginaAtual = "notIndex";
        include "../includes/rodape.php";
    ?>
</body>
</html>