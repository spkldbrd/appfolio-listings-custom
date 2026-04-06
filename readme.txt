=== Appfolio Listings Custom ===
Contributors: spkldbrd
Tags: appfolio, listings, property, real estate, shortcode
Requires at least: 5.8
Tested up to: 6.7
Stable tag: 3.0.2
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display Appfolio property listings on WordPress with shortcodes, templates, slider, and carousel.

== Description ==

Appfolio Listings Custom connects your Appfolio account to WordPress so you can embed listings, filters, maps, and detail pages. Configure your Appfolio URL and optional Google Maps API key in the plugin settings.

**Main shortcode:** `[apfl_listings]`

Place it on a full-width page for best layout. Additional shortcodes are documented in the plugin admin (Slider, Carousel, Listings builder).

**Updates:** The plugin can check a public `version.json` (default: GitHub raw) from the Appfolio settings screen in wp-admin.

== Installation ==

1. Download the release ZIP from GitHub Releases.
2. Upload via **Plugins → Add New → Upload Plugin**, or extract to `wp-content/plugins/appfolio-listings-custom/`.
3. Activate the plugin.
4. Open **Appfolio** in the admin menu and set your Appfolio listings URL and pages.

== Frequently Asked Questions ==

= Where do I get the Appfolio URL? =

Use the public listings URL from your Appfolio marketing site (as configured in Appfolio).

= Is a license key required? =

No. This distribution has no license server or activation gate.

== Changelog ==

= 3.0.2 =
* Paginated full listings: smooth scroll to top of page when changing pages.
* Multiple listings shortcode: `show_heading="no"` hides page heading and subheading.

= 3.0.1 =
* Rebranding and documentation cleanup.
* Admin update notice from public version.json (GitHub).
* No vendor license checks.

= 3.0.0 =
* Initial public release of this fork.

== Upgrade Notice ==

= 3.0.2 =
UX and shortcode options. Upload the new ZIP or pull from Git.

= 3.0.1 =
Maintenance and branding update. Upload the new ZIP or pull from Git.
