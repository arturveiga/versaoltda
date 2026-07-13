<?php
/**
 * Account billing address form.
 *
 * @package Versao_Ltda_Theme
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_address_form' );

if ( ! $load_address ) {
	wc_get_template( 'myaccount/my-address.php' );
} else {
	?>
	<header class="account-address__header">
		<h2><?php esc_html_e( 'Dados e endereço', 'versao-ltda-theme' ); ?></h2>
		<p><?php esc_html_e( 'Mantenha seus dados atualizados para garantir o envio correto das suas edições.', 'versao-ltda-theme' ); ?></p>
	</header>

	<form class="account-address__form" method="post" novalidate>
		<div class="woocommerce-address-fields">
			<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

			<div class="woocommerce-address-fields__field-wrapper">
				<?php
				foreach ( $address as $key => $field ) {
					woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
				}
				?>
			</div>

			<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

			<div class="account-address__actions">
				<button type="submit" class="button account-address__save<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="save_address" value="<?php esc_attr_e( 'Salvar', 'versao-ltda-theme' ); ?>">
					<?php esc_html_e( 'Salvar', 'versao-ltda-theme' ); ?>
				</button>
				<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
				<input type="hidden" name="action" value="edit_address">
			</div>
		</div>
	</form>

	<section class="account-payment-methods" aria-labelledby="account-payment-methods-title">
		<h3 id="account-payment-methods-title"><?php esc_html_e( 'Formas de pagamento:', 'versao-ltda-theme' ); ?></h3>
		<p><?php esc_html_e( 'Consulte ou gerencie seus métodos de pagamento para agilizar suas próximas compras.', 'versao-ltda-theme' ); ?></p>
		<a class="button account-payment-methods__button" href="<?php echo esc_url( wc_get_account_endpoint_url( 'payment-methods' ) ); ?>">
			<?php esc_html_e( 'Gerenciar formas de pagamento', 'versao-ltda-theme' ); ?>
		</a>
	</section>
	<?php
}

do_action( 'woocommerce_after_edit_account_address_form' );
