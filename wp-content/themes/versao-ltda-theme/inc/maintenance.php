<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Maintenance Mode (Coming Soon) - Versao LTDA Theme.
 *
 * Encapsulated logic only (no HTML).
 *
 * @package Versao_Ltda_Theme
 */

// Option keys.
if (!defined('VLTDTA_MAINTENANCE_OPTION_KEY')) {
	define('VLTDTA_MAINTENANCE_OPTION_KEY', 'vltda_maintenance_settings');
}

/**
 * Get maintenance settings.
 *
 * @return array<string, mixed>
 */
function vltda_maintenance_get_settings(): array
{
	$defaults = array(
		'enabled'         => false,
		'logo_attachment' => 0,
		'background_attachment' => 0,
		'title'           => 'Em breve',
		'text'            => 'Estamos preparando algo especial. Volte em breve.',
		'show_animation'  => true,
		'show_counter'    => true,
		'launch_date'     => '', // Expect Y-m-d H:i:s in site timezone, but stored as text.
		'button_text'     => 'Saiba mais',
		'button_url'      => '',
	);

	$saved = get_option(VLTDTA_MAINTENANCE_OPTION_KEY, array());
	if (!is_array($saved)) {
		$saved = array();
	}

	$settings = array_merge($defaults, $saved);
	$settings['enabled'] = (bool) ($settings['enabled'] ?? false);

	return $settings;
}

/**
 * Determine whether the maintenance mode should be applied for current request.
 *
 * @return bool
 */
function vltda_maintenance_is_active_for_request(): bool
{
	$settings = vltda_maintenance_get_settings();

	if (empty($settings['enabled'])) {
		return false;
	}

	// Never block WP admin (including wp-admin AJAX).
	if (is_admin()) {
		return false;
	}

	// Bypass for administrators and other users with manage_options.
	// Only applies when the user is logged in.
	if (is_user_logged_in() && current_user_can('manage_options')) {
		return false;
	}

	// Bypass for REST / AJAX endpoints.

	if (defined('DOING_AJAX') && DOING_AJAX) {
		return false;
	}
	if (defined('REST_REQUEST') && REST_REQUEST) {
		return false;
	}

	return true;
}

/**
 * Get default image URLs.
 *
 * @param string $type 'logo'|'background'.
 * @return string
 */
function vltda_maintenance_get_default_image_url(string $type): string
{
	$base = get_template_directory_uri() . '/assets/images/maintenance/';

	switch ($type) {
		case 'logo':
			return $base . 'default-logo.png';
		case 'background':
			return $base . 'default-background.jpg';
		default:
			return $base;
	}
}

/**
 * Resolve attachment URL or fallback to default.
 *
 * @param int $attachment_id
 * @param string $fallback_type
 * @return string
 */
function vltda_maintenance_resolve_image_url(int $attachment_id, string $fallback_type): string
{
	$attachment_id = absint($attachment_id);
	if ($attachment_id > 0) {
		$url = wp_get_attachment_image_url($attachment_id, 'full');
		if (is_string($url) && $url !== '') {
			return $url;
		}
	}

	return vltda_maintenance_get_default_image_url($fallback_type);
}

/**
 * Register Settings API page fields.
 *
 * @return void
 */
