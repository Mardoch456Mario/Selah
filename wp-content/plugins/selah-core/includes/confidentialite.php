<?php
/**
 * Outils › Exporter / Effacer les données personnelles : prise en charge des demandes d'accès.
 *
 * @package SelahCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Demandes d'accès liées à une adresse e-mail.
 *
 * @param string $email E-mail.
 * @return WP_Post[]
 */
function selah_core_demandes_par_email( $email ) {
	return get_posts(
		array(
			'post_type'      => SELAH_CORE_TYPE,
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'meta_key'       => '_selah_email', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => $email, // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
}

/**
 * @param array $exportateurs Exportateurs enregistrés.
 * @return array
 */
function selah_core_exportateur( $exportateurs ) {
	$exportateurs['selah-core'] = array(
		'exporter_friendly_name' => __( 'Demandes d’accès Selah', 'selah-core' ),
		'callback'               => 'selah_core_exporter_donnees',
	);
	return $exportateurs;
}
add_filter( 'wp_privacy_personal_data_exporters', 'selah_core_exportateur' );

/**
 * @param string $email E-mail de la personne.
 * @return array
 */
function selah_core_exporter_donnees( $email ) {
	$elements = array();
	foreach ( selah_core_demandes_par_email( $email ) as $demande ) {
		$donnees = array(
			array(
				'name'  => __( 'Nom', 'selah-core' ),
				'value' => $demande->post_title,
			),
			array(
				'name'  => __( 'Reçue le', 'selah-core' ),
				'value' => get_the_date( 'Y-m-d H:i', $demande ),
			),
		);
		foreach ( selah_core_champs() as $champ => $libelle ) {
			$valeur = get_post_meta( $demande->ID, '_selah_' . $champ, true );
			if ( '' !== $valeur ) {
				$donnees[] = array(
					'name'  => $libelle,
					'value' => $valeur,
				);
			}
		}
		$elements[] = array(
			'group_id'    => 'selah-demandes',
			'group_label' => __( 'Demandes d’accès Selah', 'selah-core' ),
			'item_id'     => 'selah-demande-' . $demande->ID,
			'data'        => $donnees,
		);
	}
	return array(
		'data' => $elements,
		'done' => true,
	);
}

/**
 * @param array $effaceurs Effaceurs enregistrés.
 * @return array
 */
function selah_core_effaceur( $effaceurs ) {
	$effaceurs['selah-core'] = array(
		'eraser_friendly_name' => __( 'Demandes d’accès Selah', 'selah-core' ),
		'callback'             => 'selah_core_effacer_donnees',
	);
	return $effaceurs;
}
add_filter( 'wp_privacy_personal_data_erasers', 'selah_core_effaceur' );

/**
 * @param string $email E-mail de la personne.
 * @return array
 */
function selah_core_effacer_donnees( $email ) {
	$supprimees = 0;
	foreach ( selah_core_demandes_par_email( $email ) as $demande ) {
		if ( wp_delete_post( $demande->ID, true ) ) {
			++$supprimees;
		}
	}
	return array(
		'items_removed'  => $supprimees > 0,
		'items_retained' => false,
		'messages'       => array(),
		'done'           => true,
	);
}

/**
 * Texte suggéré pour la politique de confidentialité.
 */
function selah_core_texte_confidentialite() {
	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}
	wp_add_privacy_policy_content(
		'Selah Core',
		'<p>' . esc_html__( 'Le formulaire de demande d’accès enregistre le nom, l’e-mail, le téléphone (facultatif), le profil, la marque et le message saisis, ainsi que la date du consentement. Ces informations servent uniquement à recontacter la personne et ne sont visibles que des administrateurs du site.', 'selah-core' ) . '</p>'
	);
}
add_action( 'admin_init', 'selah_core_texte_confidentialite' );
