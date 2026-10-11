<?php
/**
 * Les demandes d'accès : type de contenu privé, écran de suivi et export CSV.
 *
 * @package SelahCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SELAH_CORE_TYPE = 'selah_demande';

/**
 * Profils proposés dans le formulaire.
 *
 * @return array<string, string>
 */
function selah_core_profils() {
	return array(
		'essayer'  => __( 'Je veux essayer Selah', 'selah-core' ),
		'createur' => __( 'Je suis créateur·rice', 'selah-core' ),
		'autre'    => __( 'Presse, partenaire ou autre', 'selah-core' ),
	);
}

/**
 * Libellés courts des profils, pour les pilules du formulaire.
 *
 * @return array<string, string>
 */
function selah_core_profils_courts() {
	return array(
		'essayer'  => __( 'Essayer', 'selah-core' ),
		'createur' => __( 'Créateur·rice', 'selah-core' ),
		'autre'    => __( 'Presse / autre', 'selah-core' ),
	);
}

/**
 * Étapes de suivi d'une demande.
 *
 * @return array<string, string>
 */
function selah_core_statuts() {
	return array(
		'nouvelle'    => __( 'Nouvelle', 'selah-core' ),
		'contactee'   => __( 'Contactée', 'selah-core' ),
		'code_envoye' => __( 'Code envoyé', 'selah-core' ),
		'close'       => __( 'Close', 'selah-core' ),
	);
}

/**
 * Champs enregistrés avec chaque demande.
 *
 * @return array<string, string>
 */
function selah_core_champs() {
	return array(
		'email'     => __( 'E-mail', 'selah-core' ),
		'telephone' => __( 'Téléphone', 'selah-core' ),
		'profil'    => __( 'Profil', 'selah-core' ),
		'marque'    => __( 'Marque ou atelier', 'selah-core' ),
		'message'   => __( 'Message', 'selah-core' ),
	);
}

/**
 * Libellé lisible d'une valeur de liste.
 *
 * @param array<string, string> $liste Liste clé => libellé.
 * @param string                $cle   Clé enregistrée.
 * @return string
 */
function selah_core_libelle( $liste, $cle ) {
	return isset( $liste[ $cle ] ) ? $liste[ $cle ] : (string) $cle;
}

/**
 * Type de contenu « Demande d'accès », visible seulement des administrateurs.
 */
function selah_core_type_demande() {
	register_post_type(
		SELAH_CORE_TYPE,
		array(
			'labels'           => array(
				'name'               => __( 'Demandes d’accès', 'selah-core' ),
				'singular_name'      => __( 'Demande d’accès', 'selah-core' ),
				'menu_name'          => __( 'Demandes d’accès', 'selah-core' ),
				'all_items'          => __( 'Toutes les demandes', 'selah-core' ),
				'edit_item'          => __( 'Demande d’accès', 'selah-core' ),
				'search_items'       => __( 'Rechercher une demande', 'selah-core' ),
				'not_found'          => __( 'Aucune demande pour l’instant.', 'selah-core' ),
				'not_found_in_trash' => __( 'Aucune demande dans la corbeille.', 'selah-core' ),
			),
			'public'           => false,
			'show_ui'          => true,
			'show_in_menu'     => true,
			'show_in_rest'     => false,
			'menu_position'    => 26,
			'menu_icon'        => 'dashicons-tickets-alt',
			'supports'         => array( 'title' ),
			'map_meta_cap'     => true,
			'capabilities'     => array(
				'create_posts'           => 'do_not_allow',
				'edit_posts'             => 'manage_options',
				'edit_others_posts'      => 'manage_options',
				'edit_published_posts'   => 'manage_options',
				'edit_private_posts'     => 'manage_options',
				'publish_posts'          => 'manage_options',
				'read_private_posts'     => 'manage_options',
				'delete_posts'           => 'manage_options',
				'delete_others_posts'    => 'manage_options',
				'delete_published_posts' => 'manage_options',
				'delete_private_posts'   => 'manage_options',
			),
			'rewrite'          => false,
			'query_var'        => false,
			'delete_with_user' => false,
		)
	);
}
add_action( 'init', 'selah_core_type_demande' );

