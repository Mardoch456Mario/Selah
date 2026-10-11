<?php
/**
 * Title: Résultats de recherche
 * Slug: selah/resultats-recherche
 * Categories: selah, query
 * Block Types: core/query
 * Inserter: no
 *
 * Généré depuis la maquette « Streaming » ; modifiable dans l’éditeur.
 *
 * @package Selah
 */

?>
<!-- wp:query {"queryId":1,"query":{"perPage":9,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":true},"className":"selah-liste-articles"} -->
<div class="wp-block-query selah-liste-articles"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"18rem"}} -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/10"} /-->

<!-- wp:post-date {"style":{"spacing":{"margin":{"top":"0.9rem"}}}} /-->

<!-- wp:post-title {"isLink":true,"style":{"spacing":{"margin":{"top":"0.25rem"}}},"fontSize":"sous-titre"} /-->

<!-- wp:post-excerpt {"excerptLength":40,"textColor":"gris","fontSize":"secondaire"} /-->
<!-- /wp:post-template -->

<!-- wp:query-pagination {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
<!-- wp:query-pagination-previous {"label":"Plus récents"} /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next {"label":"Plus anciens"} /-->
<!-- /wp:query-pagination -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"textColor":"gris","fontSize":"chapo"} -->
<p class="has-gris-color has-text-color has-chapo-font-size">Aucun résultat pour cette recherche. Essaie un autre mot, ou parcours le <a href="<?php echo esc_url( home_url( '/journal/' ) ); ?>">journal</a>.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->
