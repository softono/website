<?php /* Site header. Vars: $key (current page key), $navPages (key => [label, href]). */ ?>
<header class="sticky top-0 z-40 t-header t-border border-b backdrop-blur">
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
        <a href="./" class="pjax flex items-center" aria-label="<?= e(config('APP_NAME')); ?> home">
            <span class="t-brand" aria-hidden="true"><svg viewBox="0 0 40 40" class="h-9 w-9"><rect x="2" y="2" width="36" height="36" rx="9" fill="currentColor"/><path d="M13 29V11h7a5.5 5.5 0 0 1 0 11h-7" fill="none" stroke="var(--primary-foreground)" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            <span class="ml-2.5 text-lg font-bold tracking-tight t-heading"><?= e(config('APP_NAME')); ?></span>
        </a>
        <div class="flex items-center gap-1">
            <nav class="flex items-center gap-1" aria-label="Primary">
                <?php foreach ($navPages as $navKey => $nav): ?>
                    <a href="<?= e($nav['href']); ?>" class="nav-link pjax"<?= $key === $navKey ? ' aria-current="page"' : ''; ?>><?= e($nav['label']); ?></a>
                <?php endforeach; ?>
            </nav>
            <button type="button" id="theme-toggle" class="icon-btn" aria-label="Toggle dark mode">
                <span class="show-light"><?= icon('moon', 'w-5 h-5'); ?></span>
                <span class="show-dark"><?= icon('sun', 'w-5 h-5'); ?></span>
            </button>
        </div>
    </div>
</header>
