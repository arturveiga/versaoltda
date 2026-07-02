<?php
/**
 * Asset loading.
 *
 * @package Versao_Ltda_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue theme styles and scripts.
 *
 * @return void
 */
function versao_ltda_enqueue_assets() {
	$theme   = wp_get_theme();
	$version = $theme->get( 'Version' );
	$css_dir = get_template_directory() . '/assets/css';
	$css_uri = get_template_directory_uri() . '/assets/css';
	$js_dir  = get_template_directory() . '/assets/js';
	$js_uri  = get_template_directory_uri() . '/assets/js';

	$styles = array(
		'reset.css',
		'variables.css',
		'typography.css',
		'layout.css',
		'components.css',
		'woocommerce.css',
		'responsive.css',
	);

	$extra_styles = glob( $css_dir . '/*.css' );

	if ( is_array( $extra_styles ) ) {
		foreach ( $extra_styles as $extra_style ) {
			$stylesheet = basename( $extra_style );

			if ( ! in_array( $stylesheet, $styles, true ) ) {
				$styles[] = $stylesheet;
			}
		}
	}

	foreach ( $styles as $style ) {
		$style_path = $css_dir . '/' . $style;

		if ( ! file_exists( $style_path ) ) {
			continue;
		}

		wp_enqueue_style(
			'versao-ltda-' . sanitize_title( basename( $style, '.css' ) ),
			$css_uri . '/' . $style,
			array(),
			$version
		);
	}

	$scripts = array(
		'app.js',
		'navigation.js',
	);

	$extra_scripts = glob( $js_dir . '/*.js' );

	if ( is_array( $extra_scripts ) ) {
		foreach ( $extra_scripts as $extra_script ) {
			$script = basename( $extra_script );

			if ( ! in_array( $script, $scripts, true ) ) {
				$scripts[] = $script;
			}
		}
	}

	foreach ( $scripts as $script ) {
		$script_path = $js_dir . '/' . $script;

		if ( ! file_exists( $script_path ) ) {
			continue;
		}

		wp_enqueue_script(
			'versao-ltda-' . sanitize_title( basename( $script, '.js' ) ),
			$js_uri . '/' . $script,
			array(),
			$version,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'versao_ltda_enqueue_assets' );
