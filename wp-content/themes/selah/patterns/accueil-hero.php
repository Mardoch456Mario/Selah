<?php
/**
 * Title: Accueil — ouverture
 * Slug: selah/accueil-hero
 * Categories: selah, banner
 * Keywords: hero, accueil, ouverture
 * Viewport Width: 1400
 *
 * @package Selah
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|60"}}}} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-center">
		<!-- wp:column {"verticalAlignment":"center","width":"58%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58%">
			<!-- wp:paragraph {"className":"selah-pastille","fontSize":"secondaire"} -->
			<p class="selah-pastille has-secondaire-font-size">Démonstration privée · Créateurs de Cotonou</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":1,"className":"selah-fil"} -->
			<h1 class="wp-block-heading selah-fil">Essaie tout, garde ce qui te va.</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"discret","fontSize":"sous-titre"} -->
			<p class="has-discret-color has-text-color has-sous-titre-font-size">Ton personnage essaie pour toi les pièces des créateurs de Cotonou. Tu regardes, tu demandes l’avis de tes amis, tu gardes ce qui te va.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
			<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#demande-acces">Demander un code d’accès</a></div>
				<!-- /wp:button -->

				<!-- wp:button {"className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#selah-app">J’ai déjà un code</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:image {"sizeSlug":"full","linkDestination":"none","align":"center","className":"selah-maquette"} -->
			<figure class="wp-block-image aligncenter size-full selah-maquette"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/maquette-essayage.svg' ) ); ?>" alt="Sur l’écran de Selah, un personnage essaie une robe aux bandes safran et trois amis ont donné leur avis."/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
