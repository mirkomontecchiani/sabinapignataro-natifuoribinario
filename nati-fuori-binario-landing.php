<?php
/**
 * Plugin Name: Nati Fuori Binario Landing
 * Plugin URI: https://natifuoribinario.it
 * Description: Plugin per la creazione della landing page del libro "Nati Fuori Binario" di Sabina Pignataro
 * Version: 1.0.0
 * Author: Sabina Pignataro
 * Author URI: https://www.sabinapignataro.it
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: nfb-landing
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('NFB_LANDING_VERSION', '1.0.0');
define('NFB_LANDING_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('NFB_LANDING_PLUGIN_URL', plugin_dir_url(__FILE__));

class NFB_Landing {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    private function load_dependencies() {
        require_once NFB_LANDING_PLUGIN_DIR . 'includes/class-nfb-post-types.php';
        require_once NFB_LANDING_PLUGIN_DIR . 'includes/class-nfb-shortcode.php';
        require_once NFB_LANDING_PLUGIN_DIR . 'admin/class-nfb-admin.php';
    }

    private function init_hooks() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));

        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    public function init() {
        NFB_Post_Types::get_instance();
        NFB_Shortcode::get_instance();
        NFB_Admin::get_instance();
    }

    public function enqueue_frontend_assets() {
        if (!is_admin()) {
            wp_enqueue_style(
                'nfb-landing-style',
                NFB_LANDING_PLUGIN_URL . 'assets/css/landing.css',
                array(),
                NFB_LANDING_VERSION
            );

            wp_enqueue_script(
                'nfb-landing-script',
                NFB_LANDING_PLUGIN_URL . 'assets/js/landing.js',
                array('jquery'),
                NFB_LANDING_VERSION,
                true
            );

            wp_localize_script('nfb-landing-script', 'nfbLanding', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('nfb_landing_nonce')
            ));
        }
    }

    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'nfb-landing') !== false) {
            wp_enqueue_style(
                'nfb-admin-style',
                NFB_LANDING_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                NFB_LANDING_VERSION
            );

            wp_enqueue_editor();
        }
    }

    public function activate() {
        NFB_Post_Types::get_instance()->register_post_types();
        flush_rewrite_rules();

        $default_options = array(
            'sinossi' => '',
            'chi_sono' => '',
        );

        if (!get_option('nfb_landing_options')) {
            add_option('nfb_landing_options', $default_options);
        }
    }

    public function deactivate() {
        flush_rewrite_rules();
    }
}

NFB_Landing::get_instance();
