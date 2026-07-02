<?php
/**
 * Single post template.
 *
 * @package Versao_Ltda_Theme
 */

get_header();
?>

<main id="main" class="site-main section">
	<div class="container container--narrow content-area">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
				<h1 class="entry__title"><?php the_title(); ?></h1>
				<div class="entry__content"><?php the_content(); ?></div>
			</article>
			<?php the_post_navigation(); ?>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
