<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

// To override the apply link
add_filter('apfl_apply_btn_link', function($current_listing_Apply_Link){
	$apfl_custom_apply_lnk = '';
    $apfl_custom_source = '';
	$apfl_template = (int)get_option('apfl_template');
	if($apfl_template){
		$template_data = get_option('apfl_template_'.$apfl_template.'_data');
		if (array_key_exists("apfl_custom_apply_lnk", $template_data)){
			$apfl_custom_apply_lnk = $template_data['apfl_custom_apply_lnk'];
		}
        if (array_key_exists("apfl_custom_source", $template_data)){
			$apfl_custom_source = $template_data['apfl_custom_source'];
		}
	} else{
		$apfl_custom_apply_lnk = get_option('apfl_custom_apply_lnk');
        $apfl_custom_source = get_option('apfl_custom_source');
	}

    if($apfl_custom_apply_lnk){
		return $apfl_custom_apply_lnk;
	} 

	if($apfl_custom_source) {
        $parsed_url = parse_url($current_listing_Apply_Link);
        parse_str(html_entity_decode($parsed_url['query'] ?? ''), $query_params);
        $query_params['source'] = $apfl_custom_source;

        $final_url = '';
        if (!empty($parsed_url['scheme']) && !empty($parsed_url['host'])) {
            $final_url = $parsed_url['scheme'] . '://' . $parsed_url['host'];
        }
        if (!empty($parsed_url['path'])) {
            $final_url .= $parsed_url['path'];
        }
        if (!empty($query_params)) {
            $final_url .= '?' . http_build_query($query_params);
        }

        return $final_url;
    }

    return $current_listing_Apply_Link;
});

// To override the listings page heading
add_filter('apfl_page_hdng', function($current_page_hdng){
	$apfl_custom_page_hdng = '';
	$apfl_template = (int)get_option('apfl_template');
	if($apfl_template){
		$template_data = get_option('apfl_template_'.$apfl_template.'_data');
		if (array_key_exists("apfl_page_hdng", $template_data)){
			$apfl_custom_page_hdng = $template_data['apfl_page_hdng'];
		}
	} else{
		$apfl_custom_page_hdng = get_option('apfl_page_hdng');
	}
	
	if($apfl_custom_page_hdng){
		return $apfl_custom_page_hdng;
	} else{
		return $current_page_hdng;
	}
});

// Remote updates: GitHub releases + version.json; WordPress one-click update (see inc/update-check.php).
// Optional wp-config: define('AFC_AUTO_UPDATE', true); for background auto-updates of this plugin only.
