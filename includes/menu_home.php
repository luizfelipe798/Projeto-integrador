<?php
    $urlDirecionamento = "perfil_usuario.php";
    $urlPerfilBase = "http://localhost/Projeto-integrador/paginas/perfil_usuario.php";

    if($acesso_perfil == true)
    {
        if(isset($_SERVER['HTTP_REFERER'])) 
        {
            $referer = $_SERVER['HTTP_REFERER'];
            
            if(strpos($referer, $urlPerfilBase) === 0)
            {
                $urlDirecionamento = "home_usuario.php";
            }
            else
            {
                $urlDirecionamento = $referer;
            }
        }
        else
        {
            $urlDirecionamento = "home_usuario.php";
        }
    }
?>

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
                <input type="hidden" name="acao" value="Logout">
                <input type="hidden" name="email" value="<?=$_SESSION['usuario']['email']?>">
                <input type="hidden" name="tipo_user" value="<?=$_SESSION['usuario']['tipoUsuario']?>">
                <button type="submit" class="btnSair">Sair</button>
            </form>

            <a href="<?=$urlDirecionamento?>"><img src="../imagens/perfil_usuario.png" alt="Perfil do usuário"></a>
        </div>
</menu>