<?php
/**
 * Home hero section.
 *
 * @package Versao_Ltda_Theme
 */

$featured_product = function_exists( 'versao_ltda_get_product_by_title' )
	? versao_ltda_get_product_by_title( 'Demons of Asteborg' )
	: null;
$product_url      = $featured_product
	? get_permalink( $featured_product->get_id() )
	: home_url( '/produto/demons-of-asteborg/' );
?>

<section class="hero">
	<div class="hero__media" aria-hidden="true"></div>

	<div class="container">
		<div class="hero__content">
			<div class="hero__copy">
				<div class="hero__release">
					<span><?php esc_html_e( 'Lançamento', 'versao-ltda-theme' ); ?></span>
					<strong>#001</strong>
				</div>

				<img
					class="hero__logo"
					src="<?php echo esc_url( vltda_asset( 'images/hero-logo.png' ) ); ?>"
					alt="<?php esc_attr_e( 'Demons of Asteborg', 'versao-ltda-theme' ); ?>">

				<div class="hero__cta">
					<p class="hero__description">
						<strong class="hero__description-title"><?php esc_html_e( 'O primeiro lançamento da VERSÃO LTDA.', 'versao-ltda-theme' ); ?></strong>
						<span class="hero__description-text">
							<?php esc_html_e(
								'Uma edição física para Mega Drive, concebida com o cuidado de quem coleciona e o rigor de quem projeta.',
								'versao-ltda-theme'
							); ?>
						</span>
					</p>

					<a class="button button--primary" href="<?php echo esc_url( $product_url ); ?>">
						<?php esc_html_e( 'Conhecer Mais', 'versao-ltda-theme' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
