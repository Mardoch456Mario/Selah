<?php
/**
 * Title: Liste des articles
 * Slug: selah/liste-articles
 * Categories: selah, query
 * Keywords: articles, journal, blog
 * Block Types: core/query
 * Inserter: no
 *
 * @package Selah
 */

?>
<!-- wp:query {"queryId":1,"query":{"perPage":9,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":true},"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
<div class="wp-block-query" style="margin-top:var(--wp--preset--spacing--50)">
	<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"18rem"}} -->
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","style":{"border":{"radius":"1.25rem"}}} /-->

		<!-- wp:post-date {"style":{"spacing":{"margin":{"top":"0.75rem"}}}} /-->

		<!-- wp:post-title {"isLink":true,"level":2,"fontSize":"sous-titre","style":{"spacing":{"margin":{"top":"0.25rem"}}}} /-->

		<!-- wp:post-excerpt {"excerptLength":24,"textColor":"discret","fontSize":"secondaire"} /-->
	<!-- /wp:post-template -->

	<!-- wp:query-pagination {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
		<!-- wp:query-pagination-previous {"label":"Plus récents"} /-->
		<!-- wp:query-pagination-numbers /-->
		<!-- wp:query-pagination-next {"label":"Plus anciens"} /-->
	<!-- /wp:query-pagination -->

	<!-- wp:query-no-results -->
		<!-- wp:paragraph {"textColor":"discret"} -->
		<p class="has-discret-color has-text-color">Rien à afficher pour l’instant. Les premières nouvelles arrivent bientôt.</p>
		<!-- /wp:paragraph -->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
