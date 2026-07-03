<?php
/**
 * Home package section.
 *
 * @package Versao_Ltda_Theme
 */

?>
<section class="details section">
	<div class="container container--narrow">
		<p class="section-kicker"><?php esc_html_e( 'O que tem na embalagem?', 'versao-ltda-theme' ); ?></p>
		<h2><?php esc_html_e( 'Cada detalhe foi pensado.', 'versao-ltda-theme' ); ?></h2>
		<p><?php esc_html_e( 'Cada edicao nasce como uma experiencia completa de colecao, do cartucho a embalagem.', 'versao-ltda-theme' ); ?></p>

		<img class="details__image" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/package-open.jpg' ); ?>" alt="<?php esc_attr_e( 'Embalagem premium aberta do jogo Demons of Asteborg', 'versao-ltda-theme' ); ?>">

		<p class="caption"><?php esc_html_e( 'Conteudo completo da Edicao #001 - Edicao Especial de Pre-Order', 'versao-ltda-theme' ); ?></p>

		<?php versao_ltda_home_features(); ?>
	</div>
</section>
