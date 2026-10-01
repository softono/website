<?php /* Feature card. Vars: $icon, $title, $text, $horizontal (optional). */ ?>
<article class="card card-hover<?= !empty($horizontal) ? ' flex gap-4' : ''; ?>">
    <span class="icon-tile"><?= icon($icon, 'w-6 h-6'); ?></span>
    <div<?= !empty($horizontal) ? '' : ' class="mt-4"'; ?>>
        <h3 class="font-semibold t-heading"><?= e($title); ?></h3>
        <p class="mt-1.5 text-sm t-muted"><?= e($text); ?></p>
    </div>
</article>
