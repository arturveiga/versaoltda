<?php
/**
 * Order billing and shipping addresses.
 *
 * @package Versao_Ltda_Theme
 * @version 8.7.0
 */

defined( 'ABSPATH' ) || exit;

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>

<section class="account-order-addresses" aria-labelledby="account-order-addresses-title">
	<h3 id="account-order-addresses-title"><?php esc_html_e( 'Dados de entrega', 'versao-ltda-theme' ); ?></h3>

	<div class="account-order-addresses__grid<?php echo $show_shipping ? '' : ' account-order-addresses__grid--single'; ?>">
		<article class="account-order-address">
			<p class="account-order-address__label"><?php esc_html_e( 'Cobrança', 'versao-ltda-theme' ); ?></p>
			<h4><?php esc_html_e( 'Endereço de cobrança', 'versao-ltda-theme' ); ?></h4>
			<address>
				<?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'Não informado', 'versao-ltda-theme' ) ) ); ?>

				<?php if ( $order->get_billing_phone() ) : ?>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $order->get_billing_phone() ) ); ?>"><?php echo esc_html( $order->get_billing_phone() ); ?></a>
				<?php endif; ?>

				<?php if ( $order->get_billing_email() ) : ?>
					<a href="mailto:<?php echo esc_attr( $order->get_billing_email() ); ?>"><?php echo esc_html( $order->get_billing_email() ); ?></a>
				<?php endif; ?>

				<?php do_action( 'woocommerce_order_details_after_customer_address', 'billing', $order ); ?>
			</address>
		</article>

		<?php if ( $show_shipping ) : ?>
			<article class="account-order-address">
				<p class="account-order-address__label"><?php esc_html_e( 'Entrega', 'versao-ltda-theme' ); ?></p>
				<h4><?php esc_html_e( 'Endereço de entrega', 'versao-ltda-theme' ); ?></h4>
				<address>
					<?php echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'Não informado', 'versao-ltda-theme' ) ) ); ?>

					<?php if ( $order->get_shipping_phone() ) : ?>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $order->get_shipping_phone() ) ); ?>"><?php echo esc_html( $order->get_shipping_phone() ); ?></a>
					<?php endif; ?>

					<?php do_action( 'woocommerce_order_details_after_customer_address', 'shipping', $order ); ?>
				</address>
			</article>
		<?php endif; ?>
	</div>

	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>
</section>
