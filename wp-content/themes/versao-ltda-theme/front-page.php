<?php
/**
 * Front page template.
 *
 * @package Versao_Ltda_Theme
 */

get_header();
?>

<main id="main" class="site-main">
	<?php get_template_part( 'template-parts/home/hero' ); ?>
	<?php get_template_part( 'template-parts/home/story' ); ?>
	<?php get_template_part( 'template-parts/home/features' ); ?>
	<?php get_template_part( 'template-parts/home/package' ); ?>
	<?php get_template_part( 'template-parts/home/gameplay' ); ?>
	<?php get_template_part( 'template-parts/home/newsletter' ); ?>
</main>

<?php
get_footer();
