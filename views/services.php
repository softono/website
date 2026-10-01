    <?php component('hero', ['crumb' => $page['name'], 'eyebrow' => 'Services', 'title' => 'Our services', 'lead' => 'Everything you need to launch and grow, in one place.']); ?>

    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
        <div class="grid gap-6 md:grid-cols-2">
            <?php foreach (site_features() as $f): ?>
                <?php component('feature-card', ['icon' => $f[0], 'title' => $f[1], 'text' => $f[2], 'horizontal' => true]); ?>
            <?php endforeach; ?>
        </div>
    </section>
