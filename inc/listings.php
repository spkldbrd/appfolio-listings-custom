<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

if (!function_exists('apfl_pp_display_all_listings')) {
	function apfl_pp_display_all_listings($atts)
	{

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (!isset($_POST['apfl_filter_nonce']) || !wp_verify_nonce($_POST['apfl_filter_nonce'], 'apfl_filter_form_action')) {
				return '';
			}
		}

		$custom_url = '';
		if ($atts && isset($atts['url'])) {
			$custom_url = $atts['url'];
		}

		$single_url = '';
		if ($atts && isset($atts['single_url'])) {
			$single_url = $atts['single_url'];
		}

		$map = '';
		if ($atts && isset($atts['map'])) {
			$map = $atts['map'];
		}

        $limit = '';
		if ($atts && isset($atts['limit'])) {
			$limit = (int)$atts['limit'];
		}

		$shortcode_columns = null;
		if ($atts && isset($atts['columns']) && $atts['columns'] !== '') {
			$shortcode_columns = max(1, min(5, (int) $atts['columns']));
		}

        $filters = '';
		if ($atts && isset($atts['filters'])) {
			$filters = $atts['filters'];
		}

		$show_heading_val = 'yes';
		if ($atts && array_key_exists('show_heading', $atts) && $atts['show_heading'] !== '') {
			$show_heading_val = strtolower(trim((string) $atts['show_heading']));
		}
		$apfl_sc_show_page_heading = !in_array($show_heading_val, array('no', '0', 'false', 'off', 'hide'), true);

		$shortcode_city_tokens = array();
		if ($atts && isset($atts['city']) && (string) $atts['city'] !== '') {
			foreach (explode(',', (string) $atts['city']) as $part) {
				$t = sanitize_text_field(trim($part));
				if ($t !== '') {
					$shortcode_city_tokens[] = $t;
				}
			}
		}

		$shortcode_empty_message = '';
		if ($atts && isset($atts['empty_message']) && (string) $atts['empty_message'] !== '') {
			$shortcode_empty_message = trim((string) $atts['empty_message']);
		}

		$render_html = '';

		if($single_url) {
			$render_html = apfl_pp_display_single_listing('', $single_url);
			return $render_html;
		} else if (isset($_GET['lid'])) {
			$render_html = apfl_pp_display_single_listing($custom_url);
			return $render_html;
		} else {
			global $apfl_plugin_url;
			global $client_listings_url;
			global $client_gmap_api;

			if (!$client_listings_url && !$custom_url) {
				return '<p>The Appfolio URL is blank. Please contact site owner.</p>';
			}

			$property_type = '';
			if ($atts && isset($atts['type'])) {
				$property_type = $atts['type'];
			}

			$apfl_template = (int) get_option('apfl_template');
			if ($apfl_template) {
				$template_data = get_option('apfl_template_' . $apfl_template . '_data');
			}
			if ($apfl_template == 1) {
				$render_html .= '<div id="apfl-listings-container" class="main-listings-page apfl-hawk-template" style="width: 100%; max-width: 100%;">';
			} else {
				$render_html .= '<div id="apfl-listings-container" class="main-listings-page apfl-eagle-template" style="width: 100%; max-width: 100%;">';
			}

			if ($custom_url) {
				$client_listings_url = $custom_url;
			}
			
			$client_listings_url = str_replace('/listings', '', $client_listings_url);

			$last_char = substr($client_listings_url, -1);
			if ($last_char == '/') {
				$client_listings_url = substr($client_listings_url, 0, -1);
			}

			$set = 0;
			$params = '';
			$is_def_order = 1;
			$def_sort_order = '';
			$textarea_input = "";

			if ($property_type != '') {
				$set = 1;
				$property_type = urlencode($property_type);
				$params .= '&filters[property_list]=' . $property_type;
			}

			// Filters
			if (isset($_POST['fltr-submt'])) {
				if (isset($_POST['filters'])) {
					foreach ($_POST['filters'] as $fltr_key => $fltr_val) {
						$fltr_key = sanitize_text_field($fltr_key);
						if ($fltr_key == 'cities' || $fltr_key == 'postal_codes') {
							if ($fltr_val) {
								$set = 1;
								foreach ($fltr_val as $val) {
									$val = sanitize_text_field($val);

									$params .= '&filters[' . $fltr_key . '][]=' . urlencode($val);

								}
							}
						} else {
							if ($fltr_key == "textarea_input") {
								continue;
							}
							$fltr_val = sanitize_text_field($fltr_val);
							if ($fltr_val) {
								$set = 1;
								if ($fltr_val != "availability") {
									$params .= '&filters[' . $fltr_key . ']=' . urlencode($fltr_val);
								}

								if ($fltr_key == 'order_by') {
									$is_def_order = 0;
								}

							}
						}
					}
				}
			}

			if (!isset($_POST['fltr-submt']) && $shortcode_city_tokens) {
				foreach ($shortcode_city_tokens as $city_token) {
					$set = 1;
					$params .= '&filters[cities][]=' . urlencode($city_token);
				}
			}

			// Sorting
			if ($is_def_order) {
				$def_sort = '';
				if (!$apfl_template) {
					$def_sort = get_option('apfl_listings_def_sort');
				} else {
					if (array_key_exists("apfl_listings_def_sort", $template_data)) {
						$def_sort = $template_data['apfl_listings_def_sort'];

					}
				}

				if ($def_sort) {
					$set = 1;
					$def_sort_order = urlencode($def_sort);
					if ($def_sort_order != "availability") {
						$params .= '&filters[order_by]=' . $def_sort_order;
					}

				}
			}

			$url = $client_listings_url . '/listings' . ($set ? '?' . ltrim($params, '&') : '');

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

				$listing_items = $html->find('#result_container .listing-item');
				$total_items = $listing_items ? count($listing_items) : 0;

				if($total_items) {
					// $render_html .= '<div class="apfl-listings-count">' . $total_items . ' listings found</div>'; // Commenting for now - need better design and placement.
				}

				$listings = array();
				$listing_title = '';
				$listing_page_hdng = apply_filters("apfl_page_hdng", '');

				if ($apfl_template) {
					$apfl_page_sub_hdng = isset($template_data['apfl_page_sub_hdng']) ? $template_data['apfl_page_sub_hdng'] : '';
				} else {
					$apfl_page_sub_hdng = get_option('apfl_page_sub_hdng') ? get_option('apfl_page_sub_hdng') : '';
				}

				$apfl_listings_banner_image_url = '';
				$has_listings_banner = false;
				if ($apfl_template) {
					$apfl_listings_banner_image_url = get_option('apfl_listings_banner_image_url_' . $apfl_template, true);
					$has_listings_banner = (
						$apfl_listings_banner_image_url
						&& isset($template_data['apfl_listings_banner_image'])
						&& $template_data['apfl_listings_banner_image'] == 'show'
					);
				}

				$wrap_listing_filters = ($filters != 'hide')
					|| ($apfl_sc_show_page_heading && ($listing_page_hdng || $apfl_page_sub_hdng))
					|| $has_listings_banner;

				if ($wrap_listing_filters) {
					if ($has_listings_banner) {
						$render_html .= '<div class="listing-filters" style="background-image: url(\'' . esc_url($apfl_listings_banner_image_url) . '\'); background-position: center;">';
					} else {
						$render_html .= '<div class="listing-filters">';
					}
				}

				if ($apfl_sc_show_page_heading && $listing_page_hdng) {
					$render_html .= '<div class="apfl_page_hdng">' . $listing_page_hdng . '</div>';
				}

				if ($apfl_sc_show_page_heading && $apfl_page_sub_hdng) {
					$render_html .= '<div class="apfl_page_sub_hdng">' . $apfl_page_sub_hdng . '</div>';
				}

				if($filters != 'hide') {
                    $listing_filters = $html->find('.filter-menu', 0);

                    $rent_min = $rent_max = $filters_bedrooms = $filters_bathrooms = $filters_cities = $filters_zip = $filters_cats = $filters_dogs = $filters_movein = $sort_dd = '';

                    if($listing_filters) {
                        $rent_min = $listing_filters->find('#filters_market_rent_from', 0);
                        $rent_max = $listing_filters->find('#filters_market_rent_to', 0);
                        $filters_bedrooms = $listing_filters->find('#filters_bedrooms', 0);
                        $filters_bathrooms = $listing_filters->find('#filters_bathrooms', 0);
                        $filters_cities = $listing_filters->find('#filters_cities', 0);
                        $filters_zip = $listing_filters->find('#filters_postal_codes', 0);
                        $filters_cats = $listing_filters->find('#filters_cats', 0);
                        $filters_dogs = $listing_filters->find('#filters_dogs', 0);
                        $filters_movein = $listing_filters->find('#filters_desired_move_in', 0);	
                    }
				
                    $topbar = $html->find('.topbar', 0);
                    $sort_dd = '';
                    if($topbar) {
                        $sort_dd = $topbar->find('#filters_order_by', 0);
                    }

                    if (!$apfl_template) {
                        $apfl_pro_enable_searching = get_option('apfl_pro_enable_searching');
                        $apfl_filters_cat = get_option('apfl_filters_cat');
                        $apfl_filters_dog = get_option('apfl_filters_dog');
                        $apfl_filters_minrent = get_option('apfl_filters_minrent');
                        $apfl_filters_maxrent = get_option('apfl_filters_maxrent');

                        $apfl_filters_bed = get_option('apfl_filters_bed');
                        $apfl_filters_bath = get_option('apfl_filters_bath');
                        $apfl_filters_cities = get_option('apfl_filters_cities');
                        $apfl_filters_zip = get_option('apfl_filters_zip');
                        $apfl_filters_movein = get_option('apfl_filters_movein');
                        $apfl_filters_textarea_input = get_option('apfl_filters_textarea_input');

                        $apfl_filters_sorting = get_option('apfl_filters_sorting');

                        $apfl_display_city_state = get_option('apfl_display_city_state') ?: 'hide';

                    } else {
                        $apfl_filters_cat = $template_data['apfl_filters_cat'];
                        $apfl_filters_dog = $template_data['apfl_filters_dog'];
                        $apfl_filters_minrent = $template_data['apfl_filters_minrent'];
                        $apfl_filters_maxrent = $template_data['apfl_filters_maxrent'];

                        $apfl_filters_bed = $template_data['apfl_filters_bed'];
                        $apfl_filters_bath = $template_data['apfl_filters_bath'];
                        $apfl_filters_cities = $template_data['apfl_filters_cities'];
                        $apfl_filters_zip = $template_data['apfl_filters_zip'];
                        if (isset($template_data['apfl_pro_enable_searching'])) {
                            $apfl_pro_enable_searching = $template_data['apfl_pro_enable_searching'];
                        } else {
                            $apfl_pro_enable_searching = 'show';
                        }
                        if (isset($template_data['apfl_filters_movein'])) {
                            $apfl_filters_movein = $template_data['apfl_filters_movein'];
                        } else {
                            $apfl_filters_movein = 'hide';
                        }
                        if (isset($template_data['apfl_filters_textarea_input'])) {
                            $apfl_filters_textarea_input = $template_data['apfl_filters_textarea_input'];
                        } else {
                            $apfl_filters_textarea_input = 'hide';
                        }
                        $apfl_filters_sorting = $template_data['apfl_filters_sorting'];

                        $apfl_display_city_state = isset($template_data['apfl_display_city_state']) ? $template_data['apfl_display_city_state'] : 'hide';


                    }

                    if($apfl_pro_enable_searching == 'show') {

                        $allowed_html = array(
                            'select' => array(
                                'class' => true,
                                'name' => true,
                                'data-show-state' => true,
                            ),
                            'option' => array(
                                'value' => true,
                                'selected' => true,
                            ),
                            'optgroup' => array(
                                'label' => true,
                            ),
                        );
                        
                        $render_html .= '<form method="post" action="">'; 
                        $render_html .= wp_nonce_field('apfl_filter_form_action', 'apfl_filter_nonce', true, false); 

                        // Filters
                        $textarea_input = "";
                        if (isset($_POST['filters']['textarea_input']) && strlen($_POST['filters']['textarea_input']) > 0) {
                            $textarea_input = sanitize_text_field($_POST['filters']['textarea_input']);
                        }

                        if ($apfl_filters_textarea_input == 'show') {
                            if (isset($_POST['filters']['textarea_input'])) {

                                $selected = sanitize_text_field($_POST['filters']['textarea_input']);
                                if ($selected) {
                                    $render_html .= '<input type="search"  name="filters[textarea_input]" value="' . $selected . '" placeholder="' . $selected . '">';
                                } else {
                                    $render_html .= '<input type="search"  name="filters[textarea_input]" placeholder="Search by address...">';
                                }
                            } else {
                                $render_html .= '<input type="search"  name="filters[textarea_input]" placeholder="Search by address...">';
                            }
                        }

                        $rent_text = get_option('apfl_rent_text', 'Rent');
                        if ($apfl_filters_minrent == 'show') {
                            if (isset($_POST['orig_min_rent'])) {
                                $correct_min_rent_raw = wp_unslash($_POST['orig_min_rent']);
                                $correct_min_rent = wp_kses($correct_min_rent_raw, $allowed_html);
                        
                                $render_html .= "<input type='hidden' value='" . esc_attr($correct_min_rent) . "' name='orig_min_rent'>";
                        
                                $correct_min_rent = preg_replace('/<option[^>]*>.*?<\/option>/s', 
                                    '<option value="">Min' . $rent_text . '</option>', 
                                    $correct_min_rent, 1
                                );
                        
                                $selected = sanitize_text_field($_POST['filters']['market_rent_from'] ?? '');
                                $str_to_replace = 'value="' . esc_attr($selected) . '"';
                                $str_to_replace_by = 'value="' . esc_attr($selected) . '" selected="selected"';
                                
                                $render_html .= '<select name="filters[market_rent_from]">' . 
                                    str_replace($str_to_replace, $str_to_replace_by, $correct_min_rent) . 
                                    '</select>';
                            } else {
                                if ($rent_min) {
                                    $correct_min_rent_raw = wp_unslash($rent_min->innertext);
                                    $correct_min_rent = wp_kses($correct_min_rent_raw, $allowed_html);
                        
                                    $correct_min_rent = preg_replace('/<option[^>]*>.*?<\/option>/s', 
                                        '<option value="">Min ' . $rent_text . '</option>', 
                                        $correct_min_rent, 1
                                    );
                        
                                    $render_html .= '<select name="filters[market_rent_from]">' . $correct_min_rent . '</select>';
                                    $render_html .= "<input type='hidden' value='" . esc_attr($correct_min_rent) . "' name='orig_min_rent'>";
                                }
                            }
                        }
                        
                        if ($apfl_filters_maxrent == 'show') {
                            if (isset($_POST['orig_max_rent'])) {
                                $correct_max_rent_raw = wp_unslash($_POST['orig_max_rent']);
                                $correct_max_rent = wp_kses($correct_max_rent_raw, $allowed_html);
                        
                                $render_html .= "<input type='hidden' value='" . esc_attr($correct_max_rent) . "' name='orig_max_rent'>";
                        
                                $correct_max_rent = preg_replace('/<option[^>]*>.*?<\/option>/s',
                                    '<option value="">Max ' . $rent_text . '</option>',
                                    $correct_max_rent, 1
                                );
                        
                                $selected = sanitize_text_field($_POST['filters']['market_rent_to'] ?? '');
                                $str_to_replace = 'value="' . esc_attr($selected) . '"';
                                $str_to_replace_by = 'value="' . esc_attr($selected) . '" selected="selected"';
                        
                                $render_html .= '<select name="filters[market_rent_to]">' .
                                    str_replace($str_to_replace, $str_to_replace_by, $correct_max_rent) .
                                    '</select>';
                            } else {
                                if ($rent_max) {
                                    $correct_max_rent_raw = wp_unslash($rent_max->innertext);
                                    $correct_max_rent = wp_kses($correct_max_rent_raw, $allowed_html);
                        
                                    $correct_max_rent = preg_replace('/<option[^>]*>.*?<\/option>/s',
                                        '<option value="">Max ' . $rent_text . '</option>',
                                        $correct_max_rent, 1
                                    );
                        
                                    $render_html .= '<select name="filters[market_rent_to]">' . $correct_max_rent . '</select>';
                                    $render_html .= "<input type='hidden' value='" . esc_attr($correct_max_rent) . "' name='orig_max_rent'>";
                                }
                            }
                        }					

                        if ($apfl_filters_bed == 'show') {
                            if (isset($_POST['orig_beds'])) {
                                $correct_beds_raw = str_replace("0+", "Beds", wp_unslash($_POST['orig_beds']));
                                $correct_beds = wp_kses($correct_beds_raw, $allowed_html);
                        
                                $render_html .= "<input type='hidden' value='" . esc_attr($correct_beds) . "' name='orig_beds'>";
                        
                                $selected = sanitize_text_field($_POST['filters']['bedrooms'] ?? '');
                                $str_to_replace = 'value="' . esc_attr($selected) . '"';
                                $str_to_replace_by = 'value="' . esc_attr($selected) . '" selected="selected"';
                        
                                $render_html .= '<select name="filters[bedrooms]">' .
                                    str_replace($str_to_replace, $str_to_replace_by, $correct_beds) .
                                    '</select>';
                            } else {
                                if ($filters_bedrooms) {
                                    $correct_beds_raw = str_replace("0+", "Beds", wp_unslash($filters_bedrooms->innertext));
                                    $correct_beds = wp_kses($correct_beds_raw, $allowed_html);
                        
                                    $render_html .= '<select name="filters[bedrooms]">' . $correct_beds . '</select>';
                                    $render_html .= "<input type='hidden' value='" . esc_attr($correct_beds) . "' name='orig_beds'>";
                                }
                            }
                        }					

                        if ($apfl_filters_bath == 'show') {
                            if (isset($_POST['orig_baths'])) {
                                $correct_baths_raw = str_replace("0+", "Baths", wp_unslash($_POST['orig_baths']));
                                $correct_baths = wp_kses($correct_baths_raw, $allowed_html);
                        
                                $render_html .= "<input type='hidden' value='" . esc_attr($correct_baths) . "' name='orig_baths'>";
                        
                                $selected = sanitize_text_field($_POST['filters']['bathrooms'] ?? '');
                                $str_to_replace = 'value="' . esc_attr($selected) . '"';
                                $str_to_replace_by = 'value="' . esc_attr($selected) . '" selected="selected"';
                        
                                $render_html .= '<select name="filters[bathrooms]">' .
                                    str_replace($str_to_replace, $str_to_replace_by, $correct_baths) .
                                    '</select>';
                            } else {
                                if ($filters_bathrooms) {
                                    $correct_baths_raw = str_replace("0+", "Baths", wp_unslash($filters_bathrooms->innertext));
                                    $correct_baths = wp_kses($correct_baths_raw, $allowed_html);
                        
                                    $render_html .= '<select name="filters[bathrooms]">' . $correct_baths . '</select>';
                                    $render_html .= "<input type='hidden' value='" . esc_attr($correct_baths) . "' name='orig_baths'>";
                                }
                            }
                        }					

                        if ($apfl_filters_cities === 'show') {
                            if (isset($_POST['orig_cities']) && isset($_POST['filters']['cities'][0])) {
                                $correct_cities_raw = wp_unslash($_POST['orig_cities']);
                                $correct_cities = wp_kses($correct_cities_raw, $allowed_html);
                        
                                $selected = sanitize_text_field($_POST['filters']['cities'][0]);
                                $str_to_replace = 'value="' . esc_attr($selected) . '"';
                                $str_to_replace_by = 'value="' . esc_attr($selected) . '" selected="selected"';
                        
                                $render_html .= "<input type='hidden' value='" . esc_attr($correct_cities) . "' name='orig_cities'>";
                                $render_html .= '<select class="apfl-city-fltr" data-show-state="' . esc_attr($apfl_display_city_state) . '" name="filters[cities][]">' . str_replace($str_to_replace, $str_to_replace_by, $correct_cities) . '</select>';
                        
                            } elseif ($filters_cities) {
                                $correct_cities_raw = wp_unslash($filters_cities->innertext);
                                $correct_cities = wp_kses($correct_cities_raw, $allowed_html);
                        
                                $render_html .= '<select class="apfl-city-fltr" data-show-state="' . esc_attr($apfl_display_city_state) . '" name="filters[cities][]">' . $correct_cities . '</select>';
                                $render_html .= "<input type='hidden' value='" . esc_attr($correct_cities) . "' name='orig_cities'>";
                            }
                        }

                        if ($apfl_filters_zip == 'show') {
                            if (isset($_POST['orig_zip'])) {
                                $correct_zip_raw = wp_unslash($_POST['orig_zip']);
                                $correct_zip = wp_kses($correct_zip_raw, $allowed_html);
                        
                                $render_html .= "<input type='hidden' value='" . esc_attr($correct_zip) . "' name='orig_zip'>";
                        
                                $selected = sanitize_text_field($_POST['filters']['postal_codes'][0] ?? '');
                                $str_to_replace = 'value="' . esc_attr($selected) . '"';
                                $str_to_replace_by = 'value="' . esc_attr($selected) . '" selected="selected"';
                        
                                $render_html .= '<select name="filters[postal_codes][]">' .
                                    str_replace($str_to_replace, $str_to_replace_by, $correct_zip) .
                                    '</select>';
                            } else {
                                if ($filters_zip) {
                                    $correct_zip_raw = wp_unslash($filters_zip->innertext);
                                    $correct_zip = wp_kses($correct_zip_raw, $allowed_html);
                        
                                    $render_html .= '<select name="filters[postal_codes][]">' . $correct_zip . '</select>';
                                    $render_html .= "<input type='hidden' value='" . esc_attr($correct_zip) . "' name='orig_zip'>";
                                }
                            }
                        }					

                        if ($apfl_filters_cat == 'show') {
                            if (isset($_POST['orig_cats'])) {
                                $correct_cats_raw = wp_unslash($_POST['orig_cats']);
                                $correct_cats = preg_replace('/<option[^>]*>.*?<\/option>/s', '<option value="">Cats</option>', $correct_cats_raw, 1);
                                $correct_cats = wp_kses($correct_cats, $allowed_html);
                        
                                $render_html .= "<input type='hidden' value='" . esc_attr($correct_cats) . "' name='orig_cats'>";
                        
                                $selected = sanitize_text_field($_POST['filters']['cats']);
                                $str_to_replace = 'value="' . esc_attr($selected) . '"';
                                $str_to_replace_by = 'value="' . esc_attr($selected) . '" selected="selected"';
                        
                                $render_html .= '<select name="filters[cats]">' .
                                    str_replace($str_to_replace, $str_to_replace_by, $correct_cats) .
                                    '</select>';
                            } else {
                                if ($filters_cats) {
                                    $correct_cats_raw = wp_unslash($filters_cats->innertext);
                                    $correct_cats = preg_replace('/<option[^>]*>.*?<\/option>/s', '<option value="">Cats</option>', $correct_cats_raw, 1);
                                    $correct_cats = wp_kses($correct_cats, $allowed_html);
                        
                                    $render_html .= '<select name="filters[cats]">' . $correct_cats . '</select>';
                                    $render_html .= "<input type='hidden' value='" . esc_attr($correct_cats) . "' name='orig_cats'>";
                                }
                            }
                        }					

                        if ($apfl_filters_dog == 'show') {
                            if (isset($_POST['orig_dogs'])) {
                                $correct_dogs_raw = wp_unslash($_POST['orig_dogs']);
                                $correct_dogs = preg_replace('/<option[^>]*>.*?<\/option>/s', '<option value="">Dogs</option>', $correct_dogs_raw, 1);
                                $correct_dogs = wp_kses($correct_dogs, $allowed_html);
                        
                                $render_html .= "<input type='hidden' value='" . esc_attr($correct_dogs) . "' name='orig_dogs'>";
                        
                                $selected = sanitize_text_field($_POST['filters']['dogs']);
                                $str_to_replace = 'value="' . esc_attr($selected) . '"';
                                $str_to_replace_by = 'value="' . esc_attr($selected) . '" selected="selected"';
                        
                                $render_html .= '<select name="filters[dogs]">' .
                                    str_replace($str_to_replace, $str_to_replace_by, $correct_dogs) .
                                    '</select>';
                            } else {
                                if ($filters_dogs) {
                                    $correct_dogs_raw = wp_unslash($filters_dogs->innertext);
                                    $correct_dogs = preg_replace('/<option[^>]*>.*?<\/option>/s', '<option value="">Dogs</option>', $correct_dogs_raw, 1);
                                    $correct_dogs = wp_kses($correct_dogs, $allowed_html);
                        
                                    $render_html .= '<select name="filters[dogs]">' . $correct_dogs . '</select>';
                                    $render_html .= "<input type='hidden' value='" . esc_attr($correct_dogs) . "' name='orig_dogs'>";
                                }
                            }
                        }					

                        if ($apfl_filters_movein == 'show') {
                            if (isset($_POST['orig_movein'])) {
                                $selected = $_POST['filters']['desired_move_in'];

                                if ($selected) {
                                    $render_html .= '
                                    <div class="datepicker">
                                        <input class="datepicker-field" type="text" name="filters[desired_move_in]" value="' . $selected . '">
                                        <span class="datepicker-icon">
                                            <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21 10H3M16 2V6M8 2V6M7.8 22H16.2C17.8802 22 18.7202 22 19.362 21.673C19.9265 21.3854 20.3854 20.9265 20.673 20.362C21 19.7202 21 18.8802 21 17.2V8.8C21 7.11984 21 6.27976 20.673 5.63803C20.3854 5.07354 19.9265 4.6146 19.362 4.32698C18.7202 4 17.8802 4 16.2 4H7.8C6.11984 4 5.27976 4 4.63803 4.32698C4.07354 4.6146 3.6146 5.07354 3.32698 5.63803C3 6.27976 3 7.11984 3 8.8V17.2C3 18.8802 3 19.7202 3.32698 20.362C3.6146 20.9265 4.07354 21.3854 4.63803 21.673C5.27976 22 6.11984 22 7.8 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    </div>';
                                } else {
                                    $render_html .= '
                                    <div class="datepicker">
                                        <input class="datepicker-field" type="text" name="filters[desired_move_in]" placeholder="Desired Move In">
                                        <span class="datepicker-icon">
                                            <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21 10H3M16 2V6M8 2V6M7.8 22H16.2C17.8802 22 18.7202 22 19.362 21.673C19.9265 21.3854 20.3854 20.9265 20.673 20.362C21 19.7202 21 18.8802 21 17.2V8.8C21 7.11984 21 6.27976 20.673 5.63803C20.3854 5.07354 19.9265 4.6146 19.362 4.32698C18.7202 4 17.8802 4 16.2 4H7.8C6.11984 4 5.27976 4 4.63803 4.32698C4.07354 4.6146 3.6146 5.07354 3.32698 5.63803C3 6.27976 3 7.11984 3 8.8V17.2C3 18.8802 3 19.7202 3.32698 20.362C3.6146 20.9265 4.07354 21.3854 4.63803 21.673C5.27976 22 6.11984 22 7.8 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    </div>';
                                }
                            } else {
                                if($filters_movein) {
                                    $correct_movein = stripslashes($filters_movein->innertext);
                                    if ($filters_movein->value) {
                                        $render_html .= '
                                        <div class="datepicker">
                                            <input class="datepicker-field" type="text" name="filters[desired_move_in]" value="' . $filters_movein->value . '">
                                            <span class="datepicker-icon">
                                                <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M21 10H3M16 2V6M8 2V6M7.8 22H16.2C17.8802 22 18.7202 22 19.362 21.673C19.9265 21.3854 20.3854 20.9265 20.673 20.362C21 19.7202 21 18.8802 21 17.2V8.8C21 7.11984 21 6.27976 20.673 5.63803C20.3854 5.07354 19.9265 4.6146 19.362 4.32698C18.7202 4 17.8802 4 16.2 4H7.8C6.11984 4 5.27976 4 4.63803 4.32698C4.07354 4.6146 3.6146 5.07354 3.32698 5.63803C3 6.27976 3 7.11984 3 8.8V17.2C3 18.8802 3 19.7202 3.32698 20.362C3.6146 20.9265 4.07354 21.3854 4.63803 21.673C5.27976 22 6.11984 22 7.8 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </span>
                                        </div>';
                                    } else {
                                        $render_html .= '
                                        <div class="datepicker">
                                            <input class="datepicker-field" type="text" name="filters[desired_move_in]" placeholder="Desired Move In">
                                            <span class="datepicker-icon">
                                                <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M21 10H3M16 2V6M8 2V6M7.8 22H16.2C17.8802 22 18.7202 22 19.362 21.673C19.9265 21.3854 20.3854 20.9265 20.673 20.362C21 19.7202 21 18.8802 21 17.2V8.8C21 7.11984 21 6.27976 20.673 5.63803C20.3854 5.07354 19.9265 4.6146 19.362 4.32698C18.7202 4 17.8802 4 16.2 4H7.8C6.11984 4 5.27976 4 4.63803 4.32698C4.07354 4.6146 3.6146 5.07354 3.32698 5.63803C3 6.27976 3 7.11984 3 8.8V17.2C3 18.8802 3 19.7202 3.32698 20.362C3.6146 20.9265 4.07354 21.3854 4.63803 21.673C5.27976 22 6.11984 22 7.8 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </span>
                                        </div>';
                                    }
                                }

                            }
                        }

                        $multiple_types = '';
                        if ($atts && isset($atts['types'])) {
                            $multiple_types = $atts['types'];
                            if (strpos($multiple_types, ",") !== false) {
                                $multiple_types = explode(',', $multiple_types);
                            }

                            if (is_array($multiple_types)) {
                                $pl = '';
                                if (isset($_POST['filters']['property_list'])) {
                                    $pl = $_POST['filters']['property_list'];
                                }

                                $render_html .= '<select id="apfl_fltr_type" name="filters[property_list]"><option value="">Type</option>';
                                foreach ($multiple_types as $prop_type) {
                                    $render_html .= '<option value="' . $prop_type . '" ' . ($pl == $prop_type ? "selected" : "") . '>' . $prop_type . '</option>';
                                }
                                $render_html .= '</select>';
                            }
                        }

                        if ($apfl_filters_sorting === 'show') {
                            if (isset($_POST['orig_sort_dd'])) {
                                
                                $correct_sort_dd_raw = stripslashes($_POST['orig_sort_dd']);
                                $correct_sort_dd = wp_kses($correct_sort_dd_raw, $allowed_html);
                        
                                $correct_sort_dd = preg_replace(
                                    '/(<option value="rent_asc">).*?(<\/option>)/',
                                    '<option value="rent_asc">' . esc_html($rent_text) . ' (Low to High)</option>',
                                    $correct_sort_dd
                                );
                                $correct_sort_dd = preg_replace(
                                    '/(<option value="rent_desc">).*?(<\/option>)/',
                                    '<option value="rent_desc">' . esc_html($rent_text) . ' (High to Low)</option>',
                                    $correct_sort_dd
                                );
                        
                                $escaped_orig_sort_dd = htmlspecialchars($correct_sort_dd, ENT_QUOTES, 'UTF-8');
                                $render_html .= "<input type='hidden' value='{$escaped_orig_sort_dd}' name='orig_sort_dd'>";
                        
                                $selected = sanitize_text_field($_POST['filters']['order_by']);
                                $str_to_replace = 'value="' . esc_attr($selected) . '"';
                                $str_to_replace_by = 'selected="selected" value="' . esc_attr($selected) . '"';
                        
                                $correct_sort_dd = str_replace('selected="selected"', '', $correct_sort_dd);
                                $render_html .= '<select id="order_by_fltr" name="filters[order_by]">' . str_replace($str_to_replace, $str_to_replace_by, $correct_sort_dd) . '</select>';
                        
                            } elseif ($sort_dd) {
                                $correct_sort_dd = stripslashes($sort_dd->innertext);
                        
                                $correct_sort_dd = preg_replace(
                                    '/(<option value="rent_asc">).*?(<\/option>)/',
                                    '<option value="rent_asc">' . esc_html($rent_text) . ' (Low to High)</option>',
                                    $correct_sort_dd
                                );
                                $correct_sort_dd = preg_replace(
                                    '/(<option selected="selected" value="rent_asc">).*?(<\/option>)/',
                                    '<option selected="selected" value="rent_asc">' . esc_html($rent_text) . ' (Low to High)</option>',
                                    $correct_sort_dd
                                );
                                $correct_sort_dd = preg_replace(
                                    '/(<option value="rent_desc">).*?(<\/option>)/',
                                    '<option value="rent_desc">' . esc_html($rent_text) . ' (High to Low)</option>',
                                    $correct_sort_dd
                                );
                                $correct_sort_dd = preg_replace(
                                    '/(<option selected="selected" value="rent_desc">).*?(<\/option>)/',
                                    '<option selected="selected" value="rent_desc">' . esc_html($rent_text) . ' (High to Low)</option>',
                                    $correct_sort_dd
                                );
                        
                                if ($is_def_order) {
                                    if ($def_sort_order === 'availability') {
                                        $correct_sort_dd .= '<option selected="selected" value="availability">Availability</option>';
                                    } else {
                                        $correct_sort_dd .= '<option value="availability">Availability</option>';
                                    }
                                } else {
                                    $correct_sort_dd .= '<option value="availability">Availability</option>';
                                }
                        
                                $correct_sort_dd = wp_kses($correct_sort_dd, $allowed_html);
                        
                                $render_html .= '<select id="order_by_fltr" name="filters[order_by]">' . $correct_sort_dd . '</select>';
                                $escaped_sort_dd = htmlspecialchars($correct_sort_dd, ENT_QUOTES, 'UTF-8');
                                $render_html .= "<input type='hidden' value='{$escaped_sort_dd}' name='orig_sort_dd'>";
                            }
                        }									

                        $render_html .= '<input type="submit" value="SEARCH" name="fltr-submt">';

                        $render_html .= '</form>';
                    
                    }
                }

				if ($wrap_listing_filters) {
					$render_html .= '</div>';
				}

				// Google map for listings
				if ($map != 'hide' && $client_gmap_api) {
					$render_html .= '<div id="googlemap"></div>';
				}

				if ($listing_items) {
					
					$apfl_listings_pagination = 'hide';

					global $apfl_plugin_url;
					if (!$apfl_template) {
						$apfl_listings_display_price = get_option('apfl_listings_display_price');
						$apfl_listings_price_pos = get_option('apfl_listings_price_pos');

						$apfl_listings_display_avail = get_option('apfl_listings_display_avail');
						$apfl_listings_avail_pos = get_option('apfl_listings_avail_pos');

						$apfl_listings_display_ttl = get_option('apfl_listings_display_ttl');
						$apfl_listings_ttl_tag = get_option('apfl_listings_ttl_tag');

						$apfl_listings_display_address = get_option('apfl_listings_display_address');

						$apfl_listings_display_beds = get_option('apfl_listings_display_beds');
						$apfl_listings_bed_img = get_option('apfl_listings_bed_img');

						$apfl_listings_display_baths = get_option('apfl_listings_display_baths');
						$apfl_listings_bath_img = get_option('apfl_listings_bath_img');

						$apfl_listings_display_area = get_option('apfl_listings_display_area', 'show');
						$apfl_listings_area_img      = get_option('apfl_listings_area_img', $apfl_plugin_url . 'images/area.png');

						$apfl_listings_display_pets  = get_option('apfl_listings_display_pets', 'show');
						$apfl_listings_pet_img       = get_option('apfl_listings_pet_img', $apfl_plugin_url . 'images/pet.png');
						$apfl_listings_no_pet_img    = get_option('apfl_listings_no_pet_img', $apfl_plugin_url . 'images/no-pet.png');

						$apfl_listings_display_detail = get_option('apfl_listings_display_detail');

						$apfl_listings_display_apply = get_option('apfl_listings_display_apply');

						$apfl_listings_display_schedule = get_option('apfl_listings_display_schedule');
						$apfl_display_large_images = get_option('apfl_display_large_images');
						

						$apfl_columns_cnt = get_option('apfl_columns_cnt');
						
						$apfl_listings_pagination = get_option('apfl_listings_pagination');
						$apfl_listings_per_page = get_option('apfl_listings_per_page');


					} else {
						$apfl_listings_display_price = $template_data['apfl_listings_display_price'];
						$apfl_listings_price_pos = $template_data['apfl_listings_price_pos'];

						$apfl_listings_display_avail = $template_data['apfl_listings_display_avail'];
						$apfl_listings_avail_pos = $template_data['apfl_listings_avail_pos'];

						$apfl_listings_display_ttl = $template_data['apfl_listings_display_ttl'];
						$apfl_listings_ttl_tag = $template_data['apfl_listings_ttl_tag'];

						$apfl_listings_display_address = $template_data['apfl_listings_display_address'];

						$apfl_listings_display_beds = $template_data['apfl_listings_display_beds'];
						$apfl_listings_bed_img = $template_data['apfl_listings_bed_img'];

						$apfl_listings_display_baths = $template_data['apfl_listings_display_baths'];
						$apfl_listings_bath_img = $template_data['apfl_listings_bath_img'];

						$apfl_listings_display_area = isset($template_data['apfl_listings_display_area']) ? $template_data['apfl_listings_display_area'] : 'show';
						$apfl_listings_area_img      = isset($template_data['apfl_listings_area_img']) ? $template_data['apfl_listings_area_img'] : $apfl_plugin_url . 'images/area.png';

						$apfl_listings_display_pets  = isset($template_data['apfl_listings_display_pets']) ? $template_data['apfl_listings_display_pets'] : 'show';
						$apfl_listings_pet_img       = isset($template_data['apfl_listings_pet_img']) ? $template_data['apfl_listings_pet_img'] : $apfl_plugin_url . 'images/pet.png';
						$apfl_listings_no_pet_img    = isset($template_data['apfl_listings_no_pet_img']) ? $template_data['apfl_listings_no_pet_img'] : $apfl_plugin_url . 'images/no-pet.png';


						$apfl_listings_display_detail = $template_data['apfl_listings_display_detail'];

						$apfl_listings_display_apply = $template_data['apfl_listings_display_apply'];
						$apfl_listings_display_schedule = isset($template_data['apfl_listings_display_schedule']) ? $template_data['apfl_listings_display_schedule'] : 'hide';
						$apfl_display_large_images = isset($template_data['apfl_display_large_images']) ? $template_data['apfl_display_large_images'] : 'hide';
						
						$apfl_columns_cnt = '';
						if (array_key_exists("apfl_columns_cnt", $template_data)) {
							$apfl_columns_cnt = $template_data['apfl_columns_cnt'];
						}

						if (array_key_exists("apfl_listings_pagination", $template_data)) {
							$apfl_listings_pagination = $template_data['apfl_listings_pagination'];
						}
						
						$apfl_listings_per_page = '';
						if (array_key_exists("apfl_listings_per_page", $template_data)) {
							$apfl_listings_per_page = $template_data['apfl_listings_per_page'];
						}

					}

					if (!$apfl_columns_cnt) {
						$apfl_columns_cnt = 3;
					}

					if ($shortcode_columns !== null) {
						$apfl_columns_cnt = $shortcode_columns;
					}

					if ((int) $apfl_columns_cnt === 1 && $shortcode_columns === null) {
						$apfl_columns_cnt = 2;
					}

					if (!$apfl_listings_per_page) {
						$apfl_listings_per_page = 10;
					}

					$apfl_col_class = apfl_pp_column_class_for_grid($apfl_columns_cnt);

					$render_html .= '<div class="all-listings" data-apfl-cols="' . esc_attr((string) (int) $apfl_columns_cnt) . '">';

					$i = 0;
					$listings_arr = array();
					$title_map = array();  // Used in map to get title
					foreach ($listing_items as $listing) {

                        if($limit && $i >= $limit) break;

						$listing_html = '';
						$db = array();
						$db['bed'] = 'N/A';
						$db['bath'] = 'N/A';
						$db["Square Feet"] = 'N/A';
						$db['pets'] = "no";
						$listingItemBody = $listing->find('.listing-item__body', 0);
						$listingItemAction = $listing->find('.listing-item__actions', 0);
						$listing_Img_obj = $listing->find('img.listing-item__image', 0);
						if ($listing_Img_obj) {
							$listing_Img = $listing_Img_obj->{'data-original'};
						}
						if ($listingItemBody) {
							foreach ($listingItemBody->find('.detail-box__item') as $db_itm) {
								$label = $db_itm->find('.detail-box__label', 0)->innertext;
								$val = $db_itm->find('.detail-box__value', 0)->innertext;
								if ($label == 'Bed / Bath') {
									if (strpos($val, 'bd') !== false) {
										$beds = explode(' bd / ', $val);
										$db['bed'] = $beds[0] . ' Beds';
									}
									if (strpos($val, 'Studio') !== false) {
										$beds = explode('Studio / ', $val);
										$db['bed'] = 'Studio';
									}
									if (strpos($val, 'ba') !== false) {
										$baths = explode(' ba', $beds[1]);
										$db['bath'] = $baths[0] . ' Baths';
									}
								} else {
									$db[$label] = $val;
								}
							}

							$listing_title_obj = $listingItemBody->find('.js-listing-title a', 0);
							if ($listing_title_obj) {
								$listing_title = $listing_title_obj->plaintext;
							}

							$listing_Address_obj = $listingItemBody->find('.js-listing-address', 0);
							if ($listing_Address_obj) {
								$listing_Address = $listing_Address_obj->plaintext;
							}

							$listing_Description_obj = $listingItemBody->find('.js-listing-description', 0);
							if ($listing_Description_obj) {
								$listing_Description = $listing_Description_obj->plaintext;
							}

							$listing_Pet_policy_obj = $listingItemBody->find('.js-listing-pet-policy', 0);
							if ($listing_Pet_policy_obj) {
								$listing_Pet_policy = $listing_Pet_policy_obj->plaintext;
								$db['pets'] = 'yes';
							}
						}
						$listing_ID = '';
						$listing_Apply_Link = '';
						if ($listingItemAction) {
							$listing_Details_Link = $listingItemAction->find('.js-link-to-detail', 0)->href;
							$listing_ID = basename($listing_Details_Link);
							$listing_Apply_Link = $listingItemAction->find('.js-listing-apply', 0);
							if ($listing_Apply_Link) {
								$listing_Apply_Link = $listing_Apply_Link->href;
								$listing_Apply_Link = $client_listings_url . $listing_Apply_Link;
							}
						}

						$listing_Apply_Link = apply_filters("apfl_apply_btn_link", $listing_Apply_Link, $listing_Apply_Link);

						$schshowing_btn_link = '';
						if ($listing_ID) {
							$schshowing_btn_link = $client_listings_url . '/listings/showings/new?listable_uid=' . $listing_ID;
						}

						$lstng_dtl_page = '';

						$apfl_sngl_lstngs_page = get_option('apfl_sngl_lstngs_page');

						if ($apfl_sngl_lstngs_page) {
							$lstng_dtl_page = $apfl_sngl_lstngs_page;
						}

						$ref = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
						if ($ref) {
							$ref = urlencode($ref . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
						}

						if ($apfl_display_large_images == 'show') {
							$listing_Img = str_replace("medium", "large", $listing_Img);
						}

						$listing_html .= '<a href="' . $lstng_dtl_page . '?lid=' . $listing_ID . '&url=' . $custom_url . '&ref=' . $ref . '">
							<div class="list-img">
								<img src="' . $listing_Img . '">';

						if ($apfl_listings_display_price == 'show' && $apfl_listings_price_pos == 'onimage') {
                            $listing_html .= '<span class="rent-price">' . ($db['RENT'] ?? $db['ROOM RENT'] ?? '') . '</span>';
						}
						if ($apfl_listings_display_avail == 'show' && $apfl_listings_avail_pos == 'onimage') {
							if (array_key_exists('Available', $db)) {
								$listing_html .= '<span class="lstng-avail">Available ' . $db["Available"] . '</span>';
							}
						}

						if ($apfl_template == 1) {
							if ($apfl_listings_display_address == 'show') {
								$listing_html .= '<h3 class="address">' . $listing_Address . '</h3>';
							}
						}

						$listing_html .= '</div></a>
							<div class="details">';

						if ($apfl_listings_display_price == 'show' && $apfl_listings_price_pos == 'offimage') {
							if ($apfl_template == 1) {
								$apfl_rent_text = get_option('apfl_rent_text', 'RENT');
								$listing_html .= '<span class="rent-price-off">' . esc_html($apfl_rent_text) . ' <b>' . ($db['RENT'] ?? $db['ROOM RENT'] ?? '') . '</b></span>';
							} else {
								$listing_html .= '<span class="rent-price-off">' . ($db['RENT'] ?? $db['ROOM RENT'] ?? '') . '</span>';
							}
						}
						if ($apfl_listings_display_avail == 'show' && $apfl_listings_avail_pos == 'offimage') {
							if (array_key_exists('Available', $db)) {
								$listing_html .= '<span class="lstng-avail-off">Available ' . $db["Available"] . '</span>';
							}
						}

						if ($apfl_listings_display_ttl == 'show') {
							if (!$apfl_listings_ttl_tag) {
								$apfl_listings_ttl_tag = 'h2';
							}
							$listing_html .= '<' . $apfl_listings_ttl_tag . ' class="lstng_ttl">' . $listing_title . '</' . $apfl_listings_ttl_tag . '>';
						}

						if ($apfl_template != 1) {
							if ($apfl_listings_display_address == 'show') {
								$listing_html .= '<h3 class="address">' . $listing_Address . '</h3>';
							}
						}

						$listing_html .= '<p>';
						if ($apfl_listings_display_beds == 'show') {
							if ($apfl_listings_bed_img) {
								$listing_html .= '<img class="bedimg" src="' . $apfl_listings_bed_img . '">';
							}
							$listing_html .= '<span class="beds">' . $db["bed"] . '</span> ';
						}
						if ($apfl_listings_display_baths == 'show') {
							if ($apfl_listings_bath_img) {
								$listing_html .= '<img class="bathimg" src="' . $apfl_listings_bath_img . '">';
							}
							$listing_html .= '<span class="baths">' . $db["bath"] . '</span>';
						}
						if ($apfl_listings_display_area == 'show') {
							if ($apfl_listings_area_img) {
								$listing_html .= '<img class="areaimg" src="' . $apfl_listings_area_img . '">';
							}
							$listing_html .= '<span class="area">' . $db["Square Feet"] . ' Sq. Ft.</span>';
						}
						if ($apfl_listings_display_pets == 'show') {
							if ($db['pets'] =='yes' && $apfl_listings_pet_img) {
								$listing_html .= '<img class="petimg" src="' . $apfl_listings_pet_img . '">';
							}
							if ($db['pets'] =='no' && $apfl_listings_no_pet_img) {
								$listing_html .= '<img class="nopetimg" src="' . $apfl_listings_no_pet_img . '">';
							}
						}
						$listing_html .= '</p><div class="btns">';

						$first_btn = "more_detail_btn";
						$second_btn = "apply_btn";
						$third_btn = "schedule_btn";


						if ($apfl_listings_display_detail == 'show') {
							$listing_html .= '<a class="' . $first_btn . '" href="' . $lstng_dtl_page . '?lid=' . $listing_ID . '&url=' . $custom_url . '&ref=' . $ref . '">Details</a>';
						}
						if ($listing_Apply_Link && $apfl_listings_display_apply == 'show') {
							$listing_html .= '<a class="' . $second_btn . '" href="' . $listing_Apply_Link . '" target="_blank">Apply</a>';
						}
						if ($apfl_listings_display_schedule == 'show') {

							if (($apfl_listings_display_apply == 'show' && $apfl_listings_display_detail == 'show') || ($apfl_listings_display_apply == 'hide' && $apfl_listings_display_detail == 'hide')) {
								if ($apfl_template == 1)
									$listing_html .= '<a class="' . $third_btn . '" href="' . $schshowing_btn_link . '" style="width: 100%;" target="_blank">Schedule Showing</a>';
								else
									$listing_html .= '<a class="' . $third_btn . '" href="' . $schshowing_btn_link . '" target="_blank">Schedule Showing</a>';
							} else {
								if ($apfl_template == 1)
									$listing_html .= '<a class="' . $third_btn . '" href="' . $schshowing_btn_link . '" style="width: 50%;" target="_blank">Schedule</a>';
								else
									$listing_html .= '<a class="' . $third_btn . '" href="' . $schshowing_btn_link . '" target="_blank">Schedule</a>';
							}

						}
						$listing_html .= '</div>
							</div>';

						$listings_arr[$i]['html'] = $listing_html;
						$listings_arr[$i]['address'] = $listing_Address;
						$listings_arr[$i]['title'] = $listing_title;
						if (array_key_exists('Available', $db)) {
							$listings_arr[$i]['avail'] = $db["Available"];
						}

						$id_attr = $listing->getAttribute('id');
						if (preg_match('/listing_(\d+)/', $id_attr, $matches)) {
							$list_id = $matches[1]; 
							$title_map[$list_id] = $listing_title;
						}

						$i++;
					} // loop


					// Sort by avail date
					if (isset($_POST['filters']['order_by'])) {
						// If avail selected in $_post
						$order_by = sanitize_text_field($_POST['filters']['order_by']);
						if ($order_by == 'availability') {
							usort($listings_arr, function ($a, $b) {
								// Check if 'avail' key exists in both $a and $b
								$availA = isset ($a['avail']) ? strtotime($a['avail']) : PHP_INT_MAX;
								$availB = isset ($b['avail']) ? strtotime($b['avail']) : PHP_INT_MAX;

								return $availA - $availB;
							});
						}
					} else {
						// If avail selected in default sort
						if ($is_def_order) {
							if ($def_sort) {
								$def_sort_order = urlencode($def_sort);
								if ($def_sort_order == 'availability') {
									usort($listings_arr, function ($a, $b) {
										// Check if 'avail' key exists in both $a and $b
										$availA = isset ($a['avail']) ? strtotime($a['avail']) : PHP_INT_MAX;
										$availB = isset ($b['avail']) ? strtotime($b['avail']) : PHP_INT_MAX;

										return $availA - $availB;
									});
								}
							}
						}
					}
					
					$itm_cntr = 0;
					$itm_searched = 0;

					foreach ($listings_arr as $key => $val) {

						// if ($apfl_template != 1) {
						// 	if ($itm_cntr % $apfl_columns_cnt == 0) {
						// 		$render_html .= '<div class="listing-items-grp">';
						// 	}
						// }
						
						$item_class = "";
						if( $apfl_listings_pagination == 'show' && $itm_searched >= $apfl_listings_per_page ){
							$item_class = "apfl-hidden";
						}
						
						if ($textarea_input) {
							if (
								str_contains(strtolower($val['address']), strtolower($textarea_input)) ||
								str_contains(strtolower($val['title']), strtolower($textarea_input))
							) {
								$render_html .= '<div class="listing-item column mcb-column ' . esc_attr($apfl_col_class) . ' ' . $item_class . '">' . $val['html'] . '</div>';
								$itm_searched++;
							}
							
						} else {
							$render_html .= '<div class="listing-item column mcb-column ' . esc_attr($apfl_col_class) . ' ' . $item_class . '">' . $val['html'] . '</div>';
							$itm_searched++;
						}

						$itm_cntr++;
						// if ($apfl_template != 1) {
						// 	if ($itm_cntr % $apfl_columns_cnt == 0 || $itm_cntr == $apfl_columns_cnt) {
						// 		$render_html .= '</div>';
						// 	}
						// }

					}
					if ($itm_searched == 0) {
						$render_html .= '<p class="search-message">No results found for this search</p>';
					}
					
					$render_html .= '</div></div>';
				
					$total_items = $itm_searched; // count($listing_items);

					// Pagination
					if($apfl_listings_pagination == 'show'){
						$total_pages = ceil($total_items / $apfl_listings_per_page);
						
						if ($total_pages > 1) {
							$render_html .= '<div class="apfl-pagination" apfl-per-page="' . $apfl_listings_per_page . '" apfl-total-pages="' . $total_pages . '">';

							// Double left arrow for the first page
							$render_html .= '<a href="javascript:void(0)" apfl-page="1" class="apfl-left-double-arrow apfl-arrow apfl-no-visibility">&lt;&lt;</a>';

							// Left arrow
							$render_html .= '<a href="javascript:void(0)" apfl-page="1" class="apfl-left-arrow apfl-arrow apfl-no-visibility">&lt;</a>';

							// Display pages
							$start_page = 1;
							$end_page = min($total_pages, $start_page + 4);

							for ($page = $start_page; $page <= $total_pages; $page++) {
								
								if($page == 1){
									$current_class = 'apfl-current-page';
								}
								else if($page > $end_page){
									$current_class = 'apfl-hidden';
								}
								else{
									$current_class = '';
								}
								
								$render_html .= '<a href="javascript:void(0)" apfl-page="' . $page . '" class="' . $current_class . '">' . $page . '</a>';
							}

							// Right arrow
							$render_html .= '<a href="javascript:void(0)" apfl-page="2" class="apfl-right-arrow apfl-arrow">&gt;</a>';
							
							// Display double arrow for the last page 
							if ($end_page < $total_pages) {
								$render_html .= '<a href="javascript:void(0)" apfl-page="' . $total_pages . '" class="apfl-right-double-arrow apfl-arrow">&gt;&gt;</a>';
							}

							$render_html .= '</div>';
						}
					}

				} 
				else {
					$default_no = '<div class="no-listings"><p>' . esc_html__('No vacancies found matching your search criteria. Please select other filters.', 'appfolio-listings-custom') . '</p></div>';
					if ($shortcode_empty_message !== '') {
						$no_html = '<div class="no-listings"><div class="apfl-no-results-custom">' . wp_kses_post($shortcode_empty_message) . '</div></div>';
					} elseif (!empty($shortcode_city_tokens)) {
						$no_html = '<div class="no-listings"><p>' . esc_html(
							sprintf(
								/* translators: %s: comma-separated city names from shortcode */
								__('No current listings in %s.', 'appfolio-listings-custom'),
								implode(', ', $shortcode_city_tokens)
							)
						) . '</p></div>';
					} else {
						$no_html = $default_no;
					}
					$render_html .= apply_filters(
						'apfl_listings_no_results_html',
						$no_html,
						array(
							'city_tokens'   => $shortcode_city_tokens,
							'empty_message' => $shortcode_empty_message,
						)
					);

					$render_html .= '</div></div>';
				}

				// Loading Map
				if ($map != 'hide' && $client_gmap_api) {

					$lat_longs = '';
					$markers_obj = $html->find('script', -2);
	
					if ($markers_obj) {
						$markers = $markers_obj->innertext;
					}
					$markers = explode('markers:', $markers);
	
					if ($markers) {
						$markers = explode('infoWindowTemplate', $markers[1]);
						$lat_longs = json_decode(str_replace('],', ']', $markers[0]), true);
					}
	
					if ($lat_longs) {
						$init_lat = $lat_longs[0]["latitude"];
						$init_lng = $lat_longs[0]["longitude"];
	
                        $j = 0;
						$is_initcity = false;
						// Group positions with same lat-lng
						$grouped_positions = array();
						foreach ($lat_longs as $pos) {

                            if($limit && $j >= $limit) break;

							$init_address = $pos["address"];
							$map_list_id = $pos['listing_id'];
							$map_list_title = isset($title_map[$map_list_id]) ? $title_map[$map_list_id] : '';

							if ($textarea_input) {
								$search_val = strtolower($textarea_input);
								if (
									!str_contains(strtolower($init_address), $search_val) &&
									!str_contains(strtolower($map_list_title), $search_val)
								) {
									continue;
								}
							}

							$lat_long_key = $pos['latitude'] . '_' . $pos['longitude'];
							if (!$is_initcity) {
								$init_lat = $pos['latitude'];
								$init_lng = $pos['longitude'];
								$is_initcity = true;
							}
							$grouped_positions[$lat_long_key][] = $pos;

                            $j++;
						}

						$render_html .= '<script type="text/javascript">

						var lastOpenedInfoWindow = null;

						function closeAllInfoWindows() {
							if (lastOpenedInfoWindow) {
								lastOpenedInfoWindow.close();
							}
						}

						function initMap(){
							const initCity = {lat: ' . $init_lat . ', lng: ' . $init_lng . '};
							var map = new google.maps.Map(
								document.getElementById("googlemap"), {zoom: ' . get_option("apfl_def_map_zoom", 8) . ', center: initCity}
							);';
	
						$i = 1;
						foreach ($grouped_positions as $lat_long_key => $group) {
                            
							$infowindows_content = "";
							foreach ($group as $pos) {
	
								$lstng_id = '';
								$lid_url = $pos['detail_page_url'];
	
								$lid_url_arr = explode('/', $lid_url);
								if ($lid_url_arr) {
									$lstng_id = end($lid_url_arr);
								}
	
								$infowindows_content .= '<div class="mm-prop-popup">' .
									'<div class="map-popup-thumbnail"><a href="' . $lstng_dtl_page . '?lid=' . $lstng_id . '&url=' . $custom_url . '" target="_blank"><img src="' . str_replace('large', 'small', $pos['default_photo_url']) . '" width="144"></a></div>' .
									'<div class="map-popup-info">' .
									'<h3 class="map-popup-rent">' . (isset($pos['rent_range']) ? $pos['rent_range'] : '') . '</h3>' .
									'<p class="map-popup-specs">' . (isset($pos['unit_specs']) ? $pos['unit_specs'] : '') . '</p>' .
									'<p class="map-popup-address">' . (isset($pos['address']) ? $pos['address'] : '') . '</p>' .
									'<p><a href="' . $lstng_dtl_page . '?lid=' . $lstng_id . '&url=' . $custom_url . '" target="_blank" class="btn btn-secondary btn-sm pt-1 pb-1">Details</a>' .
									'<a href="https://maps.google.com/maps?daddr=' . (isset($pos['address']) ? $pos['address'] : '') . '" target="_blank" class="btn btn-secondary btn-sm pt-1 pb-1 directions-link">Directions</a>' .
									'</p></div></div>';
	
							}
							$infowindows_content = substr($infowindows_content, 0, -1);
	
	
	
							list($latitude, $longitude) = explode('_', $lat_long_key);
	
							$render_html .= "var infowindow_" . $i . " = new google.maps.InfoWindow({
								content: '" . $infowindows_content . "'
							});";
							$render_html .= 'marker_' . $i . ' = new google.maps.Marker({
								map: map,
								position: new google.maps.LatLng(' . $latitude . ', ' . $longitude . ')
							});
							marker_' . $i . '.addListener("click", function() {
							 	closeAllInfoWindows();
								infowindow_' . $i . '.open(map, marker_' . $i . ');
								lastOpenedInfoWindow = infowindow_' . $i . ';
							});';
	
							$i++;
						}
	
						$render_html .= '} </script>';
						$render_html .= '<script type="text/javascript" async defer charset="utf-8" src="https://maps.googleapis.com/maps/api/js?key=' . $client_gmap_api . '&callback=initMap"></script>';
					}
				}
			}

			return $render_html;

		}

	}
}
