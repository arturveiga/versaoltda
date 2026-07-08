<?php
/**
 * Contact page template.
 *
 * @package Versao_Ltda_Theme
 */

get_header();
?>

<main id="main" class="site-main contact-page">
	<section class="contact-section section">
		<div class="container container--narrow">
			<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Caminho de navegação', 'versao-ltda-theme' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'versao-ltda-theme' ); ?></a>
				<span>/</span>
				<span><?php esc_html_e( 'Contato', 'versao-ltda-theme' ); ?></span>
			</nav>

			<div class="contact-grid">
				<div class="contact-copy">
					<p class="section-kicker">VL / <?php esc_html_e( 'Contato', 'versao-ltda-theme' ); ?></p>
					<h1><?php esc_html_e( 'Fale com a gente.', 'versao-ltda-theme' ); ?></h1>
					<p><?php esc_html_e( 'Tem uma dúvida sobre pré-venda, edição física, parceria ou lançamento futuro? Envie sua mensagem e retornaremos assim que possível.', 'versao-ltda-theme' ); ?></p>
				</div>

				<form class="contact-form" action="<?php echo esc_url( home_url( '/contato/' ) ); ?>" method="post">
					<div class="contact-form__row">
						<label for="contact-name"><?php esc_html_e( 'Nome', 'versao-ltda-theme' ); ?></label>
						<input id="contact-name" type="text" name="contact_name" placeholder="<?php esc_attr_e( 'seu nome', 'versao-ltda-theme' ); ?>">
					</div>

					<div class="contact-form__row">
						<label for="contact-email"><?php esc_html_e( 'E-mail', 'versao-ltda-theme' ); ?></label>
						<input id="contact-email" type="email" name="contact_email" placeholder="<?php esc_attr_e( 'seu e-mail', 'versao-ltda-theme' ); ?>">
					</div>

					<div class="contact-form__row">
						<label for="contact-subject"><?php esc_html_e( 'Assunto', 'versao-ltda-theme' ); ?></label>
						<input id="contact-subject" type="text" name="contact_subject" placeholder="<?php esc_attr_e( 'sobre o que quer falar?', 'versao-ltda-theme' ); ?>">
					</div>

					<div class="contact-form__row">
						<label for="contact-message"><?php esc_html_e( 'Mensagem', 'versao-ltda-theme' ); ?></label>
						<textarea id="contact-message" name="contact_message" rows="7" placeholder="<?php esc_attr_e( 'escreva sua mensagem', 'versao-ltda-theme' ); ?>"></textarea>
					</div>

					<button class="button button--primary" type="submit"><?php esc_html_e( 'Enviar mensagem', 'versao-ltda-theme' ); ?></button>
				</form>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
