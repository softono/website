<?php /* Breadcrumb for inner pages; matches the BreadcrumbList JSON-LD in src/helper/seo.php. Vars: $crumb (current page name). */ ?>
<nav aria-label="Breadcrumb" class="mb-4 text-sm">
    <ol class="flex items-center gap-2 t-muted">
        <li><a href="./" class="pjax footer-link">Home</a></li>
        <li aria-hidden="true">/</li>
        <li aria-current="page" class="t-heading"><?= e($crumb); ?></li>
    </ol>
</nav>
