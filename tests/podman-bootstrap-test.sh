#!/usr/bin/env bash
# SPDX-License-Identifier: GPL-2.0-or-later
# Mocked bootstrap tests; does not run Podman or WordPress.
set -euo pipefail
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT
mkdir -p "$TMP/project/scripts" "$TMP/bin"
cp "$ROOT/scripts/run-podman.sh" "$TMP/project/scripts/"
export TEST_LOG="$TMP/log"
cat > "$TMP/bin/podman" <<'MOCK'
#!/usr/bin/env bash
printf 'podman %s\n' "$*" >> "$TEST_LOG"
MOCK
cat > "$TMP/bin/curl" <<'MOCK'
#!/usr/bin/env bash
if [ "${FAIL_API:-0}" = 1 ]; then exit 1; fi
printf '{"version":"99.0"}'
MOCK
cat > "$TMP/project/install.sh" <<'MOCK'
#!/usr/bin/env bash
printf 'woo=%s elementor=%s runtime=%s\n' "$WP_WOOCOMMERCE_VERSION" "$WP_ELEMENTOR_VERSION" "$BOOKORA_RUNTIME" >> "$TEST_LOG"
MOCK
chmod +x "$TMP/bin/"*
export PATH="$TMP/bin:$PATH" WP_ADMIN_PASSWORD='test-password-123'
unset WP_WOOCOMMERCE_VERSION WP_ELEMENTOR_VERSION WP_CONTACT_FORM_7_VERSION WP_OCDI_VERSION WP_MAILCHIMP_VERSION || true
bash "$TMP/project/scripts/run-podman.sh" >/dev/null
grep -q 'woo=10.6.2 elementor=99.0 runtime=podman' "$TEST_LOG"
[ "$(stat -c '%a' "$TMP/project/.env")" = 600 ]
: > "$TEST_LOG"
WP_WOOCOMMERCE_VERSION=10.5.2 bash "$TMP/project/scripts/run-podman.sh" >/dev/null
grep -q 'woo=10.5.2 ' "$TEST_LOG"
: > "$TEST_LOG"
if FAIL_API=1 bash "$TMP/project/scripts/run-podman.sh" >/dev/null 2>&1; then
  echo 'Bootstrap did not stop on plugin API failure' >&2; exit 1
fi
if grep -q 'podman compose up' "$TEST_LOG"; then
  echo 'Bootstrap started containers despite API failure' >&2; exit 1
fi
echo 'Podman bootstrap version selection tests passed.'
