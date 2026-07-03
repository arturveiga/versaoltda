<?php
/**
 * Home features extension point.
 *
 * @package Versao_Ltda_Theme
 */

if ( ! function_exists( 'versao_ltda_home_features' ) ) {
	/**
	 * Render the home package feature grid.
	 *
	 * @return void
	 */
	function versao_ltda_home_features() {
		?>
		<div class="feature-grid">
			<article><h3><?php esc_html_e( 'Cartucho para Mega Drive / Genesis', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Versao original durante a Pre-Order em shell compacto em preto.', 'versao-ltda-theme' ); ?></p></article>
			<article><h3><?php esc_html_e( 'Embalagem Premium', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Acabamento rigido, impressao especial e protecao para colecao.', 'versao-ltda-theme' ); ?></p></article>
			<article><h3><?php esc_html_e( 'Conteudo Colecionavel', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Manual, poster e card, mantendo o padrao classico da plataforma.', 'versao-ltda-theme' ); ?></p></article>
			<article><h3><?php esc_html_e( 'Edicao Completa', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Cartucho, caixa premium, manual, poster e card numerado.', 'versao-ltda-theme' ); ?></p></article>
			<article><h3><?php esc_html_e( 'Experiencia de Unboxing', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Cada elemento foi pensado para valorizar o jogo fisico.', 'versao-ltda-theme' ); ?></p></article>
			<article><h3><?php esc_html_e( 'Powered by Mark1', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'PCB Mark1, uma arquitetura proprietaria desenvolvida para compatibilidade e preservacao.', 'versao-ltda-theme' ); ?></p></article>
		</div>
		<?php
	}
}
