# Website

A small multi-page PHP website (Home, About, Services, Contact) styled with Tailwind CSS and a swappable colour theme, with PJAX navigation for fast page transitions.

## Features

- **PJAX navigation**: links with the `pjax` class fetch only the `<main>` content (`?partial=1&layout=main`) instead of reloading the whole page.
- **Loading UI**: a CSS spinner shown while uncached pages load (`.loading-text` in `assets/css/custom.css`).
- **Theming**: CSS-variable themes in `assets/theme/`; the active one is `theme-claude.css`. Light/dark mode is stored in `localStorage`.
- **Clean URLs**: `.htaccess` hides `.php` extensions and 301-redirects `*.php` requests.
- **SEO**: per-page meta tags via `layout/seo.php`, plus `robots.txt` and `sitemap.xml`.
- **Contact form**: validated client-side with jQuery Validate and handled by `contact-send.php`.

## Structure

```
index.php, about.php, services.php, contact.php   Pages
contact-send.php                                  Contact form handler
layout/                                           header.php, footer.php, config.php, seo.php, icons.php
assets/css/custom.css                             Custom styles (incl. PJAX loader)
assets/theme/                                     Colour themes
assets/js/pjax.min.js                             PJAX library
assets/images/                                    Images and logo
.htaccess                                         URL rewriting
```

## Requirements

- PHP 7.4+
- Apache with `mod_rewrite` (for clean URLs)
- Internet access for the CDN-hosted Tailwind, jQuery and jQuery Validate

## Setup

1. Place the project in your web root (e.g. `C:\www\wwwroot\dev\website`).
2. Set `BASE_URL` and `ASSET_VERSION` in `layout/config.php`.
3. Open the site in a browser.

## Customising

- **Theme**: change the theme stylesheet linked in `layout/header.php`.
- **Navigation**: edit `$navItems` in `layout/header.php`.
- **SEO**: edit page titles and descriptions in `layout/seo.php`.
- **Cache busting**: bump `ASSET_VERSION` after changing CSS or JS.

## Adding a page

1. Create `page.php` modelled on an existing page, including the header and footer from `layout/`.
2. Add it to `$navItems` in `layout/header.php`.
3. Add SEO meta in `layout/seo.php` and an entry in `sitemap.xml`.
4. Give internal links the `pjax` class.

## License

MIT. See [LICENSE](LICENSE).
