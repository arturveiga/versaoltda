<?php

/**
 * Site footer.
 *
 * @package Versao_Ltda_Theme
 */

?>
<footer class="site-footer">
	<div class="site-footer__inner container">
		<div class="footer-brand">

			<img
				class="footer-brand__logo"
				src="<?php echo esc_url(vltda_asset('images/logo_versao_ltda_svg.svg')); ?>"
				alt="<?php echo esc_attr(get_bloginfo('name')); ?>">

			<p><?php esc_html_e('Alguns jogos merecem mais.', 'versao-ltda-theme'); ?></p>

		</div>

		<nav class="footer-navigation" aria-label="<?php esc_attr_e('Menu do rodapé', 'versao-ltda-theme'); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'menu_class'     => 'footer-menu',
					'container'      => false,
					'fallback_cb'    => false,
				)
			);
			?>
			<?php if (! has_nav_menu('footer')) : ?>
				<ul class="footer-menu">
					<li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e( 'Home', 'versao-ltda-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/jogos/')); ?>"><?php esc_html_e( 'Jogos', 'versao-ltda-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e( 'Extras', 'versao-ltda-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/sobre/')); ?>"><?php esc_html_e( 'Sobre', 'versao-ltda-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/contato/')); ?>"><?php esc_html_e( 'Contato', 'versao-ltda-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/minha-conta/')); ?>"><?php esc_html_e( 'Minha Conta', 'versao-ltda-theme' ); ?></a></li>
				</ul>
			<?php endif; ?>
		</nav>

		<form class="newsletter" action="<?php echo esc_url(home_url('/')); ?>" method="post">
			<label for="newsletter-email"><?php esc_html_e('Não perca nenhuma edição.', 'versao-ltda-theme'); ?></label>
			<p><?php esc_html_e('Faça parte da nossa lista e seja sempre o primeiro a saber sobre as futuras pré-orders.', 'versao-ltda-theme'); ?>
			</p>
			<div class="newsletter__row">
				<input id="newsletter-email" type="email" name="newsletter_email"
					placeholder="<?php esc_attr_e('e-mail', 'versao-ltda-theme'); ?>">
				<button type="submit"><?php esc_html_e('OK', 'versao-ltda-theme'); ?></button>
			</div>
		</form>
	</div>

	<div class="site-footer__credits container">
		<small>&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?></small>
	</div>
</footer>

<?php wp_footer(); ?>
</body>

</html>
