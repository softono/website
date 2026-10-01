    <?php component_start('hero', [
        'eyebrow' => 'Home',
        'title' => 'Build something amazing, faster',
        'lead' => 'A modern full-stack platform to power your next project. Simple, fast, and ready to scale.',
        'large' => true,
    ]); ?>
        <a href="contact" class="btn btn-primary pjax">Get Started <?= icon('arrow', 'w-4 h-4'); ?></a>
        <a href="services" class="btn btn-secondary pjax">Our services</a>
    <?php component_end(); ?>

    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
        <h2 class="mb-10 text-center text-3xl font-bold t-heading text-balance">Everything you need</h2>
        <div class="grid gap-6 md:grid-cols-3">
            <?php foreach (array_slice(site_features(), 0, 3) as $f): ?>
                <?php component('feature-card', ['icon' => $f[0], 'title' => $f[1], 'text' => $f[2]]); ?>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="t-surface border-y t-border">
        <div class="mx-auto max-w-3xl px-4 py-16 text-center sm:px-6">
            <h2 class="text-3xl font-bold t-heading text-balance">Ready to get started?</h2>
            <p class="mt-3 t-muted">Send us a message and we will help you get set up.</p>
            <a href="contact" class="btn btn-primary pjax mt-6">Contact us</a>
        </div>
    </section>
