<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../imagens/logotiposalus.png" type="image/x-icon">

    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/rodape.css">

    <title>Pacientes - Sailus</title>
</head>
<body>
    <?php
        include "../includes/menu_home.php";
        include_once "../core/conexao.php";
    ?>

    <div class="titleList">
        <h1>Gerenciar pacientes</h1>
    </div>

    <div class="list-container">
        <form action="" method="">
            <input type="hidden" name="acao" value="buscar">
            <input type="text" name="busca" placeholder="Busque por pacientes...">
            <button type="submit">Buscar</button>
        </form>
        <div class="viewLista-container">
        </div>
    </div>

    <?php
        include "../includes/rodape.php";
    ?>
</body>
</html>