<?php
/**
 * My account page template.
 *
 * @package Versao_Ltda_Theme
 */

get_header();
?>

<main id="main" class="site-main account-page">
	<section class="account-section section">
		<div class="container">
			<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Caminho de navegação', 'versao-ltda-theme' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Início', 'versao-ltda-theme' ); ?></a>
				<span>›</span>
				<span><?php esc_html_e( 'Minha conta', 'versao-ltda-theme' ); ?></span>
			</nav>

			<div class="woocommerce-notices-wrapper">
				<?php wc_print_notices(); ?>
			</div>

			<?php if ( is_user_logged_in() ) : ?>
				<div class="account-dashboard">
					<p class="section-kicker">VL / <?php esc_html_e( 'Minha conta', 'versao-ltda-theme' ); ?></p>
					<h1><?php esc_html_e( 'Bem-vindo de volta.', 'versao-ltda-theme' ); ?></h1>
					<p><?php esc_html_e( 'Gerencie seus pedidos, dados de acesso e endereços da sua conta Versão LTDA.', 'versao-ltda-theme' ); ?></p>

					<div class="account-dashboard__actions">
						<a class="button button--primary" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>"><?php esc_html_e( 'Meus pedidos', 'versao-ltda-theme' ); ?></a>
						<a class="button account-button--muted" href="<?php echo esc_url( wc_logout_url() ); ?>"><?php esc_html_e( 'Sair', 'versao-ltda-theme' ); ?></a>
					</div>

					<div class="account-dashboard__content">
						<?php woocommerce_account_content(); ?>
					</div>
				</div>
			<?php else : ?>
				<div class="account-auth" id="customer_login">
					<section class="account-panel account-panel--login">
						<h1><?php esc_html_e( 'Entrar', 'versao-ltda-theme' ); ?></h1>
						<p><?php esc_html_e( 'Bem-vindo de volta! Faça login em sua conta.', 'versao-ltda-theme' ); ?></p>

						<form class="account-form woocommerce-form woocommerce-form-login login" method="post">
							<?php do_action( 'woocommerce_login_form_start' ); ?>

							<label for="username"><?php esc_html_e( 'Nome de usuário ou e-mail', 'versao-ltda-theme' ); ?> *</label>
							<input id="username" type="text" name="username" autocomplete="username" required>

							<label for="password"><?php esc_html_e( 'Senha', 'versao-ltda-theme' ); ?> *</label>
							<input id="password" type="password" name="password" autocomplete="current-password" required>

							<label class="account-form__remember" for="rememberme">
								<input id="rememberme" type="checkbox" name="rememberme" value="forever">
								<span><?php esc_html_e( 'Lembre-me', 'versao-ltda-theme' ); ?></span>
							</label>

							<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
							<input type="hidden" name="redirect" value="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">

							<button class="button button--primary" type="submit" name="login" value="<?php esc_attr_e( 'Acessar', 'versao-ltda-theme' ); ?>">
								<?php esc_html_e( 'Acessar', 'versao-ltda-theme' ); ?>
							</button>

							<a class="account-form__link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Perdeu sua senha?', 'versao-ltda-theme' ); ?></a>

							<button class="account-google" type="button" disabled>
								<span>G</span>
								<?php esc_html_e( 'Entrar com Google', 'versao-ltda-theme' ); ?>
							</button>

							<?php do_action( 'woocommerce_login_form_end' ); ?>
						</form>
					</section>

					<div class="account-auth__divider" aria-hidden="true"><span>or</span></div>

					<section class="account-panel account-panel--register">
						<h2><?php esc_html_e( 'Cadastre-se', 'versao-ltda-theme' ); ?></h2>
						<p><?php esc_html_e( 'Crie uma nova conta hoje para ganhar os benefícios de uma experiência de compra personalizada.', 'versao-ltda-theme' ); ?></p>

						<form class="account-form woocommerce-form woocommerce-form-register register" method="post">
							<?php do_action( 'woocommerce_register_form_start' ); ?>

							<label for="reg_email"><?php esc_html_e( 'Endereço de e-mail', 'versao-ltda-theme' ); ?> *</label>
							<input id="reg_email" type="email" name="email" autocomplete="email" required>

							<label for="reg_password"><?php esc_html_e( 'Senha', 'versao-ltda-theme' ); ?> *</label>
							<input id="reg_password" type="password" name="password" autocomplete="new-password" required>

							<p class="account-form__privacy">
								<?php esc_html_e( 'Seus dados pessoais serão usados para aprimorar sua experiência neste site, gerenciar o acesso à sua conta e para outros propósitos descritos em nossa política de privacidade.', 'versao-ltda-theme' ); ?>
							</p>

							<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

							<button class="button button--primary" type="submit" name="register" value="<?php esc_attr_e( 'Cadastrar-se', 'versao-ltda-theme' ); ?>">
								<?php esc_html_e( 'Cadastrar-se', 'versao-ltda-theme' ); ?>
							</button>

							<button class="account-google" type="button" disabled>
								<span>G</span>
								<?php esc_html_e( 'Cadastrar com Google', 'versao-ltda-theme' ); ?>
							</button>

							<?php do_action( 'woocommerce_register_form_end' ); ?>
						</form>

						<div class="account-benefits">
							<h3><?php esc_html_e( 'Inscreva-se hoje e você será capaz de:', 'versao-ltda-theme' ); ?></h3>
							<ul>
								<li><?php esc_html_e( 'Acelerar o processo de checkout', 'versao-ltda-theme' ); ?></li>
								<li><?php esc_html_e( 'Acompanhar pedidos e reservas', 'versao-ltda-theme' ); ?></li>
								<li><?php esc_html_e( 'Salvar seus dados para futuras pré-vendas', 'versao-ltda-theme' ); ?></li>
							</ul>
						</div>
					</section>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
