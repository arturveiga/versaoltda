<?php
/**
 * Product notify storage and admin screen.
 *
 * @package Versao_Ltda_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'VERSAO_LTDA_PRODUCT_NOTIFY_DB_VERSION' ) ) {
	define( 'VERSAO_LTDA_PRODUCT_NOTIFY_DB_VERSION', '2' );
}

/**
 * Return the product notify table name.
 *
 * @return string
 */
function versao_ltda_get_product_notify_table_name() {
	global $wpdb;

	return $wpdb->prefix . 'versao_ltda_product_notify';
}

/**
 * Create or update the product notify table.
 *
 * @return void
 */
function versao_ltda_install_product_notify_table() {
	global $wpdb;

	$installed_version = get_option( 'versao_ltda_product_notify_db_version', '' );
	$table_name        = versao_ltda_get_product_notify_table_name();

	if ( VERSAO_LTDA_PRODUCT_NOTIFY_DB_VERSION === $installed_version ) {
		$existing_table = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) );

		if ( $existing_table === $table_name ) {
			return;
		}
	}

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$charset_collate = $wpdb->get_charset_collate();
	$sql             = "CREATE TABLE {$table_name} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		source varchar(20) NOT NULL DEFAULT 'product',
		product_id bigint(20) unsigned NOT NULL,
		product_name varchar(255) NOT NULL,
		product_url text NOT NULL,
		customer_email varchar(100) NOT NULL,
		created_at datetime NOT NULL,
		PRIMARY KEY  (id),
		KEY source (source),
		KEY product_id (product_id),
		KEY customer_email (customer_email),
		KEY created_at (created_at)
	) {$charset_collate};";

	dbDelta( $sql );
	update_option( 'versao_ltda_product_notify_db_version', VERSAO_LTDA_PRODUCT_NOTIFY_DB_VERSION );
}
add_action( 'after_switch_theme', 'versao_ltda_install_product_notify_table' );
add_action( 'init', 'versao_ltda_install_product_notify_table' );

/**
 * Return the addressable status URL for the frontend form.
 *
 * @param string $product_url Product permalink.
 * @param string $status Submission status.
 * @return string
 */
function versao_ltda_get_product_notify_status_url( $product_url, $status ) {
	$redirect_url = wp_validate_redirect( $product_url, home_url( '/' ) );

	return add_query_arg(
		'notify_status',
		sanitize_key( $status ),
		$redirect_url
	) . '#product-notify';
}

/**
 * Store a notify entry.
 *
 * @param int    $product_id Product ID.
 * @param string $product_name Product name.
 * @param string $product_url Product URL.
 * @param string $email Customer email.
 * @param string $source Entry source.
 * @return int|false
 */
function versao_ltda_insert_product_notify_entry( $product_id, $product_name, $product_url, $email, $source = 'product' ) {
	global $wpdb;

	versao_ltda_install_product_notify_table();
	$source = sanitize_key( $source );

	if ( '' === $source ) {
		$source = 'product';
	}

	$inserted = $wpdb->insert(
		versao_ltda_get_product_notify_table_name(),
		array(
			'source'         => $source,
			'product_id'     => absint( $product_id ),
			'product_name'   => sanitize_text_field( $product_name ),
			'product_url'    => esc_url_raw( $product_url ),
			'customer_email' => sanitize_email( $email ),
			'created_at'     => current_time( 'mysql' ),
		),
		array( '%s', '%d', '%s', '%s', '%s', '%s' )
	);

	return false === $inserted ? false : (int) $wpdb->insert_id;
}

/**
 * Handle the product notify form submission.
 *
 * @return void
 */
