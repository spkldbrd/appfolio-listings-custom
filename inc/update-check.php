<?php
/**
 * Remote version check and WordPress plugin updater integration.
 *
 * Uses the public GitHub raw version.json by default (no wp-config needed).
 * Optional: define AFC_UPDATE_MANIFEST_URL to override, or set it to '' to disable.
 * Or use filter afc_update_manifest_url (return '' to turn off).
 *
 * Optional: define AFC_AUTO_UPDATE as true to enable background auto-updates for this plugin
 * (in addition to the per-plugin toggle on the Plugins screen in WP 5.5+).
 *
 * @package Appfolio_Listings_Custom
 */

if (!defined('ABSPATH')) {
	exit;
}

/** Default update manifest (public GitHub raw JSON). */
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
	return trim((string) apply_filters('afc_update_manifest_url', $url));
}

/**
 * Fetch manifest from network and store in transient (12h success, 1h on error).
 *
 * @param string $url Manifest URL.
 * @param string $key Transient key.
 */
function afc_prime_remote_manifest_cache($url, $key) {
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
 * Cached manifest for display and updater, or null. Fetches on cache miss.
 *
 * @return array<string, mixed>|null
 */
function afc_get_remote_manifest_data() {
	$url = afc_get_update_manifest_url();
	if ($url === '') {
		return null;
	}
	$key = 'afc_remote_manifest_' . md5($url);
	$data = get_transient($key);
	if ($data !== false) {
		if (is_array($data) && empty($data['error']) && !empty($data['version'])) {
			return $data;
		}
		return null;
	}
	afc_prime_remote_manifest_cache($url, $key);
	$data = get_transient($key);
	if (is_array($data) && empty($data['error']) && !empty($data['version'])) {
		return $data;
	}
	return null;
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

	afc_prime_remote_manifest_cache($url, $key);
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
 * Register update with WordPress so Plugins / Dashboard → Updates can install in one click.
 *
 * @param object $transient Value for site transient update_plugins.
 * @return object
 */
function afc_inject_plugin_update_transient($transient) {
	if (!is_object($transient) || !defined('AFC_FORK_PLUGIN_FILE')) {
		return $transient;
	}
	if (empty($transient->checked) || !is_array($transient->checked)) {
		return $transient;
	}

	$plugin_file = plugin_basename(AFC_FORK_PLUGIN_FILE);
	if (!isset($transient->checked[$plugin_file])) {
		return $transient;
	}

	$url = afc_get_update_manifest_url();
	if ($url === '') {
		return $transient;
	}

	$data = afc_get_remote_manifest_data();
	if (!$data || empty($data['download_url'])) {
		return $transient;
	}

	$remote = trim((string) $data['version']);
	if (!preg_match('/^\d+(\.\d+){0,3}/', $remote)) {
		return $transient;
	}

	$local = $transient->checked[$plugin_file];
	if (version_compare($local, $remote, '>=')) {
		return $transient;
	}

	$package = esc_url_raw((string) $data['download_url']);
	if ($package === '' || !wp_http_validate_url($package)) {
		return $transient;
	}

	$transient->response[ $plugin_file ] = (object) array(
		'id'            => $plugin_file,
		'slug'          => 'appfolio-listings-custom',
		'plugin'        => $plugin_file,
		'new_version'   => $remote,
		'url'           => 'https://github.com/spkldbrd/appfolio-listings-custom',
		'package'       => $package,
		'requires'      => !empty($data['requires']) ? sanitize_text_field((string) $data['requires']) : '5.8',
		'tested'        => !empty($data['tested']) ? sanitize_text_field((string) $data['tested']) : '',
		'requires_php'  => !empty($data['requires_php']) ? sanitize_text_field((string) $data['requires_php']) : '',
	);

	if (isset($transient->no_update[ $plugin_file ])) {
		unset($transient->no_update[ $plugin_file ]);
	}

	return $transient;
}
add_filter('pre_set_site_transient_update_plugins', 'afc_inject_plugin_update_transient');

/**
 * "View details" / changelog tab for this plugin (not hosted on wordpress.org).
 *
 * @param false|object|array $result
 * @param string               $action
 * @param object               $args
 * @return false|object|array
 */
function afc_plugins_api_plugin_information($result, $action, $args) {
	if ($action !== 'plugin_information' || empty($args->slug) || $args->slug !== 'appfolio-listings-custom') {
		return $result;
	}

	$data = afc_get_remote_manifest_data();
	if (!$data) {
		return $result;
	}

	$changelog = !empty($data['changelog_url']) ? esc_url($data['changelog_url']) : 'https://github.com/spkldbrd/appfolio-listings-custom/releases';
	$changelog_html = '<p><a href="' . esc_url($changelog) . '" target="_blank" rel="noopener noreferrer">' .
		esc_html__('Release notes on GitHub', 'appfolio-listings-custom') . '</a></p>';

	return (object) array(
		'name'          => 'Appfolio Listings Custom',
		'slug'          => 'appfolio-listings-custom',
		'version'       => isset($data['version']) ? sanitize_text_field((string) $data['version']) : '',
		'author'        => '<a href="https://github.com/spkldbrd" target="_blank" rel="noopener noreferrer">spkldbrd</a>',
		'homepage'      => 'https://github.com/spkldbrd/appfolio-listings-custom',
		'requires'      => !empty($data['requires']) ? sanitize_text_field((string) $data['requires']) : '',
		'tested'        => !empty($data['tested']) ? sanitize_text_field((string) $data['tested']) : '',
		'download_link' => !empty($data['download_url']) ? esc_url($data['download_url']) : '',
		'sections'      => array(
			'description' => '<p>' . esc_html__('Property listings from Appfolio for WordPress.', 'appfolio-listings-custom') . '</p>',
			'changelog'   => $changelog_html,
		),
	);
}
add_filter('plugins_api', 'afc_plugins_api_plugin_information', 10, 3);

/**
 * Optional background auto-updates when AFC_AUTO_UPDATE is true.
 *
 * @param bool|null $update Whether to update.
 * @param object    $item   Plugin update offer.
 * @return bool|null
 */
function afc_filter_auto_update_plugin($update, $item) {
	if (!defined('AFC_AUTO_UPDATE') || !AFC_AUTO_UPDATE) {
		return $update;
	}
	if (!defined('AFC_FORK_PLUGIN_FILE')) {
		return $update;
	}
	if (!is_object($item) || empty($item->plugin)) {
		return $update;
	}
	if ($item->plugin === plugin_basename(AFC_FORK_PLUGIN_FILE)) {
		return true;
	}
	return $update;
}
add_filter('auto_update_plugin', 'afc_filter_auto_update_plugin', 10, 2);

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
	if (!isset($_GET['page']) || sanitize_text_field(wp_unslash($_GET['page'])) !== 'apfl-pp') {
		return;
	}
	$ver = sanitize_text_field(wp_unslash($_GET['afc_ver']));
	update_user_meta(get_current_user_id(), 'afc_update_dismiss_' . md5($ver), '1');

	wp_safe_redirect(
		remove_query_arg(
			array('afc_dismiss_update', 'afc_ver', '_wpnonce'),
			admin_url('admin.php?page=apfl-pp')
		)
	);
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

	$data = afc_get_remote_manifest_data();
	if (!$data) {
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

	$changelog = isset($data['changelog_url']) ? esc_url($data['changelog_url']) : '';
	$dismiss = wp_nonce_url(
		add_query_arg(
			array(
				'afc_dismiss_update' => '1',
				'afc_ver' => $remote,
			),
			admin_url('admin.php?page=apfl-pp')
		),
		'afc_dismiss_update'
	);

	$plugin_file = defined('AFC_FORK_PLUGIN_FILE') ? plugin_basename(AFC_FORK_PLUGIN_FILE) : '';
	$can_update = $plugin_file && current_user_can('update_plugins');

	echo '<div class="notice notice-info"><p>';
	echo '<strong>' . esc_html__('Appfolio Listings Custom', 'appfolio-listings-custom') . ':</strong> ';
	printf(
		/* translators: 1: new version, 2: current version */
		esc_html__('Version %1$s is available (you are running %2$s).', 'appfolio-listings-custom'),
		'<code>' . esc_html($remote) . '</code>',
		'<code>' . esc_html(APFL_PRO_CURR_VER) . '</code>'
	);
	echo ' ';

	if ($can_update) {
		$update_url = wp_nonce_url(
			self_admin_url('update.php?action=upgrade-plugin&plugin=' . rawurlencode($plugin_file)),
			'upgrade-plugin_' . $plugin_file
		);
		echo '<a href="' . esc_url($update_url) . '" class="button button-primary">' .
			esc_html__('Update now', 'appfolio-listings-custom') . '</a> ';
	} elseif (!empty($data['download_url'])) {
		echo '<a href="' . esc_url($data['download_url']) . '" class="button button-primary" target="_blank" rel="noopener noreferrer">' .
			esc_html__('Download', 'appfolio-listings-custom') . '</a> ';
	}

	if ($changelog) {
		echo '<a href="' . $changelog . '" class="button button-secondary" target="_blank" rel="noopener noreferrer">' .
			esc_html__('Changelog', 'appfolio-listings-custom') . '</a> ';
	}

	echo '<a href="' . esc_url($dismiss) . '" class="button button-link">' .
		esc_html__('Dismiss', 'appfolio-listings-custom') . '</a>';
	echo '</p></div>';
}
add_action('admin_notices', 'afc_update_available_admin_notice');
