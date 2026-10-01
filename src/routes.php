<?php
/**
 * Every URL of the site, in one place. (Page helpers are in src/helper/functions.php.)
 *   URL path => page key (a page from src/data/pages.php, rendered with views/)
 *   URL path => [src file to load, function to call] (feeds and the contact handler)
 */
function routes() {
    return [
        ''             => 'home',
        'about'        => 'about',
        'services'     => 'services',
        'contact'      => 'contact',

        'sitemap.xml'  => ['controllers/feeds', 'render_sitemap'],
        'robots.txt'   => ['controllers/feeds', 'render_robots'],
        'manifest'     => ['controllers/feeds', 'render_manifest'],
        'contact-send' => ['controllers/contact', 'handle_contact'],
    ];
}
