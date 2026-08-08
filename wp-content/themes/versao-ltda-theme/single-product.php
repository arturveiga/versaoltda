<?php
/**
 * Single WooCommerce product template.
 *
 * @package Versao_Ltda_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	global $product;

	if ( ! $product instanceof WC_Product ) {
		$product = wc_get_product( get_the_ID() );
	}

	if ( ! $product instanceof WC_Product ) {
		continue;
	}

	do_action( 'woocommerce_before_single_product' );

	$product_id   = $product->get_id();
	$product_name = $product->get_name();
	$sku          = $product->get_sku() ?: str_pad( (string) $product_id, 3, '0', STR_PAD_LEFT );
	$platform     = $product->get_attribute( 'plataforma' ) ?: $product->get_attribute( 'pa_plataforma' );
	$platform     = $platform ?: __( 'Mega Drive / Genesis', 'versao-ltda-theme' );
	$action       = versao_ltda_get_product_action_from_product( $product );
	$wishlist_ids = versao_ltda_get_wishlist_product_ids();
	$is_saved     = in_array( $product_id, $wishlist_ids, true );
	$wishlist_url = versao_ltda_get_wishlist_action_url( $product_id, $is_saved ? 'remove' : 'add' );
	$is_demo      = '001' === $sku || 'demons-of-asteborg' === $product->get_slug();
	$is_waiting_preorder = false;
	$short_copy   = trim( wp_strip_all_tags( $product->get_short_description() ) );
	$full_copy    = trim( wp_strip_all_tags( $product->get_description() ) );
	$notify_status = isset( $_GET['notify_status'] ) ? sanitize_key( wp_unslash( $_GET['notify_status'] ) ) : '';
	$copies_match = $short_copy && $full_copy && $short_copy === $full_copy;
	$short_words  = $short_copy ? preg_split( '/\s+/u', $short_copy ) : array();
	$full_words   = $full_copy ? preg_split( '/\s+/u', $full_copy ) : array();

	if ( $is_demo && count( $short_words ) < 12 ) {
		$short_copy = __( 'Assuma o papel de Gareth, um cavaleiro treinado para proteger seu povo, e enfrente Zodimus em uma jornada épica para salvar o reino de Asteborg.', 'versao-ltda-theme' );
	}

	if ( $is_demo && ( $copies_match || count( $full_words ) < 20 ) ) {
		$full_copy  = __( 'Explore florestas, montanhas, pântanos e cenários impressionantes em um dos maiores jogos já criados para 16-bit. Com 128 megabits, animações fluidas, trilha sonora poderosa e chefes memoráveis, Demons of Asteborg é uma verdadeira carta de amor aos clássicos.', 'versao-ltda-theme' );
	}

	if ( ! $short_copy ) {
		$short_copy = sprintf(
			/* translators: %s: Product name. */
			__( 'Conheça %s, uma edição física criada para quem valoriza jogos clássicos e lançamentos especiais.', 'versao-ltda-theme' ),
			$product_name
		);
	}

	if ( ! $full_copy ) {
		$full_copy = __( 'Confira os detalhes desta edição, acompanhe a disponibilidade e reserve sua unidade enquanto houver estoque.', 'versao-ltda-theme' );
	}

	$story_title = $is_demo
		? __( 'Aventura, ação e magia em 16-bits.', 'versao-ltda-theme' )
		: sprintf(
			/* translators: %s: Product name. */
			__( 'Conheça %s.', 'versao-ltda-theme' ),
			$product_name
		);

	if ( $is_demo ) {
		$gallery = array(
			array( 'package-carousel/01-complete-edition.jpg', __( 'Caixa e cartucho de Demons of Asteborg em cenário inspirado no jogo', 'versao-ltda-theme' ) ),
			array( 'package-carousel/02-game-box.jpg', __( 'Caixa da edição física de Demons of Asteborg', 'versao-ltda-theme' ) ),
			array( 'package-carousel/03-open-premium-package.jpg', __( 'Embalagem premium aberta com o cartucho de Demons of Asteborg', 'versao-ltda-theme' ) ),
			array( 'package-carousel/04-collectors-slipcase.jpg', __( 'Caixa e luva ilustrada da edição de colecionador', 'versao-ltda-theme' ) ),
			array( 'package-carousel/05-complete-contents.jpg', __( 'Conteúdo completo da edição física de Demons of Asteborg', 'versao-ltda-theme' ) ),
			array( 'package-carousel/06-open-edition-contents.jpg', __( 'Embalagem aberta com manual, impressos e cartucho', 'versao-ltda-theme' ) ),
			array( 'package-carousel/07-box-and-cartridge.jpg', __( 'Caixa e cartucho da edição física de Demons of Asteborg', 'versao-ltda-theme' ) ),
			array( 'package-carousel/08-illustrated-card.jpg', __( 'Card ilustrado de Demons of Asteborg', 'versao-ltda-theme' ) ),
			array( 'package-carousel/09-cartridge-board.jpg', __( 'Placas internas do cartucho com tecnologia Mega Drive', 'versao-ltda-theme' ) ),
			array( 'package-carousel/10-cartridge.jpg', __( 'Cartucho de Demons of Asteborg para Mega Drive', 'versao-ltda-theme' ) ),
			array( 'package-carousel/11-cartridge-interior.jpg', __( 'Cartucho aberto exibindo a placa interna', 'versao-ltda-theme' ) ),
		);
	} else {
		$image_url = has_post_thumbnail( $product_id )
			? get_the_post_thumbnail_url( $product_id, 'full' )
			: vltda_asset( 'images/product-photo.jpg' );
		$gallery   = array( array( $image_url, $product_name, true ) );
	}

	$main_image = $gallery[0];
	$main_src   = ! empty( $main_image[2] ) ? $main_image[0] : vltda_asset( 'images/' . $main_image[0] );
	$cta_label  = $is_waiting_preorder ? __( 'Pré-Venda em 08.08.26', 'versao-ltda-theme' ) : $action['label'];
	$current_locale = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();
	$trailer_src    = 'en_US' === $current_locale ? 'videos/doa-trailer-en.mp4' : 'videos/doa-trailer-pt.mp4';
	$trailer        = array(
		'src'    => vltda_asset( $is_demo ? $trailer_src : 'videos/doa-trailer.mp4' ),
		'poster' => vltda_asset( $is_demo ? 'images/thumb-trailer-jogo.jpg' : 'images/gameplay-1.jpg' ),
	);
	$product_permalink = get_permalink( $product_id );
	$show_related_products = (bool) apply_filters( 'versao_ltda_show_product_related', false );
	$related               = $show_related_products ? versao_ltda_get_catalog_products( array( 'limit' => 3 ) ) : array();
	?>
	<main id="main" class="site-main single-product-page">
		<section class="product-showcase section">
			<div class="container container--narrow">
				<nav class="breadcrumb product-showcase__breadcrumb" aria-label="<?php esc_attr_e( 'Caminho de navegação', 'versao-ltda-theme' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'versao-ltda-theme' ); ?></a>
					<span>/</span>
					<a href="<?php echo esc_url( home_url( '/jogos/' ) ); ?>"><?php esc_html_e( 'Jogos', 'versao-ltda-theme' ); ?></a>
					<span>/</span>
					<span><?php echo esc_html( $product_name ); ?></span>
				</nav>

				<div class="product-showcase__grid">
					<div class="product-gallery" data-product-gallery>
						<button class="product-gallery__stage" type="button" data-product-gallery-stage aria-label="<?php esc_attr_e( 'Ampliar imagem do produto', 'versao-ltda-theme' ); ?>">
							<img data-product-gallery-main src="<?php echo esc_url( $main_src ); ?>" alt="<?php echo esc_attr( $main_image[1] ); ?>">
						</button>

						<?php if ( 1 < count( $gallery ) ) : ?>
							<div class="product-gallery__navigation">
								<button class="product-gallery__arrow product-gallery__arrow--prev" type="button" data-gallery-prev aria-label="<?php esc_attr_e( 'Imagem anterior', 'versao-ltda-theme' ); ?>"></button>
								<div class="product-gallery__thumbs" aria-label="<?php esc_attr_e( 'Imagens do produto', 'versao-ltda-theme' ); ?>">
									<?php foreach ( $gallery as $index => $image ) : ?>
										<?php $image_src = ! empty( $image[2] ) ? $image[0] : vltda_asset( 'images/' . $image[0] ); ?>
										<button type="button" class="product-gallery__thumb<?php echo 0 === $index ? ' is-active' : ''; ?>" data-gallery-src="<?php echo esc_url( $image_src ); ?>" data-gallery-alt="<?php echo esc_attr( $image[1] ); ?>" aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>">
											<img src="<?php echo esc_url( $image_src ); ?>" alt="">
											<span class="screen-reader-text"><?php echo esc_html( $image[1] ); ?></span>
										</button>
									<?php endforeach; ?>
								</div>
								<button class="product-gallery__arrow product-gallery__arrow--next" type="button" data-gallery-next aria-label="<?php esc_attr_e( 'Próxima imagem', 'versao-ltda-theme' ); ?>"></button>
							</div>
						<?php endif; ?>

						<dialog class="product-gallery-modal" data-gallery-dialog aria-label="<?php esc_attr_e( 'Visualização ampliada do produto', 'versao-ltda-theme' ); ?>" aria-modal="true">
							<div class="product-gallery-modal__content">
								<button class="product-gallery-modal__close" type="button" data-gallery-dialog-close aria-label="<?php esc_attr_e( 'Fechar imagem ampliada', 'versao-ltda-theme' ); ?>">
									<span aria-hidden="true">&times;</span>
								</button>
								<?php if ( 1 < count( $gallery ) ) : ?>
									<button class="product-gallery-modal__arrow product-gallery-modal__arrow--prev" type="button" data-gallery-dialog-prev aria-label="<?php esc_attr_e( 'Imagem anterior', 'versao-ltda-theme' ); ?>"></button>
								<?php endif; ?>
								<figure class="product-gallery-modal__figure">
									<img data-gallery-dialog-image src="<?php echo esc_url( $main_src ); ?>" alt="<?php echo esc_attr( $main_image[1] ); ?>">
									<figcaption class="product-gallery-modal__counter" data-gallery-dialog-counter aria-live="polite">
										<?php
										printf(
											/* translators: 1: current image number, 2: total number of images. */
											esc_html__( '%1$d de %2$d', 'versao-ltda-theme' ),
											1,
											count( $gallery )
										);
										?>
									</figcaption>
								</figure>
								<?php if ( 1 < count( $gallery ) ) : ?>
									<button class="product-gallery-modal__arrow product-gallery-modal__arrow--next" type="button" data-gallery-dialog-next aria-label="<?php esc_attr_e( 'Próxima imagem', 'versao-ltda-theme' ); ?>"></button>
								<?php endif; ?>
							</div>
						</dialog>
					</div>

					<section class="product-summary" aria-labelledby="product-title">
						<p class="product-summary__eyebrow">#<?php echo esc_html( $sku ); ?> - <?php esc_html_e( 'Edição Exclusiva de Pré-Venda', 'versao-ltda-theme' ); ?></p>
						<h1 id="product-title"><?php echo esc_html( $product_name ); ?></h1>
						<p class="product-summary__platform"><?php echo esc_html( $platform ); ?></p>

						<a class="product-summary__wishlist<?php echo $is_saved ? ' is-active' : ''; ?>" href="<?php echo esc_url( $wishlist_url ); ?>">
							<span aria-hidden="true"></span>
							<?php echo $is_saved ? esc_html__( 'Remover dos favoritos', 'versao-ltda-theme' ) : esc_html__( 'Adicionar aos favoritos', 'versao-ltda-theme' ); ?>
						</a>

						<p class="product-summary__badge"><?php esc_html_e( 'Cartucho cromado exclusivo da pré-venda', 'versao-ltda-theme' ); ?></p>
						<p class="product-summary__price"><?php echo wp_kses_post( $product->get_price_html() ?: wc_price( 399 ) ); ?></p>

						<?php if ( $is_waiting_preorder ) : ?>
							<button class="button product-summary__cta is-soon" type="button">
								<?php echo esc_html( $cta_label ); ?>
							</button>
						<?php else : ?>
							<a class="button product-summary__cta <?php echo esc_attr( trim( $action['class'] ) ); ?>" href="<?php echo esc_url( $action['href'] ); ?>"<?php echo $action['attributes']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<?php echo esc_html( $cta_label ); ?>
							</a>
						<?php endif; ?>
						<p class="product-summary__note">
							<?php
							$is_waiting_preorder
								? esc_html_e( 'A compra será liberada em 08.08.26.', 'versao-ltda-theme' )
								: esc_html_e( 'Pré-venda limitada. Envio estimado em 30 dias.', 'versao-ltda-theme' );
							?>
						</p>

						<?php if ( $is_waiting_preorder ) : ?>
							<div class="product-summary__notify" id="product-notify">
								<p class="product-summary__notify-eyebrow"><?php esc_html_e( 'Avise-me', 'versao-ltda-theme' ); ?></p>
								<form class="product-summary__notify-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
									<?php wp_nonce_field( 'versao_ltda_product_notify', 'versao_ltda_product_notify_nonce' ); ?>
									<input type="hidden" name="action" value="versao_ltda_product_notify">
									<input type="hidden" name="product_id" value="<?php echo esc_attr( (string) $product_id ); ?>">
									<input type="hidden" name="product_name" value="<?php echo esc_attr( $product_name ); ?>">
									<input type="hidden" name="product_url" value="<?php echo esc_url( $product_permalink ); ?>">
									<div class="product-summary__notify-row">
										<input type="email" name="notify_email" placeholder="<?php esc_attr_e( 'Seu e-mail', 'versao-ltda-theme' ); ?>" required>
										<button type="submit"><?php esc_html_e( 'Avise-me', 'versao-ltda-theme' ); ?></button>
									</div>
								</form>

								<?php if ( 'success' === $notify_status ) : ?>
									<p class="product-summary__notify-message is-success" role="status"><?php esc_html_e( 'Pronto. Vamos avisar quando houver novidades.', 'versao-ltda-theme' ); ?></p>
								<?php elseif ( 'validation-error' === $notify_status ) : ?>
									<p class="product-summary__notify-message is-error" role="status"><?php esc_html_e( 'Informe um e-mail válido para receber o aviso.', 'versao-ltda-theme' ); ?></p>
								<?php elseif ( 'send-error' === $notify_status || 'security-error' === $notify_status ) : ?>
									<p class="product-summary__notify-message is-error" role="status"><?php esc_html_e( 'Não foi possível enviar o aviso agora. Tente novamente.', 'versao-ltda-theme' ); ?></p>
								<?php endif; ?>

							</div>
						<?php endif; ?>

						<ul class="product-summary__features">
							<li><?php esc_html_e( 'Edição exclusiva de pré-venda com cartucho cromado', 'versao-ltda-theme' ); ?></li>
							<li><?php esc_html_e( 'Cartucho para Mega Drive / Genesis', 'versao-ltda-theme' ); ?></li>
							<li><?php esc_html_e( 'Caixa premium e luva rígida', 'versao-ltda-theme' ); ?></li>
							<li><?php esc_html_e( 'Manual, pôster e card numerado', 'versao-ltda-theme' ); ?></li>
							<li><?php esc_html_e( 'Produzido com tecnologia Mark1', 'versao-ltda-theme' ); ?></li>
							<li><?php esc_html_e( 'Jogo em Português, Inglês, Espanhol, Francês, Alemão e  Italiano', 'versao-ltda-theme' ); ?></li>
							<li><?php esc_html_e( 'Manual em Português', 'versao-ltda-theme' ); ?></li>
							<li><?php esc_html_e( 'Save game nativo', 'versao-ltda-theme' ); ?></li>
						</ul>

						<img class="product-summary__mark" src="<?php echo esc_url( vltda_asset( 'images/mark1.svg' ) ); ?>" alt="Mark1">
					</section>
				</div>
			</div>
		</section>

		<section class="product-story section">
			<div class="container container--narrow product-story__grid">
				<div class="product-story__media">
					<p><?php esc_html_e( 'Vídeo / Trailer:', 'versao-ltda-theme' ); ?></p>
					<?php if ( $is_demo ) : ?>
						<div class="product-trailer">
							<div class="product-story__frame product-trailer__frame">
								<video
									controls
									playsinline
									preload="metadata"
									poster="<?php echo esc_url( $trailer['poster'] ); ?>"
									aria-label="<?php esc_attr_e( 'Trailer oficial de Demons of Asteborg', 'versao-ltda-theme' ); ?>"
								>
									<source src="<?php echo esc_url( $trailer['src'] ); ?>" type="video/mp4">
									<?php esc_html_e( 'Seu navegador não oferece suporte à reprodução deste vídeo.', 'versao-ltda-theme' ); ?>
								</video>
							</div>
						</div>
					<?php else : ?>
						<div class="product-story__frame">
							<video
								controls
								playsinline
								preload="metadata"
								poster="<?php echo esc_url( vltda_asset( 'images/gameplay-1.jpg' ) ); ?>"
								aria-label="<?php esc_attr_e( 'Trailer oficial de Demons of Asteborg', 'versao-ltda-theme' ); ?>"
							>
								<source src="<?php echo esc_url( vltda_asset( 'videos/doa-trailer.mp4' ) ); ?>" type="video/mp4">
								<?php esc_html_e( 'Seu navegador não oferece suporte à reprodução deste vídeo.', 'versao-ltda-theme' ); ?>
							</video>
						</div>
					<?php endif; ?>
				</div>

				<div class="product-story__copy">
					<p class="product-story__eyebrow"><?php esc_html_e( 'Sobre o jogo', 'versao-ltda-theme' ); ?></p>
					<h2>
						<?php if ( $is_demo ) : ?>
							<span><?php esc_html_e( 'Aventura, ação e', 'versao-ltda-theme' ); ?></span>
							<span><?php esc_html_e( 'magia em 16-bits.', 'versao-ltda-theme' ); ?></span>
						<?php else : ?>
							<?php echo esc_html( $story_title ); ?>
						<?php endif; ?>
					</h2>
					<p><?php echo esc_html( $short_copy ); ?></p>
					<p><?php echo esc_html( $full_copy ); ?></p>
				</div>
			</div>
		</section>

		<section class="product-information section">
			<div class="container container--narrow">
				<p class="product-information__eyebrow"><?php esc_html_e( 'Informações importantes', 'versao-ltda-theme' ); ?></p>
				<div class="product-information__grid">
					<?php
					$faqs = array(
						__( 'Este cartucho funciona em quais consoles?', 'versao-ltda-theme' ) => __( 'O cartucho é compatível com consoles Mega Drive e Genesis originais que aceitem o padrão da edição.', 'versao-ltda-theme' ),
						__( 'Quando o produto será enviado?', 'versao-ltda-theme' ) => __( 'O envio está previsto para até 30 dias após a confirmação da produção da pré-venda.', 'versao-ltda-theme' ),
						__( 'Esta é uma pré-venda?', 'versao-ltda-theme' ) => __( 'Sim. A reserva garante uma unidade da tiragem especial antes do lançamento.', 'versao-ltda-theme' ),
						__( 'As imagens são finais?', 'versao-ltda-theme' ) => __( 'As imagens representam o projeto da edição e podem receber pequenos ajustes de produção.', 'versao-ltda-theme' ),
						__( 'O cartucho cromado será vendido depois?', 'versao-ltda-theme' ) => __( 'O acabamento cromado é exclusivo desta campanha de pré-venda, enquanto durarem as unidades.', 'versao-ltda-theme' ),
						__( 'Ainda tem dúvidas?', 'versao-ltda-theme' ) => __( 'Entre em contato com nossa equipe pela página de contato para receber ajuda.', 'versao-ltda-theme' ),
					);
					foreach ( $faqs as $question => $answer ) :
						?>
						<details class="product-information__item">
							<summary><?php echo esc_html( $question ); ?></summary>
							<p><?php echo esc_html( $answer ); ?></p>
						</details>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php if ( $show_related_products ) : ?>
			<section class="product-related section">
				<div class="container container--narrow">
					<h2><?php esc_html_e( 'Outros lançamentos', 'versao-ltda-theme' ); ?></h2>
					<div class="product-grid product-related__grid">
						<?php foreach ( $related as $index => $related_product ) : ?>
							<?php versao_ltda_render_product_card( $related_product, $index + 1 ); ?>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>
	</main>
	<?php
	do_action( 'woocommerce_after_single_product' );
endwhile;

get_footer();
