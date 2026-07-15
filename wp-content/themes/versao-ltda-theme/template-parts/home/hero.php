<?php
/**
 * Home hero section.
 *
 * @package Versao_Ltda_Theme
 */

$reserve_action = function_exists( 'versao_ltda_get_reserve_action' )
	? versao_ltda_get_reserve_action( 'Demons of Asteborg' )
	: array(
		'href'       => home_url( '/cart/' ),
		'class'      => '',
		'attributes' => '',
	);
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

					<a class="button button--primary <?php echo esc_attr( $reserve_action['class'] ); ?>" href="<?php echo esc_url( $reserve_action['href'] ); ?>"<?php echo $reserve_action['attributes']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php esc_html_e( 'Reservar agora', 'versao-ltda-theme' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
