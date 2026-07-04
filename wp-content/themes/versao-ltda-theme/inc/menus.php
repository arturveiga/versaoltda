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
		'Home',
		'Jogos',
		'Sobre',
		'Contato',
	);
	?>
	<ul id="primary-menu" class="primary-menu">
		<?php foreach ( $items as $item ) : ?>
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $item ); ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php
}
