<?php
/**
 * Crée les pages du site Selah et règle WordPress, en ligne de commande.
 *
 * À lancer avec WP-CLI, thème Selah actif :
 *   wp eval-file scripts/configurer-site.php
 *
 * Fait la même chose que le bouton « Créer les pages du site » de
 * l'administration (voir wp-content/themes/selah/inc/installation.php).
 * Peut être relancé : une page qui existe déjà n'est pas modifiée.
 *
 * @package Selah
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( "À lancer avec WP-CLI : wp eval-file scripts/configurer-site.php\n" );
}

if ( ! function_exists( 'selah_installer_pages' ) ) {
	WP_CLI::error( 'Activez d’abord le thème Selah : wp theme activate selah' );
}

update_option( 'blogname', 'Selah' );
update_option( 'default_comment_status', 'closed' );
update_option( 'default_ping_status', 'closed' );

foreach ( selah_installer_pages() as $ligne ) {
	WP_CLI::log( $ligne );
}

WP_CLI::success( 'Site Selah configuré.' );
