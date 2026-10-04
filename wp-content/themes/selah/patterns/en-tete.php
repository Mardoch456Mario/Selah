<?php
/**
 * Title: En-tête
 * Slug: selah/en-tete
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 *
 * @package Selah
 */

?>
<!-- wp:group {"className":"selah-entete","style":{"spacing":{"padding":{"top":"0.85rem","bottom":"0.85rem"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group selah-entete" style="padding-top:0.85rem;padding-bottom:0.85rem">
	<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:group {"style":{"spacing":{"blockGap":"0.6rem"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:image {"width":"32px","height":"32px","sizeSlug":"full","linkDestination":"none","className":"selah-masquer-mobile"} -->
			<figure class="wp-block-image size-full is-resized selah-masquer-mobile"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icone.svg' ) ); ?>" alt="" style="width:32px;height:32px"/></figure>
			<!-- /wp:image -->

			<!-- wp:site-title {"level":0} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"style":{"spacing":{"blockGap":"1.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:navigation {"overlayBackgroundColor":"blanc","overlayTextColor":"encre","layout":{"type":"flex","justifyContent":"right"},"style":{"spacing":{"blockGap":"1.5rem"}}} -->
				<!-- wp:navigation-link {"label":"Comment ça marche","url":"<?php echo esc_url( home_url( '/#comment-ca-marche' ) ); ?>","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Créateurs","url":"<?php echo esc_url( home_url( '/createurs/' ) ); ?>","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"À propos","url":"<?php echo esc_url( home_url( '/a-propos/' ) ); ?>","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Questions","url":"<?php echo esc_url( home_url( '/questions-frequentes/' ) ); ?>","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"J’ai un code","url":"#selah-app","kind":"custom","className":"selah-seulement-mobile"} /-->
			<!-- /wp:navigation -->

			<!-- wp:buttons {"className":"selah-masquer-mobile"} -->
			<div class="wp-block-buttons selah-masquer-mobile">
				<!-- wp:button {"style":{"spacing":{"padding":{"top":"0.55rem","bottom":"0.55rem","left":"1.2rem","right":"1.2rem"}},"typography":{"fontSize":"0.875rem"}}} -->
				<div class="wp-block-button has-custom-font-size" style="font-size:0.875rem"><a class="wp-block-button__link wp-element-button" href="#selah-app" style="padding-top:0.55rem;padding-right:1.2rem;padding-bottom:0.55rem;padding-left:1.2rem">J’ai un code</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
