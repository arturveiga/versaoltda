<?php
/**
 * Front page template.
 *
 * @package Versao_Ltda_Theme
 */

get_header();
?>

<main id="main" class="site-main">
	<section class="hero">
		<div class="hero__media" aria-hidden="true"></div>
		<div class="hero__content container">
			<div class="hero__copy">
				<p class="eyebrow"><?php esc_html_e( 'Lancamento #001', 'versao-ltda-theme' ); ?></p>
				<h1><?php esc_html_e( 'Demons of Asteborg', 'versao-ltda-theme' ); ?></h1>
				<p><?php esc_html_e( 'O primeiro lancamento da Versao LTDA. Uma edicao fisica para Mega Drive, concebida com cuidado de quem coleciona e joga de quem projeta.', 'versao-ltda-theme' ); ?></p>
				<a class="button button--primary" href="#comprar"><?php esc_html_e( 'Reservar agora', 'versao-ltda-theme' ); ?></a>
			</div>
		</div>
	</section>

	<section class="intro section">
		<div class="container container--narrow">
			<div class="media-strip">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/gameplay-1.jpg' ); ?>" alt="<?php esc_attr_e( 'Cena de gameplay em castelo escuro', 'versao-ltda-theme' ); ?>">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/gameplay-2.jpg' ); ?>" alt="<?php esc_attr_e( 'Cena de gameplay em floresta', 'versao-ltda-theme' ); ?>">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/gameplay-3.jpg' ); ?>" alt="<?php esc_attr_e( 'Cena de gameplay em vila ao por do sol', 'versao-ltda-theme' ); ?>">
			</div>

			<div class="intro__grid">
				<h2><?php esc_html_e( 'Prepare-se, voce e a ultima esperanca, e falhar nao e uma opcao!', 'versao-ltda-theme' ); ?></h2>
				<div>
					<p><?php esc_html_e( 'Assuma o papel de Gareth, um cavaleiro treinado para proteger seu povo, e enfrente Zadimus em uma jornada epica para Mega Drive.', 'versao-ltda-theme' ); ?></p>
					<p><?php esc_html_e( 'Explore florestas, montanhas, pantanos e cenarios impressionantes em um dos maiores jogos ja criados para 16-bit, com jogabilidade fluida, magia e novas habilidades.', 'versao-ltda-theme' ); ?></p>
				</div>
			</div>

			<div class="quote-row" aria-label="<?php esc_attr_e( 'Avaliacoes', 'versao-ltda-theme' ); ?>">
				<blockquote>
					<p>"Hours of gameplay on offer"</p>
					<cite>Time Extension</cite>
				</blockquote>
				<blockquote>
					<p>"Demons of Asteborg is a must-play"</p>
					<cite>Segbits</cite>
				</blockquote>
				<blockquote>
					<p>"I love Demons of Asteborg"</p>
					<cite>Old Games DK</cite>
				</blockquote>
			</div>
		</div>
	</section>

	<section class="details section">
		<div class="container container--narrow">
			<p class="section-kicker"><?php esc_html_e( 'O que tem na embalagem?', 'versao-ltda-theme' ); ?></p>
			<h2><?php esc_html_e( 'Cada detalhe foi pensado.', 'versao-ltda-theme' ); ?></h2>
			<p><?php esc_html_e( 'Cada edicao nasce como uma experiencia completa de colecao, do cartucho a embalagem.', 'versao-ltda-theme' ); ?></p>

			<img class="details__image" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/package-open.jpg' ); ?>" alt="<?php esc_attr_e( 'Embalagem premium aberta do jogo Demons of Asteborg', 'versao-ltda-theme' ); ?>">

			<p class="caption"><?php esc_html_e( 'Conteudo completo da Edicao #001 - Edicao Especial de Pre-Order', 'versao-ltda-theme' ); ?></p>

			<div class="feature-grid">
				<article><h3><?php esc_html_e( 'Cartucho para Mega Drive / Genesis', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Versao original durante a Pre-Order em shell compacto em preto.', 'versao-ltda-theme' ); ?></p></article>
				<article><h3><?php esc_html_e( 'Embalagem Premium', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Acabamento rigido, impressao especial e protecao para colecao.', 'versao-ltda-theme' ); ?></p></article>
				<article><h3><?php esc_html_e( 'Conteudo Colecionavel', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Manual, poster e card, mantendo o padrao classico da plataforma.', 'versao-ltda-theme' ); ?></p></article>
				<article><h3><?php esc_html_e( 'Edicao Completa', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Cartucho, caixa premium, manual, poster e card numerado.', 'versao-ltda-theme' ); ?></p></article>
				<article><h3><?php esc_html_e( 'Experiencia de Unboxing', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'Cada elemento foi pensado para valorizar o jogo fisico.', 'versao-ltda-theme' ); ?></p></article>
				<article><h3><?php esc_html_e( 'Powered by Mark1', 'versao-ltda-theme' ); ?></h3><p><?php esc_html_e( 'PCB Mark1, uma arquitetura proprietaria desenvolvida para compatibilidade e preservacao.', 'versao-ltda-theme' ); ?></p></article>
			</div>
		</div>
	</section>

	<section class="physical section" id="comprar">
		<div class="container physical__grid">
			<div class="physical__copy">
				<h2><?php esc_html_e( 'Alguns jogos merecem mais do que um download.', 'versao-ltda-theme' ); ?></h2>
				<p><?php esc_html_e( 'Edicoes fisicas para jogar, guardar e colecionar.', 'versao-ltda-theme' ); ?></p>
				<a class="button button--primary" href="#produtos"><?php esc_html_e( 'Reservar agora', 'versao-ltda-theme' ); ?></a>
				<p class="product-code">#001 / Demons of Asteborg / Mega Drive<br><?php esc_html_e( 'White matte shell | Regular version', 'versao-ltda-theme' ); ?></p>
			</div>
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/cartridge.jpg' ); ?>" alt="<?php esc_attr_e( 'Cartucho fisico Demons of Asteborg', 'versao-ltda-theme' ); ?>">
		</div>
	</section>

	<section class="products section" id="produtos">
		<div class="container container--narrow product-grid">
			<?php
			$products = array(
				array( 'Demons of Asteborg', 'Mega Drive', 'Pre-Order Version', 'R$ 399,00', 'Reservar agora', 'is-available' ),
				array( 'Earthion', 'Mega Drive', 'Pre-Order Version', 'R$ 399,00', 'Em Breve', 'is-soon' ),
				array( 'Daemon Claw', 'Mega Drive', 'Pre-Order Version', 'R$ 399,00', 'Esgotado', 'is-sold' ),
			);
			foreach ( $products as $product ) :
				?>
				<article class="product-card">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-box.jpg' ); ?>" alt="<?php echo esc_attr( $product[0] ); ?>">
					<p class="product-card__label">NOVO</p>
					<h3><?php echo esc_html( $product[0] ); ?></h3>
					<p><?php echo esc_html( $product[1] ); ?><br><?php echo esc_html( $product[2] ); ?></p>
					<strong><?php echo esc_html( $product[3] ); ?></strong>
					<a class="button product-card__button <?php echo esc_attr( $product[5] ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $product[4] ); ?></a>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
</main>

<?php
get_footer();
