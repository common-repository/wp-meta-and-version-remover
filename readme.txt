=== WP Meta and Version Remover ===
Contributors: iihglobal
Plugin URI: https://wordpress.org/plugins/wp-meta-and-version-remover
Author URI: https://www.iihglobal.com/
Donate link: https://www.iihglobal.com/
Tags: remove, version, generator, security, meta, appended version, css ver, js ver, meta generator, wpml, wpml generator,  wpml generator tag, slider revolution, slider revolution generator tag, page builder, page builder generator, optimized, yoast seo, yoast seo comments, monsterinsights comments, google analytics comments
Requires at least: 4.0
Tested up to: 5.3
Stable tag: trunk
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will remove the version info appended to enqueued style and script URLs along with Meta Generator in the head section and in RSS feeds.

== Description ==

This plugin will remove the version information that gets appended to enqueued style and script URLs. It will also remove the Meta Generator tag in the head and in RSS feeds. Adds a bit of obfuscation to hide the WordPress version number and generator tag that many sniffers detect automatically from view source. Option available to selectively exclude any style or script file from version info removal process.

You can enable/disable each removal options from admin dashboard:
<ul><li>Remove WordPress Meta Generator Tag</li>
<li>Remove WPML (WordPress Multilingual Plugin) Meta Generator Tag</li>
<li>Remove Slider Revolution Meta Generator Tag</li>
<li>Remove WPBakery Page Builder Meta Generator Tag</li>
<li>Remove Version from Stylesheet</li>
<li>Remove Version from Script</li>
<li>Exclude files from version info removal process (by providing comma separated file names)</li>
<li>Remove Yoast SEO comments</li>
<li>Remove Google Analytics (MonsterInsights) comments</li></ul>

You have any suggestions to make this plugin better? Please share your thoughts in the support thread.

Dashboard > Settings > WP Meta and Version Remover


If you want to help with translations please provide localized file in your language. Use the template file (.pot) inside the 'lang' folder, and contact me via Support forum once your localization is ready.

== Installation ==

1. Unzip the zipped file and upload to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Manage individual options from Dashboard > Settings > Meta Generator and Version Info Remover

== Frequently Asked Questions ==

= Can I exclude a script/css file? =

Yes! You can exclude any script/css files by providing the file names in comma separated format.

= Will this plugin cause any problem with WordPress update process? =

Not at all! It will cause no problem with WordPress update process.

= After plugin update do I need to do anything? =

Please go to plugin settings: Dashboard > Settings > Meta Generator and Version Info Remover. Check if all the relevant checkboxes are enabled.

== Screenshots ==

1. Setting page
2. Settings page with a list of files (comma separated list) to exclude from version info removal process
3. View source demo

== Changelog ==

= 1.0 =
* Initial Commit.

== Upgrade Notice ==

N/A.
