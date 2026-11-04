#!/usr/bin/env bash
# SPDX-License-Identifier: GPL-2.0-or-later
# Rebuild nested distributions while preserving original documentation/licenses.
set -euo pipefail
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PACKAGE="$ROOT/bookora_hotel_booking_wordpress_theme_2026_01_08_10_59_53_utc.zip"
TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT
unzip -q "$PACKAGE" -d "$TMP/package"
[ -d "$ROOT/theme/bookora" ] && [ -d "$ROOT/plugin/bookora-plugin" ]
(cd "$ROOT/theme" && zip -Xqr "$TMP/bookora.zip" bookora)
(cd "$ROOT/plugin" && zip -Xqr "$TMP/bookora-plugin.zip" bookora-plugin)
cp "$TMP/bookora.zip" "$TMP/package/bookora-wp-package/bookora.zip"
cp "$TMP/bookora-plugin.zip" "$TMP/package/bookora-wp-package/plugin/bookora-plugin.zip"
(cd "$TMP/package" && zip -Xqr "$TMP/package.zip" bookora-wp-package)
unzip -tq "$TMP/package.zip" >/dev/null
cp "$TMP/package.zip" "$PACKAGE"
echo 'Package rebuilt.'
