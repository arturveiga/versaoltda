<?php
/**
 * Home package section.
 *
 * @package Versao_Ltda_Theme
 */

?>
<section class="details section">
	<div class="container container--narrow">
		<p class="section-kicker"><?php esc_html_e( 'Conteúdo da Edição', 'versao-ltda-theme' ); ?></p>
		<h2><?php esc_html_e( 'Cada detalhe foi pensado.', 'versao-ltda-theme' ); ?></h2>
		<p><?php esc_html_e( 'Uma edição completa, feita para coleção.', 'versao-ltda-theme' ); ?></p>

		<div class="details__showcase" aria-label="<?php esc_attr_e( 'Conteúdo completo da edição', 'versao-ltda-theme' ); ?>">
			<button class="details__arrow details__arrow--prev" type="button" aria-label="<?php esc_attr_e( 'Item anterior', 'versao-ltda-theme' ); ?>"></button>
			<img class="details__image" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/package-open.jpg' ); ?>" alt="<?php esc_attr_e( 'Embalagem premium aberta do jogo Demons of Asteborg', 'versao-ltda-theme' ); ?>">
			<button class="details__arrow details__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Próximo item', 'versao-ltda-theme' ); ?>"></button>
		</div>

		<div class="details__dots" aria-hidden="true">
			<span class="is-active"></span>
			<span></span>
			<span></span>
		</div>

		<p class="caption"><?php esc_html_e( 'Conteúdo completo da Edição #001 - Edição Especial de Pré-Venda', 'versao-ltda-theme' ); ?></p>

		<?php versao_ltda_home_features(); ?>
	</div>
</section>
