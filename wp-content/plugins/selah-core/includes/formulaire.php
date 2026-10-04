<?php
/**
 * Formulaire public de demande d'accès : [selah_demande_acces profil="createur"].
 *
 * Pas de nonce ici : le formulaire est anonyme et les pages peuvent être mises
 * en cache, ce qui rendrait un nonce périmé. La protection repose sur un champ
 * piège, un délai minimal signé et une limite de demandes par adresse IP.
 *
 * @package SelahCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SELAH_CORE_DELAI_MINIMAL = 3;    // Secondes avant qu'un envoi soit accepté.
const SELAH_CORE_LIMITE_HEURE  = 5;    // Demandes par heure et par adresse IP.

/**
 * Feuille de style du formulaire.
 */
function selah_core_enregistrer_style() {
	wp_register_style( 'selah-formulaire', plugins_url( 'assets/formulaire.css', SELAH_CORE_FICHIER ), array(), SELAH_CORE_VERSION );
}
add_action( 'init', 'selah_core_enregistrer_style' );

/**
 * Lien vers la politique de confidentialité.
 *
 * @return string
 */
function selah_core_url_confidentialite() {
	$url = get_privacy_policy_url();
	return $url ? $url : home_url( '/confidentialite/' );
}

/**
 * Jeton d'horodatage signé, pour refuser les envois trop rapides (robots).
 *
 * @return string
 */
function selah_core_horodatage() {
	$maintenant = (string) time();
	return $maintenant . '.' . wp_hash( 'selah_horodatage|' . $maintenant );
}

/**
 * @param string $jeton Jeton reçu.
 * @return bool Vrai si le jeton est authentique et assez ancien.
 */
function selah_core_horodatage_valide( $jeton ) {
	$parties = explode( '.', (string) $jeton, 2 );
	if ( 2 !== count( $parties ) || ! ctype_digit( $parties[0] ) ) {
		return false;
	}
	if ( ! hash_equals( wp_hash( 'selah_horodatage|' . $parties[0] ), $parties[1] ) ) {
		return false;
	}
	return ( time() - (int) $parties[0] ) >= SELAH_CORE_DELAI_MINIMAL;
}

/**
 * Textes du formulaire, au tutoiement (par défaut) ou au vouvoiement.
 *
 * @param string $registre « tu » ou « vous ».
 * @return array<string, string>
 */
function selah_core_textes( $registre ) {
	if ( 'vous' === $registre ) {
		return array(
			'merci_titre'      => __( 'Merci, votre demande est bien arrivée.', 'selah-core' ),
			'merci_texte'      => __( 'L’équipe Selah vous recontacte très vite, par e-mail ou par téléphone.', 'selah-core' ),
			'alerte'           => __( 'Quelques informations manquent ou sont à corriger :', 'selah-core' ),
			'legende'          => __( 'Vous venez pour…', 'selah-core' ),
			'message_indice'   => __( 'Dites-nous en deux mots ce qui vous amène.', 'selah-core' ),
			'err_nom'          => __( 'Indiquez votre nom.', 'selah-core' ),
			'err_email'        => __( 'Indiquez une adresse e-mail valide.', 'selah-core' ),
			'err_profil'       => __( 'Choisissez votre profil.', 'selah-core' ),
			'err_marque'       => __( 'Indiquez le nom de votre marque ou de votre atelier.', 'selah-core' ),
			'err_consentement' => __( 'Cochez la case pour accepter que nous traitions votre demande.', 'selah-core' ),
			'err_envoi'        => __( 'Trop de demandes envoyées depuis votre connexion. Réessayez dans une heure.', 'selah-core' ),
		);
	}
	return array(
		'merci_titre'      => __( 'Merci, ta demande est bien arrivée.', 'selah-core' ),
		'merci_texte'      => __( 'L’équipe Selah te recontacte très vite, par e-mail ou par téléphone.', 'selah-core' ),
		'alerte'           => __( 'Quelques informations manquent ou sont à corriger :', 'selah-core' ),
		'legende'          => __( 'Tu viens pour…', 'selah-core' ),
		'message_indice'   => __( 'Dis-nous en deux mots ce qui t’amène.', 'selah-core' ),
		'err_nom'          => __( 'Indique ton nom.', 'selah-core' ),
		'err_email'        => __( 'Indique une adresse e-mail valide.', 'selah-core' ),
		'err_profil'       => __( 'Choisis ton profil.', 'selah-core' ),
		'err_marque'       => __( 'Indique le nom de ta marque ou de ton atelier.', 'selah-core' ),
		'err_consentement' => __( 'Coche la case pour accepter que nous traitions ta demande.', 'selah-core' ),
		'err_envoi'        => __( 'Trop de demandes envoyées depuis ta connexion. Réessaie dans une heure.', 'selah-core' ),
	);
}

