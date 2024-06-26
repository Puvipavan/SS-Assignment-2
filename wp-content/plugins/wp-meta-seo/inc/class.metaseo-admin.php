<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');

/**
 * Class MetaSeoAdmin
 * Class that holds most of the admin functionality for Meta SEO.
 */
class MetaSeoAdmin
{

    /**
     * Current page
     *
     * @var mixed
     */
    private $pagenow;
    /**
     * Max length meta description
     *
     * @var integer
     */
    public static $desc_length = 158;
    /**
     * Max length meta title
     *
     * @var integer
     */
    public static $title_length = 60;
    /**
     * Google client
     *
     * @var object
     */
    public $client;
    /**
     * Default access to connect to google
     *
     * @var array
     */
    public $access = array(WPMS_CLIENTID, WPMS_CLIENTSECRET);
    /**
     * Error timeout
     *
     * @var integer
     */
    public $error_timeout;
    /**
     * List google analytics options
     *
     * @var array
     */
    public $ga_tracking;
    /**
     * Option to check connect status
     *
     * @var array
     */
    public $gaDisconnect;
    /**
     * Google alanytics options
     *
     * @var array
     */
    public $google_alanytics;

    /**
     * Google Tag Manager options setting
     *
     * @var Google tag manager options
     */
    public $google_tagmanager;

    /**
     * All settings for meta seo
     *
     * @var array
     */
    public $settings;

    /**
     * Google service
     *
     * @var object
     */
    public $service;

    /**
     * MetaSeoAdmin constructor.
     */
    public function __construct()
    {
        $this->pagenow = $GLOBALS['pagenow'];
        $this->setErrorTimeout();
        $this->initSettings();
        $this->initGaSettings();
        $this->initGADisconnect();
        if (!get_option('_wpms_dash_last_update', false)) {
            update_option('_wpms_dash_last_update', time());
        }

        add_action('admin_init', array($this, 'adminRedirects'));
        add_action('init', array($this, 'install'));
        add_action('admin_init', array($this, 'adminInit'));
        add_action('init', array($this, 'loadLangguage'));
        add_action('admin_menu', array($this, 'addMenuPage'));
        /**
         * Load admin js
         */
        add_action('admin_enqueue_scripts', array($this, 'loadAdminScripts'));

        if (!class_exists('MetaSeoContentListTable')) {
            require_once(WPMETASEO_PLUGIN_DIR . '/inc/class.metaseo-content-list-table.php');
        }
        add_action('added_post_meta', array('MetaSeoContentListTable', 'updateMetaSync'), 99, 4);
        add_action('updated_post_meta', array('MetaSeoContentListTable', 'updateMetaSync'), 99, 4);
        add_action('deleted_post_meta', array('MetaSeoContentListTable', 'deleteMetaSync'), 99, 4);

        if (!get_option('wpms_set_ignore', false)) {
            add_option('wpms_set_ignore', 1, '', 'yes');
        }

        add_action('wp_ajax_wpms_set_ignore', array($this, 'setIgnore'));
        if (0 === (int)get_option('blog_public')) {
            add_action('admin_notices', array($this, 'publicWarning'));
        }
        add_action('wp_enqueue_editor', array($this, 'linkTitleField'), 20);

        if (!class_exists('MetaSeoBrokenLinkTable')) {
            require_once(WPMETASEO_PLUGIN_DIR . '/inc/class.metaseo-broken-link-table.php');
        }
        add_action('post_updated', array('MetaSeoBrokenLinkTable', 'updatePost'), 10, 3);
        add_action('delete_post', array('MetaSeoBrokenLinkTable', 'deletePost'));
        add_action('edit_comment', array('MetaSeoBrokenLinkTable', 'updateComment'));
        add_action('deleted_comment', array('MetaSeoBrokenLinkTable', 'deletedComment'));
        add_action('admin_head', array('MetaSeoContentListTable', 'customStyles'));

        add_action('admin_footer', array($this, 'editorFooter'));
        add_action('wp_dashboard_setup', array($this, 'addDashboardWidgets'));
        add_action('category_add_form_fields', array($this, 'categoryField'), 10, 2);
        add_action('category_edit_form_fields', array($this, 'editCategoryFields'));
        add_action('edited_category', array($this, 'saveCategoryMeta'), 10, 2);
        add_action('create_category', array($this, 'saveCategoryMeta'), 10, 2);

        if (!class_exists('MetaSeoImageListTable')) {
            require_once(WPMETASEO_PLUGIN_DIR . '/inc/class.metaseo-image-list-table.php');
        }
        add_action('post_updated', array('MetaSeoImageListTable', 'updatePost'), 10, 3);
        add_action('delete_post', array('MetaSeoImageListTable', 'deletePost'));

        // Category meta hook
        add_action('admin_head', array('WPMSCategoryMetaTable', 'customStyles'));

        if (is_plugin_active(WPMSEO_ADDON_FILENAME)) {
            add_action('product_cat_add_form_fields', array($this, 'categoryField'));
            add_action('product_cat_edit_form_fields', array($this, 'editCategoryFields'), 10);
            add_action('created_term', array($this, 'saveCategoryMeta'), 10, 3);
            add_action('edit_term', array($this, 'saveCategoryMeta'), 10, 3);
        }
        add_action('wp_ajax_wpms', array($this, 'startProcess'));
        add_action('wp_ajax_wpms_gg_save_information', array($this, 'wpmsGGSaveInformation'));
        add_filter('wpms_the_content', array($this, 'wpmsTheContent'), 10, 2);

        $settings = get_option('_metaseo_settings');
        if (isset($settings['metaseo_removecatprefix']) && $settings['metaseo_removecatprefix'] === '1') {
            add_action('created_category', array($this, 'wpmsScheduleRewriteFlush'));
            add_action('edited_category', array($this, 'wpmsScheduleRewriteFlush'));
            add_action('delete_category', array($this, 'wpmsScheduleRewriteFlush'));
        }
    }

    /**
     * Init Meta seo settings
     *
     * @return void
     */
    public function initSettings()
    {
        $this->settings = wpmsGetDefaultSettings();
        $settings = get_option('_metaseo_settings');

        if (is_array($settings)) {
            $this->settings = array_merge($this->settings, $settings);
        }

        if ((isset($this->settings['metaseo_showtmetablock']) && (int)$this->settings['metaseo_showtmetablock'] === 1)) {
            $this->loadMetaBoxes();
        }
    }

    /**
     * Init google analytics tracking options
     *
     * @return void
     */
    public function initGaSettings()
    {
        $this->ga_tracking = array(
            'wpmsga_dash_tracking' => 1,
            'wpmsga_dash_tracking_type' => 'universal',
            'wpmsga_dash_anonim' => 0,
            'wpmsga_dash_remarketing' => 0,
            'wpmsga_event_tracking' => 0,
            'wpmsga_event_downloads' => 'zip|mp3*|mpe*g|pdf|docx*|pptx*|xlsx*|rar*',
            'wpmsga_aff_tracking' => 0,
            'wpmsga_event_affiliates' => '/out/',
            'wpmsga_hash_tracking' => 0,
            'wpmsga_author_dimindex' => 0,
            'wpmsga_pubyear_dimindex' => 0,
            'wpmsga_category_dimindex' => 0,
            'wpmsga_user_dimindex' => 0,
            'wpmsga_tag_dimindex' => 0,
            'wpmsga_speed_samplerate' => 1,
            'wpmsga_event_bouncerate' => 0,
            'wpmsga_enhanced_links' => 0,
            'wpmsga_dash_adsense' => 0,
            'wpmsga_crossdomain_tracking' => 0,
            'wpmsga_crossdomain_list' => '',
            'wpmsga_cookiedomain' => '',
            'wpmsga_cookiename' => '',
            'wpmsga_cookieexpires' => '',
            'wpmsga_track_exclude' => array(),
            'wpmsga_code_tracking' => ''
        );
    }

    /**
     * Init GTM setting
     *
     * @return void
     */
    public function initGADisconnect()
    {
        $this->gaDisconnect = array(
            'wpms_gg_service_tracking_id' => '',
            'wpms_gg_service_tracking_type' => 'universal',
            'wpmsga_code_tracking' => '',
            'wpmstm_header_code_tracking' => '',
            'wpmstm_body_code_tracking' => ''
        );
    }

