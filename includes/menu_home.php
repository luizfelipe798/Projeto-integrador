<?php
    session_start();
?>

<menu class="menuPrincipal">
        <div class="logotipoLinks">
                <a href="home_usuario.php" class="logotipoMenu"><img class="logotipoMenu" src="../imagens/logotiposalus.png" alt="Logotipo Sailus"></a>
        </div>

        <div class="btnsUserLogado">
            <form action="../core/usuario_repositorio.php" method="POST">
                <input type="hidden" name="acao" value="logout">
                <input type="hidden" name="email" value="<?=$_SESSION['email']?>">
                <input type="hidden" name="tipo_user" value="<?=$_SESSION['tipo_usuario']?>">
                <button type="submit" class="btnSair">Sair</button>
            </form>

            <a href=""><img src="../imagens/perfil_usuario.png" alt="Perfil do usuário"></a>
        </div>
</menu>