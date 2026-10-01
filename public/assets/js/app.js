/* Site behaviour: theme toggle and PJAX hooks. */
(function ($) {
    var root = document.documentElement;

    // ---------- Theme toggle (light <-> dark, stored in localStorage) ----------
    function syncToggle() {
        var dark = root.classList.contains('dark');
        $('#theme-toggle').attr('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
    }
    $(document).on('click', '#theme-toggle', function () {
        var dark = !root.classList.contains('dark');
        root.classList.toggle('dark', dark);
        try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {}
        syncToggle();
    });

    // The skip link must not navigate: PJAX swaps content without changing the document.
    $(document).on('click', '.skip-link', function (e) {
        e.preventDefault();
        $('#main-container')[0].focus();
    });

    // ---------- Navigation state ----------
    function markActive(href) {
        $('.nav-link').removeAttr('aria-current').filter(function () { return this.href === href; }).attr('aria-current', 'page');
    }

    $(function () {
        syncToggle();

        pjax.onLinkClick = function (link) { markActive(link.href); };

        // After each PJAX swap: sync meta description, mark the active link, move focus to content.
        pjax.onPageLoaded = function (url) {
            var $c = $('#main-content');
            var title = $c.data('title') || document.title, desc = $c.data('description') || '', canon = $c.data('canonical') || '';
            $('meta[name="description"], meta[property="og:description"], meta[name="twitter:description"]').attr('content', desc);
            $('meta[property="og:title"], meta[name="twitter:title"]').attr('content', title);
            $('meta[property="og:url"]').attr('content', canon);
            $('link[rel="canonical"]').attr('href', canon);
            markActive(new URL(url, document.baseURI).href);
            $('#main-container')[0].focus({ preventScroll: true });
        };

        pjax.init();
    });
})(jQuery);
