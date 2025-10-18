<menu class="menuPrincipal">
        <div class="logotipoLinks">
            <?php if($paginaAtual == "HomeUsuario"):?>
                <a href="../index.php" class="logotipoMenu">
            <?php else: ?>
                <a href="home_usuario.php" class="logotipoMenu">
            <?php endif; ?>
                        <img class="logotipoMenu" src="../imagens/logotiposalus.png" alt="Logotipo Sailus">
                </a>
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