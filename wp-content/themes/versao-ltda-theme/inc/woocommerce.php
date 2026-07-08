<?php
/**
 * WooCommerce integration.
 *
 * @package Versao_Ltda_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Find a WooCommerce product by its exact title.
 *
 * @param string $title Product title.
 * @return WC_Product|null
 */
function versao_ltda_get_product_by_title( $title ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return null;
	}

	$query = new WP_Query(
		array(
			'post_type'              => 'product',
			'post_status'            => 'publish',
			's'                      => $title,
			'posts_per_page'         => 10,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	foreach ( $query->posts as $post ) {
		if ( $title === get_the_title( $post ) ) {
			return wc_get_product( $post->ID );
		}
	}

	return null;
}

/**
 * Reserve action data, wired to WooCommerce when the product exists.
 *
 * @param string $title Product title.
 * @return array
 */
function versao_ltda_get_reserve_action( $title ) {
	$product = versao_ltda_get_product_by_title( $title );

	if ( $product && $product->is_purchasable() && $product->is_in_stock() ) {
		return array(
			'href'       => add_query_arg(
				array(
					'add-to-cart' => $product->get_id(),
					'quantity'    => 1,
				),
				wc_get_cart_url()
			),
			'class'      => 'add_to_cart_button',
			'attributes' => sprintf(
				' data-product_id="%1$s" data-product_sku="%2$s" data-quantity="1" rel="nofollow"',
				esc_attr( $product->get_id() ),
				esc_attr( $product->get_sku() )
			),
		);
	}

	return array(
		'href'       => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/' ),
		'class'      => '',
		'attributes' => '',
	);
}

/**
 * Product card action data, wired to WooCommerce when the product exists.
 *
 * @param string $title Product title.
 * @param string $state Visual state.
 * @return array
 */
function versao_ltda_get_product_card_action( $title, $state ) {
	if ( 'is-available' !== $state ) {
		return array(
			'href'       => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/' ),
			'class'      => '',
			'attributes' => '',
		);
	}

	return versao_ltda_get_reserve_action( $title );
}

/**
 * Keep cart counter fresh after WooCommerce AJAX add-to-cart.
 *
 * @param array $fragments WooCommerce fragments.
 * @return array
 */
function versao_ltda_cart_count_fragment( $fragments ) {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $fragments;
	}

	ob_start();
	?>
	<span class="cart-counter" aria-hidden="true">
		<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
	</span>
	<?php
	$fragments['.cart-counter'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'versao_ltda_cart_count_fragment' );
