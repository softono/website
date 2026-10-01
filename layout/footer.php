<?php if (!(!empty($_GET['partial']) && ($_GET['layout'] ?? '') === 'main')) { ?>
    </main>
    <footer class="site-footer mt-8 border-t">
        <div class="mx-auto flex max-w-6xl flex-col gap-4 px-4 py-10 text-sm sm:flex-row sm:justify-between sm:px-6">
            <p>&copy; <?= date('Y'); ?> <?= SITE_NAME; ?>. All rights reserved.</p>
            <a class="footer-link" href="mailto:<?= CONTACT_EMAIL; ?>"><?= CONTACT_EMAIL; ?></a>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="assets/js/pjax.min.js"></script>
    <script>
        $(function () {
            pjax.onLinkClick = function (link) {
                var $t = $('.nav-link').filter(function () { return this.href === link.href; });
                if ($t.length) { $('.nav-link').removeClass('active'); $t.addClass('active'); }
            };
            pjax.init();
        });
    </script>
</body>
</html>
<?php } ?>
