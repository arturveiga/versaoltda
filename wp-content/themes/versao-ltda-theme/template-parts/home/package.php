<?php
/**
 * Home package section.
 *
 * @package Versao_Ltda_Theme
 */

$package_slides = array(
	array( '02.jpg', __( 'Embalagem premium aberta do jogo Demons of Asteborg', 'versao-ltda-theme' ) ),
	array( '03.jpg', __( 'Interior da embalagem premium com o cartucho', 'versao-ltda-theme' ) ),
	array( '01b.jpg', __( 'Caixa e cartucho da edição física de Demons of Asteborg', 'versao-ltda-theme' ) ),
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
						<?php foreach ( $package_slides as $slide ) : ?>
							<figure class="details__slide" data-details-slide>
								<img class="details__image" src="<?php echo esc_url( vltda_asset( 'images/' . $slide[0] ) ); ?>" alt="<?php echo esc_attr( $slide[1] ); ?>">
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
