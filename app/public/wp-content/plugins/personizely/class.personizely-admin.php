<?php

class Personizely_Admin {

    private static $initiated = false;

    public static function init() {
        if (!self::$initiated) {
            self::init_plugin_options();
        }
    }

    public static function init_plugin_options() {
        if (current_user_can('activate_plugins')) {
            add_action('admin_menu', array('Personizely_Admin', 'plugin_settings'));
            add_action('admin_init', array('Personizely_Admin', 'handle_form'));
            add_filter('allowed_redirect_hosts', array('Personizely_Admin', 'allowed_redirect_hosts'));
            add_filter('plugin_action_links_' . plugin_basename(plugin_dir_path(__FILE__) . 'personizely.php'), array('Personizely_Admin', 'admin_plugin_settings_link'));
        }
    }

    public static function allowed_redirect_hosts($hosts) {
        $hosts[] = PERSONIZELY_APP_HOST;
        return $hosts;
    }

    public static function admin_plugin_settings_link($links) {
        $settings_link = '<a href="' . esc_url(self::get_page_url()) . '">' . __('Settings', 'personizely') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }


    public static function plugin_settings() {
        add_menu_page('Personizely Settings', 'Personizely', 'manage_options', 'personizely', array('Personizely_Admin', 'plugin_settings_view'), PERSONIZELY__PLUGIN_URL . '/assets/img/icon.png');
    }

    public static function validate_api_key($api_key) {
        return strlen($api_key) === 10 && preg_match('/^[a-z0-9]+$/', $api_key);
    }

    public static function sanitize_api_key($api_key) {
        return sanitize_key($api_key);
    }

    public static function plugin_settings_view() {
        global $wp;

        $nonce = wp_create_nonce('personizely_api_key_save');

        $params = [
            'site' => home_url(add_query_arg(array(), $wp->request)),
            'initial' => 1,
            'nonce' => $nonce
        ];

        $info = [
            'email' => get_option('admin_email'),
            'name' => get_option('blogname'),
            'domain' => parse_url(get_option('siteurl'))['host']
        ];

        $connectUrl = '/connect/wordpress?'. http_build_query($params);

        $data = array(
            'app_url' => PERSONIZELY_APP_URL,
            'api_key' => Personizely::get_api_key(),
            'async' => Personizely::get_async(),
            'connect_url' => $connectUrl,
            'nonce' => $nonce,
            'register_url' => '/register?redirect='. urlencode($connectUrl) . '&' . http_build_query($info),
        );

        include PERSONIZELY__PLUGIN_DIR . '/views/settings-main.php';
    }

    public static function handle_form() {
        if (get_option('personizely_api_key') === false) add_option('personizely_api_key', '');
        if (get_option('personizely_async') === false) add_option('personizely_async', true);

        if ($_POST) {
            if (isset($_POST['api_key']) &&
                $_POST['api_key'] &&
                isset($_POST['nonce']) &&
                wp_verify_nonce($_POST['nonce'], 'personizely_api_key_save') &&
                self::validate_api_key($_POST['api_key'])
            ) {
                update_option('personizely_api_key', self::sanitize_api_key($_POST['api_key']));
                update_option('personizely_async', (boolean) $_POST['async']);
                wp_safe_redirect(self::get_page_url());
            }
        }

        if (isset($_GET['api_key']) &&
            $_GET['api_key'] &&
            isset($_GET['nonce']) &&
            wp_verify_nonce($_GET['nonce'], 'personizely_api_key_save') &&
            self::validate_api_key($_GET['api_key'])
        ) {
            update_option('personizely_api_key', self::sanitize_api_key($_GET['api_key']));

            if (isset($_GET['get_started'])) {
                wp_safe_redirect(PERSONIZELY_APP_URL . '/start?source=wordpress');
            } else {
                wp_safe_redirect(self::get_page_url());
            }
        }
    }

    public static function get_page_url() {

        $args = array('page' => 'personizely');

        $url = add_query_arg($args, admin_url('admin.php'));

        return $url;
    }

    public static function log($personizely_debug) {
        if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG)
            error_log(print_r(compact('personizely_debug'), 1)); // send message to debug.log when in debug mode
    }
}
