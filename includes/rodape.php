<footer class="rodape-container" id="footer">
    <div class="instituto-container">
        <?php if(!isset($_SESSION['logado'])): ?>
            <a href="https://bri.ifsp.edu.br/"><img src='imagens/logoifspbribranca.png' alt=""></a>
        <?php else: ?>
            <a href="https://bri.ifsp.edu.br/"><img src='../imagens/logoifspbribranca.png' alt=""></a>
        <?php endif; ?>
    </div>
    <div class="copyright-container">
        <p>© 2025 Sailus. Todos os direitos reservados.</p>
    </div>
</footer>