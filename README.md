# Website

A small multi-page PHP website (Home, About, Services, Contact) styled with Tailwind CSS and a swappable colour theme, with PJAX navigation for fast page transitions.

## Features

- **PJAX navigation**: links with the `pjax` class fetch only the `<main>` content (`?partial=1&layout=main`) instead of reloading the whole page.
- **Loading UI**: a CSS spinner shown while uncached pages load (`.loading-text` in `assets/css/custom.css`).
- **Theming**: CSS-variable themes in `assets/theme/`; pick one with `THEME=` in `.env` (default `claude`). A header toggle switches light/dark mode, stored in `localStorage`.
- **Accessibility**: skip link, `aria-current` navigation, focus moved to the content after PJAX swaps, honeypot-protected contact form, contrast tweaks that apply to every theme.
- **Clean URLs**: a front controller (`public/index.php` + `system/router.php`) serves extensionless URLs and 301-redirects `*.php`, `/index` and trailing slashes.
- **SEO**: `src/helper/seo.php` and the breadcrumb component emit title, description, canonical, robots, Open Graph, Twitter cards and a JSON-LD graph (Organization, WebSite, WebPage, BreadcrumbList) per page, plus visible breadcrumbs. `robots.txt` and `sitemap.xml` are generated (`src/controllers/feeds.php`) from `BASE_URL` and the page registry, with `<lastmod>` from file times. Unknown URLs return a real 404 (`views/404.php`, noindex), `/index` and `*.php` 301 to the clean URL, and PJAX fragments are sent with `X-Robots-Tag: noindex`.
- **Contact form**: validated client-side with jQuery Validate and handled by `src/controllers/contact.php`, with a CSRF token, a honeypot field and a per-IP rate limit (5 per 10 minutes).

## Structure

```
public/                  Web root: the only folder the server exposes
  index.php              Front controller (every non-file request)
  .htaccess              Apache rewrites, headers, caching
  assets/                css/, js/, images/, theme/ (colour themes)
system/                  Core: loaded first, rarely edited
  bootstrap.php          Defines paths, loads config, errors and the router only
  config.php             config() (.env + defaults), e(), asset(), site_url(), load()
  errors.php             Error handling: real 500 page, details go to the PHP error log
  router.php             dispatch(): redirects, route lookup, page rendering, 404
  view.php               render_page(), render_view(), component() (loaded only for page URLs)
src/                     Feature code, each file loaded only when a URL needs it
  routes.php             routes(): every URL
  controllers/feeds.php  sitemap.xml, robots.txt, manifest
  controllers/contact.php  Contact form handler (POST /contact-send)
  helper/seo.php         Meta tags, Open Graph, JSON-LD
  helper/icons.php       Inline SVG icons
  helper/functions.php   page_path(), page_href(), site_pages(), site_features()
  helper/security.php    CSRF token and rate limit
  data/pages.php         Page registry: titles, descriptions, nav labels
  data/features.php      Feature cards used on Home and Services
views/
  layouts/main.php       <head>, PJAX partial logic, page skeleton
  home.php, about.php, services.php, contact.php, 404.php   Page content only
  500.php                Standalone error page
  components/            header, footer, hero, feature-card, breadcrumb (component('name', [...]))
  emails/contact.php     Contact email template
.env, .env.example       Settings (outside the web root)
.htaccess (root)         Forwards into public/ when the document root cannot be changed
nginx.conf               Nginx equivalent (set root to public/)
```

## Requirements

- PHP 7.4+
- Apache with `mod_rewrite` (point the document root at `public/`, or keep the root `.htaccess` forwarder) or Nginx with PHP-FPM (include `nginx.conf`, root = `public/`)
- Internet access for the CDN-hosted Tailwind, jQuery and jQuery Validate

## Setup

1. Place the project anywhere and serve the `public/` folder (or, for a subfolder like `C:\www\wwwroot\dev\website`, rely on the root `.htaccess`).
2. Copy `.env.example` to `.env` and set `BASE_URL`, `APP_NAME`, `THEME` and the mail settings. Keep `APP_DEBUG=0` in production; `1` shows PHP errors.
3. Open the site in a browser.

## Customising

- **Theme**: set `THEME` in `.env`.
- **Social preview and profiles**: `OG_IMAGE` (1200x630), `SOCIAL_LINKS` and `TWITTER_HANDLE` in `.env`.
- **Brand colours**: `THEME_COLOR`, `BACKGROUND_COLOR`, `BACKGROUND_COLOR_DARK` in `.env` (browser theme-color, manifest, email).
- **Titles and descriptions**: edit `src/data/pages.php`. URLs live in `src/routes.php`. Feature cards live in `src/data/features.php`.
- **Cache busting**: automatic. `asset()` adds the file's modification time to CSS and JS URLs.

## Adding a page

1. Create `views/page.php` with the page content only (start with `component('hero', ['crumb' => $page['name'], ...])`; the layout supplies header and footer).
2. Add its URL to `routes()` in `src/routes.php` and its title and description to `src/data/pages.php` (give it a `nav` label to show it in the menu). SEO and the sitemap pick it up automatically.
3. Give internal links the `pjax` class.

## License

MIT. See [LICENSE](LICENSE).
