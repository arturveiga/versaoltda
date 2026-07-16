<?php
/**
 * Account orders and pre-orders.
 *
 * @package Versao_Ltda_Theme
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders );
?>

<header class="account-orders__header">
	<h2><?php esc_html_e( 'Pedidos e pré-vendas', 'versao-ltda-theme' ); ?></h2>
	<p><?php esc_html_e( 'Consulte o status das suas compras, acompanhe reservas e veja os detalhes de cada edição adquirida.', 'versao-ltda-theme' ); ?></p>
</header>

<?php if ( $has_orders ) : ?>
	<div class="account-orders__list">
		<?php
		foreach ( $customer_orders->orders as $customer_order ) :
			$order           = wc_get_order( $customer_order );
			$order_date      = $order->get_date_created();
			$order_actions   = wc_get_account_orders_actions( $order );
			$shipping_method = $order->get_shipping_method() ?: __( 'A definir', 'versao-ltda-theme' );
			$payment_method  = $order->get_payment_method_title() ?: __( 'Não informado', 'versao-ltda-theme' );
			?>
			<article class="account-order account-order--<?php echo esc_attr( $order->get_status() ); ?>">
				<header class="account-order__header">
					<h3>
						<span><?php esc_html_e( 'Pedido:', 'versao-ltda-theme' ); ?></span>
						<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">#<?php echo esc_html( $order->get_order_number() ); ?></a>
						<?php if ( $order_date ) : ?>
							<time datetime="<?php echo esc_attr( $order_date->date( 'c' ) ); ?>">| <?php echo esc_html( wc_format_datetime( $order_date, 'd \d\e F \d\e Y' ) ); ?></time>
						<?php endif; ?>
					</h3>
				</header>

				<div class="account-order__panel">
					<div class="account-order__items">
						<?php foreach ( $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) ) as $item_id => $item ) : ?>
							<?php
							$product        = $item->get_product();
							$product_name   = $item->get_name();
							$product_url    = $product && $product->is_visible() ? $product->get_permalink( $item ) : '';
							$sku             = $product ? $product->get_sku() : '';
							$quantity        = $item->get_quantity();
							$item_total      = $order->get_formatted_line_subtotal( $item );
							?>
							<div class="account-order__item">
								<a class="account-order__image" href="<?php echo esc_url( $product_url ?: $order->get_view_order_url() ); ?>" aria-label="<?php echo esc_attr( $product_name ); ?>">
									<?php echo wp_kses_post( versao_ltda_get_order_product_image( $product, $product_name ) ); ?>
								</a>

								<div class="account-order__item-content">
									<h4>
										<?php if ( $sku ) : ?><span>#<?php echo esc_html( $sku ); ?> - </span><?php endif; ?>
										<?php if ( $product_url ) : ?>
											<a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product_name ); ?></a>
										<?php else : ?>
											<?php echo esc_html( $product_name ); ?>
										<?php endif; ?>
										<span class="account-order__quantity">× <?php echo esc_html( $quantity ); ?></span>
									</h4>
									<p><strong><?php esc_html_e( 'Envio:', 'versao-ltda-theme' ); ?></strong> <?php echo esc_html( $shipping_method ); ?></p>
									<p><strong><?php esc_html_e( 'Pagamento:', 'versao-ltda-theme' ); ?></strong> <?php echo esc_html( $payment_method ); ?></p>
									<p class="account-order__item-total"><?php echo wp_kses_post( $item_total ); ?></p>
									<?php do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false ); ?>
									<?php wc_display_item_meta( $item ); ?>
									<?php do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false ); ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<footer class="account-order__footer">
						<p class="account-order__total"><strong><?php esc_html_e( 'Total:', 'versao-ltda-theme' ); ?></strong> <?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></p>
						<div class="account-order__actions">
							<?php foreach ( $order_actions as $key => $action ) : ?>
								<?php
								$action_names = array(
									'view'        => __( 'Detalhes', 'versao-ltda-theme' ),
									'pay'         => __( 'Pagar agora', 'versao-ltda-theme' ),
									'cancel'      => __( 'Cancelar', 'versao-ltda-theme' ),
									'order-again' => __( 'Comprar novamente', 'versao-ltda-theme' ),
								);
								$action_name = $action_names[ $key ] ?? $action['name'];
								?>
								<a class="button account-order__action account-order__action--<?php echo esc_attr( sanitize_html_class( $key ) ); ?>" href="<?php echo esc_url( $action['url'] ); ?>">
									<?php echo esc_html( $action_name ); ?>
								</a>
							<?php endforeach; ?>
							<span class="account-order__status"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span>
						</div>
					</footer>
				</div>
			</article>
		<?php endforeach; ?>
	</div>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<nav class="account-orders__pagination" aria-label="<?php esc_attr_e( 'Paginação de pedidos', 'versao-ltda-theme' ); ?>">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Anterior', 'versao-ltda-theme' ); ?></a>
			<?php endif; ?>
			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Próxima', 'versao-ltda-theme' ); ?></a>
			<?php endif; ?>
		</nav>
	<?php endif; ?>
<?php else : ?>
	<div class="account-orders__empty">
		<h3><?php esc_html_e( 'Nenhum pedido encontrado.', 'versao-ltda-theme' ); ?></h3>
		<p><?php esc_html_e( 'Suas compras e reservas aparecerão aqui assim que forem realizadas.', 'versao-ltda-theme' ); ?></p>
		<a class="button" href="<?php echo esc_url( home_url( '/jogos/' ) ); ?>"><?php esc_html_e( 'Ver jogos', 'versao-ltda-theme' ); ?></a>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
