#!/usr/bin/env bash
# SPDX-License-Identifier: GPL-2.0-or-later
# Mocked CLI smoke test: checks control flow, not WordPress/runtime integration.
set -euo pipefail
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT
mkdir -p "$TMP/bin"
export MOCK_LOG="$TMP/log" MOCK_STATE="$TMP/state"
cat > "$TMP/bin/docker" <<'MOCK'
#!/usr/bin/env bash
set -euo pipefail
printf '%s %s\n' "$(basename "$0")" "$*" >> "$MOCK_LOG"
if [ "$1" = compose ]; then
  shift
  case "$1" in
    version) exit 0;;
    ps)
      [ "$#" = 2 ] && [ "$2" = -q ] || { echo 'Unexpected ps arguments' >&2; exit 1; }
      printf 'db-id\nweb-id\n'; exit 0;;
    exec)
      shift 2 # exec -T
      service="$1"; shift
      [ "$service" = web ] || exit 0
      [ "$1" = php ] || exit 0
      shift 5 # php phar --allow-root --path --skip-plugins
      [ "$1" = --skip-themes ] && shift
      case "$1 $2" in
        'core is-installed') [ "${MOCK_FRESH:-0}" != 1 ]; exit $?;;
        'core install') exit 0;;
        'plugin is-installed') exit 0;;
        'plugin is-active')
          case "$3" in bookora-plugin|bookora-reception) [ -f "$MOCK_STATE-$3" ];; *) true;; esac
          exit $?;;
        'plugin activate')
          case "$3" in bookora-plugin|bookora-reception) touch "$MOCK_STATE-$3";; esac
          exit 0;;
        'theme activate') exit 0;;
        'option get') echo bookora; exit 0;;
        'plugin list') exit 0;;
        *) echo "Unexpected WP command: $*" >&2; exit 1;;
      esac;;
  esac
fi
if [ "$1" = inspect ]; then
  case "${4:-}" in
    db-id) echo 'db true';;
    web-id) echo 'web true';;
    *) echo 'Unknown container' >&2; exit 1;;
  esac
fi
if [ "$1" = port ]; then echo '127.0.0.1:8081'; fi
if [ "$1" = exec ] && [ "${3:-}" = test ] && [ "${5:-}" = /var/www/html/wp-content/plugins/bookora-reception ] && [ "${MOCK_EXISTING_RECEPTION:-0}" != 1 ]; then exit 1; fi
if [ "$1" = exec ] && [ "${3:-}" = mktemp ]; then echo /tmp/bookora-test; fi
exit 0
MOCK
cat > "$TMP/bin/curl" <<'MOCK'
#!/usr/bin/env bash
while [ "$#" -gt 0 ]; do if [ "$1" = -o ]; then echo mock > "$2"; exit; fi; shift; done
exit 1
MOCK
cat > "$TMP/bin/sha256sum" <<'MOCK'
#!/usr/bin/env bash
cat >/dev/null
MOCK
chmod +x "$TMP/bin/"*
PATH="$TMP/bin:$PATH" bash "$ROOT/install.sh" >/dev/null
[ -f "$MOCK_STATE-bookora-plugin" ] || { echo 'Companion plugin was not activated' >&2; exit 1; }
[ -f "$MOCK_STATE-bookora-reception" ] || { echo 'Reception plugin was not activated' >&2; exit 1; }
grep -q 'plugin activate bookora-plugin' "$MOCK_LOG"
grep -q 'plugin activate bookora-reception' "$MOCK_LOG"
grep -q 'cp .*plugin/bookora-reception web-id:/tmp/bookora-test/bookora-reception' "$MOCK_LOG"
grep -q 'theme activate bookora' "$MOCK_LOG"
if grep -Eq 'mysql .*UPDATE|rm -rf /var/www/html/wp-content' "$MOCK_LOG"; then
  echo 'Unexpected destructive fallback' >&2; exit 1
fi
# With both CLIs on PATH, an explicit Podman selection must not invoke Docker.
cp "$TMP/bin/docker" "$TMP/bin/podman"
: > "$MOCK_LOG"
rm -f "$MOCK_STATE-bookora-plugin" "$MOCK_STATE-bookora-reception"
PATH="$TMP/bin:$PATH" BOOKORA_RUNTIME=podman bash "$ROOT/install.sh" >/dev/null
grep -q '^podman compose ' "$MOCK_LOG"
if grep -q '^docker ' "$MOCK_LOG"; then
  echo 'Explicit Podman selection unexpectedly invoked Docker' >&2; exit 1
fi
# Fresh WordPress must receive the actual published port, not a hardcoded URL.
: > "$MOCK_LOG"
rm -f "$MOCK_STATE-bookora-plugin" "$MOCK_STATE-bookora-reception"
PATH="$TMP/bin:$PATH" BOOKORA_RUNTIME=podman MOCK_FRESH=1 WP_ADMIN_PASSWORD='test-password-123' bash "$ROOT/install.sh" > "$TMP/output"
grep -q 'core install --url=http://localhost:8081' "$MOCK_LOG"
grep -q 'Open http://localhost:8081' "$TMP/output"
# Targeted replacement must not replace the existing theme or companion plugin.
: > "$MOCK_LOG"
PATH="$TMP/bin:$PATH" BOOKORA_RUNTIME=podman MOCK_EXISTING_RECEPTION=1 BOOKORA_REPLACE_RECEPTION=1 bash "$ROOT/install.sh" >/dev/null
grep -q 'cp .*plugin/bookora-reception web-id:/tmp/bookora-test/bookora-reception' "$MOCK_LOG"
if grep -Eq 'cp .*/(theme/bookora|plugin/bookora-plugin) web-id:' "$MOCK_LOG"; then
  echo 'Targeted replacement unexpectedly copied theme or companion plugin' >&2; exit 1
fi
# Existing reception plugin is preserved without an explicit switch.
: > "$MOCK_LOG"
PATH="$TMP/bin:$PATH" BOOKORA_RUNTIME=podman MOCK_EXISTING_RECEPTION=1 bash "$ROOT/install.sh" >/dev/null
if grep -q 'cp .*plugin/bookora-reception web-id:' "$MOCK_LOG"; then
  echo 'Existing reception plugin was unexpectedly replaced' >&2; exit 1
fi
echo 'Installer mock smoke test passed (Docker, Podman, and a non-default port).'
