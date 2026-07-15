<?php
/**
 * Home story section.
 *
 * @package Versao_Ltda_Theme
 */

?>
<section class="intro section">
	<div class="container container--narrow">
		<div class="gameplay-carousel" data-gameplay-carousel aria-label="<?php esc_attr_e( 'Cenas de gameplay', 'versao-ltda-theme' ); ?>">
			<div class="gameplay-carousel__viewport">
				<div class="media-strip" data-gameplay-track>
					<figure class="gameplay-carousel__slide" data-gameplay-slide>
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/gameplay-1.jpg' ); ?>" alt="<?php esc_attr_e( 'Cena de gameplay em castelo escuro', 'versao-ltda-theme' ); ?>">
					</figure>
					<figure class="gameplay-carousel__slide" data-gameplay-slide>
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/gameplay-2.jpg' ); ?>" alt="<?php esc_attr_e( 'Cena de gameplay em floresta', 'versao-ltda-theme' ); ?>">
					</figure>
					<figure class="gameplay-carousel__slide" data-gameplay-slide>
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/gameplay-3.jpg' ); ?>" alt="<?php esc_attr_e( 'Cena de gameplay em vila ao pôr do sol', 'versao-ltda-theme' ); ?>">
					</figure>
				</div>
			</div>

			<button class="gameplay-carousel__arrow gameplay-carousel__arrow--prev" type="button" data-gameplay-prev aria-label="<?php esc_attr_e( 'Imagem anterior', 'versao-ltda-theme' ); ?>"></button>
			<button class="gameplay-carousel__arrow gameplay-carousel__arrow--next" type="button" data-gameplay-next aria-label="<?php esc_attr_e( 'Próxima imagem', 'versao-ltda-theme' ); ?>"></button>

			<div class="gameplay-carousel__dots" aria-label="<?php esc_attr_e( 'Selecionar imagem', 'versao-ltda-theme' ); ?>">
				<?php for ( $index = 0; $index < 3; $index++ ) : ?>
					<button type="button" data-gameplay-dot="<?php echo esc_attr( (string) $index ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Ir para a imagem %d', 'versao-ltda-theme' ), $index + 1 ) ); ?>" <?php echo 0 === $index ? 'aria-current="true"' : ''; ?>></button>
				<?php endfor; ?>
			</div>
		</div>

		<div class="intro__grid">
			<h2><?php esc_html_e( 'Prepare-se, você é a última esperança, e falhar não é uma opção!', 'versao-ltda-theme' ); ?></h2>
			<div>
				<p><?php esc_html_e( 'Assuma o papel de Gareth, um cavaleiro treinado para proteger seu povo, e enfrente Zadimus em uma jornada épica para Mega Drive.', 'versao-ltda-theme' ); ?></p>
				<p><?php esc_html_e( 'Explore florestas, montanhas, pântanos e cenários impressionantes em um dos maiores jogos já criados para 16-bit. Com 128 Megabits, animações fluidas, magias, novas habilidades e uma trilha sonora poderosa, Demons of Asteborg entrega uma aventura feita para surpreender jogadores e colecionadores.', 'versao-ltda-theme' ); ?></p>
			</div>
		</div>

		<div class="quote-row" aria-label="<?php esc_attr_e( 'Avaliações de Demons of Asteborg', 'versao-ltda-theme' ); ?>">
			<span class="quote-row__item quote-row__item--first">
				<img src="<?php echo esc_url( vltda_asset( 'images/reviews.svg' ) ); ?>" alt="<?php esc_attr_e( 'Hours of gameplay on offer — Time Extension', 'versao-ltda-theme' ); ?>">
			</span>
			<span class="quote-row__item quote-row__item--second">
				<img src="<?php echo esc_url( vltda_asset( 'images/reviews.svg' ) ); ?>" alt="<?php esc_attr_e( 'Demons of Asteborg is a must-play — Segbits', 'versao-ltda-theme' ); ?>">
			</span>
			<span class="quote-row__item quote-row__item--third">
				<img src="<?php echo esc_url( vltda_asset( 'images/reviews.svg' ) ); ?>" alt="<?php esc_attr_e( 'I love Demons of Asteborg, it is amazing — Otaku Gamers UK', 'versao-ltda-theme' ); ?>">
			</span>
		</div>
	</div>
</section>
