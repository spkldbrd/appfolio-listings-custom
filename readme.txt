=== Appfolio Listings Custom ===
Contributors: spkldbrd
Tags: appfolio, listings, property, real estate, shortcode
Requires at least: 5.8
Tested up to: 6.7
Stable tag: 3.0.3
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display Appfolio property listings on WordPress with shortcodes, templates, slider, and carousel.

== Description ==

Appfolio Listings Custom connects your Appfolio account to WordPress so you can embed listings, filters, maps, and detail pages. Configure your Appfolio URL and optional Google Maps API key in the plugin settings.

**Main shortcode:** `[apfl_listings]`

Place it on a full-width page for best layout. Additional shortcodes are documented in the plugin admin (Slider, Carousel, Listings builder).

**Updates:** The plugin reads a public `version.json` (default: GitHub raw). When a newer version is listed, WordPress shows an update on **Plugins** and **Dashboard → Updates** with one-click install, and the Appfolio settings screen can show an **Update now** button. Optional: add `define('AFC_AUTO_UPDATE', true);` in `wp-config.php` to allow background auto-updates for this plugin (you can still use the Plugins screen “Enable auto-updates” link in WP 5.5+).

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

= 3.0.3 =
* Register updates with WordPress: one-click update from Plugins / Dashboard → Updates.
* Appfolio admin notice uses WordPress “Update now” when you have permission.
* Optional `AFC_AUTO_UPDATE` constant for background auto-updates.

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

= 3.0.3 =
In-dashboard one-click updates when version.json reports a newer release.

= 3.0.2 =
UX and shortcode options. Upload the new ZIP or pull from Git.

= 3.0.1 =
Maintenance and branding update. Upload the new ZIP or pull from Git.
