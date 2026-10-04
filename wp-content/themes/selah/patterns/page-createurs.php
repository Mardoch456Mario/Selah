<?php
/**
 * Title: Page — Créateurs
 * Slug: selah/page-createurs
 * Categories: selah-pages
 * Keywords: créateurs, ateliers, marques
 * Post Types: page
 * Block Types: core/post-content
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
			<!-- wp:paragraph {"className":"selah-surtitre","textColor":"discret","fontSize":"legende"} -->
			<p class="selah-surtitre has-discret-color has-text-color has-legende-font-size">Pour les créateurs</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":1,"className":"selah-fil"} -->
			<h1 class="wp-block-heading selah-fil">Vos créations, essayées avant d’être choisies.</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"discret","fontSize":"sous-titre"} -->
			<p class="has-discret-color has-text-color has-sous-titre-font-size">Selah met en vitrine les créateurs de Cotonou. Chaque visiteur voit vos pièces portées par son propre personnage, demande l’avis de ses amis et garde ce qui lui va.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
			<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#demande-acces">Rejoindre Selah</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:image {"sizeSlug":"full","linkDestination":"none","align":"center","className":"selah-cadre-encre"} -->
			<figure class="wp-block-image aligncenter size-full selah-cadre-encre"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/portant.svg' ) ); ?>" alt="Un portant d’atelier : chemise, robe aux bandes kanvô, boubou et pantalon."/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"selah/bandeau-kanvo"} /-->

<!-- wp:group {"align":"full","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-surface-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:heading {"align":"wide","className":"selah-fil"} -->
	<h2 class="wp-block-heading alignwide selah-fil">Ce que Selah change pour vous.</h2>
	<!-- /wp:heading -->

	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16rem"}} -->
	<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--40)">
		<!-- wp:group {"className":"selah-carte","layout":{"type":"default"}} -->
		<div class="wp-block-group selah-carte">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading">Vos pièces, portées</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"discret","fontSize":"secondaire"} -->
			<p class="has-discret-color has-text-color has-secondaire-font-size">Chacun voit vos créations sur son propre personnage. Plus besoin d’imaginer le rendu à partir d’une photo à plat.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"selah-carte","layout":{"type":"default"}} -->
		<div class="wp-block-group selah-carte">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading">Partagées entre amis</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"discret","fontSize":"secondaire"} -->
			<p class="has-discret-color has-text-color has-secondaire-font-size">Quand quelqu’un demande l’avis de ses proches, c’est votre pièce qui circule et qui se fait connaître.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"selah-carte","layout":{"type":"default"}} -->
		<div class="wp-block-group selah-carte">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading">Le savoir-faire d’ici</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"discret","fontSize":"secondaire"} -->
			<p class="has-discret-color has-text-color has-secondaire-font-size">Selah est pensé pour les créateurs de Cotonou : leurs tissus, leurs coupes, leur façon de faire la mode.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:heading {"align":"wide","className":"selah-fil"} -->
	<h2 class="wp-block-heading alignwide selah-fil">Rejoindre Selah, en trois étapes.</h2>
	<!-- /wp:heading -->

	<!-- wp:columns {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"},"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns alignwide" style="margin-top:var(--wp--preset--spacing--50)">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"className":"selah-numero","fontSize":"titre","style":{"typography":{"fontWeight":"800"}}} -->
			<p class="selah-numero has-titre-font-size" style="font-weight:800">01</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading">Faites une demande</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"discret"} -->
			<p class="has-discret-color has-text-color">Remplissez le formulaire ci-dessous en indiquant le nom de votre marque ou de votre atelier.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"className":"selah-numero","fontSize":"titre","style":{"typography":{"fontWeight":"800"}}} -->
			<p class="selah-numero has-titre-font-size" style="font-weight:800">02</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading">On fait connaissance</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"discret"} -->
			<p class="has-discret-color has-text-color">L’équipe Selah vous contacte pour découvrir vos pièces et répondre à vos questions.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"className":"selah-numero","fontSize":"titre","style":{"typography":{"fontWeight":"800"}}} -->
			<p class="selah-numero has-titre-font-size" style="font-weight:800">03</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading">Vos pièces entrent au showroom</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"discret"} -->
			<p class="has-discret-color has-text-color">Elles deviennent essayables par les personnages de toute la communauté Selah.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->

<!-- wp:group {"anchor":"demande-acces","align":"full","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"600px"}} -->
<div id="demande-acces" class="wp-block-group alignfull has-surface-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:heading {"textAlign":"center","className":"selah-fil"} -->
	<h2 class="wp-block-heading has-text-align-center selah-fil">Présentez-nous votre travail.</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","textColor":"discret"} -->
	<p class="has-text-align-center has-discret-color has-text-color">Quelques lignes suffisent. Nous revenons vers vous rapidement.</p>
	<!-- /wp:paragraph -->

	<!-- wp:shortcode -->
[selah_demande_acces profil="createur"]
	<!-- /wp:shortcode -->
</div>
<!-- /wp:group -->
