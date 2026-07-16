<?php
/**
 * Contact page template.
 *
 * @package Versao_Ltda_Theme
 */

get_header();

$contact_status = isset( $_GET['contact_status'] ) ? sanitize_key( wp_unslash( $_GET['contact_status'] ) ) : '';
$contact_notice = '';
$notice_class   = 'contact-form__notice';

if ( 'success' === $contact_status ) {
	$contact_notice = __( 'Mensagem enviada com sucesso. Em breve entraremos em contato.', 'versao-ltda-theme' );
	$notice_class  .= ' is-success';
} elseif ( 'validation-error' === $contact_status ) {
	$contact_notice = __( 'Confira os campos obrigatórios e tente novamente.', 'versao-ltda-theme' );
	$notice_class  .= ' is-error';
} elseif ( in_array( $contact_status, array( 'security-error', 'send-error' ), true ) ) {
	$contact_notice = __( 'Não foi possível enviar sua mensagem. Tente novamente mais tarde.', 'versao-ltda-theme' );
	$notice_class  .= ' is-error';
}
?>

<main id="main" class="site-main contact-page">
	<section class="contact-section section">
		<div class="container container--narrow">
			<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Caminho de navegação', 'versao-ltda-theme' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'versao-ltda-theme' ); ?></a>
				<span>/</span>
				<span><?php esc_html_e( 'Contato', 'versao-ltda-theme' ); ?></span>
			</nav>

			<header class="contact-copy">
				<h1><?php esc_html_e( 'Fale com a Versão LTDA.', 'versao-ltda-theme' ); ?></h1>
				<p><?php esc_html_e( 'Dúvidas sobre pedidos, produtos ou lançamentos?', 'versao-ltda-theme' ); ?></p>
			</header>

			<div class="contact-grid">
				<form id="contact-form" class="contact-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
					<input type="hidden" name="action" value="versao_ltda_contact">
					<?php wp_nonce_field( 'versao_ltda_contact', 'versao_ltda_contact_nonce' ); ?>

					<?php if ( $contact_notice ) : ?>
						<p class="<?php echo esc_attr( $notice_class ); ?>" role="status"><?php echo esc_html( $contact_notice ); ?></p>
					<?php endif; ?>

					<div class="contact-form__trap" aria-hidden="true">
						<label for="contact-website">Website</label>
						<input id="contact-website" type="text" name="contact_website" tabindex="-1" autocomplete="off">
					</div>
					<div class="contact-form__row">
						<label for="contact-name"><?php esc_html_e( 'Nome', 'versao-ltda-theme' ); ?></label>
						<input id="contact-name" type="text" name="contact_name" autocomplete="name" required>
					</div>

					<div class="contact-form__row">
						<label for="contact-email"><?php esc_html_e( 'E-mail', 'versao-ltda-theme' ); ?></label>
						<input id="contact-email" type="email" name="contact_email" autocomplete="email" required>
					</div>

					<div class="contact-form__row">
						<label for="contact-subject"><?php esc_html_e( 'Assunto', 'versao-ltda-theme' ); ?></label>
						<input id="contact-subject" type="text" name="contact_subject" required>
					</div>

					<div class="contact-form__row">
						<label for="contact-order"><?php esc_html_e( 'Número do Pedido (Opcional)', 'versao-ltda-theme' ); ?></label>
						<input id="contact-order" type="text" name="contact_order">
					</div>

					<div class="contact-form__row">
						<label for="contact-message"><?php esc_html_e( 'Mensagem', 'versao-ltda-theme' ); ?></label>
						<textarea id="contact-message" name="contact_message" rows="9" required></textarea>
					</div>

					<button class="button contact-form__submit" type="submit"><?php esc_html_e( 'Enviar', 'versao-ltda-theme' ); ?></button>
				</form>

				<aside class="contact-details" aria-label="<?php esc_attr_e( 'Canais de contato', 'versao-ltda-theme' ); ?>">
					<section class="contact-details__item">
						<h2><?php esc_html_e( 'Atendimento', 'versao-ltda-theme' ); ?></h2>
						<p><?php esc_html_e( 'Para dúvidas sobre pedidos, pagamentos, produtos ou entregas, utilize o formulário ao lado.', 'versao-ltda-theme' ); ?></p>
						<a href="mailto:atendimento@versaoltda.com.br">atendimento@versaoltda.com.br</a>
					</section>

					<section class="contact-details__item">
						<h2><?php esc_html_e( 'Estúdios e parceiros', 'versao-ltda-theme' ); ?></h2>
						<p><?php esc_html_e( 'Tem um jogo que merece uma edição física? Entre em contato para apresentar seu projeto e conhecer nosso trabalho de publicação e distribuição.', 'versao-ltda-theme' ); ?></p>
						<a href="mailto:publishing@versaoltda.com.br">publishing@versaoltda.com.br</a>
					</section>

					<section class="contact-details__item">
						<h2><?php esc_html_e( 'Imprensa e criadores', 'versao-ltda-theme' ); ?></h2>
						<p><?php esc_html_e( 'Para materiais de imprensa, entrevistas, análises e propostas de conteúdo:', 'versao-ltda-theme' ); ?></p>
						<a href="mailto:press@versaoltda.com.br">press@versaoltda.com.br</a>
					</section>
				</aside>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
