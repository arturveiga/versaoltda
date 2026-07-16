<?php
/**
 * WooCommerce integration.
 *
 * @package Versao_Ltda_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Find a WooCommerce product by its exact title.
 *
 * @param string $title Product title.
 * @return WC_Product|null
 */
function versao_ltda_get_product_by_title( $title ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return null;
	}

	$query = new WP_Query(
		array(
			'post_type'              => 'product',
			'post_status'            => 'publish',
			's'                      => $title,
			'posts_per_page'         => 10,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	foreach ( $query->posts as $post ) {
		if ( $title === get_the_title( $post ) ) {
			return wc_get_product( $post->ID );
		}
	}

	return null;
}

/**
 * Reserve action data, wired to WooCommerce when the product exists.
 *
 * @param string $title Product title.
 * @return array
 */
function versao_ltda_get_reserve_action( $title ) {
	$product = versao_ltda_get_product_by_title( $title );

	if ( $product && $product->is_purchasable() && $product->is_in_stock() ) {
		return array(
			'href'       => add_query_arg(
				array(
					'add-to-cart' => $product->get_id(),
					'quantity'    => 1,
				),
				wc_get_cart_url()
			),
			'class'      => 'add_to_cart_button',
			'attributes' => sprintf(
				' data-product_id="%1$s" data-product_sku="%2$s" data-quantity="1" rel="nofollow"',
				esc_attr( $product->get_id() ),
				esc_attr( $product->get_sku() )
			),
		);
	}

	return array(
		'href'       => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/' ),
		'class'      => '',
		'attributes' => '',
	);
}

/**
 * Product card action data, wired to WooCommerce when the product exists.
 *
 * @param string $title Product title.
 * @param string $state Visual state.
 * @return array
 */
function versao_ltda_get_product_card_action( $title, $state ) {
	if ( 'is-available' !== $state ) {
		return array(
			'href'       => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/' ),
			'class'      => '',
			'attributes' => '',
		);
	}

	return versao_ltda_get_reserve_action( $title );
}

/**
 * Keep cart counter fresh after WooCommerce AJAX add-to-cart.
 *
 * @param array $fragments WooCommerce fragments.
 * @return array
 */
function versao_ltda_cart_count_fragment( $fragments ) {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $fragments;
	}

	ob_start();
	?>
	<span class="cart-counter" aria-hidden="true">
		<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
	</span>
	<?php
	$fragments['.cart-counter'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'versao_ltda_cart_count_fragment' );

/**
 * Render a product image with a theme fallback when an imported attachment is missing.
 *
 * Database dumps can retain attachment metadata without the matching uploads volume.
 * In that case WordPress still generates an image tag, but its source returns 404.
 *
 * @param WC_Product|null $product Product object.
 * @param string          $product_name Accessible image description.
 * @param string          $size WordPress image size.
 * @return string
 */
function versao_ltda_get_order_product_image( $product, $product_name, $size = 'woocommerce_thumbnail' ) {
	if ( $product instanceof WC_Product ) {
		$image_id   = $product->get_image_id();
		$image_url  = $image_id ? wp_get_attachment_image_url( $image_id, $size ) : '';
		$image_file = $image_id ? get_attached_file( $image_id ) : '';

		if ( $image_url && ( ! $image_file || file_exists( $image_file ) ) ) {
			return wp_get_attachment_image(
				$image_id,
				$size,
				false,
				array(
					'alt'     => $product_name,
					'loading' => 'lazy',
				)
			);
		}
	}

	return sprintf(
		'<img src="%1$s" alt="%2$s" loading="lazy">',
		esc_url( vltda_asset( 'images/product_spin_video_1f171347-a00d-68f0-ac10-cb52c21e94b4_0_0.jpeg' ) ),
		esc_attr( $product_name )
	);
}

/**
 * Complete the customer's location from the cart postcode.
 *
 * Checkout Blocks require more than the postcode before displaying shipping,
 * so city, state and street are populated from the SuperFrete ViaCEP helper.
 *
 * @return void
 */
function versao_ltda_complete_shipping_address_from_postcode() {
	if ( ! function_exists( 'WC' ) || ! WC()->customer ) {
		return;
	}

	$postcode = WC()->customer->get_shipping_postcode();

	if ( ! $postcode || ! class_exists( '\SuperFrete_API\Helpers\AddressHelper' ) ) {
		return;
	}

	$address = \SuperFrete_API\Helpers\AddressHelper::get_address_from_postal_code( $postcode );

	if ( ! is_array( $address ) || empty( $address['uf'] ) || empty( $address['localidade'] ) ) {
		return;
	}

	$state  = wc_clean( $address['uf'] );
	$city   = wc_clean( $address['localidade'] );
	$street = isset( $address['logradouro'] ) ? wc_clean( $address['logradouro'] ) : '';

	WC()->customer->set_shipping_country( 'BR' );
	WC()->customer->set_shipping_state( $state );
	WC()->customer->set_shipping_city( $city );
	WC()->customer->set_shipping_postcode( wc_format_postcode( $postcode, 'BR' ) );

	if ( $street ) {
		WC()->customer->set_shipping_address_1( $street );
	}

	if ( ! WC()->customer->get_billing_first_name() ) {
		WC()->customer->set_billing_country( 'BR' );
		WC()->customer->set_billing_state( $state );
		WC()->customer->set_billing_city( $city );
		WC()->customer->set_billing_postcode( wc_format_postcode( $postcode, 'BR' ) );

		if ( $street ) {
			WC()->customer->set_billing_address_1( $street );
		}
	}

	WC()->customer->save();
}

/**
 * Run WooCommerce's shipping calculator for the custom cart template.
 *
 * @return void
 */
function versao_ltda_calculate_cart_shipping() {
	if ( ! isset( $_POST['calc_shipping'] ) || ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}

	$nonce = isset( $_POST['woocommerce-shipping-calculator-nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['woocommerce-shipping-calculator-nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'woocommerce-shipping-calculator' ) && ! wp_verify_nonce( $nonce, 'woocommerce-cart' ) ) {
		wc_add_notice( __( 'Não foi possível calcular o frete. Atualize a página e tente novamente.', 'versao-ltda-theme' ), 'error' );
		return;
	}

	if ( class_exists( 'WC_Shortcode_Cart' ) ) {
		WC_Shortcode_Cart::calculate_shipping();
		versao_ltda_complete_shipping_address_from_postcode();
		$packages       = WC()->shipping()->calculate_shipping( WC()->cart->get_shipping_packages() );
		$chosen_methods = WC()->session->get( 'chosen_shipping_methods', array() );

		foreach ( $packages as $package_index => $package ) {
			if ( empty( $package['rates'] ) ) {
				continue;
			}

			$current_rate = isset( $chosen_methods[ $package_index ] ) ? $chosen_methods[ $package_index ] : '';

			if ( ! isset( $package['rates'][ $current_rate ] ) ) {
				$chosen_methods[ $package_index ] = array_key_first( $package['rates'] );
			}
		}

		WC()->session->set( 'chosen_shipping_methods', $chosen_methods );
		WC()->cart->calculate_totals();
	}

	wp_safe_redirect( wc_get_cart_url() );
	exit;
}
add_action( 'template_redirect', 'versao_ltda_calculate_cart_shipping', 4 );

/**
 * Persist the shipping rate selected in the custom cart template.
 *
 * WooCommerce then reuses the chosen method and its cost during checkout.
 *
 * @return void
 */
function versao_ltda_update_cart_shipping_method() {
	if ( ! isset( $_POST['versao_ltda_update_shipping'] ) || ! function_exists( 'WC' ) || ! WC()->session ) {
		return;
	}

	$nonce = isset( $_POST['woocommerce-cart-nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['woocommerce-cart-nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'woocommerce-cart' ) ) {
		wc_add_notice( __( 'Não foi possível atualizar o frete. Tente novamente.', 'versao-ltda-theme' ), 'error' );
		return;
	}

	$posted_methods = isset( $_POST['shipping_method'] ) ? (array) wp_unslash( $_POST['shipping_method'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$posted_methods = array_map( 'wc_clean', $posted_methods );
	$packages       = WC()->shipping()->get_packages();
	$chosen_methods = array();

	foreach ( $posted_methods as $package_index => $rate_id ) {
		$package_index = absint( $package_index );

		if ( isset( $packages[ $package_index ]['rates'][ $rate_id ] ) ) {
			$chosen_methods[ $package_index ] = $rate_id;
		}
	}

	if ( empty( $chosen_methods ) ) {
		return;
	}

	WC()->session->set( 'chosen_shipping_methods', $chosen_methods );

	if ( WC()->cart ) {
		WC()->cart->calculate_totals();
	}

	wp_safe_redirect( wc_get_cart_url() );
	exit;
}
add_action( 'template_redirect', 'versao_ltda_update_cart_shipping_method', 5 );

/**
 * Default game catalog used to bootstrap WooCommerce products for the theme.
 *
 * @return array
 */
function versao_ltda_get_default_games() {
	return array(
		array(
			'name'        => 'Demons of Asteborg',
			'sku'         => '001',
			'price'       => '399',
			'state'       => '',
			'stock_status' => 'instock',
			'description' => __( 'Edição física de pré-venda para Mega Drive / Genesis.', 'versao-ltda-theme' ),
		),
		array(
			'name'        => 'Earthion',
			'sku'         => '002',
			'price'       => '399',
			'state'       => 'soon',
			'stock_status' => 'instock',
			'description' => __( 'Edição física em breve para Mega Drive / Genesis.', 'versao-ltda-theme' ),
		),
		array(
			'name'        => 'Daemon Claw',
			'sku'         => '003',
			'price'       => '399',
			'state'       => 'sold',
			'stock_status' => 'outofstock',
			'description' => __( 'Edição física esgotada para Mega Drive / Genesis.', 'versao-ltda-theme' ),
		),
	);
}

/**
 * Create the base WooCommerce products expected by the visual catalog.
 *
 * @return void
 */
function versao_ltda_ensure_default_products() {
	if ( ! class_exists( 'WC_Product_Simple' ) || ! function_exists( 'wc_get_product_id_by_sku' ) ) {
		return;
	}

	foreach ( versao_ltda_get_default_games() as $game ) {
		if ( wc_get_product_id_by_sku( $game['sku'] ) ) {
			continue;
		}

		$product = new WC_Product_Simple();
		$product->set_name( $game['name'] );
		$product->set_slug( sanitize_title( $game['name'] ) );
		$product->set_sku( $game['sku'] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_description( $game['description'] );
		$product->set_short_description( $game['description'] );
		$product->set_regular_price( $game['price'] );
		$product->set_price( $game['price'] );
		$product->set_manage_stock( false );
		$product->set_stock_status( $game['stock_status'] );
		$product->set_sold_individually( true );
		$product->update_meta_data( '_versao_ltda_edition', __( 'Pre-Order Version', 'versao-ltda-theme' ) );

		if ( $game['state'] ) {
			$product->update_meta_data( '_versao_ltda_card_state', $game['state'] );
		}

		$product->save();
	}
}
add_action( 'init', 'versao_ltda_ensure_default_products', 30 );

/**
 * Ensure the whitelist page exists so the header and product hearts have a target.
 *
 * @return void
 */
function versao_ltda_ensure_wishlist_page() {
	$page_id = (int) get_option( 'versao_ltda_wishlist_page_id' );

	if ( $page_id && 'page' === get_post_type( $page_id ) ) {
		return;
	}

	$page = get_page_by_path( 'whitelist' );

	if ( $page ) {
		update_option( 'versao_ltda_wishlist_page_id', $page->ID, false );
		return;
	}

	if ( ! add_option( 'versao_ltda_wishlist_page_lock', time(), '', 'no' ) ) {
		return;
	}

	$page_id = wp_insert_post(
		array(
			'post_title'   => __( 'Whitelist', 'versao-ltda-theme' ),
			'post_name'    => 'whitelist',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '',
		)
	);

	if ( $page_id && ! is_wp_error( $page_id ) ) {
		update_option( 'versao_ltda_wishlist_page_id', $page_id, false );
	}

	delete_option( 'versao_ltda_wishlist_page_lock' );
}
add_action( 'init', 'versao_ltda_ensure_wishlist_page', 31 );

/**
 * Get the current customer's wishlist product IDs.
 *
 * @return int[]
 */
function versao_ltda_get_wishlist_product_ids() {
	if ( is_user_logged_in() ) {
		$product_ids = get_user_meta( get_current_user_id(), '_versao_ltda_wishlist_product_ids', true );
	} elseif ( function_exists( 'WC' ) && WC()->session ) {
		$product_ids = WC()->session->get( 'versao_ltda_wishlist_product_ids', array() );
	} else {
		$product_ids = array();
	}

	return array_values( array_unique( array_filter( array_map( 'absint', (array) $product_ids ) ) ) );
}

/**
 * Save the current customer's wishlist product IDs.
 *
 * @param int[] $product_ids Product IDs.
 * @return void
 */
function versao_ltda_set_wishlist_product_ids( $product_ids ) {
	$product_ids = array_values( array_unique( array_filter( array_map( 'absint', (array) $product_ids ) ) ) );

	if ( is_user_logged_in() ) {
		update_user_meta( get_current_user_id(), '_versao_ltda_wishlist_product_ids', $product_ids );
	} elseif ( function_exists( 'WC' ) && WC()->session ) {
		WC()->session->set( 'versao_ltda_wishlist_product_ids', $product_ids );
	}
}

/**
 * Build a nonce-protected wishlist action URL.
 *
 * @param int    $product_id Product ID.
 * @param string $action Wishlist action.
 * @return string
 */
function versao_ltda_get_wishlist_action_url( $product_id, $action = 'add' ) {
	$product_id = absint( $product_id );
	$action     = 'remove' === $action ? 'remove' : 'add';
	$url        = add_query_arg(
		array(
			'wishlist_action' => $action,
			'product_id'      => $product_id,
		),
		versao_ltda_get_wishlist_url()
	);

	return wp_nonce_url( $url, 'versao_ltda_wishlist_' . $action . '_' . $product_id, 'wishlist_nonce' );
}

/**
 * Add or remove a product from the current customer's wishlist.
 *
 * @return void
 */
function versao_ltda_handle_wishlist_action() {
	$action     = isset( $_GET['wishlist_action'] ) ? sanitize_key( wp_unslash( $_GET['wishlist_action'] ) ) : '';
	$product_id = isset( $_GET['product_id'] ) ? absint( wp_unslash( $_GET['product_id'] ) ) : 0;
	$nonce      = isset( $_GET['wishlist_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['wishlist_nonce'] ) ) : '';

	if ( ! in_array( $action, array( 'add', 'remove' ), true ) || ! $product_id ) {
		return;
	}

	if ( ! wp_verify_nonce( $nonce, 'versao_ltda_wishlist_' . $action . '_' . $product_id ) ) {
		return;
	}

	if ( 'add' === $action && ! wc_get_product( $product_id ) ) {
		return;
	}

	$product_ids = versao_ltda_get_wishlist_product_ids();

	if ( 'add' === $action ) {
		$product_ids[] = $product_id;
	} else {
		$product_ids = array_values( array_diff( $product_ids, array( $product_id ) ) );
	}

	versao_ltda_set_wishlist_product_ids( $product_ids );

	wp_safe_redirect(
		add_query_arg(
			'wishlist_updated',
			$action,
			versao_ltda_get_wishlist_url()
		)
	);
	exit;
}
add_action( 'template_redirect', 'versao_ltda_handle_wishlist_action', 15 );

/**
 * Open the billing form directly when WooCommerce links to the address index.
 *
 * @return void
 */
function versao_ltda_redirect_account_address_index() {
	if ( ! is_user_logged_in() || ! function_exists( 'is_account_page' ) || ! is_account_page() ) {
		return;
	}

	if ( ! is_wc_endpoint_url( 'edit-address' ) || get_query_var( 'edit-address' ) ) {
		return;
	}

	wp_safe_redirect( versao_ltda_get_account_address_url() );
	exit;
}
add_action( 'template_redirect', 'versao_ltda_redirect_account_address_index', 20 );

/**
 * Rename the address complement label without changing its value or placeholder.
 *
 * @param array $fields Default WooCommerce address fields.
 * @return array
 */
function versao_ltda_customize_default_address_fields( $fields ) {
	if ( isset( $fields['address_2'] ) ) {
		$fields['address_2']['label'] = __( 'Complemento (opcional)', 'versao-ltda-theme' );
	}

	return $fields;
}
add_filter( 'woocommerce_default_address_fields', 'versao_ltda_customize_default_address_fields' );

/**
 * Rename the complement field rendered by Checkout Blocks.
 *
 * Checkout Blocks builds core field labels from WooCommerce translations and
 * does not consume the classic address field label filter above.
 *
 * @param string $translation Translated text.
 * @param string $text Original WooCommerce text.
 * @return string
 */
function versao_ltda_translate_checkout_address_2_label( $translation, $text ) {
	if ( 'Apartment, suite, etc.' === $text ) {
		return __( 'Complemento', 'versao-ltda-theme' );
	}

	if ( 'Apartment, suite, etc. (optional)' === $text ) {
		return __( 'Complemento (opcional)', 'versao-ltda-theme' );
	}

	return $translation;
}
add_filter( 'gettext_woocommerce', 'versao_ltda_translate_checkout_address_2_label', 10, 2 );

/**
 * Match the billing address fields to the account design while preserving the
 * native WooCommerce validation and persistence flow.
 *
 * @param array $fields Billing fields.
 * @return array
 */
function versao_ltda_customize_billing_address_fields( $fields ) {
	unset( $fields['billing_company'] );

	$field_settings = array(
		'billing_first_name' => array( 'Nome', 10, 'account-address-field--half' ),
		'billing_last_name'  => array( 'Sobrenome', 20, 'account-address-field--half' ),
		'billing_email'      => array( 'E-mail', 30, 'account-address-field--wide' ),
		'billing_phone'      => array( 'Telefone', 40, 'account-address-field--narrow' ),
		'billing_address_1'  => array( 'Endereço', 50, 'account-address-field--wide' ),
		'billing_address_2'  => array( 'Complemento (opcional)', 60, 'account-address-field--narrow' ),
		'billing_city'       => array( 'Cidade', 70, 'account-address-field--third' ),
		'billing_state'      => array( 'Estado', 80, 'account-address-field--third' ),
		'billing_postcode'   => array( 'CEP', 90, 'account-address-field--third' ),
		'billing_country'    => array( 'País', 100, 'account-address-field--full' ),
	);

	foreach ( $field_settings as $key => $settings ) {
		if ( ! isset( $fields[ $key ] ) ) {
			continue;
		}

		$fields[ $key ]['label']    = __( $settings[0], 'versao-ltda-theme' );
		$fields[ $key ]['priority'] = $settings[1];
		$fields[ $key ]['class']    = array( 'form-row', $settings[2] );
	}

	if ( isset( $fields['billing_phone'] ) ) {
		$fields['billing_phone']['required']    = false;
		$fields['billing_phone']['placeholder'] = '+55 (00) 00000-0000';
	}

	if ( isset( $fields['billing_address_2'] ) ) {
		$fields['billing_address_2']['label_class'] = array();
		$fields['billing_address_2']['placeholder'] = __( 'Apartamento, suíte, bloco etc.', 'versao-ltda-theme' );
	}

	if ( isset( $fields['billing_postcode'] ) ) {
		$fields['billing_postcode']['placeholder'] = '00000-000';
	}

	return $fields;
}
add_filter( 'woocommerce_billing_fields', 'versao_ltda_customize_billing_address_fields' );

/**
 * Get catalog products from WooCommerce.
 *
 * @param array $args Query args.
 * @return WC_Product[]
 */
function versao_ltda_get_catalog_products( $args = array() ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}

	if ( ! empty( $args['search'] ) ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				's'              => sanitize_text_field( $args['search'] ),
				'posts_per_page' => isset( $args['limit'] ) ? absint( $args['limit'] ) : 12,
			)
		);

		return array_values(
			array_filter(
				array_map(
					static function ( $post ) {
						return wc_get_product( $post->ID );
					},
					$query->posts
				)
			)
		);
	}

	$defaults = array(
		'status'  => 'publish',
		'limit'   => 12,
		'orderby' => 'menu_order',
		'order'   => 'ASC',
		'return'  => 'objects',
	);

	return wc_get_products( wp_parse_args( $args, $defaults ) );
}

/**
 * Get product card action data from a product object.
 *
 * @param WC_Product $product Product object.
 * @return array
 */
function versao_ltda_get_product_action_from_product( $product ) {
	$state = $product->get_meta( '_versao_ltda_card_state' );

	if ( 'soon' === $state ) {
		return array(
			'label'      => __( 'Em Breve', 'versao-ltda-theme' ),
			'class'      => 'is-soon',
			'href'       => get_permalink( $product->get_id() ),
			'attributes' => ' aria-disabled="true"',
		);
	}

	if ( 'sold' === $state || ! $product->is_in_stock() ) {
		return array(
			'label'      => __( 'Esgotado', 'versao-ltda-theme' ),
			'class'      => 'is-sold',
			'href'       => get_permalink( $product->get_id() ),
			'attributes' => ' aria-disabled="true"',
		);
	}

	$reserve_action = versao_ltda_get_reserve_action( $product->get_name() );

	return array(
		'label'      => __( 'Reservar agora', 'versao-ltda-theme' ),
		'class'      => 'is-available ' . $reserve_action['class'],
		'href'       => $reserve_action['href'],
		'attributes' => $reserve_action['attributes'],
	);
}

/**
 * Render a product card.
 *
 * @param WC_Product $product Product object.
 * @param int        $index Product index.
 * @param string     $wishlist_mode Wishlist action mode.
 * @return void
 */
function versao_ltda_render_product_card( $product, $index = 1, $wishlist_mode = 'auto' ) {
	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$sku          = $product->get_sku();
	$label        = $sku ? '#' . $sku : sprintf( '#%03d', $index );
	$platform     = $product->get_attribute( 'plataforma' ) ?: $product->get_attribute( 'pa_plataforma' );
	$edition      = $product->get_meta( '_versao_ltda_edition' ) ?: __( 'Pre-Order Version', 'versao-ltda-theme' );
	$action       = versao_ltda_get_product_action_from_product( $product );
	$product_name = $product->get_name();
	$wishlist_ids = versao_ltda_get_wishlist_product_ids();
	$is_saved     = in_array( $product->get_id(), $wishlist_ids, true );

	if ( 'remove' === $wishlist_mode || ( 'auto' === $wishlist_mode && $is_saved ) ) {
		$wishlist_action = 'remove';
		$wishlist_label  = sprintf( __( 'Remover %s da whitelist', 'versao-ltda-theme' ), $product_name );
	} else {
		$wishlist_action = 'add';
		$wishlist_label  = sprintf( __( 'Adicionar %s na whitelist', 'versao-ltda-theme' ), $product_name );
	}

	$wishlist_url = versao_ltda_get_wishlist_action_url( $product->get_id(), $wishlist_action );

	if ( ! $platform ) {
		$platform = __( 'Mega Drive', 'versao-ltda-theme' );
	}
	?>
	<article class="product-card">
		<a class="product-card__image-link" href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" aria-label="<?php echo esc_attr( $product_name ); ?>">
			<?php if ( has_post_thumbnail( $product->get_id() ) ) : ?>
				<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( vltda_asset( 'images/01.jpg' ) ); ?>" alt="<?php echo esc_attr( $product_name ); ?>">
			<?php endif; ?>
		</a>
		<p class="product-card__label"><?php echo esc_html( $label ); ?></p>
		<h3 class="product-card__title">
			<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>"><?php echo esc_html( $product_name ); ?></a>
		</h3>
		<p><?php esc_html_e( 'Plataforma:', 'versao-ltda-theme' ); ?> <?php echo esc_html( $platform ); ?><br><?php echo esc_html( $edition ); ?></p>
		<strong><?php echo wp_kses_post( $product->get_price_html() ?: wc_price( 399 ) ); ?></strong>
		<div class="product-card__actions">
			<a class="button product-card__button <?php echo esc_attr( trim( $action['class'] ) ); ?>" href="<?php echo esc_url( $action['href'] ); ?>"<?php echo $action['attributes']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php echo esc_html( $action['label'] ); ?>
			</a>
			<a class="product-card__wishlist<?php echo 'remove' === $wishlist_action ? ' is-active is-remove' : ''; ?>" href="<?php echo esc_url( $wishlist_url ); ?>" aria-label="<?php echo esc_attr( $wishlist_label ); ?>" title="<?php echo esc_attr( $wishlist_label ); ?>">
				<span aria-hidden="true"></span>
			</a>
		</div>
	</article>
	<?php
}