/**
 * Affiche le formulaire.
 *
 * @param array|string $atts Attributs du code court.
 * @return string
 */
function selah_core_formulaire( $atts ) {
	$atts     = shortcode_atts(
		array(
			'profil'   => 'essayer',
			'registre' => 'tu',
		),
		$atts,
		'selah_demande_acces'
	);
	$profils  = selah_core_profils();
	$registre = 'vous' === $atts['registre'] ? 'vous' : 'tu';
	$textes   = selah_core_textes( $registre );

	wp_enqueue_style( 'selah-formulaire' );

	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- simple affichage.
	if ( isset( $_GET['selah_demande'] ) && 'merci' === $_GET['selah_demande'] ) {
		return '<div class="selah-formulaire selah-formulaire--merci" role="status" tabindex="-1">'
			. '<p class="selah-formulaire__titre">' . esc_html( $textes['merci_titre'] ) . '</p>'
			. '<p>' . esc_html( $textes['merci_texte'] ) . '</p>'
			. '</div>';
	}

	$erreurs = array();
	$valeurs = array();
	if ( isset( $_GET['selah_retour'] ) ) {
		$retour = get_transient( 'selah_retour_' . sanitize_key( $_GET['selah_retour'] ) );
		if ( is_array( $retour ) ) {
			$erreurs = $retour['erreurs'];
			$valeurs = $retour['valeurs'];
		}
	}

	$profil = isset( $_GET['profil'] ) ? sanitize_key( $_GET['profil'] ) : '';
	// phpcs:enable
	if ( ! isset( $profils[ $profil ] ) ) {
		$profil = isset( $profils[ $atts['profil'] ] ) ? $atts['profil'] : 'essayer';
	}
	if ( isset( $valeurs['profil'], $profils[ $valeurs['profil'] ] ) ) {
		$profil = $valeurs['profil'];
	}

	$valeur = static function ( $champ ) use ( $valeurs ) {
		return isset( $valeurs[ $champ ] ) ? $valeurs[ $champ ] : '';
	};

	$id = 'selah-' . wp_unique_id();

	ob_start();
	?>
	<form class="selah-formulaire" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
		<input type="hidden" name="action" value="selah_demande_acces" />
		<input type="hidden" name="retour" value="<?php echo esc_attr( selah_core_url_courante() ); ?>" />
		<input type="hidden" name="horodatage" value="<?php echo esc_attr( selah_core_horodatage() ); ?>" />
		<input type="hidden" name="registre" value="<?php echo esc_attr( $registre ); ?>" />

		<?php if ( $erreurs ) : ?>
			<div class="selah-formulaire__alerte" role="alert">
				<p><?php echo esc_html( $textes['alerte'] ); ?></p>
				<ul>
					<?php foreach ( $erreurs as $champ => $message ) : ?>
						<li class="selah-formulaire__alerte-<?php echo esc_attr( $champ ); ?>"><a href="#<?php echo esc_attr( $id . '-' . $champ ); ?>"><?php echo esc_html( $message ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<fieldset class="selah-formulaire__profils">
			<legend><?php echo esc_html( $textes['legende'] ); ?></legend>
			<div class="selah-formulaire__choix-liste">
				<?php foreach ( selah_core_profils_courts() as $cle => $libelle ) : ?>
					<label class="selah-formulaire__choix">
						<input type="radio" name="profil" value="<?php echo esc_attr( $cle ); ?>" <?php checked( $profil, $cle ); ?> <?php echo 'essayer' === $cle ? 'id="' . esc_attr( $id . '-profil' ) . '"' : ''; ?> />
						<span><?php echo esc_html( $libelle ); ?></span>
					</label>
				<?php endforeach; ?>
			</div>
		</fieldset>

		<?php
		selah_core_champ(
			$id,
			'nom',
			__( 'Nom', 'selah-core' ),
			'text',
			$valeur( 'nom' ),
			$erreurs,
			array(
				'required'     => true,
				'autocomplete' => 'name',
			)
		);
		selah_core_champ(
			$id,
			'email',
			__( 'E-mail', 'selah-core' ),
			'email',
			$valeur( 'email' ),
			$erreurs,
			array(
				'required'     => true,
				'autocomplete' => 'email',
			)
		);
		selah_core_champ(
			$id,
			'telephone',
			__( 'Téléphone / WhatsApp', 'selah-core' ),
			'tel',
			$valeur( 'telephone' ),
			$erreurs,
			array(
				'autocomplete' => 'tel',
				'aide'         => __( '(facultatif)', 'selah-core' ),
			)
		);
		selah_core_champ(
			$id,
			'marque',
			__( 'Marque ou atelier', 'selah-core' ),
			'text',
			$valeur( 'marque' ),
			$erreurs,
			array(
				'autocomplete' => 'organization',
				'classe'       => 'selah-formulaire__champ--marque',
				'aide'         => __( '(créateur·rice)', 'selah-core' ),
			)
		);
		selah_core_champ(
			$id,
			'message',
			__( 'Message', 'selah-core' ),
			'textarea',
			$valeur( 'message' ),
			$erreurs,
			array(
				'aide'        => __( '(facultatif)', 'selah-core' ),
				'classe'      => 'selah-formulaire__champ--message',
				'placeholder' => $textes['message_indice'],
			)
		);
		?>

		<div class="selah-formulaire__piege" aria-hidden="true">
			<label for="<?php echo esc_attr( $id . '-site' ); ?>"><?php esc_html_e( 'Ne pas remplir ce champ', 'selah-core' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $id . '-site' ); ?>" name="site_web" value="" tabindex="-1" autocomplete="off" />
		</div>

		<label class="selah-formulaire__consentement<?php echo isset( $erreurs['consentement'] ) ? ' a-une-erreur' : ''; ?>">
			<input type="checkbox" name="consentement" value="1" id="<?php echo esc_attr( $id . '-consentement' ); ?>" required <?php checked( ! empty( $valeurs['consentement'] ) ); ?> />
			<span>
				<?php
				printf(
					/* translators: %s: lien vers la page Confidentialité */
					esc_html__( 'J’accepte que Selah utilise ces informations pour traiter ma demande. %s', 'selah-core' ),
					'<a href="' . esc_url( selah_core_url_confidentialite() ) . '">' . esc_html__( 'En savoir plus', 'selah-core' ) . '</a>'
				);
				?>
			</span>
		</label>

		<button type="submit" class="selah-formulaire__envoyer wp-element-button"><?php esc_html_e( 'Envoyer ma demande', 'selah-core' ); ?></button>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'selah_demande_acces', 'selah_core_formulaire' );

/**
 * Un champ du formulaire, avec son libellé et son éventuelle erreur.
 *
 * @param string               $id      Préfixe d'identifiant.
 * @param string               $nom     Nom du champ.
 * @param string               $libelle Libellé.
 * @param string               $type    text, email, tel ou textarea.
 * @param string               $valeur  Valeur saisie.
 * @param array<string,string> $erreurs Erreurs par champ.
 * @param array                $options required, autocomplete, aide, classe, placeholder.
 */
function selah_core_champ( $id, $nom, $libelle, $type, $valeur, $erreurs, $options = array() ) {
	$champ_id = $id . '-' . $nom;
	$requis   = ! empty( $options['required'] );
	$erreur   = isset( $erreurs[ $nom ] ) ? $erreurs[ $nom ] : '';
	$decrit   = array();
	$classes  = 'selah-formulaire__champ' . ( isset( $options['classe'] ) ? ' ' . $options['classe'] : '' ) . ( $erreur ? ' a-une-erreur' : '' );

	echo '<div class="' . esc_attr( $classes ) . '">';
	echo '<label for="' . esc_attr( $champ_id ) . '">' . esc_html( $libelle );
	if ( ! empty( $options['aide'] ) ) {
		echo ' <span class="selah-formulaire__aide">' . esc_html( $options['aide'] ) . '</span>';
	}
	echo '</label>';

	if ( $erreur ) {
		$decrit[] = $champ_id . '-erreur';
		echo '<p class="selah-formulaire__erreur" id="' . esc_attr( $champ_id . '-erreur' ) . '">' . esc_html( $erreur ) . '</p>';
	}

	$attributs = sprintf(
		'id="%1$s" name="%2$s"%3$s%4$s%5$s%6$s%7$s',
		esc_attr( $champ_id ),
		esc_attr( $nom ),
		$requis ? ' required' : '',
		! empty( $options['autocomplete'] ) ? ' autocomplete="' . esc_attr( $options['autocomplete'] ) . '"' : '',
		$erreur ? ' aria-invalid="true"' : '',
		$decrit ? ' aria-describedby="' . esc_attr( implode( ' ', $decrit ) ) . '"' : '',
		! empty( $options['placeholder'] ) ? ' placeholder="' . esc_attr( $options['placeholder'] ) . '"' : ''
	);

	if ( 'textarea' === $type ) {
		echo '<textarea ' . $attributs . ' rows="4" maxlength="2000">' . esc_textarea( $valeur ) . '</textarea>'; // phpcs:ignore WordPress.Security.EscapeOutput -- attributs échappés ci-dessus.
	} else {
		echo '<input type="' . esc_attr( $type ) . '" ' . $attributs . ' value="' . esc_attr( $valeur ) . '" maxlength="200" />'; // phpcs:ignore WordPress.Security.EscapeOutput -- attributs échappés ci-dessus.
	}
	echo '</div>';
}

/**
 * Page où revenir après l'envoi : celle qui affiche le formulaire.
 *
 * @return string
 */
function selah_core_url_courante() {
	if ( is_singular() && ! is_front_page() ) {
		return get_permalink();
	}
	return home_url( '/' );
}

/**
 * Adresse IP du visiteur, réduite à une empreinte (on ne la stocke jamais en clair).
 *
 * @return string
 */
function selah_core_empreinte_ip() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	return substr( wp_hash( 'selah_ip|' . $ip ), 0, 20 );
}

