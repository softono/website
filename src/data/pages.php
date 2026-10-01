<?php
/**
 * Single registry of site pages: routing, navigation, SEO and sitemap data.
 * Loaded by site_pages() in src/routes.php. The URL of each page is set in routes().
 *   view     file in views/ that renders the page content
 *   nav      label shown in the primary navigation and footer (omit to hide)
 *   name     short name used in breadcrumbs
 *   schema   schema.org WebPage subtype
 *   hidden   true = not in routes(), excluded from the sitemap, marked noindex (404)
 */
return [
    'home' => [
        'view' => 'home',
        'name' => 'Home',
        'schema' => 'WebPage',
        'title' => config('APP_NAME') . ' - Build something amazing, faster',
        'description' => 'A modern full-stack platform to power your next project. Simple, fast, and ready to scale.',
    ],
    'about' => [
        'view' => 'about',
        'nav' => 'About',
        'name' => 'About',
        'schema' => 'AboutPage',
        'title' => 'About - ' . config('APP_NAME'),
        'description' => 'Learn about our mission to help teams ship faster with a simple, optimized foundation.',
    ],
    'services' => [
        'view' => 'services',
        'nav' => 'Services',
        'name' => 'Services',
        'schema' => 'CollectionPage',
        'title' => 'Services - ' . config('APP_NAME'),
        'description' => 'Explore what we offer: fast rendering, secure defaults, an admin dashboard and data management.',
    ],
    'contact' => [
        'view' => 'contact',
        'nav' => 'Contact',
        'name' => 'Contact',
        'schema' => 'ContactPage',
        'title' => 'Contact - ' . config('APP_NAME'),
        'description' => 'Get in touch with us. Send a message and we will reply soon.',
    ],
    'not-found' => [
        'view' => '404',
        'hidden' => true,
        'name' => 'Page not found',
        'schema' => 'WebPage',
        'title' => 'Page not found - ' . config('APP_NAME'),
        'description' => 'The page you are looking for does not exist.',
    ],
];
