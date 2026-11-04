#!/usr/bin/env bash
# SPDX-License-Identifier: GPL-2.0-or-later
# Bootstrap the local development stack with Podman.
set -euo pipefail
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

for cmd in podman python3 curl sha256sum; do
    command -v "$cmd" >/dev/null 2>&1 || { echo "Missing required command: $cmd" >&2; exit 1; }
done
if podman compose version >/dev/null 2>&1; then
    COMPOSE=(podman compose)
elif command -v podman-compose >/dev/null 2>&1; then
    COMPOSE=(podman-compose)
else
    echo 'Install a Podman Compose provider first.' >&2; exit 1
fi

# Never replace existing passwords: an existing database retains its original credentials.
python3 <<'PY'
import os, secrets
if not os.path.exists('.env'):
    fd = os.open('.env', os.O_WRONLY | os.O_CREAT | os.O_EXCL, 0o600)
    with os.fdopen(fd, 'w') as f:
        f.write('MYSQL_ROOT_PASSWORD=' + secrets.token_hex(24) + '\n')
        f.write('MYSQL_PASSWORD=' + secrets.token_hex(24) + '\n')
    print('Created .env with random local database passwords.')
else:
    print('Using existing .env and database credentials.')
PY

plugin_version() {
    curl -fsSG 'https://api.wordpress.org/plugins/info/1.2/' \
        --data-urlencode 'action=plugin_information' \
        --data-urlencode "request[slug]=$1" |
    python3 -c '
import json, re, sys
v = json.load(sys.stdin)["version"]
if not re.fullmatch(r"[0-9][0-9A-Za-z._+-]*", v):
    sys.exit("Invalid plugin version returned")
print(v)
'
}

# Versions can be overridden for a tested deployment. Defaults are the
# latest published versions, whose compatibility still needs verification.
export WP_WOOCOMMERCE_VERSION="${WP_WOOCOMMERCE_VERSION:-$(plugin_version woocommerce)}"
export WP_ELEMENTOR_VERSION="${WP_ELEMENTOR_VERSION:-$(plugin_version elementor)}"
export WP_CONTACT_FORM_7_VERSION="${WP_CONTACT_FORM_7_VERSION:-$(plugin_version contact-form-7)}"
export WP_OCDI_VERSION="${WP_OCDI_VERSION:-$(plugin_version one-click-demo-import)}"
export WP_MAILCHIMP_VERSION="${WP_MAILCHIMP_VERSION:-$(plugin_version mailchimp-for-wp)}"

if [ -z "${WP_ADMIN_PASSWORD:-}" ]; then
    if [ ! -r /dev/tty ]; then
        echo 'Set WP_ADMIN_PASSWORD or run from an interactive terminal.' >&2; exit 1
    fi
    read -r -s -p 'WordPress admin password for a fresh install (12+ characters): ' WP_ADMIN_PASSWORD </dev/tty
    printf '\n'
fi
export WP_ADMIN_PASSWORD
"${COMPOSE[@]}" up -d
BOOKORA_RUNTIME=podman bash "$ROOT/install.sh"
unset WP_ADMIN_PASSWORD
printf '\nOpen http://localhost:8080\n'
printf 'For a fresh install, the admin username is bookora-admin.\n'
printf 'Existing WordPress installations retain their current admin password.\n'
