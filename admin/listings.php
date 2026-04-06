<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
  exit;
}

// Content Builder
if (!function_exists('apfl_pp_listings_builder_callback')) {
  function apfl_pp_listings_builder_callback()
  {
    global $apfl_plugin_dir;
    global $apfl_plugin_url;
    include($apfl_plugin_dir . 'inc/customizer-templates.php');
    if ($_POST) {
      if (isset($_POST['apfl_cstmzr_sbmt'])) {
        $apfl_template = (int) get_option('apfl_template');
        $temp_data_args = array();

        if (isset($_POST['apfl_columns_cnt'])) {
          $apfl_columns_cnt = sanitize_text_field($_POST['apfl_columns_cnt']);
          $temp_data_args['apfl_columns_cnt'] = $apfl_columns_cnt;
        }

        if (isset($_POST['apfl_custom_apply_lnk'])) {
          $apfl_custom_apply_lnk = sanitize_text_field($_POST['apfl_custom_apply_lnk']);
          $temp_data_args['apfl_custom_apply_lnk'] = $apfl_custom_apply_lnk;
        }

        if (isset($_POST['apfl_custom_source'])) {
          $apfl_custom_source = sanitize_text_field($_POST['apfl_custom_source']);
          $temp_data_args['apfl_custom_source'] = $apfl_custom_source;
        }

        if (isset($_POST['apfl_page_hdng'])) {
          $apfl_page_hdng = wp_kses_post($_POST['apfl_page_hdng']);
          $temp_data_args['apfl_page_hdng'] = $apfl_page_hdng;
        }


        if (isset($_POST['apfl_page_sub_hdng'])) {
          $apfl_page_sub_hdng = strip_tags($_POST['apfl_page_sub_hdng']);
          $temp_data_args['apfl_page_sub_hdng'] = $apfl_page_sub_hdng;
        }

        if (isset($_POST['apfl_listings_banner_bg'])) {
          $apfl_listings_banner_bg = sanitize_text_field($_POST['apfl_listings_banner_bg']);
          $temp_data_args['apfl_listings_banner_bg'] = $apfl_listings_banner_bg;
        }

        if (isset($_POST['apfl_listings_banner_image'])) {

          $apfl_listings_banner_image = filter_var($_POST['apfl_listings_banner_image'], FILTER_UNSAFE_RAW);
          if ($apfl_listings_banner_image == 'on') {
            $temp_data_args['apfl_listings_banner_image'] = 'show';
          } else {
            $temp_data_args['apfl_listings_banner_image'] = 'hide';
          }
        }

        if (isset($_POST['apfl_listings_banner_heading_font_size'])) {
          $apfl_listings_banner_heading_font_size = sanitize_text_field($_POST['apfl_listings_banner_heading_font_size']);
          $temp_data_args['apfl_listings_banner_heading_font_size'] = $apfl_listings_banner_heading_font_size;
        }
        if (isset($_POST['apfl_listings_banner_heading_font_weight'])) {
          $apfl_listings_banner_heading_font_weight = sanitize_text_field($_POST['apfl_listings_banner_heading_font_weight']);
          $temp_data_args['apfl_listings_banner_heading_font_weight'] = $apfl_listings_banner_heading_font_weight;
        }

        if (isset($_POST['apfl_listings_banner_heading_color'])) {
          $apfl_listings_banner_heading_color = sanitize_text_field($_POST['apfl_listings_banner_heading_color']);
          $temp_data_args['apfl_listings_banner_heading_color'] = $apfl_listings_banner_heading_color;
        }

        if (isset($_POST['apfl_listings_banner_heading_line_height'])) {
          $apfl_listings_banner_heading_line_height = sanitize_text_field($_POST['apfl_listings_banner_heading_line_height']);
          $temp_data_args['apfl_listings_banner_heading_line_height'] = $apfl_listings_banner_heading_line_height;
        }

        if (isset($_POST['apfl_listings_banner_heading_text_transform'])) {
          $apfl_listings_banner_heading_text_transform = sanitize_text_field($_POST['apfl_listings_banner_heading_text_transform']);
          $temp_data_args['apfl_listings_banner_heading_text_transform'] = $apfl_listings_banner_heading_text_transform;
        }

        if (isset($_POST['apfl_listings_banner_heading_text_align'])) {
          $apfl_listings_banner_heading_text_align = sanitize_text_field($_POST['apfl_listings_banner_heading_text_align']);
          $temp_data_args['apfl_listings_banner_heading_text_align'] = $apfl_listings_banner_heading_text_align;
        }

        if (isset($_POST['apfl_listings_banner_heading_padding_top'])) {
          $apfl_listings_banner_heading_padding_top = sanitize_text_field($_POST['apfl_listings_banner_heading_padding_top']);
          $temp_data_args['apfl_listings_banner_heading_padding_top'] = $apfl_listings_banner_heading_padding_top;
        }

        if (isset($_POST['apfl_listings_banner_heading_padding_bottom'])) {
          $apfl_listings_banner_heading_padding_bottom = sanitize_text_field($_POST['apfl_listings_banner_heading_padding_bottom']);
          $temp_data_args['apfl_listings_banner_heading_padding_bottom'] = $apfl_listings_banner_heading_padding_bottom;
        }

        if (isset($_POST['apfl_listings_banner_heading_padding_left'])) {
          $apfl_listings_banner_heading_padding_left = sanitize_text_field($_POST['apfl_listings_banner_heading_padding_left']);
          $temp_data_args['apfl_listings_banner_heading_padding_left'] = $apfl_listings_banner_heading_padding_left;
        }

        if (isset($_POST['apfl_listings_banner_heading_padding_right'])) {
          $apfl_listings_banner_heading_padding_right = sanitize_text_field($_POST['apfl_listings_banner_heading_padding_right']);
          $temp_data_args['apfl_listings_banner_heading_padding_right'] = $apfl_listings_banner_heading_padding_right;
        }

        if (isset($_POST['apfl_pro_enable_searching'])) {
          $apfl_pro_enable_searching = filter_var($_POST['apfl_pro_enable_searching'], FILTER_UNSAFE_RAW);
          if ($apfl_pro_enable_searching == 'on') {
            $temp_data_args['apfl_pro_enable_searching'] = 'show';
          }
        } else {
          $temp_data_args['apfl_pro_enable_searching'] = 'hide';
        }


        if (isset($_POST['apfl_pro_textarea_input'])) {
          $apfl_filters_textarea_input = filter_var($_POST['apfl_pro_textarea_input'], FILTER_UNSAFE_RAW);
          if ($apfl_filters_textarea_input == 'on') {
            $temp_data_args['apfl_filters_textarea_input'] = 'show';
          }
        } else {
          $temp_data_args['apfl_filters_textarea_input'] = 'hide';
        }

        if (isset($_POST['apfl_pro_cat_filter'])) {
          $apfl_filters_cat = filter_var($_POST['apfl_pro_cat_filter'], FILTER_UNSAFE_RAW);
          if ($apfl_filters_cat == 'on') {
            $temp_data_args['apfl_filters_cat'] = 'show';
          }
        } else {
          $temp_data_args['apfl_filters_cat'] = 'hide';
        }

        if (isset($_POST['apfl_pro_dog_filter'])) {
          $apfl_filters_dog = filter_var($_POST['apfl_pro_dog_filter'], FILTER_UNSAFE_RAW);
          if ($apfl_filters_dog == 'on') {
            $temp_data_args['apfl_filters_dog'] = 'show';
          }
        } else {
          $temp_data_args['apfl_filters_dog'] = 'hide';
        }

        if (isset($_POST['apfl_pro_minrent_filter'])) {
          $apfl_filters_minrent = filter_var($_POST['apfl_pro_minrent_filter'], FILTER_UNSAFE_RAW);
          if ($apfl_filters_minrent == 'on') {
            $temp_data_args['apfl_filters_minrent'] = 'show';
          }
        } else {
          $temp_data_args['apfl_filters_minrent'] = 'hide';
        }

        if (isset($_POST['apfl_pro_maxrent_filter'])) {
          $apfl_filters_maxrent = filter_var($_POST['apfl_pro_maxrent_filter'], FILTER_UNSAFE_RAW);
          if ($apfl_filters_maxrent == 'on') {
            $temp_data_args['apfl_filters_maxrent'] = 'show';
          }
        } else {
          $temp_data_args['apfl_filters_maxrent'] = 'hide';
        }

        if (isset($_POST['apfl_pro_bed_filter'])) {
          $apfl_filters_bed = filter_var($_POST['apfl_pro_bed_filter'], FILTER_UNSAFE_RAW);
          if ($apfl_filters_bed == 'on') {
            $temp_data_args['apfl_filters_bed'] = 'show';
          }
        } else {
          $temp_data_args['apfl_filters_bed'] = 'hide';
        }

        if (isset($_POST['apfl_pro_bath_filter'])) {
          $apfl_filters_bath = filter_var($_POST['apfl_pro_bath_filter'], FILTER_UNSAFE_RAW);
          if ($apfl_filters_bath == 'on') {
            $temp_data_args['apfl_filters_bath'] = 'show';
          }
        } else {
          $temp_data_args['apfl_filters_bath'] = 'hide';
        }

        if (isset($_POST['apfl_pro_cities_filter'])) {
          $apfl_filters_cities = filter_var($_POST['apfl_pro_cities_filter'], FILTER_UNSAFE_RAW);
          if ($apfl_filters_cities == 'on') {
            $temp_data_args['apfl_filters_cities'] = 'show';
          }
        } else {
          $temp_data_args['apfl_filters_cities'] = 'hide';
        }

        if (isset($_POST['apfl_pro_zip_filter'])) {
          $apfl_filters_zip = filter_var($_POST['apfl_pro_zip_filter'], FILTER_UNSAFE_RAW);
          if ($apfl_filters_zip == 'on') {
            $temp_data_args['apfl_filters_zip'] = 'show';
          }
        } else {
          $temp_data_args['apfl_filters_zip'] = 'hide';
        }

        if (isset($_POST['apfl_pro_movein_filter'])) {
          $apfl_filters_movein = filter_var($_POST['apfl_pro_movein_filter'], FILTER_UNSAFE_RAW);
          if ($apfl_filters_movein == 'on') {
            $temp_data_args['apfl_filters_movein'] = 'show';
          }
        } else {
          $temp_data_args['apfl_filters_movein'] = 'hide';
        }


        if (isset($_POST['apfl_pro_sorting_filter'])) {
          $apfl_filters_sorting = filter_var($_POST['apfl_pro_sorting_filter'], FILTER_UNSAFE_RAW);
          if ($apfl_filters_sorting == 'on') {
            $temp_data_args['apfl_filters_sorting'] = 'show';
          }
        } else {
          $temp_data_args['apfl_filters_sorting'] = 'hide';
        }

        if (isset($_POST['def_sort'])) {
          $def_sort = sanitize_text_field($_POST['def_sort']);
          $temp_data_args['apfl_listings_def_sort'] = $def_sort;
        }

        if (isset($_POST['desired_movein'])) {
          $desired_movein = sanitize_text_field($_POST['desired_movein']);
          $temp_data_args['apfl_listings_movein'] = $desired_movein;
        }

        if (isset($_POST['apfl_listings_search_color'])) {
          $apfl_listings_search_color = sanitize_text_field($_POST['apfl_listings_search_color']);
          $temp_data_args['apfl_listings_search_color'] = $apfl_listings_search_color;
        }
        if (isset($_POST['apfl_listings_search_bg'])) {
          $apfl_listings_search_bg = sanitize_text_field($_POST['apfl_listings_search_bg']);
          $temp_data_args['apfl_listings_search_bg'] = $apfl_listings_search_bg;
        }

        // Change rent text
        if (isset($_POST['apfl_rent_text'])) {
          $new_rent_text = sanitize_text_field($_POST['apfl_rent_text']);

          // Check if the new_rent_text is empty
          if (empty($new_rent_text)) {
            $new_rent_text = 'RENT';
          }

          update_option('apfl_rent_text', $new_rent_text);
        }

        // Pagination options
        if (isset($_POST['apfl_listings_pagination'])) {
          $apfl_listings_pagination = filter_var($_POST['apfl_listings_pagination'], FILTER_UNSAFE_RAW);
          if ($apfl_listings_pagination == 'on') {
            $temp_data_args['apfl_listings_pagination'] = 'show';
          }
        } else {
          $temp_data_args['apfl_listings_pagination'] = 'hide';
        }
        if (isset($_POST['apfl_listings_per_page'])) {
          $apfl_listings_per_page = sanitize_text_field($_POST['apfl_listings_per_page']);
          $temp_data_args['apfl_listings_per_page'] = $apfl_listings_per_page;
        }

        // Rent price options
        if (isset($_POST['apfl_listings_display_price'])) {
          $apfl_listings_display_price = filter_var($_POST['apfl_listings_display_price'], FILTER_UNSAFE_RAW);
          if ($apfl_listings_display_price == 'on') {
            $temp_data_args['apfl_listings_display_price'] = 'show';
          }
        } else {
          $temp_data_args['apfl_listings_display_price'] = 'hide';
        }


        if ($apfl_template != 1) {
          if (isset($_POST['apfl_listings_price_pos'])) {
            $apfl_listings_price_pos = sanitize_text_field($_POST['apfl_listings_price_pos']);
            $temp_data_args['apfl_listings_price_pos'] = $apfl_listings_price_pos;
          }
        } else {
          $temp_data_args['apfl_listings_price_pos'] = 'offimage';
        }

        if (isset($_POST['apfl_listings_price_color'])) {
          $apfl_listings_price_color = sanitize_text_field($_POST['apfl_listings_price_color']);
          $temp_data_args['apfl_listings_price_color'] = $apfl_listings_price_color;
        }
        if (isset($_POST['apfl_listings_price_bg'])) {
          $apfl_listings_price_bg = sanitize_text_field($_POST['apfl_listings_price_bg']);
          $temp_data_args['apfl_listings_price_bg'] = $apfl_listings_price_bg;
        }

        if (isset($_POST['apfl_listings_display_avail'])) {
          $apfl_listings_display_avail = filter_var($_POST['apfl_listings_display_avail'], FILTER_UNSAFE_RAW);
          if ($apfl_listings_display_avail == 'on') {
            $temp_data_args['apfl_listings_display_avail'] = 'show';
          }
        } else {
          $temp_data_args['apfl_listings_display_avail'] = 'hide';
        }

        if ($apfl_template != 1) {
          if (isset($_POST['apfl_listings_avail_pos'])) {
            $apfl_listings_avail_pos = sanitize_text_field($_POST['apfl_listings_avail_pos']);
            $temp_data_args['apfl_listings_avail_pos'] = $apfl_listings_avail_pos;
          }
        } else {
          $temp_data_args['apfl_listings_avail_pos'] = 'offimage';
        }

        if (isset($_POST['apfl_listings_avail_color'])) {
          $apfl_listings_avail_color = sanitize_text_field($_POST['apfl_listings_avail_color']);
          $temp_data_args['apfl_listings_avail_color'] = $apfl_listings_avail_color;
        }
        if (isset($_POST['apfl_listings_avail_bg'])) {
          $apfl_listings_avail_bg = sanitize_text_field($_POST['apfl_listings_avail_bg']);
          $temp_data_args['apfl_listings_avail_bg'] = $apfl_listings_avail_bg;
        }

        if (isset($_POST['apfl_listings_display_ttl'])) {
          $apfl_listings_display_ttl = filter_var($_POST['apfl_listings_display_ttl'], FILTER_UNSAFE_RAW);
          if ($apfl_listings_display_ttl == 'on') {
            $temp_data_args['apfl_listings_display_ttl'] = 'show';
          }
        } else {
          $temp_data_args['apfl_listings_display_ttl'] = 'hide';
        }

        if (isset($_POST['apfl_listings_ttl_tag'])) {
          $apfl_listings_ttl_tag = sanitize_text_field($_POST['apfl_listings_ttl_tag']);
          $temp_data_args['apfl_listings_ttl_tag'] = $apfl_listings_ttl_tag;
        }

        if (isset($_POST['apfl_listings_display_address'])) {
          $apfl_listings_display_address = filter_var($_POST['apfl_listings_display_address'], FILTER_UNSAFE_RAW);
          if ($apfl_listings_display_address == 'on') {
            $temp_data_args['apfl_listings_display_address'] = 'show';
          }
        } else {
          $temp_data_args['apfl_listings_display_address'] = 'hide';
        }

        if (isset($_POST['apfl_listings_display_beds'])) {
          $apfl_listings_display_beds = filter_var($_POST['apfl_listings_display_beds'], FILTER_UNSAFE_RAW);
          if ($apfl_listings_display_beds == 'on') {
            $temp_data_args['apfl_listings_display_beds'] = 'show';
          }
        } else {
          $temp_data_args['apfl_listings_display_beds'] = 'hide';
        }

        if (isset($_POST['apfl_listings_bed_img'])) {
          $apfl_listings_bed_img = sanitize_text_field($_POST['apfl_listings_bed_img']);
          $temp_data_args['apfl_listings_bed_img'] = $apfl_listings_bed_img;
        }

        if (isset($_POST['apfl_listings_display_baths'])) {
          $apfl_listings_display_baths = filter_var($_POST['apfl_listings_display_baths'], FILTER_UNSAFE_RAW);
          if ($apfl_listings_display_baths == 'on') {
            $temp_data_args['apfl_listings_display_baths'] = 'show';
          }
        } else {
          $temp_data_args['apfl_listings_display_baths'] = 'hide';
        }

        if (isset($_POST['apfl_listings_bath_img'])) {
          $apfl_listings_bath_img = sanitize_text_field($_POST['apfl_listings_bath_img']);
          $temp_data_args['apfl_listings_bath_img'] = $apfl_listings_bath_img;
        }

        if (isset($_POST['apfl_listings_display_area'])) {
          $apfl_listings_display_area = filter_var($_POST['apfl_listings_display_area'], FILTER_UNSAFE_RAW);
          if ($apfl_listings_display_area == 'on') {
            $temp_data_args['apfl_listings_display_area'] = 'show';
          }
        } else {
          $temp_data_args['apfl_listings_display_area'] = 'hide';
        }

        if (isset($_POST['apfl_listings_area_img'])) {
          $apfl_listings_area_img = sanitize_text_field($_POST['apfl_listings_area_img']);
          $temp_data_args['apfl_listings_area_img'] = $apfl_listings_area_img;
        }

        if (isset($_POST['apfl_listings_display_pets'])) {
          $apfl_listings_display_pets = filter_var($_POST['apfl_listings_display_pets'], FILTER_UNSAFE_RAW);
          if ($apfl_listings_display_pets == 'on') {
            $temp_data_args['apfl_listings_display_pets'] = 'show';
          }
        } else {
          $temp_data_args['apfl_listings_display_pets'] = 'hide';
        }

        if (isset($_POST['apfl_listings_pet_img'])) {
          $apfl_listings_pet_img = sanitize_text_field($_POST['apfl_listings_pet_img']);
          $temp_data_args['apfl_listings_pet_img'] = $apfl_listings_pet_img;
        }

        if (isset($_POST['apfl_listings_no_pet_img'])) {
          $apfl_listings_no_pet_img = sanitize_text_field($_POST['apfl_listings_no_pet_img']);
          $temp_data_args['apfl_listings_no_pet_img'] = $apfl_listings_no_pet_img;
        }

        // Details button 

        if (isset($_POST['apfl_listings_display_detail'])) {
          $apfl_listings_display_detail = filter_var($_POST['apfl_listings_display_detail'], FILTER_UNSAFE_RAW);
          if ($apfl_listings_display_detail == 'on') {
            $temp_data_args['apfl_listings_display_detail'] = 'show';
          }
        } else {
          $temp_data_args['apfl_listings_display_detail'] = 'hide';
        }
        if (isset($_POST['apfl_listings_detail_color'])) {
          $apfl_listings_detail_color = sanitize_text_field($_POST['apfl_listings_detail_color']);
          $temp_data_args['apfl_listings_detail_color'] = $apfl_listings_detail_color;
        }
        if (isset($_POST['apfl_listings_detail_bg'])) {
          $apfl_listings_detail_bg = sanitize_text_field($_POST['apfl_listings_detail_bg']);
          $temp_data_args['apfl_listings_detail_bg'] = $apfl_listings_detail_bg;
        }
        if (isset($_POST['apfl_listings_detail_hover_color'])) {
          $apfl_listings_detail_hover_color = sanitize_text_field($_POST['apfl_listings_detail_hover_color']);
          $temp_data_args['apfl_listings_detail_hover_color'] = $apfl_listings_detail_hover_color;
        }
        if (isset($_POST['apfl_listings_detail_hover_bg'])) {
          $apfl_listings_detail_hover_bg = sanitize_text_field($_POST['apfl_listings_detail_hover_bg']);
          $temp_data_args['apfl_listings_detail_hover_bg'] = $apfl_listings_detail_hover_bg;
        }

        // Apply button

        if (isset($_POST['apfl_listings_display_apply'])) {
          $apfl_listings_display_apply = filter_var($_POST['apfl_listings_display_apply'], FILTER_UNSAFE_RAW);
          if ($apfl_listings_display_apply == 'on') {
            $temp_data_args['apfl_listings_display_apply'] = 'show';
          }
        } else {
          $temp_data_args['apfl_listings_display_apply'] = 'hide';
        }

        if (isset($_POST['apfl_listings_apply_color'])) {
          $apfl_listings_apply_color = sanitize_text_field($_POST['apfl_listings_apply_color']);
          $temp_data_args['apfl_listings_apply_color'] = $apfl_listings_apply_color;
        }
        if (isset($_POST['apfl_listings_apply_bg'])) {
          $apfl_listings_apply_bg = sanitize_text_field($_POST['apfl_listings_apply_bg']);
          $temp_data_args['apfl_listings_apply_bg'] = $apfl_listings_apply_bg;
        }
        if (isset($_POST['apfl_listings_apply_hover_color'])) {
          $apfl_listings_apply_hover_color = sanitize_text_field($_POST['apfl_listings_apply_hover_color']);
          $temp_data_args['apfl_listings_apply_hover_color'] = $apfl_listings_apply_hover_color;
        }
        if (isset($_POST['apfl_listings_apply_hover_bg'])) {
          $apfl_listings_apply_hover_bg = sanitize_text_field($_POST['apfl_listings_apply_hover_bg']);
          $temp_data_args['apfl_listings_apply_hover_bg'] = $apfl_listings_detail_hover_bg;
        }

        // Schedule button 

        if (isset($_POST['apfl_listings_display_schedule'])) {
          $apfl_listings_display_schedule = filter_var($_POST['apfl_listings_display_schedule'], FILTER_UNSAFE_RAW);
          if ($apfl_listings_display_schedule == 'on') {
            $temp_data_args['apfl_listings_display_schedule'] = 'show';
          }
        } else {
          $temp_data_args['apfl_listings_display_schedule'] = 'hide';
        }

        if (isset($_POST['apfl_display_large_images'])) {
          $apfl_display_large_images = filter_var($_POST['apfl_display_large_images'], FILTER_UNSAFE_RAW);
          if ($apfl_display_large_images == 'on') {
            $temp_data_args['apfl_display_large_images'] = 'show';
          }
        } else {
          $temp_data_args['apfl_display_large_images'] = 'hide';
        }

        if (isset($_POST['apfl_display_city_state'])) {
          $apfl_display_city_state = filter_var($_POST['apfl_display_city_state'], FILTER_UNSAFE_RAW);
          if ($apfl_display_city_state == 'on') {
            $temp_data_args['apfl_display_city_state'] = 'show';
          }
        } else {
          $temp_data_args['apfl_display_city_state'] = 'hide';
        }

        if (isset($_POST['apfl_listings_schedule_color'])) {
          $apfl_listings_schedule_color = sanitize_text_field($_POST['apfl_listings_schedule_color']);
          $temp_data_args['apfl_listings_schedule_color'] = $apfl_listings_schedule_color;
        }
        if (isset($_POST['apfl_listings_schedule_bg'])) {
          $apfl_listings_schedule_bg = sanitize_text_field($_POST['apfl_listings_schedule_bg']);
          $temp_data_args['apfl_listings_schedule_bg'] = $apfl_listings_schedule_bg;
        }
        if (isset($_POST['apfl_listings_schedule_hover_color'])) {
          $apfl_listings_schedule_hover_color = sanitize_text_field($_POST['apfl_listings_schedule_hover_color']);
          $temp_data_args['apfl_listings_schedule_hover_color'] = $apfl_listings_schedule_hover_color;
        }
        if (isset($_POST['apfl_listings_schedule_hover_bg'])) {
          $apfl_listings_schedule_hover_bg = sanitize_text_field($_POST['apfl_listings_schedule_hover_bg']);
          $temp_data_args['apfl_listings_schedule_hover_bg'] = $apfl_listings_detail_hover_bg;
        }

        // Change default zoom for google map
        if (isset($_POST['apfl_def_map_zoom'])) {
          $apfl_def_map_zoom = sanitize_text_field($_POST['apfl_def_map_zoom']);

          update_option('apfl_def_map_zoom', $apfl_def_map_zoom);
        }

        if (isset($_POST['apfl_custom_css'])) {
          $apfl_custom_css = sanitize_textarea_field($_POST['apfl_custom_css']);
          $temp_data_args['apfl_custom_css'] = $apfl_custom_css;
        }

        if (!$apfl_template) {
          // for custom template, direct update option

          foreach ($temp_data_args as $key => $val) {
            update_option($key, $val);
          }
        } else {
          update_option('apfl_template_' . $apfl_template . '_data', $temp_data_args);
        }

        // Saved message
        echo '
            <div class="apfl-notice apfl-success">
                <span class="apfl-notice-text">Settings saved successfully!</span>
                <button type="button" class="apfl-notice-close" aria-label="Dismiss notice">×</button>
            </div>';
      }
    }
?>

    <div class="wrap">
      <div id="apfl-pro-customizer">
        <form id="apfl_tmplt_frm" method="POST" action="">

          <!-- Template Selection -->
          <div class="apfl_setting-container">
              <h2 class="apfl_heading">Appfolio Listings Customizer
                  <div class="apfl-sc-hint">Shortcode: 
                      <span id="apfl_copy_carousel_sc">[apfl_listings]</span>
                  </div>
                  <div id="apfl_copy">
                      <div id="apfl_sc_copy_icon">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM16 7H11C10.4239 7 10 7.42386 10 8V10H8C7.42386 10 7 10.4239 7 11V16C7 16.5761 7.42386 17 8 17H13C13.5761 17 14 16.5761 14 16V14H16C16.5761 14 17 13.5761 17 13V8C17 7.42386 16.5761 7 16 7ZM8 11H10V13C10 13.5761 10.4239 14 11 14H13V16H8V11ZM11 13V8H16V13H11Z"></path> </g></svg>
                      </div>
                      <div id="apfl_sc_copied_icon">
                          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM16.0303 8.96967C16.3232 9.26256 16.3232 9.73744 16.0303 10.0303L11.0303 15.0303C10.7374 15.3232 10.2626 15.3232 9.96967 15.0303L7.96967 13.0303C7.67678 12.7374 7.67678 12.2626 7.96967 11.9697C8.26256 11.6768 8.73744 11.6768 9.03033 11.9697L10.5 13.4393L12.7348 11.2045L14.9697 8.96967C15.2626 8.67678 15.7374 8.67678 16.0303 8.96967Z"></path> </g></svg>
                      </div>
                  </div>
              </h2>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <?php
                $apfl_template = (int) get_option('apfl_template');
                if ($apfl_template) {
                  $template_data = get_option('apfl_template_' . $apfl_template . '_data');
                }
                ?>
                <label for="apfl_template">Template</label>
              </div>
              <div class="apfl_col-2">
                <label>
                  <input type="radio" name="apfl_template" value="1" <?php echo ($apfl_template == 1) ? 'checked' : ''; ?>>
                  Hawk Template
                </label>
                <label>
                  <input type="radio" name="apfl_template" value="2" class="apfl_template_radio" <?php echo ($apfl_template == 2) ? 'checked' : ''; ?>>
                  Eagle Template
                </label>
                <label>
                  <input type="radio" name="apfl_template" value="9" class="apfl_template_radio" <?php echo (!$apfl_template) ? 'checked' : ''; ?>>
                  Custom Template
                </label>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <?php
                $apfl_page_hdng = '';
                if ($apfl_template) {
                  if (array_key_exists("apfl_page_hdng", $template_data)) {
                    $apfl_page_hdng = $template_data['apfl_page_hdng'];
                  }
                } else {
                  $apfl_page_hdng = get_option('apfl_page_hdng');
                }
                ?>
                <label for="apfl_page_hdng">Listings Page Heading<br>(You can use HTML)</label>
              </div>
              <div class="apfl_col-2">
                <input type="text" name="apfl_page_hdng" id="apfl_page_hdng" class="apfl_input"
                  placeholder="e.g. &lt;h2&gt;Find a Property for Rent&lt;/h2&gt;"
                  value="<?php echo esc_attr($apfl_page_hdng); ?>">
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_banner_bg">Heading Banner Background</label>
              </div>
              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  if (array_key_exists("apfl_listings_banner_bg", $template_data)) {
                    $apfl_listings_banner_bg = $template_data['apfl_listings_banner_bg'];
                  } else {
                    $apfl_listings_banner_bg = $apfl_template == 1 ? '#232532' : '#598fcd';
                  }
                } else {
                  $apfl_listings_banner_bg = get_option('apfl_listings_banner_bg');
                }
                ?>
                <input type="text" name="apfl_listings_banner_bg"
                  value="<?php echo esc_attr($apfl_listings_banner_bg); ?>"
                  class="apfl_input apfl-listings-color"
                  placeholder="#232532">
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_banner_image">
                  Heading Banner Image<br>
                  (1300x250px)
                </label>
              </div>

              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_listings_banner_image = $template_data['apfl_listings_banner_image'] ?? 'hide';
                } else {
                  $apfl_listings_banner_image = get_option('apfl_listings_banner_image');
                }
                $show_image_section = ($apfl_listings_banner_image === 'show');
                $apfl_listings_banner_image_url = get_option('apfl_listings_banner_image_url_' . $apfl_template);
                $has_image = !empty($apfl_listings_banner_image_url);
                ?>

                <div class="checkbox-wrap">
                  <label class="apfl_switch">
                    <input type="checkbox" name="apfl_listings_banner_image" id="apfl_listings_banner_image"
                      <?php echo $show_image_section ? 'checked' : ''; ?>>
                    <span class="apfl_slide_checkbox"></span>
                  </label>
                </div>

                <div id="banner_image" class="apfl_conditional-options <?php echo $show_image_section ? '' : 'apfl_hidden'; ?>">
                  <div class="apfl_banner-preview-wrap">
                    <img src="<?php echo esc_url($apfl_listings_banner_image_url); ?>" alt="Banner Image Preview"
                      id="apfl-banner-image-preview"
                      style="display: <?php echo $has_image ? 'block' : 'none'; ?>;" />
                  </div>

                  <div class="apfl_banner-actions">
                    <input type="file" name="apfl_listings_banner_image_upload" id="apfl_listings_banner_image_upload"
                      accept="image/*">

                    <a id="apfl-upload-banner-image" class="apfl_btn"
                      apfl-template="<?php echo esc_attr($apfl_template); ?>">Upload</a>

                    <a id="apfl-remove-banner-image"
                      class="apfl_btn danger"
                      apfl-template="<?php echo esc_attr($apfl_template); ?>"
                      file-src="<?php echo esc_attr($apfl_listings_banner_image_url); ?>"
                      style="display: <?php echo $has_image ? 'inline-block' : 'none'; ?>;">Remove</a>
                  </div>

                  <div id="apfl-upload-msg"></div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <?php
                $apfl_page_sub_hdng = '';
                if ($apfl_template) {
                  if (array_key_exists("apfl_page_sub_hdng", $template_data)) {
                    $apfl_page_sub_hdng = $template_data['apfl_page_sub_hdng'];
                  }
                } else {
                  $apfl_page_sub_hdng = get_option('apfl_page_sub_hdng');
                }
                ?>
                <label for="apfl_page_sub_hdng">Listings Page Sub Heading<br />(Do not use html)</label>
              </div>
              <div class="apfl_col-2">
                <input type="text" name="apfl_page_sub_hdng" id="apfl_page_sub_hdng"
                  class="apfl_input"
                  placeholder="e.g. Find a Property for Rent"
                  value="<?php echo esc_attr(strip_tags($apfl_page_sub_hdng)); ?>">
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label>Sub Heading</label>
              </div>
              <div class="apfl_col-2">
                <div class="apfl_input_group">
                  <?php
                  if ($apfl_template) {
                    if (array_key_exists("apfl_listings_banner_heading_font_size", $template_data)) {
                      $apfl_listings_banner_heading_font_size = $template_data['apfl_listings_banner_heading_font_size'];
                    } else {
                      $apfl_listings_banner_heading_font_size = '50px';
                    }
                  } else {
                    $apfl_listings_banner_heading_font_size = get_option('apfl_listings_banner_heading_font_size');
                  }
                  ?>
                  <label>Font Size:</label>
                  <input type="text" name="apfl_listings_banner_heading_font_size"
                    value="<?php echo esc_attr($apfl_listings_banner_heading_font_size); ?>" />
                </div>

                <div class="apfl_input_group">
                  <?php
                  if ($apfl_template) {
                    if (array_key_exists("apfl_listings_banner_heading_font_weight", $template_data)) {
                      $apfl_listings_banner_heading_font_weight = $template_data['apfl_listings_banner_heading_font_weight'];
                    } else {
                      $apfl_listings_banner_heading_font_weight = '400';
                    }
                  } else {
                    $apfl_listings_banner_heading_font_weight = get_option('apfl_listings_banner_heading_font_weight');
                  }
                  ?>
                  <label>Font Weight:</label>
                  <select name="apfl_listings_banner_heading_font_weight">
                    <?php
                    $font_weight_options = array(
                      '100',
                      '200',
                      '300',
                      '400',
                      '500',
                      '600',
                      '700',
                      '800',
                      '900',
                      'bold',
                      'bolder',
                      'lighter',
                      'normal'
                    );
                    foreach ($font_weight_options as $value) {
                      $selected = ($apfl_listings_banner_heading_font_weight == $value) ? 'selected' : '';
                      echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($value) . '</option>';
                    }
                    ?>
                  </select>
                </div>

                <div class="apfl_input_group">
                  <?php
                  if ($apfl_template) {
                    if (array_key_exists("apfl_listings_banner_heading_color", $template_data)) {
                      $apfl_listings_banner_heading_color = $template_data['apfl_listings_banner_heading_color'];
                    } else {
                      $apfl_listings_banner_heading_color = '#000000';
                    }
                  } else {
                    $apfl_listings_banner_heading_color = get_option('apfl_listings_banner_heading_color');
                  }
                  ?>
                  <label>Font Color:</label>
                  <input type="text" name="apfl_listings_banner_heading_color"
                    value="<?php echo esc_attr($apfl_listings_banner_heading_color); ?>"
                    class="apfl-listings-color" />
                </div>

                <div class="apfl_input_group">
                  <?php
                  if ($apfl_template) {
                    if (array_key_exists("apfl_listings_banner_heading_line_height", $template_data)) {
                      $apfl_listings_banner_heading_line_height = $template_data['apfl_listings_banner_heading_line_height'];
                    } else {
                      $apfl_listings_banner_heading_line_height = '1';
                    }
                  } else {
                    $apfl_listings_banner_heading_line_height = get_option('apfl_listings_banner_heading_line_height');
                  }
                  ?>
                  <label>Line Height:</label>
                  <input type="text" name="apfl_listings_banner_heading_line_height"
                    value="<?php echo esc_attr($apfl_listings_banner_heading_line_height); ?>" />
                </div>

                <div class="apfl_input_group">
                  <?php
                  if ($apfl_template) {
                    if (array_key_exists("apfl_listings_banner_heading_text_transform", $template_data)) {
                      $apfl_listings_banner_heading_text_transform = $template_data['apfl_listings_banner_heading_text_transform'];
                    } else {
                      $apfl_listings_banner_heading_text_transform = 'uppercase';
                    }
                  } else {
                    $apfl_listings_banner_heading_text_transform = get_option('apfl_listings_banner_heading_text_transform');
                  }
                  ?>
                  <label>Text Transform:</label>
                  <select name="apfl_listings_banner_heading_text_transform">
                    <?php
                    $text_transform_options = array(
                      'capitalize',
                      'lowercase',
                      'uppercase',
                      'none',
                      'math-auto'
                    );
                    foreach ($text_transform_options as $value) {
                      $selected = ($apfl_listings_banner_heading_text_transform == $value) ? 'selected' : '';
                      echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($value) . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <!-- <label>Sub Heading (Layout)</label> -->
              </div>
              <div class="apfl_col-2">
                <div class="apfl_input_group">
                  <?php
                  if ($apfl_template) {
                    if (array_key_exists("apfl_listings_banner_heading_text_align", $template_data)) {
                      $apfl_listings_banner_heading_text_align = $template_data['apfl_listings_banner_heading_text_align'];
                    } else {
                      $apfl_listings_banner_heading_text_align = 'center';
                    }
                  } else {
                    $apfl_listings_banner_heading_text_align = get_option('apfl_listings_banner_heading_text_align');
                  }
                  ?>
                  <label>Text Align:</label>
                  <input type="text" name="apfl_listings_banner_heading_text_align"
                    value="<?php echo esc_attr($apfl_listings_banner_heading_text_align); ?>" />
                </div>

                <div class="apfl_input_group">
                  <?php
                  if ($apfl_template) {
                    if (array_key_exists("apfl_listings_banner_heading_padding_top", $template_data)) {
                      $apfl_listings_banner_heading_padding_top = $template_data['apfl_listings_banner_heading_padding_top'];
                    } else {
                      $apfl_listings_banner_heading_padding_top = '0px';
                    }
                  } else {
                    $apfl_listings_banner_heading_padding_top = get_option('apfl_listings_banner_heading_padding_top');
                  }
                  ?>
                  <label>Padding Top:</label>
                  <input type="text" name="apfl_listings_banner_heading_padding_top"
                    value="<?php echo esc_attr($apfl_listings_banner_heading_padding_top); ?>" />
                </div>

                <div class="apfl_input_group">
                  <?php
                  if ($apfl_template) {
                    if (array_key_exists("apfl_listings_banner_heading_padding_bottom", $template_data)) {
                      $apfl_listings_banner_heading_padding_bottom = $template_data['apfl_listings_banner_heading_padding_bottom'];
                    } else {
                      $apfl_listings_banner_heading_padding_bottom = '0px';
                    }
                  } else {
                    $apfl_listings_banner_heading_padding_bottom = get_option('apfl_listings_banner_heading_padding_bottom');
                  }
                  ?>
                  <label>Padding Bottom:</label>
                  <input type="text" name="apfl_listings_banner_heading_padding_bottom"
                    value="<?php echo esc_attr($apfl_listings_banner_heading_padding_bottom); ?>" />
                </div>

                <div class="apfl_input_group">
                  <?php
                  if ($apfl_template) {
                    if (array_key_exists("apfl_listings_banner_heading_padding_left", $template_data)) {
                      $apfl_listings_banner_heading_padding_left = $template_data['apfl_listings_banner_heading_padding_left'];
                    } else {
                      $apfl_listings_banner_heading_padding_left = '0px';
                    }
                  } else {
                    $apfl_listings_banner_heading_padding_left = get_option('apfl_listings_banner_heading_padding_left');
                  }
                  ?>
                  <label>Padding Left:</label>
                  <input type="text" name="apfl_listings_banner_heading_padding_left"
                    value="<?php echo esc_attr($apfl_listings_banner_heading_padding_left); ?>" />
                </div>

                <div class="apfl_input_group">
                  <?php
                  if ($apfl_template) {
                    if (array_key_exists("apfl_listings_banner_heading_padding_right", $template_data)) {
                      $apfl_listings_banner_heading_padding_right = $template_data['apfl_listings_banner_heading_padding_right'];
                    } else {
                      $apfl_listings_banner_heading_padding_right = '0px';
                    }
                  } else {
                    $apfl_listings_banner_heading_padding_right = get_option('apfl_listings_banner_heading_padding_right');
                  }
                  ?>
                  <label>Padding Right:</label>
                  <input type="text" name="apfl_listings_banner_heading_padding_right"
                    value="<?php echo esc_attr($apfl_listings_banner_heading_padding_right); ?>" />
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_pro_enable_searching">Display filters</label>
              </div>
              <div class="apfl_col-2">
                <?php
                $apfl_pro_enable_searching = 'show';
                if ($apfl_template) {
                  if (isset($template_data['apfl_pro_enable_searching'])) {
                    $apfl_pro_enable_searching = $template_data['apfl_pro_enable_searching'];
                  }
                } else {
                  $apfl_pro_enable_searching = get_option('apfl_pro_enable_searching');
                }
                ?>

                <!-- Toggle switch -->
                <div class="checkbox-wrap apfl_custom">
                  <label class="apfl_switch">
                    <input type="checkbox" name="apfl_pro_enable_searching" id="apfl_pro_enable_searching" value="show"
                      <?php echo ($apfl_pro_enable_searching == 'show') ? 'checked' : ''; ?>>
                    <span class="apfl_slide_checkbox"></span>
                  </label>
                </div>
              </div>
            </div>

            <div class="apfl_admin-fltrs-options">
              <div class="apfl_inner-container">
                <div class="apfl_col-1">
                  <!-- Placeholder for label if needed -->
                </div>
                <div class="apfl_col-2 apfl-fltrs-wrap">
                  <?php
                  $apfl_filters_movein = $apfl_filters_textarea_input = 'hide';
                  if ($apfl_template) {
                    $apfl_filters_cat = $template_data['apfl_filters_cat'];
                    $apfl_filters_dog = $template_data['apfl_filters_dog'];
                    $apfl_filters_minrent = $template_data['apfl_filters_minrent'];
                    $apfl_filters_maxrent = $template_data['apfl_filters_maxrent'];
                    $apfl_filters_bed = $template_data['apfl_filters_bed'];
                    $apfl_filters_bath = $template_data['apfl_filters_bath'];
                    $apfl_filters_cities = $template_data['apfl_filters_cities'];
                    $apfl_filters_zip = $template_data['apfl_filters_zip'];
                    if (isset($template_data['apfl_filters_textarea_input'])) {
                      $apfl_filters_textarea_input = $template_data['apfl_filters_textarea_input'];
                    }
                    if (isset($template_data['apfl_filters_movein'])) {
                      $apfl_filters_movein = $template_data['apfl_filters_movein'];
                    }
                    $apfl_filters_sorting = $template_data['apfl_filters_sorting'];
                  } else {
                    $apfl_filters_textarea_input = get_option('apfl_filters_textarea_input');
                    $apfl_filters_cat = get_option('apfl_filters_cat');
                    $apfl_filters_dog = get_option('apfl_filters_dog');
                    $apfl_filters_minrent = get_option('apfl_filters_minrent');
                    $apfl_filters_maxrent = get_option('apfl_filters_maxrent');
                    $apfl_filters_bed = get_option('apfl_filters_bed');
                    $apfl_filters_bath = get_option('apfl_filters_bath');
                    $apfl_filters_cities = get_option('apfl_filters_cities');
                    $apfl_filters_movein = get_option('apfl_filters_movein');
                    $apfl_filters_sorting = get_option('apfl_filters_sorting');
                    $apfl_filters_zip = get_option('apfl_filters_zip') ?: 'show';
                  }
                  ?>
                  <label><input type="checkbox" name="apfl_pro_textarea_input" id="apfl_pro_textarea_input" <?php echo ($apfl_filters_textarea_input == 'show') ? 'checked' : ''; ?>> Search</label>
                  <label><input type="checkbox" name="apfl_pro_cat_filter" id="apfl_pro_cat_filter" <?php echo ($apfl_filters_cat == 'show') ? 'checked' : ''; ?>> Cats</label>
                  <label><input type="checkbox" name="apfl_pro_dog_filter" id="apfl_pro_dog_filter" <?php echo ($apfl_filters_dog == 'show') ? 'checked' : ''; ?>> Dogs</label>
                  <label><input type="checkbox" name="apfl_pro_minrent_filter" id="apfl_pro_minrent_filter" <?php echo ($apfl_filters_minrent == 'show') ? 'checked' : ''; ?>> Min Rent</label>
                  <label><input type="checkbox" name="apfl_pro_maxrent_filter" id="apfl_pro_maxrent_filter" <?php echo ($apfl_filters_maxrent == 'show') ? 'checked' : ''; ?>> Max Rent</label>
                  <label><input type="checkbox" name="apfl_pro_bed_filter" id="apfl_pro_bed_filter" <?php echo ($apfl_filters_bed == 'show') ? 'checked' : ''; ?>> Beds</label>
                  <label><input type="checkbox" name="apfl_pro_bath_filter" id="apfl_pro_bath_filter" <?php echo ($apfl_filters_bath == 'show') ? 'checked' : ''; ?>> Baths</label>
                  <label><input type="checkbox" name="apfl_pro_cities_filter" id="apfl_pro_cities_filter" <?php echo ($apfl_filters_cities == 'show') ? 'checked' : ''; ?>> Cities</label>
                  <label><input type="checkbox" name="apfl_pro_zip_filter" id="apfl_pro_zip_filter" <?php echo ($apfl_filters_zip == 'show') ? 'checked' : ''; ?>> Zip</label>
                  <label><input type="checkbox" name="apfl_pro_movein_filter" id="apfl_pro_movein_filter" <?php echo ($apfl_filters_movein == 'show') ? 'checked' : ''; ?>> Desired Move In</label>
                  <label><input type="checkbox" name="apfl_pro_sorting_filter" id="apfl_pro_sorting_filter" <?php echo ($apfl_filters_sorting == 'show') ? 'checked' : ''; ?>> Sorting</label>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_display_city_state">Display City with State</label>
              </div>
              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_display_city_state = isset($template_data['apfl_display_city_state']) ? $template_data['apfl_display_city_state'] : 'hide';
                } else {
                  $apfl_display_city_state = get_option('apfl_display_city_state', 'hide');
                }
                ?>
                <div class="checkbox-wrap">
                  <label class="apfl_switch">
                    <input type="checkbox" name="apfl_display_city_state"
                      id="apfl_display_city_state" <?php echo ($apfl_display_city_state == 'show') ? 'checked' : ''; ?>>
                    <span class="apfl_slide_checkbox"></span>
                  </label>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="def_sort">Default Sort Order</label>
              </div>
              <div class="apfl_col-2">
                <?php
                $def_sort = '';
                if ($apfl_template) {
                  if (array_key_exists("apfl_listings_def_sort", $template_data)) {
                    $def_sort = $template_data['apfl_listings_def_sort'];
                  }
                } else {
                  $def_sort = get_option('apfl_listings_def_sort');
                }
                ?>
                <div class="apfl_option-row">
                  <div class="apfl_option-field">
                    <select id="def_sort" name="def_sort">
                      <option value="date_posted" <?php echo ($def_sort == 'date_posted') ? 'selected' : ''; ?>>Most Recent</option>
                      <option value="rent_asc" <?php echo ($def_sort == 'rent_asc') ? 'selected' : ''; ?>>Rent (Low to High)</option>
                      <option value="rent_desc" <?php echo ($def_sort == 'rent_desc') ? 'selected' : ''; ?>>Rent (High to Low)</option>
                      <option value="bedrooms" <?php echo ($def_sort == 'bedrooms') ? 'selected' : ''; ?>>Bedrooms</option>
                      <option value="availability" <?php echo ($def_sort == 'availability') ? 'selected' : ''; ?>>Availability</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_search_color">Search Button</label>
              </div>
              <div class="apfl_col-2">
                <div class="apfl_input_group">
                  <?php
                  if ($apfl_template) {
                    $apfl_listings_search_color = $template_data['apfl_listings_search_color'];
                  } else {
                    $apfl_listings_search_color = get_option('apfl_listings_search_color');
                  }
                  if (!$apfl_listings_search_color) {
                    $apfl_listings_search_color = '#ffffff';
                  }
                  ?>

                  <label> Text Color: </label>
                  <input type="text" name="apfl_listings_search_color"
                    value="<?php echo $apfl_listings_search_color; ?>" class="apfl-listings-color" />
                </div>
                <div class="apfl_input_group">
                  <?php
                  if ($apfl_template) {
                    $apfl_listings_search_bg = $template_data['apfl_listings_search_bg'];
                  } else {
                    $apfl_listings_search_bg = get_option('apfl_listings_search_bg');
                  }
                  ?>

                  <label>Background:</label>
                  <input type="text" name="apfl_listings_search_bg"
                    value="<?php echo $apfl_listings_search_bg; ?>" class="apfl-listings-color" />
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_pagination">Pagination</label>
              </div>

              <div class="apfl_col-2">
                <?php
                $apfl_listings_pagination = 'hide';
                if ($apfl_template && isset($template_data['apfl_listings_pagination'])) {
                  $apfl_listings_pagination = $template_data['apfl_listings_pagination'];
                } else {
                  $apfl_listings_pagination = get_option('apfl_listings_pagination');
                }
                ?>
                <div class="checkbox-wrap">
                  <label class="apfl_switch">
                    <input type="checkbox" name="apfl_listings_pagination" id="apfl_listings_pagination"
                      <?php echo ($apfl_listings_pagination == 'show') ? 'checked' : ''; ?>>
                    <span class="apfl_slide_checkbox"></span>
                  </label>
                </div>

                <div class="apfl_conditional-options <?php echo ($apfl_listings_pagination == 'show') ? '' : 'apfl_hidden'; ?>">

                  <?php
                  $apfl_listings_per_page = 9;
                  if ($apfl_template && isset($template_data['apfl_listings_per_page'])) {
                    $apfl_listings_per_page = $template_data['apfl_listings_per_page'];
                  } else {
                    $apfl_listings_per_page = get_option('apfl_listings_per_page');
                  }
                  ?>
                  <div class="apfl_option-field-pagination">
                    <label for="apfl_listings_per_page">Per Page:</label>
                    <input type="number" name="apfl_listings_per_page" value="<?php echo esc_attr($apfl_listings_per_page); ?>"
                      class="apfl_regular-text" min="1" max="100" />
                  </div>

                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_columns_cnt">Listings Page Layout</label>
              </div>

              <div class="apfl_col-2">
                <?php
                $apfl_columns_cnt = '';
                if ($apfl_template) {
                  if (array_key_exists("apfl_columns_cnt", $template_data)) {
                    $apfl_columns_cnt = $template_data['apfl_columns_cnt'];
                  }
                } else {
                  $apfl_columns_cnt = get_option('apfl_columns_cnt');
                }
                if (!$apfl_columns_cnt) {
                  $apfl_columns_cnt = 3;
                }
                ?>
                <div class="apfl_option-row">
                  <div class="apfl_option-field">
                    <select name="apfl_columns_cnt" id="apfl_columns_cnt">
                      <!-- <option value="1" <?php echo ($apfl_columns_cnt == 1) ? 'selected' : ''; ?>>1 Column</option> -->
                      <option value="2" <?php echo ($apfl_columns_cnt == 2) ? 'selected' : ''; ?>>2 Columns</option>
                      <option value="3" <?php echo ($apfl_columns_cnt == 3) ? 'selected' : ''; ?>>3 Columns</option>
                      <option value="4" <?php echo ($apfl_columns_cnt == 4) ? 'selected' : ''; ?>>4 Columns</option>
                      <option value="5" <?php echo ($apfl_columns_cnt == 5) ? 'selected' : ''; ?>>5 Columns</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_rent_text">Rent Text</label>
              </div>
              <div class="apfl_col-2">
                <?php
                $apfl_rent_text = 'Rent'; // Initialize the rent text variable
                $apfl_rent_text_option = get_option('apfl_rent_text');

                if ($apfl_rent_text_option !== false) {
                  $apfl_rent_text = $apfl_rent_text_option;
                }
                ?>
                <div class="apfl_option-row">
                  <div class="apfl_option-field">
                    <input type="text" name="apfl_rent_text" id="apfl_rent_text" value="<?php echo esc_attr($apfl_rent_text); ?>" />
                  </div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_display_price">Display Rent Price</label>
              </div>

              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_listings_display_price = $template_data['apfl_listings_display_price'];
                } else {
                  $apfl_listings_display_price = get_option('apfl_listings_display_price');
                }
                ?>
                <div class="checkbox-wrap">
                  <label class="apfl_switch">
                    <input type="checkbox" name="apfl_listings_display_price" id="apfl_listings_display_price"
                      <?php echo ($apfl_listings_display_price == 'show') ? 'checked' : ''; ?>>
                    <span class="apfl_slide_checkbox"></span>
                  </label>
                </div>

                <div class="apfl_conditional-options <?php echo ($apfl_listings_display_price == 'show') ? '' : 'apfl_hidden'; ?>">
                  <div class="apfl_option-row">
                    <?php if ($apfl_template != 1): ?>
                      <?php
                      if ($apfl_template) {
                        $apfl_listings_price_pos = $template_data['apfl_listings_price_pos'];
                      } else {
                        $apfl_listings_price_pos = get_option('apfl_listings_price_pos');
                      }
                      if (!$apfl_listings_price_pos) {
                        $apfl_listings_price_pos = 'onimage';
                      }
                      ?>
                      <div class="apfl_option-field">
                        <label>Position:</label>
                        <select name="apfl_listings_price_pos">
                          <option value="onimage" <?php echo ($apfl_listings_price_pos == 'onimage') ? 'selected' : ''; ?>>On Image</option>
                          <option value="offimage" <?php echo ($apfl_listings_price_pos == 'offimage') ? 'selected' : ''; ?>>Below Image</option>
                        </select>
                      </div>
                    <?php endif; ?>

                    <?php
                    if ($apfl_template) {
                      $apfl_listings_price_color = $template_data['apfl_listings_price_color'];
                    } else {
                      $apfl_listings_price_color = get_option('apfl_listings_price_color');
                    }
                    if (!$apfl_listings_price_color) {
                      $apfl_listings_price_color = '#ffffff';
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label>Text Color:</label>
                      <input type="text" name="apfl_listings_price_color"
                        value="<?php echo $apfl_listings_price_color; ?>" class="apfl-listings-color" />
                    </div>

                    <?php
                    if ($apfl_template) {
                      $apfl_listings_price_bg = $template_data['apfl_listings_price_bg'];
                    } else {
                      $apfl_listings_price_bg = get_option('apfl_listings_price_bg');
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label>Background:</label>
                      <input type="text" name="apfl_listings_price_bg"
                        value="<?php echo $apfl_listings_price_bg; ?>" class="apfl-listings-color" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_display_avail">Display Availability</label>
              </div>

              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_listings_display_avail = $template_data['apfl_listings_display_avail'];
                } else {
                  $apfl_listings_display_avail = get_option('apfl_listings_display_avail');
                }
                ?>
                <div class="checkbox-wrap">
                  <label class="apfl_switch">
                    <input type="checkbox" name="apfl_listings_display_avail" id="apfl_listings_display_avail"
                      <?php echo ($apfl_listings_display_avail == 'show') ? 'checked' : ''; ?>>
                    <span class="apfl_slide_checkbox"></span>
                  </label>
                </div>

                <div class="apfl_conditional-options <?php echo ($apfl_listings_display_avail == 'show') ? '' : 'apfl_hidden'; ?>">
                  <div class="apfl_option-row">
                    <?php if ($apfl_template != 1): ?>
                      <?php
                      if ($apfl_template) {
                        $apfl_listings_avail_pos = $template_data['apfl_listings_avail_pos'];
                      } else {
                        $apfl_listings_avail_pos = get_option('apfl_listings_avail_pos');
                      }
                      if (!$apfl_listings_avail_pos) {
                        $apfl_listings_avail_pos = 'onimage';
                      }
                      ?>
                      <div class="apfl_option-field">
                        <label>Position:</label>
                        <select name="apfl_listings_avail_pos">
                          <option value="onimage" <?php echo ($apfl_listings_avail_pos == 'onimage') ? 'selected' : ''; ?>>On Image</option>
                          <option value="offimage" <?php echo ($apfl_listings_avail_pos == 'offimage') ? 'selected' : ''; ?>>Below Image</option>
                        </select>
                      </div>
                    <?php endif; ?>

                    <?php
                    if ($apfl_template) {
                      $apfl_listings_avail_color = $template_data['apfl_listings_avail_color'];
                    } else {
                      $apfl_listings_avail_color = get_option('apfl_listings_avail_color');
                    }
                    if (!$apfl_listings_avail_color) {
                      $apfl_listings_avail_color = '#ffffff';
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label>Text Color:</label>
                      <input type="text" name="apfl_listings_avail_color"
                        value="<?php echo $apfl_listings_avail_color; ?>" class="apfl-listings-color" />
                    </div>

                    <?php
                    if ($apfl_template) {
                      $apfl_listings_avail_bg = $template_data['apfl_listings_avail_bg'];
                    } else {
                      $apfl_listings_avail_bg = get_option('apfl_listings_avail_bg');
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label>Background:</label>
                      <input type="text" name="apfl_listings_avail_bg"
                        value="<?php echo $apfl_listings_avail_bg; ?>" class="apfl-listings-color" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_display_ttl">Display Listing Title</label>
              </div>

              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_listings_display_ttl = $template_data['apfl_listings_display_ttl'];
                } else {
                  $apfl_listings_display_ttl = get_option('apfl_listings_display_ttl');
                }
                ?>
                <div class="checkbox-wrap">
                  <label class="apfl_switch">
                    <input type="checkbox" name="apfl_listings_display_ttl" id="apfl_listings_display_ttl"
                      <?php echo ($apfl_listings_display_ttl == 'show') ? 'checked' : ''; ?>>
                    <span class="apfl_slide_checkbox"></span>
                  </label>
                </div>

                <div class="apfl_conditional-options <?php echo ($apfl_listings_display_ttl == 'show') ? '' : 'apfl_hidden'; ?>">
                  <div class="apfl_option-row">
                    <?php
                    if ($apfl_template) {
                      $apfl_listings_ttl_tag = $template_data['apfl_listings_ttl_tag'];
                    } else {
                      $apfl_listings_ttl_tag = get_option('apfl_listings_ttl_tag');
                    }
                    if (!$apfl_listings_ttl_tag) {
                      $apfl_listings_ttl_tag = 'h2';
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label for="apfl_listings_ttl_tag">Tag:</label>
                      <select name="apfl_listings_ttl_tag" id="apfl_listings_ttl_tag">
                        <option value="h1" <?php echo ($apfl_listings_ttl_tag == 'h1') ? 'selected' : ''; ?>>h1</option>
                        <option value="h2" <?php echo ($apfl_listings_ttl_tag == 'h2') ? 'selected' : ''; ?>>h2</option>
                        <option value="h3" <?php echo ($apfl_listings_ttl_tag == 'h3') ? 'selected' : ''; ?>>h3</option>
                        <option value="h4" <?php echo ($apfl_listings_ttl_tag == 'h4') ? 'selected' : ''; ?>>h4</option>
                        <option value="h5" <?php echo ($apfl_listings_ttl_tag == 'h5') ? 'selected' : ''; ?>>h5</option>
                        <option value="h6" <?php echo ($apfl_listings_ttl_tag == 'h6') ? 'selected' : ''; ?>>h6</option>
                        <option value="p" <?php echo ($apfl_listings_ttl_tag == 'p')  ? 'selected' : ''; ?>>p</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_display_address">Display Listing Address</label>
              </div>

              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_listings_display_address = $template_data['apfl_listings_display_address'];
                } else {
                  $apfl_listings_display_address = get_option('apfl_listings_display_address');
                }
                ?>
                <div class="checkbox-wrap">
                  <label class="apfl_switch">
                    <input type="checkbox" name="apfl_listings_display_address" id="apfl_listings_display_address"
                      <?php echo ($apfl_listings_display_address == 'show') ? 'checked' : ''; ?>>
                    <span class="apfl_slide_checkbox"></span>
                  </label>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_display_baths">Display Baths</label>
              </div>
              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_listings_display_baths = $template_data['apfl_listings_display_baths'];
                } else {
                  $apfl_listings_display_baths = get_option('apfl_listings_display_baths');
                }
                ?>
                <div class="checkbox-input-container">
                  <div class="checkbox-wrap">
                    <label class="apfl_switch">
                      <input type="checkbox" name="apfl_listings_display_baths" id="apfl_listings_display_baths"
                        <?php echo ($apfl_listings_display_baths == 'show') ? 'checked' : ''; ?>>
                      <span class="apfl_slide_checkbox"></span>
                    </label>
                  </div>

                  <div id="bath_img" class="apfl_conditional-options <?php echo ($apfl_listings_display_baths == 'show') ? '' : 'apfl_hidden'; ?>">
                    <?php
                    if ($apfl_template) {
                      $apfl_listings_bath_img = $template_data['apfl_listings_bath_img'];
                    } else {
                      $apfl_listings_bath_img = get_option('apfl_listings_bath_img');
                    }
                    ?>
                    <label for="apfl_listings_bath_img">Image URL:</label>
                    <input type="text" name="apfl_listings_bath_img" id="apfl_listings_bath_img"
                      value="<?php echo esc_attr($apfl_listings_bath_img); ?>"
                      placeholder="Leave blank to hide image">
                  </div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_display_beds">Display Beds</label>
              </div>

              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_listings_display_beds = $template_data['apfl_listings_display_beds'];
                } else {
                  $apfl_listings_display_beds = get_option('apfl_listings_display_beds');
                }
                ?>
                <div class="checkbox-input-container">
                  <div class="checkbox-wrap">
                    <label class="apfl_switch">
                      <input type="checkbox" name="apfl_listings_display_beds" id="apfl_listings_display_beds"
                        <?php echo ($apfl_listings_display_beds == 'show') ? 'checked' : ''; ?>>
                      <span class="apfl_slide_checkbox"></span>
                    </label>
                  </div>

                  <div class="apfl_conditional-options <?php echo ($apfl_listings_display_beds == 'show') ? '' : 'apfl_hidden'; ?>">
                    <?php
                    if ($apfl_template) {
                      $apfl_listings_bed_img = $template_data['apfl_listings_bed_img'];
                    } else {
                      $apfl_listings_bed_img = get_option('apfl_listings_bed_img');
                    }
                    ?>
                    <label for="apfl_listings_bed_img">Image URL:</label>
                    <input type="text" name="apfl_listings_bed_img" id="apfl_listings_bed_img"
                      value="<?php echo ($apfl_listings_bed_img) ? $apfl_listings_bed_img : ''; ?>"
                      placeholder="Leave blank to hide image" class="apfl_input">
                  </div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_display_pets">Display Pets</label>
              </div>
              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_listings_display_pets = isset($template_data['apfl_listings_display_pets']) ? $template_data['apfl_listings_display_pets'] : 'show';
                } else {
                  $apfl_listings_display_pets = get_option('apfl_listings_display_pets', 'show');
                }

                $pets_checked = ($apfl_listings_display_pets == 'show') ? '' : 'apfl_hidden';

                if ($apfl_template) {
                  $apfl_listings_pet_img = isset($template_data['apfl_listings_pet_img']) ? $template_data['apfl_listings_pet_img'] : $apfl_plugin_url . 'images/pet.png';
                } else {
                  $apfl_listings_pet_img = get_option('apfl_listings_pet_img', $apfl_plugin_url . 'images/pet.png');
                }

                if ($apfl_template) {
                  $apfl_listings_no_pet_img = isset($template_data['apfl_listings_no_pet_img']) ? $template_data['apfl_listings_no_pet_img'] : $apfl_plugin_url . 'images/no-pet.png';
                } else {
                  $apfl_listings_no_pet_img = get_option('apfl_listings_no_pet_img', $apfl_plugin_url . 'images/no-pet.png');
                }
                ?>
                <div class="checkbox-input-container">
                  <div class="checkbox-wrap">
                    <label class="apfl_switch">
                      <input type="checkbox" name="apfl_listings_display_pets" id="apfl_listings_display_pets"
                        <?php echo ($apfl_listings_display_pets == 'show') ? 'checked' : ''; ?>>
                      <span class="apfl_slide_checkbox"></span>
                    </label>
                  </div>

                  <div class="apfl_conditional-wrapper">
                    <div class="apfl_conditional-options <?php echo $pets_checked; ?>">
                      <label for="apfl_listings_pet_img">Pet Image URL:</label>
                      <input type="text" name="apfl_listings_pet_img" id="apfl_listings_pet_img"
                        class="apfl_input" value="<?php echo esc_attr($apfl_listings_pet_img); ?>"
                        placeholder="Leave blank to hide image">
                    </div>

                    <div class="apfl_conditional-options <?php echo $pets_checked; ?>">
                      <label for="apfl_listings_no_pet_img">No-pet Image URL:</label>
                      <input type="text" name="apfl_listings_no_pet_img" id="apfl_listings_no_pet_img"
                        class="apfl_input" value="<?php echo esc_attr($apfl_listings_no_pet_img); ?>"
                        placeholder="Leave blank to hide image">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_display_area">Display Area (Square Feet)</label>
              </div>
              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_listings_display_area = isset($template_data['apfl_listings_display_area']) ? $template_data['apfl_listings_display_area'] : 'show';
                } else {
                  $apfl_listings_display_area = get_option('apfl_listings_display_area', 'show');
                }
                ?>
                <div class="checkbox-input-container">
                  <div class="checkbox-wrap">
                    <label class="apfl_switch">
                      <input type="checkbox" name="apfl_listings_display_area" id="apfl_listings_display_area"
                        <?php echo ($apfl_listings_display_area == 'show') ? 'checked' : ''; ?>>
                      <span class="apfl_slide_checkbox"></span>
                    </label>
                  </div>

                  <div class="apfl_conditional-options <?php echo ($apfl_listings_display_area == 'show') ? '' : 'apfl_hidden'; ?>">
                    <?php
                    if ($apfl_template) {
                      $apfl_listings_area_img = isset($template_data['apfl_listings_area_img']) ? $template_data['apfl_listings_area_img'] : $apfl_plugin_url . 'images/area.png';
                    } else {
                      $apfl_listings_area_img = get_option('apfl_listings_area_img', $apfl_plugin_url . 'images/area.png');
                    }
                    ?>

                    <label for="apfl_listings_area_img">Image URL:</label>
                    <input type="text" name="apfl_listings_area_img" id="apfl_listings_area_img"
                      class="apfl_input"
                      value="<?php echo ($apfl_listings_area_img) ? $apfl_listings_area_img : ''; ?>"
                      placeholder="Leave blank to hide image">
                  </div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_display_detail">Display Details Button</label>
              </div>

              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_listings_display_detail = isset($template_data['apfl_listings_display_detail']) ? $template_data['apfl_listings_display_detail'] : 'hide';
                } else {
                  $apfl_listings_display_detail = get_option('apfl_listings_display_detail');
                }
                ?>
                <div class="checkbox-wrap">
                  <input type="hidden" name="apfl_listings_display_detail" value="hide">

                  <div class="checkbox-wrap">
                    <label class="apfl_switch">
                      <input type="checkbox" name="apfl_listings_display_detail" id="apfl_listings_display_detail"
                        <?php echo ($apfl_listings_display_detail == 'show') ? 'checked' : ''; ?>>
                      <span class="apfl_slide_checkbox"></span>
                    </label>
                  </div>
                </div>

                <div class="apfl_conditional-options <?php echo ($apfl_listings_display_detail == 'show') ? '' : 'apfl_hidden'; ?>">
                  <div class="apfl_option-row">
                    <?php
                    if ($apfl_template) {
                      $apfl_listings_detail_color = $template_data['apfl_listings_detail_color'];
                    } else {
                      $apfl_listings_detail_color = get_option('apfl_listings_detail_color');
                    }
                    if (!$apfl_listings_detail_color) {
                      $apfl_listings_detail_color = '#ffffff';
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label for="apfl_listings_detail_color">Text Color:</label>
                      <input type="text" name="apfl_listings_detail_color"
                        value="<?php echo $apfl_listings_detail_color; ?>" class="apfl-listings-color" />
                    </div>

                    <?php
                    if ($apfl_template) {
                      $apfl_listings_detail_bg = $template_data['apfl_listings_detail_bg'];
                    } else {
                      $apfl_listings_detail_bg = get_option('apfl_listings_detail_bg');
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label for="apfl_listings_detail_bg">Background:</label>
                      <input type="text" name="apfl_listings_detail_bg"
                        value="<?php echo $apfl_listings_detail_bg; ?>" class="apfl-listings-color" />
                    </div>

                    <?php
                    if ($apfl_template) {
                      $apfl_listings_detail_hover_color = $template_data['apfl_listings_detail_hover_color'];
                    } else {
                      $apfl_listings_detail_hover_color = get_option('apfl_listings_detail_hover_color');
                    }
                    if (!$apfl_listings_detail_hover_color) {
                      $apfl_listings_detail_hover_color = '#ffffff';
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label for="apfl_listings_detail_hover_color">Hover Text Color:</label>
                      <input type="text" name="apfl_listings_detail_hover_color"
                        value="<?php echo $apfl_listings_detail_hover_color; ?>" class="apfl-listings-color" />
                    </div>

                    <?php
                    if ($apfl_template) {
                      $apfl_listings_detail_hover_bg = $template_data['apfl_listings_detail_hover_bg'];
                    } else {
                      $apfl_listings_detail_hover_bg = get_option('apfl_listings_detail_hover_bg');
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label for="apfl_listings_detail_hover_bg">Hover Background:</label>
                      <input type="text" name="apfl_listings_detail_hover_bg"
                        value="<?php echo $apfl_listings_detail_hover_bg; ?>" class="apfl-listings-color" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_display_apply">Display Apply Button</label>
              </div>

              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_listings_display_apply = $template_data['apfl_listings_display_apply'];
                } else {
                  $apfl_listings_display_apply = get_option('apfl_listings_display_apply');
                }
                ?>
                <div class="checkbox-wrap">

                  <label class="apfl_switch">
                    <input type="checkbox" name="apfl_listings_display_apply" id="apfl_listings_display_apply"
                      <?php echo ($apfl_listings_display_apply == 'show') ? 'checked' : ''; ?>>
                    <span class="apfl_slide_checkbox"></span>
                  </label>
                </div>

                <div class="apfl_conditional-options <?php echo ($apfl_listings_display_apply == 'show') ? '' : 'apfl_hidden'; ?>">
                  <div class="apfl_option-row">
                    <?php
                    if ($apfl_template) {
                      $apfl_listings_apply_color = $template_data['apfl_listings_apply_color'];
                    } else {
                      $apfl_listings_apply_color = get_option('apfl_listings_apply_color');
                    }
                    if (!$apfl_listings_apply_color) {
                      $apfl_listings_apply_color = '#ffffff';
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label for="apfl_listings_apply_color">Text Color:</label>
                      <input type="text" name="apfl_listings_apply_color" value="<?php echo $apfl_listings_apply_color; ?>"
                        class="apfl-listings-color" />
                    </div>

                    <?php
                    if ($apfl_template) {
                      $apfl_listings_apply_bg = $template_data['apfl_listings_apply_bg'];
                    } else {
                      $apfl_listings_apply_bg = get_option('apfl_listings_apply_bg');
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label for="apfl_listings_apply_bg">Background:</label>
                      <input type="text" name="apfl_listings_apply_bg" value="<?php echo $apfl_listings_apply_bg; ?>"
                        class="apfl-listings-color" />
                    </div>

                    <?php
                    if ($apfl_template) {
                      $apfl_listings_apply_hover_color = $template_data['apfl_listings_apply_hover_color'];
                    } else {
                      $apfl_listings_apply_hover_color = get_option('apfl_listings_apply_hover_color');
                    }
                    if (!$apfl_listings_apply_hover_color) {
                      $apfl_listings_apply_hover_color = '#ffffff';
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label for="apfl_listings_apply_hover_color">Hover Text Color:</label>
                      <input type="text" name="apfl_listings_apply_hover_color" value="<?php echo $apfl_listings_apply_hover_color; ?>"
                        class="apfl-listings-color" />
                    </div>

                    <?php
                    if ($apfl_template) {
                      $apfl_listings_apply_hover_bg = $template_data['apfl_listings_apply_hover_bg'];
                    } else {
                      $apfl_listings_apply_hover_bg = get_option('apfl_listings_apply_hover_bg');
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label for="apfl_listings_apply_hover_bg">Hover Background:</label>
                      <input type="text" name="apfl_listings_apply_hover_bg" value="<?php echo $apfl_listings_apply_hover_bg; ?>"
                        class="apfl-listings-color" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_listings_display_schedule">Display Schedule Button</label>
              </div>

              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_listings_display_schedule = isset($template_data['apfl_listings_display_schedule']) ? $template_data['apfl_listings_display_schedule'] : 'hide';
                } else {
                  $apfl_listings_display_schedule = get_option('apfl_listings_display_schedule');
                }
                ?>
                <div class="checkbox-wrap">
                  <label class="apfl_switch">
                    <input type="checkbox" name="apfl_listings_display_schedule" id="apfl_listings_display_schedule"
                      <?php echo ($apfl_listings_display_schedule == 'show') ? 'checked' : ''; ?>>
                    <span class="apfl_slide_checkbox"></span>
                  </label>
                </div>

                <!-- <div class="apfl_conditional-options" style="<?php echo ($apfl_listings_display_schedule == 'show') ? '' : 'apfl_hidden'; ?>"> -->
                  <div class="apfl_conditional-options <?php echo ($apfl_listings_display_schedule == 'show') ? '' : 'apfl_hidden'; ?>">
                  <div class="apfl_option-row">
                    <?php
                    if ($apfl_template) {
                      $apfl_listings_schedule_color = isset($template_data['apfl_listings_schedule_color']) ? $template_data['apfl_listings_schedule_color'] : '#ffffff';
                    } else {
                      $apfl_listings_schedule_color = get_option('apfl_listings_schedule_color');
                    }
                    if (!$apfl_listings_schedule_color) {
                      $apfl_listings_schedule_color = '#ffffff';
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label for="apfl_listings_schedule_color">Text Color:</label>
                      <input type="text" name="apfl_listings_schedule_color" value="<?php echo $apfl_listings_schedule_color; ?>"
                        class="apfl-listings-color" />
                    </div>

                    <?php
                    if ($apfl_template) {
                      $apfl_listings_schedule_bg = isset($template_data['apfl_listings_schedule_bg']) ? $template_data['apfl_listings_schedule_bg'] : '#27547c';
                    } else {
                      $apfl_listings_schedule_bg = get_option('apfl_listings_schedule_bg');
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label for="apfl_listings_schedule_bg">Background:</label>
                      <input type="text" name="apfl_listings_schedule_bg" value="<?php echo $apfl_listings_schedule_bg; ?>"
                        class="apfl-listings-color" />
                    </div>

                    <?php
                    if ($apfl_template) {
                      $apfl_listings_schedule_hover_color = isset($template_data['apfl_listings_schedule_hover_color']) ? $template_data['apfl_listings_schedule_hover_color'] : '#ffffff';
                    } else {
                      $apfl_listings_schedule_hover_color = get_option('apfl_listings_schedule_hover_color');
                    }
                    if (!$apfl_listings_schedule_hover_color) {
                      $apfl_listings_schedule_hover_color = '#ffffff';
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label for="apfl_listings_schedule_hover_color">Hover Text Color:</label>
                      <input type="text" name="apfl_listings_schedule_hover_color" value="<?php echo $apfl_listings_schedule_hover_color; ?>"
                        class="apfl-listings-color" />
                    </div>

                    <?php
                    if ($apfl_template) {
                      $apfl_listings_schedule_hover_bg = isset($template_data['apfl_listings_schedule_hover_bg']) ? $template_data['apfl_listings_schedule_hover_bg'] : '#444444';
                    } else {
                      $apfl_listings_schedule_hover_bg = get_option('apfl_listings_schedule_hover_bg');
                    }
                    ?>
                    <div class="apfl_option-field">
                      <label for="apfl_listings_schedule_hover_bg">Hover Background:</label>
                      <input type="text" name="apfl_listings_schedule_hover_bg" value="<?php echo $apfl_listings_schedule_hover_bg; ?>"
                        class="apfl-listings-color" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_display_large_images">Display Large Images</label>
              </div>
              <div class="apfl_col-2">
                <?php
                if ($apfl_template) {
                  $apfl_display_large_images = isset($template_data['apfl_display_large_images']) ? $template_data['apfl_display_large_images'] : 'hide';
                } else {
                  $apfl_display_large_images = get_option('apfl_display_large_images');
                }
                ?>
                <div class="checkbox-wrap">
                  <label class="apfl_switch">
                    <input type="checkbox" name="apfl_display_large_images" id="apfl_display_large_images"
                      <?php echo ($apfl_display_large_images == 'show') ? 'checked' : ''; ?>>
                    <span class="apfl_slide_checkbox"></span>
                  </label>
                </div>
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <label for="apfl_def_map_zoom">Default Map Zoom</label>
              </div>
              <div class="apfl_col-2">
                <?php
                $apfl_def_map_zoom = 8;
                $apfl_def_map_zoom_option = get_option('apfl_def_map_zoom');

                if ($apfl_def_map_zoom_option !== false) {
                  $apfl_def_map_zoom = $apfl_def_map_zoom_option;
                }
                ?>
                <input type="number" name="apfl_def_map_zoom" id="apfl_def_map_zoom"
                  value="<?php echo esc_attr($apfl_def_map_zoom); ?>" class="apfl_regular-text" min="1" max="100" />
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <?php
                $apfl_custom_apply_lnk = '';
                if ($apfl_template) {
                  if (array_key_exists("apfl_custom_apply_lnk", $template_data)) {
                    $apfl_custom_apply_lnk = $template_data['apfl_custom_apply_lnk'];
                  }
                } else {
                  $apfl_custom_apply_lnk = get_option('apfl_custom_apply_lnk');
                }
                ?>
                <label for="apfl_custom_apply_lnk">Custom Apply Link<br>(Leave blank for default link)</label>
              </div>
              <div class="apfl_col-2">
                <input type="text" name="apfl_custom_apply_lnk" id="apfl_custom_apply_lnk"
                  class="apfl_input" placeholder="please use complete URL including http or https"
                  value="<?php echo $apfl_custom_apply_lnk; ?>">
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <?php
                $apfl_custom_source = '';
                if ($apfl_template) {
                  if (array_key_exists("apfl_custom_source", $template_data)) {
                    $apfl_custom_source = $template_data['apfl_custom_source'];
                  }
                } else {
                  $apfl_custom_source = get_option('apfl_custom_source');
                }
                ?>
                <label for="apfl_custom_source">Apply Link Source
                    <span class="apfl_tooltip apfl_popup_trigger">
                        <span class="dashicons dashicons-info"></span>
                    </span>     
                </label>
                <div class="apfl_popup_overlay"></div>
                <div class="apfl_popup_box">
                    <div class="apfl_popup_header">
                        <span class="apfl_popup_close">&times;</span>
                    </div>
                    <div class="apfl_popup_body">
                        <h3>Apply Link Source</h3>
                        <p>This option allows you to set a custom <strong>source</strong> parameter in your apply URLs for SEO and tracking purposes. Example: <code>https://youraccount.appfolio.com/apply/property_id/?source=example.com</code></p>
                    </div>
                </div>
              </div>
              <div class="apfl_col-2">
                <input type="text" name="apfl_custom_source" id="apfl_custom_source"
                  class="apfl_input" placeholder="e.g. website, facebook, linkedin"
                  value="<?php echo $apfl_custom_source; ?>">
              </div>
            </div>

            <div class="apfl_inner-container">
              <div class="apfl_col-1">
                <?php
                if ($apfl_template) {
                  $apfl_custom_css = $template_data['apfl_custom_css'];
                } else {
                  $apfl_custom_css = get_option('apfl_custom_css');
                }
                ?>
                <label for="apfl_custom_css">Custom CSS</label>
              </div>
              <div class="apfl_col-2 apfl_textarea">
                <textarea name="apfl_custom_css" id="apfl_custom_css"
                  class="apfl_custom_textarea"><?php echo esc_textarea( wp_unslash( $apfl_custom_css ) ); ?></textarea>
              </div>
            </div>

          </div>
          <!-- Save Button -->
          <div class="apfl_submit-container">
            <input type="submit" name="apfl_cstmzr_sbmt" value="Save" class="apfl_btn">
          </div>

        </form>
      </div>
    </div>

<?php
  }
}
