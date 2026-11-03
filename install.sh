#!/usr/bin/env bash
#
# Bookora local installer — works with Docker or Podman.
#
# Copies the theme + companion plugin into the WordPress web volume and
# installs/activates the required plugins via WP-CLI.
#
# The official WordPress image does NOT ship WP-CLI, so we download the
# wp-cli.phar into the bind-mounted wp-content and run it through the web
# container's own PHP.
#
# Usage:
#   docker compose up -d      # or: podman-compose up -d
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
WP_CLI_PHAR="$WP_CONTENT/wp-cli.phar"
WP_DOCROOT="/var/www/html"

if [ ! -f "$PKG" ]; then
    echo "Package not found: $PKG" >&2
    exit 1
fi

# --- Detect the Compose tool (Docker or Podman) ---
if docker compose version >/dev/null 2>&1; then
    COMPOSE=(docker compose)
elif podman compose version >/dev/null 2>&1; then
    COMPOSE=(podman compose)
elif command -v podman-compose >/dev/null 2>&1; then
    COMPOSE=(podman-compose)
else
    echo "No Compose tool found." >&2
    echo "Install one of: docker + docker-compose-plugin, or podman + podman-compose" >&2
    exit 1
fi
echo "Using: ${COMPOSE[*]}"

# --- Extract the nested package ---
echo "==> Extracting outer package"
rm -rf "$EXTRACT"
mkdir -p "$EXTRACT"
unzip -q "$PKG" -d "$EXTRACT"

PKG_DIR="$EXTRACT/bookora-wp-package"
if [ ! -d "$PKG_DIR" ]; then
    echo "Unexpected package layout: $PKG_DIR not found" >&2
    exit 1
fi

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

# --- The WordPress container owns wp-content (www-data), so file operations
#     there may need elevated permissions. Use sudo when not root, then hand
#     ownership back to the host user so WP-CLI and later edits work cleanly. ---
SUDO=""
if [ "$(id -u)" -ne 0 ]; then
    SUDO="sudo"
fi
ORIG_USER="${SUDO_USER:-$(id -un)}"

echo "==> Installing theme + plugin into wp-content"
$SUDO mkdir -p "$WP_CONTENT/themes" "$WP_CONTENT/plugins"
$SUDO rm -rf "$WP_CONTENT/themes/bookora" "$WP_CONTENT/plugins/bookora-plugin"
$SUDO cp -r "$THEME_DIR" "$WP_CONTENT/themes/bookora"
$SUDO cp -r "$PLUGIN_DIR" "$WP_CONTENT/plugins/bookora-plugin"
$SUDO chown -R "$ORIG_USER:$ORIG_USER" "$WP_CONTENT/themes/bookora" "$WP_CONTENT/plugins/bookora-plugin"
rm -rf "$EXTRACT"

# --- Download WP-CLI into the bind-mounted wp-content so the web container can
#     reach it through its mount. ---
if [ ! -f "$WP_CLI_PHAR" ]; then
    echo "==> Downloading WP-CLI"
    WP_CLI_URL="https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar"
    if ! curl -fsSL "$WP_CLI_URL" -o "$WP_CLI_PHAR"; then
        echo "ERROR: could not download WP-CLI from $WP_CLI_URL" >&2
        echo "Download it manually and place it at: $WP_CLI_PHAR" >&2
        echo "  curl -O $WP_CLI_URL && cp wp-cli.phar $WP_CLI_PHAR" >&2
        echo "Then re-run: bash install.sh" >&2
        exit 1
    fi
    chmod +x "$WP_CLI_PHAR"
else
    echo "==> Using existing WP-CLI at $WP_CLI_PHAR"
fi

# Run WP-CLI inside the web container through its own PHP.
wp() {
    "${COMPOSE[@]}" exec -T web php "$WP_DOCROOT/wp-content/wp-cli.phar" \
        --allow-root --path="$WP_DOCROOT" "$@"
}

# --- Wait for the database to be ready ---
echo "==> Waiting for database"
ready=0
for i in $(seq 1 40); do
    if "${COMPOSE[@]}" exec db mysqladmin ping -h localhost -ubookora -pbookora >/dev/null 2>&1; then
        ready=1
        break
    fi
    sleep 2
done
if [ "$ready" != "1" ]; then
    echo "Timed out waiting for the database." >&2
    exit 1
fi
# The MySQL image runs a temporary init server before the real one takes over;
# give the real mysqld a moment to fully bind the socket.
sleep 2

# --- Install WordPress core if needed ---
echo "==> Installing WordPress core (if needed)"
if ! wp core is-installed; then
    wp core install \
        --url="${WP_URL:-http://localhost:8080}" \
        --title="${WP_TITLE:-Bookora}" \
        --admin_user="${WP_ADMIN_USER:-admin}" \
        --admin_password="${WP_ADMIN_PASSWORD:-admin}" \
        --admin_email="${WP_ADMIN_EMAIL:-admin@example.com}" \
        --skip-email
fi

# --- Install required plugins ---
echo "==> Installing required plugins from wordpress.org"
# Directorist is a premium plugin (not on wordpress.org) — install it manually.
wp plugin install \
    elementor woocommerce contact-form-7 one-click-demo-import mailchimp-for-wp \
    --activate

# --- Activate the theme ---
echo "==> Activating theme"
wp theme activate bookora

# --- Remove the temporary WP-CLI phar and hand wp-content to the web server
#     user (www-data) so the site can write uploads, cache, and run demo import. ---
echo "==> Finalizing permissions"
rm -f "$WP_CLI_PHAR"
"${COMPOSE[@]}" exec -T web chown -R www-data:www-data "$WP_DOCROOT/wp-content"

echo ""
echo "Done. Open http://localhost:8080 (admin / admin by default)."
echo "Next steps:"
echo "  1. Appearance > Theme License: enter your Lemon Squeezy API key, click"
echo "     'Save API Key', then enter your license key and click 'Activate License'."
echo "  2. Appearance > Import Demo Data (One Click Demo Import) to load demo content."
echo "  3. Install Directorist manually if you need the directory/classified listings."
echo ""
echo "Note: wp-content is owned by the container's www-data user so the web"
echo "      server can write uploads. To edit files, use sudo, or run:"
echo "       sudo chown -R \$USER:\$USER wp-content"
echo "     To hand it back to the web server:"
echo "       podman exec bookora-web chown -R www-data:www-data /var/www/html/wp-content"
echo "       (or: docker exec bookora-web chown -R www-data:www-data /var/www/html/wp-content)"
