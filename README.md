# hotelbooking

A pre-built WordPress theme package: **Bookora — Hotel Booking WordPress Theme**
(theme + companion plugin). Fixed and repackaged from the original upload.

## What's here

| File | Description |
|---|---|
| `bookora_hotel_booking_wordpress_theme_2026_01_08_10_59_53_utc.zip` | The full package (theme + plugin + docs + GPL) |
| `docker-compose.yml` | Local dev stack (WordPress 6 + MySQL 8 on port 8080) |
| `install.sh` | Copies theme/plugin in and installs required plugins |
| `.gitignore` | Ignores OS junk and extracted package contents |

## Quick start (Docker — recommended)

```bash
docker compose up -d
bash install.sh
# open http://localhost:8080  (admin / admin)
```

Then:

1. **Appearance → Theme License** — enter your Lemon Squeezy **API key**, click
   *Save API Key*, then enter your **license key** and click *Activate License*.
2. **Appearance → Import Demo Data** (One Click Demo Import) to load demo content.

> The theme's own "Demo Import" page buttons are stubs; use One Click Demo Import.
> `Directorist` is a premium plugin — install it manually if needed.

## Local (non-Docker) on Fedora

```bash
sudo dnf install php php-cli php-fpm php-mysqlnd php-gd php-mbstring php-xml \
                 php-curl php-zip php-intl php-exif mariadb mariadb-server nginx
```

Point a web root at WordPress, drop `bookora` into `wp-content/themes/` and
`bookora-plugin` into `wp-content/plugins/`, then install the required plugins
and activate the theme (see the full audit report for WP-CLI commands).

## What was fixed

See `hotelbooking-audit-report.md` (in the workspace) for the complete audit.
Highlights:

- Wired in the orphaned `activation.php` (license manager + demo importer were dead code).
- Fixed an undefined `CT_URL` fatal error in the mega-menu walker.
- Added the missing license-activation JS/CSS, customizer.js, RTL files, logo, blog
  and demo placeholder images, flaticon files and `jquery.singlePageNav.js`.
- Added a **Lemon Squeezy API key field** to the license page.
- Hardened SVG upload sanitization and AJAX capability checks.
- Stripped `__MACOSX/` + `.DS_Store` junk from all zips.

## Requirements

- PHP 8.0+, MySQL/MariaDB, WP-CLI (for the installer).
- Required plugins: Elementor, WooCommerce, Contact Form 7, One Click Demo Import,
  Mailchimp for WP (+ Directorist for directory listings).
