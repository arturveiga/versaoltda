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
$shipping_postcode = WC()->customer->get_shipping_postcode();
$shipping_packages = WC()->cart->get_shipping_packages();
$chosen_methods    = WC()->session->get( 'chosen_shipping_methods', array() );

foreach ( $shipping_packages as $package_index => $shipping_package ) {
	$cached_package = WC()->session->get( 'shipping_for_package_' . $package_index );

	if ( is_array( $cached_package ) && isset( $cached_package['rates'] ) ) {
		$shipping_packages[ $package_index ]['rates'] = $cached_package['rates'];
	}
}

$selected_shipping_total = 0.0;

foreach ( $shipping_packages as $package_index => $shipping_package ) {
	$chosen_rate_id = isset( $chosen_methods[ $package_index ] ) ? $chosen_methods[ $package_index ] : '';

	if ( ! $chosen_rate_id || empty( $shipping_package['rates'][ $chosen_rate_id ] ) ) {
		continue;
	}

	$chosen_rate             = $shipping_package['rates'][ $chosen_rate_id ];
	$selected_shipping_total += (float) $chosen_rate->get_cost() + array_sum( array_map( 'floatval', $chosen_rate->get_taxes() ) );
}

$current_shipping_total = (float) WC()->cart->get_shipping_total() + (float) WC()->cart->get_shipping_tax();
$display_cart_total      = (float) WC()->cart->get_total( 'edit' ) + $selected_shipping_total - $current_shipping_total;
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
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_single' ), $cart_item, $cart_item_key );
										echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									} else {
										?>
										<img src="<?php echo esc_url( vltda_asset( 'images/product-photo.jpg' ) ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( $product_name ) ); ?>">
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
								<input type="text" id="calc_shipping_postcode" name="calc_shipping_postcode" value="<?php echo esc_attr( WC()->customer->get_shipping_postcode() ); ?>" placeholder="00000-000" inputmode="numeric" autocomplete="postal-code" maxlength="9" pattern="[0-9]{5}-?[0-9]{3}">
								<button class="button button--primary" type="submit" name="calc_shipping" value="1"><?php esc_html_e( 'OK', 'versao-ltda-theme' ); ?></button>
							</div>

							<?php if ( $shipping_postcode ) : ?>
								<div class="cart-page__shipping-options" aria-live="polite">
									<p class="cart-page__shipping-title"><?php esc_html_e( 'Opções de entrega SuperFrete', 'versao-ltda-theme' ); ?></p>
									<?php
									$has_shipping_rates = false;

									foreach ( $shipping_packages as $package_index => $package ) :
										if ( empty( $package['rates'] ) ) {
											continue;
										}

										$has_shipping_rates = true;
										$chosen_rate        = isset( $chosen_methods[ $package_index ] ) ? $chosen_methods[ $package_index ] : '';
										?>
										<fieldset class="cart-page__shipping-list">
											<legend class="screen-reader-text"><?php esc_html_e( 'Escolha uma opção de entrega', 'versao-ltda-theme' ); ?></legend>
											<?php foreach ( $package['rates'] as $rate_id => $rate ) : ?>
												<?php $field_id = 'shipping_method_' . $package_index . '_' . sanitize_title( $rate_id ); ?>
												<label class="cart-page__shipping-rate" for="<?php echo esc_attr( $field_id ); ?>">
													<input
														id="<?php echo esc_attr( $field_id ); ?>"
														type="radio"
														name="shipping_method[<?php echo esc_attr( $package_index ); ?>]"
														data-index="<?php echo esc_attr( $package_index ); ?>"
														value="<?php echo esc_attr( $rate_id ); ?>"
														<?php checked( $rate_id, $chosen_rate ); ?>
													>
													<span><?php echo wp_kses_post( wc_cart_totals_shipping_method_label( $rate ) ); ?></span>
												</label>
											<?php endforeach; ?>
										</fieldset>
									<?php endforeach; ?>

									<?php if ( ! $has_shipping_rates ) : ?>
										<p class="cart-page__shipping-empty"><?php esc_html_e( 'Nenhuma opção de entrega foi encontrada para este CEP.', 'versao-ltda-theme' ); ?></p>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>

						<div class="cart-page__summary">
							<?php if ( $selected_shipping_total > 0 ) : ?>
								<div class="cart-page__shipping-summary">
									<span><?php esc_html_e( 'Frete', 'versao-ltda-theme' ); ?></span>
									<strong><?php echo wp_kses_post( wc_price( $selected_shipping_total ) ); ?></strong>
								</div>
							<?php endif; ?>
							<p><?php esc_html_e( 'Total', 'versao-ltda-theme' ); ?></p>
							<strong data-cart-total data-currency="<?php echo esc_attr( get_woocommerce_currency() ); ?>">
								<?php echo wp_kses_post( wc_price( $display_cart_total ) ); ?>
							</strong>

							<a class="button button--primary cart-page__checkout" href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
								<?php esc_html_e( 'Ir para o Checkout', 'versao-ltda-theme' ); ?>
							</a>
						</div>
					</div>

					<button class="button cart-page__update" type="submit" name="update_cart" value="<?php esc_attr_e( 'Atualizar carrinho', 'versao-ltda-theme' ); ?>">
						<?php esc_html_e( 'Atualizar carrinho', 'versao-ltda-theme' ); ?>
					</button>
					<button class="button cart-page__update" type="submit" name="versao_ltda_update_shipping" value="1" data-update-shipping>
						<?php esc_html_e( 'Atualizar frete', 'versao-ltda-theme' ); ?>
					</button>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					<?php wp_nonce_field( 'woocommerce-shipping-calculator', 'woocommerce-shipping-calculator-nonce' ); ?>
					<?php wp_nonce_field( 'superfrete_nonce', 'superfrete_nonce', false ); ?>
				</form>
			<?php endif; ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/home/products' ); ?>
</main>

<?php
get_footer();
