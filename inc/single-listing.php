<?php

// Exit if accessed directly
if (! defined('ABSPATH')) {
	exit;
}

if (!function_exists('apfl_pp_display_single_listing')) {
	function apfl_pp_display_single_listing($custom_url, $single_url = '')
	{

		global $apfl_plugin_url;
		global $client_listings_url;

		$list_id = sanitize_text_field(get_query_var('lid'));

		if (!$client_listings_url && !$custom_url && !$single_url) {
			return '<p>The Appfolio URL is blank. Please contact site owner.</p>';
		}

		if ($custom_url) {
			$client_listings_url = $custom_url;
		}

		$client_listings_url = str_replace('/listings', '', $client_listings_url);

		$last_char = substr($client_listings_url, -1);
		if ($last_char == '/') {
			$client_listings_url = substr($client_listings_url, 0, -1);
		}

		if ($single_url) {
			$url = $single_url;
		} else {
			$url = $client_listings_url . '/listings/detail/' . $list_id;
		}

		// Template
		$single_Template = get_option('apfl_single_template') ?: 'classic';
		if ($single_Template == 'modern') {

			return apfl_pp_display_single_listing_modern($url, $list_id);
		}

		$apply_btn_link = $contact_btn_link = '';

		$sl_html = '<div class="apfl-sl-wrapper" style="width: 100%; max-width: 100%;">';

		if ($list_id || $single_url) {

			$place_area = $availability = $rent_price = $address = $address_link = $ttl = $dsc = '';

			$bed_std = $baths = 'N/A';

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

			if ($html && $html->root) {
				$listing_images = array();
				$i = 0;
				$main_gallery = $html->find('main .gallery', 0);

				if ($main_gallery) {
					$main_imgs = $main_gallery->find('a.swipebox');
					if ($main_imgs) {
						foreach ($main_imgs as $main_img) {
							$listing_images[$i]['href'] = $main_img->{'href'};
							$src = $main_img->{'style'};
							if ($src) {
								$ini = strpos($src, '(');
								$ini += strlen('(');
								$len = strpos($src, ')', $ini) - $ini;
								$listing_images[$i]['img_url'] = substr($src, $ini, $len);
							} else {
								$img_src_obj = $main_img->find('img', 0);
								$img_src = $img_src_obj->{'src'};
								$listing_images[$i]['img_url'] = $img_src;
							}
							$i++;
						}
					}
					$extra_imgs = $html->find('div[style="display:none"] a.swipebox');
					if ($extra_imgs) {
						foreach ($extra_imgs as $extra_img) {
							$listing_images[$i]['href'] = $extra_img->{'href'};
							$ext_img_obj = $extra_img->find('img', 0);
							$listing_images[$i]['img_url'] = $ext_img_obj->{'src'};
							$i++;
						}
					}
				}

				// Beds and Baths
				$rent_banner = $html->find('.rent-banner', 0);
				if ($rent_banner) {
					$cap_bed_baths_obj = $rent_banner->find('.u-float-right .rent-banner__text', 0);
					if ($cap_bed_baths_obj) {
						$cap_bed_baths = $cap_bed_baths_obj->innertext;
						$cap_bed_baths = explode("/", $cap_bed_baths);
						if ($cap_bed_baths) {
							$bed_std = $cap_bed_baths[0];
							if (strpos($bed_std, 'bd') !== false) {
								$bed_std = str_replace("bd", "Bed", $bed_std);
							}
							$baths = $cap_bed_baths[1];
							if (strpos($baths, 'ba') !== false) {
								$baths = str_replace("ba", "Baths", $baths);
							}
						}
					}
				}

				$listing_details = $html->find('.listing-detail', 0);
				if ($listing_details) {
					$ld_body = $listing_details->find('.listing-detail__body', 0);
					if ($ld_body) {
						$address_obj = $ld_body->find('.header .js-show-title', 0);
						if ($address_obj) {
							
							foreach ($address_obj->nodes as $node) {
								if ($node->nodetype === HDOM_TYPE_TEXT) {
									$address .= trim($node->innertext);
								}
							}

							$adrress_link_obj = $address_obj->find('a', 0);
							if($adrress_link_obj) {
								$address_link = $adrress_link_obj->href;
							}
						}
						$bb_obj = $ld_body->find('.header .header__summary', 0);
						if ($bb_obj) {
							$bed_bath_avail = $bb_obj->innertext;
							$bed_bath_avail = explode("| ", $bed_bath_avail);
							if ($bed_bath_avail) {
								if (strpos($bed_bath_avail[0], 'Sq.') !== false) {
									$reversedParts = explode(' ,', strrev($bed_bath_avail[0]));
									$place_area = strrev($reversedParts[0]); // get last part
								}
								if (count($bed_bath_avail) > 1) {
									$availability = $bed_bath_avail[1];
								}
							}
						}
						$ttl_obj = $ld_body->find('.listing-detail__title', 0);
						if ($ttl_obj) {
							$ttl = $ttl_obj->innertext;
						}
						$dsc_obj = $ld_body->find('.listing-detail__description', 0);
						if ($dsc_obj) {

							$desc_anchors = $dsc_obj->find('a');

							$kuula_tour = '';
							foreach ($desc_anchors as $desc_anchor_obj) {
								$a_href = $desc_anchor_obj->{'href'};
								if (str_contains($a_href, 'kuula.co')) {
									$kuula_tour = '<iframe width="100%" height="640" frameborder="0" allow="xr-spatial-tracking; gyroscope; accelerometer" allowfullscreen scrolling="no" src="' . $a_href . '"></iframe>';
									// hide this link from the description
									$desc_anchor_obj->addClass('apfl-hidden');
									break; // break the loop as we get the first occurance of the tour link
								}
							}

							// Now get the description after getting kuula code and removing acnhor
							$dsc = $dsc_obj->innertext;
						}
						$extra_fields = $ld_body->find('.grid div');
					}

					$ld_sidebar = $listing_details->find('.sidebar', 0);
					if ($ld_sidebar) {
						$rent_cap = $ld_sidebar->firstChild();
						if ($rent_cap) {
							$rent_price_obj = $rent_cap->find('h2', 0);
							if ($rent_price_obj) {
								$rent_price = $rent_price_obj->innertext;
							}

							// $cap_bed_baths_obj = $rent_cap->find('h3', 0);
							// if($cap_bed_baths_obj){
							// 	$cap_bed_baths = $cap_bed_baths_obj->innertext;
							// 	$cap_bed_baths = explode("/", $cap_bed_baths);
							// 	if($cap_bed_baths){
							// 		$bed_std = $cap_bed_baths[0];
							// 		if(strpos($bed_std, 'bd') !== false){ $bed_std = str_replace("bd","Bed",$bed_std); }
							// 		$baths = $cap_bed_baths[1];
							// 		if(strpos($baths, 'ba') !== false){ $baths = str_replace("ba","Baths",$baths); }
							// 	}
							// }
						}
						$btns = $ld_sidebar->find('.foot-button', 0);
						if ($btns) {

							$apply_btn_link_obj = $btns->find('.btn-warning', 0);
							if ($apply_btn_link_obj) {
								$apply_btn_link = $apply_btn_link_obj->{'href'};
							}

							$contact_btn_link_obj = $btns->find('.btn-secondary', 0);
							if ($contact_btn_link_obj) {
								$contact_btn_link = $contact_btn_link_obj->{'href'};
								$contact_btn_link = $client_listings_url . $contact_btn_link;
								$contact_btn_link = apply_filters("apfl_contact_btn_link", $contact_btn_link, $contact_btn_link);
							}
							$custom_contact_btn_link = get_option('apfl_custom_contact_us');
							if ($custom_contact_btn_link && $custom_contact_btn_link != '') {
								$contact_btn_link = get_option('apfl_custom_contact_us');
							}
						}

						$logo_link_obj = $ld_sidebar->find('.sidebar__portfolio-logo', 0);
						if ($logo_link_obj) {
							$logo_link = $logo_link_obj->{'src'};
						}

						$phn_ctc = '';
						$phn_ctc_obj = $ld_sidebar->find('.u-pad-bl', 0);
						if ($phn_ctc_obj) {
							$phn_ctc = $phn_ctc_obj->innertext;
							$phn_ctc = preg_replace('#<(a)(?:[^>]+)?>.*?</\1>#s', '', $phn_ctc);
						}
					}
				}

				$all_lstng_url = '';
				if (isset($_GET['ref'])) {
					$all_lstng_url = htmlentities($_GET['ref']);
				} else {

					$apfl_new_lstngs_page = get_option('apfl_all_lstngs_page');
					if ($apfl_new_lstngs_page) {
						$all_lstng_url = $apfl_new_lstngs_page;
					} else {
						$all_lstng_url = strtok($_SERVER["REQUEST_URI"], '?');
					}
				}
				if ($all_lstng_url) {
					$sl_html .= '<div class="apfl_back_to_all"><a class="apfl-prmry-btn" href="' . $all_lstng_url . '"> 
					<svg class="arrow-left-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
				<path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
			</svg>
					All Listings</a></div>';
				}

				$apfl_template = (int)get_option('apfl_template');
				if ($apfl_template) {
					$template_data = get_option('apfl_template_' . $apfl_template . '_data');

					$apfl_listings_display_beds = $template_data['apfl_listings_display_beds'];
					$apfl_listings_bed_img = $template_data['apfl_listings_bed_img'];

					$apfl_listings_display_baths = $template_data['apfl_listings_display_baths'];
					$apfl_listings_bath_img = $template_data['apfl_listings_bath_img'];
				} else {
					$apfl_listings_display_beds = get_option('apfl_listings_display_beds');
					$apfl_listings_bed_img = get_option('apfl_listings_bed_img');

					$apfl_listings_display_baths = get_option('apfl_listings_display_baths');
					$apfl_listings_bath_img = get_option('apfl_listings_bath_img');
				}


				$sl_html .= '<div class="listing-sec"><div class="apfl-column apfl-two-fifth">';
				if ($listing_images) {
					$sl_html .= '<div class="apfl-gallery">';
					$j = 1;

					$video_url = '';

					foreach ($listing_images as $list_img) {

						if (str_contains($list_img["href"], 'youtube')) {
							$video_url = $list_img["href"];
						}

						$sl_html .= '<div class="mySlides">
										<div class="numbertext">' . $j . ' / ' . count($listing_images) . '</div>
										<img src="' . (str_contains($list_img["href"], 'youtube') ? $list_img["img_url"] : $list_img["href"]) . '" data-href="' . $list_img["href"] . '" data-id="apfl_gal_img_' . $j . '">
									</div>';
						$j++;
					}
					$sl_html .= '<a class="prev" onclick="plusSlides(-1)">  <svg class="arrow-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M15 7L10 12L15 17" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
    </svg></a>
								<a class="next" onclick="plusSlides(1)"><svg class="arrow-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 7L15 12L10 17" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
    </svg></a>
								<div class="row" style="margin-top: 7px;">';
					$k = 1;
					foreach ($listing_images as $list_img) {
						$sl_html .= '<div id="image-prvw" class="imgcolumn">
											<img class="demo cursor" src="' . $list_img["img_url"] . '" onclick="currentSlide(' . $k . ')">
										</div>';
						$k++;
					}
					$sl_html .= '</div></div>';

					if ($video_url) {
						$iframe_code = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i", "<iframe width=\"560\" height=\"330\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>", $video_url);
						$sl_html .= '<div id="apfl-vdo">
									' . $iframe_code . '
								</div>';
					}

					// Load 3-d tour
					if ($kuula_tour) {
						$sl_html .= '<div id="apfl-kuula-tour">
									<h4>Virtual Tour:</h4>
									' . $kuula_tour . '
								</div>';
					}
				}
				$sl_html .= '</div>';
				$sl_html .= '<div class="apfl-column apfl-three-fifth">';
				if ($listing_details) {
					$sl_html .= '<div class="lst-dtls">
								<div class="details-left">
								<h3 class="address-hdng">
 ' . $address . '
  <a target="_blank" class="header__title__map-link" href="'.$address_link.'" aria-label="View map">
    <svg class="map-marker-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="32" height="32" aria-hidden="true" focusable="false">
      <g transform="translate(0 -1028.4)">
        <path d="m8 1030.4 8 1v19l-8-1z" fill="#ecf0f1"/>
        <path d="m2 1031.4 6-1v19l-6 1z" fill="#bdc3c7"/>
        <path d="m16 1031.4 6-1v19l-6 1z" fill="#bdc3c7"/>
        <path d="m3 1032.4 5-1v17l-5 1z" fill="#27ae60"/>
        <path d="m8 1031.4 8 1v17l-8-1z" fill="#2ecc71"/>
        <path d="m13 1c-1.657 0-3 1.3432-3 3s1.343 3 3 3 3-1.3432 3-3-1.343-3-3-3zm0 2c0.552 0 1 0.4477 1 1s-0.448 1-1 1-1-0.4477-1-1 0.448-1 1-1z" transform="translate(0 1028.4)" fill="#c0392b"/>
        <path d="m21 1048.4-5 1v-17l5-1z" fill="#27ae60"/>
        <path d="m5.6875 1031.8-2.3125 0.5 4.625 4.9v-2.9l-2.3125-2.5z" fill="#f39c12"/>
        <path d="m21 1046.4-5 1v-6l5-3z" fill="#f39c12"/>
        <path d="m21 1048.4-5 1v-6l5-3z" fill="#2980b9"/>
        <path d="m8 1042.4 8-1v6l-8-1z" fill="#f1c40f"/>
        <path d="m8 1044.4 8-1v6l-8-1z" fill="#3498db"/>
        <path d="m3 1045.4 5-3v4l-5 1z" fill="#f39c12"/>
        <path d="m3 1047.4 5-3v4l-5 1z" fill="#2980b9"/>
        <path d="m8 8.8008v-2.8985l4 8.6597h-1.469z" transform="translate(0 1028.4)" fill="#f1c40f"/>
        <path d="m13 1028.4c-2.209 0-4 1.8-4 4 0 0.7 0.1908 1.3 0.5156 1.9 0.0539 0.1 0.1105 0.2 0.1719 0.3l3.3125 5.8 3.312-5.8c0.051-0.1 0.095-0.2 0.141-0.2l0.031-0.1c0.325-0.6 0.516-1.2 0.516-1.9 0-2.2-1.791-4-4-4zm0 2c1.105 0 2 0.9 2 2s-0.895 2-2 2-2-0.9-2-2 0.895-2 2-2z" fill="#e74c3c"/>
      </g>
    </svg>
    <span class="sr-only">MAP</span>
  </a>
</h3>

							
									<p class="bed-bath-std">';
					if ($apfl_listings_display_beds == 'show') {
						if ($apfl_listings_bed_img) {
							$sl_html .= '<img class="bedimg" src="' . $apfl_listings_bed_img . '"><span>' . $bed_std . '</span>';
						} else {
							$sl_html .= '<img class="bedimg" src="' . $apfl_plugin_url . 'images/sleep.png"><span>' . $bed_std . '</span>';
						}
					}

					if ($apfl_listings_display_baths == 'show') {
						if ($apfl_listings_bath_img) {
							$sl_html .= '<img class="bathimg" src="' . $apfl_listings_bath_img . '"><span>' . $baths . '</span>';
						} else {
							$sl_html .= '<img class="bathimg" src="' . $apfl_plugin_url . 'images/bathtub.png"><span>' . $baths . '</span>';
						}
					}

					if ($place_area) {
						$sl_html .= '<span> | ' . $place_area . '</span>';
					}
					$sl_html .= '</p>';
					$sl_html .= '</div>';
					$apfl_details_display_price = get_option('apfl_details_display_price', 'show');
					$apfl_details_price_mo = get_option('apfl_details_price_mo', 'yes');
					$sl_html .= '<div class="details-right">';

					if ($apfl_details_display_price == 'show') {
						if ($apfl_details_price_mo == 'no') {
							$rent_price = str_replace('/mo', '', $rent_price);
						}
						// $sl_html .='<p class="rent-hdng"><img class="price-tag" src="'.$apfl_plugin_url.'images/dollar-tag.png">'.$rent_price.'</p>';
						$sl_html .= '<p class="rent-hdng">
<svg class="price-tag" xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 59.997 59.997">
<path d="M59.206,0.293c-0.391-0.391-1.023-0.391-1.414,0L54.084,4H30.802L1.532,33.511c-0.667,0.666-1.034,1.553-1.034,2.495
s0.367,1.829,1.034,2.495l20.466,20.466c0.687,0.687,1.588,1.03,2.491,1.03c0.906,0,1.814-0.346,2.508-1.04l28.501-29.271V5.414
l3.707-3.707C59.596,1.316,59.596,0.684,59.206,0.293z M23.412,57.553L2.946,37.087c-0.289-0.289-0.448-0.673-0.448-1.081
s0.159-0.792,0.451-1.084l8.85-8.923l22.545,22.546l-8.771,9.008C24.978,58.148,24.008,58.148,23.412,57.553z M53.499,28.874
L35.74,47.112L13.208,24.579L31.635,6h20.45l-4.833,4.833C46.461,10.309,45.516,10,44.499,10c-2.757,0-5,2.243-5,5s2.243,5,5,5
s5-2.243,5-5c0-1.017-0.309-1.962-0.833-2.753l4.833-4.833V28.874z M47.499,15c0,1.654-1.346,3-3,3s-3-1.346-3-3s1.346-3,3-3
c0.462,0,0.894,0.114,1.285,0.301l-1.992,1.992c-0.391,0.391-0.391,1.023,0,1.414C43.987,15.902,44.243,16,44.499,16
s0.512-0.098,0.707-0.293l1.992-1.992C47.385,14.106,47.499,14.538,47.499,15z "/>
</svg> ' . $rent_price . '</p>';
					}

					if ($availability) {
						$sl_html .= '<p style="margin-bottom: 1rem;">';
						if (preg_replace('/\s+/', '', $availability) == 'AvailableNow') {
							$sl_html .= '<img class="avail-now" src="' . $apfl_plugin_url . 'images/check.png">';
						}
						$sl_html .= '<span id="avail-txt">' . $availability . '</span>';
						$sl_html .= '</p>';
					}

					$phn_nmbr = explode('<br>', $phn_ctc);
					if (count($phn_nmbr) > 1) {
						$phn_nmbr = $phn_nmbr[1];
						// $sl_html .='<a class="call-top" href="tel:'.$phn_nmbr.'"><img class="call-now" src="'.$apfl_plugin_url.'images/phone-call.png"><strong>'.$phn_nmbr.'</strong></a>';
						$sl_html .= '<a class="call-top" href="tel:' . $phn_nmbr . '">
<svg class="call-now" viewBox="0 -0.5 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M6.24033 8.16795C6.99433 7.37295 7.26133 7.14995 7.58233 7.04695C7.80482 6.98843 8.03822 6.98499 8.26233 7.03695C8.55733 7.12295 8.63433 7.18795 9.60233 8.15095C10.4523 8.99695 10.5363 9.08895 10.6183 9.25095C10.7769 9.54253 10.8024 9.88825 10.6883 10.1999C10.6043 10.4349 10.4803 10.5909 9.96533 11.1089L9.62933 11.4459C9.54093 11.5356 9.51997 11.6719 9.57733 11.7839C10.3232 13.0565 11.3812 14.1179 12.6513 14.8679C12.7978 14.9465 12.9783 14.921 13.0973 14.8049L13.4203 14.4869C13.6199 14.2821 13.8313 14.0891 14.0533 13.9089C14.4015 13.6935 14.8362 13.6727 15.2033 13.8539C15.3823 13.9379 15.4423 13.9929 16.3193 14.8669C17.2193 15.7669 17.2483 15.7959 17.3493 16.0029C17.5379 16.3458 17.536 16.7618 17.3443 17.1029C17.2443 17.2949 17.1883 17.3649 16.6803 17.8839C16.3733 18.1979 16.0803 18.4839 16.0383 18.5259C15.6188 18.8727 15.081 19.043 14.5383 19.0009C13.5455 18.9101 12.5847 18.6029 11.7233 18.1009C9.81416 17.0894 8.18898 15.6155 6.99633 13.8139C6.73552 13.4373 6.50353 13.0415 6.30233 12.6299C5.76624 11.7109 5.48909 10.6638 5.50033 9.59995C5.54065 9.04147 5.8081 8.52391 6.24033 8.16795Z" stroke="#000000" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M14.8417 4.29409C14.4518 4.15416 14.0224 4.35677 13.8824 4.74664C13.7425 5.1365 13.9451 5.56598 14.335 5.70591L14.8417 4.29409ZM18.7868 10.0832C18.9333 10.4707 19.3661 10.666 19.7536 10.5195C20.141 10.373 20.3364 9.94021 20.1899 9.55276L18.7868 10.0832ZM13.6536 6.52142C13.2495 6.43018 12.848 6.68374 12.7568 7.08778C12.6655 7.49182 12.9191 7.89333 13.3231 7.98458L13.6536 6.52142ZM16.5696 11.1774C16.6676 11.5799 17.0733 11.8267 17.4757 11.7287C17.8782 11.6307 18.125 11.2251 18.0271 10.8226L16.5696 11.1774ZM14.335 5.70591C16.3882 6.44286 18.0153 8.04271 18.7868 10.0832L20.1899 9.55276C19.2631 7.10139 17.3084 5.17942 14.8417 4.29409L14.335 5.70591ZM13.3231 7.98458C14.9238 8.34607 16.1815 9.58301 16.5696 11.1774L18.0271 10.8226C17.5042 8.67475 15.8098 7.0084 13.6536 6.52142L13.3231 7.98458Z" fill="#000000"/>
</svg>
<strong>' . $phn_nmbr . '</strong></a>';
					}

					$sl_html .= '</div>
								</div>';
					$apfl_details_display_ttl = get_option('apfl_details_display_ttl', 'show');
					$apfl_details_ttl_tag = get_option('apfl_details_ttl_tag', 'p');
					if ($apfl_details_display_ttl == 'show') {
						$sl_html .= '<' . $apfl_details_ttl_tag . ' class="desctitle">' . $ttl . '</' . $apfl_details_ttl_tag . '>';
					}

					$sl_html .= '<p class="desc">' . $dsc . '</p>
								<div class="apfl-half">';
					if ($extra_fields) {
						$sl_html .= '<div class="extra">';
						foreach ($extra_fields as $field) {
							$sl_html .= '<div class="extra-half">';

							$extra_fld_obj = $field->find("h3", 0);
							if ($extra_fld_obj) {
								$sl_html .= '<h4>' . $extra_fld_obj->innertext . '</h4>';
							}

							$extra_fld_ul_obj = $field->find("ul", 0);
							if ($extra_fld_ul_obj) {
								$sl_html .= '<ul>' . $extra_fld_ul_obj->innertext . '</ul>';
							}

							$sl_html .= '</div>';
						}
						$sl_html .= '</div>';
					}
					$sl_html .= '</div>';
					$sl_html .= '<div class="apfl-half apply-sec">';
					$apfl_dtl_listings_display_apply = get_option('apfl_dtl_listings_display_apply', 'show');
					$apfl_dtl_listings_display_schedule = get_option('apfl_dtl_listings_display_schedule', 'show');
					$apfl_dtl_listings_display_contact = get_option('apfl_dtl_listings_display_contact', 'show');

					$schshowing_btn_link = '';
					if ($list_id && $apfl_dtl_listings_display_schedule == "show") {
						$schshowing_btn_link = $client_listings_url . '/listings/showings/new?listable_uid=' . $list_id;
						$schshowing_btn_link = apply_filters("apfl_schshowing_btn_link", $schshowing_btn_link, $schshowing_btn_link);
						$sl_html .= '<a id="schshowingBtn" class="sl-btns" target="_blank" href="' . $schshowing_btn_link . '">Schedule Showing</a>';
					}


					if ($apply_btn_link && $apfl_dtl_listings_display_apply == "show") {
						$apply_btn_link = $client_listings_url . $apply_btn_link;
						$apply_btn_link = apply_filters("apfl_apply_btn_link", $apply_btn_link, $apply_btn_link);
						$sl_html .= '<a id="applyBtn" class="sl-btns" target="_blank" href="' . $apply_btn_link . '">Apply Now</a>';
					}

					if ($contact_btn_link && $apfl_dtl_listings_display_contact == "show") {
						$sl_html .= '<a id="contactBtn" class="sl-btns" target="_blank" href="' . $contact_btn_link . '">Contact Us</a>';
					}

					$sl_html .= '
								<div class="apfl-share-buttons-wrapper">
									
									<a href="#" class="apfl-share-buttons" data-site="twitter" title="Share on Twitter">
										<i class="fab fa-x-twitter"></i>
									</a>

									<a href="#" class="apfl-share-buttons" data-site="facebook" title="Share on Facebook">
										<i class="fab fa-facebook-f"></i>
									</a>

									<a href="#" class="apfl-share-buttons" data-site="pinterest" title="Share on Pinterest">
										<i class="fab fa-pinterest"></i>
									</a>

									<a href="#" class="apfl-share-buttons" data-site="email" title="Share via Email">
										<i class="fas fa-envelope"></i>
									</a>

									<a href="#" class="apfl-share-buttons" data-site="copy" title="Copy Link">
										<i class="fas fa-link"></i>
									</a>
								</div>';

					$sl_html .= '<p>' . $phn_ctc . '</p></div>';
				}
				$sl_html .= '</div></div>';

				if ($main_gallery) {
					$sl_html .= '<script>
						var slideIndex = 1;
						showSlides(slideIndex);
						// Next/previous controls
						function plusSlides(n) {
						showSlides(slideIndex += n);
						}
						// Thumbnail image controls
						function currentSlide(n) {
						showSlides(slideIndex = n);
						}
						function showSlides(n) {
						var i;
						var slides = document.getElementsByClassName("mySlides");
						var dots = document.getElementsByClassName("demo");
						if (n > slides.length) {slideIndex = 1}
						if (n < 1) {slideIndex = slides.length}
						for (i = 0; i < slides.length; i++) {
							slides[i].style.display = "none";
						}
						for (i = 0; i < dots.length; i++) {
							dots[i].className = dots[i].className.replace(" active", "");
						}
						slides[slideIndex-1].style.display = "block";
						dots[slideIndex-1].className += " active";
						
						if(dots.length > 5){
							for (i = 0; i < dots.length; i++) {
								dots[i].style.display = "none";
							}
							if(slideIndex > 2 && slideIndex < dots.length-1){
								for(i=0; i<5; i++){
									dots[slideIndex-3+i].style.display = "block";
								}
							} else if(slideIndex < 3){
								for(i=0; i<5; i++){
									dots[i].style.display = "block";
								}
							} else if(slideIndex > dots.length-2){
								for(i=dots.length-1; i>dots.length-6; i--){
									dots[i].style.display = "block";
								}
							}
						}
						}
					</script>';
				}
			}
		}

		$sl_html .= '</div>';

		return $sl_html;
	}
}
