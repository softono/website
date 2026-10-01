<?php
http_response_code(404);
$seoOverride = [
    'title' => 'Page not found',
    'description' => 'The page you are looking for does not exist.',
    'robots' => 'noindex, follow',
    'jsonld' => '',
];
include 'layout/header.php';
?>
<div id="main-content" data-title="Page not found">

    <section class="mx-auto max-w-3xl px-4 py-24 text-center sm:px-6">
        <p class="eyebrow">404</p>
        <h1 class="mt-2 text-4xl font-extrabold t-heading">Page not found</h1>
        <p class="mt-4 t-muted">The page you are looking for does not exist or has moved.</p>
        <a href="./" class="btn btn-primary pjax mt-8">Back to home</a>
    </section>

</div>
<?php include 'layout/footer.php'; ?>
