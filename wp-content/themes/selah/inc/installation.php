<?php
/**
 * Mise en place du site : crée les pages à partir des compositions du thème.
 *
 * Deux façons de la lancer : le bouton « Créer les pages du site » affiché
 * après l'activation du thème, ou WP-CLI (scripts/configurer-site.php).
 *
 * @package Selah
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pages du site : identifiant => titre, composition, modèle, résumé.
 * Le résumé s'affiche dans les résultats de recherche.
 *
 * @return array<string, array{0: string, 1: string, 2: string, 3: string}>
 */
function selah_pages_du_site() {
	return array(
		'accueil'              => array( 'Accueil', '', '', 'Ton personnage essaie pour toi les pièces des créateurs de Cotonou. Tu regardes, tu demandes l’avis de tes amis, tu gardes ce qui te va.' ),
		'journal'              => array( 'Journal', '', '', 'Les nouvelles de Selah.' ),
		'createurs'            => array( 'Créateurs', 'selah/page-createurs', 'page-sans-titre', 'Sur Selah, vos pièces ne dorment pas sur un cintre : chacun les voit portées par son propre personnage, puis les montre à ses amis.' ),
		'a-propos'             => array( 'À propos', 'selah/page-a-propos', 'page-sans-titre', 'Selah est un showroom de mode. Ton personnage essaie pour toi les pièces des créateurs de Cotonou, tes amis donnent leur avis, tu gardes ce qui te va.' ),
		'questions-frequentes' => array( 'Questions fréquentes', 'selah/page-questions', 'page-sans-titre', 'Tout ce qu’il faut savoir pour entrer dans le showroom Selah.' ),
		'demande-acces'        => array( 'Demander un accès', 'selah/page-demande-acces', 'page-sans-titre', 'Selah ouvre ses portes petit à petit. Laisse ta demande : l’équipe revient vers toi avec un code.' ),
		'confidentialite'      => array( 'Confidentialité', 'selah/page-confidentialite', 'page-sans-titre', 'Ce que nous faisons des informations que tu nous confies sur ce site.' ),
	);
}

/**
 * Contenu d'une composition, compositions imbriquées comprises.
 *
 * @param string $slug Identifiant de la composition.
 * @return string
 */
function selah_contenu_composition( $slug ) {
	$composition = WP_Block_Patterns_Registry::get_instance()->get_registered( $slug );
	if ( ! $composition ) {
		return '';
	}

	return preg_replace_callback(
		'#<!-- wp:pattern \{"slug":"([^"]+)"\} /-->#',
		static function ( $correspondance ) {
			return selah_contenu_composition( $correspondance[1] );
		},
		$composition['content']
	);
}

/**
 * Crée les pages manquantes et règle l'accueil, le journal et la confidentialité.
 * Peut être relancée : une page existante n'est jamais modifiée.
 *
 * @return string[] Compte rendu, une ligne par action.
 */
function selah_installer_pages() {
	$journal = array();
	$ids     = array();

	foreach ( selah_pages_du_site() as $slug => list( $titre, $composition, $modele, $resume ) ) {
		$existante = get_page_by_path( $slug );
		if ( $existante && 'trash' !== $existante->post_status ) {
			$ids[ $slug ] = $existante->ID;
			$journal[]    = sprintf( 'Page déjà présente : %s', $titre );
			continue;
		}

		$nouvelle = wp_insert_post(
			array(
				'post_type'     => 'page',
				'post_status'   => 'publish',
				'post_title'    => $titre,
				'post_name'     => $slug,
				'post_content'  => $composition ? selah_contenu_composition( $composition ) : '',
				'post_excerpt'  => $resume,
				'page_template' => $modele,
			),
			true
		);

		if ( is_wp_error( $nouvelle ) ) {
			$journal[] = sprintf( 'Impossible de créer « %s » : %s', $titre, $nouvelle->get_error_message() );
			continue;
		}

		$ids[ $slug ] = $nouvelle;
		$journal[]    = sprintf( 'Page créée : %s', $titre );
	}

	// L'accueil est dessiné par le modèle « Page d'accueil » du thème ; le journal liste les articles.
	if ( isset( $ids['accueil'], $ids['journal'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['accueil'] );
		update_option( 'page_for_posts', $ids['journal'] );
	}
	// Page de confidentialité : la nôtre, sauf si une autre est déjà publiée.
	if ( isset( $ids['confidentialite'] ) && 'publish' !== get_post_status( (int) get_option( 'wp_page_for_privacy_policy' ) ) ) {
		update_option( 'wp_page_for_privacy_policy', $ids['confidentialite'] );
	}

	// Slogan : seulement s'il s'agit encore de celui de WordPress.
	$slogan = get_option( 'blogdescription' );
	if ( '' === $slogan || in_array( $slogan, array( 'Just another WordPress site', 'Un site utilisant WordPress' ), true ) ) {
		update_option( 'blogdescription', 'Essaie tout, garde ce qui te va' );
	}

	// Contenus d'exemple de WordPress, seulement s'ils n'ont jamais été modifiés.
	$exemples = array_merge(
		get_posts(
			array(
				'post_type'      => 'post',
				'post_name__in'  => array( 'hello-world', 'bonjour-tout-le-monde' ),
				'posts_per_page' => -1,
			)
		),
		get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => array( 'publish', 'draft' ),
				'post_name__in'  => array( 'sample-page', 'page-d-exemple', 'privacy-policy', 'politique-de-confidentialite' ),
				'posts_per_page' => -1,
			)
		)
	);
	foreach ( $exemples as $exemple ) {
		if ( $exemple->post_date === $exemple->post_modified && (int) get_option( 'wp_page_for_privacy_policy' ) !== (int) $exemple->ID ) {
			wp_delete_post( $exemple->ID, true );
			$journal[] = sprintf( 'Contenu d’exemple supprimé : %s', $exemple->post_title );
		}
	}

	// Catégorie par défaut : « Uncategorized » reste en anglais si la langue a été changée après l'installation.
	$categorie = get_term( (int) get_option( 'default_category' ), 'category' );
	if ( $categorie instanceof WP_Term && in_array( $categorie->name, array( 'Uncategorized', 'Non classé' ), true ) ) {
		wp_update_term(
			$categorie->term_id,
			'category',
			array(
				'name' => 'Actualités',
				'slug' => 'actualites',
			)
		);
		$journal[] = 'Catégorie par défaut renommée « Actualités ».';
	}

	// Adresses lisibles (/createurs/ plutôt que /?page_id=12).
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
		$journal[] = 'Adresses lisibles activées.';
	}
	flush_rewrite_rules();

	update_option( 'selah_pages_installees', SELAH_VERSION );

	return $journal;
}

