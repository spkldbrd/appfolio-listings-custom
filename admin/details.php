<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

// Content Builder
if (!function_exists('apfl_pp_details_builder_callback')) {
    function apfl_pp_details_builder_callback()
    {
        global $apfl_plugin_dir;
        include($apfl_plugin_dir . 'inc/customizer-templates.php');
        if ($_POST) {

            if (isset($_POST['apfl_details_cstmzr_sbmt'])) {

                if (isset($_POST['apfl_single_template'])) {

                    $apfl_single_template = sanitize_text_field($_POST['apfl_single_template']);
                    update_option('apfl_single_template', $apfl_single_template);

                } else {
                    update_option('apfl_single_template', 'classic');
                }

                if (isset($_POST['apfl_details_display_price'])) {
                    $apfl_details_display_price = filter_var($_POST['apfl_details_display_price'], FILTER_UNSAFE_RAW);
                    if ($apfl_details_display_price == 'on') {
                        update_option('apfl_details_display_price', 'show');
                    }
                } else {
                    update_option('apfl_details_display_price', 'hide');
                }

                if (isset($_POST['apfl_details_price_color'])) {
                    $apfl_details_price_color = sanitize_textarea_field($_POST['apfl_details_price_color']);
                    update_option('apfl_details_price_color', $apfl_details_price_color);
                }

                if (isset($_POST['apfl_details_price_mo'])) {
                    $apfl_details_price_mo = sanitize_text_field($_POST['apfl_details_price_mo']);
                    update_option('apfl_details_price_mo', $apfl_details_price_mo);
                }

                if (isset($_POST['apfl_details_display_ttl'])) {
                    $apfl_details_display_ttl = filter_var($_POST['apfl_details_display_ttl'], FILTER_UNSAFE_RAW);
                    if ($apfl_details_display_ttl == 'on') {
                        update_option('apfl_details_display_ttl', 'show');
                    }
                } else {
                    update_option('apfl_details_display_ttl', 'hide');
                }


                if (isset($_POST['apfl_details_ttl_tag'])) {
                    $apfl_details_ttl_tag = sanitize_text_field($_POST['apfl_details_ttl_tag']);
                    update_option('apfl_details_ttl_tag', $apfl_details_ttl_tag);
                }

                if (isset($_POST['apfl_custom_contact_us'])) {
                    $apfl_custom_contact_us = sanitize_text_field($_POST['apfl_custom_contact_us']);
                    update_option('apfl_custom_contact_us', $apfl_custom_contact_us);
                }

                // contact button

                if (isset($_POST['apfl_dtl_listings_display_contact'])) {
                    $apfl_dtl_listings_display_contact = filter_var($_POST['apfl_dtl_listings_display_contact'], FILTER_UNSAFE_RAW);
                    if ($apfl_dtl_listings_display_contact == 'on') {
                        update_option('apfl_dtl_listings_display_contact', 'show');
                    }
                } else {
                    update_option('apfl_dtl_listings_display_contact', 'hide');
                }

                if (isset($_POST['apfl_dtl_listings_contact_color'])) {
                    $apfl_dtl_listings_contact_color = sanitize_text_field($_POST['apfl_dtl_listings_contact_color']);
                    update_option('apfl_dtl_listings_contact_color', $apfl_dtl_listings_contact_color);
                }
                if (isset($_POST['apfl_dtl_listings_contact_bg'])) {
                    $apfl_dtl_listings_contact_bg = sanitize_text_field($_POST['apfl_dtl_listings_contact_bg']);
                    update_option('apfl_dtl_listings_contact_bg', $apfl_dtl_listings_contact_bg);
                }
                if (isset($_POST['apfl_dtl_listings_contact_hover_color'])) {
                    $apfl_dtl_listings_contact_hover_color = sanitize_text_field($_POST['apfl_dtl_listings_contact_hover_color']);
                    update_option('apfl_dtl_listings_contact_hover_color', $apfl_dtl_listings_contact_hover_color);
                }
                if (isset($_POST['apfl_dtl_listings_contact_hover_bg'])) {
                    $apfl_dtl_listings_contact_hover_bg = sanitize_text_field($_POST['apfl_dtl_listings_contact_hover_bg']);
                    update_option('apfl_dtl_listings_contact_hover_bg', $apfl_dtl_listings_contact_hover_bg);
                }


                // Apply button

                if (isset($_POST['apfl_dtl_listings_display_apply'])) {
                    $apfl_dtl_listings_display_apply = filter_var($_POST['apfl_dtl_listings_display_apply'], FILTER_UNSAFE_RAW);
                    if ($apfl_dtl_listings_display_apply == 'on') {
                        update_option('apfl_dtl_listings_display_apply', 'show');
                    }
                } else {
                    update_option('apfl_dtl_listings_display_apply', 'hide');
                }

                if (isset($_POST['apfl_dtl_listings_apply_color'])) {
                    $apfl_dtl_listings_apply_color = sanitize_text_field($_POST['apfl_dtl_listings_apply_color']);
                    update_option('apfl_dtl_listings_apply_color', $apfl_dtl_listings_apply_color);
                }
                if (isset($_POST['apfl_dtl_listings_apply_bg'])) {
                    $apfl_dtl_listings_apply_bg = sanitize_text_field($_POST['apfl_dtl_listings_apply_bg']);
                    update_option('apfl_dtl_listings_apply_bg', $apfl_dtl_listings_apply_bg);
                }
                if (isset($_POST['apfl_dtl_listings_apply_hover_color'])) {
                    $apfl_dtl_listings_apply_hover_color = sanitize_text_field($_POST['apfl_dtl_listings_apply_hover_color']);
                    update_option('apfl_dtl_listings_apply_hover_color', $apfl_dtl_listings_apply_hover_color);
                }
                if (isset($_POST['apfl_dtl_listings_apply_hover_bg'])) {
                    $apfl_dtl_listings_apply_hover_bg = sanitize_text_field($_POST['apfl_dtl_listings_apply_hover_bg']);
                    update_option('apfl_dtl_listings_apply_hover_bg', $apfl_dtl_listings_apply_hover_bg);
                }

                // Schedule button 

                if (isset($_POST['apfl_dtl_listings_display_schedule'])) {
                    $apfl_dtl_listings_display_schedule = filter_var($_POST['apfl_dtl_listings_display_schedule'], FILTER_UNSAFE_RAW);
                    if ($apfl_dtl_listings_display_schedule == 'on') {
                        update_option('apfl_dtl_listings_display_schedule', 'show');
                    }
                } else {
                    update_option('apfl_dtl_listings_display_schedule', 'hide');
                }

                if (isset($_POST['apfl_dtl_listings_schedule_color'])) {
                    $apfl_dtl_listings_schedule_color = sanitize_text_field($_POST['apfl_dtl_listings_schedule_color']);
                    update_option('apfl_dtl_listings_schedule_color', $apfl_dtl_listings_schedule_color);
                }
                if (isset($_POST['apfl_dtl_listings_schedule_bg'])) {
                    $apfl_dtl_listings_schedule_bg = sanitize_text_field($_POST['apfl_dtl_listings_schedule_bg']);
                    update_option('apfl_dtl_listings_schedule_bg', $apfl_dtl_listings_schedule_bg);
                }
                if (isset($_POST['apfl_dtl_listings_schedule_hover_color'])) {
                    $apfl_dtl_listings_schedule_hover_color = sanitize_text_field($_POST['apfl_dtl_listings_schedule_hover_color']);
                    update_option('apfl_dtl_listings_schedule_hover_color', $apfl_dtl_listings_schedule_hover_color);
                }
                if (isset($_POST['apfl_dtl_listings_schedule_hover_bg'])) {
                    $apfl_dtl_listings_schedule_hover_bg = sanitize_text_field($_POST['apfl_dtl_listings_schedule_hover_bg']);
                    update_option('apfl_dtl_listings_schedule_hover_bg', $apfl_dtl_listings_schedule_hover_bg);
                }


                echo '
                    <div class="apfl-notice apfl-success">
                        <span class="apfl-notice-text">Settings saved successfully!</span>
                        <button type="button" class="apfl-notice-close" aria-label="Dismiss notice">×</button>
                    </div>';
            }
        }

?>


        <!-- New Design -->
        <div class="wrap">
            <?php
            $apfl_template = (int) get_option('apfl_template');
            if ($apfl_template) {
                $template_data = get_option('apfl_template_' . $apfl_template . '_data');
            }
            ?>
            <div id="apfl-pro-customizer-details">

                <form method='POST' action="">
                    <div class="apfl_setting-container">

                        <h2 class="apfl_heading">Listing Details Customizer
                            <div class="apfl-sc-hint">Shortcode: 
                                <span id="apfl_copy_carousel_sc">[apfl_single_listing]</span>
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
                                $apfl_single_template = get_option('apfl_single_template') ?: 'classic';
                                ?>
                                <label for="apfl_single_template">Template</label>
                            </div>
                            <div class="apfl_col-2">
                                <label>
                                    <input type="radio" name="apfl_single_template" value="classic" <?php echo ($apfl_single_template == 'classic') ? 'checked' : ''; ?>>
                                    Classic Template
                                </label>
                                <label>
                                    <input type="radio" name="apfl_single_template" value="modern" <?php echo ($apfl_single_template == 'modern') ? 'checked' : ''; ?>>
                                    Modern Template
                                </label>
                            </div>
                        </div>

                        <div class="apfl_inner-container">
                            <div class="apfl_col-1">
                                <label for="apfl_details_display_price">Display Rent Price</label>
                            </div>

                            <div class="apfl_col-2">
                                <?php
                                $apfl_details_display_price = get_option('apfl_details_display_price', 'show');
                                ?>
                                <div class="checkbox-wrap">
                                    <label class="apfl_switch">
                                        <input type="checkbox" name="apfl_details_display_price" id="apfl_details_display_price"
                                            <?php echo ($apfl_details_display_price == 'show') ? 'checked' : ''; ?>>
                                        <span class="apfl_slide_checkbox"></span>
                                    </label>
                                </div>

                                <div class="apfl_conditional-options <?php echo ($apfl_details_display_price == 'show') ? '' : 'apfl_hidden'; ?>">
                                    <div class="apfl_option-row">
                                        <?php
                                        $apfl_details_price_color = get_option('apfl_details_price_color', '#ff6600');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_details_price_color">Text Color:</label>
                                            <input type="text" name="apfl_details_price_color" value="<?php echo $apfl_details_price_color; ?>" class="apfl-details-color" />
                                        </div>

                                        <?php
                                        $apfl_details_price_mo = get_option('apfl_details_price_mo', 'yes');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_details_price_mo">Show /mo:</label>
                                            <select name="apfl_details_price_mo">
                                                <option value="yes" <?php echo ($apfl_details_price_mo === 'yes') ? 'selected' : ''; ?>>Yes</option>
                                                <option value="no" <?php echo ($apfl_details_price_mo === 'no') ? 'selected' : ''; ?>>No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="apfl_inner-container">
                            <div class="apfl_col-1">
                                <label for="apfl_details_display_ttl">Display Details Title</label>
                            </div>

                            <div class="apfl_col-2">
                                <?php
                                $apfl_details_display_ttl = get_option('apfl_details_display_ttl', 'show');
                                ?>
                                <div class="checkbox-wrap">
                                    <label class="apfl_switch">
                                        <input type="checkbox" name="apfl_details_display_ttl" id="apfl_details_display_ttl"
                                            <?php echo ($apfl_details_display_ttl == 'show') ? 'checked' : ''; ?>>
                                        <span class="apfl_slide_checkbox"></span>
                                    </label>
                                </div>

                                <div class="apfl_conditional-options <?php echo ($apfl_details_display_ttl == 'show') ? '' : 'apfl_hidden'; ?>" id="ttl_tag">
                                    <?php
                                    $apfl_details_ttl_tag = get_option('apfl_details_ttl_tag', 'p');
                                    ?>
                                    <div class="apfl_option-field">
                                        <label for="apfl_details_ttl_tag">Tag:</label>
                                        <select name="apfl_details_ttl_tag" id="apfl_details_ttl_tag">
                                            <option value="h1" <?php selected($apfl_details_ttl_tag, 'h1'); ?>>h1</option>
                                            <option value="h2" <?php selected($apfl_details_ttl_tag, 'h2'); ?>>h2</option>
                                            <option value="h3" <?php selected($apfl_details_ttl_tag, 'h3'); ?>>h3</option>
                                            <option value="h4" <?php selected($apfl_details_ttl_tag, 'h4'); ?>>h4</option>
                                            <option value="h5" <?php selected($apfl_details_ttl_tag, 'h5'); ?>>h5</option>
                                            <option value="h6" <?php selected($apfl_details_ttl_tag, 'h6'); ?>>h6</option>
                                            <option value="p" <?php selected($apfl_details_ttl_tag, 'p');  ?>>p</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="apfl_inner-container">
                            <div class="apfl_col-1">
                                <label for="apfl_dtl_listings_display_contact">Display Contact Button</label>
                            </div>

                            <div class="apfl_col-2">
                                <?php
                                $apfl_dtl_listings_display_contact = get_option('apfl_dtl_listings_display_contact', 'show');
                                ?>
                                <div class="checkbox-wrap">
                                    <label class="apfl_switch">
                                        <input type="checkbox" name="apfl_dtl_listings_display_contact" id="apfl_dtl_listings_display_contact"
                                            <?php echo ($apfl_dtl_listings_display_contact == 'show') ? 'checked' : ''; ?>>
                                        <span class="apfl_slide_checkbox"></span>
                                    </label>
                                </div>

                                <div class="apfl_conditional-options <?php echo ($apfl_dtl_listings_display_contact == 'show') ? '' : 'apfl_hidden'; ?>">
                                    <div class="apfl_option-row">
                                        <?php
                                        $apfl_dtl_listings_contact_color = get_option('apfl_dtl_listings_contact_color', '#ffffff');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_dtl_listings_contact_color">Text Color:</label>
                                            <input type="text" name="apfl_dtl_listings_contact_color" value="<?php echo $apfl_dtl_listings_contact_color; ?>" class="apfl-details-color" />
                                        </div>

                                        <?php
                                        $apfl_dtl_listings_contact_bg = get_option('apfl_dtl_listings_contact_bg', '#27547c');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_dtl_listings_contact_bg">Background:</label>
                                            <input type="text" name="apfl_dtl_listings_contact_bg" value="<?php echo $apfl_dtl_listings_contact_bg; ?>" class="apfl-details-color" />
                                        </div>

                                        <?php
                                        $apfl_dtl_listings_contact_hover_color = get_option('apfl_dtl_listings_contact_hover_color', '#ffffff');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_dtl_listings_contact_hover_color">Hover Text Color:</label>
                                            <input type="text" name="apfl_dtl_listings_contact_hover_color" value="<?php echo $apfl_dtl_listings_contact_hover_color; ?>" class="apfl-details-color" />
                                        </div>

                                        <?php
                                        $apfl_dtl_listings_contact_hover_bg = get_option('apfl_dtl_listings_contact_hover_bg', '#444444');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_dtl_listings_contact_hover_bg">Hover Background:</label>
                                            <input type="text" name="apfl_dtl_listings_contact_hover_bg" value="<?php echo $apfl_dtl_listings_contact_hover_bg; ?>" class="apfl-details-color" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="apfl_inner-container">
                            <div class="apfl_col-1">
                                <label for="apfl_dtl_listings_display_apply">Display Apply Button</label>
                            </div>

                            <div class="apfl_col-2">
                                <?php
                                $apfl_dtl_listings_display_apply = get_option('apfl_dtl_listings_display_apply', 'show');
                                ?>
                                <div class="checkbox-wrap">
                                    <label class="apfl_switch">
                                        <input type="checkbox" name="apfl_dtl_listings_display_apply" id="apfl_dtl_listings_display_apply"
                                            <?php echo ($apfl_dtl_listings_display_apply == 'show') ? 'checked' : ''; ?>>
                                        <span class="apfl_slide_checkbox"></span>
                                    </label>
                                </div>

                                <div class="apfl_conditional-options <?php echo ($apfl_dtl_listings_display_apply == 'show') ? '' : 'apfl_hidden'; ?>">
                                    <div class="apfl_option-row">
                                        <?php
                                        $apfl_dtl_listings_apply_color = get_option('apfl_dtl_listings_apply_color', '#ffffff');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_dtl_listings_apply_color">Text Color:</label>
                                            <input type="text" name="apfl_dtl_listings_apply_color" value="<?php echo $apfl_dtl_listings_apply_color; ?>" class="apfl-details-color" />
                                        </div>

                                        <?php
                                        $apfl_dtl_listings_apply_bg = get_option('apfl_dtl_listings_apply_bg', '#27547c');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_dtl_listings_apply_bg">Background:</label>
                                            <input type="text" name="apfl_dtl_listings_apply_bg" value="<?php echo $apfl_dtl_listings_apply_bg; ?>" class="apfl-details-color" />
                                        </div>

                                        <?php
                                        $apfl_dtl_listings_apply_hover_color = get_option('apfl_dtl_listings_apply_hover_color', '#ffffff');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_dtl_listings_apply_hover_color">Hover Text Color:</label>
                                            <input type="text" name="apfl_dtl_listings_apply_hover_color" value="<?php echo $apfl_dtl_listings_apply_hover_color; ?>" class="apfl-details-color" />
                                        </div>

                                        <?php
                                        $apfl_dtl_listings_apply_hover_bg = get_option('apfl_dtl_listings_apply_hover_bg', '#444444');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_dtl_listings_apply_hover_bg">Hover Background:</label>
                                            <input type="text" name="apfl_dtl_listings_apply_hover_bg" value="<?php echo $apfl_dtl_listings_apply_hover_bg; ?>" class="apfl-details-color" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="apfl_inner-container">
                            <div class="apfl_col-1">
                                <label for="apfl_dtl_listings_display_schedule">Display Schedule Button</label>
                            </div>

                            <div class="apfl_col-2">
                                <?php
                                $apfl_dtl_listings_display_schedule = get_option('apfl_dtl_listings_display_schedule', 'show');
                                ?>
                                <div class="checkbox-wrap">
                                    <label class="apfl_switch">
                                        <input type="checkbox" name="apfl_dtl_listings_display_schedule" id="apfl_dtl_listings_display_schedule"
                                            <?php echo ($apfl_dtl_listings_display_schedule == 'show') ? 'checked' : ''; ?>>
                                        <span class="apfl_slide_checkbox"></span>
                                    </label>
                                </div>

                                <div class="apfl_conditional-options <?php echo ($apfl_dtl_listings_display_schedule == 'show') ? '' : 'apfl_hidden'; ?>">
                                    <div class="apfl_option-row">
                                        <?php
                                        $apfl_dtl_listings_schedule_color = get_option('apfl_dtl_listings_schedule_color', '#ffffff');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_dtl_listings_schedule_color">Text Color:</label>
                                            <input type="text" name="apfl_dtl_listings_schedule_color" value="<?php echo $apfl_dtl_listings_schedule_color; ?>" class="apfl-details-color" />
                                        </div>

                                        <?php
                                        $apfl_dtl_listings_schedule_bg = get_option('apfl_dtl_listings_schedule_bg', '#27547c');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_dtl_listings_schedule_bg">Background:</label>
                                            <input type="text" name="apfl_dtl_listings_schedule_bg" value="<?php echo $apfl_dtl_listings_schedule_bg; ?>" class="apfl-details-color" />
                                        </div>

                                        <?php
                                        $apfl_dtl_listings_schedule_hover_color = get_option('apfl_dtl_listings_schedule_hover_color', '#ffffff');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_dtl_listings_schedule_hover_color">Hover Text Color:</label>
                                            <input type="text" name="apfl_dtl_listings_schedule_hover_color" value="<?php echo $apfl_dtl_listings_schedule_hover_color; ?>" class="apfl-details-color" />
                                        </div>

                                        <?php
                                        $apfl_dtl_listings_schedule_hover_bg = get_option('apfl_dtl_listings_schedule_hover_bg', '#444444');
                                        ?>
                                        <div class="apfl_option-field">
                                            <label for="apfl_dtl_listings_schedule_hover_bg">Hover Background:</label>
                                            <input type="text" name="apfl_dtl_listings_schedule_hover_bg" value="<?php echo $apfl_dtl_listings_schedule_hover_bg; ?>" class="apfl-details-color" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="apfl_inner-container">
                            <div class="apfl_col-1">
                                <?php
                                $apfl_custom_contact_us = get_option('apfl_custom_contact_us');
                                ?>
                                <label for="apfl_custom_contact_us">Custom Contact Us Link<br>(Leave blank for default link)</label>
                            </div>
                            <div class="apfl_col-2">
                                <input type="text" name="apfl_custom_contact_us" id="apfl_custom_contact_us"
                                    class="apfl_input" placeholder="please use complete URL including http or https"
                                    value="<?php echo $apfl_custom_contact_us; ?>">
                            </div>
                        </div>

                    </div>

                    <!-- Save Button -->
                    <div class="apfl_submit-container">
                        <input type="submit" name="apfl_details_cstmzr_sbmt" value="Save" class="apfl_btn">
                    </div>

                </form>
            </div>
        </div>



<?php
    }
}
