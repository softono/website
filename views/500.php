<?php /* Standalone error page: depends on nothing but config() and e(), so it works even when the rest of the app is broken. */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Something went wrong - <?= e(config('APP_NAME')); ?></title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 1rem; box-sizing: border-box;
               font-family: ui-sans-serif, system-ui, sans-serif; background: <?= e(config('BACKGROUND_COLOR')); ?>; color: #3d3929; }
        main { max-width: 32rem; text-align: center; }
        h1 { margin: 0 0 .5rem; font-size: 2rem; }
        p { margin: 0 0 1.5rem; line-height: 1.6; color: #5c5848; }
        a { display: inline-block; padding: .75rem 1.25rem; border-radius: .75rem; background: <?= e(config('THEME_COLOR')); ?>; color: #fff; font-weight: 600; text-decoration: none; }
        @media (prefers-color-scheme: dark) {
            body { background: <?= e(config('BACKGROUND_COLOR_DARK')); ?>; color: #ece9df; }
            p { color: #b8b4a4; }
        }
    </style>
</head>
<body>
    <main>
        <h1>Something went wrong</h1>
        <p>We hit an unexpected error. It has been logged. Please try again in a moment.</p>
        <a href="<?= e(site_url()); ?>">Back to home</a>
    </main>
</body>
</html>