/**
 * Enregistre une demande.
 *
 * @param array<string, string> $donnees Données déjà nettoyées.
 * @return int|WP_Error Identifiant de la demande.
 */
function selah_core_creer_demande( $donnees ) {
	$meta = array(
		'_selah_statut'       => 'nouvelle',
		'_selah_consentement' => current_time( 'mysql' ),
	);
	foreach ( array_keys( selah_core_champs() ) as $champ ) {
		$meta[ '_selah_' . $champ ] = isset( $donnees[ $champ ] ) ? $donnees[ $champ ] : '';
	}

	return wp_insert_post(
		array(
			'post_type'   => SELAH_CORE_TYPE,
			'post_status' => 'publish',
			'post_title'  => $donnees['nom'],
			'post_author' => 0,
			'meta_input'  => $meta,
		),
		true
	);
}

/**
 * Une demande avec cet e-mail existe-t-elle déjà depuis moins de 24 heures ?
 *
 * @param string $email E-mail.
 * @return bool
 */
function selah_core_demande_recente( $email ) {
	$ids = get_posts(
		array(
			'post_type'      => SELAH_CORE_TYPE,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_selah_email', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => $email, // phpcs:ignore WordPress.DB.SlowDBQuery
			'date_query'     => array( array( 'after' => '24 hours ago' ) ),
		)
	);
	return ! empty( $ids );
}

/**
 * Nombre de demandes encore « nouvelles ».
 *
 * @return int
 */
function selah_core_nombre_nouvelles() {
	$requete = new WP_Query(
		array(
			'post_type'      => SELAH_CORE_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_selah_statut', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => 'nouvelle', // phpcs:ignore WordPress.DB.SlowDBQuery
			'no_found_rows'  => false,
		)
	);
	return (int) $requete->found_posts;
}

/**
 * Pastille du nombre de nouvelles demandes dans le menu.
 */
