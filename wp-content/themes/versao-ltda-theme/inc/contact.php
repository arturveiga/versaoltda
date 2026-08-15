<?php
/**
 * Contact form processing and optional SMTP configuration.
 *
 * @package Versao_Ltda_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read and trim a contact-related environment variable.
 *
 * @param string $name Environment variable name.
 * @param string $default Default value.
 * @return string
 */
function versao_ltda_contact_env( $name, $default = '' ) {
	$value = getenv( $name );

	return false === $value ? $default : trim( (string) $value );
}

/**
 * Set a valid sender before WordPress validates the message headers.
 *
 * @param string $from_email Default sender address.
 * @return string
 */
function versao_ltda_mail_from( $from_email ) {
	$configured_email = sanitize_email( versao_ltda_contact_env( 'WORDPRESS_SMTP_FROM_EMAIL' ) );
	$username         = sanitize_email( versao_ltda_contact_env( 'WORDPRESS_SMTP_USERNAME' ) );

	if ( is_email( $configured_email ) ) {
		return $configured_email;
	}

	return is_email( $username ) ? $username : $from_email;
}
add_filter( 'wp_mail_from', 'versao_ltda_mail_from' );

/**
 * Set the configured sender name.
 *
 * @param string $from_name Default sender name.
 * @return string
 */
function versao_ltda_mail_from_name( $from_name ) {
	return versao_ltda_contact_env( 'WORDPRESS_SMTP_FROM_NAME', $from_name );
}
add_filter( 'wp_mail_from_name', 'versao_ltda_mail_from_name' );

/**
 * Configure WordPress' bundled PHPMailer when SMTP variables are present.
 *
 * @param PHPMailer\PHPMailer\PHPMailer $phpmailer PHPMailer instance.
 * @return void
 */
function versao_ltda_configure_phpmailer( $phpmailer ) {
	$host = versao_ltda_contact_env( 'WORDPRESS_SMTP_HOST' );

	if ( '' === $host ) {
		return;
	}

	$username   = versao_ltda_contact_env( 'WORDPRESS_SMTP_USERNAME' );
	$password   = versao_ltda_contact_env( 'WORDPRESS_SMTP_PASSWORD' );
	$encryption = strtolower( versao_ltda_contact_env( 'WORDPRESS_SMTP_ENCRYPTION', 'tls' ) );
	$auth_env   = versao_ltda_contact_env( 'WORDPRESS_SMTP_AUTH', '' );
	$from_email = sanitize_email( versao_ltda_contact_env( 'WORDPRESS_SMTP_FROM_EMAIL' ) );
	$from_name  = versao_ltda_contact_env( 'WORDPRESS_SMTP_FROM_NAME', get_bloginfo( 'name' ) );

	$phpmailer->isSMTP();
	$phpmailer->Host       = $host;
	$phpmailer->Port       = absint( versao_ltda_contact_env( 'WORDPRESS_SMTP_PORT', '587' ) ) ?: 587;
	$phpmailer->SMTPAuth   = '' === $auth_env ? '' !== $username : filter_var( $auth_env, FILTER_VALIDATE_BOOLEAN );
	$phpmailer->Username   = $username;
	$phpmailer->Password   = $password;
	$phpmailer->SMTPSecure = in_array( $encryption, array( 'ssl', 'tls' ), true ) ? $encryption : '';
	$phpmailer->SMTPAutoTLS = in_array( $encryption, array( 'ssl', 'tls' ), true );
	$phpmailer->CharSet    = 'UTF-8';

	if ( ! $from_email && is_email( $username ) ) {
		$from_email = $username;
	}

	if ( $from_email ) {
		$phpmailer->From     = $from_email;
		$phpmailer->FromName = $from_name;
		$phpmailer->Sender   = $from_email;
	}
}
add_action( 'phpmailer_init', 'versao_ltda_configure_phpmailer' );

/**
 * Return the contact page URL with a status message anchor.
 *
 * @param string $status Submission status.
 * @return string
 */
function versao_ltda_get_contact_status_url( $status ) {
	return add_query_arg(
		'contact_status',
		sanitize_key( $status ),
		home_url( '/contato/' )
	) . '#contact-form';
}

/**
 * Process public and authenticated contact form submissions.
 *
 * @return void
 */
function versao_ltda_handle_contact_form() {
	if (
		! isset( $_POST['versao_ltda_contact_nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['versao_ltda_contact_nonce'] ) ), 'versao_ltda_contact' )
	) {
		wp_safe_redirect( versao_ltda_get_contact_status_url( 'security-error' ) );
		exit;
	}

	$honeypot = isset( $_POST['contact_website'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_website'] ) ) : '';

	if ( '' !== $honeypot ) {
		wp_safe_redirect( versao_ltda_get_contact_status_url( 'success' ) );
		exit;
	}

	$name    = isset( $_POST['contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_name'] ) ) : '';
	$email   = isset( $_POST['contact_email'] ) ? sanitize_email( wp_unslash( $_POST['contact_email'] ) ) : '';
	$subject = isset( $_POST['contact_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_subject'] ) ) : '';
	$order   = isset( $_POST['contact_order'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_order'] ) ) : '';
	$message = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';

	if ( '' === $name || ! is_email( $email ) || '' === $subject || '' === $message ) {
		wp_safe_redirect( versao_ltda_get_contact_status_url( 'validation-error' ) );
		exit;
	}

	$recipient = sanitize_email( versao_ltda_contact_env( 'WORDPRESS_CONTACT_RECIPIENT', 'atendimento@versaoltda.com.br' ) );

	if ( ! is_email( $recipient ) ) {
		wp_safe_redirect( versao_ltda_get_contact_status_url( 'send-error' ) );
		exit;
	}

	$mail_subject = sprintf( '[%s] %s', get_bloginfo( 'name' ), $subject );
	$mail_body    = implode(
		"\n",
		array(
			'Nome: ' . $name,
			'E-mail: ' . $email,
			'Assunto: ' . $subject,
			'Pedido: ' . ( $order ?: 'Não informado' ),
			'',
			'Mensagem:',
			$message,
		)
	);
	$headers      = array( sprintf( 'Reply-To: %s <%s>', $name, $email ) );
	$sent         = wp_mail( $recipient, $mail_subject, $mail_body, $headers );

	wp_safe_redirect( versao_ltda_get_contact_status_url( $sent ? 'success' : 'send-error' ) );
	exit;
}
add_action( 'admin_post_nopriv_versao_ltda_contact', 'versao_ltda_handle_contact_form' );
add_action( 'admin_post_versao_ltda_contact', 'versao_ltda_handle_contact_form' );
