#!/usr/bin/env bash
#
# Pull the latest Bookora work on your local machine.
#
# Run from anywhere inside the hotelbooking repo:
#   bash pull.sh
#
set -euo pipefail

REPO_DIR="$(git rev-parse --show-toplevel 2>/dev/null || true)"
if [ -z "$REPO_DIR" ]; then
    echo "Run this from inside the hotelbooking repo." >&2
    exit 1
fi
cd "$REPO_DIR"

BRANCH="arena/b83f1ab3-hotelbooking"

# 1. Fetch the latest refs from the remote.
git fetch origin

# 2. Make sure the arena branch exists locally (creates a tracking branch
#    automatically if it only exists on the remote).
if ! git show-ref --verify --quiet "refs/heads/$BRANCH"; then
    git checkout "$BRANCH"
fi

# 3. Switch to it if you're on another branch.
if [ "$(git rev-parse --abbrev-ref HEAD)" != "$BRANCH" ]; then
    git checkout "$BRANCH"
fi

# 4. Fast-forward to the latest remote state (fails loudly if it would
#    overwrite local work — nothing is lost).
git pull --ff-only origin "$BRANCH"

echo
echo "Up to date on $BRANCH:"
git log --oneline -1
