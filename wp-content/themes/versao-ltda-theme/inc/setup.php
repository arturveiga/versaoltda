<?php
/**
 * Theme setup.
 *
 * @package Versao_Ltda_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports and menus.
 *
 * @return void
 */
function versao_ltda_theme_setup() {
	load_theme_textdomain( 'versao-ltda-theme', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 220,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);
	add_theme_support( 'woocommerce' );

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Menu principal', 'versao-ltda-theme' ),
			'footer'  => esc_html__( 'Menu do rodape', 'versao-ltda-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'versao_ltda_theme_setup' );
