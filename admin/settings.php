<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

if (!function_exists('apfl_pp_config_callback')) {
    function apfl_pp_config_callback()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $plugin_version = defined('APFL_PRO_CURR_VER') ? APFL_PRO_CURR_VER : 'Unknown';
		
		global $apfl_plugin_url;

?>
        <div class="wrap">
            <div class="apfl_setting-container apfl_banner">
                <div class="apfl_inner-container">
                    <div class="apfl_banner_col-1">
                        <span class="dashicons dashicons-admin-multisite" style="font-size:40px;width:48px;height:48px;line-height:48px;color:#2271b1;" aria-hidden="true"></span>
                        <span><strong><?php echo esc_html__('Appfolio Listings Custom', 'appfolio-listings-custom'); ?></strong> <?php echo esc_html($plugin_version); ?></span>
                    </div>
                    <div class="apfl_col-2">
                        <a class="apfl_btn" target="_blank" rel="noopener noreferrer" href="https://github.com/spkldbrd/appfolio-listings-custom/releases"><?php echo esc_html__('Releases', 'appfolio-listings-custom'); ?></a>
                        <a class="apfl_btn" target="_blank" rel="noopener noreferrer" href="https://github.com/spkldbrd/appfolio-listings-custom"><?php echo esc_html__('Source', 'appfolio-listings-custom'); ?></a>
                    </div>
                </div>
            </div>
        </div>


        <?php
            $active_menu = 'settings';
            if (isset($_GET['tab']) && $_GET['tab']) {
                $active_menu = sanitize_text_field($_GET['tab']);
            }
        ?>

        <!-- Tabs Navbar HTML -->
        <div class="wrap">
            <nav class="apfl_tab-container">
                <a href="<?php echo get_admin_url(); ?>admin.php?page=apfl-pp&tab=settings" class="tabs <?php echo ($active_menu == 'settings' ? 'apfl_active' : ''); ?>">
                    <svg class="apfl-tab-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"/></svg>
                    Settings</a>
                <a href="<?php echo get_admin_url(); ?>admin.php?page=apfl-pp&tab=listings_page"
                    class="tabs <?php echo ($active_menu == 'listings_page' ? 'apfl_active' : ''); ?>">
                    <svg class="apfl-tab-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M480 48c0-26.5-21.5-48-48-48L336 0c-26.5 0-48 21.5-48 48l0 48-64 0 0-72c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 72-64 0 0-72c0-13.3-10.7-24-24-24S64 10.7 64 24l0 72L48 96C21.5 96 0 117.5 0 144l0 96L0 464c0 26.5 21.5 48 48 48l256 0 32 0 96 0 160 0c26.5 0 48-21.5 48-48l0-224c0-26.5-21.5-48-48-48l-112 0 0-144zm96 320l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16zM240 416l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16zM128 400c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 32zM560 256c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16l32 0zM256 176l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16zM112 160c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16l32 0zM256 304c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 32zM112 320l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16zm304-48l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16zM400 64c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16l32 0zm16 112l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16z"/></svg>
                    All Listings Page</a>
                <a href="<?php echo get_admin_url(); ?>admin.php?page=apfl-pp&tab=details_page"
                    class="tabs <?php echo ($active_menu == 'details_page' ? 'apfl_active' : ''); ?>">
                    <svg class="apfl-tab-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M48 0C21.5 0 0 21.5 0 48L0 464c0 26.5 21.5 48 48 48l96 0 0-80c0-26.5 21.5-48 48-48s48 21.5 48 48l0 80 96 0c26.5 0 48-21.5 48-48l0-416c0-26.5-21.5-48-48-48L48 0zM64 240c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32zm112-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16zm80 16c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32zM80 96l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16zm80 16c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32zM272 96l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16z"/></svg>
                    Single Listing Page</a>
                <a href="<?php echo get_admin_url(); ?>admin.php?page=apfl-pp&tab=slider" class="tabs <?php echo ($active_menu == 'slider' ? 'apfl_active' : ''); ?>">
                    <svg class="apfl-tab-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" xml:space="preserve"><path d="M29,2H3C1.3,2,0,3.3,0,5v16c0,1.7,1.3,3,3,3h26c1.7,0,3-1.3,3-3V5C32,3.3,30.7,2,29,2z M7.7,14.3c0.4,0.4,0.4,1,0,1.4
                    C7.5,15.9,7.3,16,7,16s-0.5-0.1-0.7-0.3l-2-2c-0.4-0.4-0.4-1,0-1.4l2-2c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4L6.4,13L7.7,14.3z
                    M27.7,13.7l-2,2C25.5,15.9,25.3,16,25,16s-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l1.3-1.3l-1.3-1.3c-0.4-0.4-0.4-1,0-1.4
                    s1-0.4,1.4,0l2,2C28.1,12.7,28.1,13.3,27.7,13.7z"/><circle cx="16" cy="28" r="2"/><circle cx="10" cy="28" r="2"/><circle cx="22" cy="28" r="2"/></svg>
                    </i> Slider</a>
                <a href="<?php echo get_admin_url(); ?>admin.php?page=apfl-pp&tab=carousel" class="tabs <?php echo ($active_menu == 'carousel' ? 'apfl_active' : ''); ?>">
                    <svg class="apfl-tab-icon apfl-tab-wide-icon" xmlns="http://www.w3.org/2000/svg"  viewBox="0 10 100 80"><g i:extraneous="self"><g><path d="M89.6,19.9h-3.4c-0.8-3.5-3.9-6.1-7.7-6.1H21.4c-3.7,0-6.9,2.6-7.7,6.1h-3.4c-4.3,0-7.9,3.5-7.9,7.9v30.6     c0,4.3,3.5,7.9,7.9,7.9h3.4c0.8,3.5,3.9,6.1,7.7,6.1h57.1c3.7,0,6.9-2.6,7.7-6.1h3.4c4.3,0,7.9-3.5,7.9-7.9V27.7     C97.5,23.4,94,19.9,89.6,19.9z M74.1,31.5c0,2.9-2.4,5.3-5.3,5.3s-5.3-2.4-5.3-5.3c0-3,2.4-5.3,5.3-5.3S74.1,28.6,74.1,31.5z      M8.1,58.4V27.7c0-1.3,1-2.3,2.3-2.3h3.2v35.2h-3.2C9.1,60.6,8.1,59.6,8.1,58.4z M78.6,66.8H21.4c-1.3,0-2.3-1-2.3-2.3v-3.5     l18.3-21c1.9-2.2,5.4-2.2,7.3,0l11.6,13.3c0.4,0.4,1,0.4,1.4,0l4.5-5c1.8-1.9,4.8-1.9,6.6,0l11.9,13.1v3     C80.8,65.8,79.8,66.8,78.6,66.8z M91.9,58.4c0,1.3-1,2.3-2.3,2.3h-3.2V25.5h3.2c1.3,0,2.3,1,2.3,2.3V58.4z"/><circle cx="26.9" cy="81.7" r="4.6"/><circle cx="42.3" cy="81.7" r="4.6"/><circle cx="57.7" cy="81.7" r="4.6"/><circle cx="73.1" cy="81.7" r="4.6"/></g></g></svg>
                    Carousel</a>
            </nav>
        </div>

        <?php

            if ($active_menu == 'slider') {
                include(plugin_dir_path(__FILE__) . 'slider.php');
                apfl_pp_slider_callback();
                return;
            } elseif ($active_menu == 'carousel') {
                include(plugin_dir_path(__FILE__) . 'carousel.php');
                apfl_pp_carousel_callback();
                return;
            } elseif ($active_menu == 'details_page') {
                include(plugin_dir_path(__FILE__) . 'details.php');
                apfl_pp_details_builder_callback();
                return;
            } elseif ($active_menu == 'listings_page') {
                include(plugin_dir_path(__FILE__) . 'listings.php');
                apfl_pp_listings_builder_callback();
                return;
            }

            if ($_POST) {
                if (isset($_POST['apfl_config_submit'])) {

                    if (isset($_POST['apfl_config_url'])) {
                        $apfl_url = sanitize_text_field($_POST['apfl_config_url']);
                        $apfl_url_updated = update_option('apfl_url', $apfl_url);
                    }
                    if (isset($_POST['apfl_config_gmap_api'])) {
                        $apfl_gmap_api = sanitize_text_field($_POST['apfl_config_gmap_api']);
                        $apfl_gmap_api_updated = update_option('apfl_gmap_api', $apfl_gmap_api);
                    }

                    if (isset($_POST['apfl_all_lstngs_page'])) {
                        $apfl_all_lstngs_page = sanitize_text_field($_POST['apfl_all_lstngs_page']);
                        update_option('apfl_all_lstngs_page', $apfl_all_lstngs_page);
                    }

                    if (isset($_POST['apfl_sngl_lstngs_page'])) {
                        $apfl_sngl_lstngs_page = sanitize_text_field($_POST['apfl_sngl_lstngs_page']);
                        update_option('apfl_sngl_lstngs_page', $apfl_sngl_lstngs_page);
                    }

                    // Saved message
                    echo '
                    <div class="apfl-notice apfl-success">
                        <span class="apfl-notice-text">Settings saved successfully!</span>
                        <button type="button" class="apfl-notice-close" aria-label="Dismiss notice">×</button>
                    </div>';

                }
            }

            // Backward compatibility - If new listings page is not set
            $apfl_new_lstngs_page = get_option('apfl_all_lstngs_page');
            if (!$apfl_new_lstngs_page) {
                // Checking for old values
                $apfl_slider_lst_page = get_option('apfl_slider_lst_page');
                if ($apfl_slider_lst_page) {
                    update_option('apfl_all_lstngs_page', $apfl_slider_lst_page);
                } else {
                    $apfl_crsl_lst_page = get_option('apfl_crsl_lst_page');
                    if ($apfl_crsl_lst_page) {
                        update_option('apfl_all_lstngs_page', $apfl_crsl_lst_page);
                    }
                }
                delete_option('apfl_slider_lst_page');
                delete_option('apfl_crsl_lst_page');
            }
        ?>

            <div class="wrap">
                <div id="apfl_pro_settings">
                    <form method="POST" action="">

                        <div class="apfl_setting-container">
                            <h2 class="apfl_heading"><?php echo esc_html__('Appfolio Listings settings', 'appfolio-listings-custom'); ?></h2>

                            <div class="apfl_inner-container">
                                <div class="apfl_col-1">
                                    <?php $apfl_listing_url = get_option('apfl_url'); ?>
                                    <label for="apfl_config_url">* Appfolio URL to fetch listings:</label>
                                </div>
                                <div class="apfl_col-2">
                                    <input type="text" name="apfl_config_url" id="apfl_config_url" class="apfl_input"
                                        placeholder="For Example - https://example.appfolio.com"
                                        value="<?php echo esc_attr($apfl_listing_url); ?>">
                                </div>
                            </div>


                            <div class="apfl_inner-container">
                                <div class="apfl_col-1">
                                    <?php $apfl_gmap_api = get_option('apfl_gmap_api'); ?>
                                    <label for="apfl_config_gmap_api">Google Map JS API Key</label>
                                </div>
                                <div class="apfl_col-2">
                                    <input type="text" name="apfl_config_gmap_api" id="apfl_config_gmap_api" class="apfl_input"
                                        placeholder="Leave Blank to disable Google Map"
                                        value="<?php echo esc_attr($apfl_gmap_api); ?>">
                                </div>
                            </div>


                            <div class="apfl_inner-container">
                                <div class="apfl_col-1">
                                    <?php $apfl_all_lstngs_page = get_option('apfl_all_lstngs_page'); ?>
                                    <label for="apfl_all_lstngs_page">* URL with all listings</label>

                                    <small>(Leave blank for default page)</small>

                                    <span class="apfl_tooltip apfl_popup_trigger">
                                        <span class="dashicons dashicons-info"></span>
                                    </span>

                                    <!-- Reusable Popup -->
                                    <div class="apfl_popup_overlay"></div>
                                    <div class="apfl_popup_box">
                                        <div class="apfl_popup_header">
                                            <span class="apfl_popup_close">&times;</span>
                                        </div>
                                        <div class="apfl_popup_body">
                                            <h3>Enter URL for [apfl_listings]</h3>
                                            <p>Enter your website page URL that has the <code>[apfl_listings]</code> shortcode.</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="apfl_col-2">
                                    <input type="text" name="apfl_all_lstngs_page" id="apfl_all_lstngs_page" class="apfl_input"
                                        placeholder="e.g. <?php echo site_url(); ?>/all-listings/"
                                        value="<?php echo esc_attr($apfl_all_lstngs_page); ?>">

                                </div>
                            </div>


                            <div class="apfl_inner-container">
                                <div class="apfl_col-1">
                                    <?php $apfl_sngl_lstngs_page = get_option('apfl_sngl_lstngs_page'); ?>
                                    <label for="apfl_sngl_lstngs_page">URL with single listing shortcode</label>
                                    <small>(Leave blank for default page)</small>

                                    <span class="apfl_tooltip apfl_popup_trigger">
                                        <span class="dashicons dashicons-info"></span>
                                    </span>

                                    <!-- Reusable Popup -->
                                    <div class="apfl_popup_overlay"></div>
                                    <div class="apfl_popup_box">
                                        <div class="apfl_popup_header">
                                            <span class="apfl_popup_close">&times;</span>
                                        </div>
                                        <div class="apfl_popup_body">
                                            <h3>Enter URL for [apfl_listings</h3>
                                            <p>Enter your website page URL that has
                                                <code>[apfl_single_listing]</code> shortcode.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="apfl_col-2">
                                    <input type="text" name="apfl_sngl_lstngs_page" id="apfl_sngl_lstngs_page" class="apfl_input"
                                        placeholder="e.g. <?php echo site_url(); ?>/listing-details/"
                                        value="<?php echo esc_attr($apfl_sngl_lstngs_page); ?>">

                                </div>
                            </div>


                        </div>

                        <div class="apfl_submit-container">
                            <input type="submit" name="apfl_config_submit" id="apfl_config_submit"
                                class="apfl_btn" value="Save" />
                        </div>
                    </form>
                </div>
            </div>



        <?php
    }
}
