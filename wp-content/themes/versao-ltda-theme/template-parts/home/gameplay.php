<?php
/**
 * Home gameplay section.
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
<section class="physical section" id="comprar">
	<div class="container physical__grid">
		<div class="physical__copy">
			<p class="section-kicker"><?php esc_html_e( 'Exclusivo da Pré-Venda', 'versao-ltda-theme' ); ?></p>
			<h2>
				<span><?php esc_html_e( 'Cartucho cromado especial', 'versao-ltda-theme' ); ?></span>
				<span><?php esc_html_e( 'somente na', 'versao-ltda-theme' ); ?></span>
				<span><?php esc_html_e( 'pré-venda.', 'versao-ltda-theme' ); ?></span>
			</h2>
			<p><?php esc_html_e( 'A primeira edição de Demons of Asteborg, pela Versão LTDA, acompanha um cartucho cromado especial para marcar o lançamento #001 da coleção.', 'versao-ltda-theme' ); ?></p>
			<a class="button button--primary <?php echo esc_attr( $reserve_action['class'] ); ?>" href="<?php echo esc_url( $reserve_action['href'] ); ?>"<?php echo $reserve_action['attributes']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php esc_html_e( 'Reservar agora', 'versao-ltda-theme' ); ?>
			</a>
			<p class="product-code">#001 / Demons of Asteborg / Mega Drive<br><?php esc_html_e( 'Cartucho Cromado | Versão Pré-Venda', 'versao-ltda-theme' ); ?></p>
		</div>
		<picture>
			<source media="(max-width: 700px)" srcset="<?php echo esc_url( vltda_asset( 'images/Front01.jpg' ) ); ?>">
			<img src="<?php echo esc_url( vltda_asset( 'images/Top01.jpg' ) ); ?>" alt="<?php esc_attr_e( 'Cartucho físico Demons of Asteborg', 'versao-ltda-theme' ); ?>">
		</picture>
	</div>
</section>
