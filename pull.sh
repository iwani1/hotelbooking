#!/usr/bin/env bash
# SPDX-License-Identifier: GPL-2.0-or-later
# Update the current branch only; never switch branches.
set -euo pipefail
cd "$(git rev-parse --show-toplevel)"
branch="$(git symbolic-ref --quiet --short HEAD)" || { echo 'Detached HEAD; choose a branch first.' >&2; exit 1; }
if [ -n "$(git status --porcelain)" ]; then
    echo 'Working tree is not clean. Commit or stash changes first.' >&2; exit 1
fi
git rev-parse --abbrev-ref --symbolic-full-name '@{upstream}' >/dev/null 2>&1 || { echo "No upstream is configured for $branch." >&2; exit 1; }
git pull --ff-only
echo "Updated $branch."
