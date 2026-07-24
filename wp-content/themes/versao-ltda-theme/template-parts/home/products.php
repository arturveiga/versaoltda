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
		$is_games_page = is_page_template( 'page-jogos.php' ) || is_page( 'jogos' );
		$products = function_exists( 'versao_ltda_get_catalog_products' )
			? versao_ltda_get_catalog_products( array( 'limit' => $is_games_page ? -1 : 3 ) )
			: array();

		if ( $is_games_page && function_exists( 'versao_ltda_product_can_be_reserved' ) ) {
			$products = array_values( array_filter( $products, 'versao_ltda_product_can_be_reserved' ) );
		}

		if ( $products ) :
			foreach ( $products as $index => $product ) :
				versao_ltda_render_product_card( $product, $index + 1 );
			endforeach;
		else :
			?>
			<p class="product-grid__empty">
				<?php
				if ( $is_games_page ) {
					esc_html_e( 'Nenhum jogo disponível para reserva agora.', 'versao-ltda-theme' );
				} else {
					esc_html_e( 'Nenhum lançamento cadastrado ainda.', 'versao-ltda-theme' );
				}
				?>
			</p>
		<?php endif; ?>
	</div>
</section>
