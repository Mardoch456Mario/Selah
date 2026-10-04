<?php
/**
 * Title: Appel aux créateurs
 * Slug: selah/createurs-appel
 * Categories: selah, call-to-action
 * Keywords: créateurs, ateliers, marques, cotonou
 * Viewport Width: 1400
 *
 * @package Selah
 */

?>
<!-- wp:group {"align":"full","backgroundColor":"encre","textColor":"blanc","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"margin":{"top":"0","bottom":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|blanc"}}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-blanc-color has-encre-background-color has-text-color has-background has-link-color" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|60"}}}} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-center">
		<!-- wp:column {"verticalAlignment":"center","width":"54%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:54%">
			<!-- wp:paragraph {"className":"selah-surtitre","textColor":"safran","fontSize":"legende"} -->
			<p class="selah-surtitre has-safran-color has-text-color has-legende-font-size">Créateurs de Cotonou</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"className":"selah-fil"} -->
			<h2 class="wp-block-heading selah-fil">Vos pièces méritent d’être essayées.</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"style":{"color":{"text":"#ffffffbf"}},"fontSize":"sous-titre"} -->
			<p class="has-text-color has-sous-titre-font-size" style="color:#ffffffbf">Atelier, marque ou styliste indépendant : sur Selah, chacun voit vos créations portées par son propre personnage, puis les partage avec ses amis.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
			<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
				<!-- wp:button {"backgroundColor":"blanc","textColor":"encre"} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-encre-color has-blanc-background-color has-text-color has-background wp-element-button" href="<?php echo esc_url( home_url( '/demande-acces/?profil=createur' ) ); ?>">Rejoindre Selah</a></div>
				<!-- /wp:button -->

				<!-- wp:button {"className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/createurs/' ) ); ?>">En savoir plus</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:image {"sizeSlug":"full","linkDestination":"none","align":"center"} -->
			<figure class="wp-block-image aligncenter size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/portant.svg' ) ); ?>" alt="Un portant d’atelier : chemise, robe aux bandes kanvô, boubou et pantalon."/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
