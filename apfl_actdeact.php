<?php
// Handle plugin activation to save default option values
class Apfl_Actdeact
{
	static function apfl_plugin_activate()
	{

		$apfl_url = get_option('apfl_template');
		$apfl_template = (int) get_option('apfl_template');
		if (!$apfl_url && !$apfl_template) {
			// first time install - setup template & it's data and fallback custom template data

			update_option('apfl_template', '1');

			$apfl_template_1_arr = get_option('apfl_template_1_data');

			if (!$apfl_template_1_arr) {

				$apfl_custom_css = '.main-listings-page .listing-filters {
	padding: 20px 0;
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
	box-sizing: border-box;
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
	box-sizing: border-box;
}
.main-listings-page .listing-item .btns .more_detail_btn {
	margin: 0 0 0 0;
	font-weight:600;
	text-align:center;
	width: 50%;
}
.main-listings-page .listing-item .btns .apply_btn{
	margin: 0 0 0 0;
	font-weight:600;
	text-align:center;
	width: 50%;
}

.main-listings-page .listing-item .btns .schedule_btn{
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
	margin: 0;
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
					'apfl_filters_cat' => 'hide',
					'apfl_filters_dog' => 'hide',

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
					'apfl_listings_price_pos' => 'offimage',
					'apfl_listings_price_color' => '#000000',
					'apfl_listings_price_bg' => '',

					'apfl_listings_display_avail' => 'show',
					'apfl_listings_avail_pos' => 'offimage',
					'apfl_listings_avail_color' => '#000000',
					'apfl_listings_avail_bg' => '',

					'apfl_listings_display_ttl' => 'show',
					'apfl_listings_ttl_tag' => 'h2',

					'apfl_listings_display_address' => 'show',

					'apfl_listings_display_beds' => 'show',
					'apfl_listings_bed_img' => plugin_dir_url(__FILE__) . 'images/sleep.png',

					'apfl_listings_display_baths' => 'show',
					'apfl_listings_bath_img' => plugin_dir_url(__FILE__) . 'images/bathtub.png',

					'apfl_listings_display_area' => 'show',
					'apfl_listings_area_img' => plugin_dir_url(__FILE__) . 'images/area.png',

					'apfl_listings_display_pets' => 'show',
					'apfl_listings_pet_img' => plugin_dir_url(__FILE__) . 'images/pet.png',
					'apfl_listings_no_pet_img' => plugin_dir_url(__FILE__) . 'images/no-pet.png',

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
					'apfl_display_large_images' => 'hide',
					'apfl_listings_schedule_color' => '#ffffff',
					'apfl_listings_schedule_bg' => '#9b9b96',
					'apfl_listings_schedule_hover_color' => '#ffffff',
					'apfl_listings_schedule_hover_bg' => '#444444',

					'apfl_custom_css' => $apfl_custom_css
				);

				update_option('apfl_template_1_data', $temp_1_args);

			}




			$apfl_columns_cnt = get_option('apfl_columns_cnt');
			if (!$apfl_columns_cnt) {
				update_option('apfl_columns_cnt', '3');
			}
			$apfl_listings_per_page = get_option('apfl_listings_per_page');
			if (!$apfl_listings_per_page) {
				update_option('apfl_listings_per_page', '10');
			}

			$apfl_pro_enable_searching = get_option('apfl_pro_enable_searching');
			if (!$apfl_pro_enable_searching) {
				update_option('apfl_pro_enable_searching', 'show');
			}
			$apfl_filters_cat = get_option('apfl_filters_cat');
			if (!$apfl_filters_cat) {
				update_option('apfl_filters_cat', 'show');
			}
			$apfl_filters_dog = get_option('apfl_filters_dog');
			if (!$apfl_filters_dog) {
				update_option('apfl_filters_dog', 'show');
			}
			$apfl_filters_minrent = get_option('apfl_filters_minrent');
			if (!$apfl_filters_minrent) {
				update_option('apfl_filters_minrent', 'show');
			}
			$apfl_filters_maxrent = get_option('apfl_filters_maxrent');
			if (!$apfl_filters_maxrent) {
				update_option('apfl_filters_maxrent', 'show');
			}
			$apfl_filters_bed = get_option('apfl_filters_bed');
			if (!$apfl_filters_bed) {
				update_option('apfl_filters_bed', 'show');
			}
			$apfl_filters_bath = get_option('apfl_filters_bath');
			if (!$apfl_filters_bath) {
				update_option('apfl_filters_bath', 'show');
			}
			$apfl_filters_cities = get_option('apfl_filters_cities');
			if (!$apfl_filters_cities) {
				update_option('apfl_filters_cities', 'show');
			}
			$apfl_filters_zip = get_option('apfl_filters_zip');
			if (!$apfl_filters_zip) {
				update_option('apfl_filters_zip', 'show');
			}
			$apfl_filters_movein = get_option('apfl_filters_movein');
			if (!$apfl_filters_movein) {
				update_option('apfl_filters_movein', 'show');
			}
			$apfl_filters_sorting = get_option('apfl_filters_sorting');
			if (!$apfl_filters_sorting) {
				update_option('apfl_filters_sorting', 'show');
			}

			$apfl_listings_search_color = get_option('apfl_listings_search_color');
			if (!$apfl_listings_search_color) {
				update_option('apfl_listings_search_color', '#ffffff');
			}
			$apfl_listings_search_bg = get_option('apfl_listings_search_bg');
			if (!$apfl_listings_search_bg) {
				update_option('apfl_listings_search_bg', '#ff6600');
			}

			$apfl_listings_display_price = get_option('apfl_listings_display_price');
			if (!$apfl_listings_display_price) {
				update_option('apfl_listings_display_price', 'show');
			}
			$apfl_listings_price_pos = get_option('apfl_listings_price_pos');
			if (!$apfl_listings_price_pos) {
				update_option('apfl_listings_price_pos', 'onimage');
			}
			$apfl_listings_price_color = get_option('apfl_listings_price_color');
			if (!$apfl_listings_price_color) {
				update_option('apfl_listings_price_color', '#ffffff');
			}
			$apfl_listings_price_bg = get_option('apfl_listings_price_bg');
			if (!$apfl_listings_price_bg) {
				update_option('apfl_listings_price_bg', '#ff6600');
			}

			$apfl_listings_display_avail = get_option('apfl_listings_display_avail');
			if (!$apfl_listings_display_avail) {
				update_option('apfl_listings_display_avail', 'show');
			}
			$apfl_listings_avail_pos = get_option('apfl_listings_avail_pos');
			if (!$apfl_listings_avail_pos) {
				update_option('apfl_listings_avail_pos', 'onimage');
			}
			$apfl_listings_avail_color = get_option('apfl_listings_avail_color');
			if (!$apfl_listings_avail_color) {
				update_option('apfl_listings_avail_color', '#ffffff');
			}
			$apfl_listings_avail_bg = get_option('apfl_listings_avail_bg');
			if (!$apfl_listings_avail_bg) {
				update_option('apfl_listings_avail_bg', '#ff6600');
			}

			$apfl_listings_display_ttl = get_option('apfl_listings_display_ttl');
			if (!$apfl_listings_display_ttl) {
				update_option('apfl_listings_display_ttl', 'show');
			}
			$apfl_listings_ttl_tag = get_option('apfl_listings_ttl_tag');
			if (!$apfl_listings_ttl_tag) {
				update_option('apfl_listings_ttl_tag', 'h2');
			}
			$apfl_listings_display_address = get_option('apfl_listings_display_address');
			if (!$apfl_listings_display_address) {
				update_option('apfl_listings_display_address', 'show');
			}
			$apfl_listings_display_beds = get_option('apfl_listings_display_beds');
			if (!$apfl_listings_display_beds) {
				update_option('apfl_listings_display_beds', 'show');
			}
			$apfl_listings_bed_img = get_option('apfl_listings_bed_img');
			if (!$apfl_listings_bed_img) {
				update_option('apfl_listings_bed_img', plugin_dir_url(__FILE__) . 'images/sleep.png');
			}

			$apfl_listings_display_baths = get_option('apfl_listings_display_baths');
			if (!$apfl_listings_display_baths) {
				update_option('apfl_listings_display_baths', 'show');
			}
			$apfl_listings_bath_img = get_option('apfl_listings_bath_img');
			if (!$apfl_listings_bath_img) {
				update_option('apfl_listings_bath_img', plugin_dir_url(__FILE__) . 'images/bathtub.png');
			}

			$apfl_listings_display_area = get_option('apfl_listings_display_area');
			if (!$apfl_listings_display_area) {
				update_option('apfl_listings_display_area', 'show');
			}
			$apfl_listings_area_img = get_option('apfl_listings_area_img');
			if (!$apfl_listings_area_img) {
				update_option('apfl_listings_area_img', plugin_dir_url(__FILE__) . 'images/area.png');
			}

			$apfl_listings_display_pets = get_option('apfl_listings_display_pets');
			if (!$apfl_listings_display_pets) {
				update_option('apfl_listings_display_pets', 'show');
			}
			$apfl_listings_pet_img = get_option('apfl_listings_pet_img');
			if (!$apfl_listings_pet_img) {
				update_option('apfl_listings_pet_img', plugin_dir_url(__FILE__) . 'images/pet.png');
			}
			$apfl_listings_no_pet_img = get_option('apfl_listings_no_pet_img');
			if (!$apfl_listings_no_pet_img) {
				update_option('apfl_listings_no_pet_img', plugin_dir_url(__FILE__) . 'images/no-pet.png');
			}

			$apfl_listings_display_detail = get_option('apfl_listings_display_detail');
			if (!$apfl_listings_display_detail) {
				update_option('apfl_listings_display_detail', 'show');
			}
			$apfl_listings_display_apply = get_option('apfl_listings_display_apply');
			if (!$apfl_listings_display_apply) {
				update_option('apfl_listings_display_apply', 'show');
			}

			$apfl_listings_detail_color = get_option('apfl_listings_detail_color');
			if (!$apfl_listings_detail_color) {
				update_option('apfl_listings_detail_color', '#ffffff');
			}
			$apfl_listings_detail_bg = get_option('apfl_listings_detail_bg');
			if (!$apfl_listings_detail_bg) {
				update_option('apfl_listings_detail_bg', '#27547c');
			}
			$apfl_listings_detail_hover_color = get_option('apfl_listings_detail_hover_color');
			if (!$apfl_listings_detail_hover_color) {
				update_option('apfl_listings_detail_hover_color', '#ffffff');
			}
			$apfl_listings_detail_hover_bg = get_option('apfl_listings_detail_hover_bg');
			if (!$apfl_listings_detail_hover_bg) {
				update_option('apfl_listings_detail_hover_bg', '#444444');
			}

			$apfl_listings_apply_color = get_option('apfl_listings_apply_color');
			if (!$apfl_listings_apply_color) {
				update_option('apfl_listings_apply_color', '#ffffff');
			}
			$apfl_listings_apply_bg = get_option('apfl_listings_apply_bg');
			if (!$apfl_listings_apply_bg) {
				update_option('apfl_listings_apply_bg', '#333333');
			}
			$apfl_listings_apply_hover_color = get_option('apfl_listings_apply_hover_color');
			if (!$apfl_listings_apply_hover_color) {
				update_option('apfl_listings_apply_hover_color', '#ffffff');
			}
			$apfl_listings_apply_hover_bg = get_option('apfl_listings_apply_hover_bg');
			if (!$apfl_listings_apply_hover_bg) {
				update_option('apfl_listings_apply_hover_bg', '#444444');
			}

			$apfl_listings_schedule_color = get_option('apfl_listings_schedule_color');
			if (!$apfl_listings_schedule_color) {
				update_option('apfl_listings_schedule_color', '#ffffff');
			}
			$apfl_listings_schedule_bg = get_option('apfl_listings_schedule_bg');
			if (!$apfl_listings_schedule_bg) {
				update_option('apfl_listings_schedule_bg', '#9b9b96');
			}
			$apfl_listings_schedule_hover_color = get_option('apfl_listings_schedule_hover_color');
			if (!$apfl_listings_schedule_hover_color) {
				update_option('apfl_listings_schedule_hover_color', '#ffffff');
			}
			$apfl_listings_schedule_hover_bg = get_option('apfl_listings_schedule_hover_bg');
			if (!$apfl_listings_schedule_hover_bg) {
				update_option('apfl_listings_schedule_hover_bg', '#444444');
			}
			$apfl_listings_banner_heading_font_size = get_option('apfl_listings_banner_heading_font_size');
			if (!$apfl_listings_banner_heading_font_size) {
				update_option('apfl_listings_banner_heading_font_size', '30px');
			}

			$apfl_listings_banner_heading_font_weight = get_option('apfl_listings_banner_heading_font_weight');
			if (!$apfl_listings_banner_heading_font_weight) {
				update_option('apfl_listings_banner_heading_font_weight', '400');
			}

			$apfl_listings_banner_heading_color = get_option('apfl_listings_banner_heading_color');
			if (!$apfl_listings_banner_heading_color) {
				update_option('apfl_listings_banner_heading_color', '#fff');
			}

			$apfl_listings_banner_heading_line_height = get_option('apfl_listings_banner_heading_line_height');
			if (!$apfl_listings_banner_heading_line_height) {
				update_option('apfl_listings_banner_heading_line_height', '1');
			}

			$apfl_listings_banner_heading_text_transform = get_option('apfl_listings_banner_heading_text_transform');
			if (!$apfl_listings_banner_heading_text_transform) {
				update_option('apfl_listings_banner_heading_text_transform', 'uppercase');
			}

			$apfl_listings_banner_heading_text_align = get_option('apfl_listings_banner_heading_text_align');
			if (!$apfl_listings_banner_heading_text_align) {
				update_option('apfl_listings_banner_heading_text_align', 'center');
			}

			$apfl_listings_banner_heading_padding_top = get_option('apfl_listings_banner_heading_padding_top');
			if (!$apfl_listings_banner_heading_padding_top) {
				update_option('apfl_listings_banner_heading_padding_top', '0px');
			}

			$apfl_listings_banner_heading_padding_bottom = get_option('apfl_listings_banner_heading_padding_bottom');
			if (!$apfl_listings_banner_heading_padding_bottom) {
				update_option('apfl_listings_banner_heading_padding_bottom', '30px');
			}

			$apfl_listings_banner_heading_padding_left = get_option('apfl_listings_banner_heading_padding_left');
			if (!$apfl_listings_banner_heading_padding_left) {
				update_option('apfl_listings_banner_heading_padding_left', '0px');
			}

			$apfl_listings_banner_heading_padding_right = get_option('apfl_listings_banner_heading_padding_right');
			if (!$apfl_listings_banner_heading_padding_right) {
				update_option('apfl_listings_banner_heading_padding_right', '0px');
			}

			$apfl_listings_banner_bg = get_option('apfl_listings_banner_bg');
			if (!$apfl_listings_banner_bg) {
				update_option('apfl_listings_banner_bg', '#598fcd');
			}

			$apfl_listings_banner_image = get_option('apfl_listings_banner_image');
			if (!$apfl_listings_banner_image) {
				update_option('apfl_listings_banner_image', 'hide');
			}

			$apfl_listings_banner_image_url = get_option('apfl_listings_banner_image_url');
			if (!$apfl_listings_banner_image_url) {
				update_option('apfl_listings_banner_image_url', '');
			}

			$apfl_page_sub_hdng = get_option('apfl_page_sub_hdng');
			if (!$apfl_page_sub_hdng) {
				update_option('apfl_page_sub_hdng', '');
			}

			$apfl_display_large_images = get_option('apfl_display_large_images');
			if (!$apfl_display_large_images) {
				update_option('apfl_display_large_images', 'hide');
			}

		}

	}

	static function apfl_plugin_deactivate()
	{
		// to handle deactivation
	}

}