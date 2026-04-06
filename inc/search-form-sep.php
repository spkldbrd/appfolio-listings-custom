<?php

// Exit if accessed directly
if (! defined('ABSPATH')) {
	exit;
}

if (!function_exists('apfl_pp_search_frm')) {
	function apfl_pp_search_frm($atts)
	{
		$frm_action = $atts['action'] ?? '';
		global $client_listings_url;
		if (!$client_listings_url) return '<p>The Appfolio URL is blank. Please contact site owner.</p>';

		$client_listings_url = str_replace('/listings', '', $client_listings_url);
		$last_char = substr($client_listings_url, -1);
		if ($last_char == '/') {
			$client_listings_url = substr($client_listings_url, 0, -1);
		}
		$url = $client_listings_url . '/listings';

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
		if (!$html || !$html->root) return '';

		$filters = $html->find('.filter-menu', 0);
		$topbar = $html->find('.topbar', 0);

		$render_html = '<form class="apfl-form" action="' . esc_url($frm_action) . '" method="post">';
		$render_html .= wp_nonce_field('apfl_filter_form_action', 'apfl_filter_nonce', true, false); 

		$apfl_template = (int)get_option('apfl_template');
		$template_data = $apfl_template ? get_option('apfl_template_' . $apfl_template . '_data') : [];

		$get_opt = function ($key, $default = 'hide') use ($apfl_template, $template_data) {
			return $apfl_template ? ($template_data[$key] ?? $default) : get_option($key, $default);
		};

		$filters_to_show = [
			'textarea_input' => $get_opt('apfl_filters_textarea_input', 'show'),
			'cat'            => $get_opt('apfl_filters_cat'),
			'dog'            => $get_opt('apfl_filters_dog'),
			'minrent'        => $get_opt('apfl_filters_minrent'),
			'maxrent'        => $get_opt('apfl_filters_maxrent'),
			'bed'            => $get_opt('apfl_filters_bed'),
			'bath'           => $get_opt('apfl_filters_bath'),
			'cities'         => $get_opt('apfl_filters_cities'),
			'zip'            => $get_opt('apfl_filters_zip'),
			'movein'         => $get_opt('apfl_filters_movein'),
			'sorting'        => $get_opt('apfl_filters_sorting'),
		];

		$def_sort = $apfl_template ? ($template_data['apfl_listings_def_sort'] ?? '') : get_option('apfl_listings_def_sort');
		$rent_text = get_option('apfl_rent_text', 'Rent');

		$search_btn_text_color = $get_opt('apfl_listings_search_color', '#ffffff');
    	$search_btn_bg_color   = $get_opt('apfl_listings_search_bg', '#0073aa');


		$render_field = function ($condition, $name, $innerhtml, $orig_name = '', $placeholder = '', $type = 'select') use (&$render_html) {
			if (!$condition) return;

			$field_class = "apfl-field apfl-{$name}";

			if ($type === 'select' && $innerhtml) {
				$render_html .= "<div class='{$field_class}'><select name='{$name}'>{$innerhtml}</select></div>";

                if($orig_name) {
                    $render_html .= "<input type='hidden' value='" . esc_attr($innerhtml) . "' name='". $orig_name ."'>";
                }
			} elseif ($type === 'search') {
				$render_html .= "<div class='{$field_class}'><input type='search' name='{$name}' placeholder='{$placeholder}'></div>";
			} elseif ($type === 'text') {
				$render_html .= "<div class='{$field_class}'><input type='text' name='{$name}' placeholder='{$placeholder}'></div>";
			}
		};

		if ($filters_to_show['textarea_input'] === 'show') {
			$render_html .= "<div class='apfl-field apfl-textarea_input apfl-fullwidth'><input type='search' name='filters[textarea_input]' placeholder='Search by address...'></div>";
		}


		if ($filters_to_show['minrent'] == 'show' && ($r = $filters->find('#filters_market_rent_from', 0))) {
			$opt = preg_replace('/<option[^>]*>.*?<\/option>/s', "<option value=''>Min $rent_text</option>", stripslashes($r->innertext), 1);
			$render_field(true, 'filters[market_rent_from]', $opt, 'orig_min_rent');
		}

		if ($filters_to_show['maxrent'] == 'show' && ($r = $filters->find('#filters_market_rent_to', 0))) {
			$opt = preg_replace('/<option[^>]*>.*?<\/option>/s', "<option value=''>Max $rent_text</option>", stripslashes($r->innertext), 1);
			$render_field(true, 'filters[market_rent_to]', $opt, 'orig_max_rent');
		}

		if ($filters_to_show['bed'] == 'show' && ($r = $filters->find('#filters_bedrooms', 0))) {
			$opt = str_replace('0+', 'Beds', stripslashes($r->innertext));
			$render_field(true, 'filters[bedrooms]', $opt, 'orig_beds');
		}

		if ($filters_to_show['bath'] == 'show' && ($r = $filters->find('#filters_bathrooms', 0))) {
			$opt = str_replace('0+', 'Baths', stripslashes($r->innertext));
			$render_field(true, 'filters[bathrooms]', $opt, 'orig_baths');
		}

		if ($filters_to_show['cities'] == 'show' && ($r = $filters->find('#filters_cities', 0))) {
			$render_field(true, 'filters[cities][]', stripslashes($r->innertext), 'orig_cities');
		}

		if ($filters_to_show['zip'] == 'show' && ($r = $filters->find('#filters_postal_codes', 0))) {
			$render_field(true, 'filters[postal_codes][]', stripslashes($r->innertext), 'orig_zip');
		}

		if ($filters_to_show['movein'] == 'show') {
			$render_html .= '<div class="apfl-field apfl-movein"><input type="date" name="filters[desired_move_in]" placeholder="Desired Move In Date"></div>';
		}

		if ($filters_to_show['cat'] == 'show' && ($r = $filters->find('#filters_cats', 0))) {
			$opt = preg_replace('/<option[^>]*>.*?<\/option>/s', '<option value="">Cats</option>', stripslashes($r->innertext), 1);
			$render_field(true, 'filters[cats]', $opt, 'orig_cats');
		}

		if ($filters_to_show['dog'] == 'show' && ($r = $filters->find('#filters_dogs', 0))) {
			$opt = preg_replace('/<option[^>]*>.*?<\/option>/s', '<option value="">Dogs</option>', stripslashes($r->innertext), 1);
			$render_field(true, 'filters[dogs]', $opt, 'orig_dogs');
		}

		if ($filters_to_show['sorting'] == 'show' && ($r = $topbar->find('#filters_order_by', 0))) {
			$opt = stripslashes($r->innertext);
			$opt = preg_replace('/<option value="rent_asc">.*?<\/option>/s', "<option value=\"rent_asc\">$rent_text (Low to High)</option>", $opt);
			$opt = preg_replace('/<option value="rent_desc">.*?<\/option>/s', "<option value=\"rent_desc\">$rent_text (High to Low)</option>", $opt);
			$opt .= '<option value="availability">Availability</option>';
			if ($def_sort) {
				$opt = str_replace('value="' . $def_sort . '"', 'value="' . $def_sort . '" selected="selected"', $opt);
			}
			$render_field(true, 'filters[order_by]', $opt, 'orig_sort_dd');
		}

		$render_html .= '<div class="apfl-actions">
                            <input type="submit" name="fltr-submt" value="Search" class="apfl-submit" style="color: ' . esc_attr($search_btn_text_color) . '; background-color: ' . esc_attr($search_btn_bg_color) . ';">
                        </div>';

		$render_html .= '</form>';

		return $render_html;
	}
}