/**
 * Traite l'envoi du formulaire.
 */
function selah_core_traiter_demande() {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- voir l'en-tête du fichier.
	$retour = isset( $_POST['retour'] ) ? wp_validate_redirect( esc_url_raw( wp_unslash( $_POST['retour'] ) ), home_url( '/' ) ) : home_url( '/' );
	$retour = remove_query_arg( array( 'selah_demande', 'selah_retour' ), strtok( $retour, '#' ) );
	$merci  = add_query_arg( 'selah_demande', 'merci', $retour ) . '#demande-acces';

	// Robot : champ piège rempli ou envoi instantané. On fait mine d'accepter.
	$horodatage = isset( $_POST['horodatage'] ) ? sanitize_text_field( wp_unslash( $_POST['horodatage'] ) ) : '';
	if ( ! empty( $_POST['site_web'] ) || ! selah_core_horodatage_valide( $horodatage ) ) {
		wp_safe_redirect( $merci, 303 );
		exit;
	}

	$profils = selah_core_profils();
	$textes  = selah_core_textes( isset( $_POST['registre'] ) && 'vous' === $_POST['registre'] ? 'vous' : 'tu' );
	$donnees = array(
		'nom'          => isset( $_POST['nom'] ) ? sanitize_text_field( wp_unslash( $_POST['nom'] ) ) : '',
		'email'        => isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '',
		'telephone'    => isset( $_POST['telephone'] ) ? preg_replace( '/[^0-9+().\s-]/', '', sanitize_text_field( wp_unslash( $_POST['telephone'] ) ) ) : '',
		'profil'       => isset( $_POST['profil'] ) ? sanitize_key( $_POST['profil'] ) : '',
		'marque'       => isset( $_POST['marque'] ) ? sanitize_text_field( wp_unslash( $_POST['marque'] ) ) : '',
		'message'      => isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '',
		'consentement' => ! empty( $_POST['consentement'] ),
	);
	// phpcs:enable

	$donnees['nom']       = mb_substr( $donnees['nom'], 0, 200 );
	$donnees['telephone'] = mb_substr( trim( $donnees['telephone'] ), 0, 40 );
	$donnees['marque']    = mb_substr( $donnees['marque'], 0, 200 );
	$donnees['message']   = mb_substr( $donnees['message'], 0, 2000 );

	$erreurs = array();
	if ( mb_strlen( $donnees['nom'] ) < 2 ) {
		$erreurs['nom'] = $textes['err_nom'];
	}
	if ( ! is_email( $donnees['email'] ) ) {
		$erreurs['email'] = $textes['err_email'];
	}
	if ( ! isset( $profils[ $donnees['profil'] ] ) ) {
		$erreurs['profil'] = $textes['err_profil'];
	}
	if ( 'createur' === $donnees['profil'] && '' === $donnees['marque'] ) {
		$erreurs['marque'] = $textes['err_marque'];
	}
	if ( ! $donnees['consentement'] ) {
		$erreurs['consentement'] = $textes['err_consentement'];
	}

	$cle_limite = 'selah_limite_' . selah_core_empreinte_ip();
	$compteur   = (int) get_transient( $cle_limite );
	if ( ! $erreurs && $compteur >= SELAH_CORE_LIMITE_HEURE ) {
		$erreurs['envoi'] = $textes['err_envoi'];
	}

	if ( $erreurs ) {
		// On réaffiche l'e-mail tel que saisi, même invalide, pour qu'il soit corrigé.
		if ( isset( $erreurs['email'] ) && isset( $_POST['email'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$donnees['email'] = mb_substr( sanitize_text_field( wp_unslash( $_POST['email'] ) ), 0, 200 ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		}
		$jeton = strtolower( wp_generate_password( 16, false ) );
		set_transient(
			'selah_retour_' . $jeton,
			array(
				'erreurs' => $erreurs,
				'valeurs' => $donnees,
			),
			15 * MINUTE_IN_SECONDS
		);
		wp_safe_redirect( add_query_arg( 'selah_retour', $jeton, $retour ) . '#demande-acces', 303 );
		exit;
	}

	set_transient( $cle_limite, $compteur + 1, HOUR_IN_SECONDS );

	// Même e-mail dans les dernières 24 heures : on ne crée pas de doublon.
	if ( ! selah_core_demande_recente( $donnees['email'] ) ) {
		$demande = selah_core_creer_demande( $donnees );
		if ( ! is_wp_error( $demande ) ) {
			selah_core_notifier( $demande, $donnees );
		}
	}

	wp_safe_redirect( $merci, 303 );
	exit;
}
add_action( 'admin_post_nopriv_selah_demande_acces', 'selah_core_traiter_demande' );
add_action( 'admin_post_selah_demande_acces', 'selah_core_traiter_demande' );

/**
 * Prévient l'équipe d'une nouvelle demande.
 *
 * @param int                  $demande Identifiant de la demande.
 * @param array<string, mixed> $donnees Données de la demande.
 */
function selah_core_notifier( $demande, $donnees ) {
	$lignes = array(
		__( 'Nouvelle demande d’accès à Selah.', 'selah-core' ),
		'',
		__( 'Nom', 'selah-core' ) . ' : ' . $donnees['nom'],
		__( 'E-mail', 'selah-core' ) . ' : ' . $donnees['email'],
		__( 'Téléphone', 'selah-core' ) . ' : ' . ( $donnees['telephone'] ? $donnees['telephone'] : '—' ),
		__( 'Profil', 'selah-core' ) . ' : ' . selah_core_libelle( selah_core_profils(), $donnees['profil'] ),
		__( 'Marque', 'selah-core' ) . ' : ' . ( $donnees['marque'] ? $donnees['marque'] : '—' ),
		'',
		__( 'Message', 'selah-core' ) . ' :',
		$donnees['message'] ? $donnees['message'] : '—',
		'',
		__( 'Suivre la demande :', 'selah-core' ) . ' ' . admin_url( 'post.php?post=' . (int) $demande . '&action=edit' ),
	);

	wp_mail(
		selah_core_email_notification(),
		/* translators: %s: nom de la personne */
		sprintf( __( '[Selah] Demande d’accès de %s', 'selah-core' ), $donnees['nom'] ),
		implode( "\n", $lignes ),
		array( 'Reply-To: ' . str_replace( array( '"', '<', '>' ), '', $donnees['nom'] ) . ' <' . $donnees['email'] . '>' )
	);
}
