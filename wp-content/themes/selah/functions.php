<?php
/**
 * Thème Selah : réglages, styles et liens vers l'application.
 *
 * @package Selah
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SELAH_VERSION', '2.0.0' );

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
	// Les pages ont un résumé (affiché dans la recherche) : il doit rester modifiable.
	add_post_type_support( 'page', 'excerpt' );
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
 * Style de bouton « Verre » (translucide sur les images).
 */
function selah_styles_de_blocs() {
	register_block_style(
		'core/button',
		array(
			'name'  => 'verre',
			'label' => __( 'Verre', 'selah' ),
		)
	);
}
add_action( 'init', 'selah_styles_de_blocs' );

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
 * Signale la page en cours dans le menu (les liens personnalisés n'ont pas
 * cette information d'origine).
 *
 * @param string $contenu Rendu du lien de navigation.
 * @return string
 */
function selah_lien_de_la_page_courante( $contenu ) {
	if ( is_admin() || ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $contenu;
	}

	$balises = new WP_HTML_Tag_Processor( $contenu );
	if ( ! $balises->next_tag( 'a' ) ) {
		return $contenu;
	}

	$adresse = $balises->get_attribute( 'href' );
	if ( ! is_string( $adresse ) || '' === $adresse || false !== strpos( $adresse, '#' ) ) {
		return $contenu;
	}

	$hote_lien = wp_parse_url( $adresse, PHP_URL_HOST );
	$hote_site = wp_parse_url( home_url(), PHP_URL_HOST );
	if ( $hote_lien && $hote_lien !== $hote_site ) {
		return $contenu;
	}

	$chemin_lien  = untrailingslashit( (string) wp_parse_url( $adresse, PHP_URL_PATH ) );
	$chemin_page  = untrailingslashit( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '', PHP_URL_PATH ) );
	$chemin_index = untrailingslashit( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ) );
	if ( $chemin_lien === $chemin_index || $chemin_lien !== $chemin_page ) {
		return $contenu;
	}

	$balises->set_attribute( 'aria-current', 'page' );
	return $balises->get_updated_html();
}
add_filter( 'render_block_core/navigation-link', 'selah_lien_de_la_page_courante' );

/**
 * Les titres d'affiche coupent leurs lignes avec <br> : sans espace, les
 * résumés automatiques colleraient les mots (« Le rideause lève »).
 * Ne s'applique que pendant le calcul d'un résumé.
 *
 * @param string $contenu Contenu rendu.
 * @return string
 */
function selah_espaces_dans_les_resumes( $contenu ) {
	if ( ! doing_filter( 'get_the_excerpt' ) ) {
		return $contenu;
	}
	return preg_replace( '#<br\s*/?>#i', ' ', $contenu );
}
add_filter( 'the_content', 'selah_espaces_dans_les_resumes', 99 );

/**
 * Rangées défilantes : atteignables au clavier (flèches) et nommées par leur
 * titre pour les lecteurs d'écran. Le bloc Groupe n'enregistre pas ces attributs.
 *
 * @param string $contenu Rendu du groupe.
 * @param array  $bloc    Bloc analysé.
 * @return string
 */
function selah_rangees_accessibles( $contenu, $bloc ) {
	static $numero = 0;

	$classes = isset( $bloc['attrs']['className'] ) ? $bloc['attrs']['className'] : '';
	if ( ! preg_match( '/(^|\s)selah-rangee(\s|$)/', $classes ) || ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $contenu;
	}

	$balises = new WP_HTML_Tag_Processor( $contenu );
	if ( ! $balises->next_tag( 'h2' ) ) {
		return $contenu;
	}

	$titre = $balises->get_attribute( 'id' );
	if ( ! is_string( $titre ) || '' === $titre ) {
		$titre = 'selah-rangee-' . ( ++$numero );
		$balises->set_attribute( 'id', $titre );
	}

	if ( ! $balises->next_tag( array( 'class_name' => 'selah-defile' ) ) ) {
		return $contenu;
	}
	$balises->set_attribute( 'tabindex', '0' );
	$balises->set_attribute( 'role', 'region' );
	$balises->set_attribute( 'aria-labelledby', $titre );

	return $balises->get_updated_html();
}
add_filter( 'render_block_core/group', 'selah_rangees_accessibles', 10, 2 );

/**
 * Au clavier, un élément qui reçoit le focus sous l'en-tête collant est ramené
 * juste en dessous. Le navigateur ne fait pas défiler un élément déjà dans la
 * fenêtre, même caché par l'en-tête ; un scroll-padding sur html ferait, lui,
 * sauter la page à la fermeture du menu.
 */
function selah_focus_hors_entete() {
	wp_print_inline_script_tag(
		"document.addEventListener('focusin',function(e){var h=document.querySelector('.selah-entete'),t=e.target;if(!h||h.contains(t)||!t.matches||!t.matches(':focus-visible'))return;var b=h.getBoundingClientRect().bottom,r=t.getBoundingClientRect();if(r.top<b)window.scrollBy(0,r.top-b-16);});"
	);
}
add_action( 'wp_footer', 'selah_focus_hors_entete' );

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
