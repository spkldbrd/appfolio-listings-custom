<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}
function apfl_customizer_css()
{

	$apfl_template = (int) get_option('apfl_template');

	if (!$apfl_template) {
		$apfl_listings_banner_heading_font_size = get_option('apfl_listings_banner_heading_font_size', '30px');
		$apfl_listings_banner_heading_font_weight = get_option('apfl_listings_banner_heading_font_weight', '400');
		$apfl_listings_banner_heading_color = get_option('apfl_listings_banner_heading_color', '#fff');
		$apfl_listings_banner_heading_line_height = get_option('apfl_listings_banner_heading_line_height', '1');
		$apfl_listings_banner_heading_text_transform = get_option('apfl_listings_banner_heading_text_transform', 'uppercase');
		$apfl_listings_banner_heading_text_align = get_option('apfl_listings_banner_heading_text_align', 'center');
		$apfl_listings_banner_heading_padding_top = get_option('apfl_listings_banner_heading_padding_top', '0px');
		$apfl_listings_banner_heading_padding_bottom = get_option('apfl_listings_banner_heading_padding_bottom', '30px');
		$apfl_listings_banner_heading_padding_left = get_option('apfl_listings_banner_heading_padding_left', '0px');
		$apfl_listings_banner_heading_padding_right = get_option('apfl_listings_banner_heading_padding_right', '0px');

		$apfl_columns_cnt = get_option('apfl_columns_cnt');

		$apfl_listings_search_color = get_option('apfl_listings_search_color');
		$apfl_listings_search_bg = get_option('apfl_listings_search_bg');

		$apfl_listings_price_color = get_option('apfl_listings_price_color');
		$apfl_listings_price_bg = get_option('apfl_listings_price_bg');

		$apfl_listings_avail_color = get_option('apfl_listings_avail_color');
		$apfl_listings_avail_bg = get_option('apfl_listings_avail_bg');

		$apfl_listings_detail_color = get_option('apfl_listings_detail_color');
		$apfl_listings_detail_bg = get_option('apfl_listings_detail_bg');
		$apfl_listings_detail_hover_color = get_option('apfl_listings_detail_hover_color');
		$apfl_listings_detail_hover_bg = get_option('apfl_listings_detail_hover_bg');

		$apfl_listings_apply_color = get_option('apfl_listings_apply_color');
		$apfl_listings_apply_bg = get_option('apfl_listings_apply_bg');
		$apfl_listings_apply_hover_color = get_option('apfl_listings_apply_hover_color');
		$apfl_listings_apply_hover_bg = get_option('apfl_listings_apply_hover_bg');

		$apfl_listings_schedule_color = get_option('apfl_listings_schedule_color');
		$apfl_listings_schedule_bg = get_option('apfl_listings_schedule_bg');
		$apfl_listings_schedule_hover_color = get_option('apfl_listings_schedule_hover_color');
		$apfl_listings_schedule_hover_bg = get_option('apfl_listings_schedule_hover_bg');

		$apfl_custom_css = get_option('apfl_custom_css');

	} else {

		$template_data = get_option('apfl_template_' . $apfl_template . '_data');

		if (is_array($template_data) && array_key_exists("apfl_columns_cnt", $template_data)) {
			$apfl_columns_cnt = $template_data['apfl_columns_cnt'];
		} else {
			$apfl_columns_cnt = 3;
		}

		$apfl_listings_banner_bg = isset($template_data['apfl_listings_banner_bg']) ? $template_data['apfl_listings_banner_bg'] : '#232532';
		$apfl_listings_banner_heading_font_size = isset($template_data['apfl_listings_banner_heading_font_size']) ? $template_data['apfl_listings_banner_heading_font_size'] : '50px';
		$apfl_listings_banner_heading_font_weight = isset($template_data['apfl_listings_banner_heading_font_weight']) ? $template_data['apfl_listings_banner_heading_font_weight'] : '400';
		$apfl_listings_banner_heading_color = isset($template_data['apfl_listings_banner_heading_color']) ? $template_data['apfl_listings_banner_heading_color'] : '#fff';
		$apfl_listings_banner_heading_line_height = isset($template_data['apfl_listings_banner_heading_line_height']) ? $template_data['apfl_listings_banner_heading_line_height'] : '1';
		$apfl_listings_banner_heading_text_transform = isset($template_data['apfl_listings_banner_heading_text_transform']) ? $template_data['apfl_listings_banner_heading_text_transform'] : 'uppercase';
		$apfl_listings_banner_heading_text_align = isset($template_data['apfl_listings_banner_heading_text_align']) ? $template_data['apfl_listings_banner_heading_text_align'] : 'center';
		$apfl_listings_banner_heading_padding_top = isset($template_data['apfl_listings_banner_heading_padding_top']) ? $template_data['apfl_listings_banner_heading_padding_top'] : '0px';
		$apfl_listings_banner_heading_padding_bottom = isset($template_data['apfl_listings_banner_heading_padding_bottom']) ? $template_data['apfl_listings_banner_heading_padding_bottom'] : '0px';
		$apfl_listings_banner_heading_padding_left = isset($template_data['apfl_listings_banner_heading_padding_left']) ? $template_data['apfl_listings_banner_heading_padding_left'] : '0px';
		$apfl_listings_banner_heading_padding_right = isset($template_data['apfl_listings_banner_heading_padding_right']) ? $template_data['apfl_listings_banner_heading_padding_right'] : '0px';

		$apfl_listings_search_color = $template_data['apfl_listings_search_color'];
		$apfl_listings_search_bg = $template_data['apfl_listings_search_bg'];

		$apfl_listings_price_color = $template_data['apfl_listings_price_color'];
		$apfl_listings_price_bg = $template_data['apfl_listings_price_bg'];

		$apfl_listings_avail_color = $template_data['apfl_listings_avail_color'];
		$apfl_listings_avail_bg = $template_data['apfl_listings_avail_bg'];

		$apfl_listings_detail_color = $template_data['apfl_listings_detail_color'];
		$apfl_listings_detail_bg = $template_data['apfl_listings_detail_bg'];
		$apfl_listings_detail_hover_color = $template_data['apfl_listings_detail_hover_color'];
		$apfl_listings_detail_hover_bg = $template_data['apfl_listings_detail_hover_bg'];

		$apfl_listings_apply_color = $template_data['apfl_listings_apply_color'];
		$apfl_listings_apply_bg = $template_data['apfl_listings_apply_bg'];
		$apfl_listings_apply_hover_color = $template_data['apfl_listings_apply_hover_color'];
		$apfl_listings_apply_hover_bg = $template_data['apfl_listings_apply_hover_bg'];

		$apfl_listings_schedule_color = isset($template_data['apfl_listings_schedule_color']) ? $template_data['apfl_listings_schedule_color'] : '#ffffff';
		$apfl_listings_schedule_bg = isset($template_data['apfl_listings_schedule_bg']) ? $template_data['apfl_listings_schedule_bg'] : '#598fcd';
		$apfl_listings_schedule_hover_color = isset($template_data['apfl_listings_schedule_hover_color']) ? $template_data['apfl_listings_schedule_hover_color'] : '#ffffff';
		$apfl_listings_schedule_hover_bg = isset($template_data['apfl_listings_schedule_hover_bg']) ? $template_data['apfl_listings_schedule_hover_bg'] : '#444444';


		$apfl_custom_css = $template_data['apfl_custom_css'];

	}

	$apfl_details_price_color = get_option('apfl_details_price_color', '#ff6600');

	$apfl_dtl_listings_display_apply = get_option('apfl_dtl_listings_display_apply', 'show'); 
	$apfl_dtl_listings_display_schedule = get_option('apfl_dtl_listings_display_schedule', 'show'); 
	$apfl_dtl_listings_display_contact = get_option('apfl_dtl_listings_display_contact', 'show'); 

	$apfl_dtl_listings_apply_color = get_option('apfl_dtl_listings_apply_color', '#ffffff'); 
	$apfl_dtl_listings_apply_bg = get_option('apfl_dtl_listings_apply_bg', '#27547c');
	$apfl_dtl_listings_apply_hover_color = get_option('apfl_dtl_listings_apply_hover_color', '#ffffff');
	$apfl_dtl_listings_apply_hover_bg = get_option('apfl_dtl_listings_apply_hover_bg', '#444444');

	$apfl_dtl_listings_schedule_color = get_option('apfl_dtl_listings_schedule_color', '#ffffff');
	$apfl_dtl_listings_schedule_bg = get_option('apfl_dtl_listings_schedule_bg', '#27547c');
	$apfl_dtl_listings_schedule_hover_color = get_option('apfl_dtl_listings_schedule_hover_color', '#ffffff');
	$apfl_dtl_listings_schedule_hover_bg = get_option('apfl_dtl_listings_schedule_hover_bg', '#444444');

	$apfl_dtl_listings_contact_color = get_option('apfl_dtl_listings_contact_color', '#ffffff');
	$apfl_dtl_listings_contact_bg = get_option('apfl_dtl_listings_contact_bg', '#27547c');
	$apfl_dtl_listings_contact_hover_color = get_option('apfl_dtl_listings_contact_hover_color', '#ffffff');
	$apfl_dtl_listings_contact_hover_bg = get_option('apfl_dtl_listings_contact_hover_bg', '#444444');


	echo '<style>';
	echo '@media only screen and (min-width: 768px) {';
	if ($apfl_columns_cnt == 5) {
		echo '.main-listings-page .all-listings{
						margin: auto;
					}
					#apfl-listings-container.main-listings-page .listing-item{
						width: 19%;
					}';
	} else if ($apfl_columns_cnt == 4) {
		echo '.main-listings-page .all-listings{
						margin: auto;
					}
					#apfl-listings-container.main-listings-page .listing-item{
						width: 24%;
					}';
	} else if ($apfl_columns_cnt == 3) {
		echo '.main-listings-page .all-listings{
						margin: auto;
					}
					#apfl-listings-container.main-listings-page .listing-item{
						width: 32.333%;
					}';
	} else if ($apfl_columns_cnt == 2 || $apfl_columns_cnt == 1) {
		echo '.main-listings-page .all-listings{
						margin: auto;
					}
					#apfl-listings-container.main-listings-page .listing-item{
						width: 49%;
					}
					.main-listings-page .listing-item #list-img img{
						height: 450px;
					}';
	} else if ($apfl_columns_cnt == 1) { // Temporarily disabled
		echo '.main-listings-page .all-listings{
						margin: auto;
					}
					#apfl-listings-container.main-listings-page .listing-item{
						width: 100%;
						margin-left: 0;
						margin-right: 0;
						display: flex;
						flex-wrap: wrap;
						align-items: normal;
					}
					.main-listings-page .listing-item > a{
						display: block;
						width: 40%;
					}
					.main-listings-page .listing-item .details{
						width: 60%;
						margin-top: 0;
					}
					.main-listings-page .listing-item #list-img img{
						height: 450px;
					}';
	}
	echo '}';

	echo '@media only screen and (max-width: 1200px) and (min-width: 768px) {';
	if ($apfl_columns_cnt == 5 || $apfl_columns_cnt == 4) {
		echo '#apfl-listings-container.main-listings-page .listing-item{
						width: 49%;
					}';
	}
	echo '}';

	if ($apfl_listings_banner_bg) {
		echo '.main-listings-page .listing-filters {
				background-color: ' . $apfl_listings_banner_bg . ' !important;
			}';
	}


	echo '.main-listings-page .listing-filters .apfl_page_sub_hdng {';

	// Font Size
	if ($apfl_listings_banner_heading_font_size) {
		echo 'font-size: ' . $apfl_listings_banner_heading_font_size . ';';
	}

	// Font Weight
	if ($apfl_listings_banner_heading_font_weight) {
		echo 'font-weight: ' . $apfl_listings_banner_heading_font_weight . ';';
	}

	// Color
	if ($apfl_listings_banner_heading_color) {
		echo 'color: ' . $apfl_listings_banner_heading_color . ';';
	}

	// Line Height
	if ($apfl_listings_banner_heading_line_height) {
		echo 'line-height: ' . $apfl_listings_banner_heading_line_height . ';';
	}

	// Text Transform
	if ($apfl_listings_banner_heading_text_transform) {
		echo 'text-transform: ' . $apfl_listings_banner_heading_text_transform . ';';
	}

	// Text Align
	if ($apfl_listings_banner_heading_text_align) {
		echo 'text-align: ' . $apfl_listings_banner_heading_text_align . ';';
	}

	// Padding Top
	if ($apfl_listings_banner_heading_padding_top) {
		echo 'padding-top: ' . $apfl_listings_banner_heading_padding_top . ';';
	}

	// Padding Bottom
	if ($apfl_listings_banner_heading_padding_bottom) {
		echo 'padding-bottom: ' . $apfl_listings_banner_heading_padding_bottom . ';';
	}

	// Padding Left
	if ($apfl_listings_banner_heading_padding_left) {
		echo 'padding-left: ' . $apfl_listings_banner_heading_padding_left . ';';
	}

	// Padding Right
	if ($apfl_listings_banner_heading_padding_right) {
		echo 'padding-right: ' . $apfl_listings_banner_heading_padding_right . ';';
	}
	echo '}';


	if ($apfl_listings_search_color) {
		echo '.listing-filters input[type="submit"]{
				color: ' . $apfl_listings_search_color . ';
			}';
	}
	if ($apfl_listings_search_bg) {
		echo '.listing-filters input[type="submit"]{
				background: ' . $apfl_listings_search_bg . ';
			}';
	}

	if ($apfl_listings_price_color) {
		echo '.main-listings-page .listing-item span.rent-price, .main-listings-page .listing-item span.rent-price-off{
				color: ' . $apfl_listings_price_color . ';
			}';
	}
	if ($apfl_listings_price_bg) {
		echo '.main-listings-page .listing-item span.rent-price, .main-listings-page .listing-item span.rent-price-off{
				background: ' . $apfl_listings_price_bg . ';
			}';
	}

	if ($apfl_listings_avail_color) {
		echo '.main-listings-page .listing-item span.lstng-avail, .main-listings-page .listing-item span.lstng-avail-off{
				color: ' . $apfl_listings_avail_color . ';
			}';
	}
	if ($apfl_listings_avail_bg) {
		echo '.main-listings-page .listing-item span.lstng-avail, .main-listings-page .listing-item span.lstng-avail-off{
				background: ' . $apfl_listings_avail_bg . ';
			}';
	}

	if ($apfl_listings_detail_color) {
		echo '.main-listings-page .listing-item .btns .more_detail_btn{
				color: ' . $apfl_listings_detail_color . ';
			}';
	}
	if ($apfl_listings_detail_bg) {
		echo '.main-listings-page .listing-item .btns .more_detail_btn{
				background: ' . $apfl_listings_detail_bg . ';
			}';
	}
	if ($apfl_listings_detail_hover_color) {
		echo '.main-listings-page .listing-item .btns .more_detail_btn:hover{
				color: ' . $apfl_listings_detail_hover_color . ';
			}';
	}
	if ($apfl_listings_detail_hover_bg) {
		echo '.main-listings-page .listing-item .btns .more_detail_btn:hover{
				background: ' . $apfl_listings_detail_hover_bg . ';
			}';
	}

	if ($apfl_listings_apply_color) {
		echo '.main-listings-page .listing-item .btns .apply_btn{
				color: ' . $apfl_listings_apply_color . ';
			}';
	}
	if ($apfl_listings_apply_bg) {
		echo '.main-listings-page .listing-item .btns .apply_btn{
				background: ' . $apfl_listings_apply_bg . ';
			}';
	}
	if ($apfl_listings_apply_hover_color) {
		echo '.main-listings-page .listing-item .btns .apply_btn:hover{
				color: ' . $apfl_listings_apply_hover_color . ';
			}';
	}
	if ($apfl_listings_apply_hover_bg) {
		echo '.main-listings-page .listing-item .btns .apply_btn:hover{
				background: ' . $apfl_listings_apply_hover_bg . ';
			}';
	}

	if ($apfl_listings_schedule_color) {
		echo '.main-listings-page .listing-item .btns .schedule_btn{
				color: ' . $apfl_listings_schedule_color . ';
			}';
	}
	if ($apfl_listings_schedule_bg) {
		echo '.main-listings-page .listing-item .btns .schedule_btn{
				background: ' . $apfl_listings_schedule_bg . ';
			}';
	}
	if ($apfl_listings_schedule_hover_color) {
		echo '.main-listings-page .listing-item .btns .schedule_btn:hover{
				color: ' . $apfl_listings_schedule_hover_color . ';
			}';
	}
	if ($apfl_listings_schedule_hover_bg) {
		echo '.main-listings-page .listing-item .btns .schedule_btn:hover{
				background: ' . $apfl_listings_schedule_hover_bg . ';
			}';
	}

	if ($apfl_details_price_color) {
		echo '.details-right .rent-hdng, .details-top .rent-hdng{
				color: ' . $apfl_details_price_color . ';
			}';
	}

	// Apply button
	if ($apfl_dtl_listings_display_apply === 'show') {
		echo '.apfl-sl-wrapper #applyBtn, .apfl-sl-mdrn-wrapper #applyBtn {';
		if (!empty($apfl_dtl_listings_apply_color)) {
			echo 'color: ' . $apfl_dtl_listings_apply_color . ';';
		}
		if (!empty($apfl_dtl_listings_apply_bg)) {
			echo 'background-color: ' . $apfl_dtl_listings_apply_bg . ';';
		}
		echo '}';
	
		echo '.apfl-sl-wrapper #applyBtn:hover,  .apfl-sl-mdrn-wrapper #applyBtn:hover{';
		if (!empty($apfl_dtl_listings_apply_hover_color)) {
			echo 'color: ' . $apfl_dtl_listings_apply_hover_color . ';';
		}
		if (!empty($apfl_dtl_listings_apply_hover_bg)) {
			echo 'background-color: ' . $apfl_dtl_listings_apply_hover_bg . ';';
		}
		echo '}';
	}

	// contact button

	if ($apfl_dtl_listings_display_contact === 'show') {
		echo '.apfl-sl-wrapper #contactBtn, .apfl-sl-mdrn-wrapper #contactBtn {';
		if (!empty($apfl_dtl_listings_contact_color)) {
			echo 'color: ' . $apfl_dtl_listings_contact_color . ';';
		}
		if (!empty($apfl_dtl_listings_contact_bg)) {
			echo 'background-color: ' . $apfl_dtl_listings_contact_bg . ';';
		}
		echo '}';
	
		echo '.apfl-sl-wrapper #contactBtn:hover, .apfl-sl-mdrn-wrapper #contactBtn:hover {';
		if (!empty($apfl_dtl_listings_contact_hover_color)) {
			echo 'color: ' . $apfl_dtl_listings_contact_hover_color . ';';
		}
		if (!empty($apfl_dtl_listings_contact_hover_bg)) {
			echo 'background-color: ' . $apfl_dtl_listings_contact_hover_bg . ';';
		}
		echo '}';
	}

	// Schedule showing
	if ($apfl_dtl_listings_display_schedule === 'show') {
		echo '.apfl-sl-wrapper #schshowingBtn, .apfl-sl-mdrn-wrapper #schshowingBtn {';
		if (!empty($apfl_dtl_listings_schedule_color)) {
			echo 'color: ' . $apfl_dtl_listings_schedule_color . ';';
		}
		if (!empty($apfl_dtl_listings_schedule_bg)) {
			echo 'background-color: ' . $apfl_dtl_listings_schedule_bg . ';';
		}
		echo '}';
	
		echo '.apfl-sl-wrapper #schshowingBtn:hover, .apfl-sl-mdrn-wrapper #schshowingBtn:hover {';
		if (!empty($apfl_dtl_listings_schedule_hover_color)) {
			echo 'color: ' . $apfl_dtl_listings_schedule_hover_color . ';';
		}
		if (!empty($apfl_dtl_listings_schedule_hover_bg)) {
			echo 'background-color: ' . $apfl_dtl_listings_schedule_hover_bg . ';';
		}
		echo '}';
	}

	// Template layout
	if($apfl_template && $apfl_template == 1){
		echo '#apfl-listings-container.main-listings-page:has(#googlemap) .all-listings {
			width: 60%;
		}';
	}

	echo wp_unslash( $apfl_custom_css );

	echo '</style>';
}
add_action('wp_head', 'apfl_customizer_css');
