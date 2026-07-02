<?php
/**
 * Site footer.
 *
 * @package Versao_Ltda_Theme
 */

?>
<footer class="site-footer">
	<div class="site-footer__inner container">
		<div class="site-footer__brand">
			<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<span class="site-logo__top">Versao</span>
				<span class="site-logo__bottom">LTDA</span>
			</a>
			<p><?php esc_html_e( 'Alguns jogos merecem mais.', 'versao-ltda-theme' ); ?></p>
		</div>

		<nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Menu do rodape', 'versao-ltda-theme' ); ?>">
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
			<?php if ( ! has_nav_menu( 'footer' ) ) : ?>
				<ul class="footer-menu">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Jogos</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Extras</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Sobre</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Contato</a></li>
				</ul>
			<?php endif; ?>
		</nav>

		<form class="newsletter" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">
			<label for="newsletter-email"><?php esc_html_e( 'Nao perca nenhuma edicao.', 'versao-ltda-theme' ); ?></label>
			<p><?php esc_html_e( 'Fique perto da nossa lista e seja sempre o primeiro a saber sobre as futuras pre-orders.', 'versao-ltda-theme' ); ?></p>
			<div class="newsletter__row">
				<input id="newsletter-email" type="email" name="newsletter_email" placeholder="<?php esc_attr_e( 'e-mail', 'versao-ltda-theme' ); ?>">
				<button type="submit"><?php esc_html_e( 'OK', 'versao-ltda-theme' ); ?></button>
			</div>
		</form>
	</div>

	<div class="site-footer__credits container">
		<small>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></small>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