function selah_core_pastille_menu() {
	global $menu;

	if ( ! current_user_can( 'manage_options' ) || empty( $menu ) ) {
		return;
	}

	$nombre = selah_core_nombre_nouvelles();
	if ( ! $nombre ) {
		return;
	}

	foreach ( $menu as $position => $element ) {
		if ( isset( $element[2] ) && 'edit.php?post_type=' . SELAH_CORE_TYPE === $element[2] ) {
			$menu[ $position ][0] .= sprintf( ' <span class="awaiting-mod count-%1$d"><span class="pending-count">%1$d</span></span>', $nombre ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
			break;
		}
	}
}
add_action( 'admin_menu', 'selah_core_pastille_menu', 99 );

/**
 * Colonnes de la liste des demandes.
 *
 * @param string[] $colonnes Colonnes par défaut.
 * @return string[]
 */
function selah_core_colonnes( $colonnes ) {
	return array(
		'cb'           => $colonnes['cb'],
		'title'        => __( 'Nom', 'selah-core' ),
		'selah_email'  => __( 'E-mail', 'selah-core' ),
		'selah_tel'    => __( 'Téléphone', 'selah-core' ),
		'selah_profil' => __( 'Profil', 'selah-core' ),
		'selah_marque' => __( 'Marque', 'selah-core' ),
		'selah_statut' => __( 'Suivi', 'selah-core' ),
		'date'         => __( 'Reçue le', 'selah-core' ),
	);
}
add_filter( 'manage_' . SELAH_CORE_TYPE . '_posts_columns', 'selah_core_colonnes' );

/**
 * Contenu des colonnes.
 *
 * @param string $colonne Colonne.
 * @param int    $id      Demande.
 */
function selah_core_contenu_colonne( $colonne, $id ) {
	switch ( $colonne ) {
		case 'selah_email':
			$email = get_post_meta( $id, '_selah_email', true );
			printf( '<a href="mailto:%1$s">%2$s</a>', esc_attr( $email ), esc_html( $email ) );
			break;
		case 'selah_tel':
			echo esc_html( get_post_meta( $id, '_selah_telephone', true ) );
			break;
		case 'selah_profil':
			echo esc_html( selah_core_libelle( selah_core_profils(), get_post_meta( $id, '_selah_profil', true ) ) );
			break;
		case 'selah_marque':
			echo esc_html( get_post_meta( $id, '_selah_marque', true ) );
			break;
		case 'selah_statut':
			echo esc_html( selah_core_libelle( selah_core_statuts(), get_post_meta( $id, '_selah_statut', true ) ) );
			break;
	}
}
add_action( 'manage_' . SELAH_CORE_TYPE . '_posts_custom_column', 'selah_core_contenu_colonne', 10, 2 );

/**
 * Pas de lien « Voir » : les demandes ne sont pas publiques.
 *
 * @param string[] $actions Actions de ligne.
 * @param WP_Post  $post    Demande.
 * @return string[]
 */
function selah_core_actions_ligne( $actions, $post ) {
	if ( SELAH_CORE_TYPE === $post->post_type ) {
		unset( $actions['view'], $actions['inline hide-if-no-js'] );
	}
	return $actions;
}
add_filter( 'post_row_actions', 'selah_core_actions_ligne', 10, 2 );

/**
 * Boîte « Détails de la demande » sur l'écran d'édition.
 */
function selah_core_boites() {
	add_meta_box( 'selah_details', __( 'Détails de la demande', 'selah-core' ), 'selah_core_boite_details', SELAH_CORE_TYPE, 'normal', 'high' );
	add_meta_box( 'selah_suivi', __( 'Suivi', 'selah-core' ), 'selah_core_boite_suivi', SELAH_CORE_TYPE, 'side', 'high' );
}
add_action( 'add_meta_boxes', 'selah_core_boites' );

/**
 * @param WP_Post $post Demande.
 */
function selah_core_boite_details( $post ) {
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( selah_core_champs() as $champ => $libelle ) {
		$valeur = get_post_meta( $post->ID, '_selah_' . $champ, true );
		if ( 'profil' === $champ ) {
			$valeur = selah_core_libelle( selah_core_profils(), $valeur );
		}
		echo '<tr><th scope="row">' . esc_html( $libelle ) . '</th><td>';
		if ( 'email' === $champ && $valeur ) {
			printf( '<a href="mailto:%1$s">%2$s</a>', esc_attr( $valeur ), esc_html( $valeur ) );
		} elseif ( '' === $valeur ) {
			echo '<span aria-hidden="true">—</span>';
		} else {
			echo nl2br( esc_html( $valeur ) );
		}
		echo '</td></tr>';
	}
	$consentement = get_post_meta( $post->ID, '_selah_consentement', true );
	echo '<tr><th scope="row">' . esc_html__( 'Consentement', 'selah-core' ) . '</th><td>';
	/* translators: %s: date et heure du consentement */
	echo $consentement ? esc_html( sprintf( __( 'Donné le %s', 'selah-core' ), mysql2date( 'j F Y à H:i', $consentement ) ) ) : '—';
	echo '</td></tr></tbody></table>';
}

/**
 * @param WP_Post $post Demande.
 */
function selah_core_boite_suivi( $post ) {
	wp_nonce_field( 'selah_suivi_' . $post->ID, 'selah_suivi_nonce' );
	$statut = get_post_meta( $post->ID, '_selah_statut', true );
	$notes  = get_post_meta( $post->ID, '_selah_notes', true );
	?>
	<p>
		<label for="selah_statut"><strong><?php esc_html_e( 'Étape', 'selah-core' ); ?></strong></label><br />
		<select id="selah_statut" name="selah_statut" class="widefat">
			<?php foreach ( selah_core_statuts() as $cle => $libelle ) : ?>
				<option value="<?php echo esc_attr( $cle ); ?>" <?php selected( $statut, $cle ); ?>><?php echo esc_html( $libelle ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="selah_notes"><strong><?php esc_html_e( 'Notes internes', 'selah-core' ); ?></strong></label>
		<textarea id="selah_notes" name="selah_notes" class="widefat" rows="5"><?php echo esc_textarea( $notes ); ?></textarea>
	</p>
	<?php
}

/**
 * Enregistre l'étape et les notes.
 *
 * @param int $id Demande.
 */
function selah_core_enregistrer_suivi( $id ) {
	if ( ! isset( $_POST['selah_suivi_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['selah_suivi_nonce'] ), 'selah_suivi_' . $id ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $id ) ) {
		return;
	}

	$statut = isset( $_POST['selah_statut'] ) ? sanitize_key( $_POST['selah_statut'] ) : '';
	if ( isset( selah_core_statuts()[ $statut ] ) ) {
		update_post_meta( $id, '_selah_statut', $statut );
	}
	if ( isset( $_POST['selah_notes'] ) ) {
		update_post_meta( $id, '_selah_notes', sanitize_textarea_field( wp_unslash( $_POST['selah_notes'] ) ) );
	}
}
add_action( 'save_post_' . SELAH_CORE_TYPE, 'selah_core_enregistrer_suivi' );

/**
 * Bouton « Exporter en CSV » au-dessus de la liste.
 *
 * @param string $position « top » ou « bottom ».
 */
function selah_core_bouton_export( $position ) {
	global $typenow;
	if ( SELAH_CORE_TYPE !== $typenow || 'top' !== $position || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$url = wp_nonce_url( admin_url( 'admin-post.php?action=selah_export_demandes' ), 'selah_export_demandes' );
	printf( '<div class="alignleft actions"><a class="button" href="%s">%s</a></div>', esc_url( $url ), esc_html__( 'Exporter en CSV', 'selah-core' ) );
}
add_action( 'manage_posts_extra_tablenav', 'selah_core_bouton_export' );

/**
 * Export CSV de toutes les demandes (séparateur « ; » pour Excel en français).
 */
function selah_core_exporter_demandes() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Accès refusé.', 'selah-core' ), 403 );
	}
	check_admin_referer( 'selah_export_demandes' );

	$demandes = get_posts(
		array(
			'post_type'      => SELAH_CORE_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="demandes-selah-' . gmdate( 'Y-m-d' ) . '.csv"' );

	$sortie = fopen( 'php://output', 'w' ); // phpcs:ignore WordPress.WP.AlternativeFunctions -- flux de sortie, pas un fichier.
	fwrite( $sortie, "\xEF\xBB\xBF" ); // phpcs:ignore WordPress.WP.AlternativeFunctions -- BOM : Excel lit l'UTF-8.
	fputcsv( $sortie, array( 'Date', 'Nom', 'E-mail', 'Téléphone', 'Profil', 'Marque', 'Message', 'Suivi', 'Notes' ), ';' );
	foreach ( $demandes as $demande ) {
		$meta = static function ( $cle ) use ( $demande ) {
			return selah_core_cellule_csv( get_post_meta( $demande->ID, '_selah_' . $cle, true ) );
		};
		fputcsv(
			$sortie,
			array(
				get_the_date( 'Y-m-d H:i', $demande ),
				selah_core_cellule_csv( $demande->post_title ),
				$meta( 'email' ),
				$meta( 'telephone' ),
				selah_core_libelle( selah_core_profils(), get_post_meta( $demande->ID, '_selah_profil', true ) ),
				$meta( 'marque' ),
				$meta( 'message' ),
				selah_core_libelle( selah_core_statuts(), get_post_meta( $demande->ID, '_selah_statut', true ) ),
				$meta( 'notes' ),
			),
			';'
		);
	}
	fclose( $sortie ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	exit;
}
add_action( 'admin_post_selah_export_demandes', 'selah_core_exporter_demandes' );

/**
 * Neutralise les formules dans une cellule CSV (=, +, -, @ en tête).
 *
 * @param string $valeur Valeur brute.
 * @return string
 */
function selah_core_cellule_csv( $valeur ) {
	$valeur = (string) $valeur;
	if ( '' !== $valeur && in_array( $valeur[0], array( '=', '+', '-', '@', "\t", "\r" ), true ) ) {
		$valeur = "'" . $valeur;
	}
	return $valeur;
}

/**
 * Sur la fiche d'une demande, la boîte « Publier » ne garde que l'essentiel.
 */
function selah_core_simplifier_fiche() {
	$ecran = get_current_screen();
	if ( $ecran && SELAH_CORE_TYPE === $ecran->post_type && 'post' === $ecran->base ) {
		echo '<style>#minor-publishing-actions, #visibility, .misc-pub-curtime, .misc-pub-post-status .edit-post-status { display: none; }</style>';
	}
}
add_action( 'admin_head', 'selah_core_simplifier_fiche' );
