#!/usr/bin/env bash
#
# Bookora local installer.
#
# Copies the theme + companion plugin into the WordPress web volume and
# installs/activates the required plugins via WP-CLI.
#
# Usage:
#   docker compose up -d
#   bash install.sh
#
# Optional env vars (used only when WordPress core is not yet installed):
#   WP_URL, WP_TITLE, WP_ADMIN_USER, WP_ADMIN_PASSWORD, WP_ADMIN_EMAIL
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PKG="$ROOT/bookora_hotel_booking_wordpress_theme_2026_01_08_10_59_53_utc.zip"
WP_CONTENT="$ROOT/wp-content"

if [ ! -f "$PKG" ]; then
    echo "Package not found: $PKG" >&2
    exit 1
fi

echo "==> Extracting theme package"
rm -rf "$ROOT/.bookora-extract"
mkdir -p "$ROOT/.bookora-extract"
unzip -q "$PKG" -d "$ROOT/.bookora-extract"

echo "==> Installing theme + plugin into wp-content"
mkdir -p "$WP_CONTENT/themes" "$WP_CONTENT/plugins"
rm -rf "$WP_CONTENT/themes/bookora" "$WP_CONTENT/plugins/bookora-plugin"
cp -r "$ROOT/.bookora-extract/bookora-wp-package/bookora" "$WP_CONTENT/themes/bookora"
cp -r "$ROOT/.bookora-extract/bookora-wp-package/plugin/bookora-plugin" "$WP_CONTENT/plugins/bookora-plugin"
rm -rf "$ROOT/.bookora-extract"

echo "==> Installing WordPress core (if needed)"
if ! docker compose exec -T web wp core is-installed --allow-root; then
    docker compose exec -T web wp core install \
        --url="${WP_URL:-http://localhost:8080}" \
        --title="${WP_TITLE:-Bookora}" \
        --admin_user="${WP_ADMIN_USER:-admin}" \
        --admin_password="${WP_ADMIN_PASSWORD:-admin}" \
        --admin_email="${WP_ADMIN_EMAIL:-admin@example.com}" \
        --skip-email --allow-root
fi

echo "==> Installing required plugins from wordpress.org"
# Directorist is a premium plugin (not on wordpress.org) — install it manually.
docker compose exec -T web wp plugin install \
    elementor woocommerce contact-form-7 one-click-demo-import mailchimp-for-wp \
    --activate --allow-root

echo "==> Activating theme"
docker compose exec -T web wp theme activate bookora --allow-root

echo ""
echo "Done. Open http://localhost:8080 (admin / admin by default)."
echo "Next steps:"
echo "  1. Appearance > Theme License: enter your Lemon Squeezy API key, click"
echo "     'Save API Key', then enter your license key and click 'Activate License'."
echo "  2. Appearance > Import Demo Data (One Click Demo Import) to load demo content."
echo "  3. Install Directorist manually if you need the directory/classified listings."
