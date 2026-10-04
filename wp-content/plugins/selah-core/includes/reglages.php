<?php
/**
 * Réglages › Selah : adresse de l'application et e-mail de notification.
 *
 * @package SelahCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adresse e-mail qui reçoit les nouvelles demandes.
 *
 * @return string
 */
function selah_core_email_notification() {
	$email = get_option( 'selah_email_notification', '' );
	return is_email( $email ) ? $email : get_option( 'admin_email' );
}

/**
 * Enregistre les réglages.
 */
function selah_core_enregistrer_reglages() {
	register_setting(
		'selah',
		'selah_app_url',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'selah_core_nettoyer_url_app',
			'default'           => '',
		)
	);

	register_setting(
		'selah',
		'selah_email_notification',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'selah_core_nettoyer_email',
			'default'           => '',
		)
	);

	add_settings_section( 'selah_general', '', '__return_false', 'selah' );

	add_settings_field(
		'selah_app_url',
		__( 'Adresse de l’application', 'selah-core' ),
		'selah_core_champ_url_app',
		'selah',
		'selah_general',
		array( 'label_for' => 'selah_app_url' )
	);

	add_settings_field(
		'selah_email_notification',
		__( 'E-mail des demandes', 'selah-core' ),
		'selah_core_champ_email',
		'selah',
		'selah_general',
		array( 'label_for' => 'selah_email_notification' )
	);
}
add_action( 'admin_init', 'selah_core_enregistrer_reglages' );

/**
 * @param string $valeur Adresse saisie.
 * @return string
 */
function selah_core_nettoyer_url_app( $valeur ) {
	$valeur = esc_url_raw( trim( (string) $valeur ), array( 'https', 'http' ) );
	return $valeur ? $valeur : '';
}

/**
 * @param string $valeur E-mail saisi.
 * @return string
 */
function selah_core_nettoyer_email( $valeur ) {
	$valeur = sanitize_email( (string) $valeur );
	if ( $valeur && ! is_email( $valeur ) ) {
		add_settings_error( 'selah_email_notification', 'email', __( 'Cette adresse e-mail n’est pas valide.', 'selah-core' ) );
		return get_option( 'selah_email_notification', '' );
	}
	return $valeur;
}

/**
 * Champ « Adresse de l'application ».
 */
function selah_core_champ_url_app() {
	printf(
		'<input type="url" class="regular-text code" id="selah_app_url" name="selah_app_url" value="%s" placeholder="%s" />',
		esc_attr( get_option( 'selah_app_url', '' ) ),
		esc_attr( SELAH_CORE_URL_APP_DEFAUT )
	);
	echo '<p class="description">';
	printf(
		/* translators: %s: lien réservé #selah-app */
		esc_html__( 'Tous les liens « %s » du site pointent vers cette adresse. Laissez vide pour utiliser l’adresse par défaut.', 'selah-core' ),
		'<code>#selah-app</code>'
	);
	echo '</p>';
}

/**
 * Champ « E-mail des demandes ».
 */
function selah_core_champ_email() {
	printf(
		'<input type="email" class="regular-text" id="selah_email_notification" name="selah_email_notification" value="%s" placeholder="%s" />',
		esc_attr( get_option( 'selah_email_notification', '' ) ),
		esc_attr( get_option( 'admin_email' ) )
	);
	echo '<p class="description">' . esc_html__( 'Chaque nouvelle demande d’accès y est envoyée. Laissez vide pour utiliser l’e-mail d’administration.', 'selah-core' ) . '</p>';
}

/**
 * Page Réglages › Selah.
 */
function selah_core_menu_reglages() {
	add_options_page(
		__( 'Réglages Selah', 'selah-core' ),
		__( 'Selah', 'selah-core' ),
		'manage_options',
		'selah',
		'selah_core_page_reglages'
	);
}
add_action( 'admin_menu', 'selah_core_menu_reglages' );

/**
 * Affiche la page de réglages.
 */
function selah_core_page_reglages() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Réglages Selah', 'selah-core' ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'selah' );
			do_settings_sections( 'selah' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Lien « Réglages » dans la liste des extensions.
 *
 * @param string[] $liens Liens de l'extension.
 * @return string[]
 */
function selah_core_lien_reglages( $liens ) {
	array_unshift(
		$liens,
		sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'options-general.php?page=selah' ) ), esc_html__( 'Réglages', 'selah-core' ) )
	);
	return $liens;
}
add_filter( 'plugin_action_links_' . plugin_basename( SELAH_CORE_FICHIER ), 'selah_core_lien_reglages' );