function versao_ltda_handle_product_notify_form() {
	if (
		! isset( $_POST['versao_ltda_product_notify_nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['versao_ltda_product_notify_nonce'] ) ), 'versao_ltda_product_notify' )
	) {
		wp_safe_redirect( versao_ltda_get_product_notify_status_url( home_url( '/' ), 'security-error' ) );
		exit;
	}

	$product_id = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;
	$email      = isset( $_POST['notify_email'] ) ? sanitize_email( wp_unslash( $_POST['notify_email'] ) ) : '';

	if ( ! $product_id || ! is_email( $email ) ) {
		$product_url = isset( $_POST['product_url'] ) ? esc_url_raw( wp_unslash( $_POST['product_url'] ) ) : home_url( '/' );
		wp_safe_redirect( versao_ltda_get_product_notify_status_url( $product_url, 'validation-error' ) );
		exit;
	}

	$product = get_post( $product_id );

	if ( ! $product || 'product' !== $product->post_type ) {
		wp_safe_redirect( versao_ltda_get_product_notify_status_url( home_url( '/' ), 'validation-error' ) );
		exit;
	}

	$product_name = get_the_title( $product_id );
	$product_url  = get_permalink( $product_id );

	if ( '' === $product_name || '' === $product_url ) {
		wp_safe_redirect( versao_ltda_get_product_notify_status_url( home_url( '/' ), 'validation-error' ) );
		exit;
	}

	$stored = versao_ltda_insert_product_notify_entry( $product_id, $product_name, $product_url, $email );

	if ( ! $stored ) {
		wp_safe_redirect( versao_ltda_get_product_notify_status_url( $product_url, 'send-error' ) );
		exit;
	}

	wp_safe_redirect( versao_ltda_get_product_notify_status_url( $product_url, 'success' ) );
	exit;
}
add_action( 'admin_post_nopriv_versao_ltda_product_notify', 'versao_ltda_handle_product_notify_form' );
add_action( 'admin_post_versao_ltda_product_notify', 'versao_ltda_handle_product_notify_form' );

/**
 * Return the newsletter status URL.
 *
 * @param string $status Submission status.
 * @return string
 */
function versao_ltda_get_newsletter_status_url( $status ) {
	return add_query_arg(
		'newsletter_status',
		sanitize_key( $status ),
		wp_get_referer() ? wp_get_referer() : home_url( '/' )
	) . '#newsletter';
}

/**
 * Get the admin list URL for the newsletter table.
 *
 * @param int $product_id Product filter.
 * @param int $paged Page number.
 * @return string
 */
function versao_ltda_get_product_notify_admin_url( $product_id = 0, $paged = 1 ) {
	return add_query_arg(
		array(
			'page'       => 'versao-ltda-product-notify',
			'product_id' => absint( $product_id ),
			'paged'      => max( 1, absint( $paged ) ),
		),
		admin_url( 'admin.php' )
	);
}

/**
 * Get the newsletter/admin source filter value.
 *
 * @return string
 */
function versao_ltda_get_product_notify_source_filter() {
	$source = isset( $_GET['source'] ) ? sanitize_key( wp_unslash( $_GET['source'] ) ) : '';

	return in_array( $source, array( 'product', 'newsletter' ), true ) ? $source : '';
}

/**
 * Build a redirect back to the admin list after a newsletter action.
 *
 * @param string $status Status key.
 * @param int    $product_id Current filter product id.
 * @param int    $paged Current page number.
 * @return string
 */
function versao_ltda_get_product_notify_admin_status_url( $status, $product_id = 0, $paged = 1 ) {
	return add_query_arg(
		'notify_status',
		sanitize_key( $status ),
		versao_ltda_get_product_notify_admin_url( $product_id, $paged )
	);
}

/**
 * Handle footer newsletter submissions.
 *
 * @return void
 */
function versao_ltda_handle_newsletter_form() {
	$ajax_request = ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && 'xmlhttprequest' === strtolower( (string) $_SERVER['HTTP_X_REQUESTED_WITH'] ) );

	if (
		! isset( $_POST['versao_ltda_newsletter_nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['versao_ltda_newsletter_nonce'] ) ), 'versao_ltda_newsletter' )
	) {
		if ( $ajax_request ) {
			wp_send_json_error(
				array(
					'code'    => 'security-error',
					'message' => __( 'Não foi possível enviar o cadastro agora. Tente novamente.', 'versao-ltda-theme' ),
				),
				403
			);
		}

		wp_safe_redirect( versao_ltda_get_newsletter_status_url( 'security-error' ) );
		exit;
	}

	$email = isset( $_POST['newsletter_email'] ) ? sanitize_email( wp_unslash( $_POST['newsletter_email'] ) ) : '';

	if ( ! is_email( $email ) ) {
		if ( $ajax_request ) {
			wp_send_json_error(
				array(
					'code'    => 'validation-error',
					'message' => __( 'Informe um e-mail válido para receber a newsletter.', 'versao-ltda-theme' ),
				),
				422
			);
		}

		wp_safe_redirect( versao_ltda_get_newsletter_status_url( 'validation-error' ) );
		exit;
	}

	$stored = versao_ltda_insert_product_notify_entry(
		0,
		'Newsletter',
		home_url( '/' ),
		$email,
		'newsletter'
	);

	if ( $ajax_request ) {
		if ( $stored ) {
			wp_send_json_success(
				array(
					'code'    => 'success',
					'message' => __( 'Pronto. Vamos avisar quando houver novidades.', 'versao-ltda-theme' ),
				)
			);
		}

		wp_send_json_error(
			array(
				'code'    => 'send-error',
				'message' => __( 'Não foi possível enviar o cadastro agora. Tente novamente.', 'versao-ltda-theme' ),
			),
			500
		);
	}

	wp_safe_redirect( versao_ltda_get_newsletter_status_url( $stored ? 'success' : 'send-error' ) );
	exit;
}
add_action( 'admin_post_nopriv_versao_ltda_newsletter', 'versao_ltda_handle_newsletter_form' );
add_action( 'admin_post_versao_ltda_newsletter', 'versao_ltda_handle_newsletter_form' );

/**
 * Delete a newsletter record from the admin list.
 *
 * @return void
 */
function versao_ltda_handle_product_notify_delete() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Acesso negado.', 'versao-ltda-theme' ), '', array( 'response' => 403 ) );
	}

	$entry_id    = isset( $_GET['entry_id'] ) ? absint( wp_unslash( $_GET['entry_id'] ) ) : 0;
	$product_id  = isset( $_GET['product_id'] ) ? absint( wp_unslash( $_GET['product_id'] ) ) : 0;
	$paged       = isset( $_GET['paged'] ) ? max( 1, absint( wp_unslash( $_GET['paged'] ) ) ) : 1;
	$redirect    = versao_ltda_get_product_notify_admin_status_url( 'send-error', $product_id, $paged );

	check_admin_referer( 'versao_ltda_product_notify_delete_' . $entry_id );

	if ( ! $entry_id ) {
		wp_safe_redirect( $redirect );
		exit;
	}

	global $wpdb;
	$table = versao_ltda_get_product_notify_table_name();
	$entry = $wpdb->get_row( $wpdb->prepare( "SELECT id, source FROM {$table} WHERE id = %d", $entry_id ) );

	if ( ! $entry || 'newsletter' !== $entry->source ) {
		wp_safe_redirect( $redirect );
		exit;
	}

	$deleted = $wpdb->delete( $table, array( 'id' => $entry_id ), array( '%d' ) );
	$redirect = versao_ltda_get_product_notify_admin_status_url( $deleted ? 'deleted' : 'send-error', $product_id, $paged );

	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'admin_post_versao_ltda_product_notify_delete', 'versao_ltda_handle_product_notify_delete' );

/**
 * Register the admin screen for notify leads.
 *
 * @return void
 */
function versao_ltda_register_product_notify_admin_page() {
	add_menu_page(
		'Avise-me',
		'Avise-me',
		'manage_options',
		'versao-ltda-product-notify',
		'versao_ltda_render_product_notify_admin_page',
		'dashicons-bell',
		58
	);
}
add_action( 'admin_menu', 'versao_ltda_register_product_notify_admin_page' );

/**
 * Build a filtered list of product notify entries.
 *
 * @param array<string, mixed> $args Query arguments.
 * @return array<int, object>
 */
function versao_ltda_get_product_notify_entries( $args = array() ) {
	global $wpdb;

	$defaults = array(
		'product_id' => 0,
		'source'     => '',
		'limit'      => 50,
		'offset'     => 0,
		'orderby'    => 'created_at',
		'order'      => 'DESC',
	);
	$args     = wp_parse_args( $args, $defaults );
	$table    = versao_ltda_get_product_notify_table_name();
	$where    = 'WHERE 1=1';
	$params   = array();

	if ( ! empty( $args['product_id'] ) ) {
		$where   .= ' AND product_id = %d';
		$params[] = absint( $args['product_id'] );
	}

	if ( ! empty( $args['source'] ) && in_array( $args['source'], array( 'product', 'newsletter' ), true ) ) {
		$where   .= ' AND source = %s';
		$params[] = sanitize_key( $args['source'] );
	}

	$order_by = in_array( $args['orderby'], array( 'created_at', 'product_name', 'customer_email' ), true ) ? $args['orderby'] : 'created_at';
	$order    = 'ASC' === strtoupper( (string) $args['order'] ) ? 'ASC' : 'DESC';
	$sql      = "SELECT id, source, product_id, product_name, product_url, customer_email, created_at FROM {$table} {$where} ORDER BY {$order_by} {$order}";

	if ( ! empty( $args['limit'] ) ) {
		$sql      .= ' LIMIT %d OFFSET %d';
		$params[]  = absint( $args['limit'] );
		$params[]  = absint( $args['offset'] );
		$sql       = $wpdb->prepare( $sql, $params );
	} elseif ( ! empty( $params ) ) {
		$sql = $wpdb->prepare( $sql, $params );
	}

	return $wpdb->get_results( $sql );
}

/**
 * Count entries for the current filter.
 *
 * @param int $product_id Product ID filter.
 * @return int
 */
function versao_ltda_count_product_notify_entries( $product_id = 0 ) {
	global $wpdb;

	$table = versao_ltda_get_product_notify_table_name();
	$where = '';
	$args  = array();

	if ( $product_id ) {
		$where   = 'WHERE product_id = %d';
		$args[]  = absint( $product_id );
	}

	$sql = "SELECT COUNT(*) FROM {$table} {$where}";

	if ( ! empty( $args ) ) {
		$sql = $wpdb->prepare( $sql, $args );
	}

	return (int) $wpdb->get_var( $sql );
}

/**
 * Count entries for the current filters.
 *
 * @param int    $product_id Product ID filter.
 * @param string $source Source filter.
 * @return int
 */
function versao_ltda_count_product_notify_entries_filtered( $product_id = 0, $source = '' ) {
	global $wpdb;

	$table = versao_ltda_get_product_notify_table_name();
	$where = 'WHERE 1=1';
	$args  = array();

	if ( $product_id ) {
		$where  .= ' AND product_id = %d';
		$args[] = absint( $product_id );
	}

	if ( in_array( $source, array( 'product', 'newsletter' ), true ) ) {
		$where  .= ' AND source = %s';
		$args[] = sanitize_key( $source );
	}

	$sql = "SELECT COUNT(*) FROM {$table} {$where}";

	if ( ! empty( $args ) ) {
		$sql = $wpdb->prepare( $sql, $args );
	}

	return (int) $wpdb->get_var( $sql );
}

/**
 * Return the product list used by the filter dropdown.
 *
 * @return array<int, object>
 */
function versao_ltda_get_product_notify_products() {
	global $wpdb;

	$table = versao_ltda_get_product_notify_table_name();
	$sql   = "SELECT DISTINCT product_id, product_name FROM {$table} WHERE product_id > 0 ORDER BY product_name ASC";

	return $wpdb->get_results( $sql );
}

/**
 * Convert a UTF-8 string into a PDF-safe text string.
 *
 * @param string $text Text to escape.
 * @return string
 */
function versao_ltda_pdf_text( $text ) {
	$text = (string) $text;

	if ( function_exists( 'iconv' ) ) {
		$converted = iconv( 'UTF-8', 'Windows-1252//TRANSLIT', $text );

		if ( false !== $converted ) {
			$text = $converted;
		}
	} elseif ( function_exists( 'mb_convert_encoding' ) ) {
		$text = mb_convert_encoding( $text, 'Windows-1252', 'UTF-8' );
	} else {
		$text = utf8_decode( $text );
	}

	return str_replace( array( '\\', '(', ')' ), array( '\\\\', '\\(', '\\)' ), $text );
}

/**
 * Word-wrap a string for PDF output.
 *
 * @param string $text Text to wrap.
 * @param int    $length Max characters per line.
 * @return array<int, string>
 */
function versao_ltda_pdf_wrap_lines( $text, $length = 88 ) {
	$text  = trim( preg_replace( '/\s+/u', ' ', (string) $text ) );
	$lines = array();

	if ( '' === $text ) {
		return $lines;
	}

	$words = preg_split( '/\s+/u', $text );
	$line  = '';

	foreach ( $words as $word ) {
		$test = '' === $line ? $word : $line . ' ' . $word;

		if ( strlen( $test ) <= $length ) {
			$line = $test;
			continue;
		}

		if ( '' !== $line ) {
			$lines[] = $line;
		}

		$line = $word;

		while ( strlen( $line ) > $length ) {
			$lines[] = substr( $line, 0, $length );
			$line    = substr( $line, $length );
		}
	}

	if ( '' !== $line ) {
		$lines[] = $line;
	}

	return $lines;
}

/**
 * Build a minimal PDF document.
 *
 * @param array<int, string> $pages Page content lines.
 * @return string
 */
function versao_ltda_build_pdf_document( $pages ) {
	$objects = array();
	$objects[] = '<< /Type /Catalog /Pages 2 0 R >>';

	$page_kids = array();
	$object_id  = 3;

	foreach ( $pages as $page_lines ) {
		$page_object_id    = $object_id;
		$content_object_id  = $object_id + 1;
		$page_kids[]        = $page_object_id . ' 0 R';
		$objects[]          = sprintf(
			'<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 3 0 R >> >> /Contents %d 0 R >>',
			$page_object_id
		);

		$content = "BT /F1 12 Tf 50 790 Td 14 TL";
		$first   = true;

		foreach ( $page_lines as $line ) {
			$pdf_line = versao_ltda_pdf_text( $line );

			if ( $first ) {
				$content .= ' (' . $pdf_line . ') Tj';
				$first    = false;
				continue;
			}

			$content .= ' T* (' . $pdf_line . ') Tj';
		}

		$content  .= ' ET';
		$objects[] = '<< /Length ' . strlen( $content ) . " >>\nstream\n" . $content . "\nendstream";
		$object_id += 2;
	}

	$objects[1] = '<< /Type /Pages /Kids [' . implode( ' ', $page_kids ) . '] /Count ' . count( $pages ) . ' >>';
	$objects[]  = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';

	$pdf      = "%PDF-1.4\n";
	$offsets  = array( 0 );

	foreach ( $objects as $index => $body ) {
		$offsets[] = strlen( $pdf );
		$pdf      .= ( $index + 1 ) . " 0 obj\n" . $body . "\nendobj\n";
	}

	$xref_offset = strlen( $pdf );
	$pdf        .= "xref\n0 " . ( count( $objects ) + 1 ) . "\n";
	$pdf        .= "0000000000 65535 f \n";

	foreach ( array_slice( $offsets, 1 ) as $offset ) {
		$pdf .= sprintf( '%010d 00000 n ', $offset ) . "\n";
	}

	$pdf .= "trailer\n<< /Size " . ( count( $objects ) + 1 ) . " /Root 1 0 R >>\nstartxref\n" . $xref_offset . "\n%%EOF";

	return $pdf;
}

/**
 * Build an Excel-compatible HTML document for the current filter.
 *
 * @param array<int, object> $entries Filtered entries.
 * @param string             $filter_label Current filter label.
 * @return string
 */
function versao_ltda_build_product_notify_excel_document( $entries, $filter_label ) {
	ob_start();
	?>
	<!DOCTYPE html>
	<html lang="pt-BR">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Avise-me</title>
	</head>
	<body>
		<table>
			<tr>
				<td><strong>Avise-me - Versao LTDA</strong></td>
			</tr>
			<tr>
				<td>Filtro: <?php echo esc_html( $filter_label ); ?></td>
			</tr>
			<tr>
				<td>Gerado em: <?php echo esc_html( wp_date( 'd/m/Y H:i' ) ); ?></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<th>Data</th>
				<th>Jogo</th>
				<th>E-mail</th>
				<th>Link</th>
			</tr>
			<?php if ( empty( $entries ) ) : ?>
				<tr>
					<td colspan="4"><?php esc_html_e( 'Nenhum cadastro encontrado para o filtro selecionado.', 'versao-ltda-theme' ); ?></td>
				</tr>
			<?php else : ?>
				<?php foreach ( $entries as $entry ) : ?>
					<tr>
						<td><?php echo esc_html( wp_date( 'd/m/Y H:i', strtotime( $entry->created_at ) ) ); ?></td>
						<td><?php echo esc_html( $entry->product_name ); ?></td>
						<td><?php echo esc_html( $entry->customer_email ); ?></td>
						<td><?php echo esc_html( $entry->product_url ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</table>
	</body>
	</html>
	<?php
	return (string) ob_get_clean();
}

/**
 * Send an Excel export of the current filter.
 *
 * @return void
 */
function versao_ltda_handle_product_notify_export_excel() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Acesso negado.', 'versao-ltda-theme' ), '', array( 'response' => 403 ) );
	}

	check_admin_referer( 'versao_ltda_product_notify_export_excel' );

	$product_id = isset( $_GET['product_id'] ) ? absint( wp_unslash( $_GET['product_id'] ) ) : 0;
	$source     = versao_ltda_get_product_notify_source_filter();
	$entries    = versao_ltda_get_product_notify_entries(
		array(
			'product_id' => $product_id,
			'source'     => $source,
			'limit'      => 0,
		)
	);

	$filter_label = array();
	$filter_label[] = $product_id ? get_the_title( $product_id ) : __( 'Todos os jogos', 'versao-ltda-theme' );
	if ( $source ) {
		$filter_label[] = 'newsletter' === $source ? __( 'Newsletter', 'versao-ltda-theme' ) : __( 'Produto', 'versao-ltda-theme' );
	}
	$filter_label = implode( ' / ', $filter_label );
	$excel        = versao_ltda_build_product_notify_excel_document( $entries, $filter_label );

	nocache_headers();
	header( 'Content-Type: application/vnd.ms-excel; charset=UTF-8' );
	header( 'Content-Disposition: attachment; filename="avise-me-' . gmdate( 'Y-m-d' ) . '.xls"' );
	header( 'Content-Length: ' . strlen( $excel ) );
	echo $excel; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit;
}
add_action( 'admin_post_versao_ltda_product_notify_export_excel', 'versao_ltda_handle_product_notify_export_excel' );

