<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

if (!function_exists('apfl_pp_display_multiple_listings')) {
	function apfl_pp_display_multiple_listings($atts)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (!isset($_POST['apfl_filter_nonce']) || !wp_verify_nonce($_POST['apfl_filter_nonce'], 'apfl_filter_form_action')) {
				return '';
			}
		}

		$atts = shortcode_atts(
			array(
				'url' => '',
				'map' => '',
				'type' => '',
				'columns' => '',
				'limit' => '',
				'class' => '',
				'show_heading' => 'yes',
				'city' => '',
			),
			is_array($atts) ? $atts : array(),
			'apfl_listings_multiple'
		);

		$show_heading_val = strtolower(trim((string) $atts['show_heading']));
		$apfl_sc_show_page_heading = !in_array($show_heading_val, array('no', '0', 'false', 'off', 'hide'), true);

		$shortcode_columns = ($atts['columns'] !== '') ? max(1, min(5, (int) $atts['columns'])) : null;
		$shortcode_limit = ($atts['limit'] !== '') ? max(0, (int) $atts['limit']) : null;
		$apfl_sc_extra_classes = '';
		if ($atts['class'] !== '') {
			$extra = preg_split('/\s+/', trim($atts['class']), -1, PREG_SPLIT_NO_EMPTY);
			$extra = array_map('sanitize_html_class', $extra);
			$extra = array_filter($extra);
			if ($extra) {
				$apfl_sc_extra_classes = ' ' . implode(' ', $extra);
			}
		}

		// $custom_url_arr stores list of url.
		$custom_url_arr = array();
		if ($atts && isset($atts['url'])) {
			if (strpos($atts['url'], ',') !== false) {
				$custom_url_arr = explode(',', $atts['url']);
			} else {
				$custom_url_arr = array($atts['url']);
			}
		}

		$map = '';
		if ($atts && isset($atts['map'])) {
			$map = $atts['map'];
		}

        $property_type = '';
        if ($atts && isset($atts['type'])) {
            $property_type = $atts['type'];
        }

		$shortcode_city_tokens = array();
		if (isset($atts['city']) && (string) $atts['city'] !== '') {
			foreach (explode(',', (string) $atts['city']) as $part) {
				$t = sanitize_text_field(trim($part));
				if ($t !== '') {
					$shortcode_city_tokens[] = $t;
				}
			}
		}

		$render_html = '';
		if (isset($_GET['lid']) && isset($_GET['url'])) {
			$render_html = apfl_pp_display_single_listing(sanitize_text_field($_GET['url']));
			return $render_html;
		} else {
			global $apfl_plugin_url;
			global $client_listings_url;
			global $client_gmap_api;

			if (!$client_listings_url && !$custom_url_arr) {
				return '<p>The Appfolio URL is blank. Please contact site owner.</p>';
			}

			$apfl_template = (int) get_option('apfl_template');
			if ($apfl_template) {
				$template_data = get_option('apfl_template_' . $apfl_template . '_data');
			}
			if ($apfl_template == 1) {
				$render_html .= '<div id="apfl-listings-container" class="main-listings-page apfl-hawk-template apfl-listings-shortcode' . $apfl_sc_extra_classes . '" style="width: 100%; max-width: 100%;">';
			} else {
				$render_html .= '<div id="apfl-listings-container" class="main-listings-page apfl-eagle-template apfl-listings-shortcode' . $apfl_sc_extra_classes . '" style="width: 100%; max-width: 100%;">';
			}

			$set = 0;
			$params = '';
			$is_def_order = 1;
			$def_sort_order = '';
			$target_city = $target_zip = '';

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
									if ($fltr_val != "availability") {
										$params .= '&filters[' . $fltr_key . '][]=' . urlencode($val);
									}
								}

								if ($fltr_key == 'cities') {
									$target_city = $val;
								}

								if ($fltr_key == 'postal_codes') {
									$target_zip = $val;
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
				if (count($shortcode_city_tokens) === 1) {
					$target_city = $shortcode_city_tokens[0];
				}
			}
			
			$textarea_input = "";
			if (isset($_POST['filters']['textarea_input']) && strlen($_POST['filters']['textarea_input']) > 0) {
				$textarea_input = sanitize_text_field($_POST['filters']['textarea_input']);
			}

			$apfl_template = (int) get_option('apfl_template');
			if ($apfl_template) {
				$template_data = get_option('apfl_template_' . $apfl_template . '_data');
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

			// $render_html .= '<!--TOTAL_COUNT_PLACEHOLDER-->';

			if ($apfl_template) {
				$apfl_listings_banner_image_url = get_option('apfl_listings_banner_image_url_' . $apfl_template, true);

				if (
					$apfl_listings_banner_image_url && isset($template_data['apfl_listings_banner_image']) && $template_data['apfl_listings_banner_image'] == 'show'
				) {
					$render_html .= '<div class="listing-filters" style="background-image: url(\'' . esc_url($apfl_listings_banner_image_url) . '\'); background-position: center;">';
				} else {
					$render_html .= '<div class="listing-filters">';
				}

			} else {
				$render_html .= '<div class="listing-filters">';
			}

			if ($apfl_sc_show_page_heading) {
				$listing_page_hdng = apply_filters("apfl_page_hdng", '');
				if ($listing_page_hdng) {
					$render_html .= '<div class="apfl_page_hdng">' . $listing_page_hdng . '</div>';
				}
			}

			if (!$apfl_template) {
				$apfl_filters_textarea_input = 'show';
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
				$apfl_page_sub_hdng = get_option('apfl_page_sub_hdng') ? get_option('apfl_page_sub_hdng') : '';
				$apfl_display_large_images = get_option('apfl_display_large_images');

				$apfl_display_city_state = get_option('apfl_display_city_state') ?: 'hide';

			} else {
				$apfl_filters_textarea_input = 'show';
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
				$apfl_page_sub_hdng = isset($template_data['apfl_page_sub_hdng']) ? $template_data['apfl_page_sub_hdng'] : '';
				$apfl_display_large_images = isset($template_data['apfl_display_large_images']) ? $template_data['apfl_display_large_images'] : 'hide';

				$apfl_display_city_state = isset($template_data['apfl_display_city_state']) ? $template_data['apfl_display_city_state'] : 'hide';

			}

			if ($apfl_sc_show_page_heading && $apfl_page_sub_hdng) {
				$render_html .= '<div class="apfl_page_sub_hdng">' . $apfl_page_sub_hdng . '</div>';
			}

			$min_ren_vals = $max_ren_vals = $filters_bedroom_vals = $filters_bathroom_vals = $filters_city_vals = $filters_zip_vals = array();
			
			$apfl_listings_pagination = 'hide';
			
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

			$lstng_html = '';

			$all_listing_items = array();
			$cnt = 0;
			$lat_longs = array();
			$m = 0;

			$rent_min = $rent_max = $filters_bedrooms = $filters_bathrooms = $filters_cities = $filters_zip = $filters_cats = $filters_dogs = $filters_movein = $sort_dd = '';

			$listings_arr = array();
			$title_map = array();
			foreach ($custom_url_arr as $custom_url) {

				$url = $custom_url . '/listings' . ($set ? '?' . ltrim($params, '&') : '');

				$city_fnd = $zip_fnd = false;

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
					$listings = array();
					$db = array();
					$listing_title = '';

					$listing_filters = $html->find('.filter-menu', 0);
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
					$sort_dd = $topbar->find('#filters_order_by', 0);

					foreach ($rent_min->find("option") as $mr_val) {
						$rent_min_val = (int) $mr_val->value;
						if ($rent_min_val != 0) {
							$min_ren_vals[] = $rent_min_val;
						}
					}
					foreach ($rent_max->find("option") as $maxr_val) {
						$rent_max_val = (int) $maxr_val->value;
						if ($rent_max_val != 0) {
							$max_ren_vals[] = $rent_max_val;
						}
					}
					if ($filters_bedrooms) {
						foreach ($filters_bedrooms->find("option") as $filters_bedroom) {
							$filters_bedroom_val = (int) $filters_bedroom->value;
							if ($filters_bedroom_val != 0) {
								$filters_bedroom_vals[] = $filters_bedroom_val;
							}
						}
					}
					if ($filters_bathrooms) {
						foreach ($filters_bathrooms->find("option") as $filters_bathroom) {
							$filters_bathroom_val = (int) $filters_bathroom->value;
							if ($filters_bathroom_val != 0) {
								$filters_bathroom_vals[] = $filters_bathroom_val;
							}
						}
					}
					foreach ($filters_cities->find("option") as $filters_city) {
						$filters_city_vals[] = $filters_city->value;

						if ($target_city == $filters_city->value || $target_city == '') {
							$city_fnd = true;
						}

					}
					foreach ($filters_zip->find("option") as $filter_zip) {
						$filters_zip_vals[] = $filter_zip->value;

						if ($target_zip == $filter_zip->value || $target_zip == '') {
							$zip_fnd = true;
						}

					}


					// get listing items
					$listing_items = $html->find('#result_container .listing-item');
					if ($listing_items && $city_fnd && $zip_fnd) {
						$i = 0;


						foreach ($listing_items as $listing) {

							$all_listing_items[$cnt]['html'] = '';
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
									$listing_Apply_Link = $custom_url . $listing_Apply_Link;
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
							
							$rent_in_db = ($db['RENT'] ?? $db['ROOM RENT'] ?? '');

							if($rent_in_db){
								$all_listing_items[$cnt]['rent'] = (int) str_replace("$", "", str_replace(",", "", $rent_in_db));
							}
							$all_listing_items[$cnt]['bed'] = (int) str_replace("beds", "", $db["bed"]);

							if ($apfl_display_large_images == 'show') {
								$listing_Img = str_replace("medium", "large", $listing_Img);
							}
							$all_listing_items[$cnt]['html'] .= '<a href="' . $lstng_dtl_page . '?lid=' . $listing_ID . '&url=' . $custom_url . '">
								<div class="list-img">
									<img src="' . $listing_Img . '">';
							if ($apfl_listings_display_price == 'show' && $apfl_listings_price_pos == 'onimage') {
								$all_listing_items[$cnt]['html'] .= '<span class="rent-price">' . ($db['RENT'] ?? $db['ROOM RENT'] ?? '') . '</span>';
							}
							if ($apfl_listings_display_avail == 'show' && $apfl_listings_avail_pos == 'onimage') {
								if (array_key_exists('Available', $db)) {
									$all_listing_items[$cnt]['html'] .= '<span class="lstng-avail">Available ' . $db["Available"] . '</span>';
								}
							}

							if ($apfl_template == 1) {
								if ($apfl_listings_display_address == 'show') {
									$all_listing_items[$cnt]['html'] .= '<h3 class="address">' . $listing_Address . '</h3>';
								}
							}

							$all_listing_items[$cnt]['html'] .= '</div></a>
								<div class="details">';

							if ($apfl_listings_display_price == 'show' && $apfl_listings_price_pos == 'offimage') {
								if ($apfl_template == 1) {
									$apfl_rent_text = get_option('apfl_rent_text', 'RENT');
									$all_listing_items[$cnt]['html'] .= '<span class="rent-price-off">' . esc_html($apfl_rent_text) . ' <b>' . ($db['RENT'] ?? $db['ROOM RENT'] ?? '') . '</b></span>';
								} else {
									$all_listing_items[$cnt]['html'] .= '<span class="rent-price-off">' . ($db['RENT'] ?? $db['ROOM RENT'] ?? '') . '</span>';
								}
							}
							if ($apfl_listings_display_avail == 'show' && $apfl_listings_avail_pos == 'offimage') {
								if (array_key_exists('Available', $db)) {
									$all_listing_items[$cnt]['html'] .= '<span class="lstng-avail-off">Available ' . $db["Available"] . '</span>';
								}
							}

							if ($apfl_listings_display_ttl == 'show') {
								if (!$apfl_listings_ttl_tag) {
									$apfl_listings_ttl_tag = 'h2';
								}
								$all_listing_items[$cnt]['html'] .= '<' . $apfl_listings_ttl_tag . ' class="lstng_ttl">' . $listing_title . '</' . $apfl_listings_ttl_tag . '>';
							}

							if ($apfl_template != 1) {
								if ($apfl_listings_display_address == 'show') {
									$all_listing_items[$cnt]['html'] .= '<h3 class="address">' . $listing_Address . '</h3>';
								}
							}

							$all_listing_items[$cnt]['html'] .= '<p>';
							if ($apfl_listings_display_beds == 'show') {
								if ($apfl_listings_bed_img) {
									$all_listing_items[$cnt]['html'] .= '<img class="bedimg" src="' . $apfl_listings_bed_img . '">';
								}
								$all_listing_items[$cnt]['html'] .= '<span class="beds">' . $db["bed"] . '</span> ';
							}
							if ($apfl_listings_display_baths == 'show') {
								if ($apfl_listings_bath_img) {
									$all_listing_items[$cnt]['html'] .= '<img class="bathimg" src="' . $apfl_listings_bath_img . '">';
								}
								$all_listing_items[$cnt]['html'] .= '<span class="baths">' . $db["bath"] . '</span>';
							}
							if ($apfl_listings_display_area == 'show') {
								if ($apfl_listings_area_img) {
									$all_listing_items[$cnt]['html'] .= '<img class="areaimg" src="' . $apfl_listings_area_img . '">';
								}
								$all_listing_items[$cnt]['html'] .= '<span class="area">' . $db["Square Feet"] . ' Sq. Ft.</span>';
							}
							if ($apfl_listings_display_pets == 'show') {
								if ($db['pets'] =='yes' && $apfl_listings_pet_img) {
									$all_listing_items[$cnt]['html'] .= '<img class="petimg" src="' . $apfl_listings_pet_img . '">';
								}
								if ($db['pets'] =='no' && $apfl_listings_no_pet_img) {
									$all_listing_items[$cnt]['html'] .= '<img class="nopetimg" src="' . $apfl_listings_no_pet_img . '">';
								}
							}
							$all_listing_items[$cnt]['html'] .= '</p><div class="btns">';

							if ($apfl_listings_display_detail == 'show') {
								$all_listing_items[$cnt]['html'] .= '<a class="more_detail_btn" href="' . $lstng_dtl_page . '?lid=' . $listing_ID . '&url=' . $custom_url . '">Details</a>';
							}
							if ($apfl_listings_display_apply == 'show') {
								$all_listing_items[$cnt]['html'] .= '<a class="apply_btn" href="' . $listing_Apply_Link . '" target="_blank">Apply</a>';
							}
							if ($apfl_listings_display_schedule == 'show') {

								if (($apfl_listings_display_apply == 'show' && $apfl_listings_display_detail == 'show') || ($apfl_listings_display_apply == 'hide' && $apfl_listings_display_detail == 'hide')) {
									if ($apfl_template == 1)
										$all_listing_items[$cnt]['html'] .= '<a class="schedule_btn" href="' . $schshowing_btn_link . '" style="width: 100%;" target="_blank">Schedule Showing</a>';
									else
										$all_listing_items[$cnt]['html'] .= '<a class="schedule_btn" href="' . $schshowing_btn_link . '" target="_blank">Schedule Showing</a>';
								} else {
									if ($apfl_template == 1)
										$all_listing_items[$cnt]['html'] .= '<a class="schedule_btn" href="' . $schshowing_btn_link . '" style="width: 50%;" target="_blank">Schedule</a>';
									else
										$all_listing_items[$cnt]['html'] .= '<a class="schedule_btn" href="' . $schshowing_btn_link . '" target="_blank">Schedule</a>';
								}

							}
							$all_listing_items[$cnt]['html'] .= '</div>
								</div>';

							$listings_arr[$m]['html'] = $all_listing_items[$cnt]['html'];
							$listings_arr[$m]['address'] = $listing_Address;
							$listings_arr[$m]['title'] = $listing_title;
							if (array_key_exists('Available', $db)) {
								$listings_arr[$m]['avail'] = $db["Available"];
							}

							$id_attr = $listing->getAttribute('id');
							if (preg_match('/listing_(\d+)/', $id_attr, $matches)) {
								$list_id = $matches[1]; 
								$title_map[$list_id] = $listing_title;
							}

							$i++;
							$m++;
							$cnt++;

						}

						if ($client_gmap_api) {
							$markers_obj = $html->find('script', -2);
							if ($markers_obj) {
								$markers = $markers_obj->innertext;
							}
							$markers = explode('markers:', $markers);
							if ($markers) {
								$markers = explode('infoWindowTemplate', $markers[1]);
								$lat_longs[] = json_decode(str_replace('],', ']', $markers[0]), true);
							}
						}

					} else {
						// $render_html .= '<div class="no-listings"><p>No vacancies found matching your search criteria. Please select other filters.</p></div>';
					}
				}

			}

			$count_html = '';
			if($cnt) {
				// $count_html = '<div class="apfl-listings-count">' . $cnt . ' listings found</div>'; // Commenting for now - need better design and placement.
			}
			// $render_html = str_replace('<!--TOTAL_COUNT_PLACEHOLDER-->', $count_html, $render_html);

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


				$min_ren_vals = array_unique($min_ren_vals);
				sort($min_ren_vals);
				$max_ren_vals = array_unique($max_ren_vals);
				sort($max_ren_vals);
				$filters_bedroom_vals = array_unique($filters_bedroom_vals);
				$filters_bathroom_vals = array_unique($filters_bathroom_vals);

				$filters_city_vals = array_unique($filters_city_vals);
				array_shift($filters_city_vals);
				sort($filters_city_vals);
				array_unshift($filters_city_vals, 'All Cities');

				$filters_zip_vals = array_unique($filters_zip_vals);
				array_shift($filters_zip_vals);
				sort($filters_zip_vals);
				array_unshift($filters_zip_vals, 'All Zip Codes');


				// Filters
				if ($apfl_filters_textarea_input == 'show') {
					if (isset($_POST['filters']['textarea_input'])) {
						$selected = $_POST['filters']['textarea_input'];
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
						$correct_min_rent_raw = stripslashes($_POST['orig_min_rent']);
						$correct_min_rent = wp_kses($correct_min_rent_raw, $allowed_html);
				
						$correct_min_rent = preg_replace(
							'/<option[^>]*>.*?<\/option>/s',
							'<option value="">' . esc_html('Min ' . $rent_text) . '</option>',
							$correct_min_rent, 1
						);
				
						$escaped_min_rent = htmlspecialchars($correct_min_rent, ENT_QUOTES, 'UTF-8');
						$render_html .= "<input type='hidden' value='{$escaped_min_rent}' name='orig_min_rent'>";
				
						$selected = sanitize_text_field($_POST['filters']['market_rent_from']);
						$str_to_replace = 'value="' . esc_attr($selected) . '"';
						$str_to_replace_by = 'value="' . esc_attr($selected) . '" selected="selected"';
						$render_html .= '<select name="filters[market_rent_from]">' . str_replace($str_to_replace, $str_to_replace_by, $correct_min_rent) . '</select>';
				
					} else {
						if ($min_ren_vals) {
							$correct_min_rent = '<option value>' . esc_html('Min ' . $rent_text) . '</option>';
				
							foreach ($min_ren_vals as $min_ren_val) {
								$correct_min_rent .= '<option value="' . esc_attr($min_ren_val) . '">' . '$' . esc_html($min_ren_val) . '</option>';
							}
				
							$render_html .= '<select name="filters[market_rent_from]">' . wp_kses($correct_min_rent, $allowed_html) . '</select>';
				
							$escaped_min_rent = htmlspecialchars($correct_min_rent, ENT_QUOTES, 'UTF-8');
							$render_html .= "<input type='hidden' value='{$escaped_min_rent}' name='orig_min_rent'>";
						}
					}
				}				

				if ($apfl_filters_maxrent == 'show') {
					if (isset($_POST['orig_max_rent'])) {
						$correct_max_rent_raw = stripslashes($_POST['orig_max_rent']);
						$correct_max_rent = wp_kses($correct_max_rent_raw, $allowed_html);
				
						$correct_max_rent = preg_replace(
							'/<option[^>]*>.*?<\/option>/s',
							'<option value="">' . esc_html('Max ' . $rent_text) . '</option>',
							$correct_max_rent, 1
						);
				
						$escaped_max_rent = htmlspecialchars($correct_max_rent, ENT_QUOTES, 'UTF-8');
						$render_html .= "<input type='hidden' value='{$escaped_max_rent}' name='orig_max_rent'>";
				
						$selected = sanitize_text_field($_POST['filters']['market_rent_to']);
						$str_to_replace = 'value="' . esc_attr($selected) . '"';
						$str_to_replace_by = 'value="' . esc_attr($selected) . '" selected="selected"';
						$render_html .= '<select name="filters[market_rent_to]">' . str_replace($str_to_replace, $str_to_replace_by, $correct_max_rent) . '</select>';
				
					} else {
						if ($max_ren_vals) {
							$correct_max_rent = '<option value>' . esc_html('Max ' . $rent_text) . '</option>';
				
							foreach ($max_ren_vals as $max_ren_val) {
								$correct_max_rent .= '<option value="' . esc_attr($max_ren_val) . '">' . '$' . esc_html($max_ren_val) . '</option>';
							}
				
							$render_html .= '<select name="filters[market_rent_to]">' . wp_kses($correct_max_rent, $allowed_html) . '</select>';
				
							$escaped_max_rent = htmlspecialchars($correct_max_rent, ENT_QUOTES, 'UTF-8');
							$render_html .= "<input type='hidden' value='{$escaped_max_rent}' name='orig_max_rent'>";
						}
					}
				}
				
				if ($apfl_filters_bed == 'show') {
					if (isset($_POST['orig_beds'])) {
						$correct_beds_raw = stripslashes($_POST['orig_beds']);
						$correct_beds = wp_kses($correct_beds_raw, $allowed_html);
				
						$escaped_beds = htmlspecialchars($correct_beds, ENT_QUOTES, 'UTF-8');
						$render_html .= "<input type='hidden' value='{$escaped_beds}' name='orig_beds'>";
				
						$selected = sanitize_text_field($_POST['filters']['bedrooms']);
						$str_to_replace = 'value="' . esc_attr($selected) . '"';
						$str_to_replace_by = 'value="' . esc_attr($selected) . '" selected="selected"';
						$render_html .= '<select name="filters[bedrooms]">' . str_replace($str_to_replace, $str_to_replace_by, $correct_beds) . '</select>';
				
					} else {
						if ($filters_bedroom_vals) {
							$correct_beds = '<option value>' . esc_html('Beds') . '</option>';
				
							foreach ($filters_bedroom_vals as $filters_bedroom_val) {
								$correct_beds .= '<option value="' . esc_attr($filters_bedroom_val) . '">' . esc_html($filters_bedroom_val) . '+</option>';
							}
				
							$render_html .= '<select name="filters[bedrooms]">' . wp_kses($correct_beds, $allowed_html) . '</select>';
				
							$escaped_beds = htmlspecialchars($correct_beds, ENT_QUOTES, 'UTF-8');
							$render_html .= "<input type='hidden' value='{$escaped_beds}' name='orig_beds'>";
						}
					}
				}
				
				if ($apfl_filters_bath == 'show') {
					if (isset($_POST['orig_baths'])) {
						$correct_baths_raw = stripslashes($_POST['orig_baths']);
						$correct_baths = wp_kses($correct_baths_raw, $allowed_html);
				
						$escaped_baths = htmlspecialchars($correct_baths, ENT_QUOTES, 'UTF-8');
						$render_html .= "<input type='hidden' value='{$escaped_baths}' name='orig_baths'>";
				
						$selected = sanitize_text_field($_POST['filters']['bathrooms']);
						$str_to_replace = 'value="' . esc_attr($selected) . '"';
						$str_to_replace_by = 'value="' . esc_attr($selected) . '" selected="selected"';
						$render_html .= '<select name="filters[bathrooms]">' . str_replace($str_to_replace, $str_to_replace_by, $correct_baths) . '</select>';
				
					} else {
						if ($filters_bathroom_vals) {
							$correct_baths = '<option value>' . esc_html('Baths') . '</option>';
				
							foreach ($filters_bathroom_vals as $filters_bathroom_val) {
								$correct_baths .= '<option value="' . esc_attr($filters_bathroom_val) . '">' . esc_html($filters_bathroom_val) . '+</option>';
							}
				
							$render_html .= '<select name="filters[bathrooms]">' . wp_kses($correct_baths, $allowed_html) . '</select>';
				
							$escaped_baths = htmlspecialchars($correct_baths, ENT_QUOTES, 'UTF-8');
							$render_html .= "<input type='hidden' value='{$escaped_baths}' name='orig_baths'>";
						}
					}
				}				

				if ($apfl_filters_cities == 'show') {
					if (isset($_POST['orig_cities'])) {
						$correct_cities = wp_kses($_POST['orig_cities'], $allowed_html);
						$render_html .= "<input type='hidden' value='" . htmlspecialchars($correct_cities, ENT_QUOTES) . "' name='orig_cities'>";
						$selected = sanitize_text_field($_POST['filters']['cities'][0]);
						$str_to_replace = 'value="' . $selected . '"';
						$str_to_replace_by = 'value="' . $selected . '" selected="selected"';
						$render_html .= '<select class="apfl-city-fltr" data-show-state="'.$apfl_display_city_state.'" name="filters[cities][]">' . str_replace($str_to_replace, $str_to_replace_by, $correct_cities) . '</select>';
					} else {
						if($filters_city_vals) {
							$correct_cities = '';
							foreach ($filters_city_vals as $filters_city_val) {
								$correct_cities .= '<option value="' . esc_attr($filters_city_val) . '">' . esc_html($filters_city_val) . '</option>';
							}
							$render_html .= '<select class="apfl-city-fltr" data-show-state="'.$apfl_display_city_state.'" name="filters[cities][]">' . $correct_cities . '</select>';
							$render_html .= "<input type='hidden' value='" . htmlspecialchars($correct_cities, ENT_QUOTES) . "' name='orig_cities'>";
						}
					}
				}
				
				if ($apfl_filters_zip == 'show') {
					if (isset($_POST['orig_zip'])) {
						$correct_zip = wp_kses($_POST['orig_zip'], $allowed_html);
						$render_html .= "<input type='hidden' value='" . htmlspecialchars($correct_zip, ENT_QUOTES) . "' name='orig_zip'>";
						$selected = sanitize_text_field($_POST['filters']['postal_codes'][0]);
						$str_to_replace = 'value="' . $selected . '"';
						$str_to_replace_by = 'value="' . $selected . '" selected="selected"';
						$render_html .= '<select name="filters[postal_codes][]">' . str_replace($str_to_replace, $str_to_replace_by, $correct_zip) . '</select>';
					} else {
						if($filters_zip_vals) {
							$correct_zip = '';
							foreach ($filters_zip_vals as $filters_zip_val) {
								$correct_zip .= '<option value="' . esc_attr($filters_zip_val) . '">' . esc_html($filters_zip_val) . '</option>';
							}
							$render_html .= '<select name="filters[postal_codes][]">' . $correct_zip . '</select>';
							$render_html .= "<input type='hidden' value='" . htmlspecialchars($correct_zip, ENT_QUOTES) . "' name='orig_zip'>";
						}
					}
				}
				
				if ($apfl_filters_cat == 'show') {
					if (isset($_POST['orig_cats'])) {
						$correct_cats = preg_replace('/<option[^>]*>.*?<\/option>/s', '<option value="">Cats</option>', wp_kses($_POST['orig_cats'], $allowed_html), 1);
						$render_html .= "<input type='hidden' value='" . htmlspecialchars($correct_cats, ENT_QUOTES) . "' name='orig_cats'>";
						$selected = sanitize_text_field($_POST['filters']['cats']);
						$str_to_replace = 'value="' . $selected . '"';
						$str_to_replace_by = 'value="' . $selected . '" selected="selected"';
						$render_html .= '<select name="filters[cats]">' . str_replace($str_to_replace, $str_to_replace_by, $correct_cats) . '</select>';
					} else {
						if($filters_cats) {
							$correct_cats = preg_replace('/<option[^>]*>.*?<\/option>/s', '<option value="">Cats</option>', wp_kses($filters_cats->innertext, $allowed_html), 1);
							$render_html .= '<select name="filters[cats]">' . $correct_cats . '</select>';
							$render_html .= "<input type='hidden' value='" . htmlspecialchars($correct_cats, ENT_QUOTES) . "' name='orig_cats'>";
						}
					}
				}
				
				if ($apfl_filters_dog == 'show') {
					if (isset($_POST['orig_dogs'])) {
						$correct_dogs = preg_replace('/<option[^>]*>.*?<\/option>/s', '<option value="">Dogs</option>', wp_kses($_POST['orig_dogs'], $allowed_html), 1);
						$render_html .= "<input type='hidden' value='" . htmlspecialchars($correct_dogs, ENT_QUOTES) . "' name='orig_dogs'>";
						$selected = sanitize_text_field($_POST['filters']['dogs']);
						$str_to_replace = 'value="' . $selected . '"';
						$str_to_replace_by = 'value="' . $selected . '" selected="selected"';
						$render_html .= '<select name="filters[dogs]">' . str_replace($str_to_replace, $str_to_replace_by, $correct_dogs) . '</select>';
					} else {
						if($filters_dogs) {
							$correct_dogs = preg_replace('/<option[^>]*>.*?<\/option>/s', '<option value="">Dogs</option>', wp_kses($filters_dogs->innertext, $allowed_html), 1);
							$render_html .= '<select name="filters[dogs]">' . $correct_dogs . '</select>';
							$render_html .= "<input type='hidden' value='" . htmlspecialchars($correct_dogs, ENT_QUOTES) . "' name='orig_dogs'>";
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

				if ($apfl_filters_sorting == 'show') {
					if (isset($_POST['orig_sort_dd'])) {
						$correct_sort_dd = wp_kses(wp_unslash($_POST['orig_sort_dd']), $allowed_html);
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
						$render_html .= "<input type='hidden' value='" . esc_attr($correct_sort_dd) . "' name='orig_sort_dd'>";
						$selected = sanitize_text_field($_POST['filters']['order_by'] ?? '');
						$str_to_replace = 'value="' . esc_attr($selected) . '"';
						$str_to_replace_by = 'selected="selected" value="' . esc_attr($selected) . '"';
						$correct_sort_dd = str_replace('selected="selected"', '', $correct_sort_dd);
						$render_html .= '<select id="order_by_fltr" name="filters[order_by]">' . str_replace($str_to_replace, $str_to_replace_by, $correct_sort_dd) . '</select>';
					} else {
						if ($sort_dd) {
							$correct_sort_dd = wp_kses(wp_unslash($sort_dd->innertext), $allowed_html);
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
							if ($is_def_order) {
								if ($def_sort_order == 'availability') {
									$correct_sort_dd .= '<option selected="selected" value="availability">Availability</option>';
								} else {
									$correct_sort_dd .= '<option value="availability">Availability</option>';
								}
							} else {
								$correct_sort_dd .= '<option value="availability">Availability</option>';
							}
							$render_html .= '<select id="order_by_fltr" name="filters[order_by]">' . $correct_sort_dd . '</select>';
							$render_html .= "<input type='hidden' value='" . esc_attr($correct_sort_dd) . "' name='orig_sort_dd'>";
						}
					}
				}

				$render_html .= '<input type="submit" value="SEARCH" name="fltr-submt">';
				$render_html .= '</form>';
			}
			
			$render_html .= '</div>';

			// Google map for listings
			if ($map != 'hide' && $client_gmap_api) {
				$render_html .= '<div id="googlemap"></div>';
			}

			// All listings in columns
			$render_html .= '<div class="all-listings" data-apfl-cols="' . esc_attr((string) (int) $apfl_columns_cnt) . '">';

			if ($shortcode_limit !== null && $shortcode_limit > 0) {
				$listings_arr = array_slice($listings_arr, 0, $shortcode_limit);
				$apfl_listings_pagination = 'hide';
			}

			// $render_html .= $lstng_html;

			if (isset($_POST['filters']['order_by'])) {
				$order_by = sanitize_text_field($_POST['filters']['order_by']);
				if ($order_by == 'rent_asc') {
					usort($all_listing_items, function ($a, $b) {
						return $a['rent'] <=> $b['rent'];
					});
				} else if ($order_by == 'rent_desc') {
					usort($all_listing_items, function ($a, $b) {
						return $b['rent'] <=> $a['rent'];
					});
				} else if ($order_by == 'bedrooms') {
					usort($all_listing_items, function ($a, $b) {
						return $a['bed'] <=> $b['bed'];
					});
				}
			}

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
				if ($is_def_order && $def_sort && urlencode($def_sort) == 'availability') {
					usort($listings_arr, function ($a, $b) {
						// Check if 'avail' key exists in both $a and $b
						$availA = isset ($a['avail']) ? strtotime($a['avail']) : PHP_INT_MAX;
						$availB = isset ($b['avail']) ? strtotime($b['avail']) : PHP_INT_MAX;

						return $availA - $availB;
					});
				}
			}

			$itm_cntr = 0;
			$itm_searched = 0;

			foreach ($listings_arr as $key => $val) {

				if ($apfl_template != 1) {
					if ($itm_cntr % $apfl_columns_cnt == 0) {
						$render_html .= '<div class="listing-items-grp">';
					}
				}
				
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
				if ($apfl_template != 1) {
					if ($itm_cntr % $apfl_columns_cnt == 0 || $itm_cntr == $apfl_columns_cnt) {
						$render_html .= '</div>';
					}
				}

			}
			if ($itm_searched == 0 && $itm_cntr > 0) {
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

			// Loading Map
			if ($map != 'hide' && $client_gmap_api) {
				if ($lat_longs) {

					foreach ($lat_longs as $positions) {
						foreach ($positions as $pos) {
							$init_lat = $pos["latitude"];
							$init_lng = $pos["longitude"];
						}
					}

					$is_initcity = false;
					$grouped_positions = array();
					foreach ($lat_longs as $positions) {
						foreach ($positions as $pos) {
							
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

							$pos['listing_title'] = $map_list_title;

							$grouped_positions[$lat_long_key][] = $pos;
						}
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
							if (
								str_contains(strtolower($pos['address']), strtolower($textarea_input)) ||
								str_contains(strtolower($pos['listing_title']), strtolower($textarea_input))
							) {
								$lstng_id = '';
								$lid_url = $pos['detail_page_url'];

								$lid_url_arr = explode('/', $lid_url);
								if ($lid_url_arr) {
									$lstng_id = end($lid_url_arr);
								}

								$infowindows_content .= '<div class="mm-prop-popup">' .
									'<div class="map-popup-thumbnail"><a href="' . $lstng_dtl_page . '?lid=' . $lstng_id . '&url=' . $custom_url . '" target="_blank"><img src="' . str_replace('large', 'small', $pos['default_photo_url']) . '" width="144"></a></div>' .
									'<div class="map-popup-info">' .
									'<h3 class="map-popup-rent">' . (isset($pos['market_rent']) ? $pos['market_rent'] : '') . '</h3>' .
									'<p class="map-popup-specs">' . (isset($pos['unit_specs']) ? $pos['unit_specs'] : '') . '</p>' .
									'<p class="map-popup-address">' . (isset($pos['address']) ? $pos['address'] : '') . '</p>' .
									'<p><a href="' . $lstng_dtl_page . '?lid=' . $lstng_id . '&url=' . $custom_url . '" target="_blank" class="btn btn-secondary btn-sm pt-1 pb-1">Details</a>' .
									'<a href="https://maps.google.com/maps?daddr=' . (isset($pos['address']) ? $pos['address'] : '') . '" target="_blank" class="btn btn-secondary btn-sm pt-1 pb-1 directions-link">Directions</a>' .
									'</p></div></div>';
							}
						}

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

			return $render_html;

		}

	}
}
