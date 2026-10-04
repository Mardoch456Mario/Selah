<?php
/**
 * Title: Fin d’article
 * Slug: selah/fin-article
 * Categories: selah
 * Inserter: no
 *
 * @package Selah
 */

$selah_journal = (int) get_option( 'page_for_posts' );
$selah_journal = $selah_journal ? get_permalink( $selah_journal ) : home_url( '/' );
?>
<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|40"}}}} -->
<hr class="wp-block-separator has-alpha-channel-opacity" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--40)"/>
<!-- /wp:separator -->

<!-- wp:group {"style":{"spacing":{"blockGap":"1.5rem"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group">
	<!-- wp:post-navigation-link {"type":"previous","label":"Article précédent","showTitle":true} /-->

	<!-- wp:post-navigation-link {"label":"Article suivant","showTitle":true} /-->
</div>
<!-- /wp:group -->

<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
	<!-- wp:button {"className":"is-style-outline"} -->
	<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $selah_journal ); ?>">← Tous les articles</a></div>
	<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
