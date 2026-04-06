<?php

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
	exit;
}

if (!function_exists('apfl_pp_display_slider')) {
	function apfl_pp_display_slider($atts){

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			return '[Listings for Appfolio Shortcode]';
		}

		wp_enqueue_script('apfl-slider-script');

		global $client_listings_url;
		if(!$client_listings_url) { 
			return '<p>The Appfolio URL is blank. Please contact site owner.</p>'; 
		}
		
		global $apfl_plugin_url;
		$render_html = '<div class="apfl-listings-slider">
			<div class="apfl-loading">
				<p>Loading Listings...</p>
				<img src="'.$apfl_plugin_url.'/images/loading.gif">
			</div>
		</div>';
		return $render_html;
	}
}