/**
 * Invitation à créer les pages, tant qu'elles n'existent pas.
 */
function selah_avis_installation() {
	if ( ! current_user_can( 'manage_options' ) || get_option( 'selah_pages_installees' ) ) {
		return;
	}

	$ecran = get_current_screen();
	if ( $ecran && in_array( $ecran->base, array( 'post', 'site-editor' ), true ) ) {
		return;
	}

	$creer   = wp_nonce_url( admin_url( 'admin-post.php?action=selah_installer' ), 'selah_installer' );
	$masquer = wp_nonce_url( admin_url( 'admin-post.php?action=selah_installer&masquer=1' ), 'selah_installer' );
	?>
	<div class="notice notice-info">
		<p><strong><?php esc_html_e( 'Bienvenue sur le thème Selah.', 'selah' ); ?></strong>
		<?php esc_html_e( 'Créez en un clic les pages du site (Créateurs, À propos, Questions fréquentes, Demander un accès, Confidentialité, Journal) et la page d’accueil.', 'selah' ); ?></p>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( $creer ); ?>"><?php esc_html_e( 'Créer les pages du site', 'selah' ); ?></a>
			<a class="button-link" href="<?php echo esc_url( $masquer ); ?>"><?php esc_html_e( 'Plus tard, masquer', 'selah' ); ?></a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'selah_avis_installation' );

/**
 * Traite le bouton « Créer les pages du site ».
 */
function selah_action_installer() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Accès refusé.', 'selah' ), 403 );
	}
	check_admin_referer( 'selah_installer' );

	if ( ! empty( $_GET['masquer'] ) ) {
		update_option( 'selah_pages_installees', 'masque' );
		wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url() );
		exit;
	}

	set_transient( 'selah_installation_' . get_current_user_id(), selah_installer_pages(), 5 * MINUTE_IN_SECONDS );
	wp_safe_redirect( admin_url( 'edit.php?post_type=page&selah_installation=1' ) );
	exit;
}
add_action( 'admin_post_selah_installer', 'selah_action_installer' );

/**
 * Compte rendu après la création des pages.
 */
function selah_avis_compte_rendu() {
	if ( empty( $_GET['selah_installation'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- simple affichage.
		return;
	}
	$journal = get_transient( 'selah_installation_' . get_current_user_id() );
	if ( ! is_array( $journal ) ) {
		return;
	}
	delete_transient( 'selah_installation_' . get_current_user_id() );
	?>
	<div class="notice notice-success is-dismissible">
		<p><strong><?php esc_html_e( 'Le site Selah est en place.', 'selah' ); ?></strong>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Voir le site', 'selah' ); ?></a></p>
		<ul style="list-style:disc;padding-left:1.5em">
			<?php foreach ( $journal as $ligne ) : ?>
				<li><?php echo esc_html( $ligne ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}
add_action( 'admin_notices', 'selah_avis_compte_rendu' );
