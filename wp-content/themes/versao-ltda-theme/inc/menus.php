<?php
/**
 * Menu helpers.
 *
 * @package Versao_Ltda_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render a default primary menu when no WordPress menu is assigned.
 *
 * @return void
 */
function versao_ltda_default_menu() {
	$items = array(
		'Home'    => home_url( '/' ),
		'Jogos'   => home_url( '/jogos/' ),
		'Extras'  => home_url( '/#extras' ),
		'Sobre'   => home_url( '/sobre/' ),
		'Contato' => home_url( '/contato/' ),
	);
	?>
	<ul id="primary-menu" class="primary-menu">
		<?php foreach ( $items as $label => $url ) : ?>
			<li><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php
}
