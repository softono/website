<?php /* Site footer. Vars: $navPages (key => [label, href]). */ ?>
<footer class="site-footer mt-8 border-t">
    <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 py-10 text-sm sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
            <p class="font-semibold t-heading"><?= e(config('APP_NAME')); ?></p>
            <p class="mt-1">&copy; <?= date('Y'); ?> <?= e(config('APP_NAME')); ?>. All rights reserved.</p>
        </div>
        <nav class="flex flex-wrap gap-x-6 gap-y-2" aria-label="Footer">
            <?php foreach ($navPages as $nav): ?>
                <a class="footer-link pjax" href="<?= e($nav['href']); ?>"><?= e($nav['label']); ?></a>
            <?php endforeach; ?>
            <?php if (config('CONTACT_EMAIL') !== ''): ?>
                <a class="footer-link" href="mailto:<?= e(config('CONTACT_EMAIL')); ?>"><?= e(config('CONTACT_EMAIL')); ?></a>
            <?php endif; ?>
        </nav>
    </div>
</footer>
