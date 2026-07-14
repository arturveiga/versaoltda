<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

$logo_src = vltda_asset('images/maintenance/Logo_VersaoLTDA.png');
$bg_src = vltda_asset('images/maintenance/bg-maintenance.webp');


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
            <div class="vltda-maintenance__coming">Está chegando</div>
            <img class="vltda-maintenance__logo" src="<?php echo esc_url($logo_src); ?>" alt="" />
            <div class="vltda-maintenance__subtitle">
                Inspirada na era de ouro dos videogames, criamos edições físicas
                para serem vividas, colecionadas e lembradas.<br>
                <strong>Porque alguns jogos merecem mais.</strong>
            </div>
            <div class="coming-soon" aria-live="polite">
                <div class="loader-dots" aria-hidden="true">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </main>


    <?php wp_footer(); ?>
</body>

</html>