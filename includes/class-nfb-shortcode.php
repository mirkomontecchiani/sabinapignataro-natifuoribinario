<?php
/**
 * Shortcode per la Landing Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class NFB_Shortcode {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_shortcode('landing', array($this, 'render_landing'));
        add_filter('body_class', array($this, 'add_body_class'));
    }

    public function add_body_class($classes) {
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'landing')) {
            $classes[] = 'nfb-landing-page';
        }
        return $classes;
    }

    public function render_landing($atts) {
        $atts = shortcode_atts(array(), $atts, 'landing');

        $options = get_option('nfb_landing_options');
        $sinossi = isset($options['sinossi']) ? $options['sinossi'] : '';
        $chi_sono = isset($options['chi_sono']) ? $options['chi_sono'] : '';
        $premio_attivo = isset($options['premio_attivo']) ? $options['premio_attivo'] : '1';
        $default_premio_text = '<p>Questo libro è stato selezionato tra i candidati al <strong>Premio Inge Feltrinelli</strong>, un riconoscimento dedicato ai libri che raccontano storie di coraggio, inclusione e cambiamento sociale.</p><p>Il tuo voto può fare la differenza!</p>';
        $premio_testo = isset($options['premio_testo']) ? $options['premio_testo'] : $default_premio_text;

        ob_start();
        ?>
        <div class="nfb-landing-wrapper">
            <?php
            $this->render_header($premio_attivo);
            $this->render_sinossi($sinossi);
            $this->render_chi_sono($chi_sono);
            if ($premio_attivo === '1') {
                $this->render_premio($premio_testo);
            }
            $this->render_rassegna();
            $this->render_eventi();
            $this->render_footer();
            ?>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_header($premio_attivo = '1') {
        $site_title = get_bloginfo('name');
        $site_description = get_bloginfo('description');
        $cover_image = NFB_LANDING_PLUGIN_URL . 'assets/images/Copertina-Nati-Fuori-Binario.jpg';
        ?>
        <header class="nfb-header" id="nfb-header">
            <div class="nfb-header-inner">
                <div class="nfb-header-left">
                    <img src="<?php echo esc_url($cover_image); ?>" alt="<?php echo esc_attr($site_title); ?>" class="nfb-cover-image" style="width:auto!important;height:128px!important;max-height:128px!important;object-fit:contain!important;">
                    <div class="nfb-header-text">
                        <h1 class="nfb-site-title"><?php echo esc_html($site_title); ?></h1>
                        <?php if ($site_description): ?>
                            <p class="nfb-site-description"><?php echo esc_html($site_description); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <button class="nfb-mobile-menu-toggle" id="nfb-mobile-toggle" aria-label="<?php _e('Apri menu', 'nfb-landing'); ?>">
                    <span class="nfb-hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <nav class="nfb-nav" id="nfb-nav">
                    <button class="nfb-mobile-close" id="nfb-mobile-close" aria-label="<?php _e('Chiudi menu', 'nfb-landing'); ?>">
                        <span>&times;</span>
                    </button>
                    <ul class="nfb-nav-list">
                        <li><a href="#sinossi"><?php _e('Il libro', 'nfb-landing'); ?></a></li>
                        <li><a href="#chi-sono"><?php _e('Chi Sono', 'nfb-landing'); ?></a></li>
                        <?php if ($premio_attivo === '1'): ?>
                            <li><a href="#premio"><?php _e('Premio', 'nfb-landing'); ?></a></li>
                        <?php endif; ?>
                        <li><a href="#rassegna"><?php _e('Rassegna', 'nfb-landing'); ?></a></li>
                        <li><a href="#eventi"><?php _e('Eventi', 'nfb-landing'); ?></a></li>
                        <li><a href="https://www.sabinapignataro.it/contatti/" target="_blank" rel="noopener"><?php _e('Contatti', 'nfb-landing'); ?></a></li>
                        <li class="nfb-nav-cta">
                            <a href="https://www.erickson.it/it/nati-fuori-binario" target="_blank" rel="noopener" class="nfb-btn nfb-btn-buy"><?php _e('Acquista', 'nfb-landing'); ?></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>
        <div class="nfb-header-spacer"></div>
        <?php
    }

    private function render_sinossi($content) {
        if (empty($content)) {
            return;
        }
        $cover_image = NFB_LANDING_PLUGIN_URL . 'assets/images/Copertina-Nati-Fuori-Binario.jpg';
        ?>
        <section class="nfb-section nfb-sinossi" id="sinossi">
            <div class="nfb-container">
                <div class="nfb-sinossi-layout">
                    <div class="nfb-sinossi-text">
                        <?php echo wp_kses_post($content); ?>
                    </div>
                    <div class="nfb-sinossi-cover">
                        <img src="<?php echo esc_url($cover_image); ?>" alt="<?php _e('Copertina Nati Fuori Binario', 'nfb-landing'); ?>" class="nfb-sinossi-image">
                    </div>
                </div>
            </div>
        </section>
        <?php
    }

    private function render_chi_sono($content) {
        if (empty($content)) {
            return;
        }
        ?>
        <section class="nfb-section nfb-chi-sono" id="chi-sono">
            <div class="nfb-container">
                <h2 class="nfb-section-title"><?php _e('Chi Sono', 'nfb-landing'); ?></h2>
                <div class="nfb-section-content">
                    <?php echo wp_kses_post($content); ?>
                </div>
            </div>
        </section>
        <?php
    }

    private function render_premio($content = '') {
        ?>
        <section class="nfb-section nfb-premio" id="premio">
            <div class="nfb-container">
                <h2 class="nfb-section-title"><?php _e('Candidato al Premio Inge Feltrinelli', 'nfb-landing'); ?></h2>
                <div class="nfb-premio-content">
                    <?php echo wp_kses_post($content); ?>
                </div>
                <div class="nfb-premio-cta">
                    <a href="https://premioingefeltrinelli.it/libri/nati-fuori-binario/" target="_blank" rel="noopener" class="nfb-btn nfb-btn-premio">
                        <?php _e('Vota ora', 'nfb-landing'); ?>
                    </a>
                </div>
            </div>
        </section>
        <?php
    }

    private function render_rassegna() {
        $args = array(
            'post_type' => 'nfb_rassegna',
            'posts_per_page' => -1,
            'orderby' => 'meta_value',
            'meta_key' => '_nfb_rassegna_data',
            'order' => 'DESC',
        );

        $query = new WP_Query($args);

        if (!$query->have_posts()) {
            return;
        }

        $articles = array();
        while ($query->have_posts()) {
            $query->the_post();
            $articles[] = array(
                'title' => get_the_title(),
                'link' => get_post_meta(get_the_ID(), '_nfb_rassegna_link', true),
                'date' => get_post_meta(get_the_ID(), '_nfb_rassegna_data', true),
                'testata' => get_post_meta(get_the_ID(), '_nfb_rassegna_testata', true),
            );
        }
        wp_reset_postdata();

        ?>
        <section class="nfb-section nfb-rassegna" id="rassegna">
            <div class="nfb-container">
                <h2 class="nfb-section-title"><?php _e('Rassegna Stampa', 'nfb-landing'); ?></h2>
                <div class="nfb-rassegna-grid">
                    <?php
                    $count = 0;
                    foreach ($articles as $article):
                        $hidden_class = $count >= 9 ? 'nfb-rassegna-hidden' : '';
                        $formatted_date = '';
                        if (!empty($article['date'])) {
                            $date_obj = DateTime::createFromFormat('Y-m-d', $article['date']);
                            if ($date_obj) {
                                $formatted_date = $date_obj->format('d/m/Y');
                            }
                        }
                    ?>
                        <div class="nfb-rassegna-item <?php echo esc_attr($hidden_class); ?>">
                            <a href="<?php echo esc_url($article['link']); ?>" target="_blank" rel="noopener" class="nfb-rassegna-link">
                                <?php if (!empty($article['testata'])): ?>
                                    <span class="nfb-rassegna-testata"><?php echo esc_html($article['testata']); ?></span>
                                <?php endif; ?>
                                <h3 class="nfb-rassegna-title"><?php echo esc_html($article['title']); ?></h3>
                                <?php if ($formatted_date): ?>
                                    <span class="nfb-rassegna-date"><?php echo esc_html($formatted_date); ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php
                        $count++;
                    endforeach;
                    ?>
                </div>
                <?php if (count($articles) > 9): ?>
                    <div class="nfb-toggle-container">
                        <button class="nfb-toggle-btn" id="nfb-rassegna-toggle" data-expanded="false">
                            <span class="nfb-toggle-more"><?php _e('Mostra altri articoli', 'nfb-landing'); ?></span>
                            <span class="nfb-toggle-less" style="display: none;"><?php _e('Mostra meno', 'nfb-landing'); ?></span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }

    private function render_eventi() {
        $today = date('Y-m-d');

        // Eventi futuri
        $future_args = array(
            'post_type' => 'nfb_evento',
            'posts_per_page' => -1,
            'meta_key' => '_nfb_evento_data',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => '_nfb_evento_data',
                    'value' => $today,
                    'compare' => '>=',
                    'type' => 'DATE',
                ),
            ),
        );

        // Eventi passati
        $past_args = array(
            'post_type' => 'nfb_evento',
            'posts_per_page' => -1,
            'meta_key' => '_nfb_evento_data',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'meta_query' => array(
                array(
                    'key' => '_nfb_evento_data',
                    'value' => $today,
                    'compare' => '<',
                    'type' => 'DATE',
                ),
            ),
        );

        $future_query = new WP_Query($future_args);
        $past_query = new WP_Query($past_args);

        $has_future = $future_query->have_posts();
        $has_past = $past_query->have_posts();

        if (!$has_future && !$has_past) {
            return;
        }

        ?>
        <section class="nfb-section nfb-eventi" id="eventi">
            <div class="nfb-container">
                <h2 class="nfb-section-title"><?php _e('Eventi e Incontri', 'nfb-landing'); ?></h2>

                <?php if ($has_future): ?>
                    <div class="nfb-eventi-future">
                        <h3 class="nfb-eventi-subtitle"><?php _e('Prossimi Eventi', 'nfb-landing'); ?></h3>
                        <div class="nfb-eventi-list">
                            <?php
                            while ($future_query->have_posts()):
                                $future_query->the_post();
                                $this->render_evento_card();
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_past): ?>
                    <div class="nfb-eventi-past" id="nfb-eventi-past" style="display: none;">
                        <h3 class="nfb-eventi-subtitle"><?php _e('Eventi Passati', 'nfb-landing'); ?></h3>
                        <div class="nfb-eventi-list">
                            <?php
                            while ($past_query->have_posts()):
                                $past_query->the_post();
                                $this->render_evento_card();
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                    <div class="nfb-toggle-container">
                        <button class="nfb-toggle-btn" id="nfb-eventi-toggle" data-expanded="false">
                            <span class="nfb-toggle-more"><?php _e('Mostra eventi passati', 'nfb-landing'); ?></span>
                            <span class="nfb-toggle-less" style="display: none;"><?php _e('Nascondi eventi passati', 'nfb-landing'); ?></span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }

    private function render_evento_card() {
        $luogo = get_post_meta(get_the_ID(), '_nfb_evento_luogo', true);
        $data = get_post_meta(get_the_ID(), '_nfb_evento_data', true);
        $ora = get_post_meta(get_the_ID(), '_nfb_evento_ora', true);
        $link = get_post_meta(get_the_ID(), '_nfb_evento_link', true);
        $content = apply_filters('the_content', get_the_content());

        $formatted_date = '';
        if (!empty($data)) {
            $date_obj = DateTime::createFromFormat('Y-m-d', $data);
            if ($date_obj) {
                $formatter = new IntlDateFormatter(
                    'it_IT',
                    IntlDateFormatter::LONG,
                    IntlDateFormatter::NONE
                );
                $formatted_date = $formatter->format($date_obj);
            }
        }

        $formatted_time = '';
        if (!empty($ora)) {
            $time_obj = DateTime::createFromFormat('H:i', $ora);
            if ($time_obj) {
                $formatted_time = $time_obj->format('H:i');
            }
        }
        ?>
        <div class="nfb-evento-card">
            <div class="nfb-evento-date-badge">
                <?php if (!empty($data)):
                    $date_obj = DateTime::createFromFormat('Y-m-d', $data);
                    if ($date_obj):
                ?>
                    <span class="nfb-evento-day"><?php echo esc_html($date_obj->format('d')); ?></span>
                    <span class="nfb-evento-month"><?php echo esc_html(ucfirst(strftime('%b', $date_obj->getTimestamp()))); ?></span>
                <?php endif; endif; ?>
            </div>
            <div class="nfb-evento-content">
                <h4 class="nfb-evento-title"><?php the_title(); ?></h4>
                <div class="nfb-evento-meta">
                    <?php if ($luogo): ?>
                        <span class="nfb-evento-luogo">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <?php echo esc_html($luogo); ?>
                        </span>
                    <?php endif; ?>
                    <?php if ($formatted_time): ?>
                        <span class="nfb-evento-ora">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <?php echo esc_html($formatted_time); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <?php if ($content): ?>
                    <div class="nfb-evento-description">
                        <?php echo wp_kses_post($content); ?>
                    </div>
                <?php endif; ?>
                <?php if ($link): ?>
                    <div class="nfb-evento-cta">
                        <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener" class="nfb-btn nfb-btn-evento">
                            <?php _e('Vai all\'evento', 'nfb-landing'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    private function render_footer() {
        ?>
        <footer class="nfb-footer">
            <div class="nfb-container">
                <div class="nfb-footer-content">
                    <div class="nfb-footer-cta">
                        <a href="https://www.erickson.it/it/nati-fuori-binario" target="_blank" rel="noopener" class="nfb-btn nfb-btn-buy nfb-btn-large">
                            <?php _e('Acquista il libro', 'nfb-landing'); ?>
                        </a>
                    </div>
                    <p class="nfb-footer-copyright">
                        &copy;<?php echo date('Y'); ?> by Sabina Pignataro - <a href="https://natifuoribinario.it/privacy/" rel="noopener"><?php _e('Privacy Policy', 'nfb-landing'); ?></a>
                    </p>
                </div>
            </div>
        </footer>
        <a href="#nfb-header" class="nfb-scroll-top" id="nfb-scroll-top" aria-label="<?php _e('Torna su', 'nfb-landing'); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="18 15 12 9 6 15"></polyline>
            </svg>
        </a>
        <?php
    }
}
