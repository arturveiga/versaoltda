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
				<?php
				$current_user    = wp_get_current_user();
				$user_name       = versao_ltda_get_account_first_name( $current_user );
				$is_orders       = is_wc_endpoint_url( 'orders' ) || is_wc_endpoint_url( 'view-order' );
				$is_edit_address = is_wc_endpoint_url( 'edit-address' );
				$is_dashboard    = ! is_wc_endpoint_url();
				?>
				<div class="account-shell">
					<aside class="account-shell__sidebar">
						<h1>
							<?php
							printf(
								esc_html__( 'Olá, %s', 'versao-ltda-theme' ),
								esc_html( $user_name )
							);
							?>
						</h1>
						<p class="account-shell__intro"><?php esc_html_e( 'Bem-vindo à sua conta VERSÃO LTDA.', 'versao-ltda-theme' ); ?></p>

						<nav class="account-shell__menu" aria-label="<?php esc_attr_e( 'Menu da conta', 'versao-ltda-theme' ); ?>">
							<a<?php echo $is_orders ? ' class="is-active"' : ''; ?> href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>">
								<strong><?php esc_html_e( 'Pedidos e pré-vendas', 'versao-ltda-theme' ); ?></strong>
								<span><?php esc_html_e( 'Veja o status das suas compras.', 'versao-ltda-theme' ); ?></span>
							</a>
							<a<?php echo $is_edit_address ? ' class="is-active"' : ''; ?> href="<?php echo esc_url( versao_ltda_get_account_address_url() ); ?>">
								<strong><?php esc_html_e( 'Dados e endereço', 'versao-ltda-theme' ); ?></strong>
								<span><?php esc_html_e( 'Mantenha seus dados de entrega e cobrança atualizados.', 'versao-ltda-theme' ); ?></span>
							</a>
							<a href="<?php echo esc_url( versao_ltda_get_wishlist_url() ); ?>">
								<strong><?php esc_html_e( 'Lista de desejos', 'versao-ltda-theme' ); ?></strong>
								<span><?php esc_html_e( 'Salve edições que você quer acompanhar.', 'versao-ltda-theme' ); ?></span>
							</a>
							<a href="<?php echo esc_url( wc_logout_url() ); ?>">
								<strong><?php esc_html_e( 'Sair', 'versao-ltda-theme' ); ?></strong>
							</a>
						</nav>
					</aside>

					<section class="account-shell__content">
						<?php if ( $is_dashboard ) : ?>
							<div class="account-dashboard">
								<p>
									<?php esc_html_e( 'Olá,', 'versao-ltda-theme' ); ?>
									<strong><?php echo esc_html( $user_name ); ?></strong>
									<?php esc_html_e( '(não é', 'versao-ltda-theme' ); ?>
									<?php echo esc_html( $user_name ); ?>?
									<a href="<?php echo esc_url( wc_logout_url() ); ?>"><?php esc_html_e( 'Sair', 'versao-ltda-theme' ); ?></a>)
									<br>
									<?php esc_html_e( 'A partir do painel de controle da sua conta, você pode ver suas compras recentes, gerenciar seus endereços de entrega e cobrança, e editar sua senha e detalhes da conta.', 'versao-ltda-theme' ); ?>
								</p>
								<?php do_action( 'woocommerce_account_dashboard' ); ?>
							</div>
						<?php else : ?>
							<?php woocommerce_account_content(); ?>
						<?php endif; ?>
					</section>
				</div>
			<?php else : ?>
				<div class="account-auth" id="customer_login">
					<section class="account-panel account-panel--login">
						<h1><?php esc_html_e( 'Acesse sua conta.', 'versao-ltda-theme' ); ?></h1>
						<p><?php esc_html_e( 'Entre para acompanhar seus pedidos, consultar suas reservas e gerenciar suas informações.', 'versao-ltda-theme' ); ?></p>

						<form class="account-form woocommerce-form woocommerce-form-login login" method="post">
							<?php do_action( 'woocommerce_login_form_start' ); ?>

							<label for="username"><?php esc_html_e( 'Nome de usuário ou e-mail', 'versao-ltda-theme' ); ?> *</label>
							<input id="username" type="text" name="username" autocomplete="username" required>

							<label for="password"><?php esc_html_e( 'Senha', 'versao-ltda-theme' ); ?> *</label>
							<input id="password" type="password" name="password" autocomplete="current-password" required>

							<?php do_action( 'woocommerce_login_form' ); ?>

							<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
							<input type="hidden" name="redirect" value="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">

							<div class="account-form__actions">
								<label class="account-form__remember" for="rememberme">
									<input id="rememberme" type="checkbox" name="rememberme" value="forever">
									<span><?php esc_html_e( 'Lembre-me', 'versao-ltda-theme' ); ?></span>
								</label>

								<button class="button button--primary" type="submit" name="login" value="<?php esc_attr_e( 'Entrar', 'versao-ltda-theme' ); ?>">
									<?php esc_html_e( 'Entrar', 'versao-ltda-theme' ); ?>
								</button>
							</div>

							<a class="account-form__link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Esqueci minha senha.', 'versao-ltda-theme' ); ?></a>

							<?php do_action( 'woocommerce_login_form_end' ); ?>
						</form>

						<p class="account-security-copy">
							<strong><?php esc_html_e( 'Verificação de segurança', 'versao-ltda-theme' ); ?></strong>
							<?php esc_html_e( 'Seus dados serão utilizados para criar e gerenciar sua conta, processar pedidos e melhorar sua experiência no site, conforme nossa Política de Privacidade.', 'versao-ltda-theme' ); ?>
						</p>
					</section>

					<div class="account-auth__divider" aria-hidden="true"><span><?php esc_html_e( 'OU', 'versao-ltda-theme' ); ?></span></div>

					<section class="account-panel account-panel--register">
						<h2><?php esc_html_e( 'Crie sua conta.', 'versao-ltda-theme' ); ?></h2>
						<p><?php esc_html_e( 'Tenha uma experiência de compra mais rápida e acompanhe de perto suas edições da VERSÃO LTDA.', 'versao-ltda-theme' ); ?></p>

						<form class="account-form woocommerce-form woocommerce-form-register register" method="post">
							<?php do_action( 'woocommerce_register_form_start' ); ?>

							<label for="reg_email"><?php esc_html_e( 'Endereço de e-mail', 'versao-ltda-theme' ); ?> *</label>
							<input id="reg_email" type="email" name="email" autocomplete="email" required>

							<label for="reg_password"><?php esc_html_e( 'Senha', 'versao-ltda-theme' ); ?> *</label>
							<input id="reg_password" type="password" name="password" autocomplete="new-password" required>

							<?php do_action( 'woocommerce_register_form' ); ?>

							<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

							<div class="account-form__actions account-form__actions--end">
								<button class="button button--primary" type="submit" name="register" value="<?php esc_attr_e( 'Criar conta', 'versao-ltda-theme' ); ?>">
									<?php esc_html_e( 'Criar conta', 'versao-ltda-theme' ); ?>
								</button>
							</div>

							<?php do_action( 'woocommerce_register_form_end' ); ?>
						</form>

						<div class="account-benefits">
							<h3><?php esc_html_e( 'Com uma conta, você pode:', 'versao-ltda-theme' ); ?></h3>
							<ul>
								<li><?php esc_html_e( 'Finalizar compras com mais rapidez', 'versao-ltda-theme' ); ?></li>
								<li><?php esc_html_e( 'Acompanhar pedidos e pré-vendas', 'versao-ltda-theme' ); ?></li>
								<li><?php esc_html_e( 'Consultar seu histórico de compras', 'versao-ltda-theme' ); ?></li>
								<li><?php esc_html_e( 'Receber novidades sobre futuras edições', 'versao-ltda-theme' ); ?></li>
							</ul>
						</div>

						<p class="account-security-copy">
							<strong><?php esc_html_e( 'Verificação de segurança', 'versao-ltda-theme' ); ?></strong>
							<?php esc_html_e( 'Seus dados serão utilizados para criar e gerenciar sua conta, processar pedidos e melhorar sua experiência no site, conforme nossa Política de Privacidade.', 'versao-ltda-theme' ); ?>
						</p>
					</section>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
