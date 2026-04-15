<?php
/**
 * Admin reference: shortcodes and attributes.
 *
 * @package Appfolio_Listings_Custom
 */

if (! defined('ABSPATH')) {
	exit;
}

if (! function_exists('apfl_pp_shortcodes_reference_callback')) {

	function apfl_pp_shortcodes_reference_callback() {
		if (! current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		$sections = array(
			array(
				'tag'     => 'apfl_listings',
				'title'   => __('Main listings grid', 'appfolio-listings-custom'),
				'desc'    => __('Shows listings from the Appfolio URL in plugin settings (or override with url). Use on a full-width page for best layout. First-load filters (type, city, default sort) are sent as a normal query string so Appfolio can apply them.', 'appfolio-listings-custom'),
				'example' => '[apfl_listings columns="4" limit="4" map="hide" filters="hide" show_heading="no"]',
				'rows'    => array(
					array('url', __('Appfolio site base URL', 'appfolio-listings-custom'), __('Optional. Overrides the global Appfolio URL for this instance only.', 'appfolio-listings-custom')),
					array('single_url', __('Path or URL', 'appfolio-listings-custom'), __('If set, renders a single listing from this URL instead of the grid.', 'appfolio-listings-custom')),
					array('map', __('hide or (empty)', 'appfolio-listings-custom'), __('Use map="hide" to omit the Google Map (still requires a Map API key in settings for the default map).', 'appfolio-listings-custom')),
					array('limit', __('integer', 'appfolio-listings-custom'), __('Maximum number of listings to output.', 'appfolio-listings-custom')),
					array('columns', __('1–5', 'appfolio-listings-custom'), __('Grid column count. Overrides the default from All Listings template settings.', 'appfolio-listings-custom')),
					array('filters', __('hide or (empty)', 'appfolio-listings-custom'), __('Use filters="hide" to hide the search/filter bar.', 'appfolio-listings-custom')),
					array('type', __('string', 'appfolio-listings-custom'), __('Property list filter passed to Appfolio (e.g. Residential).', 'appfolio-listings-custom')),
					array('types', __('comma-separated', 'appfolio-listings-custom'), __('Adds a property-type dropdown in the filter form when filters are shown.', 'appfolio-listings-custom')),
					array('show_heading', __('yes (default) or no', 'appfolio-listings-custom'), __('Use show_heading="no" (or 0, false, off, hide) to hide the listings page heading and subheading from settings.', 'appfolio-listings-custom')),
					array('city', __('one or comma-separated', 'appfolio-listings-custom'), __('Pre-filters by Appfolio city on first load (same as City dropdown). Only when the visitor has not submitted the filter form. Values must match Appfolio option text exactly (e.g. Templeton).', 'appfolio-listings-custom')),
					array('empty_message', __('HTML or text', 'appfolio-listings-custom'), __('Optional. When the grid has no listing items, show this instead of the default no-results message. Basic HTML is allowed (same rules as post content). Ignored when listings are found. Developers can override the final HTML with the apfl_listings_no_results_html filter.', 'appfolio-listings-custom')),
				),
			),
			array(
				'tag'     => 'apfl_listings_multiple',
				'title'   => __('Multiple Appfolio URLs', 'appfolio-listings-custom'),
				'desc'    => __('Combines listings from more than one Appfolio marketing site. Not the same as apfl_listings—requires url with one or more comma-separated listing page URLs. First-load filters use the same query-string format as the main shortcode.', 'appfolio-listings-custom'),
				'example' => '[apfl_listings_multiple url="https://a.appfolio.com/listings,https://b.appfolio.com/listings" columns="3" show_heading="no"]',
				'rows'    => array(
					array('url', __('comma-separated URLs', 'appfolio-listings-custom'), __('Required for multi-site use. Each URL should be the public /listings page for an Appfolio site.', 'appfolio-listings-custom')),
					array('map', __('hide or (empty)', 'appfolio-listings-custom'), __('Same as main shortcode.', 'appfolio-listings-custom')),
					array('type', __('string', 'appfolio-listings-custom'), __('Property list filter.', 'appfolio-listings-custom')),
					array('columns', __('1–5', 'appfolio-listings-custom'), __('Grid columns; falls back to template/global if omitted.', 'appfolio-listings-custom')),
					array('limit', __('integer', 'appfolio-listings-custom'), __('Cap total listings shown.', 'appfolio-listings-custom')),
					array('class', __('CSS classes', 'appfolio-listings-custom'), __('Extra space-separated classes on the wrapper (sanitized).', 'appfolio-listings-custom')),
					array('show_heading', __('yes (default) or no', 'appfolio-listings-custom'), __('Hide page heading and subheading when set to no / 0 / false / off / hide.', 'appfolio-listings-custom')),
					array('city', __('one or comma-separated', 'appfolio-listings-custom'), __('Same as main shortcode: pre-filter cities when the filter form was not submitted. With multiple comma-separated cities, the fetch uses all; internal city matching uses the first name only when a single city is set.', 'appfolio-listings-custom')),
				),
			),
			array(
				'tag'     => 'apfl_single_listing',
				'title'   => __('Single listing detail', 'appfolio-listings-custom'),
				'desc'    => __('Place on the page used for listing details. The visitor opens a listing with ?lid=… (and optional &url= for a custom Appfolio base). No shortcode attributes.', 'appfolio-listings-custom'),
				'example' => '[apfl_single_listing]',
				'rows'    => array(),
			),
			array(
				'tag'     => 'apfl_slider',
				'title'   => __('Listing slider', 'appfolio-listings-custom'),
				'desc'    => __('Loads a slider of listings; options are configured under the Slider admin tab. No shortcode attributes in code.', 'appfolio-listings-custom'),
				'example' => '[apfl_slider]',
				'rows'    => array(),
			),
			array(
				'tag'     => 'apfl_carousel',
				'title'   => __('Listing carousel', 'appfolio-listings-custom'),
				'desc'    => __('Carousel widget; defaults come from the Carousel admin tab.', 'appfolio-listings-custom'),
				'example' => '[apfl_carousel template="modern" count="6" class="my-carousel" id="featured"]',
				'rows'    => array(
					array('template', __('classic or modern', 'appfolio-listings-custom'), __('Overrides the carousel template choice from settings.', 'appfolio-listings-custom')),
					array('count', __('integer ≥ 1', 'appfolio-listings-custom'), __('Number of slides to show.', 'appfolio-listings-custom')),
					array('class', __('CSS classes', 'appfolio-listings-custom'), __('Extra classes on the wrapper.', 'appfolio-listings-custom')),
					array('id', __('string', 'appfolio-listings-custom'), __('HTML id on the wrapper (sanitized as a slug).', 'appfolio-listings-custom')),
				),
			),
			array(
				'tag'     => 'apfl_search_frm',
				'title'   => __('Standalone search form', 'appfolio-listings-custom'),
				'desc'    => __('Renders filter fields as a form that POSTs to another page (typically your full listings page).', 'appfolio-listings-custom'),
				'example' => '[apfl_search_frm action="' . esc_url(home_url('/listings/')) . '"]',
				'rows'    => array(
					array('action', __('URL', 'appfolio-listings-custom'), __('Required target URL for form submission (page with [apfl_listings]).', 'appfolio-listings-custom')),
				),
			),
		);

		?>
		<div class="wrap">
			<div class="apfl_setting-container">
				<h2 class="apfl_heading"><?php echo esc_html__('Shortcodes', 'appfolio-listings-custom'); ?></h2>
				<p class="description" style="margin: 0 0 1.5em;">
					<?php echo esc_html__('Copy these into pages or blocks. Attribute names are lowercase. Unknown attributes are ignored.', 'appfolio-listings-custom'); ?>
				</p>

				<?php foreach ($sections as $block) : ?>
					<div class="apfl_inner-container apfl-shortcode-section" style="display:block;margin-bottom:2em;padding-bottom:1.5em;border-bottom:1px solid #c3c4c7;">
						<h3 style="margin-top:0;">
							<code>[<?php echo esc_html($block['tag']); ?>]</code>
							— <?php echo esc_html($block['title']); ?>
						</h3>
						<p class="description"><?php echo esc_html($block['desc']); ?></p>
						<p>
							<strong><?php echo esc_html__('Example', 'appfolio-listings-custom'); ?>:</strong>
							<code style="display:inline-block;margin-top:4px;padding:6px 10px;background:#f6f7f7;border:1px solid #c3c4c7;border-radius:4px;white-space:pre-wrap;word-break:break-word;"><?php echo esc_html($block['example']); ?></code>
						</p>
						<?php if (! empty($block['rows'])) : ?>
							<table class="widefat striped" style="max-width:920px;">
								<thead>
									<tr>
										<th scope="col" style="width:22%;"><?php echo esc_html__('Attribute', 'appfolio-listings-custom'); ?></th>
										<th scope="col" style="width:22%;"><?php echo esc_html__('Values', 'appfolio-listings-custom'); ?></th>
										<th scope="col"><?php echo esc_html__('Description', 'appfolio-listings-custom'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($block['rows'] as $row) : ?>
										<tr>
											<td><code><?php echo esc_html($row[0]); ?></code></td>
											<td><?php echo esc_html($row[1]); ?></td>
											<td><?php echo esc_html($row[2]); ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php else : ?>
							<p class="description"><?php echo esc_html__('No attributes.', 'appfolio-listings-custom'); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
