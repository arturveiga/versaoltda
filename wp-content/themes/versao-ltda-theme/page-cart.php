<?php
/**
 * Cart page template.
 *
 * @package Versao_Ltda_Theme
 */

get_header();

if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
	?>
	<main id="main" class="site-main cart-page section">
		<div class="container container--narrow">
			<p><?php esc_html_e( 'WooCommerce precisa estar ativo para exibir o carrinho.', 'versao-ltda-theme' ); ?></p>
		</div>
	</main>
	<?php
	get_footer();
	return;
}

$cart_items = WC()->cart->get_cart();
?>

<main id="main" class="site-main cart-page">
	<section class="cart-page__main section">
		<div class="container container--narrow">
			<p class="section-kicker cart-page__kicker"><?php esc_html_e( 'Carrinho', 'versao-ltda-theme' ); ?></p>
			<h1><?php esc_html_e( 'Seu carrinho.', 'versao-ltda-theme' ); ?></h1>

			<div class="woocommerce-notices-wrapper">
				<?php wc_print_notices(); ?>
			</div>

			<?php if ( empty( $cart_items ) ) : ?>
				<div class="cart-page__empty">
					<p><?php esc_html_e( 'Seu carrinho está vazio por enquanto.', 'versao-ltda-theme' ); ?></p>
					<a class="button button--primary" href="<?php echo esc_url( home_url( '/#produtos' ) ); ?>">
						<?php esc_html_e( 'Ver lançamentos', 'versao-ltda-theme' ); ?>
					</a>
				</div>
			<?php else : ?>
				<form class="cart-page__form woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
					<div class="cart-page__items">
						<?php
						$item_index = 1;
						foreach ( $cart_items as $cart_item_key => $cart_item ) :
							$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

							if ( ! $_product instanceof WC_Product || ! $_product->exists() || $cart_item['quantity'] <= 0 ) {
								continue;
							}

							$product_id        = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
							$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							$product_label     = $_product->get_sku() ? '#' . $_product->get_sku() : sprintf( '#%03d', $item_index );
							$edition_label     = 1 === $item_index ? __( 'Edição Exclusiva de Pré-Venda', 'versao-ltda-theme' ) : __( 'Edição Regular', 'versao-ltda-theme' );
							$platform          = $_product->get_attribute( 'plataforma' ) ?: $_product->get_attribute( 'pa_plataforma' );

							if ( ! $platform ) {
								$platform = __( 'Mega Drive / Genesis', 'versao-ltda-theme' );
							}
							$unit_price = wc_get_price_to_display( $_product );
							?>
							<article class="cart-page__item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>" data-cart-item data-unit-price="<?php echo esc_attr( $unit_price ); ?>">
								<a class="cart-page__thumb" href="<?php echo esc_url( $product_permalink ?: '#' ); ?>" aria-label="<?php echo esc_attr( wp_strip_all_tags( $product_name ) ); ?>">
									<?php
									if ( has_post_thumbnail( $product_id ) ) {
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
										echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									} else {
										?>
										<img src="<?php echo esc_url( vltda_asset( 'images/product-box.jpg' ) ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( $product_name ) ); ?>">
										<?php
									}
									?>
								</a>

								<div class="cart-page__item-content">
									<p class="cart-page__item-label">
										<?php echo esc_html( $product_label . ' - ' . $edition_label ); ?>
									</p>

									<h2>
										<?php if ( $product_permalink ) : ?>
											<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo esc_html( $product_name ); ?></a>
										<?php else : ?>
											<?php echo esc_html( $product_name ); ?>
										<?php endif; ?>
									</h2>

									<p class="cart-page__platform"><?php echo esc_html( $platform ); ?></p>

									<?php
									$item_data = wc_get_formatted_cart_item_data( $cart_item );
									if ( $item_data ) :
										?>
										<div class="cart-page__meta"><?php echo wp_kses_post( $item_data ); ?></div>
									<?php endif; ?>

									<strong class="cart-page__price">
										<?php echo wp_kses_post( WC()->cart->get_product_price( $_product ) ); ?>
									</strong>
								</div>

								<div class="cart-page__item-actions">
									<div class="quantity cart-page__quantity">
										<button class="cart-page__qty-button" type="button" data-cart-qty="minus" aria-label="<?php esc_attr_e( 'Diminuir quantidade', 'versao-ltda-theme' ); ?>">-</button>
										<?php
										woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $_product->get_max_purchase_quantity(),
												'min_value'    => $_product->is_sold_individually() ? 1 : 0,
												'product_name' => $product_name,
											),
											$_product,
											true
										);
										?>
										<button class="cart-page__qty-button" type="button" data-cart-qty="plus" aria-label="<?php esc_attr_e( 'Aumentar quantidade', 'versao-ltda-theme' ); ?>">+</button>
									</div>

									<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a role="button" href="%s" class="cart-page__remove remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">X %s</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_attr( sprintf( __( 'Remover %s do carrinho', 'versao-ltda-theme' ), wp_strip_all_tags( $product_name ) ) ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() ),
											esc_html__( 'Remover', 'versao-ltda-theme' )
										),
										$cart_item_key
									);
									?>
								</div>
							</article>
							<?php
							$item_index++;
						endforeach;
						?>
					</div>

					<div class="cart-page__bottom">
						<div class="cart-page__forms">
							<div class="cart-page__inline-form">
								<label class="screen-reader-text" for="calc_shipping_postcode"><?php esc_html_e( 'CEP para calcular o frete', 'versao-ltda-theme' ); ?></label>
								<input type="hidden" name="calc_shipping_country" value="BR">
								<input type="text" id="calc_shipping_postcode" name="calc_shipping_postcode" value="<?php echo esc_attr( WC()->customer->get_shipping_postcode() ); ?>" placeholder="<?php esc_attr_e( 'CEP para calcular o frete', 'versao-ltda-theme' ); ?>">
								<button class="button button--primary" type="submit" name="calc_shipping" value="1"><?php esc_html_e( 'OK', 'versao-ltda-theme' ); ?></button>
							</div>

							<?php if ( wc_coupons_enabled() ) : ?>
								<div class="cart-page__inline-form">
									<label class="screen-reader-text" for="coupon_code"><?php esc_html_e( 'Cupom de desconto', 'versao-ltda-theme' ); ?></label>
									<input type="text" id="coupon_code" name="coupon_code" placeholder="<?php esc_attr_e( 'cupom de desconto', 'versao-ltda-theme' ); ?>">
									<button class="button button--primary" type="submit" name="apply_coupon" value="<?php esc_attr_e( 'Aplicar cupom', 'versao-ltda-theme' ); ?>"><?php esc_html_e( 'OK', 'versao-ltda-theme' ); ?></button>
								</div>
							<?php endif; ?>
						</div>

						<div class="cart-page__summary">
							<p><?php esc_html_e( 'Total', 'versao-ltda-theme' ); ?></p>
							<strong data-cart-total data-currency="<?php echo esc_attr( get_woocommerce_currency() ); ?>">
								<?php echo wp_kses_post( WC()->cart->get_total() ); ?>
							</strong>

							<a class="button button--primary cart-page__checkout" href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
								<?php esc_html_e( 'Continuar para Finalização', 'versao-ltda-theme' ); ?>
							</a>
						</div>
					</div>

					<button class="button cart-page__update" type="submit" name="update_cart" value="<?php esc_attr_e( 'Atualizar carrinho', 'versao-ltda-theme' ); ?>">
						<?php esc_html_e( 'Atualizar carrinho', 'versao-ltda-theme' ); ?>
					</button>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					<?php wp_nonce_field( 'woocommerce-shipping-calculator', 'woocommerce-shipping-calculator-nonce' ); ?>
				</form>
			<?php endif; ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/home/products' ); ?>
</main>

<?php
get_footer();
