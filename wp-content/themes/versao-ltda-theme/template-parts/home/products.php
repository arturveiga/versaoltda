<?php
/**
 * Home products section.
 *
 * @package Versao_Ltda_Theme
 */

?>
<section class="products section" id="produtos">
	<div class="container container--narrow product-grid">
		<?php
		$products = array(
			array( 'Demons of Asteborg', 'Mega Drive', 'Pre-Order Version', 'R$ 399,00', 'Reservar agora', 'is-available' ),
			array( 'Earthion', 'Mega Drive', 'Pre-Order Version', 'R$ 399,00', 'Em Breve', 'is-soon' ),
			array( 'Daemon Claw', 'Mega Drive', 'Pre-Order Version', 'R$ 399,00', 'Esgotado', 'is-sold' ),
		);
		foreach ( $products as $product ) :
			?>
			<article class="product-card">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-box.jpg' ); ?>" alt="<?php echo esc_attr( $product[0] ); ?>">
				<p class="product-card__label">NOVO</p>
				<h3><?php echo esc_html( $product[0] ); ?></h3>
				<p><?php echo esc_html( $product[1] ); ?><br><?php echo esc_html( $product[2] ); ?></p>
				<strong><?php echo esc_html( $product[3] ); ?></strong>
				<a class="button product-card__button <?php echo esc_attr( $product[5] ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $product[4] ); ?></a>
			</article>
		<?php endforeach; ?>
	</div>
</section>
