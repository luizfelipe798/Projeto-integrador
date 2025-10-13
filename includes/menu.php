<?php
    session_start();
?>

<?php if(!isset($_SESSION['logado']) || $_SESSION['logado'] !== "Sim"):?>
<menu class="menuPrincipal">
        <div class="logotipoLinks">
                <a href="index.php" class="logotipoMenu"><img class="logotipoMenu" src="imagens/logotiposalus.png" alt="Logotipo Sailus"></a>
                <a href="#menuPrincipal">Início</a>
                <a href="#sobrenosBody">Sobre nós</a>
                <a href="#desenvolvedoresBody">Desenvolvedores</a>
                <a href="#footer">Instituição</a>
        </div>

        <div class="botoesCadastroLogin">
            <a href="paginas/login.php" class="btnLogin">Login</a>
            <a href="paginas/escolha.html" class="btnCadastro">Cadastre-se</a>
        </div>
</menu>
<?php else:?>
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
<?php endif; ?>