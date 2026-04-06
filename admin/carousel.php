<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

// Carousel Settings
if (!function_exists('apfl_pp_carousel_callback')) {
    function apfl_pp_carousel_callback()
    {

        if ($_POST) {
            if (isset($_POST['apfl_crsl_sbmt'])) {

                if (isset($_POST['apfl_carousel_template'])) {

                    $apfl_carousel_template = sanitize_text_field($_POST['apfl_carousel_template']);
                    update_option('apfl_carousel_template', $apfl_carousel_template);

                } else {
                    update_option('apfl_carousel_template', 'classic');
                }
                
                if (isset($_POST['apfl_crsl_cnt'])) {
                    $apfl_crsl_cnt = sanitize_text_field($_POST['apfl_crsl_cnt']);
                    update_option('apfl_crsl_cnt', $apfl_crsl_cnt);
                }

                if (isset($_POST['apfl_crsl_recent'])) {
                    $apfl_crsl_recent = filter_var($_POST['apfl_crsl_recent'], FILTER_UNSAFE_RAW);
                    if ($apfl_crsl_recent == 'on') {
                        update_option('apfl_crsl_recent', 1);
                    }
                } else {
                    update_option('apfl_crsl_recent', 0);
                }

                if (isset($_POST['apfl_crsl_slide'])) {
                    $apfl_crsl_slides = isset($_POST['apfl_crsl_slide']) ? (array) $_POST['apfl_crsl_slide'] : array();
                    $apfl_crsl_slides = array_map('esc_attr', $apfl_crsl_slides);
                    update_option('apfl_crsl_slides', $apfl_crsl_slides);
                }

                if (isset($_POST['apfl_crsl_nav'])) {
                    $apfl_crsl_nav = filter_var($_POST['apfl_crsl_nav'], FILTER_UNSAFE_RAW);
                    if ($apfl_crsl_nav == 'on') {
                        update_option('apfl_crsl_nav', 'yes');
                    }
                } else {
                    update_option('apfl_crsl_nav', 'no');
                }

                if (isset($_POST['apfl_crsl_autoplay'])) {
                    $apfl_crsl_autoplay = filter_var($_POST['apfl_crsl_autoplay'], FILTER_UNSAFE_RAW);
                    if ($apfl_crsl_autoplay == 'on') {
                        update_option('apfl_crsl_autoplay', 'yes');
                    }
                } else {
                    update_option('apfl_crsl_autoplay', 'no');
                    update_option('apfl_crsl_nav', 'yes');
                }

                if (isset($_POST['apfl_crsl_interval'])) {
                    $apfl_crsl_interval = sanitize_text_field($_POST['apfl_crsl_interval']);
                    update_option('apfl_crsl_interval', $apfl_crsl_interval);
                }

                if (isset($_POST['apfl_crsl_scroll_dir'])) {
                    $apfl_crsl_scroll_dir = sanitize_text_field($_POST['apfl_crsl_scroll_dir']);
                    update_option('apfl_crsl_scroll_dir', $apfl_crsl_scroll_dir);
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
            <div id="apfl_pp_crsl">
                <form method="POST" action="">

                    <div class="apfl_setting-container">
                        <h2 class="apfl_heading">Appfolio Listings Carousel
                            <div class="apfl-sc-hint">Shortcode: 
                                <span id="apfl_copy_carousel_sc">[apfl_carousel]</span>
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

                        <!-- Template Selection -->
                        <div class="apfl_inner-container">
                            <div class="apfl_col-1">
                                <?php
                                $apfl_carousel_template = get_option('apfl_carousel_template', 'classic');
                                ?>
                                <label for="apfl_carousel_template">Template</label>
                            </div>
                            <div class="apfl_col-2">
                                <label>
                                    <input type="radio" name="apfl_carousel_template" value="classic" <?php echo ($apfl_carousel_template == 'classic') ? 'checked' : ''; ?>>
                                    Classic Template
                                </label>
                                <label>
                                    <input type="radio" name="apfl_carousel_template" value="modern" <?php echo ($apfl_carousel_template == 'modern') ? 'checked' : ''; ?>>
                                    Modern Template
                                </label>
                            </div>
                        </div>

                        <!-- Number of Slides -->
                        <div class="apfl_inner-container">
                            <div class="apfl_col-1">
                                <?php $apfl_crsl_cnt = get_option('apfl_crsl_cnt', 6); ?>
                                <label for="apfl_crsl_cnt">Number of Slides</label>
                            </div>
                            <div class="apfl_col-2">
                                <input type="number" name="apfl_crsl_cnt" id="apfl_crsl_cnt" class="apfl_regular-text"
                                    value="<?php echo esc_attr($apfl_crsl_cnt); ?>">
                            </div>
                        </div>

                        <!-- Use Recent Listings -->
                        <div class="apfl_inner-container">
                            <div class="apfl_col-1">
                                <?php $apfl_crsl_recent = get_option('apfl_crsl_recent', 1); ?>
                                <label for="apfl_crsl_recent">Use Recent Listings</label>
                            </div>
                            <div class="apfl_col-2">
                                <div class="checkbox-wrap">
                                    <label class="apfl_switch">
                                        <input type="checkbox" name="apfl_crsl_recent" id="apfl_crsl_recent" <?php echo ($apfl_crsl_recent) ? 'checked' : ''; ?>>
                                        <span class="apfl_slide_checkbox"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Slide Inputs -->
                        <?php if ($apfl_crsl_cnt) {
                            $apfl_crsl_slides = get_option('apfl_crsl_slides');
                            for ($i = 0; $i < $apfl_crsl_cnt; $i++) { ?>
                                <div class="apfl_inner-container apfl_slide_tr" <?php echo ($apfl_crsl_recent) ? 'style="display:none;"' : ''; ?>>
                                    <div class="apfl_col-1">
                                        <label for="apfl_crsl_slide_<?php echo $i; ?>">Slide <?php echo $i + 1; ?> Listing ID</label>
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
                                        if ($apfl_crsl_slides && array_key_exists($i, $apfl_crsl_slides)) {
                                            $val = $apfl_crsl_slides[$i];
                                        }
                                        ?>
                                        <input type="text" name="apfl_crsl_slide[]" id="apfl_crsl_slide_<?php echo $i; ?>" class="apfl_input"
                                            placeholder="Enter single listing ID" value="<?php echo esc_attr($val); ?>">
                                    </div>
                                </div>
                        <?php }
                        } ?>

                        <div class="apfl-crsl-modern-settings <?php echo ($apfl_carousel_template == 'modern' ? '' : 'apfl_hidden'); ?>">

                            <div class="apfl_inner-container">
                                <div class="apfl_col-1">
                                    <label for="apfl_crsl_autoplay">Autoplay</label>
                                </div>

                                <div class="apfl_col-2">
                                    <?php
                                    $apfl_crsl_autoplay = get_option('apfl_crsl_autoplay', 'yes');
                                    ?>
                                    <div class="checkbox-wrap">
                                        <label class="apfl_switch">
                                            <input type="checkbox" name="apfl_crsl_autoplay" id="apfl_crsl_autoplay"
                                                <?php echo ($apfl_crsl_autoplay == 'yes') ? 'checked' : ''; ?>>
                                            <span class="apfl_slide_checkbox"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="apfl-crsl-autoplay-settings <?php echo ($apfl_crsl_autoplay == 'yes' ? '' : 'apfl_hidden'); ?>">
                                <div class="apfl_inner-container">
                                    <div class="apfl_col-1">
                                        <label for="apfl_crsl_nav">Navigation</label>
                                    </div>

                                    <div class="apfl_col-2">
                                        <?php
                                        $apfl_crsl_nav = get_option('apfl_crsl_nav', 'yes');
                                        ?>
                                        <div class="checkbox-wrap">
                                            <label class="apfl_switch">
                                                <input type="checkbox" name="apfl_crsl_nav" id="apfl_crsl_nav"
                                                    <?php echo ($apfl_crsl_nav == 'yes') ? 'checked' : ''; ?>>
                                                <span class="apfl_slide_checkbox"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="apfl_inner-container">
                                    <div class="apfl_col-1">
                                        <?php $apfl_crsl_interval = get_option('apfl_crsl_interval', 2000); ?>
                                        <label for="apfl_crsl_interval">Interval</label>
                                    </div>

                                    <div class="apfl_col-2">
                                        <select name="apfl_crsl_interval" id="apfl_crsl_interval">
                                            <option value="1000" <?php selected($apfl_crsl_interval, '1000'); ?>>1s</option>
                                            <option value="1500" <?php selected($apfl_crsl_interval, '1500'); ?>>1.5s</option>
                                            <option value="2000" <?php selected($apfl_crsl_interval, '2000'); ?>>2s</option>
                                            <option value="2500" <?php selected($apfl_crsl_interval, '2500'); ?>>2.5s</option>
                                            <option value="3000" <?php selected($apfl_crsl_interval, '3000'); ?>>3s</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="apfl_inner-container">
                                    <div class="apfl_col-1">
                                        <?php $apfl_crsl_scroll_dir = get_option('apfl_crsl_scroll_dir', 'right'); ?>
                                        <label for="apfl_crsl_scroll_dir">Scroll Direction</label>
                                    </div>

                                    <div class="apfl_col-2">
                                        <select name="apfl_crsl_scroll_dir" id="apfl_crsl_scroll_dir">
                                            <option value="left" <?php selected($apfl_crsl_scroll_dir, 'left'); ?>>Left</option>
                                            <option value="right" <?php selected($apfl_crsl_scroll_dir, 'right'); ?>>Right</option>                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="apfl_submit-container">
                        <input type="submit" name="apfl_crsl_sbmt" id="apfl_crsl_sbmt" value="Save" class="apfl_btn">
                    </div>

                </form>
            </div>
        </div>



<?php }
}
