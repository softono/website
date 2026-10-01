# Website

A small multi-page PHP website (Home, About, Services, Contact) styled with a prebuilt Tailwind CSS file and a swappable colour theme, with PJAX navigation for fast page transitions.

## Features

- **PJAX navigation**: links with the `pjax` class fetch only the `<main>` content (`?partial=1&layout=main`) instead of reloading the whole page.
- **Loading UI**: a CSS spinner shown while uncached pages load (`.loading-text` in `assets/css/custom.css`).
- **Theming**: CSS-variable themes in `assets/theme/`; the active one is `theme-claude.css`. Light/dark mode is stored in `localStorage`.
- **Clean URLs**: `public/.htaccess` (Apache) or `nginx.conf` (Nginx) hides `.php` extensions and 301-redirects `*.php` requests.
- **SEO**: per-page title/description/canonical, Open Graph and Twitter tags, and JSON-LD structured data from `src/seo.php`. `robots.txt` and `sitemap.xml` are generated (`robots.php`, `sitemap.php`) from `BASE_URL`, so they never contain placeholder domains. PJAX fragments are sent with `X-Robots-Tag: noindex`, and the 404 page is `noindex`.
- **Security**: `src/security.php` sends CSP, HSTS (when `BASE_URL` is `https://`), `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy` and `Permissions-Policy`. Only `public/` is web-accessible; `.htaccess` / `nginx.conf` additionally deny dotfiles and docs, and redirect to HTTPS outside localhost (Apache). The CDN-hosted jQuery scripts use Subresource Integrity.
- **Contact form**: validated client-side with jQuery Validate and handled by `contact-send.php`, which enforces same-origin POSTs, a honeypot field, length limits and a per-IP rate limit (5 per 10 minutes, based on `REMOTE_ADDR`).

## Structure

```
public/                                           Web root (the only folder the server exposes)
  index.php, about.php, services.php, contact.php   Pages
  404.php                                         Not-found page
  robots.php, sitemap.php                         Served as /robots.txt and /sitemap.xml
  contact-send.php                                Contact form handler
  assets/css/custom.css                           Custom styles (incl. PJAX loader)
  assets/css/tailwind.css                         Generated Tailwind build (committed; do not edit)
  assets/theme/                                   Colour themes
  assets/js/pjax.min.js                           PJAX library
  assets/images/                                  Images and logo
  .htaccess                                       URL rewriting, caching (Apache)
src/                                              config.php, security.php, seo.php, icons.php (helpers)
src/layouts/                                      header.php, footer.php (page templates)
.env                                              Local configuration (never committed)
.htaccess                                         Dev only: maps the project root to public/
resources/tailwind.css, tailwind.config.js        Tailwind input and config (build with `npm run build`)
nginx.conf                                        URL rewriting (Nginx), include in your server block
```

## Requirements

- PHP 7.4+
- Apache with `mod_rewrite` (uses `.htaccess`) or Nginx with PHP-FPM (include `nginx.conf` in your server block)
- Internet access for the CDN-hosted jQuery and jQuery Validate
- Node.js, only if you change Tailwind classes (to rebuild the CSS)

## Setup

1. Place the project anywhere and point the server's document root at `public/` (Nginx: `root /path/to/website/public;`). `.env` and `src/` then sit outside the web root and cannot be requested. For local development you can instead serve the project root: the root `.htaccess` maps every request to `public/`.
2. Copy `.env.example` to `.env` and set `BASE_URL` (with trailing slash; use your real `https://` URL in production), `APP_NAME` and `ASSET_VERSION`. Never commit `.env`.
3. Open the site in a browser.

## Customising

- **Tailwind**: after adding or changing utility classes in any `.php` file, run `npm install` once, then `npm run build` (or `npm run watch`) and bump `ASSET_VERSION`. Commit the regenerated `public/assets/css/tailwind.css`; the server needs no Node.

- **Theme**: change the theme stylesheet linked in `src/layouts/header.php`.
- **Navigation**: edit `$navItems` in `src/layouts/header.php`.
- **SEO**: edit page titles and descriptions in the `seo_pages()` registry in `src/seo.php`; it also feeds the sitemap.
- **Cache busting**: bump `ASSET_VERSION` after changing CSS or JS.

## Adding a page

1. Create `public/page.php` modelled on an existing page, including the header and footer from `src/layouts/`.
2. Add it to `$navItems` in `src/layouts/header.php`.
3. Add an entry to `seo_pages()` in `src/seo.php` (this also adds it to the sitemap).
4. Give internal links the `pjax` class.

## License

MIT. See [LICENSE](LICENSE).
