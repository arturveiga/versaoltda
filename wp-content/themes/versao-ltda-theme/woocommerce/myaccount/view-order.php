<?php
/**
 * Account order detail header and updates.
 *
 * @package Versao_Ltda_Theme
 * @version 10.6.0
 */

defined( 'ABSPATH' ) || exit;

$notes      = $order->get_customer_order_notes();
$order_date = $order->get_date_created();
?>

<header class="account-order-view__header">
	<a class="account-order-view__back" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>">&larr; <?php esc_html_e( 'Voltar aos pedidos', 'versao-ltda-theme' ); ?></a>
	<div class="account-order-view__heading">
		<div>
			<p class="account-order-view__eyebrow">
				<?php
				printf(
					/* translators: 1: Order number. 2: Order date. */
					esc_html__( 'Pedido #%1$s · %2$s', 'versao-ltda-theme' ),
					esc_html( $order->get_order_number() ),
					esc_html( $order_date ? wc_format_datetime( $order_date, 'd/m/Y' ) : '' )
				);
				?>
			</p>
			<h2><?php esc_html_e( 'Detalhes do pedido', 'versao-ltda-theme' ); ?></h2>
		</div>
		<span class="account-order-view__status account-order-view__status--<?php echo esc_attr( sanitize_html_class( $order->get_status() ) ); ?>">
			<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
		</span>
	</div>
	<p><?php esc_html_e( 'Confira os itens, valores e endereços vinculados a esta compra.', 'versao-ltda-theme' ); ?></p>
</header>

<?php if ( $notes ) : ?>
	<section class="account-order-updates" aria-labelledby="account-order-updates-title">
		<h3 id="account-order-updates-title"><?php esc_html_e( 'Atualizações do pedido', 'versao-ltda-theme' ); ?></h3>
		<ol>
			<?php foreach ( $notes as $note ) : ?>
				<li>
					<time datetime="<?php echo esc_attr( mysql2date( 'c', $note->comment_date ) ); ?>">
						<?php echo esc_html( date_i18n( 'd/m/Y \à\s H:i', strtotime( $note->comment_date ) ) ); ?>
					</time>
					<div><?php echo wp_kses_post( wpautop( wptexturize( $note->comment_content ) ) ); ?></div>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>
<?php endif; ?>

<?php do_action( 'woocommerce_view_order', $order_id ); ?>
