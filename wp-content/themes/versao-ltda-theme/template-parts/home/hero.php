<?php

/**
 * Home hero section.
 *
 * @package Versao_Ltda_Theme
 */
?>

<section class="hero">

	<div class="hero__media" aria-hidden="true"></div>

	<div class="container">

		<div class="hero__content">

			<div class="hero__copy">

				<div class="hero__release">
					<span><?php esc_html_e('Lançamento', 'versao-ltda-theme'); ?></span>
					<strong>#001</strong>
				</div>

				<img
					class="hero__logo"
					src="<?php echo esc_url(vltda_asset('images/hero-logo.png')); ?>"
					alt="<?php esc_attr_e('Demons of Asteborg', 'versao-ltda-theme'); ?>">

				<p class="hero__description">
					<span><?php esc_html_e('O primeiro lançamento da Versão LTDA.', 'versao-ltda-theme'); ?></span><br>
					<?php esc_html_e(
						'Uma edição física para Mega Drive, concebida com o cuidado de quem coleciona e o rigor de quem projeta.',
						'versao-ltda-theme'
					); ?>
				</p>

				<a class="button button--primary" href="#comprar">
					<?php esc_html_e('Reservar agora', 'versao-ltda-theme'); ?>
				</a>

			</div>

		</div>

	</div>

</section>