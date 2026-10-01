# Website

A small multi-page PHP website (Home, About, Services, Contact) styled with Tailwind CSS and a swappable colour theme, with PJAX navigation for fast page transitions.

## Features

- **PJAX navigation**: links with the `pjax` class fetch only the `<main>` content (`?partial=1&layout=main`) instead of reloading the whole page.
- **Loading UI**: a CSS spinner shown while uncached pages load (`.loading-text` in `assets/css/custom.css`).
- **Theming**: CSS-variable themes in `assets/theme/`; the active one is `theme-claude.css`. Light/dark mode is stored in `localStorage`.
- **Clean URLs**: `.htaccess` (Apache) or `nginx.conf` (Nginx) hides `.php` extensions and 301-redirects `*.php` requests.
- **SEO**: per-page title/description/canonical, Open Graph and Twitter tags, and JSON-LD structured data from `src/seo.php`. `robots.txt` and `sitemap.xml` are generated (`robots.php`, `sitemap.php`) from `BASE_URL`, so they never contain placeholder domains. PJAX fragments are sent with `X-Robots-Tag: noindex`, and the 404 page is `noindex`.
- **Security**: `src/security.php` sends CSP, HSTS (when `BASE_URL` is `https://`), `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy` and `Permissions-Policy`. `.htaccess` / `nginx.conf` deny `.env`, dotfiles, `layout/`, `src/` and docs, and redirect to HTTPS outside localhost (Apache). CDN scripts use Subresource Integrity (the Tailwind CDN build is dynamic and cannot be pinned).
- **Contact form**: validated client-side with jQuery Validate and handled by `contact-send.php`, which enforces same-origin POSTs, a honeypot field, length limits and a per-IP rate limit (5 per 10 minutes, based on `REMOTE_ADDR`).

## Structure

```
index.php, about.php, services.php, contact.php   Pages
404.php                                           Not-found page
robots.php, sitemap.php                           Served as /robots.txt and /sitemap.xml
contact-send.php                                  Contact form handler
layout/                                           header.php, footer.php (page templates)
src/                                              config.php, security.php, seo.php, icons.php (helpers)
assets/css/custom.css                             Custom styles (incl. PJAX loader)
assets/theme/                                     Colour themes
assets/js/pjax.min.js                             PJAX library
assets/images/                                    Images and logo
.htaccess, nginx.conf                                 URL rewriting (Apache / Nginx)
```

## Requirements

- PHP 7.4+
- Apache with `mod_rewrite` (uses `.htaccess`) or Nginx with PHP-FPM (include `nginx.conf` in your server block)
- Internet access for the CDN-hosted Tailwind, jQuery and jQuery Validate

## Setup

1. Place the project in your web root (e.g. `C:\www\wwwroot\dev\website`).
2. Copy `.env.example` to `.env` and set `BASE_URL` (with trailing slash; use your real `https://` URL in production), `APP_NAME` and `ASSET_VERSION`. Never commit `.env`.
3. Open the site in a browser.

## Customising

- **Theme**: change the theme stylesheet linked in `layout/header.php`.
- **Navigation**: edit `$navItems` in `layout/header.php`.
- **SEO**: edit page titles and descriptions in the `seo_pages()` registry in `src/seo.php`; it also feeds the sitemap.
- **Cache busting**: bump `ASSET_VERSION` after changing CSS or JS.

## Adding a page

1. Create `page.php` modelled on an existing page, including the header and footer from `layout/`.
2. Add it to `$navItems` in `layout/header.php`.
3. Add an entry to `seo_pages()` in `src/seo.php` (this also adds it to the sitemap).
4. Give internal links the `pjax` class.

## License

MIT. See [LICENSE](LICENSE).
