<?php
/** Per-page SEO metadata (dummy content). */
function get_seo_meta($pageKey) {
    $map = [
        'home'     => ['Next Demo - Build something amazing, faster', 'A modern full-stack platform to power your next project. Simple, fast, and ready to scale.'],
        'about'    => ['About - Next Demo', 'Dummy about page for the skeleton site.'],
        'services' => ['Services - Next Demo', 'Dummy list of services.'],
        'contact'  => ['Contact - Next Demo', 'Dummy contact form.'],
    ];
    [$title, $desc] = $map[$pageKey] ?? $map['home'];
    return [
        'title' => $title,
        'description' => $desc,
        'robots' => 'index, follow',
        'canonical' => BASE_URL . ($pageKey === 'home' ? '' : $pageKey),
    ];
}
