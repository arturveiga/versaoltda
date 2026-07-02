<?php
/**
 * Theme bootstrap.
 *
 * @package Versao_Ltda_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$versao_ltda_includes = array(
	'inc/setup.php',
	'inc/enqueue.php',
	'inc/menus.php',
	'inc/widgets.php',
	'inc/theme-support.php',
	'inc/woocommerce.php',
);

foreach ( $versao_ltda_includes as $versao_ltda_file ) {
	$versao_ltda_path = get_template_directory() . '/' . $versao_ltda_file;

	if ( file_exists( $versao_ltda_path ) ) {
		require_once $versao_ltda_path;
	}
}
