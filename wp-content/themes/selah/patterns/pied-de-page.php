<?php
/**
 * Title: Pied de page
 * Slug: selah/pied-de-page
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 *
 * @package Selah
 */

?>
<!-- wp:group {"style":{"spacing":{"margin":{"top":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="margin-top:0">
	<!-- wp:separator {"className":"selah-kanvo"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity selah-kanvo"/>
	<!-- /wp:separator -->

	<!-- wp:group {"backgroundColor":"encre","textColor":"blanc","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|40"}},"elements":{"link":{"color":{"text":"var:preset|color|blanc"}}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
	<div class="wp-block-group has-blanc-color has-encre-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40)">
		<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-columns alignwide">
			<!-- wp:column {"width":"45%"} -->
			<div class="wp-block-column" style="flex-basis:45%">
				<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.75rem","fontWeight":"800","letterSpacing":"-0.025em","lineHeight":"1"}}} -->
				<p style="font-size:1.75rem;font-weight:800;letter-spacing:-0.025em;line-height:1">Selah</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"style":{"color":{"text":"#ffffffbf"}}} -->
				<p class="has-text-color" style="color:#ffffffbf">Ton personnage essaie pour toi les pièces des créateurs de Cotonou. Tu regardes, tu demandes l’avis de tes amis, tu gardes ce qui te va.</p>
				<!-- /wp:paragraph -->

				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button {"backgroundColor":"blanc","textColor":"encre","className":"selah-bouton-clair"} -->
					<div class="wp-block-button selah-bouton-clair"><a class="wp-block-button__link has-encre-color has-blanc-background-color has-text-color has-background wp-element-button" href="#selah-app">Ouvrir Selah</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column">
				<!-- wp:paragraph {"className":"selah-surtitre","style":{"color":{"text":"#ffffffb3"}},"fontSize":"legende"} -->
				<p class="selah-surtitre has-text-color has-legende-font-size" style="color:#ffffffb3">Découvrir</p>
				<!-- /wp:paragraph -->

				<!-- wp:list {"className":"selah-liste-simple"} -->
				<ul class="wp-block-list selah-liste-simple">
					<!-- wp:list-item -->
					<li><a href="<?php echo esc_url( home_url( '/#comment-ca-marche' ) ); ?>">Comment ça marche</a></li>
					<!-- /wp:list-item -->
					<!-- wp:list-item -->
					<li><a href="<?php echo esc_url( home_url( '/createurs/' ) ); ?>">Pour les créateurs</a></li>
					<!-- /wp:list-item -->
					<!-- wp:list-item -->
					<li><a href="<?php echo esc_url( home_url( '/journal/' ) ); ?>">Journal</a></li>
					<!-- /wp:list-item -->
				</ul>
				<!-- /wp:list -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column">
				<!-- wp:paragraph {"className":"selah-surtitre","style":{"color":{"text":"#ffffffb3"}},"fontSize":"legende"} -->
				<p class="selah-surtitre has-text-color has-legende-font-size" style="color:#ffffffb3">Selah</p>
				<!-- /wp:paragraph -->

				<!-- wp:list {"className":"selah-liste-simple"} -->
				<ul class="wp-block-list selah-liste-simple">
					<!-- wp:list-item -->
					<li><a href="<?php echo esc_url( home_url( '/a-propos/' ) ); ?>">À propos</a></li>
					<!-- /wp:list-item -->
					<!-- wp:list-item -->
					<li><a href="<?php echo esc_url( home_url( '/questions-frequentes/' ) ); ?>">Questions fréquentes</a></li>
					<!-- /wp:list-item -->
					<!-- wp:list-item -->
					<li><a href="<?php echo esc_url( home_url( '/demande-acces/' ) ); ?>">Demander un accès</a></li>
					<!-- /wp:list-item -->
					<!-- wp:list-item -->
					<li><a href="<?php echo esc_url( home_url( '/confidentialite/' ) ); ?>">Confidentialité</a></li>
					<!-- /wp:list-item -->
				</ul>
				<!-- /wp:list -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->

		<!-- wp:separator {"align":"wide","className":"selah-separateur-sombre"} -->
		<hr class="wp-block-separator alignwide has-alpha-channel-opacity selah-separateur-sombre"/>
		<!-- /wp:separator -->

		<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
		<div class="wp-block-group alignwide">
			<!-- wp:paragraph {"style":{"color":{"text":"#ffffffb3"}},"fontSize":"secondaire"} -->
			<p class="has-text-color has-secondaire-font-size" style="color:#ffffffb3">© <?php echo esc_html( gmdate( 'Y' ) ); ?> Selah — showroom de mode</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"style":{"color":{"text":"#ffffffb3"}},"fontSize":"secondaire"} -->
			<p class="has-text-color has-secondaire-font-size" style="color:#ffffffb3">Essaie tout, garde ce qui te va.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
