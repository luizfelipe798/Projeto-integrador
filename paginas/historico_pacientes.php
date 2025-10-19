<?php
    include_once "../core/tempo_sessao.php";
    session_start();

    include_once "../core/verifica_login.php";
    verificar_login();

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
        $stmtBuscarTodos = $conexao->prepare("SELECT * FROM HistFuncPaciente ORDER BY dtAcao DESC");
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
    <link rel="stylesheet" href="../css/gerenciamento_geral.css">

    <title>Histórico de Pacientes - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        include_once "../includes/menu_home.php";
    ?>

    <div class="titleList">
        <h1>Histórico de pacientes</h1>
    </div>

    <div class="global-list-container">
        <div class="list-container">
            <div class="buscar-e-adicionar-container">
                <form action="../core/buscar.php" method="POST">
                    <input type="hidden" name="tabela" value="HistFuncPaciente">
                    <input type="text" name="busca" placeholder="Busque pelo histórico..." required>
                    <button type="submit">Buscar</button>
                </form>

                <div class="btn-adicionar-container">
                    <a href="inicio_pacientes.php">Voltar</a>
                </div>
            </div>

            <?php if($temBusca): ?>
                <div class="verResultado-container">
                    <?php if(count($lista) > 0): ?>
                        <p><?=count($lista)?> histórico(s) encontrado(s) na busca por <strong><?=htmlspecialchars($termoBusca)?></strong></p>
                    <?php else: ?>
                        <p>Nenhum histórico(s) encontrado(s) na busca por <strong><?=htmlspecialchars($termoBusca)?></strong></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="tbl-pacientes-container">
                <table>
                    <thead>
                        <tr>
                            <td>Data da ação</td>
                            <td>ID</td>
                            <td>Tipo de ação</td>
                            <td>Funcionário relacionado</td>
                            <td>Paciente relacionado</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($lista) > 0): ?>
                            <?php foreach($lista as $historico):
                                $dataAcao = date('d/m/Y à\s H:i:s', strtotime($historico['dtAcao']));

                                $tipoUsuario = "Funcionario";
                                $stmtFuncionario = $conexao->prepare("SELECT nome FROM Usuario
                                                                      WHERE id = ?
                                                                      AND tipo = ?");
                                $stmtFuncionario->bind_param("is", $historico['idFuncionario'], $tipoUsuario);
                                $stmtFuncionario->execute();
                                
                                $resultadoFuncionario = $stmtFuncionario->get_result();
                                $nomeFuncionario = "Não encontrado";

                                if($resultadoFuncionario->num_rows == 1)
                                {
                                    $funcionario = $resultadoFuncionario->fetch_assoc();
                                    $nomeFuncionario = htmlspecialchars($funcionario['nome']);
                                }

                                $stmtFuncionario->close();

                                $stmtPaciente = $conexao->prepare("SELECT nome FROM Paciente
                                                                      WHERE id = ?");
                                $stmtPaciente->bind_param("i", $historico['idPaciente']);
                                $stmtPaciente->execute();
                                
                                $resultadoPaciente = $stmtPaciente->get_result();
                                $nomePaciente = "Não encontrado";

                                if($resultadoPaciente->num_rows == 1)
                                {
                                    $paciente = $resultadoPaciente->fetch_assoc();
                                    $nomePaciente = htmlspecialchars($paciente['nome']);
                                }

                                $stmtPaciente->close();
                            ?>
                                <tr>
                                    <td><?=htmlspecialchars($dataAcao)?></td>
                                    <td><?=htmlspecialchars($historico['id'])?></td>
                                    <td><?=htmlspecialchars($historico['tipoAcao'])?></td>
                                    <td><?=$nomeFuncionario?></td>
                                    <td><?=$nomePaciente?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="not-resultado-linha">
                                    <?php if(!$temBusca): ?>
                                        Nenhum histórico registrado
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