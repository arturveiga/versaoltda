<?php
/**
 * Home package section.
 *
 * @package Versao_Ltda_Theme
 */

$package_slides = array(
	array( 'package-carousel/01-complete-edition.jpg', __( 'Edição física completa de Demons of Asteborg em cenário ambientado', 'versao-ltda-theme' ) ),
	array( 'package-carousel/02-game-box.jpg', __( 'Caixa da edição física de Demons of Asteborg', 'versao-ltda-theme' ), 'details__image--product-box' ),
	array( 'package-carousel/03-open-premium-package.jpg', __( 'Embalagem premium aberta com o cartucho de Demons of Asteborg', 'versao-ltda-theme' ) ),
	array( 'package-carousel/04-collectors-slipcase.jpg', __( 'Caixa e luva ilustrada da edição de colecionador', 'versao-ltda-theme' ) ),
	array( 'package-carousel/05-complete-contents.jpg', __( 'Conteúdo completo da edição física de Demons of Asteborg', 'versao-ltda-theme' ) ),
	array( 'package-carousel/06-open-edition-contents.jpg', __( 'Embalagem aberta com manual, impressos e cartucho', 'versao-ltda-theme' ) ),
	array( 'package-carousel/07-box-and-cartridge.jpg', __( 'Caixa e cartucho da edição física de Demons of Asteborg', 'versao-ltda-theme' ) ),
	array( 'package-carousel/08-illustrated-card.jpg', __( 'Card ilustrado de Demons of Asteborg', 'versao-ltda-theme' ) ),
	array( 'package-carousel/09-cartridge-board.jpg', __( 'Placas internas do cartucho com tecnologia Mega Drive', 'versao-ltda-theme' ) ),
	array( 'package-carousel/10-cartridge.jpg', __( 'Cartucho de Demons of Asteborg para Mega Drive', 'versao-ltda-theme' ) ),
	array( 'package-carousel/11-cartridge-interior.jpg', __( 'Cartucho aberto exibindo a placa interna', 'versao-ltda-theme' ) ),
);

?>
<section class="details section">
	<div class="container container--narrow">
		<p class="section-kicker"><?php esc_html_e( 'Conteúdo da Edição', 'versao-ltda-theme' ); ?></p>
		<h2><?php esc_html_e( 'Cada detalhe foi pensado.', 'versao-ltda-theme' ); ?></h2>
		<p><?php esc_html_e( 'Uma edição completa, feita para coleção.', 'versao-ltda-theme' ); ?></p>

		<div class="details__carousel" data-details-carousel role="region" aria-label="<?php esc_attr_e( 'Conteúdo completo da edição', 'versao-ltda-theme' ); ?>" tabindex="0">
			<div class="details__showcase">
				<button class="details__arrow details__arrow--prev" type="button" data-details-prev aria-label="<?php esc_attr_e( 'Item anterior', 'versao-ltda-theme' ); ?>"></button>

				<div class="details__viewport">
					<div class="details__track" data-details-track>
						<?php foreach ( $package_slides as $index => $slide ) : ?>
							<figure class="details__slide" data-details-slide>
								<img
									class="details__image<?php echo ! empty( $slide[2] ) ? ' ' . esc_attr( $slide[2] ) : ''; ?>"
									src="<?php echo esc_url( vltda_asset( 'images/' . $slide[0] ) ); ?>"
									alt="<?php echo esc_attr( $slide[1] ); ?>"
									width="1920"
									height="1080"
									decoding="async"
									loading="<?php echo esc_attr( 0 === $index ? 'eager' : 'lazy' ); ?>"
									<?php if ( 0 === $index ) : ?>
										fetchpriority="high"
									<?php endif; ?>
								>
							</figure>
						<?php endforeach; ?>
					</div>
				</div>

				<button class="details__arrow details__arrow--next" type="button" data-details-next aria-label="<?php esc_attr_e( 'Próximo item', 'versao-ltda-theme' ); ?>"></button>
			</div>

			<div class="details__dots" aria-label="<?php esc_attr_e( 'Selecionar imagem', 'versao-ltda-theme' ); ?>">
				<?php foreach ( $package_slides as $index => $slide ) : ?>
					<button type="button" data-details-dot="<?php echo esc_attr( (string) $index ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Ir para a imagem %d', 'versao-ltda-theme' ), $index + 1 ) ); ?>" <?php echo 0 === $index ? 'aria-current="true"' : ''; ?>></button>
				<?php endforeach; ?>
			</div>
		</div>

		<p class="caption"><?php esc_html_e( 'Conteúdo completo da Edição #001 - Edição Especial de Pré-Venda', 'versao-ltda-theme' ); ?></p>

		<?php versao_ltda_home_features(); ?>
	</div>
</section>
