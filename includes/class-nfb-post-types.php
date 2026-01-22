<?php
/**
 * Custom Post Types per Nati Fuori Binario Landing
 */

if (!defined('ABSPATH')) {
    exit;
}

class NFB_Post_Types {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', array($this, 'register_post_types'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
    }

    public function register_post_types() {
        // Rassegna Stampa
        register_post_type('nfb_rassegna', array(
            'labels' => array(
                'name' => __('Rassegna Stampa', 'nfb-landing'),
                'singular_name' => __('Articolo Rassegna', 'nfb-landing'),
                'add_new' => __('Aggiungi Articolo', 'nfb-landing'),
                'add_new_item' => __('Aggiungi Nuovo Articolo', 'nfb-landing'),
                'edit_item' => __('Modifica Articolo', 'nfb-landing'),
                'new_item' => __('Nuovo Articolo', 'nfb-landing'),
                'view_item' => __('Visualizza Articolo', 'nfb-landing'),
                'search_items' => __('Cerca Articoli', 'nfb-landing'),
                'not_found' => __('Nessun articolo trovato', 'nfb-landing'),
                'not_found_in_trash' => __('Nessun articolo nel cestino', 'nfb-landing'),
                'menu_name' => __('Rassegna Stampa', 'nfb-landing'),
            ),
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-media-document',
            'capability_type' => 'post',
            'supports' => array('title'),
            'has_archive' => false,
            'rewrite' => false,
        ));

        // Eventi/Incontri
        register_post_type('nfb_evento', array(
            'labels' => array(
                'name' => __('Eventi/Incontri', 'nfb-landing'),
                'singular_name' => __('Evento', 'nfb-landing'),
                'add_new' => __('Aggiungi Evento', 'nfb-landing'),
                'add_new_item' => __('Aggiungi Nuovo Evento', 'nfb-landing'),
                'edit_item' => __('Modifica Evento', 'nfb-landing'),
                'new_item' => __('Nuovo Evento', 'nfb-landing'),
                'view_item' => __('Visualizza Evento', 'nfb-landing'),
                'search_items' => __('Cerca Eventi', 'nfb-landing'),
                'not_found' => __('Nessun evento trovato', 'nfb-landing'),
                'not_found_in_trash' => __('Nessun evento nel cestino', 'nfb-landing'),
                'menu_name' => __('Eventi/Incontri', 'nfb-landing'),
            ),
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 6,
            'menu_icon' => 'dashicons-calendar-alt',
            'capability_type' => 'post',
            'supports' => array('title', 'editor'),
            'has_archive' => false,
            'rewrite' => false,
        ));
    }

    public function add_meta_boxes() {
        // Meta box per Rassegna
        add_meta_box(
            'nfb_rassegna_details',
            __('Dettagli Articolo', 'nfb-landing'),
            array($this, 'render_rassegna_meta_box'),
            'nfb_rassegna',
            'normal',
            'high'
        );

        // Meta box per Eventi
        add_meta_box(
            'nfb_evento_details',
            __('Dettagli Evento', 'nfb-landing'),
            array($this, 'render_evento_meta_box'),
            'nfb_evento',
            'normal',
            'high'
        );
    }

    public function render_rassegna_meta_box($post) {
        wp_nonce_field('nfb_rassegna_meta_box', 'nfb_rassegna_meta_box_nonce');

        $link = get_post_meta($post->ID, '_nfb_rassegna_link', true);
        $data = get_post_meta($post->ID, '_nfb_rassegna_data', true);
        $testata = get_post_meta($post->ID, '_nfb_rassegna_testata', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="nfb_rassegna_testata"><?php _e('Testata', 'nfb-landing'); ?></label>
                </th>
                <td>
                    <input type="text" id="nfb_rassegna_testata" name="nfb_rassegna_testata"
                           value="<?php echo esc_attr($testata); ?>" class="regular-text"
                           placeholder="Es: Corriere della Sera, La Repubblica...">
                    <p class="description"><?php _e('Nome della testata giornalistica', 'nfb-landing'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="nfb_rassegna_link"><?php _e('Link Articolo', 'nfb-landing'); ?></label>
                </th>
                <td>
                    <input type="url" id="nfb_rassegna_link" name="nfb_rassegna_link"
                           value="<?php echo esc_url($link); ?>" class="regular-text"
                           placeholder="https://esempio.com/articolo">
                    <p class="description"><?php _e('Inserisci l\'URL completo dell\'articolo', 'nfb-landing'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="nfb_rassegna_data"><?php _e('Data Pubblicazione', 'nfb-landing'); ?></label>
                </th>
                <td>
                    <input type="date" id="nfb_rassegna_data" name="nfb_rassegna_data"
                           value="<?php echo esc_attr($data); ?>">
                </td>
            </tr>
        </table>
        <?php
    }

    public function render_evento_meta_box($post) {
        wp_nonce_field('nfb_evento_meta_box', 'nfb_evento_meta_box_nonce');

        $luogo = get_post_meta($post->ID, '_nfb_evento_luogo', true);
        $data = get_post_meta($post->ID, '_nfb_evento_data', true);
        $ora = get_post_meta($post->ID, '_nfb_evento_ora', true);
        $link = get_post_meta($post->ID, '_nfb_evento_link', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="nfb_evento_luogo"><?php _e('Luogo Evento', 'nfb-landing'); ?></label>
                </th>
                <td>
                    <input type="text" id="nfb_evento_luogo" name="nfb_evento_luogo"
                           value="<?php echo esc_attr($luogo); ?>" class="regular-text"
                           placeholder="Es: Libreria Feltrinelli, Milano">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="nfb_evento_data"><?php _e('Data Evento', 'nfb-landing'); ?></label>
                </th>
                <td>
                    <input type="date" id="nfb_evento_data" name="nfb_evento_data"
                           value="<?php echo esc_attr($data); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="nfb_evento_ora"><?php _e('Ora Evento', 'nfb-landing'); ?></label>
                </th>
                <td>
                    <input type="time" id="nfb_evento_ora" name="nfb_evento_ora"
                           value="<?php echo esc_attr($ora); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="nfb_evento_link"><?php _e('Link Evento', 'nfb-landing'); ?></label>
                </th>
                <td>
                    <input type="url" id="nfb_evento_link" name="nfb_evento_link"
                           value="<?php echo esc_url($link); ?>" class="regular-text"
                           placeholder="https://esempio.com/evento">
                    <p class="description"><?php _e('Inserisci un link per maggiori informazioni sull\'evento (opzionale)', 'nfb-landing'); ?></p>
                </td>
            </tr>
        </table>
        <p class="description"><?php _e('Utilizza l\'editor sopra per inserire la descrizione dell\'evento.', 'nfb-landing'); ?></p>
        <?php
    }

    public function save_meta_boxes($post_id) {
        // Rassegna
        if (isset($_POST['nfb_rassegna_meta_box_nonce']) &&
            wp_verify_nonce($_POST['nfb_rassegna_meta_box_nonce'], 'nfb_rassegna_meta_box')) {

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            if (isset($_POST['nfb_rassegna_testata'])) {
                update_post_meta($post_id, '_nfb_rassegna_testata', sanitize_text_field($_POST['nfb_rassegna_testata']));
            }

            if (isset($_POST['nfb_rassegna_link'])) {
                update_post_meta($post_id, '_nfb_rassegna_link', esc_url_raw($_POST['nfb_rassegna_link']));
            }

            if (isset($_POST['nfb_rassegna_data'])) {
                update_post_meta($post_id, '_nfb_rassegna_data', sanitize_text_field($_POST['nfb_rassegna_data']));
            }
        }

        // Eventi
        if (isset($_POST['nfb_evento_meta_box_nonce']) &&
            wp_verify_nonce($_POST['nfb_evento_meta_box_nonce'], 'nfb_evento_meta_box')) {

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            if (isset($_POST['nfb_evento_luogo'])) {
                update_post_meta($post_id, '_nfb_evento_luogo', sanitize_text_field($_POST['nfb_evento_luogo']));
            }

            if (isset($_POST['nfb_evento_data'])) {
                update_post_meta($post_id, '_nfb_evento_data', sanitize_text_field($_POST['nfb_evento_data']));
            }

            if (isset($_POST['nfb_evento_ora'])) {
                update_post_meta($post_id, '_nfb_evento_ora', sanitize_text_field($_POST['nfb_evento_ora']));
            }

            if (isset($_POST['nfb_evento_link'])) {
                update_post_meta($post_id, '_nfb_evento_link', esc_url_raw($_POST['nfb_evento_link']));
            }
        }
    }
}
