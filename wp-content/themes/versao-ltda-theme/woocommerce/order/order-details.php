<?php
/**
 * Account order details.
 *
 * @package Versao_Ltda_Theme
 * @version 10.9.0
 *
 * @var bool $show_downloads Whether downloads should be rendered.
 */

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $order ) {
	return;
}

$order_items        = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$downloads          = $order->get_downloadable_items();
$actions            = array_filter(
	wc_get_account_orders_actions( $order ),
	function ( $key ) {
		return 'view' !== $key;
	},
	ARRAY_FILTER_USE_KEY
);
$show_customer_details = $order->get_user_id() === get_current_user_id();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>

<section class="account-order-detail">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

	<div class="account-order-detail__layout">
		<div class="account-order-detail__products">
			<h3><?php esc_html_e( 'Itens do pedido', 'versao-ltda-theme' ); ?></h3>

			<div class="account-order-detail__items">
				<?php do_action( 'woocommerce_order_details_before_order_table_items', $order ); ?>

				<?php foreach ( $order_items as $item_id => $item ) : ?>
					<?php
					$product          = $item->get_product();
					$product_name     = $item->get_name();
					$product_url      = $product && $product->is_visible() ? $product->get_permalink( $item ) : '';
					$sku              = $product ? $product->get_sku() : '';
					$quantity         = $item->get_quantity();
					$purchase_note    = $product ? $product->get_purchase_note() : '';
					$line_total       = $order->get_formatted_line_subtotal( $item );
					?>
					<article class="account-order-detail__item">
						<a class="account-order-detail__image" href="<?php echo esc_url( $product_url ?: '#' ); ?>"<?php echo $product_url ? '' : ' aria-disabled="true"'; ?> tabindex="<?php echo $product_url ? '0' : '-1'; ?>">
							<?php echo wp_kses_post( versao_ltda_get_order_product_image( $product, $product_name, 'medium' ) ); ?>
						</a>

						<div class="account-order-detail__item-copy">
							<?php if ( $sku ) : ?>
								<p class="account-order-detail__sku">#<?php echo esc_html( $sku ); ?></p>
							<?php endif; ?>
							<h4>
								<?php if ( $product_url ) : ?>
									<a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product_name ); ?></a>
								<?php else : ?>
									<?php echo esc_html( $product_name ); ?>
								<?php endif; ?>
							</h4>
							<p class="account-order-detail__quantity">
								<?php
								printf(
									/* translators: %s: Product quantity. */
									esc_html__( 'Quantidade: %s', 'versao-ltda-theme' ),
									esc_html( $quantity )
								);
								?>
							</p>
							<?php do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false ); ?>
							<?php wc_display_item_meta( $item ); ?>
							<?php do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false ); ?>
							<?php if ( $show_purchase_note && $purchase_note ) : ?>
								<p class="account-order-detail__note"><?php echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) ); ?></p>
							<?php endif; ?>
						</div>

						<strong class="account-order-detail__price"><?php echo wp_kses_post( $line_total ); ?></strong>
					</article>
				<?php endforeach; ?>

				<?php do_action( 'woocommerce_order_details_after_order_table_items', $order ); ?>
			</div>
		</div>

		<aside class="account-order-detail__summary" aria-labelledby="account-order-summary-title">
			<h3 id="account-order-summary-title"><?php esc_html_e( 'Resumo do pedido', 'versao-ltda-theme' ); ?></h3>
			<dl>
				<?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
					<div class="account-order-detail__total account-order-detail__total--<?php echo esc_attr( sanitize_html_class( $key ) ); ?>">
						<dt><?php echo esc_html( rtrim( $total['label'], ':' ) ); ?></dt>
						<dd><?php echo wp_kses_post( $total['value'] ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>

			<?php if ( $order->get_customer_note() ) : ?>
				<div class="account-order-detail__customer-note">
					<strong><?php esc_html_e( 'Observação', 'versao-ltda-theme' ); ?></strong>
					<p><?php echo wp_kses_post( nl2br( wc_wptexturize_order_note( $order->get_customer_note() ) ) ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $actions ) : ?>
				<div class="account-order-detail__actions">
					<?php foreach ( $actions as $key => $action ) : ?>
						<?php
						$action_names = array(
							'pay'         => __( 'Pagar agora', 'versao-ltda-theme' ),
							'cancel'      => __( 'Cancelar', 'versao-ltda-theme' ),
							'order-again' => __( 'Comprar novamente', 'versao-ltda-theme' ),
						);
						$action_name = $action_names[ $key ] ?? $action['name'];
						?>
						<a class="button account-order-detail__action account-order-detail__action--<?php echo esc_attr( sanitize_html_class( $key ) ); ?>" href="<?php echo esc_url( $action['url'] ); ?>">
							<?php echo esc_html( $action_name ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</aside>
	</div>

	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</section>

<?php
do_action( 'woocommerce_after_order_details', $order );

if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}