    /**
     * Ajax request google tag manager containers list
     *
     * @return mixed
     */
    public function tagmanagerContainersReport()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }
        $accont_id = $_POST['accountID'];
        if (isset($accont_id) && $accont_id) {
            $accont_id = sanitize_text_field($accont_id);
            require_once(WPMETASEO_PLUGIN_DIR . 'inc/google-tag-manager/wpms-tagmanager-api.php');
            $wpmstm = new WpmsTagManagerController();
            $list_containers = $wpmstm->getListContainers($accont_id);
            $this->google_tagmanager = get_option('wpms_tagmanager_setting');
            $listsContainer = $list_containers->getContainer();
            $lists = array();
            foreach ($listsContainer as $container) {
                $lists[] = array(
                    'containerId' => $container->getContainerId(),
                    'name' => $container->getName(),
                    'path' => $container->getPath(),
                    'publicId' => $container->getPublicId(),
                    'usageContext' => $container->getUsageContext(),
                    'notes' => $container->getNotes(),
                );
            }
            $this->google_tagmanager['list_containers'] = $lists;
            update_option('wpms_tagmanager_setting', $this->google_tagmanager);
            return wp_send_json($list_containers->getContainer());
        }
    }

    /**
     * Ajax return tag manager container information
     *
     * @return mixed
     */
    public function tagmanagerContainerInfo()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }
        $container_id = $_POST['containerID'];
        if (isset($container_id) && $container_id) {
            $container_id = sanitize_text_field($container_id);
            $gg_tagmanager = get_option('wpms_tagmanager_setting');
            $gg_tagmanager_containers = $gg_tagmanager['list_containers'];
            $containerIndex = 0;
            foreach ($gg_tagmanager_containers as $container) {
                if ($container['containerId'] === $container_id) {
                    break;
                }
                $containerIndex++;
            }

            return wp_send_json($gg_tagmanager_containers[$containerIndex]);
        }
    }

    /**
     * Ajax refresh tag manager connect
     *
     * @return mixed
     */
    public function tagmanagerRefreshConnect()
    {
        // Update list accounts tag managers
        require_once(WPMETASEO_PLUGIN_DIR . 'inc/google-tag-manager/wpms-tagmanager-api.php');
        $wpmstm_controller = new WpmsTagManagerController();
        $wpmstm_listAccounts = $wpmstm_controller->getListAccounts();
        $wpmstm_accounts = $wpmstm_listAccounts->getAccount();
        if (isset($wpmstm_accounts[0])) {
            $lists_acc = array();
            foreach ($wpmstm_accounts as $account) {
                $lists_acc[] = array(
                    'accountId' => $account->getAccountId(),
                    'name' => $account->getName(),
                    'path' => $account->getPath(),
                );
            }
            $this->google_tagmanager['list_accounts'] = $lists_acc;
            update_option('wpms_tagmanager_setting', $this->google_tagmanager);
            return wp_send_json($this->google_tagmanager['list_accounts']);
        } else {
            return false;
        }
    }

    /**
     * Admin init
     *
     * @return void
     */
    public function adminInit()
    {
        $this->createDatabase();
        $this->updateLinksTable();

        // Column Content.
        $this->registerPostColumns();
        add_filter('metaseo_seokeywords_details_column', array($this, 'getColumnSeoKeywordsDetails'), 5);
    }

    /**
     * Add category meta field
     *
     * @return void
     */
    public function categoryField()
    {
        wp_enqueue_style('wpms-tippy-style');
        wp_enqueue_script('wpms-tippy-core');
        wp_enqueue_script('wpms-tippy');
        wp_enqueue_script('wpms-my-tippy');

        wp_enqueue_style('wpms-category-field');
        wp_enqueue_script('wpms-category-field');
        // this will add the custom meta field to the add new term page
        ?>
        <div class="form-field">
            <label class="wpms_custom_cat_field"
                   data-tippy="<?php esc_attr_e('This is the title of your content that may be displayed in search engine
                    results (meta title). By default it’s the content title (page title, post title…).
                     69 characters max allowed.', 'wp-meta-seo') ?>">
                <?php esc_html_e('Search engine title', 'wp-meta-seo'); ?>
            </label>
            <label>
                <textarea name="wpms_category_metatitle" class="wpms_category_metatitle"></textarea>
                <br/>
            </label>
            <div class="cat-title-len"><?php echo esc_html(MPMSCAT_TITLE_LENGTH); ?></div>
        </div>

        <?php
        $settings = get_option('_metaseo_settings');
        if (isset($settings['metaseo_showkeywords']) && (int)$settings['metaseo_showkeywords'] === 1) :
            ?>
            <div class="form-field" style="margin-top: 20px;margin-bottom: 20px;">
                <label class="wpms_custom_cat_field"
                       data-tippy="<?php esc_attr_e('This is the keywords of your content that may be
                        displayed in search engine results (meta keywords).', 'wp-meta-seo') ?>">
                    <?php esc_html_e('Search engine keywords', 'wp-meta-seo'); ?>
                </label>
                <label>
                    <textarea name="wpms_category_metakeywords" class="wpms_cat_keywords"></textarea><br/>
                </label>
                <div class="cat-keywords-len"><?php echo esc_html(MPMSCAT_KEYWORDS_LENGTH); ?></div>
            </div>
        <?php endif; ?>
        <div class="form-field" style="margin-top: 20px;margin-bottom: 20px;">
            <label for="extra1" class="wpms_custom_cat_field"
                   data-tippy="<?php esc_attr_e('This is the title of your content that may be displayed in search
                    engine results (meta title). By default it’s the content title (page title, post title…).
                     69 characters max allowed.', 'wp-meta-seo') ?>">
                <?php esc_html_e('Search engine description', 'wp-meta-seo'); ?>
            </label>
            <label>
                <textarea name="wpms_category_metadesc" class="wpms_category_metadesc"></textarea><br/>
                <input type="hidden" name="wpms_nonce" value="<?php echo esc_attr(wp_create_nonce('wpms_nonce')) ?>">
            </label>
            <div class="cat-desc-len"><?php echo esc_html(MPMSCAT_DESC_LENGTH); ?></div>
        </div>
        <?php
        if (isset($settings['metaseo_canonical']) && (int)$settings['metaseo_canonical'] === 1) : ?>
            <div class="form-field" style="margin-top: 20px;margin-bottom: 20px;">
                <label class="wpms_custom_cat_field"
                       data-tippy="<?php esc_attr_e('Put the canonical URL which this page should point to. By default, it\'s the permalink', 'wp-meta-seo') ?>">
                    <?php esc_html_e('Canonical URL', 'wp-meta-seo'); ?>
                </label>
                <label>
                    <textarea name="wpms_category_canonical" class="wpms_category_canonical"></textarea><br/>
                </label>
            </div>
        <?php endif;
    }

    /**
     * Save category meta
     *
     * @param integer $term_id Current category id
     *
     * @return void
     */
    public function saveCategoryMeta($term_id)
    {
        global $pagenow;
        // phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce used in next lines
        if ($pagenow === 'edit-tags.php' || (isset($_POST['action'], $_POST['screen']) && $_POST['action'] === 'add-tag' && ($_POST['screen'] === 'edit-category' || $_POST['screen'] === 'edit-product_cat'))) {
            if (isset($_POST['taxonomy']) && ($_POST['taxonomy'] === 'product_cat' || $_POST['taxonomy'] === 'category')) {
                if (empty($_POST['wpms_nonce'])
                    || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
                    die();
                }

                if (isset($_POST['wpms_category_metatitle'])) {
                    update_term_meta($term_id, 'wpms_category_metatitle', $_POST['wpms_category_metatitle']);
                }

                if (isset($_POST['wpms_category_metadesc'])) {
                    update_term_meta($term_id, 'wpms_category_metadesc', $_POST['wpms_category_metadesc']);
                }

                $settings = get_option('_metaseo_settings');
                if (isset($settings['metaseo_showkeywords']) && (int)$settings['metaseo_showkeywords'] === 1) {
                    if (isset($_POST['wpms_category_metakeywords'])) {
                        update_term_meta($term_id, 'wpms_category_metakeywords', $_POST['wpms_category_metakeywords']);
                    }
                }

                if (isset($settings['metaseo_canonical']) && (int)$settings['metaseo_canonical'] === 1) {
                    if (isset($_POST['wpms_category_canonical'])) {
                        // Set link to field
                        $canonical = self::convertCanonicalUrlToSave($_POST['wpms_category_canonical']);

                        update_term_meta($term_id, 'wpms_category_canonical', $canonical);
                    }
                }
            }
        }
        //phpcs:enable
    }

    /**
     * Add extra fields to category edit form callback function
     *
     * @param object $tag Current category
     *
     * @return void
     */
    public function editCategoryFields($tag)
    {
        wp_enqueue_style('wpms-tippy-style');
        wp_enqueue_script('wpms-tippy-core');
        wp_enqueue_script('wpms-tippy');
        wp_enqueue_script('wpms-my-tippy');

        wp_enqueue_style('wpms-category-field');
        wp_enqueue_script('wpms-category-field');
        $t_id = $tag->term_id;
        $metatitle = get_term_meta($t_id, 'wpms_category_metatitle', true);
        $metadesc = get_term_meta($t_id, 'wpms_category_metadesc', true);
        $metakeywords = get_term_meta($t_id, 'wpms_category_metakeywords', true);
        $metacanonical = get_term_meta($t_id, 'wpms_category_canonical', true);
        $metacanonical = self::convertCanonicalUrlToDisplay($metacanonical);
        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label class="wpms_custom_cat_field" data-tippy="<?php esc_attr_e('This is the title of your content that may
                 be displayed in search engine results (meta title). By default it’s the content title
                  (page title, post title…).69 characters max allowed.', 'wp-meta-seo') ?>">
                    <?php esc_html_e('Search engine title', 'wp-meta-seo'); ?>
                </label>
            </th>
            <td>
                <label>
                    <?php if ((!empty($metatitle))) : ?>
                        <textarea name="wpms_category_metatitle"
                                  class="wpms_category_metatitle"><?php echo esc_textarea($metatitle); ?></textarea>
                    <?php else : ?>
                        <textarea name="wpms_category_metatitle"
                                  class="wpms_category_metatitle"></textarea>
                    <?php endif; ?>
                    <br/>
                </label>
                <div class="cat-title-len">
                    <?php
                    echo esc_html($metatitle ? MPMSCAT_TITLE_LENGTH - strlen($metatitle) : MPMSCAT_TITLE_LENGTH);
                    ?>
                </div>
            </td>
        </tr>

        <?php
        $settings = get_option('_metaseo_settings');
        if (isset($settings['metaseo_showkeywords']) && (int)$settings['metaseo_showkeywords'] === 1) :
            ?>
            <tr class="form-field">
                <th scope="row" valign="top">
                    <label for="extra1" class="wpms_custom_cat_field"
                           data-tippy="<?php esc_attr_e('This is the keywords of your content that may be
                            displayed in search engine results (meta keywords).', 'wp-meta-seo') ?>">
                        <?php esc_html_e('Search engine keywords', 'wp-meta-seo'); ?>
                    </label>
                </th>
                <td>
                    <label>
                        <?php if ((!empty($metakeywords))) : ?>
                            <textarea name="wpms_category_metakeywords"
                                      class="wpms_cat_keywords"><?php echo esc_textarea($metakeywords); ?></textarea>
                        <?php else : ?>
                            <textarea name="wpms_category_metakeywords"
                                      class="wpms_cat_keywords"></textarea>
                        <?php endif; ?>
                        <br/>
                    </label>

                    <div class="cat-keywords-len">
                        <?php
                        echo esc_html($metakeywords ? MPMSCAT_KEYWORDS_LENGTH - strlen($metakeywords) : MPMSCAT_KEYWORDS_LENGTH);
                        ?>
                    </div>
                </td>
            </tr>
        <?php endif; ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="extra1" class="wpms_custom_cat_field"
                       data-tippy="<?php esc_attr_e('This is the title of your content that may be displayed in
                        search engine results (meta title). By default it’s the content title (page title, post title…).
                         69 characters max allowed.', 'wp-meta-seo') ?>">
                    <?php esc_html_e('Search engine description', 'wp-meta-seo'); ?>
                </label>
            </th>
            <td>
                <label>
                    <?php if ((!empty($metadesc))) : ?>
                        <textarea name="wpms_category_metadesc"
                                  class="wpms_category_metadesc"><?php echo esc_textarea($metadesc); ?></textarea>
                    <?php else : ?>
                        <textarea name="wpms_category_metadesc"
                                  class="wpms_category_metadesc"></textarea>
                    <?php endif; ?>
                    <br/>
                </label>
                <input type="hidden" name="wpms_nonce" value="<?php echo esc_attr(wp_create_nonce('wpms_nonce')) ?>">
                <div class="cat-desc-len">
                    <?php echo esc_html($metadesc ? MPMSCAT_DESC_LENGTH - strlen($metadesc) : MPMSCAT_DESC_LENGTH); ?>
                </div>
            </td>
        </tr>
        <?php
        if (isset($settings['metaseo_canonical']) && (int)$settings['metaseo_canonical'] === 1) : ?>
            <tr class="form-field">
                <th scope="row" valign="top">
                    <label for="extra1" class="wpms_custom_cat_field"
                           data-tippy="<?php esc_attr_e('Put the canonical URL which this page should point to. By default, it\'s the permalink', 'wp-meta-seo') ?>">
                        <?php esc_html_e('Canonical URL', 'wp-meta-seo'); ?>
                    </label>
                </th>
                <td>
                    <label>
                        <?php if ((!empty($metacanonical))) : ?>
                            <textarea name="wpms_category_canonical"
                                      class="wpms_category_canonical"><?php echo esc_textarea($metacanonical); ?></textarea>
                        <?php else : ?>
                            <textarea name="wpms_category_canonical"
                                      class="wpms_category_canonical"></textarea>
                        <?php endif; ?>
                        <br/>
                    </label>
                </td>
            </tr>
        <?php endif;
    }

    /**
     * Function that outputs the contents of the dashboard widget
     *
     * @return void
     */
    public function dashboardWidget()
    {
        wp_enqueue_style('wpms-tippy-style');
        wp_enqueue_style('wpms-mytippy-style');
        wp_enqueue_script('wpms-tippy-core');
        wp_enqueue_script('wpms-tippy');
        wp_enqueue_script('wpms-my-tippy');
        wp_enqueue_style('wpms-dashboard-widgets');
        wp_enqueue_script(
            'wpms-dashboard-widgets',
            plugins_url('assets/js/dashboard_widgets/dashboard_widgets.js', dirname(__FILE__)),
            array('jquery'),
            WPMSEO_VERSION
        );
        require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/dashboard-widgets/dashboard_widgets.php');
    }

    /**
     * Generate dasboard Analytics widget
     *
     * @return void
     */
    public function dashboardAnalyticsWidget()
    {
        // Jsapi
        $lang = get_bloginfo('language');
        $lang = explode('-', $lang);
        $lang = $lang[0];
        wp_enqueue_script(
            'wpmsgooglejsapi',
            'https://www.google.com/jsapi?autoload=%7B%22modules%22%3A%5B%7B%22name%22%3A%22visualization%22%2C%22version%22%3A%221%22%2C%22language%22%3A%22' . $lang . '%22%2C%22packages%22%3A%5B%22corechart%22%2C%20%22imagechart%22%2C%20%22table%22%2C%20%22orgchart%22%2C%20%22geochart%22%5D%7D%5D%7D',
            array(),
            null
        );
        // WPMS style
        wp_enqueue_style(
            'wpms-dashboard-analytics-widgets',
            plugins_url('assets/css/dashboard-widgets/analytics-widgets.css', dirname(__FILE__)),
            array(),
            WPMSEO_VERSION
        );
        // WPMS script
        wp_enqueue_script(
            'wpms-dashboard-analytics-widgets',
            plugins_url('assets/js/dashboard_widgets/analytics-widgets.js', dirname(__FILE__)),
            array('jquery'),
            WPMSEO_VERSION,
            true
        );

        wp_localize_script('wpms-dashboard-analytics-widgets', 'wpmsDashboardAnalytics', $this->localizeDashoardWidgets());

        $requestDates = array(
            array('value' => 'today', 'html' => 'TODAY'),
            array('value' => 'yesterday', 'html' => 'YESTERDAY'),
            array('value' => '7daysAgo', 'html' => 'LAST 7 DAYS'),
            array('value' => '14daysAgo', 'html' => 'LAST 14 DAYS'),
            array('value' => '30daysAgo', 'html' => 'LAST 30 DAYS'),
            array('value' => '90daysAgo', 'html' => 'LAST 90 DAYS'),
            array('value' => '365daysAgo', 'html' => 'ONE YEAR'),
            array('value' => '1095daysAgo', 'html' => 'THREE YEARS'),
        );
        $requestQuery = array(
            array('value' => 'sessions', 'html' => 'SESSIONS'),
            array('value' => 'users', 'html' => 'USERS'),
            array('value' => 'pageviews', 'html' => 'PAGE VIEWS'),
            array('value' => 'visitBounceRate', 'html' => 'ENGAGEMENT'),
            array('value' => 'organicSearches', 'html' => 'TRANSACTIONS'),
            array('value' => 'contentpages', 'html' => 'PAGES'),
            array('value' => 'locations', 'html' => 'LOCATION'),
            array('value' => 'referrers', 'html' => 'REFERRERS'),
            array('value' => 'searches', 'html' => 'SEARCHES'),
            array('value' => 'channelGrouping', 'html' => 'TRAFFIC'),
            array('value' => 'deviceCategory', 'html' => 'TECHNOLOGY')
        );
        $google_analytics =  get_option('wpms_google_alanytics');
        if (!empty($google_analytics['tableid_jail'])) {
            require_once WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmstools.php';
            $profile = WpmsGaTools::getSelectedProfile($google_analytics['profile_list'], $google_analytics['tableid_jail']);
            if (!empty($profile[4]) && $profile[4] === 'UA') {
                $requestQuery[3] = array('value' => 'visitBounceRate', 'html' => 'BOUNCE RATE');
                $requestQuery[4] = array('value' => 'organicSearches', 'html' => 'ORGANIC');
            }
        }

        $selectedDate = 'today';
        $selectedQuery = 'sessions';
        if (!empty($google_analytics['dashboard_analytics_widgets'])) {
            $selectedDate = $google_analytics['dashboard_analytics_widgets']['requestDate'];
            $selectedQuery = $google_analytics['dashboard_analytics_widgets']['requestQuery'];
        }

        require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/dashboard-widgets/analytics_widgets.php');
    }

    /**
     * Function used in the action hook
     *
     * @return void
     */
    public function addDashboardWidgets()
    {
        wp_add_dashboard_widget(
            'wpms_dashboard_widget',
            esc_html__('WP Meta SEO: Quick SEO preview', 'wp-meta-seo'),
            array(
                $this,
                'dashboardWidget'
            )
        );

        // Add Google Analytics Overview dashboard widget
        wp_add_dashboard_widget(
            'wpms_dashboard_analytics_widget',
            esc_html__('Google Analytics Overview', 'wp-meta-seo'),
            array(
                $this,
                'dashboardAnalyticsWidget'
            )
        );
    }

    /**
     * Localize dashboard widget
     *
     * @return array
     */
    public function localizeDashoardWidgets()
    {
        return array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'wpms_nonce' => wp_create_nonce('wpms_nonce'),
            'wpms_security' => wp_create_nonce('dashboard_analytics_widgets_security'),
            'colorVariations' => array(
                esc_attr('#1e73be'),
                esc_attr('#0459a4'),
                esc_attr('#378cd7'),
                esc_attr('#51a6f1'),
                esc_attr('#00408b'),
                esc_attr('#6abfff'),
                esc_attr('#002671')
            ),
            'region' => false,
            'language' => get_bloginfo('language'),
            'viewList' => false
        );
    }

    /**
     * Create link dialog
     *
     * @return void
     */
    public function editorFooter()
    {
        if (!class_exists('_WP_Editors', false)) {
            require_once ABSPATH . 'wp-includes/class-wp-editor.php';
            _WP_Editors::wp_link_dialog();
        }
    }

    /**
     * Create or update 404 page
     *
     * @param string $title    Old page title
     * @param string $newtitle New page title
     *
     * @return void
     */
    public function create404Page($title, $newtitle)
    {
        global $wpdb;
        $post_if = $wpdb->get_results($wpdb->prepare(
            'SELECT * FROM ' . $wpdb->prefix . 'posts
                     WHERE post_title = %s AND post_excerpt = %s AND post_type = %s',
            array($title, 'metaseo_404_page', 'page')
        ));

        if (empty($post_if)) {
            $content = '<div class="wall"
 style="background-color: #F2F3F7; border: 30px solid #fff; width: 90%; height: 90%; margin: 0 auto;">

        <h1 style="text-align: center; font-family:\'open-sans\', arial;
         color: #444; font-size: 60px; padding: 50px;">ERROR 404 <br />-<br />NOT FOUND</h1>
    <p style="text-align: center; font-family:\'open-sans\', arial; color: #444;
     font-size: 40px; padding: 20px; line-height: 55px;">
    // You may have mis-typed the URL,<br />
    // Or the page has been removed,<br />
    // Actually, there is nothing to see here...</p>
        <p style="text-align: center;"><a style=" font-family:\'open-sans\', arial; color: #444;
         font-size: 20px; padding: 20px; line-height: 30px; text-decoration: none;"
          href="' . get_home_url() . '"><< Go back to home page >></a></p>
    </div>';
            $_page404 = array(
                'post_title' => $newtitle,
                'post_content' => $content,
                'post_status' => 'publish',
                'post_excerpt' => 'metaseo_404_page',
                'post_type' => 'page'
            );
            wp_insert_post($_page404);
        } else {
            $my_post = array(
                'ID' => $post_if[0]->ID,
                'post_title' => $newtitle
            );

            wp_update_post($my_post);
        }
    }

    /**
     * Update links table
     *
     * @return void
     */
    public function updateLinksTable()
    {
        global $wpdb;
        $option_v = 'metaseo_db_version3.7.2';
        $db_installed = get_option($option_v, false);
        if (!$db_installed) {
            $wpdb->query('ALTER TABLE ' . $wpdb->prefix . 'wpms_links MODIFY `link_url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL');
            update_option($option_v, true);
        }

        // Add index for wpms links
        $option_v = 'metaseo_db_version4.0.4';
        $db_installed = get_option($option_v, false);
        if (!$db_installed) {
            // Add index for wpms_links table;
            $wpdb->query('ALTER TABLE ' . $wpdb->prefix . 'wpms_links ADD INDEX linkurl(link_url(256))');
            $wpdb->query('ALTER TABLE ' . $wpdb->prefix . 'wpms_links ADD INDEX typeurl(type(50))');
            $wpdb->query('ALTER TABLE ' . $wpdb->prefix . 'wpms_links ADD INDEX sourceid(source_id)');

            // Add index for metaseo_images table;
            $wpdb->query('ALTER TABLE ' . $wpdb->prefix . 'metaseo_images ADD INDEX postid(post_id)');
            // Update option metaseo_db
            update_option($option_v, true);
        }
    }

    /**
     * Create wpms_links table
     *
     * @return void
     */
    public function createDatabase()
    {
        global $wpdb;
        $option_v = 'metaseo_db_version3.3.0';
        $db_installed = get_option($option_v, false);
        if (!$db_installed) {
            // create table wpms_links
            $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'wpms_links(
                    `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
                    `link_url` text CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
                    `link_final_url` text CHARACTER SET latin1 COLLATE latin1_general_cs,
                    `link_url_redirect` text CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
                    `link_text` text NOT NULL DEFAULT "",
                    `source_id` int(20) DEFAULT "0",
                    `type` varchar(100) DEFAULT "",
                    `status_code` varchar(100) DEFAULT "",
                    `status_text` varchar(250) DEFAULT "",
                    `hit` int(20) NOT NULL DEFAULT "1",
                    `redirect` tinyint(1) NOT NULL DEFAULT "0",
                    `broken_indexed` tinyint(1) unsigned NOT NULL DEFAULT "0",
                    `broken_internal` tinyint(1) unsigned NOT NULL DEFAULT "0",
                    `warning` tinyint(1) unsigned NOT NULL DEFAULT "0",
                    `dismissed` tinyint(1) NOT NULL DEFAULT "0",
                    PRIMARY KEY  (id))';


            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            $row = $wpdb->get_results($wpdb->prepare(
                'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_name = %s AND column_name = %s  AND TABLE_SCHEMA = %s',
                array($wpdb->prefix . 'wpms_links', 'follow', $wpdb->dbname)
            ));

            if (empty($row)) {
                $wpdb->query('ALTER TABLE ' . $wpdb->prefix . 'wpms_links ADD follow tinyint(1) DEFAULT 1');
            }

            $row = $wpdb->get_results($wpdb->prepare(
                'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_name = %s AND column_name = %s  AND TABLE_SCHEMA = %s',
                array($wpdb->prefix . 'wpms_links', 'meta_title', $wpdb->dbname)
            ));

            if (empty($row)) {
                $wpdb->query('ALTER TABLE ' . $wpdb->prefix . 'wpms_links ADD meta_title varchar(250) DEFAULT ""');
            }

            $row = $wpdb->get_results($wpdb->prepare(
                'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_name = %s AND column_name = %s  AND TABLE_SCHEMA = %s',
                array($wpdb->prefix . 'wpms_links', 'internal', $wpdb->dbname)
            ));

            if (empty($row)) {
                $wpdb->query('ALTER TABLE ' . $wpdb->prefix . 'wpms_links ADD internal tinyint(1) DEFAULT 1');
            }

            // create page 404
            $this->create404Page('WP Meta SEO 404 Page', '404 error page');
            // create sitemap page
            $post_if = $wpdb->get_var(
                $wpdb->prepare('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'posts
                 WHERE post_title = %s AND post_excerpt = %s AND post_type = %s', array(
                    'WPMS HTML Sitemap',
                    'metaseo_html_sitemap',
                    'page'
                ))
            );
            if ($post_if < 1) {
                $_sitemap_page = array(
                    'post_title' => 'WPMS HTML Sitemap',
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_excerpt' => 'metaseo_html_sitemap',
                    'post_type' => 'page',
                );
                wp_insert_post($_sitemap_page);
            }

            update_option($option_v, true);
        }


        $option_v = 'metaseo_db_version3.7.3';
        $db_installed = get_option($option_v, false);
        if (!$db_installed) {
            // create page 404
            $this->create404Page('404 error page', '404 Error, content does not exist anymore');
            update_option($option_v, true);
        }

        $option_v = 'metaseo_db_version4.0.0';
        $db_installed = get_option($option_v, false);
        if (!$db_installed) {
            $row = $wpdb->get_results($wpdb->prepare(
                'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_name = %s AND column_name = %s  AND TABLE_SCHEMA = %s',
                array($wpdb->prefix . 'wpms_links', 'referrer', $wpdb->dbname)
            ));

            if (empty($row)) {
                $wpdb->query('ALTER TABLE ' . $wpdb->prefix . 'wpms_links ADD referrer text DEFAULT ""');
            }
            update_option($option_v, true);
        }

        $option_v = 'metaseo_db_version4.0.4';
        $db_installed = get_option($option_v, false);
        $wpms_db_version = get_option('metaseo_db_version', false);
        // update plugin v4.3.5
        if ($db_installed && !$wpms_db_version) {
            // Get old settings
            $gaDisconnect = get_option('_metaseo_ggtracking_disconnect_settings');

            // Update value to new ga parameters
            if ($gaDisconnect) {
                $this->gaDisconnect['wpms_gg_service_tracking_id'] = $gaDisconnect['wpms_ga_uax_reference'];
                $this->gaDisconnect['wpms_gg_service_tracking_type'] = $gaDisconnect['wpmsga_dash_tracking_type'];
                $this->gaDisconnect['wpmsga_code_tracking'] = $gaDisconnect['wpmsga_code_tracking'];
                $this->gaDisconnect['wpmstm_header_code_tracking'] = '';
                $this->gaDisconnect['wpmstm_body_code_tracking'] = '';
                update_option('_metaseo_ggtracking_disconnect_settings', $this->gaDisconnect);
            }
            update_option('metaseo_db_version', WPMSEO_VERSION);
        }
    }

    /**
     * Add field title in dialog link when edit a link
     *
     * @return void
     */
    public function linkTitleField()
    {
        if (isset($this->settings['metaseo_linkfield']) && (int)$this->settings['metaseo_linkfield'] === 1) {
            wp_enqueue_script(
                'wpmslinkTitle',
                plugins_url('assets/js/wpms-link-title-field.js', dirname(__FILE__)),
                array('wplink'),
                WPMSEO_VERSION,
                true
            );
            wp_localize_script('wpmslinkTitle', 'wpmsLinkTitleL10n', array(
                'titleLabel' => esc_html__('Title', 'wp-meta-seo'),
            ));
        }
    }

    /**
     * Update option wpms_set_ignore
     *
     * @return void
     */
    public function setIgnore()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }
        if (!current_user_can('manage_options')) {
            wp_send_json(false);
        }
        update_option('wpms_set_ignore', 0);
        wp_send_json(true);
    }

    /**
     * Render message error when disable search engines from indexing this site
     *
     * @return void
     */
    public function publicWarning()
    {
        if ((function_exists('is_network_admin') && is_network_admin())) {
            return;
        }

        if ((int)get_option('wpms_set_ignore') === 0) {
            return;
        }

        printf(
            '<div id="robotsmessage" class="error">
                            <p>
                                    <strong>%1$s</strong>
                                    %2$s
                                    <a href="javascript:wpmsIgnore(\'wpms_public_warning\',\'robotsmessage\',\'%3$s\');"
                                     class="button">%4$s</a>
                            </p>
                    </div>',
            esc_html__('Your website is not indexed by search engine because of your WordPress settings.', 'wp-meta-seo'),
            sprintf(
                esc_html__('%1$sFix it now%2$s', 'wp-meta-seo'),
                sprintf('<a href="%s">', esc_url(admin_url('options-reading.php'))),
                '</a>'
            ),
            esc_js(wp_create_nonce('wpseo-ignore')),
            esc_html__('OK I know that.', 'wp-meta-seo')
        );
    }

    /**
     * Loads translated strings.
     *
     * @return void
     */
    public function loadLangguage()
    {
        load_plugin_textdomain(
            'wp-meta-seo',
            false,
            dirname(plugin_basename(__FILE__)) . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR
        );
    }

    /**
     * Handle redirects to setup/welcome page after install and updates.
     *
     * For setup wizard, transient must be present, the user must have access rights, and we must ignore the network/bulk plugin updaters.
     *
     * @return void
     */
    public function adminRedirects()
    {
        // Disable all admin notice for page belong to plugin
        add_action('admin_print_scripts', function () {
            global $wp_filter;
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
            if ((!empty($_GET['page']) && in_array($_GET['page'], array('wpms-setup', 'metaseo_settings', 'metaseo_console')))) {
                if (is_user_admin()) {
                    if (isset($wp_filter['user_admin_notices'])) {
                        unset($wp_filter['user_admin_notices']);
                    }
                } elseif (isset($wp_filter['admin_notices'])) {
                    unset($wp_filter['admin_notices']);
                }
                if (isset($wp_filter['all_admin_notices'])) {
                    unset($wp_filter['all_admin_notices']);
                }
            }
        });

        // Setup wizard redirect
        if (is_null(get_option('_wpmf_activation_redirect', null)) && is_null(get_option('wpms_version', null))) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View request, no action
            if ((!empty($_GET['page']) && in_array($_GET['page'], array('wpms-setup')))) {
                return;
            }
            update_option('wpms_version', WPMSEO_VERSION);
            wp_safe_redirect(admin_url('index.php?page=wpms-setup'));
            exit;
        }
    }

    /**
     * Includes WP Media Folder setup
     *
     * @return void
     */
    public function install()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View request, no action
        if (!empty($_GET['page'])) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View request, no action
            switch ($_GET['page']) {
                case 'wpms-setup':
                    require_once WPMETASEO_PLUGIN_DIR . '/inc/install-wizard/install-wizard.php';
                    break;
            }
        }
    }

    /**
     * Create field analysis
     *
     * @param string $data_title   Title
     * @param string $alt          Alt text
     * @param string $dashicon     Type
     * @param string $label        Label
     * @param string $value_hidden Value
     *
     * @return string
     */
    public function createFieldAnalysis($data_title, $alt, $dashicon, $label, $value_hidden)
    {
        $output = '<div class="metaseo_analysis metaseo_tool" data-title="' . esc_attr($data_title) . '" data-tippy="' . esc_attr($alt) . '">';
        if ($dashicon === 'done') {
            $output .= '<i class="metaseo-dashicons material-icons dashicons-before icons-mboxdone">done</i>';
        } else {
            $output .= '<i class="metaseo-dashicons material-icons dashicons-before icons-mboxwarning">error_outline</i>';
        }

        $output .= esc_html($label);
        $output .= '</div>';
        $output .= '<input type="hidden" class="wpms_analysis_hidden"
         name="' . esc_attr('wpms[' . $data_title . ']') . '" value="' . esc_attr($value_hidden) . '">';
        return $output;
    }

    /**
     * Get content
     *
     * @param string  $content Post content
     * @param integer $post_id Post ID
     *
     * @return string
     */
    public function wpmsTheContent($content, $post_id)
    {
        $content = apply_filters(
            'the_content',
            $content,
            $post_id
        );

        if (is_plugin_active('oxygen/functions.php')) {
            $shortcodes = get_post_meta($post_id, 'ct_builder_shortcodes', true);
            $cf = do_shortcode($shortcodes);
            $content = $content . $cf;
        }

        return $content;
    }

    /**
     * Get all the values of an array
     *
     * @param array $array List array to get value
     *
     * @return array|string
     */
    public function getValues($array)
    {
        if (is_array($array)) {
            $array = array_values($array);
        }

        return $array;
    }

    /**
     * Check strpos with list array
     *
     * @param string  $haystack String to compare
     * @param array   $needle   List array need compare
     * @param integer $offset   Offset value
     *
     * @return boolean
     */
    public function strPosArray($haystack, $needle, $offset = 0)
    {
        if (!is_array($needle)) {
            $needle = array($needle);
        }
        foreach ($needle as $query) {
            // stop on first true result
            if (strpos($haystack, $query, $offset) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Recursion to get specific value of file
     *
     * @param array $fields List specific value of field
     *
     * @return string
     */
    public function getACFData($fields)
    {
        $values = $this->getValues($fields);
        $data = '';

        if (is_array($values)) {
            foreach ($values as $item) {
                switch (gettype($item)) {
                    case 'array':
                        $data .= $this->getACFData($item);
                        break;
                    case 'string':
                        // Check link
                        if (filter_var($item, FILTER_VALIDATE_URL)) {
                            $checkImageLink = $this->strPosArray($item, array('.jpg', '.png', '.jpeg', 'svg', 'gif'));
                            if (!$checkImageLink) {
                                $item = '<a href="' . $item . '">' . $item . '</a>';
                            }
                        }
                        $data .= ' ' . $item;
                        break;
                }
            }
        }
        if (is_string($values)) {
            $data .= ' ' . $values;
        }
        return $data;
    }

    /**
     * Inject ACF field to content
     *
     * @param string  $content Post content
     * @param integer $post_id Post ID
     *
     * @return string
     */
    public function injectAcfField($content, $post_id)
    {
        if (class_exists('ACF')) {
            $fields = get_field_objects($post_id);

            if (!empty($fields)) {
                $inject = '';
                foreach ($fields as $name => $field) {
                    if ($field['type'] === 'image') {
                        $size = $field['preview_size'];
                        if (is_array($field['value'])) {
                            $caption = $field['value']['caption'];
                            // Get image link if field is array
                            if ($caption) {
                                $inject .= '<div class="wp-caption">';
                            }
                            $inject = '<a href="' . $field['value']['url'] . '" title="' . $field['value']['title'] . '">';
                            $inject .= '<img src="' . $field['value']['sizes'][$size] . '" alt="' . $field['value']['alt'] . '" width="' . $field['value']['sizes'][$size . '-width'] . '" height="' . $field['value']['sizes'][$size . '-height'] . '" />';
                            $inject .= '</a>';
                            if ($caption) {
                                $inject .= '<p class="wp-caption-text">' . $caption . '</p>';
                            }
                            $inject .= '</div>';
                        } elseif (is_string($field['value'])) {
                            $inject = '<img src="' . $field['value'] . '" />';
                        } else {
                            $inject = wp_get_attachment_image($field['value'], $size);
                        }
                    } elseif ($field['type'] === 'link') {
                        // Get link if field is array
                        if (is_array($field['value'])) {
                            $inject = '<a class="link-url" href="' . $field['value']['url'] . '" target="' . ($field['value']['target'] ? $field['value']['target'] : '_self') . '">' . esc_html($field['value']['title']) . '</a>';
                        } else {
                            $inject = '<a class="link-url" href="' . $field['value'] . '">' . esc_html($field['value']) . '</a>';
                        }
                    } else {
                        $inject = $this->getACFData($field['value']);
                    }

                    $content .= ' ' . $inject;
                }
            }
        }

        return $content;
    }

    /**
     * Inject Woocommerce short description to content
     *
     * @param string  $content Post content
     * @param integer $post_id Post ID
     *
     * @return string
     */
    public function injectWooCommerce($content, $post_id)
    {
        if (class_exists('WooCommerce')) {
            $post = get_post($post_id);

            if (!empty($post->post_excerpt)) {
                $content .= '' . $post->post_excerpt;
            }
        }

        return $content;
    }

    /**
     * Ajax load page analysis
     *
     * @return void
     */
    public function reloadAnalysis()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (!current_user_can('edit_posts')) {
            wp_send_json(array('status' => false));
        }
        $tooltip_page = array();
        $tooltip_page['title_in_heading'] = esc_attr__('Check if a word of this content title
         is also in a title heading (h1, h2...)', 'wp-meta-seo');
        $tooltip_page['title_in_content'] = esc_attr__('Check if a word of this content
         title is also in the text', 'wp-meta-seo');
        $tooltip_page['page_url'] = esc_attr__('Does the page title match with the permalink (URL structure)', 'wp-meta-seo');
        $tooltip_page['meta_title'] = esc_attr__('Is the meta title of this page filled?', 'wp-meta-seo');
        $tooltip_page['meta_desc'] = esc_attr__('Is the meta description of this page filled?', 'wp-meta-seo');
        $tooltip_page['image_resize'] = esc_attr__('Check for image HTML resizing in content
         (usually image resized using handles)', 'wp-meta-seo');
        $tooltip_page['image_alt'] = esc_attr__('Check for image Alt text and title', 'wp-meta-seo');
        if (empty($_POST['datas'])) {
            wp_send_json(false);
        }

        if (isset($_POST['datas']['post_id']) && empty($_POST['datas']['first_load'])) {
            update_post_meta($_POST['datas']['post_id'], 'wpms_validate_analysis', '');
        }

        $meta_analysis = get_post_meta((int)$_POST['datas']['post_id'], 'wpms_validate_analysis', true);
        $check = 0;
        $output = '';

        // title heading
        $words_post_title = preg_split(
            '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
            strtolower($_POST['datas']['title']),
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        // do shortcode js_composer plugin
        if (is_plugin_active('js_composer_theme/js_composer.php')) {
            add_shortcode('mk_fancy_title', 'vc_do_shortcode');
        }

        $content = apply_filters(
            'wpms_the_content',
            '<div>' . html_entity_decode(stripcslashes($_POST['datas']['content']), ENT_COMPAT, 'UTF-8') . '</div>',
            $_POST['datas']['post_id']
        );

        $content = $this->injectAcfField($content, $_POST['datas']['post_id']);

        $content = $this->injectWooCommerce($content, $_POST['datas']['post_id']);

        if (isset($_POST['datas']['first_load']) && !empty($meta_analysis) && !empty($meta_analysis['heading_title'])) {
            $output .= $this->createFieldAnalysis(
                'heading_title',
                $tooltip_page['title_in_heading'],
                'done',
                esc_html__('Page title word in content heading', 'wp-meta-seo'),
                1
            );
            $check++;
        } else {
            if ($content === '') {
                $output .= $this->createFieldAnalysis(
                    'heading_title',
                    $tooltip_page['title_in_heading'],
                    'warning',
                    esc_html__('Page title word in content heading', 'wp-meta-seo'),
                    0
                );
            } else {
                // Extracting the specified elements from the web page
                $tags_h1 = wpmsExtractTags($content, 'h1', false, true);
                $tags_h2 = wpmsExtractTags($content, 'h2', false, true);
                $tags_h3 = wpmsExtractTags($content, 'h3', false, true);
                $tags_h4 = wpmsExtractTags($content, 'h4', false, true);
                $tags_h5 = wpmsExtractTags($content, 'h5', false, true);
                $tags_h6 = wpmsExtractTags($content, 'h6', false, true);

                // extract heading tags from WPBakery plugin shortcode
                if (is_plugin_active('js_composer/js_composer.php') && isset($_POST['datas']['content'])) {
                    $textContent = $_POST['datas']['content'];
                    $textContent = html_entity_decode(stripcslashes($textContent), ENT_COMPAT, 'UTF-8');
                    $msPostTitle = isset($_POST['datas']['title']) ? $_POST['datas']['title'] : '';
                    $vcCustomHeading = $this->wpmsShortcodeExtract($msPostTitle, $textContent, array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'));
                    if (!empty($vcCustomHeading)) {
                        $tags_h1 = array_merge($tags_h1, $vcCustomHeading['h1']);
                        $tags_h2 = array_merge($tags_h2, $vcCustomHeading['h2']);
                        $tags_h3 = array_merge($tags_h3, $vcCustomHeading['h3']);
                        $tags_h4 = array_merge($tags_h4, $vcCustomHeading['h4']);
                        $tags_h5 = array_merge($tags_h5, $vcCustomHeading['h5']);
                        $tags_h6 = array_merge($tags_h6, $vcCustomHeading['h6']);
                    }
                }
                $test = false;
                if (empty($tags_h1) && empty($tags_h2) && empty($tags_h3)
                    && empty($tags_h4) && empty($tags_h5) && empty($tags_h6)) {
                    $test = false;
                } else {
                    // check tag h1
                    if (!empty($tags_h1)) {
                        foreach ($tags_h1 as $order => $tagh1) {
                            $words_tagh1 = preg_split(
                                '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
                                strtolower($tagh1['contents']),
                                -1,
                                PREG_SPLIT_NO_EMPTY
                            );

                            if (is_array($words_tagh1) && is_array($words_post_title)) {
                                foreach ($words_tagh1 as $mh) {
                                    if (in_array($mh, $words_post_title) && $mh !== '') {
                                        $test = true;
                                    }
                                }
                            }
                        }
                    }

                    // check tag h2
                    if (!empty($tags_h2)) {
                        foreach ($tags_h2 as $order => $tagh2) {
                            $words_tagh2 = preg_split(
                                '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
                                strtolower($tagh2['contents']),
                                -1,
                                PREG_SPLIT_NO_EMPTY
                            );
                            if (is_array($words_tagh2) && is_array($words_post_title)) {
                                foreach ($words_tagh2 as $mh) {
                                    if (in_array($mh, $words_post_title) && $mh !== '') {
                                        $test = true;
                                    }
                                }
                            }
                        }
                    }

                    // check tag h3
                    if (!empty($tags_h3)) {
                        foreach ($tags_h3 as $order => $tagh3) {
                            $words_tagh3 = preg_split(
                                '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
                                strtolower($tagh3['contents']),
                                -1,
                                PREG_SPLIT_NO_EMPTY
                            );
                            if (is_array($words_tagh3) && is_array($words_post_title)) {
                                foreach ($words_tagh3 as $mh) {
                                    if (in_array($mh, $words_post_title) && $mh !== '') {
                                        $test = true;
                                    }
                                }
                            }
                        }
                    }

                    // check tag h4
                    if (!empty($tags_h4)) {
                        foreach ($tags_h4 as $order => $tagh4) {
                            $words_tagh4 = preg_split(
                                '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
                                strtolower($tagh4['contents']),
                                -1,
                                PREG_SPLIT_NO_EMPTY
                            );
                            if (is_array($words_tagh4) && is_array($words_post_title)) {
                                foreach ($words_tagh4 as $mh) {
                                    if (in_array($mh, $words_post_title) && $mh !== '') {
                                        $test = true;
                                    }
                                }
                            }
                        }
                    }

                    // check tag h5
                    if (!empty($tags_h5)) {
                        foreach ($tags_h5 as $order => $tagh5) {
                            $words_tagh5 = preg_split(
                                '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
                                strtolower($tagh5['contents']),
                                -1,
                                PREG_SPLIT_NO_EMPTY
                            );
                            if (is_array($words_tagh5) && is_array($words_post_title)) {
                                foreach ($words_tagh5 as $mh) {
                                    if (in_array($mh, $words_post_title) && $mh !== '') {
                                        $test = true;
                                    }
                                }
                            }
                        }
                    }

                    // check tag h6
                    if (!empty($tags_h6)) {
                        foreach ($tags_h6 as $order => $tagh6) {
                            $words_tagh6 = preg_split(
                                '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
                                strtolower($tagh6['contents']),
                                -1,
                                PREG_SPLIT_NO_EMPTY
                            );
                            if (is_array($words_tagh6) && is_array($words_post_title)) {
                                foreach ($words_tagh6 as $mh) {
                                    if (in_array($mh, $words_post_title) && $mh !== '') {
                                        $test = true;
                                    }
                                }
                            }
                        }
                    }
                }

                if ($test) {
                    $output .= $this->createFieldAnalysis(
                        'heading_title',
                        $tooltip_page['title_in_heading'],
                        'done',
                        esc_html__('Page title word in content heading', 'wp-meta-seo'),
                        1
                    );
                    $check++;
                } else {
                    $output .= $this->createFieldAnalysis(
                        'heading_title',
                        $tooltip_page['title_in_heading'],
                        'warning',
                        esc_html__('Page title word in content heading', 'wp-meta-seo'),
                        0
                    );
                }
            }
        }

        // title content
        $words_title = preg_split(
            '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
            strtolower($_POST['datas']['title']),
            -1,
            PREG_SPLIT_NO_EMPTY
        );
        $words_post_content = preg_split(
            '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
            strtolower(strip_tags($content)),
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        $test1 = false;
        if (is_array($words_title) && is_array($words_post_content)) {
            foreach ($words_title as $mtitle) {
                if (in_array($mtitle, $words_post_content) && $mtitle !== '') {
                    $test1 = true;
                    break;
                }
            }
        } else {
            $test1 = false;
        }

        if (isset($_POST['datas']['first_load']) && !empty($meta_analysis) && !empty($meta_analysis['content_title'])) {
            $output .= $this->createFieldAnalysis(
                'content_title',
                $tooltip_page['title_in_content'],
                'done',
                esc_html__('Page title word in content', 'wp-meta-seo'),
                1
            );
            $check++;
        } else {
            if ($test1) {
                $output .= $this->createFieldAnalysis(
                    'content_title',
                    $tooltip_page['title_in_content'],
                    'done',
                    esc_html__('Page title word in content', 'wp-meta-seo'),
                    1
                );
                $check++;
            } else {
                $output .= $this->createFieldAnalysis(
                    'content_title',
                    $tooltip_page['title_in_content'],
                    'warning',
                    esc_html__('Page title word in content', 'wp-meta-seo'),
                    0
                );
            }
        }

        // page url matches page title
        $mtitle = $_POST['datas']['title'];
        if (isset($_POST['datas']['first_load']) && !empty($meta_analysis) && !empty($meta_analysis['pageurl'])) {
            $output .= $this->createFieldAnalysis(
                'pageurl',
                $tooltip_page['page_url'],
                'done',
                esc_html__('Page url matches with page title', 'wp-meta-seo'),
                1
            );
            $check++;
        } else {
            $mpageurl = '';
            if (isset($_POST['datas']['mpageurl'])) {
                $infos = pathinfo($_POST['datas']['mpageurl']);
                $mpageurl = $infos['filename'];
                if (substr($mpageurl, 0, 1) === '?') {
                    $mpageurl = ltrim($mpageurl, '?'); // remove ? from ?page=abc
                }
            }

            //check is home page
            $home_url = home_url();
            $home_url_1 = explode('//', $home_url);
            $is_home = false;
            if (!empty($home_url_1[1]) && isset($_POST['datas']['mpageurl'])) {
                $home_url_2 = explode($home_url_1[1], $_POST['datas']['mpageurl']);
                if (!isset($home_url_2[1]) || (!empty($home_url_2[1]) && $home_url_2[1] === '/')) {
                    $is_home = true;
                }
            }

            if ((isset($_POST['datas']['mpageurl']) && $is_home) || (!empty($mpageurl) && !empty($mtitle) && strpos(sanitize_title($mtitle), sanitize_title($mpageurl)) !== false)) {
                $output .= $this->createFieldAnalysis(
                    'pageurl',
                    $tooltip_page['page_url'],
                    'done',
                    esc_html__('Page url matches with page title', 'wp-meta-seo'),
                    1
                );
                $check++;
            } else {
                $output .= $this->createFieldAnalysis(
                    'pageurl',
                    $tooltip_page['page_url'],
                    'warning',
                    esc_html__('Page url matches with page title', 'wp-meta-seo'),
                    0
                );
            }
        }

        // meta title filled
        if (isset($_POST['datas']['first_load']) && !empty($meta_analysis) && !empty($meta_analysis['metatitle'])) {
            $output .= $this->createFieldAnalysis(
                'metatitle',
                $tooltip_page['meta_title'],
                'done',
                esc_html__('Meta title filled', 'wp-meta-seo'),
                1
            );
            $check++;
        } else {
            if (($_POST['datas']['meta_title'] !== ''
                && mb_strlen($_POST['datas']['meta_title'], 'UTF-8') <= self::$title_length)) {
                $output .= $this->createFieldAnalysis(
                    'metatitle',
                    $tooltip_page['meta_title'],
                    'done',
                    esc_html__('Meta title filled', 'wp-meta-seo'),
                    1
                );
                $check++;
            } else {
                $output .= $this->createFieldAnalysis(
                    'metatitle',
                    $tooltip_page['meta_title'],
                    'warning',
                    esc_html__('Meta title filled', 'wp-meta-seo'),
                    0
                );
            }
        }

        // desc filled
        if (isset($_POST['datas']['first_load']) && !empty($meta_analysis) && !empty($meta_analysis['metadesc'])) {
            $output .= $this->createFieldAnalysis(
                'metadesc',
                $tooltip_page['meta_desc'],
                'done',
                esc_html__('Meta description filled', 'wp-meta-seo'),
                1
            );
            $check++;
        } else {
            if ($_POST['datas']['meta_desc'] !== ''
                && mb_strlen($_POST['datas']['meta_desc'], 'UTF-8') <= self::$desc_length) {
                $output .= $this->createFieldAnalysis(
                    'metadesc',
                    $tooltip_page['meta_desc'],
                    'done',
                    esc_html__('Meta description filled', 'wp-meta-seo'),
                    1
                );
                $check++;
            } else {
                $output .= $this->createFieldAnalysis(
                    'metadesc',
                    $tooltip_page['meta_desc'],
                    'warning',
                    esc_html__('Meta description filled', 'wp-meta-seo'),
                    0
                );
            }
        }

        // image resize
        if ($content === '') {
            $output .= $this->createFieldAnalysis(
                'imgresize',
                $tooltip_page['image_resize'],
                'done',
                esc_html__('Wrong image resize', 'wp-meta-seo'),
                1
            );
            $output .= $this->createFieldAnalysis(
                'imgalt',
                $tooltip_page['image_alt'],
                'done',
                esc_html__('Image alt information filled', 'wp-meta-seo'),
                1
            );
            $check += 2;
        } else {
            // Extracting the specified elements from the web page
            $img_tags = wpmsExtractTags($content, 'img', true, true);
            $img_wrong = false;
            $img_wrong_alt = false;
            foreach ($img_tags as $order => $tag) {
                if (!isset($tag['attributes']['src'])) {
                    continue;
                }

                $src = $tag['attributes']['src'];
                // validation src data
                $src = wp_http_validate_url($src);
                if (!$src) {
                    continue;
                }
                $imgpath = str_replace(site_url(), ABSPATH, $src);
                if (!file_exists($imgpath)) {
                    continue;
                }
                if (!list($width_origin, $height_origin) = getimagesize($imgpath)) {
                    continue;
                }

                if (empty($tag['attributes']['width']) && empty($tag['attributes']['height'])) {
                    $img_wrong = false;
                } else {
                    if (!empty($width_origin) && !empty($height_origin)) {
                        if ((isset($tag['attributes']['width']) && (int)$width_origin !== (int)$tag['attributes']['width'])
                            || (isset($tag['attributes']['height']) && (int)$height_origin !== (int)$tag['attributes']['height'])) {
                            $img_wrong = true;
                        }
                    }
                }

                if (empty($tag['attributes']['alt'])) {
                    $img_wrong_alt = true;
                }
            }

            if (isset($_POST['datas']['first_load']) && !empty($meta_analysis) && !empty($meta_analysis['imgresize'])) {
                $output .= $this->createFieldAnalysis(
                    'imgresize',
                    $tooltip_page['image_resize'],
                    'done',
                    esc_html__('Wrong image resize', 'wp-meta-seo'),
                    1
                );
                $check++;
            } else {
                if (!$img_wrong) {
                    $output .= $this->createFieldAnalysis(
                        'imgresize',
                        $tooltip_page['image_resize'],
                        'done',
                        esc_html__('Wrong image resize', 'wp-meta-seo'),
                        1
                    );
                    $check++;
                } else {
                    $output .= $this->createFieldAnalysis(
                        'imgresize',
                        $tooltip_page['image_resize'],
                        'warning',
                        esc_html__('Wrong image resize', 'wp-meta-seo'),
                        0
                    );
                }
            }

            if (isset($_POST['datas']['first_load']) && !empty($meta_analysis) && !empty($meta_analysis['imgalt'])) {
                $output .= $this->createFieldAnalysis(
                    'imgalt',
                    $tooltip_page['image_alt'],
                    'done',
                    esc_html__('Image alt information filled', 'wp-meta-seo'),
                    1
                );
                $check++;
            } else {
                if (!$img_wrong_alt) {
                    $output .= $this->createFieldAnalysis(
                        'imgalt',
                        $tooltip_page['image_alt'],
                        'done',
                        esc_html__('Image alt information filled', 'wp-meta-seo'),
                        1
                    );
                    $check++;
                } else {
                    $output .= $this->createFieldAnalysis(
                        'imgalt',
                        $tooltip_page['image_alt'],
                        'warning',
                        esc_html__('Image alt information filled', 'wp-meta-seo'),
                        0
                    );
                }
            }

            $output .= $this->createFieldAnalysis(
                'seokeyword',
                esc_html__('At least one of the SEO keywords are found in the page title OR meta title OR SEO meta description OR page content heading OR the page content', 'wp-meta-seo'),
                'warning',
                esc_html__('SEO keywords are found', 'wp-meta-seo'),
                0
            );

            $right_output = '<div class="metaseo_analysis metaseo_tool"><span style="font-weight: 700">' . esc_html__('Page SEO keywords check', 'wp-meta-seo') . '</span></div>';

            $right_output .= $this->createFieldAnalysis(
                'keyintitle',
                esc_html__('The SEO keywords are found in page title', 'wp-meta-seo'),
                'warning',
                esc_html__('The keywords are found in page title', 'wp-meta-seo'),
                0
            );

            $right_output .= $this->createFieldAnalysis(
                'keyincontent',
                esc_html__('The SEO keywords are found in page content', 'wp-meta-seo'),
                'warning',
                esc_html__('The keywords are found in page content', 'wp-meta-seo'),
                0
            );


            $right_output .= $this->createFieldAnalysis(
                'keyinmetatitle',
                esc_html__('The SEO keywords are found in meta title', 'wp-meta-seo'),
                'warning',
                esc_html__('The keywords are found in meta title', 'wp-meta-seo'),
                0
            );

            $right_output .= $this->createFieldAnalysis(
                'keyinmetadescription',
                esc_html__('The SEO keywords are found in meta description', 'wp-meta-seo'),
                'warning',
                esc_html__('The keywords are found in meta description', 'wp-meta-seo'),
                0
            );

            $right_output .= $this->createFieldAnalysis(
                'keyincontentheading',
                esc_html__('The SEO keywords are found in page content heading', 'wp-meta-seo'),
                'warning',
                esc_html__('The keywords are found in page content heading', 'wp-meta-seo'),
                0
            );
        }
        $total_check = 7;
        if (isset($_POST['datas']['seo_keywords']) && !empty($_POST['datas']['seo_keywords'])) {
            $total_check++;
        }
        $circliful = ceil(100 * ($check) / $total_check);
        /**
         * Reload analytics
         *
         * @param integer Post ID
         * @param array   All the datas
         */
        do_action('wpms_reload_analytics', $_POST['datas']['post_id'], $_POST['datas']);
        wp_send_json(array('circliful' => $circliful, 'output' => $output, 'check' => $check, 'right_output' => $right_output));
    }

    /**
     * Extract html heading tags from WPBakery Page Builder shortcode
     *
     * @param string $postTitle   Post title
     * @param string $textContent Text content page/post
     * @param array  $search      Html heading tags to extract
     *
     * @return array
     */
    public function wpmsShortcodeExtract($postTitle, $textContent, $search)
    {
        $extracted = array();
        // Capture vc_custom_heading shortcode from post content
        if (preg_match_all('/\[vc_custom_heading(.*?)\]/i', $textContent, $matches)) {
            if (!empty($matches)) {
                foreach ($matches[1] as $vcCustomHeading) {
                    $attrs = shortcode_parse_atts(trim($vcCustomHeading));
                    if (!empty($attrs['font_container'])) {
                        foreach ($search as $s) {
                            if (strpos($attrs['font_container'], 'tag:' . $s . '|') !== false) {
                                $tag = $s;
                                break; // find out html tag
                            }
                        }
                    } else {
                        $tag = 'h2'; // default by WPBakery page builder if empty font_container attr
                    }

                    // Check case source="post_title"
                    $content = '';
                    if (isset($attrs['source']) && $attrs['source'] === 'post_title') {
                        $content = $postTitle;
                    } elseif (isset($attrs['text']) && !empty($attrs['text'])) {
                        $content = $attrs['text'];
                    }

                    if (!empty($attrs) && isset($tag)) {
                        $extracted[$tag][] = array(
                            'tag_name' => $tag,
                            'offset' => '',
                            'contents' => $content,
                            'attributes' => array(),
                            'full_tag' => '[vc_custom_heading ' . $vcCustomHeading . ']'
                        );
                    }
                }
            }
        }

        // Capture trx_sc_title shortcode from post content
        if (preg_match_all('/\[trx_sc_title(.*?)\]/i', $textContent, $matches)) {
            if (!empty($matches)) {
                foreach ($matches[1] as $trxScTitle) {
                    $attrs = shortcode_parse_atts(trim($trxScTitle));
                    if (!empty($attrs['title_tag'])) {
                        $titleTag = $attrs['title_tag'];
                    } else {
                        $titleTag = 'h2'; // default by WPBakery page builder if empty title_tag attr
                    }

                    // Check case source="post_title"
                    $title = '';
                    if (isset($attrs['title'])) {
                        $title = $attrs['title'];
                    }

                    if (!empty($attrs) && !empty($title)) {
                        $extracted[$titleTag][] = array(
                            'tag_name' => $titleTag,
                            'offset' => '',
                            'contents' => $title,
                            'attributes' => array(),
                            'full_tag' => '[trx_sc_title ' . $trxScTitle . ']'
                        );
                    }
                }
            }
        }

        return $extracted;
    }
    /**
     * Validate propertyin page optimization
     *
     * @return void
     */
    public function validateAnalysis()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json(false);
        }
        $post_id = $_POST['post_id'];
        $key = 'wpms_validate_analysis';
        $analysis = get_post_meta($post_id, $key, true);
        if (empty($analysis)) {
            $analysis = array();
        }

        $analysis[$_POST['field']] = 1;
        update_post_meta($post_id, $key, $analysis);
        wp_send_json(true);
    }

    /**
     * Remove link in source 404
     *
     * @return void
     */
    public function removeLink()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json(array('status' => false));
        }

        global $wpdb;
        if (isset($_POST['link_id'])) {
            $wpdb->delete($wpdb->prefix . 'wpms_links', array('id' => (int)$_POST['link_id']));
            wp_send_json(array('status' => true));
        }

        wp_send_json(array('status' => false));
    }

    /**
     * Ajax update link meta title and content editor
     *
     * @param string  $type        Action type
     * @param string  $link_detail Link details
     * @param string  $title       Title value
     * @param integer $link_id     Link ID
     *
     * @return void
     */
    public function updateLink1($type, $link_detail, $title, $link_id)
    {
        global $wpdb;
        global $wp_version;
        // Purge title
        $title = strip_tags($title);
        $title = str_replace("'", '', $title);
        $title = str_replace('"', '', $title);

        $value = array('meta_title' => $title);
        $wpdb->update(
            $wpdb->prefix . 'wpms_links',
            $value,
            array('id' => (int)$link_id)
        );
        $post = get_post($link_detail->source_id);

        if (!empty($post)) {
            if (version_compare($wp_version, '5.0', '>=')) {
                if (function_exists('has_blocks')) {
                    if (has_blocks((int)$link_detail->source_id)) {
                        // Gutenberg
                        $post_content = $this->gutenbergUpdateContent($post->post_content, $link_detail, $title);
                    } else {
                        // Classic editor
                        $post_content = $this->replaceNewTitle($post->post_content, $link_detail, $title);
                    }
                }
            } else {
                // Classic editor
                $post_content = $this->replaceNewTitle($post->post_content, $link_detail, $title);
            }

            $my_post = array(
                'ID' => (int)$link_detail->source_id,
                'post_content' => $post_content
            );
            remove_action('post_updated', array('MetaSeoBrokenLinkTable', 'updatePost'));
            wp_update_post($my_post);
            if ($type === 'ajax') {
                wp_send_json(array('status' => true));
            }
        }
    }

    /**
     * Update link meta title and content editor in gutenberg
     *
     * @param string $post_content Content of posts
     * @param string $link_detail  Link details
     * @param string $title        Title value
     *
     * @return string
     */
    public function gutenbergUpdateContent($post_content, $link_detail, $title)
    {
        $blocks = parse_blocks($post_content);
        // phpcs:disable Generic.PHP.LowerCaseConstant.Found -- In special case the block returns NULL
        $allowed_blocks = array(
            // Classic blocks have their blockName set to null.
            null,
            NULL,
            'core/button',
            'core/paragraph',
            'core/heading',
            'core/list',
            'core/quote',
            'core/cover',
            'core/html',
            'core/verse',
            'core/preformatted',
            'core/pullquote',
            'core/table',
            'core/media-text'
        );
        // phpcs:enable
        foreach ($blocks as $block) {
            // Gutenberg block
            if (in_array($block['blockName'], $allowed_blocks, true)) {
                if (!empty($block['innerBlocks'])) {
                    // Skip the block if it has disallowed or nested inner blocks.
                    foreach ($block['innerBlocks'] as $inner_block) {
                        if (!in_array($inner_block['blockName'], $allowed_blocks, true) ||
                            !empty($inner_block['innerBlocks'])
                        ) {
                            continue;
                        }
                    }
                }

                if (strpos($block['innerHTML'], $link_detail->link_text) !== false) {
                    $post_content = $this->replaceNewTitle($post_content, $link_detail, $title);
                }
            }
        }

        return $post_content;
    }

    /**
     * Filter and replace new title
     *
     * @param string $post_content Content of posts
     * @param string $link_detail  Link details
     * @param string $title        Title value
     *
     * @return string
     */
    protected function replaceNewTitle($post_content, $link_detail, $title)
    {
        $links = wpmsExtractTags($post_content, 'a', false, true);
        $title_tag = sprintf('%s="%s"', 'title', esc_attr($title));
        if (!empty($links)) {
            foreach ($links as $link) {
                if ($link['contents'] === $link_detail->link_text) {
                    if (!isset($link['attributes']['title'])) {
                        // Not titlte, add new
                        $new_html = preg_replace(
                            '/<a/is',
                            '<a ' . $title_tag,
                            $link['full_tag']
                        );
                    } else {
                        $new_html = preg_replace(
                            '/title=(["\'])(.*?)["\']/is',
                            $title_tag,
                            $link['full_tag']
                        );
                    }
                    // Replace tag
                    $post_content = str_replace($link['full_tag'], $new_html, $post_content);
                }
            }
        }

        return $post_content;
    }

    /**
     * Ajax update link meta title and content editor
     *
     * @return void
     */
    public function updateLink()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json(false);
        }
        if (isset($_POST['link_id'])) {
            global $wpdb;
            $link_detail = $wpdb->get_row($wpdb->prepare(
                'SELECT * FROM ' . $wpdb->prefix . 'wpms_links WHERE id=%d',
                array(
                    (int)$_POST['link_id']
                )
            ));
            if (empty($link_detail)) {
                wp_send_json(false);
            }
            $this->updateLink1('ajax', $link_detail, $_POST['meta_title'], $_POST['link_id']);
        }
        wp_send_json(false);
    }

    /**
     * Ajax update meta index for page
     *
     * @return void
     */
    public function updatePageIndex()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json(array('status' => false));
        }
        if (isset($_POST['page_id']) && isset($_POST['index'])) {
            update_post_meta($_POST['page_id'], '_metaseo_metaindex', $_POST['index']);
            /**
             * Update index/noindex for robots meta tag of a page
             *
             * @param integer Page ID
             * @param string  Page meta index
             * @param integer Page index value
             */
            do_action('wpms_update_page_index', $_POST['page_id'], '_metaseo_metaindex', $_POST['index']);
            wp_send_json(array('status' => true));
        }
        wp_send_json(array('status' => false));
    }

    /**
     * Ajax update meta follow for page
     *
     * @return void
     */
    public function updatePageFollow()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json(array('status' => false));
        }
        if (isset($_POST['page_id']) && isset($_POST['follow'])) {
            update_post_meta((int)$_POST['page_id'], '_metaseo_metafollow', $_POST['follow']);

            /**
             * Update follow/nofollow for robots meta tag of a page
             *
             * @param integer Page ID
             * @param string  Page meta follow
             * @param integer Page follow value
             */
            do_action('wpms_update_page_follow', $_POST['page_id'], '_metaseo_metafollow', $_POST['follow']);
            wp_send_json(array('status' => true));
        }
        wp_send_json(array('status' => false));
    }

    /**
     * Ajax update meta follow for link
     *
     * @return void
     */
    public function updateFollow()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json(array('status' => false));
        }
        if (isset($_POST['link_id'])) {
            $this->doUpdateFollow($_POST['link_id'], $_POST['follow']);
            /**
             * Update follow/nofollow for rel attribute of a link
             *
             * @param integer Link ID
             * @param integer Link follow
             */
            do_action('wpms_update_link_follow', $_POST['link_id'], $_POST['follow']);
            wp_send_json(array('status' => true));
        }
        wp_send_json(array('status' => false));
    }

    /**
     * Ajax update multitle meta follow for link
     *
     * @return void
     */
    public function updateMultipleFollow()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json(array('status' => false));
        }
        global $wpdb;
        $action_name = $_POST['action_name'];
        $limit = 20;

        switch ($action_name) {
            case 'copy_title_selected':
                if (empty($_POST['linkids'])) {
                    wp_send_json(array('status' => true));
                }
                foreach ($_POST['linkids'] as $linkId) {
                    $link = $wpdb->get_row(
                        $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wpms_links WHERE id = %d', $linkId)
                    );
                    $link_text = $link->link_text;
                    if ($link_text !== '') {
                        $this->updateLink1('bulk', $link, $link_text, $linkId);
                    }
                }

                break;
            case 'copy_title_all':
                $links = $wpdb->get_results(
                    'SELECT * FROM ' . $wpdb->prefix . 'wpms_links WHERE type="url"'
                );
                foreach ($links as $link) {
                    $link_text = $link->link_text;
                    if ($link_text !== '') {
                        $this->updateLink1('bulk', $link, $link_text, $link->id);
                    }
                }
                break;
            case 'follow_selected':
                if (empty($_POST['linkids'])) {
                    wp_send_json(array('status' => true));
                }

                $follow = 1;
                foreach ($_POST['linkids'] as $linkId) {
                    $this->doUpdateFollow($linkId, $follow);
                    /**
                     * Update follow/nofollow for rel attribute of a link
                     *
                     * @param integer Link ID
                     * @param integer Link follow
                     *
                     * @ignore Hook already documented
                     */
                    do_action('wpms_update_link_follow', $linkId, $follow);
                }
                break;
            case 'follow_all':
                $follow = 1;
                $i = 0;
                $links = $wpdb->get_results(
                    'SELECT * FROM ' . $wpdb->prefix . 'wpms_links WHERE follow=0 AND type="url"'
                );
                foreach ($links as $link) {
                    if ($i > $limit) {
                        wp_send_json(array('status' => false, 'message' => 'limit'));
                    } else {
                        $this->doUpdateFollow($link->id, $follow);
                        $i++;
                        /**
                         * Update follow/nofollow for rel attribute of a link
                         *
                         * @param integer Link ID
                         * @param integer Link follow
                         *
                         * @ignore Hook already documented
                         */
                        do_action('wpms_update_link_follow', $link->id, $follow);
                    }
                }
                break;
            case 'nofollow_selected':
                $follow = 0;
                if (empty($_POST['linkids'])) {
                    wp_send_json(array('status' => true));
                }

                foreach ($_POST['linkids'] as $linkId) {
                    $this->doUpdateFollow($linkId, $follow);
                    /**
                     * Update follow/nofollow for rel attribute of a link
                     *
                     * @param integer Link ID
                     * @param integer Link follow
                     *
                     * @ignore Hook already documented
                     */
                    do_action('wpms_update_link_follow', $linkId, $follow);
                }
                break;
            case 'nofollow_all':
                $follow = 0;
                $i = 0;
                $links = $wpdb->get_results(
                    'SELECT * FROM ' . $wpdb->prefix . 'wpms_links WHERE follow=1 AND type="url"'
                );
                foreach ($links as $link) {
                    if ($i > $limit) {
                        wp_send_json(array('status' => false, 'message' => 'limit'));
                    } else {
                        $this->doUpdateFollow($link->id, $follow);
                        $i++;
                        /**
                         * Update follow/nofollow for rel attribute of a link
                         *
                         * @param integer Link ID
                         * @param integer Link follow
                         *
                         * @ignore Hook already documented
                         */
                        do_action('wpms_update_link_follow', $link->id, $follow);
                    }
                }
                break;
        }
        wp_send_json(array('status' => true));
    }

    /**
     * Ajax update meta follow for link
     *
     * @param integer $linkId Link id
     * @param integer $follow Follow value
     *
     * @return void
     */
    public function doUpdateFollow($linkId, $follow)
    {
        global $wpdb;
        $link_detail = $wpdb->get_row($wpdb->prepare(
            'SELECT * FROM ' . $wpdb->prefix . 'wpms_links WHERE id=%d',
            array((int)$linkId)
        ));
        if (empty($link_detail)) {
            wp_send_json(array('status' => false));
        }

        $value = array('follow' => $follow);
        $wpdb->update(
            $wpdb->prefix . 'wpms_links',
            $value,
            array('id' => (int)$linkId)
        );

        $post = get_post($link_detail->source_id);
        if (!empty($post)) {
            $old_value = $post->post_content;
            $edit_result = $this->editLinkHtml(
                $old_value,
                $link_detail->link_url,
                $link_detail->link_url,
                $link_detail->meta_title,
                $follow
            );
            $my_post = array(
                'ID' => (int)$link_detail->source_id,
                'post_content' => $edit_result['content']
            );
            remove_action('post_updated', array('MetaSeoBrokenLinkTable', 'updatePost'));
            wp_update_post($my_post);
        }
    }

    /**
     * Render new content when edit a link
     *
     * @param string  $content    Post content
     * @param string  $new_url    New url
     * @param string  $old_url    Old url
     * @param string  $meta_title Meta title
     * @param integer $follow     Follow value
     * @param null    $new_text   New text of link
     *
     * @return array
     */
    public function editLinkHtml($content, $new_url, $old_url, $meta_title, $follow, $new_text = null)
    {
        //Save the old & new URLs for use in the edit callback.
        $args = array(
            'old_url' => $old_url,
            'new_url' => $new_url,
            'new_text' => $new_text,
            'meta_title' => $meta_title,
            'follow' => $follow
        );

        //Find all links and replace those that match $old_url.
        $content = MetaSeoBrokenLinkTable::multiEdit(
            $content,
            array(
                'MetaSeoBrokenLinkTable',
                'editHtmlCallback'
            ),
            $args
        );

        $result = array(
            'content' => $content,
            'raw_url' => $new_url,
        );
        if (isset($new_text)) {
            $result['link_text'] = $new_text;
        }
        return $result;
    }

    /**
     * Update option wpms_settings_404
     *
     * @return void
     */
    public function save404Settings()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json(false);
        }

        if (isset($_POST['wpms_redirect'])) {
            update_option('wpms_settings_404', $_POST['wpms_redirect']);
        }

        if (is_plugin_active(WPMSEO_ADDON_FILENAME)) {
            $params = array('enable', 'numberFrequency', 'showlinkFrequency');
            $settinglink = array();
            foreach ($params as $param) {
                if (isset($_POST[$param])) {
                    $settinglink[$param] = $_POST[$param];
                }
            }

            if (empty($settinglink['wpms_lastRun_scanlink'])) {
                $settinglink['wpms_lastRun_scanlink'] = time();
            }
            update_option('wpms_link_settings', $settinglink);
        }

        wp_send_json(true);
    }

    /**
     * Update breadcrumb settings
     *
     * @return void
     */
    public function saveBreadcrumbSettings()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json(false);
        }

        $params = array('separator', 'include_home', 'home_text', 'clickable', 'home_text_default');
        $settinglink = array();
        foreach ($params as $param) {
            if (isset($_POST[$param])) {
                $settinglink[$param] = $_POST[$param];
            }
        }

        update_option('_metaseo_breadcrumbs', $settinglink);
        wp_send_json(true);
    }

    /**
     * Show meta box in single post, on elementor plugin
     *
     * @return void
     */
    private function loadMetaBoxes()
    {
        if (in_array($this->pagenow, array(
                'edit.php',
                'post.php',
                'post-new.php',
            )) || apply_filters('wpmseo_always_register_metaboxes_on_admin', false)
        ) {
            require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-metabox.php');
            $GLOBALS['wpmseo_metabox'] = new WPMSEOMetabox;
        }
    }

    /**
     * Update meta title , meta description , meta keyword for content
     *
     * @return void
     */
    public function updateContentMetaCallback()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json(array('status' => false));
        }
        $_POST = stripslashes_deep($_POST);
        $response = new stdClass();
        $metakey = strtolower(trim($_POST['metakey']));
        $postID = intval($_POST['postid']);
        $value = trim($_POST['value']);
        $response->msg = esc_html__('Modification was saved', 'wp-meta-seo');
        if ($metakey === 'metatitle') {
            if (!update_post_meta($postID, '_metaseo_metatitle', $value)) {
                $response->updated = false;
                $response->msg = esc_html__('Meta title was not saved', 'wp-meta-seo');
            } else {
                $response->updated = true;
                $response->msg = esc_html__('Meta title was saved', 'wp-meta-seo');
            }
        }

        if ($metakey === 'metadesc') {
            if (!update_post_meta($postID, '_metaseo_metadesc', $value)) {
                $response->updated = false;
                $response->msg = esc_html__('Meta description was not saved', 'wp-meta-seo');
            } else {
                $response->updated = true;
                $response->msg = esc_html__('Meta description was saved', 'wp-meta-seo');
            }
        }

        if ($metakey === 'metakeywords') {
            if (!update_post_meta($postID, '_metaseo_metakeywords', $value)) {
                $response->updated = false;
                $response->msg = esc_html__('Meta keywords was not saved', 'wp-meta-seo');
            } else {
                $response->updated = true;
                $response->msg = esc_html__('Meta keywords was saved', 'wp-meta-seo');
            }
        }
        update_option('wpms_last_update_post', time());
        echo json_encode($response);
        wp_die();
    }

    /**
     * Loads js/ajax scripts
     *
     * @return void
     */
    public function loadAdminScripts()
    {
        global $pagenow, $current_screen;
        wp_enqueue_script('jquery');
        $array_menu = array(
            'wp-meta-seo_page_metaseo_dashboard',
            'wp-meta-seo_page_metaseo_image_optimize',
            'wp-meta-seo_page_metaseo_google_sitemap',
            'wp-meta-seo_page_metaseo_image_compression',
            'wp-meta-seo_page_metaseo_broken_link',
            'wp-meta-seo_page_metaseo_settings',
            'wp-meta-seo_page_metaseo_content_meta',
            'wp-meta-seo_page_metaseo_category_meta',
            'wp-meta-seo_page_metaseo_image_meta',
            'wp-meta-seo_page_metaseo_link_meta'
        );

        $lists_pages = array(
            'toplevel_page_metaseo_dashboard',
            'wp-meta-seo_page_metaseo_content_meta',
            'wp-meta-seo_page_metaseo_category_meta',
            'wp-meta-seo_page_metaseo_google_sitemap',
            'wp-meta-seo_page_metaseo_image_meta',
            'wp-meta-seo_page_metaseo_link_meta',
            'wp-meta-seo_page_metaseo_broken_link',
            'wp-meta-seo_page_metaseo_console',
            'wp-meta-seo_page_metaseo_google_analytics',
            'wp-meta-seo_page_metaseo_sendemail',
            'wp-meta-seo_page_metaseo_settings'
        );

        wp_enqueue_style(
            'metaseo-google-icon',
            '//fonts.googleapis.com/icon?family=Material+Icons'
        );

        if (!empty($current_screen) && in_array($current_screen->base, $lists_pages)) {
            wp_enqueue_style(
                'wpms-magnific-popup-style',
                plugins_url('assets/css/magnific-popup.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            wp_enqueue_style(
                'wpms_ju_waves_css',
                plugins_url('assets/wordpress-css-framework/css/waves.min.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            wp_enqueue_script(
                'wpms-magnific-popup-script',
                plugins_url('assets/js/jquery.magnific-popup.min.js', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            wp_enqueue_style(
                'wpms_ju_framework_styles',
                plugins_url('assets/wordpress-css-framework/css/style.min.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            wp_enqueue_script(
                'wpms_ju_velocity_js',
                plugins_url('assets/wordpress-css-framework/js/velocity.min.js', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );
            wp_enqueue_script(
                'wpms_ju_waves_js',
                plugins_url('assets/wordpress-css-framework/js/waves.min.js', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            wp_enqueue_script(
                'wpms_ju_tabs_js',
                plugins_url('assets/wordpress-css-framework/js/tabs.js', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            wp_enqueue_script(
                'wpms_ju_framework_js',
                plugins_url('assets/wordpress-css-framework/js/script.js', dirname(__FILE__)),
                array('wpms_ju_tabs_js'),
                WPMSEO_VERSION
            );

            if ($current_screen->base !== 'wp-meta-seo_page_metaseo_settings') {
                wp_enqueue_style(
                    'wpms_materialize_style',
                    plugins_url('assets/css/materialize/materialize.css', dirname(__FILE__)),
                    array(),
                    WPMSEO_VERSION
                );
            } else {
                // Register CSS
                wp_enqueue_style(
                    'wpms-settings-styles',
                    plugins_url('assets/css/settings.css', dirname(__FILE__)),
                    array('wpms_main'),
                    WPMSEO_VERSION
                );

                wp_enqueue_script(
                    'wpms-settings-script',
                    plugins_url('assets/js/settings.js', dirname(__FILE__)),
                    array('jquery'),
                    WPMSEO_VERSION,
                    true
                );

                wp_localize_script('wpms-settings-script', 'wpmsSettingsL10n', array(
                    'choose_image' => esc_html__('Use Image', 'wp-meta-seo')
                ));
            }

            wp_enqueue_style(
                'wpms_main',
                plugins_url('assets/css/main.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );
        }

        wp_enqueue_script(
            'wpmetaseoAdmin',
            plugins_url('assets/js/metaseo_admin.js', dirname(__FILE__)),
            array('jquery'),
            WPMSEO_VERSION,
            true
        );

        if (!empty($current_screen) && (in_array($current_screen->base, $array_menu) || $pagenow === 'post.php' || $pagenow === 'post-new.php')) {
            wp_enqueue_style(
                'wpmetaseoAdmin',
                plugins_url('assets/css/metaseo_admin.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );
            wp_enqueue_style(
                'tooltip-metaimage',
                plugins_url('assets/css/tooltip-metaimage.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            wp_enqueue_style('style', plugins_url('assets/css/style.css', dirname(__FILE__)), array(), WPMSEO_VERSION);
        }

        if (!empty($current_screen) && ($current_screen->base === 'wp-meta-seo_page_metaseo_image_meta'
                || $current_screen->base === 'wp-meta-seo_page_metaseo_content_meta')) {
            wp_enqueue_script(
                'wpms-bulk',
                plugins_url('assets/js/wpms-bulk-action.js', dirname(__FILE__)),
                array('jquery'),
                WPMSEO_VERSION,
                true
            );
            wp_localize_script('wpms-bulk', 'wpmseobulkL10n', $this->localizeScript());
        }

        if (!empty($current_screen) && $current_screen->base === 'wp-meta-seo_page_metaseo_broken_link') {
            wp_enqueue_style(
                'wpms_brokenlink_style',
                plugins_url('assets/css/broken_link.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            wp_enqueue_style(
                'wpms-bootstrap-style',
                plugins_url('assets/css/bootstrap/bootstrap.min.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            wp_enqueue_style(
                'wpms-bootstrap-multiselect-style',
                plugins_url('assets/css/bootstrap/bootstrap-multiselect.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            wp_enqueue_script(
                'wpms-bootstrap-multiselect-script',
                plugins_url('assets/css/bootstrap/bootstrap-multiselect.js', dirname(__FILE__)),
                array('jquery'),
                WPMSEO_VERSION,
                true
            );
        }

        if (!empty($current_screen) && $current_screen->base === 'toplevel_page_metaseo_dashboard') {
            wp_enqueue_script(
                'metaseo-dashboard',
                plugins_url('assets/js/dashboard.js', dirname(__FILE__)),
                array('jquery'),
                WPMSEO_VERSION,
                true
            );

            wp_enqueue_style(
                'metaseo-quirk',
                plugins_url('assets/css/metaseo-quirk.css', dirname(__FILE__))
            );

            wp_enqueue_style(
                'm-style-dashboard',
                plugins_url('assets/css/dashboard.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            wp_enqueue_style(
                'wpms-ju-css-framwork',
                plugins_url('assets/css/main.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            if (class_exists('MetaSeoAddonAdmin')) {
                wp_enqueue_style(
                    'msaddon-style-dashboard',
                    WPMETASEO_ADDON_PLUGIN_URL . 'assets/css/dashboard.css',
                    array(),
                    WPMSEO_ADDON_VERSION
                );
            }
        }

        if (!empty($current_screen) && $current_screen->base === 'wp-meta-seo_page_metaseo_better_ranking') {
            wp_enqueue_style(
                'wpms-better-seo',
                plugins_url('assets/css/better_seo.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            wp_enqueue_style(
                'wpms-ju-css-framwork',
                plugins_url('assets/css/main.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );
        }

        $lists_pages = array(
            'toplevel_page_metaseo_dashboard',
            'wp-meta-seo_page_metaseo_google_sitemap',
            'wp-meta-seo_page_metaseo_link_meta',
            'wp-meta-seo_page_metaseo_broken_link',
            'wp-meta-seo_page_metaseo_google_analytics'
        );
        if (!empty($current_screen) && in_array($current_screen->base, $lists_pages)) {
            wp_enqueue_style(
                'wpms_notification_style',
                plugins_url('assets/css/notification.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );
            wp_enqueue_script(
                'wpms_notification_script',
                plugins_url('assets/js/notification.js', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );
        }

        wp_register_style(
            'wpms-dashboard-widgets',
            plugins_url('assets/css/dashboard-widgets/dashboard_widgets.css', dirname(__FILE__)),
            null,
            WPMSEO_VERSION
        );
        wp_register_style(
            'wpms-category-field',
            plugins_url('assets/css/category_field.css', dirname(__FILE__)),
            null,
            WPMSEO_VERSION
        );
        wp_register_script(
            'wpms-category-field',
            plugins_url('assets/js/category_field.js', dirname(__FILE__)),
            array('jquery'),
            WPMSEO_VERSION,
            true
        );

        // Enqueue for category meta page
        if (!empty($current_screen) && $current_screen->base === 'wp-meta-seo_page_metaseo_category_meta') {
            wp_enqueue_style(
                'wpmsCategoryMeta',
                plugins_url('assets/css/metaseo-category-meta-bulk.css', dirname(__FILE__)),
                array(),
                WPMSEO_VERSION
            );

            wp_enqueue_script(
                'wpmsCategoryMeta',
                plugins_url('assets/js/wpms-category-meta.js', dirname(__FILE__)),
                array('jquery'),
                WPMSEO_VERSION,
                true
            );
            wp_enqueue_script('wpms-category-field');
        }

        wp_register_style(
            'wpms-tippy-style',
            plugins_url('assets/tippy/tippy.css', dirname(__FILE__)),
            array(),
            WPMSEO_VERSION
        );

        wp_register_script(
            'wpms-tippy-core',
            plugins_url('assets/tippy/tippy-core.js', dirname(__FILE__)),
            array('jquery'),
            '2.2.1',
            true
        );

        wp_register_script(
            'wpms-tippy',
            plugins_url('assets/tippy/tippy.js', dirname(__FILE__)),
            array('jquery'),
            '2.2.1',
            true
        );

        wp_register_script(
            'wpms-my-tippy',
            plugins_url('assets/tippy/my-tippy.js', dirname(__FILE__)),
            array('jquery'),
            WPMSEO_VERSION,
            true
        );

        wp_enqueue_style(
            'wpms-mytippy-style',
            plugins_url('assets/tippy/my-tippy.css', dirname(__FILE__)),
            array(),
            WPMSEO_VERSION
        );
        // Register snackbar script alert
        wp_register_script(
            'wpms-snackbar-script',
            plugins_url('assets/js/snackbar.js', dirname(__FILE__)),
            array('jquery'),
            WPMSEO_VERSION,
            true
        );
        // Register snackbar style alert
        wp_register_style(
            'wpms-snackbar-style',
            plugins_url('assets/css/snackbar.css', dirname(__FILE__)),
            array(),
            WPMSEO_VERSION
        );
        wp_register_script(
            'wpms-broken-link',
            plugins_url('assets/js/wpms-broken-link.js', dirname(__FILE__)),
            array('jquery'),
            WPMSEO_VERSION,
            true
        );

        wp_register_style('metaseo-google-icon', '//fonts.googleapis.com/icon?family=Material+Icons');
        if (!empty($current_screen) && $current_screen->base === 'wp-meta-seo_page_metaseo_google_analytics') {
            $lang = get_bloginfo('language');
            $lang = explode('-', $lang);
            $lang = $lang[0];
            wp_enqueue_style(
                'wpms-nprogress',
                plugins_url('assets/css/google-analytics/nprogress.css', dirname(__FILE__)),
                null,
                WPMSEO_VERSION
            );

            wp_register_style(
                'wpms-backend-item-reports',
                plugins_url('assets/css/google-analytics/admin-widgets.css', dirname(__FILE__)),
                null,
                WPMSEO_VERSION
            );
            wp_register_style(
                'wpms-backend-tracking-code',
                plugins_url('assets/css/google-analytics/wpms-tracking-code.css', dirname(__FILE__)),
                null,
                WPMSEO_VERSION
            );

            wp_register_style(
                'jquery-ui-tooltip-html',
                plugins_url('assets/css/google-analytics/jquery.ui.tooltip.html.css', dirname(__FILE__))
            );

            wp_enqueue_style('jquery-ui-tooltip-html');

            wp_enqueue_script(
                'wpmsgooglejsapi',
                'https://www.google.com/jsapi?autoload=%7B%22modules%22%3A%5B%7B%22name%22%3A%22visualization%22%2C%22version%22%3A%221%22%2C%22language%22%3A%22' . $lang . '%22%2C%22packages%22%3A%5B%22corechart%22%2C%20%22imagechart%22%2C%20%22table%22%2C%20%22orgchart%22%2C%20%22geochart%22%5D%7D%5D%7D',
                array(),
                null
            );

            wp_enqueue_script(
                'wpms-nprogress',
                plugins_url('assets/js/google-analytics/nprogress.js', dirname(__FILE__)),
                array('jquery'),
                WPMSEO_VERSION
            );

            wp_enqueue_script(
                'wpms-google-analytics',
                plugins_url('assets/js/google-analytics/google_analytics.js', dirname(__FILE__)),
                array('jquery'),
                WPMSEO_VERSION,
                true
            );

            // Select toolbar
            require_once 'google_analytics/wpmstools.php';
            $wpms_google_analytics = get_option('wpms_google_alanytics');
            if (isset($wpms_google_analytics['tableid_jail']) && isset($wpms_google_analytics['profile_list'])) {
                $wpms_profile = WpmsGaTools::getSelectedProfile($wpms_google_analytics['profile_list'], $wpms_google_analytics['tableid_jail']);
            }
            if (isset($wpms_profile[4])) {
                $property_type = $wpms_profile[4];
            } else {
                $property_type = 'UA';
            }
            /* @formatter:off */
            wp_localize_script(
                'wpms-google-analytics',
                'wpmsItemData',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'security' => wp_create_nonce('wpms_backend_item_reports'),
                    'dateList' => array(
                        'realtime' => esc_html__('Real-Time', 'wp-meta-seo'),
                        'today' => esc_html__('Today', 'wp-meta-seo'),
                        'yesterday' => esc_html__('Yesterday', 'wp-meta-seo'),
                        '7daysAgo' => sprintf(esc_html__('Last %d Days', 'wp-meta-seo'), 7),
                        '14daysAgo' => sprintf(esc_html__('Last %d Days', 'wp-meta-seo'), 14),
                        '30daysAgo' => sprintf(esc_html__('Last %d Days', 'wp-meta-seo'), 30),
                        '90daysAgo' => sprintf(esc_html__('Last %d Days', 'wp-meta-seo'), 90),
                        '365daysAgo' => sprintf(_n('%s Year', '%s Years', 1, 'wp-meta-seo'), esc_html__('One', 'wp-meta-seo')),
                        '1095daysAgo' => sprintf(
                            _n('%s Year', '%s Years', 3, 'wp-meta-seo'),
                            esc_html__('Three', 'wp-meta-seo')
                        ),
                    ),
                    'property_type' => $property_type,
                    'reportList' => array(
                        'sessions' => esc_html__('Sessions', 'wp-meta-seo'),
                        'users' => esc_html__('Users', 'wp-meta-seo'),
                        'organicSearches' => esc_html__('Organic', 'wp-meta-seo'),
                        'pageviews' => esc_html__('Page Views', 'wp-meta-seo'),
                        'visitBounceRate' => esc_html__('Bounce Rate', 'wp-meta-seo'),
                        'locations' => esc_html__('Location', 'wp-meta-seo'),
                        'contentpages' => esc_html__('Pages', 'wp-meta-seo'),
                        'referrers' => esc_html__('Referrers', 'wp-meta-seo'),
                        'searches' => esc_html__('Searches', 'wp-meta-seo'),
                        'trafficdetails' => esc_html__('Traffic', 'wp-meta-seo'),
                        'technologydetails' => esc_html__('Technology', 'wp-meta-seo'),
                    ),
                    'reportList_ga4' => array(
                        'sessions' => esc_html__('Sessions', 'wp-meta-seo'),
                        'users' => esc_html__('Users', 'wp-meta-seo'),
                        'pageviews' => esc_html__('Page Views', 'wp-meta-seo'),
                        'visitBounceRate' => esc_html__('Engagement', 'wp-meta-seo'),
                        'organicSearches' => esc_html__('Transactions', 'wp-meta-seo'),
                        'locations' => esc_html__('Location', 'wp-meta-seo'),
                        'contentpages' => esc_html__('Pages', 'wp-meta-seo'),
                        'referrers' => esc_html__('Referrers', 'wp-meta-seo'),
                        'searches' => esc_html__('Searches', 'wp-meta-seo'),
                        'trafficdetails' => esc_html__('Traffic', 'wp-meta-seo'),
                        'technologydetails' => esc_html__('Technology', 'wp-meta-seo'),
                    ),
                    'i18n' => array(
                        esc_html__('A JavaScript Error is blocking plugin resources!', 'wp-meta-seo'), //0
                        esc_html__('Traffic Mediums', 'wp-meta-seo'),
                        esc_html__('Visitor Type', 'wp-meta-seo'),
                        esc_html__('Search Engines', 'wp-meta-seo'),
                        esc_html__('Social Networks', 'wp-meta-seo'),
                        esc_html__('Sessions', 'wp-meta-seo'), //5
                        esc_html__('Users', 'wp-meta-seo'),
                        esc_html__('Page Views', 'wp-meta-seo'),
                        esc_html__('Bounce Rate', 'wp-meta-seo'),
                        esc_html__('Organic Search', 'wp-meta-seo'),
                        esc_html__('Pages/Session', 'wp-meta-seo'), //10
                        esc_html__('Invalid response', 'wp-meta-seo'),
                        esc_html__('Not enough data collected', 'wp-meta-seo'),
                        esc_html__('This report is unavailable', 'wp-meta-seo'),
                        esc_html__('report generated by', 'wp-meta-seo'), //14
                        esc_html__('This plugin needs an authorization:', 'wp-meta-seo') . '
                         <a href="' . esc_html(admin_url('admin.php?page=metaseo_google_analytics&view=wpmsga_trackcode')) . '">
                         ' . esc_html__('authorize the plugin', 'wp-meta-seo') . '</a>.',
                        esc_html__('Browser', 'wp-meta-seo'), //16
                        esc_html__('Operating System', 'wp-meta-seo'),
                        esc_html__('Screen Resolution', 'wp-meta-seo'),
                        esc_html__('Mobile Brand', 'wp-meta-seo'),
                        esc_html__('REFERRALS', 'wp-meta-seo'), //20
                        esc_html__('KEYWORDS', 'wp-meta-seo'),
                        esc_html__('SOCIAL', 'wp-meta-seo'),
                        esc_html__('CAMPAIGN', 'wp-meta-seo'),
                        esc_html__('DIRECT', 'wp-meta-seo'),
                        esc_html__('NEW', 'wp-meta-seo'), //25
                        esc_html__('You need select a profile:', 'wp-meta-seo') . '
                         <a href="' . esc_html(admin_url('admin.php?page=metaseo_google_analytics&view=wpmsga_trackcode')) . '">
                         ' . esc_html__('authorize the plugin', 'wp-meta-seo') . '</a>.',
                        esc_html__('Engagement', 'wp-meta-seo'),
                        esc_html__('Transactions', 'wp-meta-seo'),
                    ),
                    'rtLimitPages' => 10,
                    'colorVariations' => array(
                        '#1e73be',
                        '#0459a4',
                        '#378cd7',
                        '#51a6f1',
                        '#00408b',
                        '#6abfff',
                        '#002671'
                    ),
                    'region' => false,
                    'language' => get_bloginfo('language'),
                    'viewList' => false,
                    'scope' => 'admin-widgets',
                    'admin_url' => admin_url()
                )
            );

            wp_enqueue_script(
                'wpms-cookie',
                plugins_url('assets/js/jquery.cookie.js', dirname(__FILE__)),
                array(),
                '1.4.1'
            );
        }

        // Add intro text on each wpms topic
        $tippy_pages = array(
            'wp-meta-seo_page_metaseo_content_meta',
            'wp-meta-seo_page_metaseo_category_meta',
            'wp-meta-seo_page_metaseo_image_meta',
            'wp-meta-seo_page_metaseo_google_sitemap',
            'wp-meta-seo_page_metaseo_link_meta',
            'wp-meta-seo_page_metaseo_broken_link',
            'wp-meta-seo_page_metaseo_google_analytics',
            'wp-meta-seo_page_metaseo_sendemail'
        );
        if (!empty($current_screen) && in_array($current_screen->base, $tippy_pages)) {
            wp_enqueue_style('wpms-tippy-style');
            wp_enqueue_script('wpms-tippy-core');
            wp_enqueue_script('wpms-tippy');
            wp_enqueue_script('wpms-my-tippy');
        }

        if (is_plugin_active(WPMSEO_ADDON_FILENAME)) {
            $addon_active = 1;
        } else {
            $addon_active = 0;
        }
        // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
        wp_localize_script('wpmetaseoAdmin', 'wpms_localize', array(
            'filter_by' => esc_html__('Select to filter', 'wp-meta-seo'),
            'replaced' => esc_html__('Replaced', 'wp-meta-seo'),
            'index_link' => esc_html__('Loading...', 'wp-meta-seo'),
            'addon_active' => $addon_active,
            'ajax_url' => admin_url('admin-ajax.php'),
            'settings' => $this->settings,
            'wpms_cat_metatitle_length' => MPMSCAT_TITLE_LENGTH,
            'wpms_cat_metadesc_length' => MPMSCAT_DESC_LENGTH,
            'wpms_cat_metakeywords_length' => MPMSCAT_KEYWORDS_LENGTH,
            'wpms_nonce' => wp_create_nonce('wpms_nonce'),
            'home_url' => home_url(),
            'images_url' => WPMETASEO_PLUGIN_URL . 'assets/images/',
            'dashboard_tooltips' => array(
                'url_rewwrite' => esc_html__('Optimized at: ', 'wp-meta-seo'),
                'images_resized' => esc_html__('HTML image resized (using handles) count: ', 'wp-meta-seo'),
                'metatitle' => esc_html__('Meta title filled: ', 'wp-meta-seo'),
                'image_alt' => esc_html__('Image data filled (in content): ', 'wp-meta-seo'),
                'metadesc' => esc_html__('Meta description filled: ', 'wp-meta-seo'),
                'link_title' => esc_html__('Links title completed: ', 'wp-meta-seo'),
                'fresh_content' => esc_html__('Last month new or updated content: ', 'wp-meta-seo'),
                'elements' => esc_html__(' elements', 'wp-meta-seo'),
                'duplicate_title' => esc_html__('Duplicate Meta Titles: ', 'wp-meta-seo'),
                'duplicate_desc' => esc_html__('Duplicate Meta Descriptions: ', 'wp-meta-seo'),
                '404' => esc_html__('Redirected 404 errors: ', 'wp-meta-seo')
            ),
            'gg_disconnect_title' => array (
                'universal' => esc_html__('UA-Tracking ID', 'wp-meta-seo'),
                'ga4' => esc_html__('Measurement ID', 'wp-meta-seo'),
                'tagmanager' => esc_html__('Container ID', 'wp-meta-seo'),
            )
        ));


        $this->columnsEnqueue();
    }

    /**
     * Enqueue styles and scripts.
     *
     * @return void
     */
    public function columnsEnqueue()
    {
        if (function_exists('get_current_screen')) {
            $screen = get_current_screen();
            $allowed_post_types = self::getAccessiblePostTypes();

            if (!empty($current_screen) && !in_array($screen->post_type, $allowed_post_types, true)) {
                return;
            }
        }

        wp_enqueue_style(
            'wpms-post-bulk-edit',
            plugins_url('assets/css/wpms-post-bulk-edit.css', dirname(__FILE__)),
            null,
            WPMSEO_VERSION
        );

        wp_enqueue_script(
            'wpms-post-bulk-edit-js',
            plugins_url('assets/js/wpms-post-bulk-edit.js', dirname(__FILE__)),
            array('jquery'),
            WPMSEO_VERSION,
            true
        );
        wp_localize_script('wpms-post-bulk-edit-js', 'wpms_post_bulk', array(
            'title' => esc_attr__('Bulk Edit This Field', 'wp-meta-seo'),
            'saveAll' => esc_attr__('Save all', 'wp-meta-seo'),
            'cancelButton' => esc_attr__('Cancel all', 'wp-meta-seo'),
            'nonce' => wp_create_nonce('wpms_nonce')
        ));
    }

    /**
     * Add content for title column.
     *
     * @param integer $post_id The current post ID.
     *
     * @return void
     */
    public function getColumnSeoKeywordsDetails($post_id)
    {
        $score = get_post_meta($post_id, 'wp_metaseo_seoscore', true);
        $keywords = get_post_meta($post_id, '_metaseo_metaspecific_keywords', true);
        $class = !empty($score) ? $this->getScoreClass((int)$score) : 'wpms-no-score';
        $score = !empty($score) ? $score . ' / 100' : 'N/A';

        ?>
        <span class="wpms-column-display score <?php echo esc_attr($class); ?> <?php echo empty($score) ? 'disabled' : ''; ?>">
            <strong><?php echo esc_html($score); ?></strong>
        </span>
        <span class="wpms-column-display keyword" <?php echo (empty($keywords)) ? 'style="display:none"' : ''; ?> >
                <strong title="SEO Keyword" class="title"><?php esc_html_e('Keyword', 'wp-meta-seo'); ?>:</strong>
                <span><?php echo $keywords ? esc_html($keywords) : ''; ?></span>
            </span>
        <span class="wpms-column-value" data-field="seo_keyword" contenteditable="true" tabindex="11">
                <span><?php echo esc_html($keywords); ?></span>
            </span>
        <div class="wpms-column-edit">
            <a href="#" class="wpms-column-save"><?php esc_html_e('Save', 'wp-meta-seo'); ?></a>
            <a href="#" class="button-link-delete wpms-column-cancel"><?php esc_html_e('Cancel', 'wp-meta-seo'); ?></a>
        </div>
        <?php
    }

    /**
     * Get SEO score rating string: great/good/bad.
     *
     * @param integer $score Score.
     *
     * @return string
     */
    private function getScoreClass($score)
    {
        if ($score > 75) {
            return 'wpms-great-score';
        }

        return 'wpms-bad-score';
    }

    /**
     * Register post column hooks.
     *
     * @return void
     */
    public function registerPostColumns()
    {
        foreach (self::getAccessiblePostTypes() as $post_type) {
            add_filter('edd_download_columns', array($this, 'addColumns'), 11);
            add_filter('manage_' . $post_type . '_posts_columns', array($this, 'addColumns'), 11);


            add_action('manage_' . $post_type . '_posts_custom_column', array($this, 'columnsContents'), 11, 2);
            add_filter('manage_edit-' . $post_type . '_sortable_columns', array($this, 'sortableColumns'), 11);
        }
    }

    /**
     * Make the SEO Score column sortable.
     *
     * @param array $columns Array of column names.
     *
     * @return array
     */
    public function sortableColumns($columns)
    {

        $columns['metaseo_seokeywords_details_column'] = 'wp_metaseo_seoscore';

        return $columns;
    }

    /**
     * Add content for custom column.
     *
     * @param string  $column_name The name of the column to display.
     * @param integer $post_id     The current post ID.
     *
     * @return void
     */
    public function columnsContents($column_name, $post_id)
    {
        do_action($column_name, $post_id);
    }

    /**
     * Add new columns for SEO title, description and focus keywords.
     *
     * @param array $columns Array of column names.
     *
     * @return array
     */
    public function addColumns($columns)
    {
        $columns['metaseo_seokeywords_details_column'] = esc_html__('SEO score', 'wp-meta-seo');

        return $columns;
    }

    /**
     * Get post types that are public and not set to noindex.
     *
     * @codeCoverageIgnore
     *
     * @return array All the accessible post types.
     */
    public static function getAccessiblePostTypes()
    {
        static $accessible_post_types;

        if (isset($accessible_post_types) && did_action('wp_loaded')) {
            return $accessible_post_types;
        }

        $accessible_post_types = get_post_types(array('public' => true));
        $accessible_post_types = array_filter($accessible_post_types, 'is_post_type_viewable');

        if (!is_array($accessible_post_types)) {
            $accessible_post_types = array();
        }

        if (isset($accessible_post_types['elementor_library'])) {
            unset($accessible_post_types['elementor_library']);
        }

        return $accessible_post_types;
    }

    /**
     * Update post meta keywords
     *
     * @return void
     */
    public function updateSeokeywordBulkEdit()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (!isset($_POST['listData']) || empty($_POST['listData'])) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            wp_die(__('Unspecified list data', 'wp-meta-seo'));
        }

        $result = array();
        foreach ($_POST['listData'] as $data) {
            if (empty($data['idpost'])) {
                continue;
            }

            $circliful = $this->doAnalysisFromBulkEdit((int)$data['idpost'], $data['keyword']);
            update_post_meta((int)$data['idpost'], '_metaseo_metaspecific_keywords', $data['keyword']);
            update_post_meta((int)$data['idpost'], 'wp_metaseo_seoscore', $circliful);
            $result[(int)$data['idpost']] = array($circliful, $data['keyword']);
        }

        wp_send_json(array('status' => true, 'res_back' => $result));
    }

    /**
     * Ajax load analysis from post edit
     *
     * @param integer $post_id  Id post
     * @param string  $keywords Keyword search
     *
     * @return mixed
     */
    public function doAnalysisFromBulkEdit($post_id, $keywords)
    {
        if (empty($_POST['wpms_nonce']) || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (!current_user_can('edit_posts')) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            wp_die(__('There is no edit permission here', 'wp-meta-seo'));
        }

        $content_post = get_post($post_id);
        $content = apply_filters('the_content', $content_post->post_content);
        $title = !(empty($content_post->post_title)) ? $content_post->post_title : null;
        $meta_title = get_post_meta($post_id, '_metaseo_metatitle', true);
        $meta_desc = get_post_meta($post_id, '_metaseo_metadesc', true);
        $check = 0;

        // title heading
        $words_post_title = preg_split(
            '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
            strtolower($title),
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        // do shortcode js_composer plugin
        if (is_plugin_active('js_composer_theme/js_composer.php')) {
            add_shortcode('mk_fancy_title', 'vc_do_shortcode');
        }

        $content = apply_filters('wpms_the_content', '<div>' . html_entity_decode(stripcslashes($content), ENT_COMPAT, 'UTF-8') . '</div>', $post_id);
        $content = $this->injectAcfField($content, $post_id);
        $content = $this->injectWooCommerce($content, $post_id);

        if ($content !== '') {
            // Extracting the specified elements from the web page
            $tags_h1 = wpmsExtractTags($content, 'h1', false, true);
            $tags_h2 = wpmsExtractTags($content, 'h2', false, true);
            $tags_h3 = wpmsExtractTags($content, 'h3', false, true);
            $tags_h4 = wpmsExtractTags($content, 'h4', false, true);
            $tags_h5 = wpmsExtractTags($content, 'h5', false, true);
            $tags_h6 = wpmsExtractTags($content, 'h6', false, true);

            $test = false;
            if (empty($tags_h1) && empty($tags_h2) && empty($tags_h3)
                && empty($tags_h4) && empty($tags_h5) && empty($tags_h6)) {
                $test = false;
            } else {
                // check tag h1
                if (!empty($tags_h1)) {
                    foreach ($tags_h1 as $order => $tagh1) {
                        $words_tagh1 = preg_split(
                            '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
                            utf8_decode(strtolower($tagh1['contents'])),
                            -1,
                            PREG_SPLIT_NO_EMPTY
                        );

                        if (is_array($words_tagh1) && is_array($words_post_title)) {
                            foreach ($words_tagh1 as $mh) {
                                if (in_array($mh, $words_post_title) && $mh !== '') {
                                    $test = true;
                                }
                            }
                        }
                    }
                }

                // check tag h2
                if (!empty($tags_h2)) {
                    foreach ($tags_h2 as $order => $tagh2) {
                        $words_tagh2 = preg_split(
                            '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
                            utf8_decode(strtolower($tagh2['contents'])),
                            -1,
                            PREG_SPLIT_NO_EMPTY
                        );
                        if (is_array($words_tagh2) && is_array($words_post_title)) {
                            foreach ($words_tagh2 as $mh) {
                                if (in_array($mh, $words_post_title) && $mh !== '') {
                                    $test = true;
                                }
                            }
                        }
                    }
                }

                // check tag h3
                if (!empty($tags_h3)) {
                    foreach ($tags_h3 as $order => $tagh3) {
                        $words_tagh3 = preg_split(
                            '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
                            utf8_decode(strtolower($tagh3['contents'])),
                            -1,
                            PREG_SPLIT_NO_EMPTY
                        );
                        if (is_array($words_tagh3) && is_array($words_post_title)) {
                            foreach ($words_tagh3 as $mh) {
                                if (in_array($mh, $words_post_title) && $mh !== '') {
                                    $test = true;
                                }
                            }
                        }
                    }
                }

                // check tag h4
                if (!empty($tags_h4)) {
                    foreach ($tags_h4 as $order => $tagh4) {
                        $words_tagh4 = preg_split(
                            '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
                            utf8_decode(strtolower($tagh4['contents'])),
                            -1,
                            PREG_SPLIT_NO_EMPTY
                        );
                        if (is_array($words_tagh4) && is_array($words_post_title)) {
                            foreach ($words_tagh4 as $mh) {
                                if (in_array($mh, $words_post_title) && $mh !== '') {
                                    $test = true;
                                }
                            }
                        }
                    }
                }

                // check tag h5
                if (!empty($tags_h5)) {
                    foreach ($tags_h5 as $order => $tagh5) {
                        $words_tagh5 = preg_split(
                            '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
                            utf8_decode(strtolower($tagh5['contents'])),
                            -1,
                            PREG_SPLIT_NO_EMPTY
                        );
                        if (is_array($words_tagh5) && is_array($words_post_title)) {
                            foreach ($words_tagh5 as $mh) {
                                if (in_array($mh, $words_post_title) && $mh !== '') {
                                    $test = true;
                                }
                            }
                        }
                    }
                }

                // check tag h6
                if (!empty($tags_h6)) {
                    foreach ($tags_h6 as $order => $tagh6) {
                        $words_tagh6 = preg_split(
                            '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
                            utf8_decode(strtolower($tagh6['contents'])),
                            -1,
                            PREG_SPLIT_NO_EMPTY
                        );
                        if (is_array($words_tagh6) && is_array($words_post_title)) {
                            foreach ($words_tagh6 as $mh) {
                                if (in_array($mh, $words_post_title) && $mh !== '') {
                                    $test = true;
                                }
                            }
                        }
                    }
                }
            }

            if ($test) {
                $check++;
            }
        }

        // title content
        $words_title = preg_split(
            '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
            strtolower($title),
            -1,
            PREG_SPLIT_NO_EMPTY
        );
        $words_post_content = preg_split(
            '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/',
            strtolower(strip_tags($content)),
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        $test1 = false;
        if (is_array($words_title) && is_array($words_post_content)) {
            foreach ($words_title as $mtitle) {
                if (in_array($mtitle, $words_post_content) && $mtitle !== '') {
                    $test1 = true;
                    break;
                }
            }
        } else {
            $test1 = false;
        }

        if ($test1) {
            $check++;
        }

        // page url matches page title
        $mtitle = $title;
        $pageurl = pathinfo(get_permalink($post_id));
        $mpageurl = $pageurl['filename'];

        if (!empty($mpageurl) && !empty($mtitle) && $mpageurl === sanitize_title($mtitle)) {
            $check++;
        }

        // meta title filled
        if (($meta_title !== '' && mb_strlen($meta_title, 'UTF-8') <= self::$title_length)) {
            $check++;
        }

        // desc filled
        if ($meta_desc !== '' && mb_strlen($meta_desc, 'UTF-8') <= self::$desc_length) {
            $check++;
        }

        // image resize
        if ($content === '') {
            $check += 2;
        } else {
            // Extracting the specified elements from the web page
            $img_tags = wpmsExtractTags($content, 'img', true, true);
            $img_wrong = false;
            $img_wrong_alt = false;
            foreach ($img_tags as $order => $tag) {
                if (!isset($tag['attributes']['src'])) {
                    continue;
                }

                $src = $tag['attributes']['src'];
                $imgpath = str_replace(site_url(), ABSPATH, $src);
                if (!file_exists($imgpath)) {
                    continue;
                }
                if (!list($width_origin, $height_origin) = getimagesize($imgpath)) {
                    continue;
                }

                if (empty($tag['attributes']['width']) && empty($tag['attributes']['height'])) {
                    $img_wrong = false;
                } else {
                    if (!empty($width_origin) && !empty($height_origin)) {
                        if ((isset($tag['attributes']['width']) && (int)$width_origin !== (int)$tag['attributes']['width'])
                            || (isset($tag['attributes']['height']) && (int)$height_origin !== (int)$tag['attributes']['height'])) {
                            $img_wrong = true;
                        }
                    }
                }

                if (empty($tag['attributes']['alt'])) {
                    $img_wrong_alt = true;
                }
            }

            if (!$img_wrong) {
                $check++;
            }


            if (!$img_wrong_alt) {
                $check++;
            }
        }

        $total_check = 7;
        if (!empty($keywords)) {
            $total_check++;

            $listkeywords = array_map('trim', explode(',', strtolower($keywords)));

            foreach ($listkeywords as $key) {
                if (strpos(strtolower($title), $key) !== false) {
                    $check++;
                    break;
                }

                if (strpos(strtolower($content), $key) !== false) {
                    $check++;
                    break;
                }

                if (strpos(strtolower($meta_title), $key) !== false) {
                    $check++;
                    break;
                }

                if (strpos(strtolower($meta_desc), $key) !== false) {
                    $check++;
                    break;
                }

                $pattern = '/<h[2-6][^>]*>.*' . $key . '.*<\/h[2-6]>/';
                if (preg_match($pattern, $content)) {
                    $check++;
                    break;
                }
            }
        }

        $circliful = ceil(100 * ($check) / $total_check);

        return $circliful;
    }

    /**
     * Localize a script.
     *
     * Works only if the script has already been added.
     *
     * @return array
     */
    public function localizeScript()
    {
        return array(
            'metaseo_message_false_copy' => esc_html__('Warning, you\'re about to replace existing image
             alt or tile content, are you sire about that?', 'wp-meta-seo'),
        );
    }

    /**
     * Add a top-level menu page.
     *
     * This function takes a capability which will be used to determine whether
     * or not a page is included in the menu.
     *
     * @return void
     */
    public function addMenuPage()
    {
        // Add main page
        add_menu_page(
            esc_html__('WP Meta SEO: Dashboard', 'wp-meta-seo'),
            esc_html__('WP Meta SEO', 'wp-meta-seo'),
            'manage_options',
            'metaseo_dashboard',
            array(
                $this,
                'loadPage'
            ),
            'dashicons-chart-area'
        );

        /**
         * Allow changing the capability users need to view the settings pages
         *
         * @return string
         * @api    string Default capability
         */
        $manage_options_cap = apply_filters('metaseo_manage_options_capability', 'manage_options');

        // Sub menu pages
        $submenu_pages = array(
            array(
                'metaseo_dashboard',
                '',
                esc_html__('Dashboard', 'wp-meta-seo'),
                $manage_options_cap,
                'metaseo_dashboard',
                array($this, 'loadPage'),
                null
            ),
            array(
                'metaseo_dashboard',
                '',
                esc_html__('Content meta', 'wp-meta-seo'),
                $manage_options_cap,
                'metaseo_content_meta',
                array($this, 'loadPage'),
                null
            ),
            array(
                'metaseo_dashboard',
                '',
                esc_html__('Category meta', 'wp-meta-seo'),
                $manage_options_cap,
                'metaseo_category_meta',
                array($this, 'loadPage'),
                null
            ),
            array(
                'metaseo_dashboard',
                '',
                esc_html__('Image editor', 'wp-meta-seo'),
                $manage_options_cap,
                'metaseo_image_meta',
                array($this, 'loadPage'),
                null
            ),
            array(
                'metaseo_dashboard',
                '',
                esc_html__('Sitemap', 'wp-meta-seo'),
                $manage_options_cap,
                'metaseo_google_sitemap',
                array($this, 'loadPage'),
                null,
            ),
            array(
                'metaseo_dashboard',
                '',
                esc_html__('Link editor', 'wp-meta-seo'),
                $manage_options_cap,
                'metaseo_link_meta',
                array($this, 'loadPage'),
                null
            ),
            array(
                'metaseo_dashboard',
                '',
                esc_html__('404 & Redirects', 'wp-meta-seo'),
                $manage_options_cap,
                'metaseo_broken_link',
                array($this, 'loadPage'),
                null
            ),
            array(
                'metaseo_dashboard',
                '',
                esc_html__('Google Analytics', 'wp-meta-seo'),
                $manage_options_cap,
                'metaseo_google_analytics',
                array($this, 'loadPage'),
                null
            )
        );

        if (is_plugin_active(WPMSEO_ADDON_FILENAME)) {
            global $metaseo_addon;
            $submenu_pages[] = array(
                'metaseo_dashboard',
                esc_html__('WP Meta SEO Send email', 'wp-meta-seo'),
                esc_html__('Email report', 'wp-meta-seo'),
                $manage_options_cap,
                'metaseo_sendemail',
                array($metaseo_addon, 'loadPageSendEmail'),
                null
            );

            // REMOVE SEARCH CONSOLE TAB, WAIT NEW API FROM GOOGLE GUYS
            //
            //
            //
//            $submenu_pages[] = array(
//                'metaseo_dashboard',
//                esc_html__('Search Console', 'wp-meta-seo'),
//                esc_html__('Search Console', 'wp-meta-seo'),
//                $manage_options_cap,
//                'metaseo_console',
//                array($metaseo_addon->admin_features['metaseo_gsc'], 'display'),
//                null
//            );
        }

        $submenu_pages[] = array(
            'metaseo_dashboard',
            '',
            esc_html__('Settings', 'wp-meta-seo'),
            $manage_options_cap,
            'metaseo_settings',
            array($this, 'loadPage'),
            null
        );

        if (!is_plugin_active(WPMSEO_ADDON_FILENAME)) {
            $submenu_pages[] = array(
                'metaseo_dashboard',
                '',
                '<span style="color:orange">' . esc_html__('Better SEO ranking', 'wp-meta-seo') . '</span>',
                $manage_options_cap,
                'metaseo_better_ranking',
                array($this, 'loadPage'),
                null
            );
        }

        // Allow submenu pages manipulation
        $submenu_pages = apply_filters('metaseo_submenu_pages', $submenu_pages);

        // Loop through submenu pages and add them
        if (count($submenu_pages)) {
            foreach ($submenu_pages as $submenu_page) {
                // Add submenu page
                $admin_page = add_submenu_page(
                    $submenu_page[0],
                    $submenu_page[2] . ' - ' . esc_html__('WP Meta SEO:', 'wp-meta-seo'),
                    $submenu_page[2],
                    $submenu_page[3],
                    $submenu_page[4],
                    $submenu_page[5]
                );

                // Check if we need to hook
                if (isset($submenu_page[6]) && null !== $submenu_page[6]
                    && is_array($submenu_page[6]) && count($submenu_page[6]) > 0) {
                    foreach ($submenu_page[6] as $submenu_page_action) {
                        add_action('load-' . $admin_page, $submenu_page_action);
                    }
                }
            }
        }
    }

    /**
     * Set error time out
     *
     * @return void
     */
    public function setErrorTimeout()
    {
        $midnight = strtotime('tomorrow 00:00:00'); // UTC midnight
        $midnight = $midnight + 8 * 3600; // UTC 8 AM
        $this->error_timeout = $midnight - time();
    }

    /**
     * Load the form for a WPSEO admin page
     *
     * @return void
     */
    public function loadPage()
    {
        if (isset($_GET['page'])) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
            switch ($_GET['page']) {
                case 'metaseo_google_analytics':
                    $this->google_alanytics = get_option('wpms_google_alanytics');
                    if (isset($this->google_alanytics['setting_success']) && $this->google_alanytics['setting_success'] === 1) {
                        echo '<div id="setting-error-settings_updated"
 class="updated settings-error notice is-dismissible"> 
<p><strong>' . esc_html__('Settings saved.', 'wp-meta-seo') . '</strong></p><button type="button" class="notice-dismiss">
<span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                        $this->google_alanytics['setting_success'] = 0;
                        update_option('wpms_google_alanytics', $this->google_alanytics);
                    }
                    if (isset($_POST['_metaseo_ggtracking_settings'])) {
                        if (empty($_POST['gadash_security'])
                            || !wp_verify_nonce($_POST['gadash_security'], 'gadash_form')) {
                            die();
                        }
                        update_option('_metaseo_ggtracking_settings', $_POST['_metaseo_ggtracking_settings']);
                        echo '<div id="setting-error-settings_updated"
 class="updated settings-error notice is-dismissible"> 
<p><strong>' . esc_html__('Settings saved.', 'wp-meta-seo') . '</strong></p><button type="button" class="notice-dismiss">
<span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                    }

                    // Update selected profile
                    if (!empty($_POST['tableid_jail'])) {
                        if (empty($_POST['wpms_nonce'])
                            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
                            die();
                        }
                        if ($this->google_alanytics['tableid_jail'] !== $_POST['tableid_jail']) {
                            $this->google_alanytics['tableid_jail'] = $_POST['tableid_jail'];
                            $this->google_alanytics['setting_success'] = 1;
                            update_option('wpms_google_alanytics', $this->google_alanytics);
                            wp_redirect($_SERVER['HTTP_REFERER']);
                        }
                    }

                    $ga_tracking = get_option('_metaseo_ggtracking_settings');
                    if (is_array($ga_tracking)) {
                        $this->ga_tracking = array_merge($this->ga_tracking, $ga_tracking);
                    }
                    include_once(WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmstools.php');
                    include_once(WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmsgapi.php');
                    wp_enqueue_style('wpms-tippy-style');
                    wp_enqueue_script('wpms-tippy-core');
                    wp_enqueue_script('wpms-tippy');
                    // Enqueue snackbar
                    wp_enqueue_style('wpms-snackbar-style');
                    wp_enqueue_script('wpms-snackbar-script');

                    // WPMS Google Analytics Data
                    if (isset($_GET['view']) && $_GET['view'] === 'wpms_gg_service_data') {
                        wp_enqueue_style('wpms-backend-tracking-code');
                        wp_enqueue_style('wpms-backend-item-reports');

                        // When user save access code
                        if (isset($_POST['wpmsga_dash_clientid']) && isset($_POST['wpmsga_dash_clientsecret'])) {
                            // Check nonce field
                            if (empty($_POST['wpms_nonce'])
                                || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
                                die();
                            }
                            $this->google_alanytics['wpmsga_dash_clientid'] = $_POST['wpmsga_dash_clientid'];
                            $this->google_alanytics['wpmsga_dash_clientsecret'] = $_POST['wpmsga_dash_clientsecret'];
                            update_option('wpms_google_alanytics', $this->google_alanytics);
                        }

                        require_once WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmstools.php';
                        if (!empty($this->google_alanytics['wpmsga_dash_clientid']) && !empty($this->google_alanytics['wpmsga_dash_clientsecret'])) {
                            $this->client = WpmsGaTools::initClient($this->google_alanytics['wpmsga_dash_clientid'], $this->google_alanytics['wpmsga_dash_clientsecret']);
                            $controller = new WpmsGapiController();
                            $this->service = new \WPMSGoogle\Service\Analytics($this->client);
                        }

                        $this->setErrorTimeout();

                        if (!empty($_POST['wpms_gg_access_code'])) {
                            $wpms_gg_access_code = $_POST['wpms_gg_access_code'];
                            if (!stripos('x' . $wpms_gg_access_code, 'UA-', 1)) {
                                WpmsGaTools::deleteCache('gapi_errors');
                                WpmsGaTools::deleteCache('last_error');
                                WpmsGaTools::clearCache();
                                try {
                                    $this->client->authenticate($wpms_gg_access_code);
                                    $getAccessToken = $this->client->getAccessToken();
                                    if ($getAccessToken) {
                                        try {
                                            $this->client->setAccessToken($getAccessToken);
                                            $this->google_alanytics['googleCredentials']
                                                = $this->client->getAccessToken();
                                        } catch (WPMSGoogle\Service\Exception $e) {
                                            WpmsGaTools::setCache(
                                                'wpmsga_dash_lasterror',
                                                date('Y-m-d H:i:s') . ': ' . esc_html('(' . $e->getCode() . ') ' . $e->getMessage()),
                                                $this->error_timeout
                                            );
                                            WpmsGaTools::setCache(
                                                'wpmsga_dash_gapi_errors',
                                                $e->getCode(),
                                                $this->error_timeout
                                            );
                                        } catch (Exception $e) {
                                            WpmsGaTools::setCache(
                                                'wpmsga_dash_lasterror',
                                                date('Y-m-d H:i:s') . ': ' . esc_html($e),
                                                $this->error_timeout
                                            );
                                        }
                                    }

                                    if (!empty($this->google_alanytics['profile_list'])) {
                                        $profiles = $this->google_alanytics['profile_list'];
                                    } else {
                                        $profiles = WpmsGaTools::refreshProfiles($this->service, $getAccessToken['access_token'], $this->error_timeout);
                                    }

                                    $this->google_alanytics['code'] = $wpms_gg_access_code;
                                    $this->google_alanytics['googleCredentials'] = $getAccessToken;
                                    $this->google_alanytics['profile_list'] = $profiles;
                                    update_option('wpms_google_alanytics', $this->google_alanytics);
                                } catch (WPMSGoogle\Service\Exception $e) {
                                    echo '';
                                } catch (Exception $e) {
                                    echo '';
                                }
                            } else {
                                echo '<div class="error"><p>' . esc_html__('The access code is
<strong>NOT</strong> your <strong>Tracking ID</strong>
 (UA-XXXXX-X). Try again, and use the red link to get your access code', 'wp-meta-seo') . '.</p></div>';
                            }
                            update_option('wpms_google_alanytics', $this->google_alanytics);
                            wp_redirect($_SERVER['HTTP_REFERER']);
                            exit;
                        }
                        $gg_analytcis = get_option('wpms_google_alanytics');
                        if (is_array($gg_analytcis)) {
                            $this->google_alanytics = array_merge($gg_analytcis, $this->google_alanytics);
                        }
                        // If user have google credentials
                        if (!empty($this->google_alanytics['googleCredentials'])) {
                            // WPMS google analytics setting
                            include_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/google-services/ga-trackcode.php');
                            // Google analytics data
                            if (!empty($this->google_alanytics['tableid_jail'])) {
                                echo '<h1 class="wpms-top-h1">' . esc_html__('Google Analytics Report', 'wp-meta-seo') . '
                            <i class="material-icons intro-topic-tooltip" data-tippy="' . esc_html__('Create a Google Analytics property then connect WordPress to this Analytics property. You can then follow your traffic and include the data in your Email report (Pro Addon)', 'wp-meta-seo') . '">help_outline</i>
                            </h1>';
                                echo '<div id="wpms-window-1">
                                 <!-- WPMS Google analytics response using JS -->

                                    </div>';
                            }
                        } else {
                            include_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/google-services/gg-services-connect.php');
                        }
                    } else {
                        // WPMS Google Service Tracking - when user don't have Google cloud credentials
                        $gaDisconnect = get_option('_metaseo_ggtracking_disconnect_settings');
                        if (is_array($gaDisconnect)) {
                            $this->gaDisconnect = array_merge(
                                $this->gaDisconnect,
                                $gaDisconnect
                            );
                        }

                        // When user submit tracking options
                        if (isset($_POST['_metaseo_gg_service_disconnect'])) {
                            // Check none field
                            if (empty($_POST['wpms_nonce'])
                                || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
                                die();
                            }
                            // Sanitizing submit data and save options
                            $_metaseo_ga_disconnect = $_POST['_metaseo_gg_service_disconnect'];
                            $_metaseo_ga_disconnect['wpms_gg_service_tracking_id'] = sanitize_text_field($_metaseo_ga_disconnect['wpms_gg_service_tracking_id']);
                            $_metaseo_ga_disconnect['wpms_gg_service_tracking_type'] = sanitize_text_field($_metaseo_ga_disconnect['wpms_gg_service_tracking_type']);
                            $_metaseo_ga_disconnect['wpmsga_code_tracking'] = stripslashes($_metaseo_ga_disconnect['wpmsga_code_tracking']);
                            $_metaseo_ga_disconnect['wpmstm_header_code_tracking'] = stripslashes($_metaseo_ga_disconnect['wpmstm_header_code_tracking']);
                            $_metaseo_ga_disconnect['wpmstm_body_code_tracking'] = stripslashes($_metaseo_ga_disconnect['wpmstm_body_code_tracking']);
                            update_option(
                                '_metaseo_ggtracking_disconnect_settings',
                                $_metaseo_ga_disconnect
                            );
                            $gaDisconnect = get_option('_metaseo_ggtracking_disconnect_settings');
                            if (is_array($gaDisconnect)) {
                                $this->gaDisconnect = array_merge(
                                    $this->gaDisconnect,
                                    $gaDisconnect
                                );
                            }
                        }
                        require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/google-services/google-services.php');
                    }

                    $w = '99%';
                    $text = esc_html__('Bring your WordPress website SEO to the next level with the PRO Addon: Email Report,
                     Google Search Console Connect, Automatic Redirect, Advanced Sitemaps and more!', 'wp-meta-seo');
                    $class_btn_close = 'close_gga';
                    require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/notification.php');
                    break;
                case 'metaseo_google_sitemap':
                    if (is_plugin_active(WPMSEO_ADDON_FILENAME)) {
                        // remove filter by lang
                        if (is_plugin_active('sitepress-multilingual-cms/sitepress.php')) {
                            global $sitepress;
                            remove_filter('terms_clauses', array($sitepress, 'terms_clauses'));
                        } elseif (is_plugin_active('polylang/polylang.php')) {
                            global $polylang;
                            $filters_term = $polylang->filters_term;
                            remove_filter('terms_clauses', array($filters_term, 'terms_clauses'));
                        }
                    }

                    if (!class_exists('MetaSeoSitemap')) {
                        require_once(WPMETASEO_PLUGIN_DIR . '/inc/class.metaseo-sitemap.php');
                    }

                    $sitemap = new MetaSeoSitemap();
                    require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/sitemaps/metaseo-google-sitemap.php');
                    break;
                case 'metaseo_broken_link':
                    require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/metaseo-broken-link.php');
                    break;
                case 'metaseo_settings':
                    if (isset($_POST['_metaseo_settings'])) {
                        if (empty($_POST['wpms_nonce'])
                            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
                            die();
                        }
                        update_option('_metaseo_settings', $_POST['_metaseo_settings']);
                        if (isset($_POST['_metaseo_settings']['wpms_save_general'])) {
                            $_SESSION['_metaseo_settings_general'] = 1;
                        }
                        if (isset($_POST['_metaseo_settings']['wpms_save_social'])) {
                            $_SESSION['_metaseo_settings_social'] = 1;
                        }
                        if (isset($_POST['gscapi']['save'])) {
                            $_SESSION['_metaseo_settings_search_console'] = 1;
                        }
                    }

                    $posts = get_posts(array('post_type' => 'page', 'posts_per_page' => -1, 'numberposts' => -1));
                    $types_404 = array(
                        'none' => 'None',
                        'wp-meta-seo-page' => esc_html__('WP Meta SEO page', 'wp-meta-seo'),
                        'custom_page' => esc_html__('Custom page', 'wp-meta-seo')
                    );

                    // get settings 404
                    $defaul_settings_404 = array(
                        'wpms_redirect_homepage' => 0,
                        'wpms_type_404' => 'none',
                        'wpms_page_redirected' => 'none'
                    );
                    $wpms_settings_404 = get_option('wpms_settings_404');
                    if (is_array($wpms_settings_404)) {
                        $defaul_settings_404 = array_merge($defaul_settings_404, $wpms_settings_404);
                    }

                    // get settings breadcrumb
                    $home_title = get_the_title(get_option('page_on_front'));
                    if (empty($home_title)) {
                        $home_title = get_bloginfo('title');
                    }
                    $breadcrumbs = array(
                        'separator' => ' &gt; ',
                        'include_home' => 1,
                        'home_text' => $home_title,
                        'home_text_default' => 0,
                        'clickable' => 1
                    );
                    $breadcrumb_settings = get_option('_metaseo_breadcrumbs');
                    if (is_array($breadcrumb_settings)) {
                        $breadcrumbs = array_merge($breadcrumbs, $breadcrumb_settings);
                    }

                    // email settings
                    $email_settings = array(
                        'enable' => 0,
                        'host' => 'smtp.gmail.com',
                        'type_encryption' => 'ssl',
                        'port' => '465',
                        'autentication' => 'yes',
                        'username' => '',
                        'password' => '',
                    );

                    $mailsettings = get_option('wpms_email_settings');
                    if (is_array($mailsettings)) {
                        $email_settings = array_merge($email_settings, $mailsettings);
                    }

                    $html_tabemail = apply_filters('wpmsaddon_emailsettings', '', $email_settings);

                    // link settings
                    $link_settings = array(
                        'enable' => 0,
                        'numberFrequency' => 1,
                        'showlinkFrequency' => 'month'
                    );

                    $linksettings = get_option('wpms_link_settings');
                    if (is_array($linksettings)) {
                        $link_settings = array_merge($link_settings, $linksettings);
                    }

                    $link_settings_html = apply_filters('wpmsaddon_linksettings', '', $link_settings);

                    // local business settings
                    $local_business = array(
                        'enable' => 0,
                        'logo' => '',
                        'type_name' => '',
                        'country' => '',
                        'address' => '',
                        'city' => '',
                        'state' => '',
                        'phone' => '',
                        'pricerange' => '$$'
                    );

                    $business = get_option('wpms_local_business');
                    if (is_array($business)) {
                        $local_business = array_merge($local_business, $business);
                    }
                    $countrys = apply_filters('wpms_get_countryList', array());
                    $local_business_html = apply_filters('wpmsaddon_local_business', '', $local_business, $countrys);

                    $search_console_html = apply_filters('wpmsaddon_search_console', '');

                    $default_settings = wpmsGetDefaultSettings();
                    $settings = get_option('_metaseo_settings');
                    if (is_array($settings)) {
                        $settings = array_merge($default_settings, $settings);
                    } else {
                        $settings = $default_settings;
                    }

                    foreach ($settings as $setting_name => $setting_value) {
                        ${$setting_name} = $setting_value;
                    }

                    $home_meta_active = wpmsGetOption('home_meta_active');
                    require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/settings.php');
                    break;

                case 'metaseo_content_meta':
                    require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/content-meta.php');
                    break;

                case 'metaseo_category_meta':
                    require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/category-meta.php');
                    break;

                case 'metaseo_image_meta':
                    require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/image-meta.php');
                    break;

                case 'metaseo_link_meta':
                    require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/link-meta.php');
                    break;

                case 'metaseo_image_optimize':
                    require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/image-optimize.php');
                    break;

                case 'metaseo_better_ranking':
                    require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/better_seo.php');
                    break;
                case 'metaseo_dashboard':
                default:
                    require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/dashboard.php');
                    break;
            }
        }
    }

    /**
     * Ajax check attachment have alt empty
     *
     * @return void
     */
    public function checkExist()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (isset($_POST['action_name'])) {
            switch ($_POST['action_name']) {
                case 'img-copy-alt':
                    $margs = array(
                        'posts_per_page' => -1,
                        'post_type' => 'attachment',
                        'post_status' => 'any',
                        'meta_query' => array(
                            'relation' => 'OR',
                            array(
                                'key' => '_wp_attachment_image_alt',
                                'value' => '',
                                'compare' => '!='
                            )
                        )
                    );

                    $m_newquery = new WP_Query($margs);
                    $mposts_empty_alt = $m_newquery->get_posts();
                    if (!empty($mposts_empty_alt)) {
                        wp_send_json(true);
                    } else {
                        wp_send_json(false);
                    }
                    break;

                case 'img-copy-title':
                    global $wpdb;
                    $check_title = $wpdb->get_var('SELECT COUNT(posts.ID) as total FROM ' . $wpdb->prefix . 'posts as posts
                     WHERE posts.post_type = "attachment" AND post_title != ""');
                    if ($check_title > 0) {
                        wp_send_json(true);
                    } else {
                        wp_send_json(false);
                    }
                    break;
                case 'img-copy-desc':
                    global $wpdb;
                    $check_title = $wpdb->get_var('SELECT COUNT(posts.ID) as total FROM ' . $wpdb->prefix . 'posts as posts
                     WHERE posts.post_type = "attachment" AND post_content != ""');
                    if ($check_title > 0) {
                        wp_send_json(true);
                    } else {
                        wp_send_json(false);
                    }
                    break;
            }
        }
    }

    /**
     * Ajax update image alt and image title
     *
     * @return void
     */
    public function bulkImageCopy()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        global $wpdb;
        if (empty($_POST['action_name'])) {
            wp_send_json(false);
        }

        if (isset($_POST['sl_bulk']) && $_POST['sl_bulk'] === 'all') {
            // select all
            $limit = 500;
            // query attachment and update meta
            $total = $wpdb->get_var('SELECT COUNT(posts.ID) as total FROM ' . $wpdb->prefix . 'posts as posts
                     WHERE posts.post_type = "attachment"');

            $j = ceil((int)$total / $limit);
            for ($i = 0; $i <= $j; $i++) {
                $ofset = $i * $limit;
                $attachments = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'posts as posts
                       WHERE posts.post_type = %s LIMIT %d OFFSET %d', array('attachment', $limit, $ofset)));

                foreach ($attachments as $attachment) {
                    $i_info_url = pathinfo($attachment->guid);
                    switch ($_POST['action_name']) {
                        case 'img-copy-alt':
                            $value = $i_info_url['filename'];
                            /**
                             * Filter before update meta for image
                             *
                             * @param string  Meta value
                             * @param integer Image ID
                             * @param string  Meta key
                             * @param array   Extra informations
                             *
                             * @return string
                             */
                            $value = apply_filters('wpms_update_image_meta', $value, $attachment->ID, '_wp_attachment_image_alt', array('source' => 'bulk_copy_alt'));
                            update_post_meta($attachment->ID, '_wp_attachment_image_alt', $value);
                            break;

                        case 'img-copy-title':
                            $value = $i_info_url['filename'];
                            /**
                             * Filter before update meta for image
                             *
                             * @param string  Meta value
                             * @param integer Image ID
                             * @param string  Post title field name
                             * @param array   Extra informations
                             *
                             * @return string
                             *
                             * @ignore Hook already documented
                             */
                            $value = apply_filters('wpms_update_image_meta', $value, $attachment->ID, 'post_title', array('source' => 'bulk_copy_title'));
                            wp_update_post(array('ID' => $attachment->ID, 'post_title' => $value));
                            break;
                        case 'img-copy-desc':
                            wp_update_post(array('ID' => $attachment->ID, 'post_content' => $i_info_url['filename']));
                            break;
                    }
                }
            }

            wp_send_json(true);
        } else {
            // selected
            if (isset($_POST['ids'])) {
                $ids =  (array)$_POST['ids'];
                $ids = array_map('absint', $ids);
                switch ($_POST['action_name']) {
                    case 'img-copy-alt':
                        $margs = array(
                            'posts_per_page' => -1,
                            'post_type' => 'attachment',
                            'post_status' => 'any',
                            'post__in' => $ids
                        );

                        $m_newquery = new WP_Query($margs);
                        $mposts_empty_alt = $m_newquery->get_posts();
                        if (!empty($mposts_empty_alt)) {
                            foreach ($mposts_empty_alt as $post) {
                                $i_info_url = pathinfo($post->guid);
                                $value = $i_info_url['filename'];
                                /**
                                 * Filter before update meta for image
                                 *
                                 * @param string  Meta value
                                 * @param integer Image ID
                                 * @param string  Meta key
                                 * @param array   Extra informations
                                 *
                                 * @return string
                                 *
                                 * @ignore Hook already documented
                                 */
                                $value = apply_filters('wpms_update_image_meta', $value, $post->ID, '_wp_attachment_image_alt', array('source' => 'bulk_copy_alt'));
                                update_post_meta($post->ID, '_wp_attachment_image_alt', $value);
                            }
                        } else {
                            wp_send_json(false);
                        }
                        break;

                    case 'img-copy-title':
                        $posts_result = $wpdb->get_results($wpdb->prepare('SELECT *
                        FROM ' . $wpdb->posts . ' WHERE post_type = %s
                               AND post_mime_type LIKE %s AND ID IN (' . implode(',', esc_sql($ids)) . ') 
                        ', array('attachment', '%image%')));

                        if (!empty($posts_result)) {
                            foreach ($posts_result as $post) {
                                $i_info_url = pathinfo($post->guid);
                                $value = $i_info_url['filename'];
                                /**
                                 * Filter before update meta for image
                                 *
                                 * @param string  Meta value
                                 * @param integer Image ID
                                 * @param string  Post title field name
                                 * @param array   Extra informations
                                 *
                                 * @return string
                                 *
                                 * @ignore Hook already documented
                                 */
                                $value = apply_filters('wpms_update_image_meta', $value, $post->ID, 'post_title', array('source' => 'bulk_copy_title'));
                                wp_update_post(array('ID' => $post->ID, 'post_title' => $value));
                            }
                        } else {
                            wp_send_json(false);
                        }
                        break;
                    case 'img-copy-desc':
                        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Variable has been prepare
                        $sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->posts . ' WHERE post_type = %s AND post_mime_type LIKE %s AND ID IN ('.implode(',', $ids).')', array('attachment', '%image%'  ));
                        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Variable has been prepare
                        $posts_result = $wpdb->get_results($sql);
                        if (!empty($posts_result)) {
                            foreach ($posts_result as $post) {
                                $i_info_url = pathinfo($post->guid);
                                wp_update_post(array('ID' => $post->ID, 'post_content' => $i_info_url['filename']));
                            }
                        } else {
                            wp_send_json(false);
                        }
                        break;
                }
                wp_send_json(true);
            } else {
                wp_send_json(false);
            }
        }
    }

    /**
     * Ajax bulk update meta title for a post/page
     *
     * @return void
     */
    public function bulkPostCopy()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        $post_types = get_post_types(array('public' => true, 'exclude_from_search' => false));
        unset($post_types['attachment']);
        $margs = array();

        switch ($_POST['action_name']) {
            case 'post-copy-title':
                $key = '_metaseo_metatitle';
                break;
            case 'post-copy-desc':
                $key = '_metaseo_metadesc';
                break;
        }

        if (isset($_POST['sl_bulk']) && $_POST['sl_bulk'] === 'all') { // for select a;;
            $margs = array(
                'posts_per_page' => -1,
                'post_type' => $post_types,
                'post_status' => 'any'
            );
        } else { // for select some post
            if (isset($_POST['ids'])) {
                $ids = $_POST['ids'];
                $margs = array(
                    'posts_per_page' => -1,
                    'post_type' => $post_types,
                    'post_status' => 'any',
                    'post__in' => $ids
                );
            } else {
                wp_send_json(false);
            }
        }

        $m_newquery = new WP_Query($margs);
        $mposts = $m_newquery->get_posts();
        if (!empty($mposts)) {
            foreach ($mposts as $post) {
                $value = $post->post_title;
                /**
                 * Filter before update meta for post/page
                 *
                 * @param string  Meta value
                 * @param integer Post ID
                 * @param string  Meta key
                 * @param array   Extra informations
                 *
                 * @return string
                 *
                 * @ignore Hook already documented
                 */
                $value = apply_filters('wpms_update_content_meta', $value, $post->ID, $key, array('source' => 'copy_meta'));
                update_post_meta($post->ID, $key, $value);
            }
            wp_send_json(true);
        } else {
            wp_send_json(false);
        }
    }

    /**
     * Set cookie notification
     *
     * @return void
     */
    public function setcookieNotification()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        if (isset($_POST['page'])) {
            setcookie($_POST['page'], time(), time() + (86400 * 30), '/');
            wp_send_json(true);
        }
        wp_send_json(false);
    }


    /**
     * Ajax save ga information
     *
     * @return void
     */
    public function wpmsGGSaveInformation()
    {
        check_ajax_referer('wpms_nonce', 'wpms_nonce');
        if (!current_user_can('manage_options')) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            wp_die(__('There is no permission here', 'wp-meta-seo'));
        }

        $google_alanytics = array(
            'wpmsga_dash_clientid' => isset($_POST['wpmsga_dash_clientid']) ? trim($_POST['wpmsga_dash_clientid']) : '',
            'wpmsga_dash_clientsecret' => isset($_POST['wpmsga_dash_clientsecret']) ? trim($_POST['wpmsga_dash_clientsecret']) : ''
        );

        update_option('wpms_google_alanytics', $google_alanytics);

        require_once WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmstools.php';
        $client = WpmsGaTools::initClient($google_alanytics['wpmsga_dash_clientid'], $google_alanytics['wpmsga_dash_clientsecret']);
        $authUrl = $client->createAuthUrl();

        if (!empty($authUrl)) {
            echo json_encode(array('status' => 'true', 'authUrl' => $authUrl));
            die();
        }

        echo json_encode(array('status' => 'false', 'authUrl' => ''));
        die();
    }

    /**
     * Run ajax
     *
     * @return void
     */
    public function startProcess()
    {
        if (empty($_POST['wpms_nonce']) || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }
        if (!current_user_can('edit_posts')) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            wp_die(__('There is no edit permission here', 'wp-meta-seo'));
        }
        if (isset($_REQUEST['task'])) {
            switch ($_REQUEST['task']) {
                case 'updateContentMeta':
                    $this->updateContentMetaCallback();
                    break;
                case 'scanPosts':
                    MetaSeoImageListTable::scanPostsCallback();
                    break;
                case 'load_posts':
                    MetaSeoImageListTable::loadPostsCallback();
                    break;
                case 'optimize_imgs':
                    MetaSeoImageListTable::optimizeImages();
                    break;
                case 'updateMeta':
                    MetaSeoImageListTable::updateMetaCallback();
                    break;
                case 'import_meta_data':
                    MetaSeoContentListTable::importMetaData();
                    break;
                case 'dismiss_import_meta':
                    MetaSeoContentListTable::dismissImport();
                    break;
                case 'bulk_post_copy':
                    $this->bulkPostCopy();
                    break;
                case 'bulk_image_copy':
                    $this->bulkImageCopy();
                    break;
                case 'ajax_check_exist':
                    $this->checkExist();
                    break;
                case 'reload_analysis':
                    $this->reloadAnalysis();
                    break;
                case 'wpmsElementorSavePost':
                    require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-metabox.php');
                    WPMSEOMetabox::savePostByElementor();
                    break;
                case 'validate_analysis':
                    $this->validateAnalysis();
                    break;
                case 'update_link':
                    $this->updateLink();
                    break;
                case 'remove_link':
                    $this->removeLink();
                    break;
                case 'save_settings404':
                    $this->save404Settings();
                    break;
                case 'save_settings_breadcrumb':
                    $this->saveBreadcrumbSettings();
                    break;
                case 'update_link_redirect':
                    MetaSeoBrokenLinkTable::updateLinkRedirect();
                    break;
                case 'add_custom_redirect':
                    MetaSeoBrokenLinkTable::addCustomRedirect();
                    break;
                case 'unlink':
                    MetaSeoBrokenLinkTable::unlink();
                    break;
                case 'recheck_link':
                    MetaSeoBrokenLinkTable::reCheckLink();
                    break;
                case 'scan_link':
                    MetaSeoBrokenLinkTable::scanLink();
                    break;
                case 'flush_link':
                    MetaSeoBrokenLinkTable::flushLink();
                    break;
                case 'update_follow':
                    $this->updateFollow();
                    break;
                case 'update_multiplefollow':
                    $this->updateMultipleFollow();
                    break;
                case 'update_pagefollow':
                    $this->updatePageFollow();
                    break;
                case 'update_pageindex':
                    $this->updatePageIndex();
                    break;
                case 'backend_item_reports':
                    MetaSeoGoogleAnalytics::itemsReport();
                    break;
                case 'analytics_widgets_data':
                    MetaSeoGoogleAnalytics::analyticsWidgetsData();
                    break;
                case 'wpms_tagmanager_request_containers':
                    $this->tagmanagerContainersReport();
                    break;
                case 'wpms_tagmanager_container_info':
                    $this->tagmanagerContainerInfo();
                    break;
                case 'wpms_tagmanager_refresh_connect':
                    $this->tagmanagerRefreshConnect();
                    break;
                case 'ga_clearauthor':
                    MetaSeoGoogleAnalytics::clearAuthor();
                    break;
                case 'ga_update_option':
                    MetaSeoGoogleAnalytics::updateOption();
                    break;
                case 'dash_permalink':
                    MetaSeoDashboard::dashboardPermalink();
                    break;
                case 'dash_newcontent':
                    MetaSeoDashboard::dashboardNewContent();
                    break;
                case 'dash_linkmeta':
                    MetaSeoDashboard::dashboardLinkMeta();
                    break;
                case 'dash_metatitle':
                    MetaSeoDashboard::dashboardMetaTitle();
                    break;
                case 'dash_metadesc':
                    MetaSeoDashboard::dashboardMetaDesc();
                    break;
                case 'dash_imgsmeta':
                    MetaSeoDashboard::dashImgsMeta();
                    break;
                case 'reload-web':
                    MetaSeoDashboard::reloadWeb();
                    break;
                case 'setcookie_notification':
                    $this->setcookieNotification();
                    break;
                case 'image_scan_meta':
                    MetaSeoImageListTable::imageScanMeta();
                    break;
                case 'update_seokeyword_bulk_edit':
                    $this->updateSeokeywordBulkEdit();
                    break;
                case 'updateCategoryContent':
                    WPMSCategoryMetaTable::updateCategoryContent();
                    break;
                case 'wpmsDeleteCat':
                    WPMSCategoryMetaTable::wpmsDeleteCat();
                    break;
                case 'wpmsBulkCatCopy':
                    WPMSCategoryMetaTable::wpmsBulkCatCopy();
                    break;
            }
        }
    }


    /**
     * Convert canonical url
     *
     * @param string $url Url
     *
     * @return string
     */
    public static function convertCanonicalUrlToSave($url)
    {
        if (empty($url)) {
            return $url;
        }

        $url = preg_replace('/\s+/', '', $url);

        if (substr($url, 0, 2) === '//') {
            $url = (is_ssl() ? 'https:' : 'http:') . $url;
        }

        // Remove / in first
        $url = ltrim($url, '/');

        // Full link
        if (preg_match('#http(s?)://#i', $url)) {
            if (strpos($url, trim(home_url(), '/')) !== false) {
                $url = str_replace(trim(home_url(), '/'), '', $url);
                return (empty($url) ? '/' : $url);
            } else {
                // External link
                return $url;
            }
        }

        $parseHome = parse_url(home_url());
        $host = (!empty($parseHome['host']) ? $parseHome['host'] : '');
        $host .= (!empty($parseHome['path']) ? $parseHome['path'] : '');

        if ($host && $url && strpos($url, $host) !== false) {
            $url = str_replace($host, '', $url);
        }

        return '/' . ltrim($url, '/');
    }

    /**
     * Convert canonical url to display
     *
     * @param string $url Url
     *
     * @return string
     */
    public static function convertCanonicalUrlToDisplay($url)
    {
        if (empty($url)) {
            return $url;
        }

        if (preg_match('#http(s?)://#i', $url)) {
            // External link
            return $url;
        }

        return trim(home_url(), '/') . $url;
    }

    /**
     * Schedules a rewrite flush.
     *
     * @return void
     */
    public function wpmsScheduleRewriteFlush()
    {
        // Bail if this is a multisite installation and the site has been switched.
        if (is_multisite() && ms_is_switched()) {
            return;
        }

        add_action('shutdown', 'flush_rewrite_rules');
    }
}