function vltda_register_maintenance_settings(): void
{
	register_setting(
		'vltda_maintenance_group',
		VLTDTA_MAINTENANCE_OPTION_KEY,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'vltda_maintenance_sanitize_settings',
			'show_in_rest'     => false,
		)
	);

	add_settings_section(
		'vltda_maintenance_main',
		'',
		'__return_null',
		'vltda_maintenance_page'
	);

	add_settings_field(
		'vltda_maintenance_enabled',
		sprintf('%s', esc_html__('Ativar modo manutenção', 'versao-ltda-theme')),
		'vltda_maintenance_field_enabled_render',
		'vltda_maintenance_page',
		'vltda_maintenance_main'
	);

	add_settings_field(
		'vltda_maintenance_logo',
		sprintf('%s', esc_html__('Logo (upload)', 'versao-ltda-theme')),
		'vltda_maintenance_field_logo_render',
		'vltda_maintenance_page',
		'vltda_maintenance_main'
	);

	add_settings_field(
		'vltda_maintenance_background',
		sprintf('%s', esc_html__('Imagem de fundo (upload)', 'versao-ltda-theme')),
		'vltda_maintenance_field_background_render',
		'vltda_maintenance_page',
		'vltda_maintenance_main'
	);

	add_settings_field(
		'vltda_maintenance_title',
		sprintf('%s', esc_html__('Título', 'versao-ltda-theme')),
		'vltda_maintenance_field_title_render',
		'vltda_maintenance_page',
		'vltda_maintenance_main'
	);

	add_settings_field(
		'vltda_maintenance_text',
		sprintf('%s', esc_html__('Texto', 'versao-ltda-theme')),
		'vltda_maintenance_field_text_render',
		'vltda_maintenance_page',
		'vltda_maintenance_main'
	);

	add_settings_field(
		'vltda_maintenance_show_animation',
		sprintf('%s', esc_html__('Mostrar animação', 'versao-ltda-theme')),
		'vltda_maintenance_field_show_animation_render',
		'vltda_maintenance_page',
		'vltda_maintenance_main'
	);

	add_settings_field(
		'vltda_maintenance_show_counter',
		sprintf('%s', esc_html__('Mostrar contador', 'versao-ltda-theme')),
		'vltda_maintenance_field_show_counter_render',
		'vltda_maintenance_page',
		'vltda_maintenance_main'
	);

	add_settings_field(
		'vltda_maintenance_launch_date',
		sprintf('%s', esc_html__('Data de lançamento', 'versao-ltda-theme')),
		'vltda_maintenance_field_launch_date_render',
		'vltda_maintenance_page',
		'vltda_maintenance_main'
	);

	add_settings_field(
		'vltda_maintenance_button_text',
		sprintf('%s', esc_html__('Texto do botão', 'versao-ltda-theme')),
		'vltda_maintenance_field_button_text_render',
		'vltda_maintenance_page',
		'vltda_maintenance_main'
	);

	add_settings_field(
		'vltda_maintenance_button_url',
		sprintf('%s', esc_html__('URL do botão', 'versao-ltda-theme')),
		'vltda_maintenance_field_button_url_render',
		'vltda_maintenance_page',
		'vltda_maintenance_main'
	);
}

/**
 * Sanitize maintenance settings.
 *
 * @param array<string, mixed> $input
 * @return array<string, mixed>
 */
function vltda_maintenance_sanitize_settings(array $input): array
{
	$sanitized = vltda_maintenance_get_settings();

	$sanitized['enabled'] = !empty($input['enabled']);
	$sanitized['logo_attachment'] = isset($input['logo_attachment']) ? absint($input['logo_attachment']) : 0;
	$sanitized['background_attachment'] = isset($input['background_attachment']) ? absint($input['background_attachment']) : 0;

	$sanitized['title'] = isset($input['title']) ? sanitize_text_field((string) $input['title']) : '';
	$sanitized['text'] = isset($input['text']) ? wp_kses_post((string) $input['text']) : '';

	$sanitized['show_animation'] = !empty($input['show_animation']);
	$sanitized['show_counter'] = !empty($input['show_counter']);

	$sanitized['launch_date'] = isset($input['launch_date']) ? sanitize_text_field((string) $input['launch_date']) : '';

	$sanitized['button_text'] = isset($input['button_text']) ? sanitize_text_field((string) $input['button_text']) : '';
	$sanitized['button_url'] = isset($input['button_url']) ? esc_url_raw((string) $input['button_url']) : '';

	return $sanitized;
}

/**
 * Register admin page under Appearance.
 *
 * @return void
 */
function vltda_maintenance_add_admin_page(): void
{
	if (!current_user_can('manage_options')) {
		return;
	}

	add_theme_page(
		sprintf('%s', esc_html__('Modo Manutenção', 'versao-ltda-theme')),
		sprintf('%s', esc_html__('Modo Manutenção', 'versao-ltda-theme')),
		'manage_options',
		'vltda-maintenance',
		'vltda_render_maintenance_page'
	);
}

/**
 * Admin page renderer.
 *
 * @return void
 */
function vltda_render_maintenance_page(): void
{
	if (!current_user_can('manage_options')) {
		wp_die(esc_html__('Você não tem permissão para acessar esta página.', 'versao-ltda-theme'));
	}

	vltda_register_maintenance_settings();

	// Validate nonce only when the form is submitted.
	if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['vltda_maintenance_nonce'])) {
		check_admin_referer('vltda_maintenance_page_action', 'vltda_maintenance_nonce');
	}


	$settings = vltda_maintenance_get_settings();
	?>
	<div class="wrap">

			<h1><?php echo esc_html__('Modo Manutenção (Coming Soon)', 'versao-ltda-theme'); ?></h1>
			<p><?php echo esc_html__('Ative o modo manutenção e personalize a página que visitantes verão.', 'versao-ltda-theme'); ?></p>

			<form method="post" action="options.php">
				<?php
				// Settings API nonce.
				settings_fields('vltda_maintenance_group');
				?>
				<?php
				// Render nonce for extra security.
				wp_nonce_field('vltda_maintenance_page_action', 'vltda_maintenance_nonce');
				?>
				<table class="form-table" role="presentation">
					<?php
					// Ensure fields are registered.
					add_settings_section(
						'vltda_maintenance_main',
						'',
						'__return_null',
						'vltda_maintenance_page'
					);
					vltda_register_maintenance_settings();
					do_settings_sections('vltda_maintenance_page');
					?>
				</table>
				<?php submit_button(esc_html__('Salvar alterações', 'versao-ltda-theme')); ?>
			</form>
		</div>


	<script>
	// Lightweight uploader helpers.
	window.VLTDTA_MAINTENANCE = window.VLTDTA_MAINTENANCE || {};
	</script>
	<?php
}

