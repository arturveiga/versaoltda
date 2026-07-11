<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

$logo_src = vltda_asset('images/logo_versao_ltda_svg.svg');
$bg_src = vltda_asset('images/maintenance/bg.jpg');

?>
<!doctype html>
<html <?php echo esc_html(language_attributes()); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head(); ?>

		<link rel="preload" as="image" href="<?php echo esc_url($bg_src); ?>">
	</head>

	<body <?php body_class('vltda-maintenance'); ?> data-bg="<?php echo esc_attr($bg_src); ?>">
		<?php wp_body_open(); ?>

		<main class="vltda-maintenance" role="main">
			<div class="vltda-maintenance__bg" aria-hidden="true"></div>
			<div class="vltda-maintenance__center">
				<img class="vltda-maintenance__logo" src="<?php echo esc_url($logo_src); ?>" alt="" />
				<div class="vltda-maintenance__subtitle">Porque alguns jogos merecem mais</div>
			</div>
		</main>

		<?php wp_footer(); ?>
	</body>
</html>


