<?php
/**
 * Thème Selah : réglages, styles et liens vers l'application.
 *
 * @package Selah
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SELAH_VERSION', '1.0.0' );

/**
 * Adresse par défaut de l'application Selah.
 */
define( 'SELAH_URL_APP_DEFAUT', 'https://selah-ebon.vercel.app/' );

/**
 * Lien réservé : tout lien dont l'adresse est « #selah-app » pointe vers
 * l'application. On le met dans les boutons et les menus depuis l'éditeur.
 */
define( 'SELAH_LIEN_APP', '#selah-app' );

require_once get_template_directory() . '/inc/installation.php';

/**
 * Adresse de l'application, réglable dans Réglages › Selah (extension Selah Core).
 *
 * @return string
 */
function selah_url_app() {
	$url = get_option( 'selah_app_url', '' );
	if ( ! $url ) {
		$url = SELAH_URL_APP_DEFAUT;
	}

	/**
	 * Filtre l'adresse de l'application Selah.
	 *
	 * @param string $url Adresse de l'application.
	 */
	return apply_filters( 'selah_url_app', $url );
}

/**
 * Réglages du thème.
 */
function selah_reglages() {
	load_theme_textdomain( 'selah', get_template_directory() . '/languages' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', 'selah_reglages' );

/**
 * Feuille de style du thème.
 */
function selah_styles() {
	wp_enqueue_style( 'selah', get_stylesheet_uri(), array(), SELAH_VERSION );
}
add_action( 'wp_enqueue_scripts', 'selah_styles' );

/**
 * Catégorie de compositions « Selah » dans l'éditeur.
 */
function selah_categories_compositions() {
	register_block_pattern_category(
		'selah',
		array(
			'label'       => __( 'Selah', 'selah' ),
			'description' => __( 'Sections de la vitrine Selah.', 'selah' ),
		)
	);
	register_block_pattern_category(
		'selah-pages',
		array(
			'label'       => __( 'Selah — pages', 'selah' ),
			'description' => __( 'Pages complètes prêtes à l’emploi.', 'selah' ),
		)
	);
}
add_action( 'init', 'selah_categories_compositions' );

/**
 * Remplace le lien réservé « #selah-app » par l'adresse de l'application.
 *
 * @param string $contenu Rendu HTML du bloc.
 * @return string
 */
function selah_lien_vers_app( $contenu ) {
	if ( false === strpos( $contenu, SELAH_LIEN_APP ) ) {
		return $contenu;
	}

	return str_replace(
		array( 'href="' . SELAH_LIEN_APP . '"', "href='" . SELAH_LIEN_APP . "'" ),
		'href="' . esc_url( selah_url_app() ) . '"',
		$contenu
	);
}
add_filter( 'render_block', 'selah_lien_vers_app' );

/**
 * Les modèles exécutent les codes courts avant de déplier les compositions :
 * le formulaire placé dans une composition resterait sous forme de texte brut.
 *
 * @param string $contenu Rendu du bloc « Code court ».
 * @return string
 */
function selah_formulaire_dans_les_modeles( $contenu ) {
	if ( ! has_shortcode( $contenu, 'selah_demande_acces' ) ) {
		return $contenu;
	}
	// Le bloc passe son contenu dans wpautop() : on retire le <p> autour du formulaire.
	return do_shortcode( shortcode_unautop( trim( $contenu ) ) );
}
add_filter( 'render_block_core/shortcode', 'selah_formulaire_dans_les_modeles' );

/**
 * Icône du site par défaut tant qu'aucune n'est choisie dans l'administration.
 */
function selah_icone_par_defaut() {
	if ( has_site_icon() ) {
		return;
	}
	printf(
		'<link rel="icon" href="%s" type="image/svg+xml" />' . "\n",
		esc_url( get_theme_file_uri( 'assets/images/icone.svg' ) )
	);
	echo '<meta name="theme-color" content="#0b0b0c" />' . "\n";
}
add_action( 'wp_head', 'selah_icone_par_defaut', 5 );

/**
 * Formulaire de secours si l'extension Selah Core n'est pas active :
 * on renvoie vers l'application plutôt que d'afficher un code brut.
 */
function selah_formulaire_de_secours() {
	if ( shortcode_exists( 'selah_demande_acces' ) ) {
		return;
	}

	add_shortcode(
		'selah_demande_acces',
		static function () {
			return sprintf(
				'<div class="wp-block-buttons"><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="%s">%s</a></div></div>',
				esc_url( selah_url_app() ),
				esc_html__( 'Ouvrir Selah', 'selah' )
			);
		}
	);
}
add_action( 'init', 'selah_formulaire_de_secours', 99 );
