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
	<a class="skip-link screen-reader-text"
		href="#main"><?php esc_html_e('Ir para o conteudo', 'versao-ltda-theme'); ?></a>

	<header class="site-header" id="site-header">
		<div class="site-header__inner container">
			<div class="site-branding">

				<a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home"
					aria-label="<?php bloginfo('name'); ?>">

					<img src="<?php echo esc_url(vltda_asset('images/Logo_versao_ltda_svg.svg')); ?>"
						alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="site-logo__image">

				</a>

			</div>

			<button class="nav-toggle" type="button" aria-controls="primary-menu" aria-expanded="false">
				<span class="nav-toggle__bar"></span>
				<span class="screen-reader-text"><?php esc_html_e('Abrir menu', 'versao-ltda-theme'); ?></span>
			</button>

			<!-- Menu -->
			<nav class="primary-navigation">

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

				<a href="#" class="header-action" aria-label="Buscar">

					<img src="<?php echo esc_url(vltda_asset('icons/search.svg')); ?>" alt="">

				</a>

				<a href="#" class="header-action" aria-label="Favoritos">

					<img src="<?php echo esc_url(vltda_asset('icons/fav.svg')); ?>" alt="">

				</a>

				<a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>"
					class="header-action" aria-label="Minha Conta">

					<img src="<?php echo esc_url(vltda_asset('icons/user.svg')); ?>" alt="">

				</a>

				<a href="<?php echo esc_url(wc_get_cart_url()); ?>"
					class="header-action header-action--cart"
					aria-label="Carrinho">

					<img src="<?php echo esc_url(vltda_asset('icons/cart.svg')); ?>" alt="">

					<span class="cart-counter">
						<?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
					</span>

				</a>

			</div>
		</div>
	</header>