<?php

class Personizely {

    private static $initiated = false;

    public static function init() {
        if (!self::$initiated) {
            self::init_plugin();
        }
    }

    public static function init_plugin() {
        if (self::get_api_key() && !is_admin()) {
            add_action('wp_print_scripts', array('Personizely', 'personizely_script'));
            add_shortcode('ply-widget', array('Personizely', 'shortcode_widget'));
            add_shortcode('ply-placeholder', array('Personizely', 'shortcode_placeholder'));

            // SiteGround Optimizer
            add_filter('sgo_javascript_combine_excluded_external_paths', array('Personizely', 'optimization_exclude'));

            // WP Rocket
            add_filter('rocket_minify_excluded_external_js', array('Personizely', 'optimization_exclude'));

            // Jetpack Boost
            add_filter('jetpack_boost_render_blocking_js_exclude_handles', array('Personizely', 'optimization_exclude'));

            // WP Meteor
            add_filter('wpmeteor_exclude', function ($exclude, $content) {
                if (str_contains($content, PERSONIZELY_STATIC_HOST)) {
                    return true;
                }

                return $exclude;
            });
        }
    }

    public static function optimization_exclude($exclude_list) {
        $exclude_list[] = PERSONIZELY_STATIC_HOST;

        return $exclude_list;
    }

    public static function shortcode_widget($arguments) {
        $widgetId = $arguments[0];
        echo "<div data-ply-embedded-widget='$widgetId'></div>";
    }

    public static function shortcode_placeholder($arguments) {
        $placeholderId = $arguments[0];
        echo "<div data-ply-placeholder='$placeholderId'></div>";
    }

    public static function personizely_script() {
        $url = (PERSONIZELY_STATIC_URL . '/' . self::get_api_key() . '.js');
        echo '<script src="' . $url . '"' . (self::get_async() ? ' async' : '') . ' type="text/javascript"></script>' . PHP_EOL;
    }

    public static function get_api_key() {
        if (get_option('personizely_api_key') !== false)
            return get_option('personizely_api_key');
        else
            return '';
    }

    public static function get_async() {
        if (get_option('personizely_async') !== false)
            return get_option('personizely_async');
        else
            return false;
    }
}