/**
 * Fields rendering helpers.
 *
 * @return void
 */
function vltda_maintenance_field_enabled_render(): void
{
	$settings = vltda_maintenance_get_settings();
	$checked = !empty($settings['enabled']);
	?>
	<label>
		<input type="checkbox" name="<?php echo esc_attr(VLTDTA_MAINTENANCE_OPTION_KEY); ?>[enabled]" value="1" <?php checked($checked); ?> />
		<span class="description"><?php echo esc_html__('Quando ativo, visitantes recebem 503 e veem a página de manutenção.', 'versao-ltda-theme'); ?></span>
	</label>
	<?php
}

function vltda_maintenance_field_logo_render(): void
{
	$settings = vltda_maintenance_get_settings();
	$attachment_id = absint($settings['logo_attachment'] ?? 0);
	$preview_url = vltda_maintenance_resolve_image_url($attachment_id, 'logo');
	?>
	<div class="vltda-media-field" data-kind="logo">
		<input type="hidden" class="vltda-attachment-id" name="<?php echo esc_attr(VLTDTA_MAINTENANCE_OPTION_KEY); ?>[logo_attachment]" value="<?php echo esc_attr((string) $attachment_id); ?>" />
		<div class="vltda-preview">
			<img src="<?php echo esc_url($preview_url); ?>" alt="" style="max-width:160px;height:auto;" />
		</div>
		<p>
			<button type="button" class="button vltda-upload-button" data-target="logo">Selecionar logo</button>
			<button type="button" class="button vltda-clear-button" data-target="logo">Remover</button>
		</p>
	</div>
	<?php
}

function vltda_maintenance_field_background_render(): void
{
	$settings = vltda_maintenance_get_settings();
	$attachment_id = absint($settings['background_attachment'] ?? 0);
	$preview_url = vltda_maintenance_resolve_image_url($attachment_id, 'background');
	?>
	<div class="vltda-media-field" data-kind="background">
		<input type="hidden" class="vltda-attachment-id" name="<?php echo esc_attr(VLTDTA_MAINTENANCE_OPTION_KEY); ?>[background_attachment]" value="<?php echo esc_attr((string) $attachment_id); ?>" />
		<div class="vltda-preview">
			<img src="<?php echo esc_url($preview_url); ?>" alt="" style="max-width:240px;height:auto;" />
		</div>
		<p>
			<button type="button" class="button vltda-upload-button" data-target="background">Selecionar fundo</button>
			<button type="button" class="button vltda-clear-button" data-target="background">Remover</button>
		</p>
	</div>
	<?php
}

function vltda_maintenance_field_title_render(): void
{
	$settings = vltda_maintenance_get_settings();
	?>
	<input type="text" class="regular-text" name="<?php echo esc_attr(VLTDTA_MAINTENANCE_OPTION_KEY); ?>[title]" value="<?php echo esc_attr((string) ($settings['title'] ?? '')); ?>" />
	<?php
}

function vltda_maintenance_field_text_render(): void
{
	$settings = vltda_maintenance_get_settings();
	?>
	<textarea class="large-text" rows="5" name="<?php echo esc_attr(VLTDTA_MAINTENANCE_OPTION_KEY); ?>[text]"><?php echo esc_html((string) ($settings['text'] ?? '')); ?></textarea>
	<p class="description"><?php echo esc_html__('Use texto simples. Se precisar de HTML, habilite via wp_kses_post no salvamento.', 'versao-ltda-theme'); ?></p>
	<?php
}

function vltda_maintenance_field_show_animation_render(): void
{
	$settings = vltda_maintenance_get_settings();
	?>
	<label>
		<input type="checkbox" name="<?php echo esc_attr(VLTDTA_MAINTENANCE_OPTION_KEY); ?>[show_animation]" value="1" <?php checked(!empty($settings['show_animation'])); ?> />
		<span class="description"><?php echo esc_html__('Exibe animação decorativa na página.', 'versao-ltda-theme'); ?></span>
	</label>
	<?php
}

