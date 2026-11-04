#!/usr/bin/env bash
# SPDX-License-Identifier: GPL-2.0-or-later
# Install the checked-in Bookora source into the local Compose stack.
set -euo pipefail
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT"

if command -v docker >/dev/null && docker compose version >/dev/null 2>&1; then
    COMPOSE=(docker compose); RUNTIME=(docker)
elif command -v podman >/dev/null && podman compose version >/dev/null 2>&1; then
    COMPOSE=(podman compose); RUNTIME=(podman)
elif command -v podman-compose >/dev/null && command -v podman >/dev/null; then
    COMPOSE=(podman-compose); RUNTIME=(podman)
else
    echo 'Docker Compose or Podman Compose is required.' >&2; exit 1
fi

WEB_ID="$("${COMPOSE[@]}" ps -q web)"
[ -n "$WEB_ID" ] || { echo 'Start the stack first: docker compose up -d' >&2; exit 1; }
"${RUNTIME[@]}" inspect "$WEB_ID" >/dev/null

# A release version and digest are deliberately pinned. Update both together
# after checking the official WP-CLI release, not from an unverified mirror.
WP_CLI_VERSION=2.12.0
WP_CLI_SHA256=ce34ddd838f7351d6759068d09793f26755463b4a4610a5a5c0a97b68220d85c
TMP="$(mktemp -d)"
STAGE=''
cleanup() {
    rm -rf "$TMP"
    if [ -n "$STAGE" ]; then "${RUNTIME[@]}" exec "$WEB_ID" rm -rf "$STAGE" >/dev/null 2>&1 || true; fi
}
trap cleanup EXIT
curl -fL --retry 3 "https://github.com/wp-cli/wp-cli/releases/download/v${WP_CLI_VERSION}/wp-cli-${WP_CLI_VERSION}.phar" -o "$TMP/wp-cli.phar"
printf '%s  %s\n' "$WP_CLI_SHA256" "$TMP/wp-cli.phar" | sha256sum -c -
STAGE="$("${RUNTIME[@]}" exec "$WEB_ID" mktemp -d /tmp/bookora-install.XXXXXX | tr -d '\r')"
"${RUNTIME[@]}" cp "$TMP/wp-cli.phar" "$WEB_ID:$STAGE/wp-cli.phar"
wp() {
    "${COMPOSE[@]}" exec -T web php "$STAGE/wp-cli.phar" --allow-root --path=/var/www/html --skip-plugins --skip-themes "$@"
}

printf 'Waiting for the database...\n'
ready=0
for i in $(seq 1 60); do
    if "${COMPOSE[@]}" exec -T db sh -c 'mysqladmin ping -h 127.0.0.1 -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --silent' >/dev/null 2>&1; then ready=1; break; fi
    sleep 2
done
[ "$ready" = 1 ] || { echo 'Database did not become ready.' >&2; exit 1; }

if ! wp core is-installed; then
    password="${WP_ADMIN_PASSWORD:-}"
    if [ "${#password}" -lt 12 ]; then
        echo 'Set WP_ADMIN_PASSWORD to a unique password of at least 12 characters before installing WordPress.' >&2; exit 1
    fi
    wp core install --url="${WP_URL:-http://localhost:8080}" \
        --title="${WP_TITLE:-Bookora}" --admin_user="${WP_ADMIN_USER:-bookora-admin}" \
        --admin_password="$password" --admin_email="${WP_ADMIN_EMAIL:-admin@example.com}" --skip-email
    unset password
fi

# Existing code is never replaced unless explicitly requested. Back it up first.
install_code() {
    local source="$1" destination="$2" name="$3"
    if "${RUNTIME[@]}" exec "$WEB_ID" test -d "$destination/$name"; then
        if [ "${BOOKORA_REPLACE:-0}" != 1 ]; then
            echo "$name already exists; preserving it (set BOOKORA_REPLACE=1 after backing up to replace)."
            return
        fi
        echo "Replacing $destination/$name; ensure you have backed up local modifications."
    fi
    "${RUNTIME[@]}" cp "$source" "$WEB_ID:$STAGE/$name"
    "${RUNTIME[@]}" exec "$WEB_ID" sh -c 'mkdir -p "$1" && rm -rf "$1/$2" && cp -a "$3/$2" "$1/$2" && chown -R www-data:www-data "$1/$2"' sh "$destination" "$name" "$STAGE"
}
install_code "$ROOT/theme/bookora" /var/www/html/wp-content/themes bookora
install_code "$ROOT/plugin/bookora-plugin" /var/www/html/wp-content/plugins bookora-plugin

install_plugin() {
    local slug="$1" version="$2"
    if ! wp plugin is-installed "$slug"; then
        if [ -z "$version" ]; then
            echo "Set a tested version for $slug before rerunning; see README.md." >&2; exit 1
        fi
        wp plugin install "$slug" --version="$version"
        "${RUNTIME[@]}" exec "$WEB_ID" chown -R www-data:www-data "/var/www/html/wp-content/plugins/$slug"
    fi
    if ! wp plugin is-active "$slug"; then wp plugin activate "$slug"; fi
    wp plugin is-active "$slug" || { echo "Plugin activation failed: $slug" >&2; exit 1; }
}
# Explicit versions are required on a fresh install; existing versions are preserved.
install_plugin woocommerce "${WP_WOOCOMMERCE_VERSION:-}"
install_plugin elementor "${WP_ELEMENTOR_VERSION:-}"
install_plugin contact-form-7 "${WP_CONTACT_FORM_7_VERSION:-}"
install_plugin one-click-demo-import "${WP_OCDI_VERSION:-}"
install_plugin mailchimp-for-wp "${WP_MAILCHIMP_VERSION:-}"
if ! wp plugin is-active bookora-plugin; then wp plugin activate bookora-plugin; fi
wp plugin is-active bookora-plugin || { echo 'Companion plugin activation failed.' >&2; exit 1; }
wp theme activate bookora
[ "$(wp option get stylesheet)" = bookora ] || { echo 'Theme activation failed.' >&2; exit 1; }
"${COMPOSE[@]}" exec -T web sh -c 'mkdir -p /var/www/html/wp-content/uploads && chown www-data:www-data /var/www/html/wp-content/uploads'
echo 'Installation complete. Open http://localhost:8080. Booking requires a separate provider.'
wp plugin list --fields=name,status,version
