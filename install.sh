#!/usr/bin/env bash
#
# Bookora local installer — works with Docker or Podman.
#
# Copies the theme + companion plugin into the WordPress web volume and
# installs/activates the required plugins via WP-CLI.
#
# The official WordPress image does NOT ship WP-CLI, so we download the
# wp-cli.phar and copy it into the running web container (via podman cp /
# docker cp), then run it through the container's own PHP.
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
WP_CLI_TMP="$ROOT/.wp-cli.phar"
WP_CLI_CONTAINER="/tmp/wp-cli.phar"
WP_DOCROOT="/var/www/html"
CONTAINER_NAME="bookora-web"

if [ ! -f "$PKG" ]; then
    echo "Package not found: $PKG" >&2
    exit 1
fi

# --- Detect the Compose tool and container runtime ---
if docker compose version >/dev/null 2>&1; then
    COMPOSE=(docker compose)
    CONTAINER_RUNTIME=(docker)
elif podman compose version >/dev/null 2>&1; then
    COMPOSE=(podman compose)
    CONTAINER_RUNTIME=(podman)
elif command -v podman-compose >/dev/null 2>&1; then
    COMPOSE=(podman-compose)
    CONTAINER_RUNTIME=(podman)
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

# --- Permissions setup ---
# The web container creates wp-content as www-data. Hand it to the host user
# so file operations (copy, remove) work without per-command sudo.
# We chown back to www-data at the end so the web server can write uploads.
SUDO=""
if [ "$(id -u)" -ne 0 ]; then
    SUDO="sudo"
fi
ORIG_USER="${SUDO_USER:-$(id -un)}"

echo "==> Preparing wp-content"
$SUDO mkdir -p "$WP_CONTENT/themes" "$WP_CONTENT/plugins"
$SUDO chown -R "$ORIG_USER:$ORIG_USER" "$WP_CONTENT"

echo "==> Installing theme + plugin into wp-content"
rm -rf "$WP_CONTENT/themes/bookora" "$WP_CONTENT/plugins/bookora-plugin"
cp -r "$THEME_DIR" "$WP_CONTENT/themes/bookora"
cp -r "$PLUGIN_DIR" "$WP_CONTENT/plugins/bookora-plugin"
rm -rf "$EXTRACT"

# --- Download WP-CLI and copy it into the running container ---
# We copy the phar into the container (via podman cp / docker cp) rather than
# relying on the bind mount, which can have SELinux label issues on Fedora.
if [ ! -f "$WP_CLI_TMP" ]; then
    echo "==> Downloading WP-CLI"
    WP_CLI_URL="https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar"
    if ! curl -fsSL "$WP_CLI_URL" -o "$WP_CLI_TMP"; then
        rm -f "$WP_CLI_TMP"
        echo "ERROR: could not download WP-CLI from $WP_CLI_URL" >&2
        echo "Download it manually and place it at: $WP_CLI_TMP" >&2
        echo "  curl -O $WP_CLI_URL" >&2
        echo "Then re-run: bash install.sh" >&2
        exit 1
    fi
    chmod +x "$WP_CLI_TMP"
else
    echo "==> Using existing WP-CLI at $WP_CLI_TMP"
fi

echo "==> Copying WP-CLI into the web container"
"${CONTAINER_RUNTIME[@]}" cp "$WP_CLI_TMP" "$CONTAINER_NAME:$WP_CLI_CONTAINER"
"${CONTAINER_RUNTIME[@]}" exec "$CONTAINER_NAME" chmod +x "$WP_CLI_CONTAINER"

# Run WP-CLI inside the web container through its own PHP.
wp() {
    "${COMPOSE[@]}" exec -T web php "$WP_CLI_CONTAINER" \
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
if ! wp core is-installed 2>/dev/null; then
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
# Install each plugin individually so one failure doesn't block the others.
# Directorist is a premium plugin (not on wordpress.org) — install it manually.

wp plugin install elementor 2>/dev/null || true
wp plugin install woocommerce 2>/dev/null || true
# contact-form-7 has a malformed Requires PHP field in some versions —
# try latest, fall back to 5.9.7, then skip if both fail.
if ! wp plugin install contact-form-7 2>/dev/null; then
    wp plugin install contact-form-7@5.9.7 2>/dev/null \
        || echo "WARNING: contact-form-7 could not be installed — install it manually from the WordPress admin"
fi
wp plugin install one-click-demo-import 2>/dev/null || true
wp plugin install mailchimp-for-wp 2>/dev/null || true

# Deactivate Elementor if active — activating WooCommerce while Elementor is
# active crashes with "Call to a member function add_feature() on null" because
# WooCommerce's CLI runner fires rest_api_init during activation, and Elementor's
# module manager isn't initialized in CLI context at that point.
wp plugin deactivate elementor 2>/dev/null || true

echo "==> Activating WooCommerce"
wp plugin activate woocommerce

# Try to activate Elementor via WP-CLI (runs activation hooks). If it crashes
# (same WooCommerce/Elementor CLI issue), fall back to direct database activation
# for all remaining plugins and the theme.
WP_CLI_OK=0
if wp plugin activate elementor 2>/dev/null; then
    WP_CLI_OK=1
    echo "==> Activating remaining plugins via WP-CLI"
    wp plugin activate contact-form-7 2>/dev/null || true
    wp plugin activate one-click-demo-import 2>/dev/null || true
    wp plugin activate mailchimp-for-wp 2>/dev/null || true
else
    echo "==> WP-CLI crashes with both WooCommerce and Elementor active"
    echo "==> Activating remaining plugins via database"
    SERIALIZED=$("${COMPOSE[@]}" exec -T web php -r 'echo serialize(["woocommerce/woocommerce.php","elementor/elementor.php","contact-form-7/contact-form-7.php","one-click-demo-import/one-click-demo-import.php","mailchimp-for-wp/mailchimp-for-wp.php"]);' 2>/dev/null)
    "${COMPOSE[@]}" exec db mysql -ubookora -pbookora bookora -e "UPDATE wp_options SET option_value='$SERIALIZED' WHERE option_name='active_plugins'"
fi

# --- Activate the theme ---
echo "==> Activating theme"
if [ "$WP_CLI_OK" = "1" ]; then
    if ! wp theme activate bookora 2>/dev/null; then
        echo "Theme activation via WP-CLI failed — using database"
        "${COMPOSE[@]}" exec db mysql -ubookora -pbookora bookora -e "UPDATE wp_options SET option_value='bookora' WHERE option_name IN ('template','stylesheet')"
    fi
else
    "${COMPOSE[@]}" exec db mysql -ubookora -pbookora bookora -e "UPDATE wp_options SET option_value='bookora' WHERE option_name IN ('template','stylesheet')"
fi

# --- Clean up and hand wp-content to the web server user (www-data) ---
echo "==> Finalizing permissions"
rm -f "$WP_CLI_TMP"
"${CONTAINER_RUNTIME[@]}" exec "$CONTAINER_NAME" rm -f "$WP_CLI_CONTAINER"
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
