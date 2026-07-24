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
			<article><h3><?php esc_html_e( 'Cartucho para Mega Drive', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Versão especial durante a Pré-Order em shell cromado em prata.', 'versao-ltda-theme' ); ?></p></article>
			<article><h3><?php esc_html_e( 'Embalagem Premium', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Acabamento rígido, impressão especial e proteção para coleção.', 'versao-ltda-theme' ); ?></p></article>
			<article><h3><?php esc_html_e( 'Conteúdo Colecionável', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Manual, pôster e card, mantendo o padrão clássico da plataforma.', 'versao-ltda-theme' ); ?></p></article>
			<article><h3><?php esc_html_e( 'Edição Completa', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Cartucho, caixa premium, manual, pôster e card numerado.', 'versao-ltda-theme' ); ?></p></article>
			<article><h3><?php esc_html_e( 'Experiência de Unboxing', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Cada elemento foi pensado para valorizar o jogo físico.', 'versao-ltda-theme' ); ?></p></article>
			<article><h3><?php esc_html_e( 'Powered by Mark1', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'PCB Mark1, uma arquitetura proprietária desenvolvida para compatibilidade e preservação.', 'versao-ltda-theme' ); ?></p></article>
		</div>
		<?php
	}
}
