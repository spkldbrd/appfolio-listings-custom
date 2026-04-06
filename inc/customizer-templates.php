<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

if ($_POST) {
	if ( isset($_POST['apfl_template']) && !isset($_POST['apfl_cstmzr_sbmt']) ) {

		// $selected = (int) filter_var($_POST['apfl_template'], FILTER_SANITIZE_STRING);
		$selected = isset($_POST['apfl_template']) ? (int) sanitize_text_field($_POST['apfl_template']) : 0;

		if ($selected == 1) {

			update_option('apfl_template', '1');

			$apfl_template_1_arr = get_option('apfl_template_1_data');

			if (!$apfl_template_1_arr) {

				$apfl_custom_css = '.main-listings-page .listing-filters {
	padding: 20px 0;
	background-color: #232532;
}
.main-listings-page .listing-filters select {
	padding: 2px 25px 3px 10px;
	font-size: 16px;
}
.main-listings-page .listing-filters form select, .main-listings-page .listing-filters input[type=submit] {
	border: none;
	border-radius: 4px;
}
.main-listings-page .listing-filters input[type=submit] {
    cursor: pointer;
}
.main-listings-page #googlemap {
	height: 90vh;
	width: 40%;
	float: left;
}
.main-listings-page .all-listings{
	width: 60%;
	float: left;
	max-height: 90vh;
	overflow-y: auto;
	padding: 17px;
	-ms-overflow-style: none;
	scrollbar-width: none;
	display: grid;
    gap: 14px;
    grid-auto-rows: max-content;
    grid-template-columns: repeat(auto-fill,minmax(285px,1fr));
    margin-bottom: 15px;
}
.main-listings-page .all-listings::-webkit-scrollbar {
	display: none;
}
.main-listings-page .all-listings .details p{
	order: 2;
}
.main-listings-page .all-listings .listing-item {
	width: 100%;
	border-radius: 5px;
	overflow: hidden;
	padding-bottom: 55px;
	box-shadow: 0 2px 5px #0003;
	background: #fff;
}
.main-listings-page .all-listings .listing-item .btns{
	order: 5;
	position: absolute;
    width: 100%;
	bottom: 0;
}
.main-listings-page .all-listings .details .lstng_ttl {
	font-size: 16px;
	font-weight: normal;
	color: #666;
	order: 3;
	line-height: 1.3;
}
.main-listings-page .all-listings .listing-item span.rent-price-off{
	font-weight: normal;
	margin-bottom: 0;
	padding-bottom: 0;
	order: 1;
	font-size: 16px;
}
.main-listings-page .all-listings .listing-item span.rent-price-off b{
	font-size: 28px;
	color: #444;
	vertical-align: sub;
}
.main-listings-page .all-listings .listing-item .lstng-avail-off{
	order: 4;
	margin: 0;
	padding-top: 0;
	padding-bottom: 0;
}
.main-listings-page .listing-item .btns a{
	padding: 8px 10px;
}
.main-listings-page .listing-item .btns .more_detail_btn {
	background: #27547c;
	margin: 0 0 0 0;
	font-weight:600;
	text-align:center;
	width: 50%;
}
.main-listings-page .listing-item .btns .apply_btn{
	background: #9b9b96;
	margin: 0 0 0 0;
	font-weight:600;
	text-align:center;
	width: 50%;
}
.main-listings-page .listing-item .btns .schedule_btn{
	background: #9b9b96;
	margin: 0 0 0 0;
	font-weight:600;
	text-align:center;
	width: 100%;
}
.main-listings-page .all-listings .details {
	padding: 15px 0 0 0;
	display: flex;
	flex-direction: column;
	gap: 15px;
	background: #fff;
}
.main-listings-page .all-listings .address{
	align-items: flex-end;
    background: linear-gradient(#0000,#000);
    bottom: 0;
    color: #fff;
    display: flex;
    font-size: 20px;
    height: 50%;
    left: 0;
    line-height: 1.25;
    margin: 0;
    padding: 10px 15px;
    pointer-events: none;
    position: absolute;
    text-align: left;
    text-shadow: 0 1px 2px #0009;
    width: 100%;
}
.main-listings-page .all-listings .lstng_ttl, .main-listings-page .all-listings .details p{
	padding: 0 0 0 10px;
}

@media only screen and (max-width: 1366px){
	.main-listings-page .listing-item {
		width: 49%;
	}
}
@media only screen and (max-width: 1348px){
	.listing-filters select, .listing-filters input[type=submit]{
		margin-bottom: 0;
		margin-top: 15px;
	}
	.listing-filters form{
		margin-bottom: 15px;
	}
}
@media screen and (max-width: 767px) {
	.listing-filters select, .listing-filters input[type=submit] {
		width: 48%;
		margin: 1%;
		font-size: 13px;
	}
	.main-listings-page #googlemap {
		width: 100%;
		height: 246px;
	}
	 .main-listings-page .all-listings {
		width: 100%;
		height: auto;
		overflow-y: hidden;
	}
 
	.main-listings-page .listing-item {
		width: 100%;
	}
}';

				$temp_1_args = array(
					'apfl_filters_textarea_input' => 'show',
					'apfl_filters_cat' => 'hide',
					'apfl_filters_dog' => 'hide',

					'apfl_pro_enable_searching' => 'show',
					'apfl_filters_minrent' => 'show',
					'apfl_filters_maxrent' => 'show',
					'apfl_filters_bed' => 'show',
					'apfl_filters_bath' => 'show',
					'apfl_filters_cities' => 'show',
					'apfl_filters_zip' => 'show',
					'apfl_filters_movein' => 'show',
					'apfl_filters_sorting' => 'show',

					'apfl_listings_search_color' => '#000000',
					'apfl_listings_search_bg' => '#ffffff',

					'apfl_listings_display_price' => 'show',
					'apfl_details_display_price' => 'show',
					'apfl_listings_price_pos' => 'offimage',
					'apfl_listings_price_color' => '#000000',
					'apfl_details_price_color' => '#000000',
					'apfl_listings_price_bg' => '',

					'apfl_listings_display_avail' => 'show',
					'apfl_listings_avail_pos' => 'offimage',
					'apfl_listings_avail_color' => '#000000',
					'apfl_listings_avail_bg' => '',

					'apfl_listings_display_ttl' => 'show',
					'apfl_listings_ttl_tag' => 'h2',

					'apfl_listings_display_address' => 'show',

					'apfl_listings_display_beds' => 'show',
					'apfl_listings_bed_img' => plugin_dir_url(__FILE__) . '../images/sleep.png',

					'apfl_listings_display_baths' => 'show',
					'apfl_listings_bath_img' => plugin_dir_url(__FILE__) . '../images/bathtub.png',

					'apfl_listings_display_area' => 'show',
					'apfl_listings_area_img' => plugin_dir_url(__FILE__) . '../images/area.png',

					'apfl_listings_display_pets' => 'show',
					'apfl_listings_pet_img' => plugin_dir_url(__FILE__) . '../images/pet.png',
					'apfl_listings_no_pet_img' => plugin_dir_url(__FILE__) . '../images/no-pet.png',

					'apfl_listings_display_detail' => 'show',
					'apfl_listings_detail_color' => '#ffffff',
					'apfl_listings_detail_bg' => '#27547c',
					'apfl_listings_detail_hover_color' => '#ffffff',
					'apfl_listings_detail_hover_bg' => '#444444',

					'apfl_listings_display_apply' => 'show',
					'apfl_listings_apply_color' => '#ffffff',
					'apfl_listings_apply_bg' => '#9b9b96',
					'apfl_listings_apply_hover_color' => '#ffffff',
					'apfl_listings_apply_hover_bg' => '#444444',

					'apfl_listings_display_schedule' => 'hide',
					'apfl_listings_schedule_color' => '#ffffff',
					'apfl_listings_schedule_bg' => '#598fcd',
					'apfl_listings_schedule_hover_color' => '#ffffff',
					'apfl_listings_schedule_hover_bg' => '#444444',

					'apfl_listings_banner_bg' => '#232532',
					'apfl_listings_banner_image' => 'hide',
					'apfl_listings_banner_image_url' => '',


					'apfl_listings_banner_heading_font_size' => '50px',
					'apfl_listings_banner_heading_font_weight' => '400',
					'apfl_listings_banner_heading_color' => '#fff',
					'apfl_listings_banner_heading_line_height' => '1',
					'apfl_listings_banner_heading_text_transform' => 'uppercase',
					'apfl_listings_banner_heading_text_align' => 'center',
					'apfl_listings_banner_heading_padding_top' => '0px',
					'apfl_listings_banner_heading_padding_bottom' => '0px',
					'apfl_listings_banner_heading_padding_left' => '0px',
					'apfl_listings_banner_heading_padding_right' => '0px',
					'apfl_display_large_images' => 'hide',
					'apfl_display_city_state' => 'hide',

					'apfl_page_sub_hdng' => '',

					'apfl_custom_css' => $apfl_custom_css
				);

				update_option('apfl_template_1_data', $temp_1_args);

			}

			// Template Saved message
			echo '<div class="notice notice-success is-dismissible"><p>Template Updated!</p></div>';

		} else if ($selected == 2) {

			update_option('apfl_template', '2');

			$apfl_template_2_arr = get_option('apfl_template_2_data');

			if (!$apfl_template_2_arr) {

				$temp_2_args = array(
					'apfl_filters_textarea_input' => 'show',
					'apfl_filters_cat' => 'show',
					'apfl_filters_dog' => 'show',

					'apfl_pro_enable_searching' => 'show',
					'apfl_filters_minrent' => 'show',
					'apfl_filters_maxrent' => 'show',
					'apfl_filters_bed' => 'show',
					'apfl_filters_bath' => 'show',
					'apfl_filters_cities' => 'show',
					'apfl_filters_zip' => 'show',
					'apfl_filters_movein' => 'show',
					'apfl_filters_sorting' => 'show',

					'apfl_listings_search_color' => '#ffffff',
					'apfl_listings_search_bg' => '#ff6600',

					'apfl_listings_display_price' => 'show',
					'apfl_details_display_price' => 'show',
					'apfl_listings_price_pos' => 'onimage',
					'apfl_listings_price_color' => '#ffffff',
					'apfl_details_price_color' => '#ffffff',
					'apfl_listings_price_bg' => '#',

					'apfl_listings_display_avail' => 'show',
					'apfl_listings_avail_pos' => 'onimage',
					'apfl_listings_avail_color' => '#ffffff',
					'apfl_listings_avail_bg' => '#ff6600',

					'apfl_listings_display_ttl' => 'show',
					'apfl_listings_ttl_tag' => 'h4',

					'apfl_listings_display_address' => 'show',

					'apfl_listings_display_beds' => 'show',
					'apfl_listings_bed_img' => plugin_dir_url(__FILE__) . '../images/sleep.png',

					'apfl_listings_display_baths' => 'show',
					'apfl_listings_bath_img' => plugin_dir_url(__FILE__) . '../images/bathtub.png',

					'apfl_listings_display_area' => 'show',
					'apfl_listings_area_img' => plugin_dir_url(__FILE__) . '../images/area.png',

					'apfl_listings_display_pets' => 'show',
					'apfl_listings_pet_img' => plugin_dir_url(__FILE__) . '../images/pet.png',
					'apfl_listings_no_pet_img' => plugin_dir_url(__FILE__) . '../images/no-pet.png',

					'apfl_listings_display_detail' => 'show',
					'apfl_listings_detail_color' => '#ffffff',
					'apfl_listings_detail_bg' => '#598fcd',
					'apfl_listings_detail_hover_color' => '#ffffff',
					'apfl_listings_detail_hover_bg' => '#444444',

					'apfl_listings_display_apply' => 'show',
					'apfl_listings_apply_color' => '#ffffff',
					'apfl_listings_apply_bg' => '#598fcd',
					'apfl_listings_apply_hover_color' => '#ffffff',
					'apfl_listings_apply_hover_bg' => '#444444',

					'apfl_listings_display_schedule' => 'hide',
					'apfl_listings_schedule_color' => '#ffffff',
					'apfl_listings_schedule_bg' => '#598fcd',
					'apfl_listings_schedule_hover_color' => '#ffffff',
					'apfl_listings_schedule_hover_bg' => '#444444',

					'apfl_listings_banner_bg' => '#598fcd',
					'apfl_listings_banner_image' => 'hide',
					'apfl_listings_banner_image_url' => '',

					'apfl_listings_banner_heading_font_size' => '50px',
					'apfl_listings_banner_heading_font_weight' => '400',
					'apfl_listings_banner_heading_color' => '#fff',
					'apfl_listings_banner_heading_line_height' => '1',
					'apfl_listings_banner_heading_text_transform' => 'uppercase',
					'apfl_listings_banner_heading_text_align' => 'center',
					'apfl_listings_banner_heading_padding_top' => '0px',
					'apfl_listings_banner_heading_padding_bottom' => '0px',
					'apfl_listings_banner_heading_padding_left' => '0px',
					'apfl_listings_banner_heading_padding_right' => '0px',
					'apfl_display_large_images' => 'hide',
					'apfl_display_city_state' => 'hide',

					'apfl_page_sub_hdng' => '',

					'apfl_custom_css' => ''
				);

				update_option('apfl_template_2_data', $temp_2_args);
			}

			// Template Saved message
			echo '<div class="notice notice-success is-dismissible"><p>Template Updated!</p></div>';

		} else {
			// custom template
			delete_option('apfl_template');
		}

	}
}
