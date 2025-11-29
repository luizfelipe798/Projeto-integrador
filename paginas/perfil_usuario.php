<?php
    require_once "../core/tempo_sessao.php";
    session_start();

    require_once "../core/verifica_login.php";
    verificar_login();

    require_once "../core/conexao.php";
    require_once "../core/sql.php";
    require_once "../core/mysql.php";

    function verificarEdicao() : void
    {
        if(!isset($_GET['editar'])) echo "disabled";
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
    <link rel="stylesheet" href="../css/perfil_usuario.css">

    <title>Perfil de <?=$_SESSION['usuario']['nome']?> - Sailus</title>
</head>
<body>
    <?php
        $paginaAtual = "notIndex";
        $acesso_perfil = true;

        include_once "../includes/menu_home.php";
    ?>

    <div class="dados-perfil-container">
        <div class="foto-e-nome-container">
            <img src="../imagens/perfil_usuario.png">

            <div class="nome-container">
                <h1><?=$_SESSION['usuario']['nome']?></h1>
                <h4><?=$_SESSION['usuario']['tipoUsuario'] == "Funcionario" ? "Funcionário" : "Médico" ?></h4>

                <div class="alterar-container">
                    <?php if(isset($_GET['editar'])): ?>
                        <a class="btn-cancelar-edicao" href="perfil_usuario.php">Cancelar</a>
                    <?php else: ?>
                        <a class="btn-habilitar-edicao" href="perfil_usuario.php?editar">Editar dados</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if(isset($_SESSION['mensagem_perfil'])): ?>
            <div class="rsltd-acoes-container">
                <p><?=$_SESSION['mensagem_perfil']?></p>
            </div>
        <?php
            unset($_SESSION['mensagem_perfil']);
            endif;
        ?>

        <form class="dados-usuario-container" action="../core/usuario_repositorio.php" method="POST">
            <input type="hidden" name="tipoUsuario" value="<?=$_SESSION['usuario']['tipoUsuario']?>">
            <input type="hidden" name="acao" value="Edição">
            <input type="hidden" name="id" value="<?=$_SESSION['usuario']['id']?>">
            <input type="hidden" name="emailBefore" value="<?=$_SESSION['usuario']['email']?>">

            <div class="titulo-dados-usuario">
                <h1>Dados do usuário</h1>
            </div>

            <div class="label-e-input-dados-container">
                <label for="nome">Nome</label>
                <input type="text" name="nome" value="<?=$_SESSION['usuario']['nome']?>" <?php verificarEdicao(); ?>>
            </div>

            <div class="label-e-input-dados-container">
                <label for="email">E-mail</label>
                <input type="email" name="email" value="<?=$_SESSION['usuario']['email']?>" <?php verificarEdicao(); ?>>
            </div>

            <div class="label-e-input-dados-container">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" value="<?=$_SESSION['usuario']['telefone']?>" <?php verificarEdicao(); ?> pattern="\([0-9]{2}\)\s[0-9]{4,5}-[0-9]{4}">
            </div>

            <div class="label-e-input-dados-container">
                <label for="senha">Senha</label>
                <input type="password" name="senha" value="<?=$_SESSION['usuario']['senha']?>" <?php verificarEdicao(); ?>>
            </div>

            <?php if($_SESSION['usuario']['tipoUsuario'] == "Medico"): ?>
                <input type="hidden" name="crmBefore" value="<?=$_SESSION['usuario']['crm']?>">
                
                <div class="label-e-input-dados-container">
                    <label for="crm">CRM</label>
                    <input type="text" name="crm" value="<?=$_SESSION['usuario']['crm']?>" <?php verificarEdicao(); ?> pattern="^\d{4,6}\/[A-Z]{2}$">
                </div>

                <div class="label-e-input-dados-container">
                    <label for="especialidade">Especialidade</label>
                    <input type="text" name="especialidade" value="<?=$_SESSION['usuario']['especialidade']?>" <?php verificarEdicao(); ?>>
                </div>

                <div class="label-e-input-dados-container">
                    <label for="plantonista">Plantonista</label>

                    <?php if(isset($_GET['editar'])): ?>
                        <select name="plantonista" id="plantonista">
                            <option value="Sim" <?= $_SESSION['usuario']['plantonista'] == 'Sim' ? 'selected' : '' ?>>Sim</option>
                            <option value="Não" <?= $_SESSION['usuario']['plantonista'] == 'Não' ? 'selected' : '' ?>>Não</option>
                        </select>
                    <?php else: ?>
                        <input type="text" name="plantonista" value="<?=$_SESSION['usuario']['plantonista']?>" disabled>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="label-e-input-dados-container">
                    <label for="dataContratacao">Data de contratação</label>
                    <input type="date" name="dataContratacao" value="<?=$_SESSION['usuario']['dataContratacao']?>" <?php verificarEdicao(); ?>>
                </div>

                <div class="label-e-input-dados-container">
                    <label for="turno">Turno</label>
                    
                    <?php if(isset($_GET['editar'])): ?>
                        <select name="turno" id="turno">
                            <option value="Manhã" <?= $_SESSION['usuario']['turno'] == 'Manhã' ? 'selected' : '' ?>>Manhã</option>
                            <option value="Tarde" <?= $_SESSION['usuario']['turno'] == 'Tarde' ? 'selected' : '' ?>>Tarde</option>
                            <option value="Noite" <?= $_SESSION['usuario']['turno'] == 'Noite' ? 'selected' : '' ?>>Noite</option>
                        </select>
                    <?php else: ?>
                        <input type="text" name="turno" id="turno" value="<?=$_SESSION['usuario']['turno']?>" disabled>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['editar'])): ?>
                <div class="form-btn-container">
                    <button type="submit">Editar</button>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <div class="dados-acoes-container">
        <div class="titulo-dados-acoes-container">
            <?php if($_SESSION['usuario']['tipoUsuario'] == "Funcionario"): ?>
                <h1>Ações no sistema</h1>
            <?php else: ?>
                <h1>Consultas agendadas</h1>
            <?php endif; ?>
        </div>

        <div class="opcoes-acoes-container">
            <?php if($_SESSION['usuario']['tipoUsuario'] == "Funcionario" || $_SESSION['usuario']['tipoUsuario'] == "Adm"): ?>
                <a href="pacientes_funcionario.php">Pacientes</a>
            <?php endif; ?>
                <a href="consultas.php">Acessar</a>
        </div>
    </div>
    
    <?php
        include "../includes/rodape.php";
    ?>
</body>
</html>