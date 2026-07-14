<?php
/**
 * WooCommerce shop archive.
 *
 * @package Versao_Ltda_Theme
 */

get_header();

$products = function_exists( 'versao_ltda_get_catalog_products' )
	? versao_ltda_get_catalog_products( array( 'limit' => 12 ) )
	: array();
?>

<main id="main" class="site-main shop-page">
	<section class="page-heading section">
		<div class="container container--narrow">
			<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Caminho de navegação', 'versao-ltda-theme' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'versao-ltda-theme' ); ?></a>
				<span>/</span>
				<span><?php esc_html_e( 'Shop', 'versao-ltda-theme' ); ?></span>
			</nav>

			<p class="section-kicker">VL / <?php esc_html_e( 'Loja', 'versao-ltda-theme' ); ?></p>
			<h1><?php esc_html_e( 'Shop.', 'versao-ltda-theme' ); ?></h1>
		</div>
	</section>

	<section class="products section shop-products">
		<div class="container container--narrow product-grid">
			<?php if ( $products ) : ?>
				<?php
				foreach ( $products as $index => $product ) :
					versao_ltda_render_product_card( $product, $index + 1 );
				endforeach;
				?>
			<?php else : ?>
				<p class="product-grid__empty"><?php esc_html_e( 'Nenhum produto encontrado.', 'versao-ltda-theme' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
