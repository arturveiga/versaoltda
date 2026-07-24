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
		$is_games_page       = is_page_template( 'page-jogos.php' ) || is_page( 'jogos' );
		$is_cart_page        = is_page_template( 'page-cart.php' ) || ( function_exists( 'is_cart' ) && is_cart() );
		$reservable_only     = $is_games_page || $is_cart_page;
		$product_query_limit = $reservable_only ? -1 : 3;
		$products            = function_exists( 'versao_ltda_get_catalog_products' )
			? versao_ltda_get_catalog_products( array( 'limit' => $product_query_limit ) )
			: array();

		if ( $reservable_only && function_exists( 'versao_ltda_product_can_be_reserved' ) ) {
			$products = array_values( array_filter( $products, 'versao_ltda_product_can_be_reserved' ) );
		}

		if ( $is_cart_page ) {
			$cart_product_ids = array();

			if ( function_exists( 'WC' ) && WC()->cart ) {
				foreach ( WC()->cart->get_cart() as $cart_item ) {
					$cart_product_ids[] = (int) $cart_item['product_id'];
				}
			}

			if ( $cart_product_ids ) {
				$products = array_values(
					array_filter(
						$products,
						static function ( $product ) use ( $cart_product_ids ) {
							return $product instanceof WC_Product
								&& ! in_array( $product->get_id(), $cart_product_ids, true );
						}
					)
				);
			}

			$products = array_slice( $products, 0, 3 );
		}

		if ( $products ) :
			foreach ( $products as $index => $product ) :
				versao_ltda_render_product_card( $product, $index + 1 );
			endforeach;
		else :
			?>
			<p class="product-grid__empty">
				<?php
				if ( $reservable_only ) {
					esc_html_e( 'Nenhum jogo disponível para reserva agora.', 'versao-ltda-theme' );
				} else {
					esc_html_e( 'Nenhum lançamento cadastrado ainda.', 'versao-ltda-theme' );
				}
				?>
			</p>
		<?php endif; ?>
	</div>
</section>
