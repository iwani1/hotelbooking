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
EXTRACT="$ROOT/.bookora-extract"

if [ ! -f "$PKG" ]; then
    echo "Package not found: $PKG" >&2
    exit 1
fi

echo "==> Extracting outer package"
rm -rf "$EXTRACT"
mkdir -p "$EXTRACT"
unzip -q "$PKG" -d "$EXTRACT"

PKG_DIR="$EXTRACT/bookora-wp-package"
if [ ! -d "$PKG_DIR" ]; then
    echo "Unexpected package layout: $PKG_DIR not found" >&2
    exit 1
fi

# The package is nested: the theme and plugin are inside inner zips.
echo "==> Extracting theme + plugin (nested zips)"
mkdir -p "$EXTRACT/theme" "$EXTRACT/plugin"
unzip -q "$PKG_DIR/bookora.zip" -d "$EXTRACT/theme"
unzip -q "$PKG_DIR/plugin/bookora-plugin.zip" -d "$EXTRACT/plugin"

THEME_DIR="$EXTRACT/theme/bookora"
PLUGIN_DIR="$EXTRACT/plugin/bookora-plugin"

if [ ! -d "$THEME_DIR" ]; then
    echo "Theme not found at $THEME_DIR" >&2
    exit 1
fi
if [ ! -d "$PLUGIN_DIR" ]; then
    echo "Plugin not found at $PLUGIN_DIR" >&2
    exit 1
fi

echo "==> Installing theme + plugin into wp-content"
mkdir -p "$WP_CONTENT/themes" "$WP_CONTENT/plugins"
rm -rf "$WP_CONTENT/themes/bookora" "$WP_CONTENT/plugins/bookora-plugin"
cp -r "$THEME_DIR" "$WP_CONTENT/themes/bookora"
cp -r "$PLUGIN_DIR" "$WP_CONTENT/plugins/bookora-plugin"
rm -rf "$EXTRACT"

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
