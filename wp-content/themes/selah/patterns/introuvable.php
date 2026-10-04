<?php
/**
 * Title: Page introuvable
 * Slug: selah/introuvable
 * Categories: selah
 * Inserter: no
 *
 * @package Selah
 */

?>
<!-- wp:paragraph {"className":"selah-numero","style":{"typography":{"fontWeight":"800"}},"fontSize":"titre"} -->
<p class="selah-numero has-titre-font-size" style="font-weight:800">404</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"className":"selah-fil"} -->
<h1 class="wp-block-heading selah-fil">Cette page ne nous va pas.</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"discret","fontSize":"sous-titre"} -->
<p class="has-discret-color has-text-color has-sous-titre-font-size">Elle n’existe pas, ou plus. Essaie autre chose : tu trouveras sûrement ce qui te va.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
	<!-- wp:button -->
	<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>">Retour à l’accueil</a></div>
	<!-- /wp:button -->

	<!-- wp:button {"className":"is-style-outline"} -->
	<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#selah-app">Ouvrir Selah</a></div>
	<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
