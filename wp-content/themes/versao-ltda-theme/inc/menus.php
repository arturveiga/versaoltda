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
		__( 'Home', 'versao-ltda-theme' )    => home_url( '/' ),
		__( 'Jogos', 'versao-ltda-theme' )   => home_url( '/jogos/' ),
		__( 'Extras', 'versao-ltda-theme' )  => home_url( '/#extras' ),
		__( 'Sobre', 'versao-ltda-theme' )   => home_url( '/sobre/' ),
		__( 'Contato', 'versao-ltda-theme' ) => home_url( '/contato/' ),
	);
	?>
	<ul id="primary-menu" class="primary-menu">
		<?php foreach ( $items as $label => $url ) : ?>
			<li><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php
}
