<?php
/**
 * About page template.
 *
 * @package Versao_Ltda_Theme
 */

get_header();
?>

<main id="main" class="site-main about-page">
	<section class="about-hero section">
		<div class="container container--narrow">
			<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Caminho de navegação', 'versao-ltda-theme' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'versao-ltda-theme' ); ?></a>
				<span>/</span>
				<span><?php esc_html_e( 'Sobre', 'versao-ltda-theme' ); ?></span>
			</nav>

			<h1>
				<span class="about-hero__title-line"><?php esc_html_e( 'O digital é prático.', 'versao-ltda-theme' ); ?></span>
				<span class="about-hero__title-line"><?php esc_html_e( 'O físico é', 'versao-ltda-theme' ); ?></span>
				<span class="about-hero__title-line about-hero__title-line--accent"><?php esc_html_e( 'memorável.', 'versao-ltda-theme' ); ?></span>
			</h1>

			<div class="about-hero__copy">
				<div>
					<p><?php esc_html_e( 'Em uma época em que o digital se tornou o padrão, acreditamos que certas experiências merecem ocupar um espaço além da tela. Merecem ser preservadas, compartilhadas, revisitadas e mantidas vivas ao longo do tempo.', 'versao-ltda-theme' ); ?></p>
					<p><?php esc_html_e( 'Inspirada pela era de ouro dos videogames, a VERSÃO LTDA publica edições físicas desenvolvidas para celebrar jogos que deixam uma marca — sejam eles clássicos que atravessaram gerações ou novas obras destinadas a conquistar seu lugar entre eles.', 'versao-ltda-theme' ); ?></p>
				</div>

				<div>
					<p><?php esc_html_e( 'Cada lançamento é tratado como um projeto único. Dos componentes à embalagem, dos detalhes gráficos à experiência de unboxing, cada elemento é pensado para fortalecer a conexão entre jogador, obra e coleção.', 'versao-ltda-theme' ); ?></p>
					<div class="about-hero__statement">
						<p><?php esc_html_e( 'Esta é a VERSÃO LTDA.', 'versao-ltda-theme' ); ?></p>
						<p><?php esc_html_e( 'Porque alguns jogos merecem mais do que um download.', 'versao-ltda-theme' ); ?></p>
					</div>
				</div>
			</div>

			<div class="about-video">
				<iframe
					src="https://www.youtube.com/embed/BoebDxvaWoo"
					title="<?php esc_attr_e( 'Vídeo institucional da Versão LTDA', 'versao-ltda-theme' ); ?>"
					allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
					allowfullscreen></iframe>
			</div>

			<div class="about-powered">
				<div class="about-powered__logo">
					<span><?php esc_html_e( 'Powered by', 'versao-ltda-theme' ); ?></span>
					<img src="<?php echo esc_url( vltda_asset( 'images/mark1.svg' ) ); ?>" alt="<?php esc_attr_e( 'Mark1 cartridge technology', 'versao-ltda-theme' ); ?>">
				</div>
				<p><?php esc_html_e( 'As edições para Mega Drive da VERSÃO LTDA são produzidas utilizando a plataforma Mark1, uma arquitetura proprietária desenvolvida para oferecer compatibilidade, confiabilidade e preservação a longo prazo.', 'versao-ltda-theme' ); ?></p>
			</div>
		</div>
	</section>

	<section class="about-principles section">
		<div class="container container--narrow">
			<p class="section-kicker">VL / <?php esc_html_e( 'Princípios', 'versao-ltda-theme' ); ?></p>
			<h2><?php esc_html_e( 'No que acreditamos.', 'versao-ltda-theme' ); ?></h2>

			<div class="about-principles__grid">
				<article>
					<span>01</span>
					<h3><?php esc_html_e( 'Cuidado', 'versao-ltda-theme' ); ?></h3>
					<p><?php esc_html_e( 'Cada componente é pensado e produzido com atenção ao detalhe. Para nós, a embalagem de um jogo não é apenas um invólucro — é parte da experiência.', 'versao-ltda-theme' ); ?></p>
				</article>

				<article>
					<span>02</span>
					<h3><?php esc_html_e( 'Curadoria', 'versao-ltda-theme' ); ?></h3>
					<p><?php esc_html_e( 'Não produzimos qualquer jogo. Cada título é escolhido pela sua qualidade, identidade e relevância. Prezamos pela curadoria, não pelo volume — porque uma edição física só faz sentido quando o jogo merece esse espaço.', 'versao-ltda-theme' ); ?></p>
				</article>

				<article>
					<span>03</span>
					<h3><?php esc_html_e( 'Respeito à obra', 'versao-ltda-theme' ); ?></h3>
					<p><?php esc_html_e( 'Cada projeto é desenvolvido em colaboração com seus criadores. Nosso papel é preservar a essência do jogo e dar a ele uma forma física à altura da sua importância.', 'versao-ltda-theme' ); ?></p>
				</article>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
