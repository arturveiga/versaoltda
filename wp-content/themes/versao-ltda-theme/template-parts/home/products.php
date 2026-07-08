<?php
/**
 * Home products section.
 *
 * @package Versao_Ltda_Theme
 */

?>
<section class="products section" id="produtos">
	<div class="container container--narrow">
		<h2><?php esc_html_e( 'Outros lançamentos', 'versao-ltda-theme' ); ?></h2>
	</div>

	<div class="container container--narrow product-grid">
		<?php
		$products = array(
			array( '#001', 'Demons of Asteborg', 'Mega Drive', 'Pre-Order Version', 'R$ 399,00', 'Reservar agora', 'is-available' ),
			array( '#002', 'Earthion', 'Mega Drive', 'Pre-Order Version', 'R$ 399,00', 'Em Breve', 'is-soon' ),
			array( '#003', 'Daemon Claw', 'Mega Drive', 'Pre-Order Version', 'R$ 399,00', 'Esgotado', 'is-sold' ),
		);
		foreach ( $products as $product ) :
			$action = function_exists( 'versao_ltda_get_product_card_action' )
				? versao_ltda_get_product_card_action( $product[1], $product[6] )
				: array(
					'href'       => home_url( '/' ),
					'class'      => '',
					'attributes' => '',
				);
			?>
			<article class="product-card">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-box.jpg' ); ?>" alt="<?php echo esc_attr( $product[1] ); ?>">
				<p class="product-card__label"><?php echo esc_html( $product[0] ); ?></p>
				<h3><?php echo esc_html( $product[1] ); ?></h3>
				<p><?php esc_html_e( 'Plataforma:', 'versao-ltda-theme' ); ?> <?php echo esc_html( $product[2] ); ?><br><?php echo esc_html( $product[3] ); ?></p>
				<strong><?php echo esc_html( $product[4] ); ?></strong>
				<a class="button product-card__button <?php echo esc_attr( trim( $product[6] . ' ' . $action['class'] ) ); ?>" href="<?php echo esc_url( $action['href'] ); ?>"<?php echo $action['attributes']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php echo esc_html( $product[5] ); ?>
				</a>
			</article>
		<?php endforeach; ?>
	</div>
</section>