/**
 * Render the admin screen.
 *
 * @return void
 */
function versao_ltda_render_product_notify_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Acesso negado.', 'versao-ltda-theme' ), '', array( 'response' => 403 ) );
	}

	$selected_product_id = isset( $_GET['product_id'] ) ? absint( wp_unslash( $_GET['product_id'] ) ) : 0;
	$selected_source     = versao_ltda_get_product_notify_source_filter();
	$paged               = isset( $_GET['paged'] ) ? max( 1, absint( wp_unslash( $_GET['paged'] ) ) ) : 1;
	$per_page            = 20;
	$offset              = ( $paged - 1 ) * $per_page;

	$entries = versao_ltda_get_product_notify_entries(
		array(
			'product_id' => $selected_product_id,
			'source'     => $selected_source,
			'limit'      => $per_page,
			'offset'     => $offset,
		)
	);
	$total   = versao_ltda_count_product_notify_entries_filtered( $selected_product_id, $selected_source );
	$pages   = max( 1, (int) ceil( $total / $per_page ) );
	$products = versao_ltda_get_product_notify_products();
	$export_url = add_query_arg(
		array(
			'action'     => 'versao_ltda_product_notify_export_excel',
			'product_id' => $selected_product_id,
			'source'     => $selected_source,
		),
		admin_url( 'admin-post.php' )
	);
	$export_url = wp_nonce_url( $export_url, 'versao_ltda_product_notify_export_excel' );
	$page_url   = versao_ltda_get_product_notify_admin_url( $selected_product_id, $paged );
	$notice     = isset( $_GET['notify_status'] ) ? sanitize_key( wp_unslash( $_GET['notify_status'] ) ) : '';
	?>
	<div class="wrap">
		<h1>Avise-me</h1>
		<p>Cadastros recebidos pelo formulário do produto. Use o filtro para ver um jogo específico ou exportar o resultado para Excel.</p>

		<?php if ( 'deleted' === $notice ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Cadastro removido com sucesso.', 'versao-ltda-theme' ); ?></p></div>
		<?php endif; ?>

		<form method="get" action="<?php echo esc_url( $page_url ); ?>" style="margin: 1rem 0; display:flex; gap:12px; align-items:end; flex-wrap:wrap;">
			<input type="hidden" name="page" value="versao-ltda-product-notify">
			<label>
				<span style="display:block; margin-bottom:4px;">Filtrar por jogo</span>
				<select name="product_id">
					<option value="0"><?php esc_html_e( 'Todos os jogos', 'versao-ltda-theme' ); ?></option>
					<?php foreach ( $products as $product ) : ?>
						<option value="<?php echo esc_attr( $product->product_id ); ?>" <?php selected( $selected_product_id, $product->product_id ); ?>><?php echo esc_html( $product->product_name ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
			<label>
				<span style="display:block; margin-bottom:4px;">Filtrar por origem</span>
				<select name="source">
					<option value=""><?php esc_html_e( 'Todas as origens', 'versao-ltda-theme' ); ?></option>
					<option value="newsletter" <?php selected( $selected_source, 'newsletter' ); ?>><?php esc_html_e( 'Newsletter', 'versao-ltda-theme' ); ?></option>
					<option value="product" <?php selected( $selected_source, 'product' ); ?>><?php esc_html_e( 'Produto', 'versao-ltda-theme' ); ?></option>
				</select>
			</label>
			<button class="button button-primary" type="submit"><?php esc_html_e( 'Filtrar', 'versao-ltda-theme' ); ?></button>
			<a class="button" href="<?php echo esc_url( $export_url ); ?>"><?php esc_html_e( 'Baixar Excel', 'versao-ltda-theme' ); ?></a>
		</form>

		<p><strong><?php echo esc_html( number_format_i18n( $total ) ); ?></strong> cadastros encontrados.</p>

		<table class="widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Data', 'versao-ltda-theme' ); ?></th>
					<th><?php esc_html_e( 'Origem', 'versao-ltda-theme' ); ?></th>
					<th><?php esc_html_e( 'Jogo', 'versao-ltda-theme' ); ?></th>
					<th><?php esc_html_e( 'E-mail', 'versao-ltda-theme' ); ?></th>
					<th><?php esc_html_e( 'Link', 'versao-ltda-theme' ); ?></th>
					<th><?php esc_html_e( 'Ações', 'versao-ltda-theme' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $entries ) ) : ?>
					<tr>
						<td colspan="6"><?php esc_html_e( 'Nenhum cadastro encontrado.', 'versao-ltda-theme' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $entries as $entry ) : ?>
						<tr>
							<td><?php echo esc_html( wp_date( 'd/m/Y H:i', strtotime( $entry->created_at ) ) ); ?></td>
							<td><?php echo esc_html( 'newsletter' === $entry->source ? __( 'Newsletter', 'versao-ltda-theme' ) : __( 'Produto', 'versao-ltda-theme' ) ); ?></td>
							<td><?php echo esc_html( $entry->product_name ); ?></td>
							<td><a href="mailto:<?php echo esc_attr( $entry->customer_email ); ?>"><?php echo esc_html( $entry->customer_email ); ?></a></td>
							<td><a href="<?php echo esc_url( $entry->product_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Ver produto', 'versao-ltda-theme' ); ?></a></td>
							<td>
								<?php if ( 'newsletter' === $entry->source ) : ?>
									<?php
									$delete_url = add_query_arg(
										array(
											'action'     => 'versao_ltda_product_notify_delete',
											'entry_id'   => $entry->id,
											'product_id' => $selected_product_id,
											'paged'      => $paged,
										),
										admin_url( 'admin-post.php' )
									);
									$delete_url = wp_nonce_url( $delete_url, 'versao_ltda_product_notify_delete_' . $entry->id );
									?>
									<a class="button button-small" href="<?php echo esc_url( $delete_url ); ?>" onclick="return confirm('<?php echo esc_js( __( 'Remover este cadastro?', 'versao-ltda-theme' ) ); ?>');"><?php esc_html_e( 'Remover', 'versao-ltda-theme' ); ?></a>
								<?php else : ?>
									&mdash;
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>

		<?php if ( $pages > 1 ) : ?>
			<?php
			$pagination_base = add_query_arg(
				array(
					'page'       => 'versao-ltda-product-notify',
					'product_id' => $selected_product_id,
					'source'     => $selected_source,
					'paged'      => '%#%',
				),
				admin_url( 'admin.php' )
			);
			echo wp_kses_post(
				paginate_links(
					array(
						'base'      => $pagination_base,
						'format'    => '',
						'current'   => $paged,
						'total'     => $pages,
						'prev_text' => '&laquo;',
						'next_text' => '&raquo;',
					)
				)
			);
			?>
		<?php endif; ?>
	</div>
	<?php
}