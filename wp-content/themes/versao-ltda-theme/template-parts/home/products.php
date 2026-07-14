<?php
/**
 * Home products section.
 *
 * @package Versao_Ltda_Theme
 */

?>
<section class="products section" id="produtos">
	<div class="container container--narrow">
		<h2><?php esc_html_e( 'Outros lançamentos', 'versao-ltda-theme' ); ?></h2>
	</div>

	<div class="container container--narrow product-grid">
		<?php
		$products = function_exists( 'versao_ltda_get_catalog_products' )
			? versao_ltda_get_catalog_products( array( 'limit' => 3 ) )
			: array();

		if ( $products ) :
			foreach ( $products as $index => $product ) :
				versao_ltda_render_product_card( $product, $index + 1 );
			endforeach;
		else :
			?>
			<p class="product-grid__empty"><?php esc_html_e( 'Nenhum lançamento cadastrado ainda.', 'versao-ltda-theme' ); ?></p>
		<?php endif; ?>
	</div>
</section>
