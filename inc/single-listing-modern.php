<?php

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

if (!function_exists('apfl_pp_display_single_listing_modern')) {
	function apfl_pp_display_single_listing_modern($url = '', $list_id = ''){

        global $apfl_plugin_url;
		global $client_listings_url;

        $apply_btn_link = $contact_btn_link = '';
		
		$sl_html = '<div class="apfl-sl-mdrn-wrapper">';
		
		if($url){
			
			$place_area = $availability = $rent_price = $address = $ttl = $dsc = '';
			
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
			
			if($html && $html->root) {
				$listing_images = array();
				$i = 0;
				$main_gallery = $html->find('main .gallery', 0);
				
				if($main_gallery){
					$main_imgs = $main_gallery->find('a.swipebox');
					if($main_imgs){
						foreach($main_imgs as $main_img){
							$listing_images[$i]['href'] = $main_img->{'href'};	
							$src = $main_img->{'style'};
							if($src){
								$ini = strpos($src, '(');
								$ini += strlen('(');
								$len = strpos($src, ')', $ini) - $ini;
								$listing_images[$i]['img_url'] = substr($src, $ini, $len);
							} else{
								$img_src_obj = $main_img->find('img', 0);
								$img_src = $img_src_obj->{'src'};
								$listing_images[$i]['img_url'] = $img_src;
							}
							$i++;
						}
					}
					$extra_imgs = $html->find('div[style="display:none"] a.swipebox');
					if($extra_imgs){
						foreach($extra_imgs as $extra_img){
							$listing_images[$i]['href'] = $extra_img->{'href'};
							$ext_img_obj = $extra_img->find('img', 0);
							$listing_images[$i]['img_url'] = $ext_img_obj->{'src'};
							$i++;
						}
					}
				}

				// Beds and Baths
				$rent_banner = $html->find('.rent-banner', 0);
				if($rent_banner) {
					$cap_bed_baths_obj = $rent_banner->find('.u-float-right .rent-banner__text', 0);
					if($cap_bed_baths_obj){
						$cap_bed_baths = $cap_bed_baths_obj->innertext;
						$cap_bed_baths = explode("/", $cap_bed_baths);
						if($cap_bed_baths){
							$bed_std = $cap_bed_baths[0];
							if(strpos($bed_std, 'bd') !== false){ $bed_std = str_replace("bd","Bed",$bed_std); }
							$baths = $cap_bed_baths[1];
							if(strpos($baths, 'ba') !== false){ $baths = str_replace("ba","Baths",$baths); }
						}
					}
				}
				
				$listing_details = $html->find('.listing-detail', 0);
				if($listing_details){
					$ld_body = $listing_details->find('.listing-detail__body', 0);
					if($ld_body){
						$address_obj = $ld_body->find('.header .js-show-title', 0);
						if($address_obj){
							$address = $address_obj->innertext;
						}
						$bb_obj = $ld_body->find('.header .header__summary', 0);
						if($bb_obj){
							$bed_bath_avail = $bb_obj->innertext;
							$bed_bath_avail = explode("| ", $bed_bath_avail);
							if($bed_bath_avail){
								if(strpos($bed_bath_avail[0], 'Sq.') !== false){
									$reversedParts = explode(' ,', strrev($bed_bath_avail[0]));
									$place_area = strrev($reversedParts[0]); // get last part
								}
								if(count($bed_bath_avail) > 1){
									$availability = $bed_bath_avail[1];
								}
							}
						}
						$ttl_obj = $ld_body->find('.listing-detail__title', 0);
						if($ttl_obj){
							$ttl = $ttl_obj->innertext;
						}
						$dsc_obj = $ld_body->find('.listing-detail__description', 0);
						if($dsc_obj){
							
							$desc_anchors = $dsc_obj->find('a');
							
							$kuula_tour = '';
							foreach($desc_anchors as $desc_anchor_obj){
								$a_href = $desc_anchor_obj->{'href'};
								if (str_contains($a_href, 'kuula.co')) { 
									$kuula_tour = '<iframe width="100%" height="640" frameborder="0" allow="xr-spatial-tracking; gyroscope; accelerometer" allowfullscreen scrolling="no" src="'.$a_href.'"></iframe>';
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
					if($ld_sidebar){
						$rent_cap = $ld_sidebar->firstChild();
						if($rent_cap){
							$rent_price_obj = $rent_cap->find('h2', 0);
							if($rent_price_obj){ $rent_price = $rent_price_obj->innertext; }
							
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
						if($btns){
							
							$apply_btn_link_obj = $btns->find('.btn-warning', 0);
							if($apply_btn_link_obj){ $apply_btn_link = $apply_btn_link_obj->{'href'}; }
							
							$contact_btn_link_obj = $btns->find('.btn-secondary', 0);
							if($contact_btn_link_obj){ 
								$contact_btn_link = $contact_btn_link_obj->{'href'};
								$contact_btn_link = $client_listings_url.$contact_btn_link;
								$contact_btn_link = apply_filters( "apfl_contact_btn_link", $contact_btn_link, $contact_btn_link );
							}
							$custom_contact_btn_link = get_option('apfl_custom_contact_us');
							if($custom_contact_btn_link && $custom_contact_btn_link != ''){
								$contact_btn_link = get_option('apfl_custom_contact_us');
							}
						}
						
						$logo_link_obj = $ld_sidebar->find('.sidebar__portfolio-logo', 0);
						if($logo_link_obj){ $logo_link = $logo_link_obj->{'src'}; }
						
						$phn_ctc = '';
						$phn_ctc_obj = $ld_sidebar->find('.u-pad-bl', 0);
						if($phn_ctc_obj){
							$phn_ctc = $phn_ctc_obj->innertext;
							$phn_ctc = preg_replace('#<(a)(?:[^>]+)?>.*?</\1>#s', '', $phn_ctc);
						}
					}
				}
				
				$apfl_template = (int)get_option('apfl_template');
				if($apfl_template){
					$template_data = get_option('apfl_template_'.$apfl_template.'_data');
					
					$apfl_listings_display_beds = $template_data['apfl_listings_display_beds'];
					$apfl_listings_bed_img = $template_data['apfl_listings_bed_img'];
					
					$apfl_listings_display_baths = $template_data['apfl_listings_display_baths'];
					$apfl_listings_bath_img = $template_data['apfl_listings_bath_img'];
					
					
				} else{
					$apfl_listings_display_beds = get_option('apfl_listings_display_beds');
					$apfl_listings_bed_img = get_option('apfl_listings_bed_img');
					
					$apfl_listings_display_baths = get_option('apfl_listings_display_baths');
					$apfl_listings_bath_img = get_option('apfl_listings_bath_img');
				}
					
				$sl_html .= '
				<div class="listing-sec">';
					
					// Gallery
					$sl_html .= '
					<div class="apfl-mdrn-gallery-wrapper">';
						if($listing_images){
							$sl_html .='<div class="apfl-gallery">';
								$j = 1; 
								
								$video_url = '';
								
								foreach($listing_images as $list_img){
									
									if(str_contains($list_img["href"], 'youtube')){
										$video_url = $list_img["href"];
									}
									
									$sl_html .='<div class="mySlides">
										<div class="numbertext">'.$j.' / '.count($listing_images).'</div>
										<img src="' . (str_contains($list_img["href"], 'youtube') ? $list_img["img_url"] : $list_img["href"]) . '" data-href="'.$list_img["href"].'" data-id="apfl_gal_img_'.$j.'">
									</div>';
									$j++;
								}
								$sl_html .='<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
								<a class="next" onclick="plusSlides(1)">&#10095;</a>
								<div class="row" style="margin-top: 7px;">';
									$k = 1; 
									foreach($listing_images as $list_img){
										$sl_html .='<div id="image-prvw" class="imgcolumn">
											<img class="demo cursor" src="'.$list_img["img_url"].'" onclick="currentSlide('.$k.')">
										</div>';
									$k++; }
							$sl_html .= '</div></div>';

							if($video_url){
								$iframe_code = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<iframe width=\"560\" height=\"330\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>", $video_url);
								$sl_html .= '<div id="apfl-vdo">
									'.$iframe_code.'
								</div>';
							}
							
							// Load 3-d tour
							if($kuula_tour){
								$sl_html .= '<div id="apfl-kuula-tour">
									<h4>Virtual Tour:</h4>
									'.$kuula_tour.'
								</div>';
							}
						
						}
					$sl_html .='
					</div>';

					// Content
					$sl_html .= '
					<div class="apfl-mdrn-mob-rent">
						<div>';

							$apfl_details_display_price = get_option('apfl_details_display_price', 'show');
							$apfl_details_price_mo = get_option('apfl_details_price_mo', 'yes');
							if($apfl_details_display_price == 'show') {
								if($apfl_details_price_mo == 'no') {
									$rent_price = str_replace('/mo', '', $rent_price);
								}
								$sl_html .='<div>RENT</div><div style="font-size:25px">'.$rent_price.'</div>';
							}

						$sl_html .=
						'</div>
						<div>
							<div>BED / BATH</div>
							<div style="font-size:25px">';

								if($apfl_listings_display_beds == 'show'){
									$sl_html .= '<span>' . $bed_std . '</span>';
								}

								if($apfl_listings_display_baths == 'show'){
									$sl_html .= '<span> / ' . $baths . '</span>';
								}

							$sl_html .= '
							</div>
						</div>
					</div>';

					$sl_html .='
					<div class="apfl-mdrn-content-wrapper">
						<div class="apfl-mdrn-three-fourth">'; // Left section
					
							// Listing header
							$sl_html .='
							<div class="apfl-mdrn-content-header">
								<h3 class="address-hdng">'.$address.'</h3>
								<p class="bed-bath-std">';
									if($apfl_listings_display_beds == 'show'){
										if($apfl_listings_bed_img){
											$sl_html .= '<img class="bedimg" src="'.$apfl_listings_bed_img.'"><span>'.$bed_std.'</span>';
										} else{
											$sl_html .= '<img class="bedimg" src="'.$apfl_plugin_url.'images/sleep.png"><span>'.$bed_std.'</span>';
										}
									}
									
									if($apfl_listings_display_baths == 'show'){
										if($apfl_listings_bath_img){
											$sl_html .= '<img class="bathimg" src="'.$apfl_listings_bath_img.'"><span>'.$baths.'</span>';
										} else{
											$sl_html .= '<img class="bathimg" src="'.$apfl_plugin_url.'images/bathtub.png"><span>'.$baths.'</span>';
										}
									}
									
									if($place_area){
										$sl_html .='<span> | '.$place_area.'</span>';
									}

									if($availability){
										$sl_html .='<span style="margin-bottom: 1rem;"> | ';
										if(preg_replace('/\s+/', '', $availability) == 'AvailableNow'){
											$sl_html .='<img class="avail-now" src="'.$apfl_plugin_url.'images/check.png">';
										}
										$sl_html .='<span id="avail-txt">'.$availability.'</span>';
										$sl_html .='</spanp>';
									}
								$sl_html .='</p>';
							$sl_html .= '
							</div>';
								
							$sl_html .= '
							<div class="apfl-mdrn-content-desc">';
								// Description
								$apfl_details_display_ttl = get_option('apfl_details_display_ttl', 'show');
								$apfl_details_ttl_tag = get_option('apfl_details_ttl_tag', 'p'); 
								if($apfl_details_display_ttl == 'show'){
									$sl_html .='<' . $apfl_details_ttl_tag . ' class="content-title desctitle">'.$ttl.'</' . $apfl_details_ttl_tag . '>';
								}
								$sl_html .='<p class="desc">'.$dsc.'</p>';

								// Extra
								$sl_html .= '
								<div class="apfl-mdrn-extra">';
									if($extra_fields){
										$sl_html .='<div class="extra">';
										foreach($extra_fields as $field){
											$sl_html .='<div class="extra-half">';
											
											$extra_fld_obj = $field->find("h3", 0);
											if($extra_fld_obj){ $sl_html .='<h4 class="content-title">'.$extra_fld_obj->innertext.'</h4>'; }
											
											$extra_fld_ul_obj = $field->find("ul", 0);
											if($extra_fld_ul_obj){ $sl_html .='<ul>'.$extra_fld_ul_obj->innertext.'</ul>'; }
											
											$sl_html .='</div>';
										}
										$sl_html .='</div>';
									}
								$sl_html .='
								</div>';
							$sl_html .= '
							</div>
						</div>';

						$sl_html .= '
						<div class="apfl-mdrn-one-fourth">'; // Right section

							$sl_html .='
							<div class="details-top">';
								if($apfl_details_display_price == 'show') {
									if($apfl_details_price_mo == 'no') {
										$rent_price = str_replace('/mo', '', $rent_price);
									}
									$sl_html .='<div>RENT</div><p class="rent-hdng">'.$rent_price.'</p>';
								}
									
							$sl_html .='
							</div>';
						
							$sl_html .='<div class="apfl-mdrn-apply-sec">';
							$apfl_dtl_listings_display_apply = get_option('apfl_dtl_listings_display_apply', 'show'); 
							$apfl_dtl_listings_display_schedule = get_option('apfl_dtl_listings_display_schedule', 'show'); 
							$apfl_dtl_listings_display_contact = get_option('apfl_dtl_listings_display_contact', 'show'); 

							$schshowing_btn_link = '';
							if ($list_id && $apfl_dtl_listings_display_schedule == "show") {
								$schshowing_btn_link = $client_listings_url . '/listings/showings/new?listable_uid=' . $list_id;
								$schshowing_btn_link = apply_filters( "apfl_schshowing_btn_link", $schshowing_btn_link, $schshowing_btn_link );
									$sl_html .='<a id="schshowingBtn" class="sl-btns" target="_blank" href="'.$schshowing_btn_link.'">Schedule Showing</a>';
							}

						
							if($apply_btn_link && $apfl_dtl_listings_display_apply == "show") {
								$apply_btn_link = $client_listings_url.$apply_btn_link;
								$apply_btn_link = apply_filters( "apfl_apply_btn_link", $apply_btn_link, $apply_btn_link );
								$sl_html .='<a id="applyBtn" class="sl-btns" target="_blank" href="'.$apply_btn_link.'">Apply Now</a>';
							}
						
							if($contact_btn_link && $apfl_dtl_listings_display_contact == "show") {
								$sl_html .='<a id="contactBtn" class="sl-btns" target="_blank" href="'.$contact_btn_link.'">Contact Us</a>';
							}
						
							$sl_html .= '
							<div>Share this listing:</div>
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
						
							$sl_html .='<p>'.$phn_ctc.'</p>';

							$all_lstng_url = '';
							if(isset($_GET['ref'])){
								$all_lstng_url = htmlentities( $_GET['ref'] );
							}
							else{
							
								$apfl_new_lstngs_page = get_option('apfl_all_lstngs_page');
								if($apfl_new_lstngs_page){
									$all_lstng_url = $apfl_new_lstngs_page;
								} else {
									$all_lstng_url = strtok($_SERVER["REQUEST_URI"], '?');
								}
								
							}
							if($all_lstng_url){
								$sl_html .= '<div><a class="" href="'.$all_lstng_url.'"> View All Listings</a></div>';
							}
						
						$sl_html .= '
						</div>';
					
					$sl_html .='
					</div>
				</div>';

				if($main_gallery){
					$sl_html .='<script>
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
		
		$sl_html .='
        </div>';

		return $sl_html;
    }
}