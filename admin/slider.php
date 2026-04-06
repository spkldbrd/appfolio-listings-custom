<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
	exit;
}

// Slider Settings
if (!function_exists('apfl_pp_slider_callback')) {
	function apfl_pp_slider_callback()
	{

		if ($_POST) {
			if (isset($_POST['apfl_slider_sbmt'])) {
				if (isset($_POST['apfl_slider_cnt'])) {
					$apfl_slider_cnt = sanitize_text_field($_POST['apfl_slider_cnt']);
					update_option('apfl_slider_cnt', $apfl_slider_cnt);
				}
				if (isset($_POST['apfl_slider_recent'])) {
					$apfl_slider_recent = filter_var($_POST['apfl_slider_recent'], FILTER_UNSAFE_RAW);
					if ($apfl_slider_recent == 'on') {
						update_option('apfl_slider_recent', 1);
					}
				} else {
					update_option('apfl_slider_recent', 0);
				}

				if (isset($_POST['apfl_slide'])) {
					$apfl_slides = isset($_POST['apfl_slide']) ? (array) $_POST['apfl_slide'] : array();
					$apfl_slides = array_map('esc_attr', $apfl_slides);
					update_option('apfl_slides', $apfl_slides);
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
			<div id="apfl_pp_slider">
				<form method="POST" action="">

					<div class="apfl_setting-container">
                        <h2 class="apfl_heading">Appfolio Listings Slider
                            <div class="apfl-sc-hint">Shortcode: 
                                <span id="apfl_copy_carousel_sc">[apfl_slider]</span>
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

						<!-- Number of Slides -->
						<div class="apfl_inner-container">
							<div class="apfl_col-1">
								<?php $apfl_slider_cnt = get_option('apfl_slider_cnt'); ?>
								<label for="apfl_slider_cnt">Number of Slides</label>
							</div>
							<div class="apfl_col-2">
								<input type="number" name="apfl_slider_cnt" id="apfl_slider_cnt" class="apfl_regular-text"
									value="<?php echo esc_attr($apfl_slider_cnt); ?>">
							</div>
						</div>

						<!-- Use Recent Listings -->
						<div class="apfl_inner-container">
							<div class="apfl_col-1">
								<?php $apfl_slider_recent = get_option('apfl_slider_recent'); ?>
								<label for="apfl_slider_recent">Use Recent Listings</label>
							</div>
							<div class="apfl_col-2">
								<div class="checkbox-wrap">
									<label class="apfl_switch">
										<input type="checkbox" name="apfl_slider_recent" id="apfl_slider_recent" <?php echo ($apfl_slider_recent) ? 'checked' : ''; ?>>
										<span class="apfl_slide_checkbox"></span>
									</label>
								</div>
							</div>
						</div>

						<!-- Slide Inputs -->
						<?php if ($apfl_slider_cnt) {
							$apfl_slides = get_option('apfl_slides');
							for ($i = 0; $i < $apfl_slider_cnt; $i++) { ?>
								<div class="apfl_inner-container apfl_slide_tr" <?php echo ($apfl_slider_recent) ? 'style="display:none;"' : ''; ?>>
									<div class="apfl_col-1">
										<label for="apfl_slide_<?php echo $i; ?>">Slide <?php echo $i + 1; ?> Listing ID</label>
										<small>(Leave blank for default page)</small>

										<span class="apfl_tooltip apfl_popup_trigger">
											<span class="dashicons dashicons-info"></span>
										</span>

										<div class="apfl_popup_overlay"></div>
										<div class="apfl_popup_box">
											<div class="apfl_popup_header">
												<span class="apfl_popup_close">&times;</span>
											</div>
											<div class="apfl_popup_body">
												<h3>Enter single listing ID</h3>
												<p>Enter the 'lid' from your Appfolio listing URL, e.g., from https://example.appfolio.com/listings/detail?lid=64c608b4-e8fe-49o5-9efc-29013d909m19, use 64c608b4-e8fe-49o5-9efc-29013d909m19</p>
											</div>
										</div>
									</div>
									<div class="apfl_col-2">
										<?php
										$val = '';
										if ($apfl_slides && array_key_exists($i, $apfl_slides)) {
											$val = $apfl_slides[$i];
										}
										?>
										<input type="text" name="apfl_slide[]" id="apfl_slide_<?php echo $i; ?>" class="apfl_input"
											placeholder="Enter single listing ID" value="<?php echo esc_attr($val); ?>">
									</div>
								</div>
						<?php }
						} ?>
					</div>

					<!-- Submit Button -->
					<div class="apfl_submit-container">
						<input type="submit" name="apfl_slider_sbmt" id="apfl_slider_sbmt" value="Save" class="apfl_btn">
					</div>

				</form>
			</div>
		</div>


<?php }
}
