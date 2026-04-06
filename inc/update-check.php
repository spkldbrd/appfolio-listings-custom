<?php
/**
 * Remote version check when visiting Appfolio → settings (toplevel_page_apfl-pp).
 *
 * Uses the public GitHub raw version.json by default (no wp-config needed).
 * Optional: define AFC_UPDATE_MANIFEST_URL to override, or set it to '' to disable.
 * Or use filter afc_update_manifest_url (return '' to turn off).
 *
 * @package Appfolio_Listings_Custom
 */

if (!defined('ABSPATH')) {
	exit;
}

/** @var string Default manifest for this fork (public repo). */
const AFC_DEFAULT_UPDATE_MANIFEST = 'https://raw.githubusercontent.com/spkldbrd/appfolio-listings-custom/main/version.json';

/**
 * @return string Manifest URL or empty to disable checks.
 */
function afc_get_update_manifest_url() {
	if (defined('AFC_UPDATE_MANIFEST_URL')) {
		$url = (string) AFC_UPDATE_MANIFEST_URL;
	} else {
		$url = AFC_DEFAULT_UPDATE_MANIFEST;
	}
	return apply_filters('afc_update_manifest_url', $url);
}

/**
 * Run on Appfolio admin screen load; caches result for 12 hours.
 */
function afc_maybe_check_for_updates() {
	if (!current_user_can('manage_options')) {
		return;
	}
	$url = afc_get_update_manifest_url();
	if ($url === '') {
		return;
	}

	$key = 'afc_remote_manifest_' . md5($url);
	if (false !== get_transient($key)) {
		return;
	}

	$response = wp_remote_get(
		$url,
		array(
			'timeout' => 10,
			'headers' => array(
				'Accept' => 'application/json',
			),
		)
	);

	if (is_wp_error($response) || (int) wp_remote_retrieve_response_code($response) !== 200) {
		set_transient($key, array('error' => 1), HOUR_IN_SECONDS);
		return;
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, true);
	if (!is_array($data) || empty($data['version'])) {
		set_transient($key, array('error' => 1), HOUR_IN_SECONDS);
		return;
	}

	$data['version'] = trim((string) $data['version']);
	set_transient($key, $data, 12 * HOUR_IN_SECONDS);
}

/**
 * @param WP_Screen $screen
 */
function afc_update_check_on_appfolio_screen($screen) {
	if (!$screen || $screen->id !== 'toplevel_page_apfl-pp') {
		return;
	}
	afc_maybe_check_for_updates();
}
add_action('current_screen', 'afc_update_check_on_appfolio_screen');

/**
 * Dismiss per remote version (GET + nonce).
 */
function afc_handle_dismiss_update_notice() {
	if (!isset($_GET['afc_dismiss_update'], $_GET['afc_ver'], $_GET['_wpnonce'])) {
		return;
	}
	if (!current_user_can('manage_options')) {
		return;
	}
	if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'afc_dismiss_update')) {
		return;
	}
	$ver = sanitize_text_field(wp_unslash($_GET['afc_ver']));
	update_user_meta(get_current_user_id(), 'afc_update_dismiss_' . md5($ver), '1');

	wp_safe_redirect(remove_query_arg(array('afc_dismiss_update', 'afc_ver', '_wpnonce')));
	exit;
}
add_action('admin_init', 'afc_handle_dismiss_update_notice');

function afc_update_available_admin_notice() {
	$url = afc_get_update_manifest_url();
	if ($url === '' || !current_user_can('manage_options')) {
		return;
	}

	$screen = function_exists('get_current_screen') ? get_current_screen() : null;
	if (!$screen || $screen->id !== 'toplevel_page_apfl-pp') {
		return;
	}

	$key = 'afc_remote_manifest_' . md5($url);
	$data = get_transient($key);
	if (!is_array($data) || !empty($data['error']) || empty($data['version'])) {
		return;
	}

	$remote = $data['version'];
	if (!preg_match('/^\d+(\.\d+){0,3}/', $remote)) {
		return;
	}

	if (version_compare(APFL_PRO_CURR_VER, $remote, '>=')) {
		return;
	}

	$dismiss_key = 'afc_update_dismiss_' . md5($remote);
	if (get_user_meta(get_current_user_id(), $dismiss_key, true)) {
		return;
	}

	$download = isset($data['download_url']) ? esc_url($data['download_url']) : '';
	$changelog = isset($data['changelog_url']) ? esc_url($data['changelog_url']) : '';
	$dismiss = wp_nonce_url(
		add_query_arg(
			array(
				'afc_dismiss_update' => '1',
				'afc_ver' => rawurlencode($remote),
			),
			admin_url('admin.php?page=apfl-pp')
		),
		'afc_dismiss_update'
	);

	echo '<div class="notice notice-info"><p>';
	echo '<strong>' . esc_html__('Appfolio Listings Custom', 'appfolio-listings-custom') . ':</strong> ';
	printf(
		/* translators: 1: new version, 2: current version */
		esc_html__('Version %1$s is available (you are running %2$s).', 'appfolio-listings-custom'),
		'<code>' . esc_html($remote) . '</code>',
		'<code>' . esc_html(APFL_PRO_CURR_VER) . '</code>'
	);
	echo ' ';

	if ($download) {
		echo '<a href="' . $download . '" class="button button-primary" target="_blank" rel="noopener noreferrer">' .
			esc_html__('Download', 'appfolio-listings-custom') . '</a> ';
	}
	if ($changelog && $changelog !== $download) {
		echo '<a href="' . $changelog . '" class="button button-secondary" target="_blank" rel="noopener noreferrer">' .
			esc_html__('Changelog', 'appfolio-listings-custom') . '</a> ';
	}

	echo '<a href="' . esc_url($dismiss) . '" class="button button-link">' .
		esc_html__('Dismiss', 'appfolio-listings-custom') . '</a>';
	echo '</p></div>';
}
add_action('admin_notices', 'afc_update_available_admin_notice');
