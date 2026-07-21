<?php
/**
 * Checkout page template.
 *
 * @package Versao_Ltda_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="site-main checkout-page">
	<section class="checkout-page__section section">
		<div class="container container--narrow">
			<nav class="breadcrumb checkout-page__breadcrumb" aria-label="<?php esc_attr_e( 'Caminho de navegação', 'versao-ltda-theme' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'versao-ltda-theme' ); ?></a>
				<span>/</span>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'Carrinho', 'versao-ltda-theme' ); ?></a>
				<span>/</span>
				<span aria-current="page"><?php esc_html_e( 'Checkout', 'versao-ltda-theme' ); ?></span>
			</nav>

			<header class="checkout-page__header">
				<h1><?php esc_html_e( 'Finalizar pedido', 'versao-ltda-theme' ); ?></h1>
				<p><?php esc_html_e( 'Preencha seus dados de entrega e revise o pedido antes de concluir a compra.', 'versao-ltda-theme' ); ?></p>
			</header>

			<div class="checkout-page__content">
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
