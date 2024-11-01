<?php
/**
 * Plugin Name: WP Meta and Version Remover
 * Plugin URI: https://wordpress.org/plugins/wp-wp-wp-meta-and-version-remover/
 * Text Domain: wp-wp-meta-and-version-remover
 * Version: 1.0.0
 * Requires at least: 5.0
 * Author: IIH Global
 * Author URI: https://www.iihglobal.com/
 * Description: This plugin will remove the version information that gets appended to enqueued style and script URLs. It will also remove the Meta Generator in the head and in RSS feeds. Adds a bit of obfuscation to hide the WordPress version number and generator tag that many sniffers detect automatically from view source. But always remember to keep your WordPress updated.
 *Tags: remove, version, generator, security, meta, appended version, css ver, js ver, meta generator, wpml, wpml generator,  wpml generator tag, slider revolution, slider revolution generator tag, page builder, page builder generator, optimized, yoast seo, yoast seo comments, monsterinsights comments, google analytics comments
 *
 * License: GPLv2 or later.
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @author    IIH Global <info@iihglobal.com>
 * @license   GPLv2 or later
 * @package   Meta and Version Remover
 */
/**
 * If this file is called directly, abort.
 **/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


if (!defined('MAVR_PLUGIN_NAME'))
    define('MAVR_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('MAVR_PLUGIN_DIR'))
    define('MAVR_PLUGIN_DIR', plugin_dir_path(__FILE__));

if (!defined('MAVR_PLUGIN_URL'))
    define('MAVR_PLUGIN_URL', plugin_dir_url(__FILE__));

if (!defined('MAVR_VERSION'))
    define('MAVR_VERSION', '1.0.0');

add_action('admin_enqueue_scripts', 'mavr_add_stylesheet_to_admin');
if (!function_exists('mavr_add_stylesheet_to_admin')) {
    function mavr_add_stylesheet_to_admin(){
        wp_register_style('mavr_style', MAVR_PLUGIN_URL . 'css/mavr_style.css', false, MAVR_VERSION);
        wp_enqueue_style('mavr_style');
    }
}

class Meta_and_version_remover {
    public $options;
    public function __construct() {
        $this->options = get_option('meta_and_version_remover_options');
        $this->iih_register_settings_and_fields();
    }

    public function iih_add_menu_page() {
        add_options_page(__('Meta and Version Remover', 'wp-meta-and-version-remover'), __('Meta and Version Remover', 'wp-meta-and-version-remover'), 'administrator', __FILE__, array('Meta_and_version_remover','iih_display_options_page'));
    }

    public static function iih_display_options_page() {
        ?>
        <div class="wrap">
            <?php if ( function_exists('screen_icon') ) { screen_icon(); } ?>
            <h2 class="top_label"><?php _e('WP Meta and Version Remover Settings', 'wp-meta-and-version-remover'); ?><em>Created By - IIH Global</em></h2>
            <form method="post" action="options.php" id="mavr_form">
                <?php 
                    settings_fields('meta_and_version_remover_options');
                    do_settings_sections(__FILE__);
                ?>
                <p class="submit">
                    <input name="submit" type="submit" class="button-primary" value="<?php _e('Save changes', 'wp-meta-and-version-remover'); ?>" />
                </p>
            </form>
        </div>
        <?php
    }

    public function iih_register_settings_and_fields() {
        register_setting('meta_and_version_remover_options', 'meta_and_version_remover_options');
        add_settings_section('iih_meta_generator_remover_section', __('Meta Remover Settings', 'wp-meta-and-version-remover'), array($this, 'iih_meta_and_version_remover_callback'), __FILE__);
        add_settings_field('iih_meta_generator_remover_enable_checkbox', __('Remove WordPress default meta generator tag', 'wp-meta-and-version-remover'), array($this, 'iih_meta_generator_remover_checkbox_setting'), __FILE__, 'iih_meta_generator_remover_section');
        add_settings_field('iih_wpml_generator_remover_enable_checkbox', __('Remove WPML generator tag (Applicable if WordPress Multilingual Plugin is used)', 'wp-meta-and-version-remover'), array($this, 'iih_wpml_generator_remover_checkbox_setting'), __FILE__, 'iih_meta_generator_remover_section');
        add_settings_field('iih_revslider_generator_remover_enable_checkbox', __('Remove Slider Revolution generator tag (Applicable if Slider Revolution Plugin is used)', 'wp-meta-and-version-remover'), array($this, 'iih_revslider_generator_remover_checkbox_setting'), __FILE__, 'iih_meta_generator_remover_section');
        add_settings_field('iih_visual_composer_generator_remover_enable_checkbox', __('Remove WPBakery Page Builder generator tag (Applicable if WPBakery Page Builder Plugin is used)', 'wp-meta-and-version-remover'), array($this, 'iih_visual_composer_generator_remover_checkbox_setting'), __FILE__, 'iih_meta_generator_remover_section');
        add_settings_section('iih_meta_and_version_remover_section', __('Version Remover Settings', 'wp-meta-and-version-remover'), array($this, 'iih_meta_and_version_remover_callback'), __FILE__);
        add_settings_field('iih_version_info_remover_style_checkbox', __('Remove version from stylesheet (CSS files)', 'wp-meta-and-version-remover'), array($this, 'iih_version_info_remover_style_checkbox_setting'), __FILE__, 'iih_meta_and_version_remover_section');
        add_settings_field('iih_version_info_remover_script_checkbox', __('Remove version from script (JS files)', 'wp-meta-and-version-remover'), array($this, 'iih_version_info_remover_script_checkbox_setting'), __FILE__, 'iih_meta_and_version_remover_section');
        add_settings_field('iih_version_info_remover_script_exclude_css', __('Enter CSS/JS file names to exclude from version removal (comma separated list)', 'wp-meta-and-version-remover'), array($this, 'iih_version_info_remover_script_exclude_css'), __FILE__, 'iih_meta_and_version_remover_section');
        add_settings_section('iih_view_source_comments_remover_section', __('View Source Comments Remover Settings', 'wp-meta-and-version-remover'), array($this, 'iih_meta_and_version_remover_callback'), __FILE__);
        add_settings_field('iih_comments_remover_yoast_seo_checkbox', __('Remove Yoast SEO comments', 'wp-meta-and-version-remover'), array($this, 'iih_comments_remover_yoast_seo_checkbox_setting'), __FILE__, 'iih_view_source_comments_remover_section');
        add_settings_field('iih_comments_remover_monsterinsights_checkbox', __('Remove Google Analytics (MonsterInsights) comments', 'wp-meta-and-version-remover'), array($this, 'iih_comments_remover_monsterinsights_checkbox_setting'), __FILE__, 'iih_view_source_comments_remover_section');
    }
    
    public function iih_meta_and_version_remover_callback() {
        // silence is golden
    }

    public function iih_meta_generator_remover_checkbox_setting() {
        ?>
        <input name="meta_and_version_remover_options[iih_meta_generator_remover_enable_checkbox]" type="checkbox" value="1"<?php checked( 1 == (isset($this->options['iih_meta_generator_remover_enable_checkbox']) && $this->options['iih_meta_generator_remover_enable_checkbox']) ); ?> />
        <?php 
    }

    public function iih_wpml_generator_remover_checkbox_setting() {
        ?>
        <input name="meta_and_version_remover_options[iih_wpml_generator_remover_enable_checkbox]" type="checkbox" value="1"<?php checked( 1 == (isset($this->options['iih_wpml_generator_remover_enable_checkbox']) && $this->options['iih_wpml_generator_remover_enable_checkbox']) ); ?> />
        <?php 
    }

    public function iih_revslider_generator_remover_checkbox_setting() {
        ?>
        <input name="meta_and_version_remover_options[iih_revslider_generator_remover_enable_checkbox]" type="checkbox" value="1"<?php checked( 1 == (isset($this->options['iih_revslider_generator_remover_enable_checkbox']) && $this->options['iih_revslider_generator_remover_enable_checkbox']) ); ?> />
        <?php 
    }

    public function iih_visual_composer_generator_remover_checkbox_setting() {
        ?>
        <input name="meta_and_version_remover_options[iih_visual_composer_generator_remover_enable_checkbox]" type="checkbox" value="1"<?php checked( 1 == (isset($this->options['iih_visual_composer_generator_remover_enable_checkbox']) && $this->options['iih_visual_composer_generator_remover_enable_checkbox']) ); ?> />
        <?php 
    }

    public function iih_version_info_remover_style_checkbox_setting() {
        ?>
        <input name="meta_and_version_remover_options[iih_version_info_remover_style_checkbox]" type="checkbox" value="1"<?php checked( 1 == (isset($this->options['iih_version_info_remover_style_checkbox']) && $this->options['iih_version_info_remover_style_checkbox']) ); ?> />
        <?php
    }

    public function iih_version_info_remover_script_checkbox_setting() {
        ?>
        <input name="meta_and_version_remover_options[iih_version_info_remover_script_checkbox]" type="checkbox" value="1"<?php checked( 1 == (isset($this->options['iih_version_info_remover_script_checkbox']) && $this->options['iih_version_info_remover_script_checkbox']) ); ?> />
        <?php
    }

    public function iih_version_info_remover_script_exclude_css() {
        ?>
        <textarea placeholder="<?php _e('Enter comma separated list of file names (CSS/JS files) to exclude them from version removal process. Version info will be kept for these files.', 'wp-meta-and-version-remover'); ?>" name="meta_and_version_remover_options[iih_version_info_remover_script_exclude_css]" rows="7" cols="60" style="resize:none;"><?php if (isset($this->options['iih_version_info_remover_script_exclude_css'])) { echo $this->options['iih_version_info_remover_script_exclude_css']; } ?></textarea>
        <?php
    }

    public function iih_comments_remover_yoast_seo_checkbox_setting() {
        ?>
        <input name="meta_and_version_remover_options[iih_comments_remover_yoast_seo_checkbox]" type="checkbox" value="1"<?php checked( 1 == (isset($this->options['iih_comments_remover_yoast_seo_checkbox']) && $this->options['iih_comments_remover_yoast_seo_checkbox']) ); ?> />
        <?php
    }

    public function iih_comments_remover_monsterinsights_checkbox_setting() {
        ?>
        <input name="meta_and_version_remover_options[iih_comments_remover_monsterinsights_checkbox]" type="checkbox" value="1"<?php checked( 1 == (isset($this->options['iih_comments_remover_monsterinsights_checkbox']) && $this->options['iih_comments_remover_monsterinsights_checkbox']) ); ?> />
        <?php
    }
}


$options = get_option('meta_and_version_remover_options');
$exclude_file_list = '';
if ( isset($options['iih_version_info_remover_script_exclude_css']) ) {
    $exclude_file_list = $options['iih_version_info_remover_script_exclude_css'];
}
$exclude_files_arr = array_map('trim', explode(',', $exclude_file_list));

/**
 * Hook into the WordPress default generator.
 */
if ( isset($options['iih_meta_generator_remover_enable_checkbox']) && ($options['iih_meta_generator_remover_enable_checkbox'] == 1) ) {
    add_filter( 'the_generator', '__return_null' );
}

/**
 * Hook into the WPML generator.
 */
if ( isset($options['iih_wpml_generator_remover_enable_checkbox']) && ($options['iih_wpml_generator_remover_enable_checkbox'] == 1) ) {
    if ( !empty ( $GLOBALS['sitepress'] ) ) {
        function remove_wpml_generator() {
            remove_action(
                current_filter(),
                array ( $GLOBALS['sitepress'], 'meta_generator_tag' )
            );
        }
        add_action( 'wp_head', 'remove_wpml_generator', 0 );
    }
}

/**
 * Hook into the Slider Revolution generator.
 */
if ( isset($options['iih_revslider_generator_remover_enable_checkbox']) && ($options['iih_revslider_generator_remover_enable_checkbox'] == 1) ) {
    function remove_revslider_meta_tag() {
        return '';
    }
    add_filter( 'revslider_meta_generator', 'remove_revslider_meta_tag' );
}

/**
 * Hook into the WPBakery Page Builder generator.
 */
if ( isset($options['iih_visual_composer_generator_remover_enable_checkbox']) && ($options['iih_visual_composer_generator_remover_enable_checkbox'] == 1) ) {
    add_action('init', 'wpbakery_page_builder_generator_remover_fn', 100);
    function wpbakery_page_builder_generator_remover_fn() {
        if ( class_exists( 'Vc_Manager' ) || class_exists( 'Vc_Base' ) ) {
            remove_action('wp_head', array(visual_composer(), 'addMetaData'));
        }
    }
}

/**
 * Hook into the Yoast SEO comments.
 */
if ( isset($options['iih_comments_remover_yoast_seo_checkbox']) && ($options['iih_comments_remover_yoast_seo_checkbox'] == 1) ) {
    function remove_yoast_seo_comments_fn() {
        if ( ! class_exists( 'WPSEO_Frontend' ) ) {
            return;
        }
        $instance = WPSEO_Frontend::get_instance();
        // To ensure that future version of the plugin does not cause any problem
        if ( ! method_exists( $instance, 'debug_mark') ) {
            return;
        }
        remove_action( 'wpseo_head', array( $instance, 'debug_mark' ), 2 );
    }
    add_action('template_redirect', 'remove_yoast_seo_comments_fn', 9999);
}

/**
 * Hook into the Google Analytics (MonsterInsights) comments.
 */
if ( isset($options['iih_comments_remover_monsterinsights_checkbox']) && ($options['iih_comments_remover_monsterinsights_checkbox'] == 1) ) {
    function rgamc_active( $plugin ) {
        $network_active = false;
        if ( is_multisite() ) {
            $plugins = get_site_option( 'active_sitewide_plugins' );
            if ( isset( $plugins[$plugin] ) ) {
                $network_active = true;
            }
        }
        return in_array( $plugin, get_option( 'active_plugins' ) ) || $network_active;
    }
    if ( rgamc_active( 'google-analytics-for-wordpress/googleanalytics.php' ) || rgamc_active( 'google-analytics-premium/googleanalytics.php' ) ) {
        add_action('get_header',function (){
            ob_start(function ($o) {
                return preg_replace('/\n?<.*?monsterinsights.*?>/mi','',$o);
            });
        });
        add_action('wp_head',function (){
            ob_end_flush();
        }, 999);
    }
}

/**
 *  remove wp version param from any enqueued scripts (using wp_enqueue_script()) or styles (using wp_enqueue_style()). But first check the list of user defined excluded CSS/JS files... Those files will be skipped and version information will be kept.
 */
function iih_remove_appended_version_script_style( $target_url ) {
    $filename_arr = explode('?', basename($target_url));
    $filename = $filename_arr[0];
    global $exclude_files_arr, $exclude_file_list;
    // first check the list of user defined excluded CSS/JS files
    if (!in_array(trim($filename), $exclude_files_arr)) {
        /* check if "ver=" argument exists in the url or not */
        if (strpos( $target_url, 'ver=' )) {
            $target_url = remove_query_arg( 'ver', $target_url );
        }
        /* check if "version=" argument exists in the url or not */
        if (strpos( $target_url, 'version=' )) {
            $target_url = remove_query_arg( 'version', $target_url );
        }
    }
    return $target_url;
}

/**
 * Priority set to 9999999. Higher numbers correspond with later execution.
 * Hook into the style loader and remove the version information.
 */
if ( isset($options['iih_version_info_remover_style_checkbox']) && ($options['iih_version_info_remover_style_checkbox'] == 1) ) {
    add_filter('style_loader_src', 'iih_remove_appended_version_script_style', 9999999);
}

/**
 * Hook into the script loader and remove the version information.
 */
if ( isset($options['iih_version_info_remover_script_checkbox']) && ($options['iih_version_info_remover_script_checkbox'] == 1) ) {
    add_filter('script_loader_src', 'iih_remove_appended_version_script_style', 9999999);
}

add_action('admin_menu', 'iih_meta_generator_add_options_page_function');

function iih_meta_generator_add_options_page_function() {
    $object = new Meta_and_version_remover();
    $object->iih_add_menu_page();
}

add_action('admin_init', 'iih_meta_generator_remover_initiate_class');

function iih_meta_generator_remover_initiate_class() {
    new Meta_and_version_remover();
}

function meta_and_version_remover_defaults() {
    $current_options = get_option('meta_and_version_remover_options');
    $defaults = array(
        'iih_meta_generator_remover_enable_checkbox'            => 1,
        'iih_wpml_generator_remover_enable_checkbox'            => 1,
        'iih_revslider_generator_remover_enable_checkbox'       => 1,
        'iih_visual_composer_generator_remover_enable_checkbox' => 1,
        'iih_version_info_remover_style_checkbox'               => 1,
        'iih_version_info_remover_script_checkbox'              => 1,
        'iih_version_info_remover_script_exclude_css'           => ( isset($current_options['iih_version_info_remover_script_exclude_css']) ? $current_options['iih_version_info_remover_script_exclude_css'] : '' ),
        'iih_comments_remover_yoast_seo_checkbox'               => 1,
        'iih_comments_remover_monsterinsights_checkbox'         => 1
    );

    if ( is_admin() ) {
        update_option( 'meta_and_version_remover_options', $defaults );
    }
}

register_activation_hook( __FILE__, 'meta_and_version_remover_defaults' );

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'mavr_setting_action_links',10 ,1);
function mavr_setting_action_links($links)
{
    $links[] = '<a href="' . esc_url(get_admin_url(null, 'options-general.php?page=wp-meta-and-version-remover/meta_and_version_remover.php')) . '">'.__('Settings','wp-meta-and-version-remover').'</a>';
    return $links;
}

