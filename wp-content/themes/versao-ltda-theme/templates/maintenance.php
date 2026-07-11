<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

$settings = vltda_maintenance_get_settings();

$logo_url = vltda_maintenance_resolve_image_url(
	(int) ($settings['logo_attachment'] ?? 0),
	'logo'
);

$bg_url = vltda_maintenance_resolve_image_url(
	(int) ($settings['background_attachment'] ?? 0),
	'background'
);

$title = (string) ($settings['title'] ?? '');
$text = (string) ($settings['text'] ?? '');
$show_animation = (bool) ($settings['show_animation'] ?? true);
$show_counter = (bool) ($settings['show_counter'] ?? true);
$launch_date = (string) ($settings['launch_date'] ?? '');
$button_text = (string) ($settings['button_text'] ?? '');
$button_url = (string) ($settings['button_url'] ?? '');

?>
<!doctype html>
<html <?php echo esc_html(language_attributes()); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head(); ?>

		<link rel="preload" as="image" href="<?php echo esc_url($bg_url); ?>">
	</head>

	<body <?php body_class('vltda-maintenance'); ?> data-bg="<?php echo esc_attr($bg_url); ?>">
		<?php wp_body_open(); ?>

		<div class="vltda-maintenance" role="main">
			<div class="vltda-maintenance__bg" aria-hidden="true"></div>
			<div class="vltda-maintenance__inner">
				<div class="vltda-maintenance__brand">
					<img class="vltda-maintenance__logo" src="<?php echo esc_url($logo_url); ?>" alt="" />
				</div>

				<?php if ($title !== '') : ?>
					<h1 class="vltda-maintenance__title"><?php echo esc_html($title); ?></h1>
				<?php endif; ?>

				<?php if ($text !== '') : ?>
					<div class="vltda-maintenance__text"><?php echo wp_kses_post($text); ?></div>
				<?php endif; ?>

				<?php if ($show_animation) : ?>
					<div class="vltda-maintenance__animation" aria-hidden="true">
						<div class="vltda-maintenance__spinner"></div>
					</div>
				<?php endif; ?>

				<?php if ($show_counter && $launch_date !== '') : ?>
					<div class="vltda-maintenance__counter" data-launch-date="<?php echo esc_attr($launch_date); ?>" aria-label="Contador regressivo"></div>
				<?php endif; ?>

				<?php if ($button_text !== '' && $button_url !== '') : ?>
					<div class="vltda-maintenance__cta">
						<a class="vltda-maintenance__button" href="<?php echo esc_url($button_url); ?>"><?php echo esc_html($button_text); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<?php wp_footer(); ?>
	</body>
</html>

