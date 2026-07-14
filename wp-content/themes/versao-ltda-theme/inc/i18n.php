<?php
/**
 * Front-end locale switching and multilingual helpers.
 *
 * @package Versao_Ltda_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return locales supported by the theme switcher.
 *
 * @return array<string, array<string, string>>
 */
function versao_ltda_get_supported_locales() {
	return array(
		'pt_BR' => array(
			'label' => 'Português (Brasil)',
			'icon'  => 'icon_br.svg',
		),
		'en_US' => array(
			'label' => 'English (United States)',
			'icon'  => 'icon_us.svg',
		),
	);
}

/**
 * Read a valid locale from the request or language cookie.
 *
 * @return string
 */
function versao_ltda_get_requested_locale() {
	$supported = versao_ltda_get_supported_locales();
	$locale    = '';

	if ( isset( $_GET['site_lang'] ) ) {
		$locale = sanitize_text_field( wp_unslash( $_GET['site_lang'] ) );
	} elseif ( isset( $_COOKIE['versao_ltda_locale'] ) ) {
		$locale = sanitize_text_field( wp_unslash( $_COOKIE['versao_ltda_locale'] ) );
	}

	return isset( $supported[ $locale ] ) ? $locale : '';
}

/**
 * Match the browser language to a locale supported by the theme.
 *
 * Browsers build the Accept-Language header from their language/OS settings.
 * An explicit selection stored in the theme cookie always takes priority.
 *
 * @return string
 */
function versao_ltda_get_browser_locale() {
	if ( empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
		return '';
	}

	$languages   = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) );
	$best_locale = '';
	$best_quality = -1;

	foreach ( $languages as $language ) {
		if ( ! preg_match( '/^\s*([a-z]{2,3})(?:[-_][a-z]{2})?(?:\s*;\s*q=([01](?:\.\d+)?))?/i', $language, $matches ) ) {
			continue;
		}

		$language_code = strtolower( $matches[1] );
		$quality      = isset( $matches[2] ) ? (float) $matches[2] : 1.0;

		if ( $quality <= $best_quality ) {
			continue;
		}

		if ( 'pt' === $language_code ) {
			$best_locale = 'pt_BR';
			$best_quality = $quality;
		} elseif ( 'en' === $language_code ) {
			$best_locale = 'en_US';
			$best_quality = $quality;
		}
	}

	return $best_locale;
}

/**
 * Apply the selected locale to front-end requests.
 *
 * @param string|null $locale Previously determined locale.
 * @return string|null
 */
function versao_ltda_filter_frontend_locale( $locale ) {
	if ( is_admin() && ! wp_doing_ajax() ) {
		return $locale;
	}

	$requested_locale = versao_ltda_get_requested_locale();

	if ( $requested_locale ) {
		return $requested_locale;
	}

	$browser_locale = versao_ltda_get_browser_locale();

	return $browser_locale ?: $locale;
}
add_filter( 'pre_determine_locale', 'versao_ltda_filter_frontend_locale' );

/**
 * Persist a valid language selection and return to the clean URL.
 *
 * @return void
 */
function versao_ltda_persist_frontend_locale() {
	if ( ! isset( $_GET['site_lang'] ) || is_admin() || wp_doing_ajax() ) {
		return;
	}

	$locale = versao_ltda_get_requested_locale();

	if ( ! $locale ) {
		return;
	}

	setcookie(
		'versao_ltda_locale',
		$locale,
		array(
			'expires'  => time() + YEAR_IN_SECONDS,
			'path'     => COOKIEPATH ?: '/',
			'domain'   => COOKIE_DOMAIN,
			'secure'   => is_ssl(),
			'httponly' => true,
			'samesite' => 'Lax',
		)
	);

	wp_safe_redirect( remove_query_arg( 'site_lang' ) );
	exit;
}
add_action( 'template_redirect', 'versao_ltda_persist_frontend_locale', 1 );

/**
 * Return data for the compact language switcher.
 *
 * @return array<string, string>
 */
function versao_ltda_get_language_switcher() {
	$supported = versao_ltda_get_supported_locales();
	$current   = determine_locale();
	$current   = isset( $supported[ $current ] ) ? $current : 'pt_BR';
	$target    = 'pt_BR' === $current ? 'en_US' : 'pt_BR';
	$label     = 'en_US' === $target
		? __( 'Switch to English', 'versao-ltda-theme' )
		: __( 'Mudar para português', 'versao-ltda-theme' );

	return array(
		'current' => $current,
		'target'  => $target,
		'href'    => add_query_arg( 'site_lang', $target ),
		'label'   => $label,
		'icon'    => $supported[ $current ]['icon'],
	);
}

/**
 * Translate labels saved in assigned WordPress menus.
 *
 * @param WP_Post[] $items Menu items.
 * @return WP_Post[]
 */
function versao_ltda_translate_menu_items( $items ) {
	$labels = array(
		'Home'        => __( 'Home', 'versao-ltda-theme' ),
		'Jogos'       => __( 'Jogos', 'versao-ltda-theme' ),
		'Extras'      => __( 'Extras', 'versao-ltda-theme' ),
		'Sobre'       => __( 'Sobre', 'versao-ltda-theme' ),
		'Contato'     => __( 'Contato', 'versao-ltda-theme' ),
		'Minha Conta' => __( 'Minha Conta', 'versao-ltda-theme' ),
	);

	foreach ( $items as $item ) {
		if ( isset( $labels[ $item->title ] ) ) {
			$item->title = $labels[ $item->title ];
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'versao_ltda_translate_menu_items' );

/**
 * Add the active locale to the body classes.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function versao_ltda_locale_body_class( $classes ) {
	$classes[] = 'locale-' . strtolower( str_replace( '_', '-', determine_locale() ) );

	return $classes;
}
add_filter( 'body_class', 'versao_ltda_locale_body_class' );
