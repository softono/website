    <?php component_start('hero', [
        'crumb' => $page['name'],
        'eyebrow' => 'Error 404',
        'title' => 'Page not found',
        'lead' => 'The page you are looking for does not exist or has moved.',
    ]); ?>
        <a href="./" class="btn btn-primary pjax">Back to home</a>
        <a href="contact" class="btn btn-secondary pjax">Contact us</a>
    <?php component_end(); ?>