function vltda_maintenance_field_show_counter_render(): void
{
	$settings = vltda_maintenance_get_settings();
	?>
	<label>
		<input type="checkbox" name="<?php echo esc_attr(VLTDTA_MAINTENANCE_OPTION_KEY); ?>[show_counter]" value="1" <?php checked(!empty($settings['show_counter'])); ?> />
		<span class="description"><?php echo esc_html__('Exibe contador regressivo até a data informada.', 'versao-ltda-theme'); ?></span>
	</label>
	<?php
}

function vltda_maintenance_field_launch_date_render(): void
{
	$settings = vltda_maintenance_get_settings();
	?>
	<input type="text" class="regular-text" placeholder="YYYY-mm-dd HH:MM:SS" name="<?php echo esc_attr(VLTDTA_MAINTENANCE_OPTION_KEY); ?>[launch_date]" value="<?php echo esc_attr((string) ($settings['launch_date'] ?? '')); ?>" />
	<?php
}

function vltda_maintenance_field_button_text_render(): void
{
	$settings = vltda_maintenance_get_settings();
	?>
	<input type="text" class="regular-text" name="<?php echo esc_attr(VLTDTA_MAINTENANCE_OPTION_KEY); ?>[button_text]" value="<?php echo esc_attr((string) ($settings['button_text'] ?? '')); ?>" />
	<?php
}

function vltda_maintenance_field_button_url_render(): void
{
	$settings = vltda_maintenance_get_settings();
	?>
	<input type="url" class="regular-text" name="<?php echo esc_attr(VLTDTA_MAINTENANCE_OPTION_KEY); ?>[button_url]" value="<?php echo esc_attr((string) ($settings['button_url'] ?? '')); ?>" />
	<?php
}

/**
 * Intercept frontend requests.
 *
 * @return void
 */
function vltda_maintenance_template_redirect(): void
{
	if (!vltda_maintenance_is_active_for_request()) {
		return;
	}

	// Ensure correct headers.
	if (!headers_sent()) {
		status_header(503);
	}

	// Prevent caching of maintenance response.
	nocache_headers();

	// Load template.
	$template = get_template_directory() . '/templates/maintenance.php';

	if (file_exists($template)) {
		require $template;
		exit;
	}

	// Fallback - should not happen.
	wp_die(esc_html__('Modo manutenção ativo.', 'versao-ltda-theme'), '', array('response' => 503));
}

/**
 * Register hooks.
 *
 * @return void
 */
function vltda_maintenance_mode_init(): void
{
	add_action('admin_menu', 'vltda_maintenance_add_admin_page');
	add_action('admin_init', 'vltda_register_maintenance_settings');
	add_action('template_redirect', 'vltda_maintenance_template_redirect');
}

add_action('after_setup_theme', 'vltda_maintenance_mode_init');

// Media uploader scripts.
add_action(
	'admin_enqueue_scripts',
	function (string $hook_suffix): void {
		if (!current_user_can('manage_options')) {
			return;
		}
		// Only load on our theme page.
		if ($hook_suffix !== 'appearance_page_vltda-maintenance') {
			return;
		}

		wp_enqueue_media();

		$script = <<<'JS'
(function(){
  function wpMediaButton(button, target){
    button.addEventListener('click', function(e){
      e.preventDefault();
      var frame = wp.media({
        title: 'Selecionar imagem',
        button: { text: 'Usar imagem' },
        multiple: false
      });
      frame.on('close', function(){
        var attachment = frame.state().get('selection').first();
        if (!attachment) return;
        var id = attachment.id;
        var url = attachment.attributes.url;

        var container = button.closest('.vltda-media-field');
        if (!container) return;
        var hidden = container.querySelector('.vltda-attachment-id');
        if (hidden) hidden.value = String(id);

        var img = container.querySelector('.vltda-preview img');
        if (img) img.src = url;
      });
      frame.open();
    });
  }

  function clearButton(button){
    button.addEventListener('click', function(e){
      e.preventDefault();
      var container = button.closest('.vltda-media-field');
      if (!container) return;
      var hidden = container.querySelector('.vltda-attachment-id');
      if (hidden) hidden.value = '0';
      var img = container.querySelector('.vltda-preview img');
      if (img) {
        // keep current src; server will fallback to defaults via resolver on render.
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.vltda-upload-button').forEach(function(btn){
      var target = btn.getAttribute('data-target');
      wpMediaButton(btn, target);
    });
    document.querySelectorAll('.vltda-clear-button').forEach(function(btn){
      clearButton(btn);
    });
  });
})();
JS;

		wp_add_inline_script('jquery-core', $script);
	}
);

