<?php
/**
 * Games page template.
 *
 * @package Versao_Ltda_Theme
 */

get_header();
?>

<main id="main" class="site-main games-page">
	<section class="page-heading section">
		<div class="container container--narrow">
			<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Caminho de navegação', 'versao-ltda-theme' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'versao-ltda-theme' ); ?></a>
				<span>/</span>
				<span><?php esc_html_e( 'Jogos', 'versao-ltda-theme' ); ?></span>
			</nav>

			<p class="section-kicker">VL / <?php esc_html_e( 'Catálogo', 'versao-ltda-theme' ); ?></p>
			<h1><?php esc_html_e( 'Jogos', 'versao-ltda-theme' ); ?></h1>
		</div>
	</section>

	<?php get_template_part( 'template-parts/home/products' ); ?>
</main>

<?php
get_footer();
