<?php
/**
 * Whitelist page template.
 *
 * @package Versao_Ltda_Theme
 */

get_header();

$wishlist_ids = function_exists( 'versao_ltda_get_wishlist_product_ids' ) ? versao_ltda_get_wishlist_product_ids() : array();
$products     = array_values(
	array_filter(
		array_map(
			static function ( $product_id ) {
				return function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : null;
			},
			$wishlist_ids
		)
	)
);
$user         = wp_get_current_user();
$user_name    = $user && $user->exists() ? $user->display_name : __( '[Nome]', 'versao-ltda-theme' );
$updated      = isset( $_GET['wishlist_updated'] ) ? sanitize_key( wp_unslash( $_GET['wishlist_updated'] ) ) : '';
?>

<main id="main" class="site-main wishlist-page">
	<section class="wishlist-account section">
		<div class="container wishlist-account__inner">
			<aside class="wishlist-account__sidebar">
				<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Caminho de navegacao', 'versao-ltda-theme' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'versao-ltda-theme' ); ?></a>
					<span>/</span>
					<span><?php esc_html_e( 'Minha Conta', 'versao-ltda-theme' ); ?></span>
				</nav>

				<h1>
					<?php
					printf(
						/* translators: %s: logged user display name. */
						esc_html__( 'Olá, %s', 'versao-ltda-theme' ),
						esc_html( $user_name )
					);
					?>
				</h1>
				<p class="wishlist-account__intro"><?php esc_html_e( 'Bem-vindo à sua conta VERSÃO LTDA.', 'versao-ltda-theme' ); ?></p>

				<nav class="wishlist-account__menu" aria-label="<?php esc_attr_e( 'Menu da conta', 'versao-ltda-theme' ); ?>">
					<a href="<?php echo esc_url( function_exists( 'wc_get_account_endpoint_url' ) ? wc_get_account_endpoint_url( 'orders' ) : home_url( '/minha-conta/' ) ); ?>">
						<strong><?php esc_html_e( 'Pedidos e pré-vendas', 'versao-ltda-theme' ); ?></strong>
						<span><?php esc_html_e( 'Veja o status das suas compras.', 'versao-ltda-theme' ); ?></span>
					</a>
					<a href="<?php echo esc_url( versao_ltda_get_account_address_url() ); ?>">
						<strong><?php esc_html_e( 'Dados e endereço', 'versao-ltda-theme' ); ?></strong>
						<span><?php esc_html_e( 'Mantenha seus dados de entrega e cobrança atualizados.', 'versao-ltda-theme' ); ?></span>
					</a>
					<a class="is-active" href="<?php echo esc_url( versao_ltda_get_wishlist_url() ); ?>">
						<strong><?php esc_html_e( 'Lista de desejos', 'versao-ltda-theme' ); ?></strong>
						<span><?php esc_html_e( 'Salve edições que você quer acompanhar.', 'versao-ltda-theme' ); ?></span>
					</a>
					<a href="<?php echo esc_url( is_user_logged_in() && function_exists( 'wc_logout_url' ) ? wc_logout_url() : wp_login_url( versao_ltda_get_wishlist_url() ) ); ?>">
						<strong><?php echo is_user_logged_in() ? esc_html__( 'Sair', 'versao-ltda-theme' ) : esc_html__( 'Entrar', 'versao-ltda-theme' ); ?></strong>
					</a>
				</nav>
			</aside>

			<section class="wishlist-account__content" aria-labelledby="wishlist-title">
				<h2 id="wishlist-title"><?php esc_html_e( 'Lista de desejos', 'versao-ltda-theme' ); ?></h2>
				<p><?php esc_html_e( 'Salve edições do seu interesse para acompanhar depois.', 'versao-ltda-theme' ); ?></p>

				<?php if ( in_array( $updated, array( 'add', 'remove' ), true ) ) : ?>
					<p class="wishlist-account__notice" role="status">
						<?php echo 'remove' === $updated ? esc_html__( 'Produto removido da sua lista de desejos.', 'versao-ltda-theme' ) : esc_html__( 'Produto adicionado à sua lista de desejos.', 'versao-ltda-theme' ); ?>
					</p>
				<?php endif; ?>

				<div class="product-grid wishlist-page__grid">
					<?php if ( $products ) : ?>
						<?php
						foreach ( $products as $index => $catalog_product ) :
							versao_ltda_render_product_card( $catalog_product, $index + 1, 'remove' );
						endforeach;
						?>
					<?php else : ?>
						<div class="wishlist-page__empty">
							<h3><?php esc_html_e( 'Sua lista de desejos está vazia.', 'versao-ltda-theme' ); ?></h3>
							<p><?php esc_html_e( 'Use o coração nos jogos para salvar as edições que você quer acompanhar.', 'versao-ltda-theme' ); ?></p>
							<a class="button" href="<?php echo esc_url( home_url( '/jogos/' ) ); ?>"><?php esc_html_e( 'Ver jogos', 'versao-ltda-theme' ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</div>
	</section>
</main>

<?php
get_footer();
