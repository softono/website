<?php
/* Hero band. Vars: $eyebrow, $title, $lead (optional), $large (optional), $actions (optional HTML), $crumb (optional page name: shows a breadcrumb). */
$h1 = !empty($large) ? 'text-4xl sm:text-5xl' : 'text-3xl sm:text-4xl';
?>
<section class="t-surface hero">
    <div class="bg-grid" aria-hidden="true"></div>
    <div class="relative mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:py-20">
        <?php if (!empty($crumb)) component('breadcrumb', ['crumb' => $crumb]); ?>
        <p class="eyebrow"><?= e($eyebrow); ?></p>
        <h1 class="mt-2 max-w-3xl <?= $h1; ?> font-extrabold tracking-tight t-heading text-balance"><?= e($title); ?></h1>
        <?php if (!empty($lead)): ?>
            <p class="mt-5 max-w-2xl text-lg t-muted"><?= e($lead); ?></p>
        <?php endif; ?>
        <?php if (!empty($actions)): ?>
            <div class="mt-8 flex flex-wrap gap-3"><?= $actions; ?></div>
        <?php endif; ?>
    </div>
</section>
