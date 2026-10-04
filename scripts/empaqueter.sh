#!/usr/bin/env bash
# Fabrique les archives à téléverser dans WordPress :
#   dist/selah.zip       → Apparence › Thèmes › Ajouter › Téléverser
#   dist/selah-core.zip  → Extensions › Ajouter › Téléverser
set -euo pipefail

RACINE=$(cd "$(dirname "$0")/.." && pwd)
mkdir -p "$RACINE/dist"
rm -f "$RACINE/dist/selah.zip" "$RACINE/dist/selah-core.zip"

(cd "$RACINE/wp-content/themes" && zip -rq "$RACINE/dist/selah.zip" selah -x '*.DS_Store')
(cd "$RACINE/wp-content/plugins" && zip -rq "$RACINE/dist/selah-core.zip" selah-core -x '*.DS_Store')

ls -lh "$RACINE/dist"
