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
	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Ir para o conteudo', 'versao-ltda-theme'); ?></a>

	<header class="site-header" id="site-header">
		<div class="site-header__inner container">
			<div class="site-branding">

				<a
					class="site-logo"
					href="<?php echo esc_url(home_url('/')); ?>"
					rel="home"
					aria-label="<?php bloginfo('name'); ?>">

					<img
						src="<?php echo esc_url(vltda_asset('images/logo-top.png')); ?>"
						alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
						class="site-logo__image">

				</a>

			</div>

			<button class="nav-toggle" type="button" aria-controls="primary-menu" aria-expanded="false">
				<span class="nav-toggle__bar"></span>
				<span class="screen-reader-text"><?php esc_html_e('Abrir menu', 'versao-ltda-theme'); ?></span>
			</button>

			<nav class="primary-navigation" aria-label="<?php esc_attr_e('Menu principal', 'versao-ltda-theme'); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'primary-menu',
						'container'      => false,
						'fallback_cb'    => 'versao_ltda_default_menu',
					)
				);
				?>
			</nav>

			<div class="site-actions" aria-label="<?php esc_attr_e('Acoes da loja', 'versao-ltda-theme'); ?>">
				<span class="site-actions__flag" aria-hidden="true"></span>
				<a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Pesquisar', 'versao-ltda-theme'); ?>">S</a>
				<a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Minha conta', 'versao-ltda-theme'); ?>">C</a>
				<a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Carrinho', 'versao-ltda-theme'); ?>">0</a>
			</div>
		</div>
	</header>