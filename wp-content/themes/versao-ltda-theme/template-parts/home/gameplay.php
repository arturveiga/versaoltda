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
				<span><?php esc_html_e( 'Cartucho cromado', 'versao-ltda-theme' ); ?></span>
				<span><?php esc_html_e( 'especial somente', 'versao-ltda-theme' ); ?></span>
				<span><?php esc_html_e( 'na pré-venda.', 'versao-ltda-theme' ); ?></span>
			</h2>
			<p><?php esc_html_e( 'A primeira edição de Demons of Asteborg, pela Versão LTDA, acompanha um cartucho cromado especial para marcar o lançamento #001 da coleção.', 'versao-ltda-theme' ); ?></p>
			<a class="button button--primary <?php echo esc_attr( $reserve_action['class'] ); ?>" href="<?php echo esc_url( $reserve_action['href'] ); ?>"<?php echo $reserve_action['attributes']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php esc_html_e( 'Reservar agora', 'versao-ltda-theme' ); ?>
			</a>
			<p class="product-code">#001 / Demons of Asteborg / Mega Drive<br><?php esc_html_e( 'Cartucho Cromado | Versão Pré-Venda', 'versao-ltda-theme' ); ?></p>
		</div>
		<div class="physical__media" role="group" aria-label="<?php esc_attr_e( 'Passe o mouse ou use o foco para ver o interior do cartucho', 'versao-ltda-theme' ); ?>" tabindex="0">
			<img class="physical__image physical__image--closed" src="<?php echo esc_url( vltda_asset( 'images/cartridge-chrome.png' ) ); ?>" alt="<?php esc_attr_e( 'Cartucho cromado de Demons of Asteborg', 'versao-ltda-theme' ); ?>" width="3840" height="2160" loading="lazy" decoding="async">
			<img class="physical__image physical__image--open" src="<?php echo esc_url( vltda_asset( 'images/cartridge-chrome-pcb.png' ) ); ?>" alt="" width="3840" height="2160" loading="lazy" decoding="async" aria-hidden="true">
		</div>
	</div>
</section>
