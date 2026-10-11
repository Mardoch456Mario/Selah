<?php
/**
 * Plugin Name:       Selah Core
 * Plugin URI:        https://selah-ebon.vercel.app/
 * Description:       Demandes d’accès à la démonstration Selah : formulaire, suivi dans l’administration, notification par e-mail et export CSV. Règle aussi l’adresse de l’application.
 * Version:           2.0.0
 * Requires at least: 6.6
 * Requires PHP:      7.4
 * Author:            Équipe Selah
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       selah-core
 *
 * @package SelahCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SELAH_CORE_VERSION', '2.0.0' );
define( 'SELAH_CORE_FICHIER', __FILE__ );
define( 'SELAH_CORE_URL_APP_DEFAUT', 'https://selah-ebon.vercel.app/' );

require_once __DIR__ . '/includes/reglages.php';
require_once __DIR__ . '/includes/demandes.php';
require_once __DIR__ . '/includes/formulaire.php';
require_once __DIR__ . '/includes/confidentialite.php';
