<?php include 'layout/header.php'; ?>
<div id="main-content" data-title="Home">

    <section class="t-surface">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:py-20">
            <p class="eyebrow">Home</p>
            <h1 class="mt-2 max-w-3xl text-4xl font-extrabold tracking-tight t-heading sm:text-5xl">Build something amazing, faster</h1>
            <p class="mt-5 max-w-2xl text-lg t-muted">A modern full-stack platform to power your next project. Simple, fast, and ready to scale.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="contact" class="btn btn-primary pjax">Get Started</a>
                <a href="services" class="btn btn-secondary pjax">Our services</a>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
        <h2 class="mb-8 text-center text-3xl font-bold t-heading">Everything you need</h2>
        <div class="grid gap-6 md:grid-cols-3">
            <?php foreach ([
                ['bolt', 'Lightning Fast', 'Optimized for speed with server-side rendering and smart caching out of the box.'],
                ['shield', 'Secure by Default', 'Built-in authentication, role-based access control, and data validation.'],
                ['layout', 'Admin Dashboard', 'A powerful admin panel to manage users, content, and settings with ease.'],
            ] as $c): ?>
                <article class="card">
                    <span class="t-brand"><?= icon($c[0], 'w-6 h-6'); ?></span>
                    <h2 class="mt-3 font-semibold t-heading"><?= $c[1]; ?></h2>
                    <p class="mt-1.5 text-sm t-muted"><?= $c[2]; ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="t-surface">
        <div class="mx-auto max-w-3xl px-4 py-16 text-center sm:px-6">
            <h2 class="text-3xl font-bold t-heading">Ready to get started?</h2>
            <p class="mt-3 t-muted">Create your account in seconds and start building today.</p>
            <a href="contact" class="btn btn-primary pjax mt-6">Create Free Account</a>
        </div>
    </section>

</div>
<?php include 'layout/footer.php'; ?>
