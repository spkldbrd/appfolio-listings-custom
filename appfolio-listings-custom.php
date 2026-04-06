<?php
/**
 * Plugin Name: Appfolio Listings Custom
 * Description: Appfolio listings integration for WordPress (custom fork — no vendor license). Uninstall the original “Listings for Appfolio Pro” before activating to avoid duplicate shortcodes.
 * Version: 3.0.0
 * Author: Custom fork (based on Listings for Appfolio Pro)
 * License: GPL+2
 * Text Domain: appfolio-listings-custom
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

define('APFL_PRO_CURR_VER', '3.0.0');
define('AFC_FORK_PLUGIN_FILE', __FILE__);

if (is_admin()) {
	require_once dirname(__FILE__) . '/inc/update-check.php';
}

include_once dirname(__FILE__) . '/apfl_actdeact.php';
register_activation_hook(__FILE__, array('Apfl_Actdeact', 'apfl_plugin_activate'));

add_action('init', 'apfl_pp_init_plugin', 1);
if (!function_exists('apfl_pp_init_plugin')) {
	function apfl_pp_init_plugin()
	{
		global $apfl_plugin_url;
		global $apfl_plugin_dir;
		global $client_listings_url;
		global $client_gmap_api;
		$apfl_plugin_url = plugin_dir_url(__FILE__);
		$apfl_plugin_dir = plugin_dir_path(__FILE__);
		$client_listings_url = get_option('apfl_url');
		$client_gmap_api = get_option('apfl_gmap_api');

		if (is_admin()) {
			add_action('admin_enqueue_scripts', 'apfl_ppp_admin_styles_scripts');
		}

		include(plugin_dir_path(__FILE__) . 'admin/settings.php');

		add_action('wp_enqueue_scripts', 'apfl_pp_styles_scripts');

		if (!class_exists('simple_html_dom')) {
			require(plugin_dir_path(__FILE__) . 'inc/simple_html_dom.php');
		}
		include(plugin_dir_path(__FILE__) . 'inc/customizer.php');
		include(plugin_dir_path(__FILE__) . 'inc/single-listing.php');
		include(plugin_dir_path(__FILE__) . 'inc/single-listing-modern.php');
		include(plugin_dir_path(__FILE__) . 'inc/listings.php');
		include(plugin_dir_path(__FILE__) . 'inc/multiple-listings.php');
		include(plugin_dir_path(__FILE__) . 'inc/slider.php');
		include(plugin_dir_path(__FILE__) . 'inc/carousel.php');
		include(plugin_dir_path(__FILE__) . 'config.php');
		include(plugin_dir_path(__FILE__) . 'inc/search-form-sep.php');
		include(plugin_dir_path(__FILE__) . 'inc/admin-functions.php');
		include(plugin_dir_path(__FILE__) . 'inc/functions-front.php');

		add_shortcode('apfl_listings', 'apfl_pp_display_all_listings');
		add_shortcode('apfl_listings_multiple', 'apfl_pp_display_multiple_listings');
		add_shortcode('apfl_single_listing', 'apfl_pp_redirect_sl');
		add_shortcode('apfl_slider', 'apfl_pp_display_slider');
		add_shortcode('apfl_carousel', 'apfl_pp_display_carousel');
		add_shortcode('apfl_search_frm', 'apfl_pp_search_frm');
	}
}

function lfa_add_query_var_lid( $wp_query ) {
	if(isset($_GET['lid'])){
		$wp_query->query_vars['lid'] = sanitize_text_field($_GET['lid']);
	}
}
add_action( 'pre_get_posts', 'lfa_add_query_var_lid' );

add_action('wp_head', 'lfa_fb_opengraph', 5);
function lfa_fb_opengraph() {
	$list_id = sanitize_text_field(get_query_var('lid'));
	if ($list_id) {

		global $client_listings_url;

		$custom_url = '';
		if (isset($_GET['url'])) {
			$custom_url = sanitize_text_field($_GET['url']);
		}

		if ($client_listings_url || $custom_url) {

			if ($custom_url) {
				$client_listings_url = $custom_url;
			}

			$url = $client_listings_url . '/listings/detail/' . $list_id;
			$html = new simple_html_dom();
			$response = wp_remote_get($url, [
				'timeout' => 15,
				'headers' => [
					'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
					'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
					'Accept-Language' => 'en-US,en;q=0.5',
					'Connection'      => 'keep-alive',
				]
			]);
			if (!is_wp_error($response)) {
				$html->load(wp_remote_retrieve_body($response));
			}

			if($html && $html->root) {
				$main_img_src = $address = $ttl = '';
				$listing_images = array();
				$i = 0;
				$main_img = $html->find('main .gallery img', 0);
				if ($main_img) {
					$main_img_src = $main_img->{'src'};
				}

				$address_obj = $html->find('head title', 0);
				if ($address_obj) {
					$address = $address_obj->innertext;
				}

				$listing_details = $html->find('.listing-detail', 0);
				if ($listing_details) {
					$ld_body = $listing_details->find('.listing-detail__body', 0);
					if ($ld_body) {
						$ttl_obj = $ld_body->find('.listing-detail__title', 0);
						if ($ttl_obj) {
							$ttl = $ttl_obj->innertext;
						}
					}
				}

				?>

				<meta property="og:title" content="<?php echo $address; ?>" />
				<meta property="og:description" content="<?php echo $ttl; ?>" />
				<meta property="og:url" content="<?php echo the_permalink() . '?lid=' . $list_id; ?>" />
				<meta property="og:image" content="<?php echo $main_img_src; ?>" />

				<?php
			}
		}
	}
}

if (!function_exists('apfl_pp_redirect_sl')) {
	function apfl_pp_redirect_sl()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			return '[Listings for Appfolio Shortcode]';
		}

		if (isset($_GET['lid'])) {
			$custom_url = '';
			if (isset($_GET['url'])) {
				$custom_url = sanitize_text_field($_GET['url']);
			}
			$render_html = apfl_pp_display_single_listing($custom_url);
			return $render_html;
		}

	}
}

if (!function_exists('apfl_pp_styles_scripts')) {

	function apfl_pp_styles_scripts()
	{
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style(
			'apfl-jquery-ui-style',
			plugin_dir_url(__FILE__) . 'css/jquery-ui.css',
			array(),
			APFL_PRO_CURR_VER
		);
		wp_enqueue_style(
			'apfl-pp-style',
			plugin_dir_url(__FILE__) . 'css/style.css',
			array(),
			APFL_PRO_CURR_VER
		);
		wp_enqueue_style(
			'apfl-pp-gall-style',
			plugin_dir_url(__FILE__) . 'css/gallery.css',
			array(),
			APFL_PRO_CURR_VER
		);
		wp_enqueue_style(
			'apfl-font-awesome',
			plugin_dir_url(__FILE__) . 'css/fa.min.css',
			array(),
			APFL_PRO_CURR_VER
		);
		wp_enqueue_script(
			'apfl-pp-script',
			plugins_url('js/main.js', __FILE__),
			array('jquery', 'jquery-ui-datepicker'),
			APFL_PRO_CURR_VER
		);

		wp_localize_script(
			'apfl-pp-script',
			'apfl_load_listing_obj',
			array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'apfl_pro_plugin_url' => plugin_dir_url(__FILE__),
				'nonce' => wp_create_nonce('apfl_ajax_nonce')
			)
		);

		wp_register_script(
			'apfl-slider-script',
			plugins_url('js/slider.js', __FILE__),
			APFL_PRO_CURR_VER
		);

		wp_localize_script(
			'apfl-slider-script',
			'apfl_slider_obj',
			array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('apfl_ajax_nonce')
			)
		);

        wp_register_style(
			'apfl-carousel-mdrn-style',
			plugin_dir_url(__FILE__) . 'css/carousel-mdrn.css',
			array(),
			APFL_PRO_CURR_VER
		);

        wp_register_script(
			'apfl-carousel-mdrn-script',
			plugins_url('slider/js/carousel-mdrn.js', __FILE__),
			APFL_PRO_CURR_VER
		);

		wp_register_script(
			'apfl-carousel-script',
			plugins_url('js/carousel.js', __FILE__),
			APFL_PRO_CURR_VER
		);

		wp_localize_script(
			'apfl-carousel-script',
			'apfl_carousel_obj',
			array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('apfl_ajax_nonce')
			)
		);

	}
}

if (!function_exists('apfl_ppp_admin_styles_scripts')) {
	function apfl_ppp_admin_styles_scripts()
	{
		wp_enqueue_style(
			'apfl-pp-admin-style',
			plugin_dir_url(__FILE__) . 'css/admin-style.css',
			array(),
			APFL_PRO_CURR_VER
		);
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script(
			'apfl-pp-admin-script',
			plugins_url('js/admin-main.js', __FILE__),
			array('jquery', 'wp-color-picker'),
			APFL_PRO_CURR_VER
		);
		wp_localize_script(
			'apfl-pp-admin-script',
			'apfl_admin_obj',
			array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'apfl_pro_plugin_url' => plugin_dir_url(__FILE__),
				'nonce' => wp_create_nonce('apfl_ajax_nonce')
			)
		);
	}
}

// Plugin Configuration Page
if (is_admin()) {
	add_action('admin_menu', 'apfl_pp_admin_config');
	if (!function_exists('apfl_pp_admin_config')) {
		function apfl_pp_admin_config()
		{
			add_menu_page('Appfolio Listings', 'Appfolio', 'manage_options', 'apfl-pp', 'apfl_pp_config_callback', 'dashicons-admin-home');
		}
	}
}

function apfl_pp_code_editor() {
    if ('toplevel_page_apfl-pp' !== get_current_screen()->id) {
        return;
    }

    $settings = wp_enqueue_code_editor(array('type' => 'text/css'));
    if (false === $settings) {
        return;
    }

    wp_add_inline_script(
        'code-editor',
        sprintf(
            'jQuery( function() { wp.codeEditor.initialize( "apfl_custom_css", %s ); } );',
            wp_json_encode($settings)
        )
    );
}
add_action('admin_enqueue_scripts', 'apfl_pp_code_editor');


function apfl_enqueue_plugin_assets() {
	wp_enqueue_style(
			'apfl-form-css',
			plugin_dir_url(__FILE__) . 'css/form.css',
			array(),
			'1.0'
	);
}
add_action('wp_enqueue_scripts', 'apfl_enqueue_plugin_assets');
