<?php

/**
 * Theme bootstrap.
 *
 * @package Versao_Ltda_Theme
 */

if (! defined('ABSPATH')) {
	exit;
}

$versao_ltda_includes = array(
	'inc/i18n.php',
	'inc/setup.php',
	'inc/enqueue.php',
	'inc/menus.php',
	'inc/widgets.php',
	'inc/theme-support.php',
	'inc/woocommerce.php',
	'inc/helpers.php',
	'inc/maintenance.php',
);


foreach ($versao_ltda_includes as $versao_ltda_file) {
	$versao_ltda_path = get_template_directory() . '/' . $versao_ltda_file;

	if (file_exists($versao_ltda_path)) {
		require_once $versao_ltda_path;
	}
}

add_action('wp_head', 'vltda_default_favicon', 1);

function vltda_default_favicon()
{

	if (has_site_icon()) {
		return;
	}

?>
	<link rel="icon" href="<?php echo esc_url(vltda_asset('images/favicon/favicon.ico')); ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url(vltda_asset('images/favicon/favicon-32x32.png')); ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url(vltda_asset('images/favicon/favicon-16x16.png')); ?>">
	<link rel="apple-touch-icon" href="<?php echo esc_url(vltda_asset('images/favicon/apple-touch-icon.png')); ?>">
<?php
}
