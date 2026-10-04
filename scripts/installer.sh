#!/usr/bin/env bash
# Installe et configure le site Selah avec WP-CLI.
#
#   ./scripts/installer.sh                                    # WordPress local (commande « wp »)
#   WP="docker compose run --rm -T cli wp" ./scripts/installer.sh   # avec Docker (voir README)
#
# Variables utiles : SITE_URL, ADMIN_USER, ADMIN_EMAIL, ADMIN_PASSWORD.
set -euo pipefail

WP=${WP:-wp}
DOSSIER=$(cd "$(dirname "$0")" && pwd)
SITE_URL=${SITE_URL:-http://localhost:8080}
ADMIN_USER=${ADMIN_USER:-admin}
ADMIN_EMAIL=${ADMIN_EMAIL:-admin@example.com}

wp_() { $WP "$@"; }

if ! wp_ core is-installed >/dev/null 2>&1; then
	echo "→ Installation de WordPress sur $SITE_URL"
	args=(core install --url="$SITE_URL" --title="Selah" --admin_user="$ADMIN_USER" --admin_email="$ADMIN_EMAIL" --skip-email)
	if [ -n "${ADMIN_PASSWORD:-}" ]; then
		args+=(--admin_password="$ADMIN_PASSWORD")
	fi
	wp_ "${args[@]}"
	# WP-CLI devine parfois mal l'adresse (sous-dossier) : on la fixe.
	wp_ option update home "$SITE_URL"
	wp_ option update siteurl "$SITE_URL"
fi

echo "→ Langue : français"
wp_ language core install fr_FR --activate || echo "  (langue non installée : vérifiez l'accès à internet)"

echo "→ Thème et extension Selah"
wp_ theme activate selah
wp_ plugin activate selah-core

echo "→ Fuseau horaire de Cotonou et formats de date"
wp_ option update timezone_string "Africa/Porto-Novo"
wp_ option update date_format "j F Y"
wp_ option update time_format "H:i"
wp_ option update start_of_week 1

echo "→ Pages, page d'accueil et journal"
wp_ eval-file - < "$DOSSIER/configurer-site.php"

echo "→ Adresses lisibles"
wp_ rewrite structure "/%postname%/"
wp_ rewrite flush

echo
echo "Site Selah prêt : $SITE_URL"
echo "Administration : $SITE_URL/wp-admin/"
