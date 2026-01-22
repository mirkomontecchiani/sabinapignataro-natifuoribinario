<?php
/**
 * Admin Settings per Nati Fuori Binario Landing
 */

if (!defined('ABSPATH')) {
    exit;
}

class NFB_Admin {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_menu', array($this, 'hide_elementor_for_editors'), 999);
    }

    public function add_admin_menu() {
        add_menu_page(
            __('Testi della Home', 'nfb-landing'),
            __('Testi della home', 'nfb-landing'),
            'edit_posts',
            'nfb-landing',
            array($this, 'render_settings_page'),
            'dashicons-book',
            24
        );

        add_submenu_page(
            'nfb-landing',
            __('Impostazioni Landing', 'nfb-landing'),
            __('Impostazioni', 'nfb-landing'),
            'edit_posts',
            'nfb-landing',
            array($this, 'render_settings_page')
        );
    }

    public function hide_elementor_for_editors() {
        // Hide Elementor menu for editors and authors (non-admins)
        if (!current_user_can('manage_options')) {
            remove_menu_page('elementor');
            remove_menu_page('edit.php?post_type=elementor_library');
        }
    }

    public function register_settings() {
        register_setting('nfb_landing_options_group', 'nfb_landing_options', array(
            'sanitize_callback' => array($this, 'sanitize_options'),
        ));

        // Allow editors and authors to save options
        add_filter('option_page_capability_nfb_landing_options_group', function() {
            return 'edit_posts';
        });

        // Sezione Sinossi
        add_settings_section(
            'nfb_sinossi_section',
            __('Sinossi del Libro', 'nfb-landing'),
            array($this, 'sinossi_section_callback'),
            'nfb-landing'
        );

        add_settings_field(
            'sinossi',
            __('Testo Sinossi', 'nfb-landing'),
            array($this, 'sinossi_field_callback'),
            'nfb-landing',
            'nfb_sinossi_section'
        );

        // Sezione Chi Sono
        add_settings_section(
            'nfb_chi_sono_section',
            __('Chi Sono (L\'Autrice)', 'nfb-landing'),
            array($this, 'chi_sono_section_callback'),
            'nfb-landing'
        );

        add_settings_field(
            'chi_sono',
            __('Testo Chi Sono', 'nfb-landing'),
            array($this, 'chi_sono_field_callback'),
            'nfb-landing',
            'nfb_chi_sono_section'
        );
    }

    public function sanitize_options($input) {
        $sanitized = array();

        if (isset($input['sinossi'])) {
            $sanitized['sinossi'] = wp_kses_post($input['sinossi']);
        }

        if (isset($input['chi_sono'])) {
            $sanitized['chi_sono'] = wp_kses_post($input['chi_sono']);
        }

        return $sanitized;
    }

    public function sinossi_section_callback() {
        echo '<p>' . __('Inserisci il testo della sinossi del libro che verrà mostrato nella landing page.', 'nfb-landing') . '</p>';
    }

    public function chi_sono_section_callback() {
        echo '<p>' . __('Inserisci la biografia dell\'autrice che verrà mostrata nella sezione "Chi Sono".', 'nfb-landing') . '</p>';
    }

    public function sinossi_field_callback() {
        $options = get_option('nfb_landing_options');
        $content = isset($options['sinossi']) ? $options['sinossi'] : '';

        wp_editor($content, 'nfb_sinossi_editor', array(
            'textarea_name' => 'nfb_landing_options[sinossi]',
            'textarea_rows' => 10,
            'media_buttons' => false,
            'teeny' => false,
            'quicktags' => true,
        ));
    }

    public function chi_sono_field_callback() {
        $options = get_option('nfb_landing_options');
        $content = isset($options['chi_sono']) ? $options['chi_sono'] : '';

        wp_editor($content, 'nfb_chi_sono_editor', array(
            'textarea_name' => 'nfb_landing_options[chi_sono]',
            'textarea_rows' => 10,
            'media_buttons' => true,
            'teeny' => false,
            'quicktags' => true,
        ));
    }

    public function render_settings_page() {
        if (!current_user_can('edit_posts')) {
            return;
        }

        if (isset($_GET['settings-updated'])) {
            add_settings_error('nfb_landing_messages', 'nfb_landing_message',
                __('Impostazioni salvate.', 'nfb-landing'), 'updated');
        }

        settings_errors('nfb_landing_messages');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <div class="nfb-admin-intro">
                <h2><?php _e('Benvenuto nel plugin Nati Fuori Binario Landing', 'nfb-landing'); ?></h2>
                <p><?php _e('Configura i contenuti della tua landing page qui sotto.', 'nfb-landing'); ?></p>

                <div class="nfb-shortcode-info">
                    <h3><?php _e('Come utilizzare', 'nfb-landing'); ?></h3>
                    <p><?php _e('Per visualizzare la landing page, crea una nuova pagina e inserisci lo shortcode:', 'nfb-landing'); ?></p>
                    <code>[landing]</code>
                    <p><?php _e('Ricorda di impostare la pagina come template a larghezza piena (se disponibile nel tema) o utilizza un tema minimale.', 'nfb-landing'); ?></p>
                </div>

                <div class="nfb-sections-info">
                    <h3><?php _e('Sezioni della Landing', 'nfb-landing'); ?></h3>
                    <ul>
                        <li><strong><?php _e('Header:', 'nfb-landing'); ?></strong> <?php _e('Copertina, titolo e menu fisso', 'nfb-landing'); ?></li>
                        <li><strong><?php _e('Sinossi:', 'nfb-landing'); ?></strong> <?php _e('Configura sotto', 'nfb-landing'); ?></li>
                        <li><strong><?php _e('Chi Sono:', 'nfb-landing'); ?></strong> <?php _e('Configura sotto', 'nfb-landing'); ?></li>
                        <li><strong><?php _e('Rassegna Stampa:', 'nfb-landing'); ?></strong> <a href="<?php echo admin_url('edit.php?post_type=nfb_rassegna'); ?>"><?php _e('Gestisci articoli', 'nfb-landing'); ?></a></li>
                        <li><strong><?php _e('Eventi/Incontri:', 'nfb-landing'); ?></strong> <a href="<?php echo admin_url('edit.php?post_type=nfb_evento'); ?>"><?php _e('Gestisci eventi', 'nfb-landing'); ?></a></li>
                        <li><strong><?php _e('Contatti:', 'nfb-landing'); ?></strong> <?php _e('Link a sabinapignataro.it/contatti/', 'nfb-landing'); ?></li>
                        <li><strong><?php _e('Acquista:', 'nfb-landing'); ?></strong> <?php _e('Pulsante per Erickson', 'nfb-landing'); ?></li>
                    </ul>
                </div>
            </div>

            <form action="options.php" method="post">
                <?php
                settings_fields('nfb_landing_options_group');
                do_settings_sections('nfb-landing');
                submit_button(__('Salva Impostazioni', 'nfb-landing'));
                ?>
            </form>
        </div>

        <style>
            .nfb-admin-intro {
                background: #fff;
                padding: 20px;
                margin: 20px 0;
                border: 1px solid #ccd0d4;
                border-radius: 4px;
            }
            .nfb-shortcode-info {
                background: #f0f6fc;
                padding: 15px;
                margin: 15px 0;
                border-left: 4px solid #2271b1;
            }
            .nfb-shortcode-info code {
                display: inline-block;
                padding: 5px 15px;
                background: #1d2327;
                color: #50c878;
                font-size: 16px;
                margin: 10px 0;
            }
            .nfb-sections-info {
                margin-top: 20px;
            }
            .nfb-sections-info ul {
                list-style: disc;
                margin-left: 20px;
            }
            .nfb-sections-info li {
                margin: 8px 0;
            }
        </style>
        <?php
    }
}
