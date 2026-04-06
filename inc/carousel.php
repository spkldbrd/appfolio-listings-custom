<?php

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
	exit;
}

if (!function_exists('apfl_pp_display_carousel')) {
	function apfl_pp_display_carousel($atts){

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			return '';
		}

		$atts = shortcode_atts(
			array(
				'class' => '',
				'id' => '',
				'count' => '',
				'template' => '',
			),
			is_array($atts) ? $atts : array(),
			'apfl_carousel'
		);

		$apfl_carousel_template = get_option('apfl_carousel_template', 'classic');
		if ($atts['template'] === 'modern' || $atts['template'] === 'classic') {
			$apfl_carousel_template = $atts['template'];
		}

		if ($apfl_carousel_template === 'modern') {
			wp_enqueue_style('apfl-carousel-mdrn-style');
			wp_enqueue_script('apfl-carousel-mdrn-script');
		}
		wp_enqueue_script('apfl-carousel-script');

		global $client_listings_url;
		if(!$client_listings_url) {
			return '<p>The Appfolio URL is blank. Please contact the site owner.</p>';
		}

		global $apfl_plugin_url;

		$wrapper_id = '';
		if ($atts['id'] !== '') {
			$wrapper_id = ' id="' . esc_attr(sanitize_title($atts['id'])) . '"';
		}

		$extra_classes = array('apfl-listings-crsl', 'apfl-carousel-root');
		if ($atts['class'] !== '') {
			foreach (preg_split('/\s+/', trim($atts['class']), -1, PREG_SPLIT_NO_EMPTY) as $c) {
				$san = sanitize_html_class($c);
				if ($san !== '') {
					$extra_classes[] = $san;
				}
			}
		}

		$data = array();
		if ($atts['count'] !== '') {
			$data[] = 'data-apfl-crsl-count="' . esc_attr((string) max(1, (int) $atts['count'])) . '"';
		}
		if ($atts['template'] === 'modern' || $atts['template'] === 'classic') {
			$data[] = 'data-apfl-crsl-template="' . esc_attr($atts['template']) . '"';
		}

		$data_attr = $data ? ' ' . implode(' ', $data) : '';

		$render_html = '<div' . $wrapper_id . ' class="' . esc_attr(implode(' ', $extra_classes)) . '"' . $data_attr . '>
			<div class="apfl-loading">
				<p>Loading Listings...</p>
				<img src="' . esc_url($apfl_plugin_url . 'images/loading.gif') . '" alt="">
			</div>
		</div>';

		return $render_html;
	}

}
