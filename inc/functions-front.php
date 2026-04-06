<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Muffin Builder / BeTheme style column class for listing grid cells.
 *
 * @param int $n Column count (1–5).
 * @return string
 */
if (!function_exists('apfl_pp_column_class_for_grid')) {
    function apfl_pp_column_class_for_grid($n)
    {
        $n = (int) $n;
        $map = array(
            1 => 'one',
            2 => 'one-second',
            3 => 'one-third',
            4 => 'one-fourth',
            5 => 'one-fifth',
        );
        return isset($map[$n]) ? $map[$n] : 'one-third';
    }
}

add_action('wp_ajax_apfl_generate_listing_html', 'apfl_generate_listing_html');
add_action('wp_ajax_nopriv_apfl_generate_listing_html', 'apfl_generate_listing_html');
if (!function_exists('apfl_generate_listing_html')) {
    function apfl_generate_listing_html()
    {
        $res_array = array();
        if (!isset($_POST) || empty($_POST) || !wp_verify_nonce($_POST['apfl_nonce'], 'apfl_ajax_nonce')) {
            header('HTTP/1.1 400 Empty POST Values');
            $res_array['error'] = ('Error - Could not verify POST values');
            echo json_encode($res_array);
            exit;
        }

        $current_page = '';
        if (isset($_POST['page'])) {
            $current_page = sanitize_text_field($_POST['page']);
        }

        $type = 'listings';
        if (isset($_POST['type'])) {
            $type = sanitize_text_field($_POST['type']);
        }

        $itm_cntr = 0;
        $itm_searched = 0;
        $render_html = '';
        $apfl_listings_per_page = get_option('apfl_listings_per_page', true);
        if ($type == 'listings') {
            $listings_arr = get_option('apfl_listings_arr', true);
            $textarea_input = get_option('apfl_textarea_input', true);
            $apfl_columns_cnt = get_option('apfl_columns_cnt', true);
            $total_items = get_option('apfl_listing_items_count', true);
            $total_pages = get_option('apfl_total_pages', true);
        } else if ($type == 'multiple-listings') {
            $listings_arr = get_option('apfl_listings_arr_multiple', true);
            $textarea_input = get_option('apfl_textarea_input_multiple', true);
            $apfl_columns_cnt = get_option('apfl_columns_cnt_multiple', true);
            $total_items = get_option('apfl_listing_items_count_multiple', true);
            $total_pages = get_option('apfl_total_pages_multiple', true);
        }




        $apfl_template = (int) get_option('apfl_template');

        $items_per_page =  $apfl_listings_per_page;
        $offset = ($current_page - 1) * $items_per_page;

        // Get the listings for the current page
        $listings_arr = array_slice($listings_arr, $offset, $items_per_page);


        foreach ($listings_arr as $key => $val) {

            if ($apfl_template != 1) {
                if ($itm_cntr % $apfl_columns_cnt == 0) {
                    $render_html .= '<div class="listing-items-grp">';
                }
            }

            if ($textarea_input) {
                if (str_contains(strtolower($val['address']), strtolower($textarea_input))) {
                    $render_html .= $val['html'];
                    $itm_searched++;
                }
            } else {
                $render_html .= $val['html'];
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


        $pagination_html = '';

        // // Left arrow
        // if ($current_page >= 1) {
        //     $pagination_html .= '<a href="javascript:void(0)" apfl-page="' . max(1, $current_page - 1) . '" class="apfl-left-arrow arrow">&lt;</a>';
        // } 
        
        // // Display pages
        // $start_page = max(1, $current_page - 2);
        // $end_page = min($total_pages, $start_page + 4);
        
        // for ($page = $start_page; $page <= $end_page; $page++) {
        //     $current_class = ($page == $current_page) ? 'current-page' : '';
        //     $pagination_html .= '<a href="javascript:void(0)" apfl-page="' . max(1, min($total_pages, $page)) . '" class="' . $current_class . '">' . $page . '</a>';
        // }
        
        // // Display ellipsis and last page link
        // if ($end_page < $total_pages) {
        //     $pagination_html .= '<span class="ellipsis">...</span>';
        //     $pagination_html .= '<a href="javascript:void(0)" apfl-page="' . $total_pages . '">' . $total_pages . '</a>';
        // }
        
        // // Right arrow
        // if ($current_page < $total_pages) {
        //     $pagination_html .= '<a href="javascript:void(0)" apfl-page="' . min($total_pages, $current_page + 1) . '" class="apfl-right-arrow arrow">&gt;</a>';
        // }

        // Double arrow left arrow to go directly to the first page
				if ($current_page > 5) {
					$pagination_html .= '<a href="javascript:void(0)" apfl-page="1" class="double-arrow arrow">&lt;&lt;</a>';
				}

				// Left arrow
				if ($current_page >= 1) {
					$pagination_html .= '<a href="javascript:void(0)" apfl-page="' . max(1, $current_page - 1) . '" class="apfl-left-arrow arrow">&lt;</a>';
				}

				// Display pages
				$start_page = max(1, $current_page - 2);
				$end_page = min($total_pages, $start_page + 4);

				for ($page = $start_page; $page <= $end_page; $page++) {
					$current_class = ($page == $current_page) ? 'current-page' : '';
					$pagination_html .= '<a href="javascript:void(0)" apfl-page="' . max(1, min($total_pages, $page)) . '" class="' . $current_class . '">' . $page . '</a>';
				}



				// Right arrow
				if ($current_page < $total_pages) {
					$pagination_html .= '<a href="javascript:void(0)" apfl-page="' . (min($total_pages, $current_page + 1)) . '" class="apfl-right-arrow arrow">&gt;</a>';
				}
				// Display double arrow at right 
				if ($end_page < $total_pages) {
					$pagination_html .= '<a href="javascript:void(0)" apfl-page="' . $total_pages . '" class="double-arrow arrow">&gt;&gt;</a>';
				}
        $res_array['pagination_html'] = $pagination_html;
        $res_array['html'] = $render_html;
        $res_array['total_pages'] = $total_pages;
        echo json_encode($res_array);
        exit;
    }
}

add_action('wp_ajax_apfl_get_slider_options', 'apfl_get_slider_options');
add_action('wp_ajax_nopriv_apfl_get_slider_options', 'apfl_get_slider_options');
if (!function_exists('apfl_get_slider_options')) {
    function apfl_get_slider_options()
    {
        $res_array = array();
        if (!isset($_POST) || empty($_POST) || !wp_verify_nonce($_POST['apfl_nonce'], 'apfl_ajax_nonce')) {
            header('HTTP/1.1 400 Empty POST Values');
            $res_array['error'] = ('Error - Could not verify POST values');
            echo json_encode($res_array);
            exit;
        }

        global $apfl_plugin_url;
		global $client_listings_url;

		if(!$client_listings_url) { 
            $res_array['error'] = ('The Appfolio URL is blank. Please contact site owner.');
            echo json_encode($res_array);
            exit;
        }

        $apfl_slider_cnt = get_option('apfl_slider_cnt');
        if(!$apfl_slider_cnt) {
            $res_array['error'] = ('Please set the number of slides in the settings.');
            echo json_encode($res_array);
            exit;
        }

        $last_char = substr($client_listings_url, -1);
        if($last_char == '/'){
            $client_listings_url = substr($client_listings_url, 0, -1);
        }

        $apfl_slider_recent = get_option('apfl_slider_recent');

        $lstng_dtl_page = get_option('apfl_all_lstngs_page');;
        $apfl_sngl_lstngs_page = get_option('apfl_sngl_lstngs_page');
        if($apfl_sngl_lstngs_page){
            $lstng_dtl_page = $apfl_sngl_lstngs_page;
        }

        $apfl_custom_apply_lnk = '';
        $apfl_template = (int)get_option('apfl_template');
        if($apfl_template){
            $template_data = get_option('apfl_template_'.$apfl_template.'_data');
            if (array_key_exists("apfl_custom_apply_lnk", $template_data)){
                $apfl_custom_apply_lnk = $template_data['apfl_custom_apply_lnk'];
            }
        } else{
            $apfl_custom_apply_lnk = get_option('apfl_custom_apply_lnk');
        }

        $res_array['client_listings_url'] = $client_listings_url;
        $res_array['apfl_plugin_url'] = $apfl_plugin_url;
        $res_array['apfl_slider_cnt'] = $apfl_slider_cnt;
        $res_array['apfl_slider_recent'] = $apfl_slider_recent;
        $res_array['lstng_dtl_page'] = $lstng_dtl_page;
        $res_array['apfl_custom_apply_lnk'] = $apfl_custom_apply_lnk;

        if(!$apfl_slider_recent){
            $apfl_slides = get_option('apfl_slides');
            $res_array['apfl_slides'] = $apfl_slides;
        }

        echo json_encode($res_array);
        exit;
    }
}

add_action('wp_ajax_apfl_get_carousel_options', 'apfl_get_carousel_options');
add_action('wp_ajax_nopriv_apfl_get_carousel_options', 'apfl_get_carousel_options');
if (!function_exists('apfl_get_carousel_options')) {
    function apfl_get_carousel_options()
    {
        $res_array = array();
        if (!isset($_POST) || empty($_POST) || !wp_verify_nonce($_POST['apfl_nonce'], 'apfl_ajax_nonce')) {
            header('HTTP/1.1 400 Empty POST Values');
            $res_array['error'] = ('Error - Could not verify POST values');
            echo json_encode($res_array);
            exit;
        }

        global $apfl_plugin_url;
		global $client_listings_url;

		if(!$client_listings_url) { 
            $res_array['error'] = ('The Appfolio URL is blank. Please contact site owner.');
            echo json_encode($res_array);
            exit;
        }

        $apfl_crsl_cnt = get_option('apfl_crsl_cnt', 6);
        if(!$apfl_crsl_cnt) {
            $res_array['error'] = ('Please set the number of slides in the settings.');
            echo json_encode($res_array);
            exit;
        }

        $last_char = substr($client_listings_url, -1);
        if($last_char == '/'){
            $client_listings_url = substr($client_listings_url, 0, -1);
        }

        $apfl_crsl_recent = get_option('apfl_crsl_recent', 1);

        $lstng_dtl_page = get_option('apfl_all_lstngs_page');;
        $apfl_sngl_lstngs_page = get_option('apfl_sngl_lstngs_page');
        if($apfl_sngl_lstngs_page){
            $lstng_dtl_page = $apfl_sngl_lstngs_page;
        }

        $apfl_custom_apply_lnk = '';
        $apfl_template = (int)get_option('apfl_template');
        if($apfl_template){
            $template_data = get_option('apfl_template_'.$apfl_template.'_data');
            if (array_key_exists("apfl_custom_apply_lnk", $template_data)){
                $apfl_custom_apply_lnk = $template_data['apfl_custom_apply_lnk'];
            }
        } else{
            $apfl_custom_apply_lnk = get_option('apfl_custom_apply_lnk');
        }

        $apfl_carousel_template = get_option('apfl_carousel_template', 'classic');
        $apfl_crsl_autoplay = get_option('apfl_crsl_autoplay', 'yes');
        $apfl_crsl_nav = get_option('apfl_crsl_nav', 'yes');
        $apfl_crsl_interval = get_option('apfl_crsl_interval', 2000);
        $apfl_crsl_scroll_dir = get_option('apfl_crsl_scroll_dir', 'right');

        $res_array['client_listings_url'] = $client_listings_url;
        $res_array['apfl_plugin_url'] = $apfl_plugin_url;
        $res_array['apfl_crsl_cnt'] = $apfl_crsl_cnt;
        $res_array['apfl_crsl_recent'] = $apfl_crsl_recent;
        $res_array['lstng_dtl_page'] = $lstng_dtl_page;
        $res_array['apfl_custom_apply_lnk'] = $apfl_custom_apply_lnk;
        $res_array['template'] = $apfl_carousel_template;
        $res_array['autoplay'] = $apfl_crsl_autoplay;
        $res_array['nav'] = $apfl_crsl_nav;
        $res_array['interval'] = $apfl_crsl_interval;
        $res_array['scroll_dir'] = $apfl_crsl_scroll_dir;

        if(!$apfl_crsl_recent){
            $apfl_crsl_slides = get_option('apfl_crsl_slides');
            $res_array['apfl_crsl_slides'] = $apfl_crsl_slides;
        }

        echo json_encode($res_array);
        exit;
    }
}