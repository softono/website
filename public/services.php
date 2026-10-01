<?php include __DIR__ . '/../src/layouts/header.php'; ?>
<div id="main-content" data-title="Services">

    <section class="t-surface hero">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <p class="eyebrow">Services</p>
            <h1 class="mt-2 text-4xl font-extrabold t-heading">Our services</h1>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
        <div class="grid gap-6 md:grid-cols-2">
            <?php foreach ([
                ['bolt', 'Lightning Fast', 'Optimized for speed with server-side rendering and smart caching out of the box.'],
                ['shield', 'Secure by Default', 'Built-in authentication, role-based access control, and data validation.'],
                ['layout', 'Admin Dashboard', 'A powerful admin panel to manage users, content, and settings with ease.'],
                ['database', 'Data Management', 'Store, query, and export your data with a clean, reliable backend.'],
            ] as $s): ?>
                <article class="card flex gap-4">
                    <span class="icon-tile"><?= icon($s[0], 'w-6 h-6'); ?></span>
                    <div>
                        <h2 class="font-semibold t-heading"><?= $s[1]; ?></h2>
                        <p class="mt-1.5 text-sm t-muted"><?= $s[2]; ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

</div>
<?php include __DIR__ . '/../src/layouts/footer.php'; ?>
