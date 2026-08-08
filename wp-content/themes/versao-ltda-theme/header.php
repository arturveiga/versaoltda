<?php

/**
 * Site header.
 *
 * @package Versao_Ltda_Theme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<?php $language_switcher = versao_ltda_get_language_switcher(); ?>
	<?php $supported_locales = versao_ltda_get_supported_locales(); ?>
	<a class="skip-link screen-reader-text"
		href="#main"><?php esc_html_e('Ir para o conteúdo', 'versao-ltda-theme'); ?></a>

	<header class="site-header" id="site-header">
		<div class="site-header__inner container">
			<div class="site-branding">

				<a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home"
					aria-label="<?php bloginfo('name'); ?>">

					<img src="<?php echo esc_url(vltda_asset('images/logo_versao_ltda_svg.svg')); ?>"
						alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="site-logo__image">

				</a>

			</div>

			<button class="nav-toggle" type="button" aria-controls="primary-menu" aria-expanded="false">
				<span class="nav-toggle__bar"></span>
				<span class="screen-reader-text"><?php esc_html_e('Abrir menu', 'versao-ltda-theme'); ?></span>
			</button>

			<!-- Menu -->
			<nav class="primary-navigation">
				<div class="header-language header-language--mobile" data-language-dropdown>
					<button class="header-action header-action--language" type="button" aria-label="<?php esc_attr_e( 'Selecionar idioma', 'versao-ltda-theme' ); ?>" aria-expanded="false" aria-controls="header-language-menu-mobile" data-language-toggle>
						<img src="<?php echo esc_url( vltda_asset( 'icons/' . $language_switcher['icon'] ) ); ?>" alt="">
					</button>

					<div class="header-language__menu" id="header-language-menu-mobile" data-language-menu hidden>
						<?php foreach ( $supported_locales as $locale => $language ) : ?>
							<a class="header-language__option<?php echo $locale === $language_switcher['current'] ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'site_lang', $locale ) ); ?>" lang="<?php echo esc_attr( str_replace( '_', '-', $locale ) ); ?>" aria-label="<?php echo esc_attr( $language['label'] ); ?>" title="<?php echo esc_attr( $language['label'] ); ?>"<?php echo $locale === $language_switcher['current'] ? ' aria-current="true"' : ''; ?>>
								<img src="<?php echo esc_url( vltda_asset( 'icons/' . $language['icon'] ) ); ?>" alt="">
							</a>
						<?php endforeach; ?>
					</div>
				</div>

				<?php
				wp_nav_menu([
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'primary-menu',
					'fallback_cb'    => 'versao_ltda_default_menu',
				]);
				?>

			</nav>

			<!-- Ações -->
			<div class="site-header__actions">

				<div class="header-language" data-language-dropdown>
					<button class="header-action header-action--language" type="button" aria-label="<?php esc_attr_e( 'Selecionar idioma', 'versao-ltda-theme' ); ?>" aria-expanded="false" aria-controls="header-language-menu" data-language-toggle>
						<img src="<?php echo esc_url( vltda_asset( 'icons/' . $language_switcher['icon'] ) ); ?>" alt="">
					</button>

					<div class="header-language__menu" id="header-language-menu" data-language-menu hidden>
						<?php foreach ( $supported_locales as $locale => $language ) : ?>
							<a class="header-language__option<?php echo $locale === $language_switcher['current'] ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'site_lang', $locale ) ); ?>" lang="<?php echo esc_attr( str_replace( '_', '-', $locale ) ); ?>" aria-label="<?php echo esc_attr( $language['label'] ); ?>" title="<?php echo esc_attr( $language['label'] ); ?>"<?php echo $locale === $language_switcher['current'] ? ' aria-current="true"' : ''; ?>>
								<img src="<?php echo esc_url( vltda_asset( 'icons/' . $language['icon'] ) ); ?>" alt="">
							</a>
						<?php endforeach; ?>
					</div>
				</div>

				<form class="header-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<input class="header-search__input" type="search" name="s" placeholder="<?php esc_attr_e( 'Buscar jogos', 'versao-ltda-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
					<button class="header-action header-search__toggle" type="button" aria-label="<?php esc_attr_e( 'Abrir busca', 'versao-ltda-theme' ); ?>" aria-expanded="false">

						<img src="<?php echo esc_url(vltda_asset('icons/search.svg')); ?>" alt="">

					</button>
					<button class="screen-reader-text" type="submit"><?php esc_html_e( 'Buscar', 'versao-ltda-theme' ); ?></button>
				</form>

				<a href="<?php echo esc_url( versao_ltda_get_wishlist_url() ); ?>" class="header-action" aria-label="<?php esc_attr_e( 'Whitelist', 'versao-ltda-theme' ); ?>">

					<img src="<?php echo esc_url(vltda_asset('icons/fav.svg')); ?>" alt="">

				</a>

				<a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/')); ?>"
					class="header-action header-action--account" aria-label="<?php esc_attr_e( 'Minha Conta', 'versao-ltda-theme' ); ?>">

					<img src="<?php echo esc_url(vltda_asset('icons/user.svg')); ?>" alt="">

				</a>

				<a href="<?php echo esc_url(function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/')); ?>"
					class="header-action header-action--cart"
					aria-label="<?php esc_attr_e( 'Carrinho', 'versao-ltda-theme' ); ?>">

					<img src="<?php echo esc_url(vltda_asset('icons/cart.svg')); ?>" alt="">

					<span class="cart-counter" aria-hidden="true">
						<?php echo function_exists('WC') && class_exists('WooCommerce') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
					</span>

				</a>

			</div>
		</div>
	</header>
