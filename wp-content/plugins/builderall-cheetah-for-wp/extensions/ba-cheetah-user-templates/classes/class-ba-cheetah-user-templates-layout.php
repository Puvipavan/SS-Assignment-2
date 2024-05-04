<?php
final class BACheetahUserTemplatesLayout
{


	static public $content_types = array('ba-cheetah-header', 'ba-cheetah-footer', 'ba-cheetah-popup');

	static public function init()
	{

		// Resolve the editing template
		add_filter('template_include', __CLASS__ . '::resolve_template', 99);
		add_filter('theme_page_templates', __CLASS__ . '::register_templates');

		// update Cheetah mode on save layout changes
		add_action('save_post', __CLASS__ . '::save_post', 10, 3);

		// Register the layout content types
		add_action('init', __CLASS__ . '::add_layout_post_types');

		// Adjust selected menu on adding
		add_filter('submenu_file', __CLASS__ . '::submenu_file', 999, 2);

		// Collumn
		add_filter('manage_ba-cheetah-header_posts_columns', __CLASS__ . '::add_column_headings');
		add_filter('manage_ba-cheetah-footer_posts_columns', __CLASS__ . '::add_column_headings');
		add_action('manage_ba-cheetah-header_posts_custom_column', __CLASS__ . '::add_column_content', 10, 2);
		add_action('manage_ba-cheetah-footer_posts_custom_column', __CLASS__ . '::add_column_content', 10, 2);

		self::register_layout_fields();

		// Category and custom taxonomys extra fields
		self::register_category_and_custom_taxonomy_layout_fields();

		// Replace header and footer
		add_action('get_header', __CLASS__ . '::get_header');
		add_action('get_footer', __CLASS__ . '::get_footer');


		add_action('admin_enqueue_scripts', __CLASS__ . '::styles_scripts');

		add_action('admin_head-post.php', __CLASS__ . '::save_post_before_open_editor');
	}

	public static function save_post_before_open_editor()
	{
		global $post;

		$post_types = self::$content_types;
		$post_types[] = 'ba-cheetah-template';

		if (in_array($post->post_type, $post_types)) {
			$post_url = admin_url('post.php'); #In case we're on post-new.php
			echo "
			<script>
				jQuery(document).ready(function($){
					//Click handler - you might have to bind this click event another way
					$('.ba-cheetah-save-before-open-href').click(function(e){

						$('.spinner').addClass('is-active');

						//Post to post.php
						var postURL = '$post_url';

						//Collate all post form data
						var data = $('form#post').serializeArray();

						//Set a trigger for our save_post action
						data.push({foo_doing_ajax: true});

						//The XHR Goodness
						console.log(data);
						$.post(postURL, data, function(response){
							$('.spinner').removeClass('is-active');
							window.location.href = e.target.href;
						});
						return false;
					});
				});
			</script>";
		}
	}

	public static function register_templates($templates = array())
	{
		$templates['ba-cheetah-canvas-template'] = __('Builderall canvas', 'ba-cheetah');
		return $templates;
	}

	static public function styles_scripts()
	{
		wp_enqueue_script('ba-cheetah-admin-layout-rules', BA_CHEETAH_URL . 'js/ba-cheetah-admin-layout-rules.js', null, BA_CHEETAH_VERSION);
	}

	/**
	 * Return the defined header or footer of the global $post
	 *
	 * @param  mixed $type
	 * @return void
	 */
	static public function get_layout_part_id($type = 'header')
	{
		global $post;

		$resolve_global_layout_by_key = function ($type, $meta_key) use ($post) {
			$args = array(
				'post_type' => "ba-cheetah-$type",
				'meta_query' => array(
					array(
						'key' => $meta_key,
						'compare' => '=',
						'value' => 1
					)
				),
				'posts_per_page' => 1
			);

			$posts = get_posts($args);

			if (isset($posts[0])) {
				return $posts[0]->ID;
			}

			// If cheetah canvas is active, disables the header/footer if there is no global defined
			if (
				!is_archive()
				&& $post && $post->ID && get_post_meta($post->ID, 'ba_cheetah_mode', true) === 'canvas'
				&& ( get_post_meta( $post->ID, '_ba_cheetah_enabled', true )|| isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) ) {
				return 'disabled';
			}

			return false;
		};

		if (is_home()) {
			// is_home = Post page
			return $resolve_global_layout_by_key($type, 'ba_layout_posts_page');
		} else if (is_search()) {
			return $resolve_global_layout_by_key($type, 'ba_layout_search_page');
		} else if (is_category()) {
			$meta_key = 'ba_layout_all_categories';
			$meta_function = 'get_term_meta';
			$object_id = get_query_var('cat');
		} else if (is_tax()) {
			$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$meta_key = 'ba_layout_all_categories';
			$meta_function = 'get_term_meta';
			$object_id = $term->term_id;
		} else if (is_tag()) {
			$term = get_term_by('slug', get_query_var('tag'), 'post_tag');
			$meta_key = 'ba_layout_tags_page';
			$meta_function = 'get_term_meta';
			$object_id = $term->term_id;
		} else if (is_archive()) {
			return $resolve_global_layout_by_key($type, 'ba_layout_archive_page');
		} else if (is_404()) {
			return $resolve_global_layout_by_key($type, 'ba_layout_404_page');
		} else if ($post) {
			$meta_key = 'ba_layout_content_' . $post->post_type;
			$meta_function = 'get_post_meta';
			$object_id = $post->ID;
		} else {
			return false;
		}

		$layout_option = $meta_function($object_id, "ba-cheetah-$type-option", true);

		// Check if header or footer is disabled
		if ($layout_option == 'disabled') {
			return 'disabled';
		}

		// Check if post or tax use theme default
		if ($layout_option == 'default') {
			return false;
		}

		// Check if post use especific layout
		if (
			$layout_option == "custom"
			&& $layout_id = $meta_function($object_id, "ba-cheetah-custom-$type-id", true)
		) {
			return $layout_id;
		}

		/**
		 * Assumes the global layout is being used
		 * Check if exist a layout for all site ou for that specif post type
		 * */

		return $resolve_global_layout_by_key($type, $meta_key);
	}

	static public function get_header($name)
	{
		global $ba_cheetah_header_id;
		$ba_cheetah_header_id = self::get_layout_part_id('header');
		if ($ba_cheetah_header_id) {

			require BA_CHEETAH_USER_TEMPLATES_DIR . 'includes/header.php';

			$templates = [];
			$name = (string) $name;
			if ('' !== $name) {
				$templates[] = "header-{$name}.php";
			}

			$templates[] = 'header.php';

			// Avoid running wp_head hooks again
			remove_all_actions('wp_head');
			ob_start();
			// It cause a `require_once` so, in the get_header it self it will not be required again.
			locate_template($templates, true);
			ob_get_clean();
		}
	}

	static public function get_footer($name)
	{ 
		// Builderall watermark
		$post_id = get_the_ID();
		$type = get_post_type($post_id);
		$meta = get_post_meta($post_id, '_wp_page_template', true);
		
		// Show only in pages and posts edited by cheetah and to Builderall User
		if( BA_CHEETAH_BUILDERALL && !BACheetahModel::is_builder_active() && $meta === 'ba-cheetah-canvas-template' && ($type === 'post' || $type === 'page') ) {
			BACheetah::render_watermark();
		}
		// end watermark

		global $ba_cheetah_footer_id;
		$ba_cheetah_footer_id = self::get_layout_part_id('footer');;
		if ($ba_cheetah_footer_id) {
			require BA_CHEETAH_USER_TEMPLATES_DIR . 'includes/footer.php';

			$templates = [];
			$name = (string) $name;
			if ('' !== $name) {
				$templates[] = "footer-{$name}.php";
			}

			$templates[] = 'footer.php';

			ob_start();
			// It cause a `require_once` so, in the get_header it self it will not be required again.
			locate_template($templates, true);
			ob_get_clean();
		}
	}

	public static function resolve_template($template)
	{
		// Check if the post type is a layout content type and apply the right template
		$type = get_post_type(get_the_ID());
		if (self::is_layout_content_type($type)) {
			return BA_CHEETAH_DIR . 'templates/edit-template.php';
		}

		// Case canvas active, always return the page template
		if (
			get_post_meta(get_the_ID(), 'ba_cheetah_mode', true) === 'canvas'
			&& ( get_post_meta( get_the_ID(), '_ba_cheetah_enabled', true ) || isset($_GET['ba_cheetah']) || isset($_GET['ba_builder']) )
			) {
			return BA_CHEETAH_DIR . 'templates/page-template.php';
		}

		/**	Fallback in case of for any reason,
		 * the user could change the layout to the cheetah canvas withou
		 * enable the canvas mode
		 */
		if(is_page()) {
			$selected_template = get_post_meta(get_the_ID(), '_wp_page_template', true);
			if($selected_template === 'ba-cheetah-canvas-template') {
				return BA_CHEETAH_DIR . 'templates/page-template.php';
			}
		}

		return $template;
	}

	/**
	 * save_post
	 *
	 * @param  mixed $post_id
	 * @param  mixed $post
	 * @param  mixed $updated
	 * @return void
	 */
	public static function save_post($post_id, $post, $update)
	{
		if($update && get_post_type() == 'page') {
			$saved_template = get_post_meta(get_the_ID(), '_wp_page_template', true);
			$mode = $saved_template === 'ba-cheetah-canvas-template' ? 'canvas' : 'default';
			update_post_meta(get_the_ID(), 'ba_cheetah_mode', $mode);
		}
	}

	public static function is_layout_content_type($type)
	{
		return in_array($type, self::getLayoutPostTypes());
	}

	public static function is_custom_layout_content_type($type)
	{
		return in_array($type, self::$content_types);
	}

	public static function add_layout_post_types()
	{

		$can_edit = BACheetahUserAccess::current_user_can('unrestricted_editing');

		$show_in_menu = 'ba-cheetah-settings';

		register_post_type(
			'ba-cheetah-header',
			array(
				'labels'      => array(
					'name'          => __('Headers', 'ba-cheetah'),
					'singular_name' => __('Header', 'ba-cheetah'),
				),
				'public'      => $can_edit,
				'has_archive' => false,
				'exclude_from_search' => true,
				'show_ui' => true,
				'show_in_menu' => $show_in_menu,
				'show_in_nav_menus'	=> false,
				'supports' => array(
					'title',
					'revisions'
				),
			)
		);

		register_post_type(
			'ba-cheetah-footer',
			array(
				'labels'      => array(
					'name'          => __('Footers', 'ba-cheetah'),
					'singular_name' => __('Footer', 'ba-cheetah'),
				),
				'public'      => $can_edit,
				'has_archive' => false,
				'exclude_from_search' => true,
				'show_ui' => true,
				'show_in_menu' => $show_in_menu,
				'show_in_nav_menus'	=> false,
				'supports' => array(
					'title',
					'revisions',
				),
			)
		);

		register_post_type(
			'ba-cheetah-popup',
			array(
				'labels'      => array(
					'name'          => __('Popups', 'ba-cheetah'),
					'singular_name' => __('Popup', 'ba-cheetah'),
				),
				'public'      => $can_edit,
				'has_archive' => false,
				'exclude_from_search' => true,
				'show_ui' => true,
				'show_in_menu' => $show_in_menu,
				'supports' => array(
					'title',
					'revisions',
				),
				'show_in_nav_menus'	=> false,
			)
		);
	}

	public static function register_layout_fields()
	{
		add_action('add_meta_boxes', __CLASS__ . '::add_layout_fields');
		add_action('save_post', __CLASS__ . '::save_layout_data');
	}

	public static function add_layout_fields()
	{

		// Header and footer meta boxes
		add_meta_box(
			'ba_cheetah_header_fixed_rule', // Unique ID
			__('Header options', 'ba-cheetah'), // Box title
			__CLASS__ . '::register_header_options', // Content callback, must be of type callable
			'ba-cheetah-header',
			'side'
		);

		add_meta_box(
			'ba_cheetah_header_rule', // Unique ID
			__('Header location options', 'ba-cheetah'), // Box title
			__CLASS__ . '::register_layout_location_fields', // Content callback, must be of type callable
			'ba-cheetah-header' // Post type
		);

		add_meta_box(
			'ba_cheetah_footer_rule', // Unique ID
			__('Footer location options', 'ba-cheetah'), // Box title
			__CLASS__ . '::register_layout_location_fields', // Content callback, must be of type callable
			'ba-cheetah-footer' // Post type
		);

        // Popup Meta boxes
        add_meta_box(
            'ba_cheetah_popup_width_rule', // Unique ID
            __('Popup options', 'ba-cheetah'), // Box title
            __CLASS__ . '::register_popup_options', // Content callback, must be of type callable
            'ba-cheetah-popup',
            'side'
        );


		// Pages and posts (including custom post types) meta boxes
		$itens_to_apply_rules = array('page', 'post');

		$post_types = get_post_types(array(
			'public' => true,
		), 'objects');
		unset($post_types['ba-cheetah-template']);
		unset($post_types['ba-cheetah-header']);
		unset($post_types['ba-cheetah-footer']);
		unset($post_types['ba-cheetah-popup']);
		unset($post_types['attachment']);

		array_map(function ($type) {
			$itens_to_apply_rules[] = $type->name;
		}, $post_types);

		$taxonomies = get_taxonomies(array(
			'public' => true,
			'_builtin' => false
		), 'objects');
		unset($taxonomies['ba-cheetah-template-category']);

		array_map(function ($tax) {
			$itens_to_apply_rules[] = $tax->name;
		}, $taxonomies);

		foreach ($itens_to_apply_rules as $key => $item) {

			add_meta_box(
				"ba_cheetah_location_header", // Unique ID
				__('Builderall header', 'ba-cheetah'), // Box title
				__CLASS__ . '::register_layout_header_fields', // Content callback, must be of type callable
				$item,
				'side'
			);

			add_meta_box(
				"ba_cheetah_location_footer", // Unique ID
				__('Builderall footer', 'ba-cheetah'), // Box title
				__CLASS__ . '::register_layout_footer_fields', // Content callback, must be of type callable
				$item,
				'side'
			);
		}
	}

	public static function register_header_options($post)
	{
		$fixed_header = get_post_meta($post->ID, "ba-cheetah-fixed-header", true);
?>
		<span>
			<input type="checkbox" id="ba-cheetah-fixed-header" name="ba-cheetah-fixed-header" value="default" <?php checked($fixed_header, 1) ?> />
		</span>
		<label for="ba-cheetah-fixed-header">
			<?= __('Fixed header'); ?>
		</label>
	<?php
	}

	public static function register_popup_options($post)
	{
		$popup_width = get_post_meta($post->ID, "ba-cheetah-popup-width", true);

		if(empty($popup_width)) {
            $global_settings = BACheetahModel::get_global_settings();
            $popup_width = $global_settings->popup_width ? $global_settings->popup_width.$global_settings->popup_width_unit : '700';
        }

        $popup_width = (float)$popup_width;
        ?>
        <p class="post-attributes-label-wrapper page-template-label-wrapper">
            <label class="post-attributes-label" for="ba-cheetah-popup-width">
                <?= __('Popup Width'); ?>
            </label>
        </p>
		<div>
			<input type="number" id="ba-cheetah-popup-width" name="ba-cheetah-popup-width" value="<?= $popup_width; ?>" style="max-width:100px;" min="1" required/>
            <span>px</span>
		</div>
		<div>
			<br><?= __('Changing the popup width requires you to republish the pages it is on'); ?>
		</div>
	    <?php
	}

	public static function register_category_and_custom_taxonomy_layout_fields()
	{

		add_action('registered_taxonomy', function ($tax) {
			if ($tax !== 'ba-cheetah-template-category') {
				add_action($tax . "_edit_form_fields", __CLASS__ . '::add_category_and_custom_taxonomy_layout_fields', 10, 2);
				add_action("edited_" . $tax, __CLASS__ . '::save_extra_category_fileds');
			}
		});
	}

	public static function save_extra_category_fileds($tag_id)
	{

		// Tax header and footer exception
		if (isset($_POST['ba-cheetah-footer-option'])) {

			// Case to use custom
			if ($_POST['ba-cheetah-footer-option'] == 'custom') {
				update_term_meta(
					$tag_id,
					'ba-cheetah-custom-footer-id',
					$_POST['ba-cheetah-custom-footer-id']
				);
			} else {
				delete_term_meta($tag_id, 'ba-cheetah-custom-footer-id');
			}

			update_term_meta(
				$tag_id,
				'ba-cheetah-footer-option',
				$_POST['ba-cheetah-footer-option']
			);
		}

		if (isset($_POST['ba-cheetah-header-option'])) {

			// Case to use custom
			if ($_POST['ba-cheetah-header-option'] == 'custom') {
				update_term_meta(
					$tag_id,
					'ba-cheetah-custom-header-id',
					$_POST['ba-cheetah-custom-header-id']
				);
			} else {
				delete_term_meta($tag_id, 'ba-cheetah-custom-header-id');
			}

			update_term_meta(
				$tag_id,
				'ba-cheetah-header-option',
				$_POST['ba-cheetah-header-option']
			);
		}
	}

	public static function add_category_and_custom_taxonomy_layout_fields($tag)
	{
		self::create_category_and_custom_taxonomy_layout_fields($tag, 'header');
		self::create_category_and_custom_taxonomy_layout_fields($tag, 'footer');
	}

	public static function create_category_and_custom_taxonomy_layout_fields($tag, $type)
	{
		$args = array(
			'posts_per_page' => -1,
			'post_type' => "ba-cheetah-$type",
			'suppress_filters' => true
		);
		$items = get_posts($args);

		$type_translated = $type === 'header' ? __('header', 'ba-cheetah') : __('footer', 'ba-cheetah');

		$layout_option = get_term_meta($tag->term_id, "ba-cheetah-$type-option", true);
		$custom_header_or_footer_id = get_term_meta($tag->term_id, "ba-cheetah-custom-$type-id", true);

		if (!$layout_option) {
			$layout_option = 'global';
		}

	?>

		<tr class="form-field">
			<th scope="row" valign="top"><label><?= sprintf(__('Builderall %s', 'ba-cheetah'), $type_translated); ?></label></th>
			<td>
				<div>
					<select name="ba-cheetah-<?= $type ?>-option" id="ba-cheetah-<?= $type; ?>-option">
						<option value="default" <?php selected($layout_option, 'default'); ?>><?= sprintf(__('Theme %s', 'ba-cheetah'), $type_translated); ?></option>
						<option value="custom" <?php selected($layout_option, 'custom'); ?>><?= sprintf(__('Custom %s', 'ba-cheetah'), $type_translated); ?></option>
						<option value="global" <?php selected($layout_option, 'global'); ?>><?= sprintf(__('Global %s', 'ba-cheetah'), $type_translated); ?></option>
						<option value="disabled" <?php selected($layout_option, 'disabled'); ?>><?= sprintf(__('No %s', 'ba-cheetah'), $type_translated); ?></option>
					</select>

					<select name="ba-cheetah-custom-<?= $type ?>-id" id="ba-cheetah-custom-<?= $type ?>-selector">
						<option value="0"><?= sprintf(__('Select a %s', 'ba-cheetah'), $type_translated); ?></option>
						<?php foreach ($items as $item) : ?>
							<option value="<?= $item->ID; ?>" <?php selected($custom_header_or_footer_id, $item->ID); ?>><?= $item->post_title; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</td>
		</tr>

	<?php
	}

	public static function register_layout_footer_fields($post)
	{
		self::register_layout_rules_fields($post, 'footer');
	}

	public static function register_layout_header_fields($post)
	{
		self::register_layout_rules_fields($post, 'header');
	}

	public static function register_layout_rules_fields($post, $type)
	{
		$args = array(
			'posts_per_page' => -1,
			'post_type' => "ba-cheetah-$type",
			'suppress_filters' => true
		);
		$items = get_posts($args);

		$type_translated = $type === 'header' ? __('header', 'ba-cheetah') : __('footer', 'ba-cheetah');

		$layout_option = get_post_meta($post->ID, "ba-cheetah-$type-option", true);
		$custom_header_or_footer_id = get_post_meta($post->ID, "ba-cheetah-custom-$type-id", true);

		if (!$layout_option) {
			$layout_option = 'global';
		}

	?>
		<div>
			<div>
				<label for="ba-cheetah-<?= $type; ?>-option"><?= __('Type', 'ba-cheetah') ?></label>
			</div>

			<select name="ba-cheetah-<?= $type ?>-option" id="ba-cheetah-<?= $type; ?>-option">
				<option value="default" <?php selected($layout_option, 'default'); ?>><?= sprintf(__('Theme %s', 'ba-cheetah'), $type_translated); ?></option>
				<option value="custom" <?php selected($layout_option, 'custom'); ?>><?= sprintf(__('Custom %s', 'ba-cheetah'), $type_translated); ?></option>
				<option value="global" <?php selected($layout_option, 'global'); ?>><?= sprintf(__('Global %s', 'ba-cheetah'), $type_translated); ?></option>
				<option value="disabled" <?php selected($layout_option, 'disabled'); ?>><?= sprintf(__('No %s', 'ba-cheetah'), $type_translated); ?></option>
			</select>
		</div>

		<div id="ba-cheetah-custom-<?= $type ?>-selector">
			<div>
				<label for="ba-cheetah-<?= $type; ?>-option"><?= sprintf(__('Custom %s', 'ba-cheetah'), $type_translated); ?></label>
			</div>
			<select name="ba-cheetah-custom-<?= $type ?>-id">
				<option value="0"><?= sprintf(__('Select a %s', 'ba-cheetah'), $type_translated); ?></option>
				<?php foreach ($items as $item) : ?>
					<option value="<?= $item->ID; ?>" <?php selected($custom_header_or_footer_id, $item->ID); ?>><?= $item->post_title; ?></option>
				<?php endforeach; ?>
			</select>
		</div>

	<?php
	}

	static public function add_column_headings($columns)
	{
		$columns['ba_apply_to'] = __('Global for', 'ba-cheetah');

		return $columns;
	}

	static public function add_column_content($column, $post_id)
	{
		if ('ba_apply_to' != $column) {
			return;
		}

		if ($application = self::get_layout_application($post_id)) {
			echo $application;
		} else {
			echo '&#8212;';
		}
	}

	static private function get_layout_application($post_id)
	{

		$application = array();

		$options_group = self::get_layout_location_options_array();
		foreach ($options_group as $option_group) {
			if (isset($option_group['options'])) {
				foreach ($option_group['options'] as $option_key => $option) {
					if (get_post_meta($post_id, $option_key, true) == 1) {
						$application[] = trim($option);
					}
				}
			}
		}

		if ($application) {
			return implode(', ', $application);
		}

		return false;
	}

	public static function get_layout_location_options_array()
	{

		$locations = array(

			'content' => array(
				'title' => 'Content pages',
				'options' => array()
			),

			'categories' => array(
				'title' => 'Category pages',
				'options' => array(
					'ba_layout_all_categories' => __('All post categories', 'ba-cheetah')
				)
			),

			'templates' => array(
				'title' => 'Templates',
				'options' => array(
					'ba_layout_posts_page' => __('Posts page ', 'ba-cheetah'),
					'ba_layout_search_page' => __('Search page ', 'ba-cheetah'),
					'ba_layout_tags_page' => __('Tags page', 'ba-cheetah'),
					'ba_layout_archive_page' => __('Archive page', 'ba-cheetah'),
					'ba_layout_404_page' => __('404 page', 'ba-cheetah'),
				),
			)

		);

		// Content items
		$post_types = get_post_types(array(
			'public' => true,
		), 'objects');

		unset($post_types['ba-cheetah-template']);
		unset($post_types['ba-cheetah-header']);
		unset($post_types['ba-cheetah-footer']);
		unset($post_types['ba-cheetah-popup']);
		unset($post_types['attachment']);

		if($post_types) {

			$locations['content']['title'] = __('Custom content types', 'ba-cheetah');

			foreach ($post_types as $post_type) {
				$key = 'content_' . str_replace('-', '_', $post_type->name);
				$locations['content']['options']['ba_layout_' . $key] = $post_type->labels->name;
			}
		}


		// Category items
		$taxonomies = get_taxonomies(array(
			'public' => true,
			'_builtin' => false
		), 'objects');

		unset($taxonomies['ba-cheetah-template-category']);

		if($taxonomies) {
			$locations['category']['title'] = __('Custom taxonomy types', 'ba-cheetah');
			foreach ($taxonomies as $taxonomy) {
				$key = 'content_' . str_replace('-', '_', $taxonomy->name);
				$locations['category']['options']['ba_layout_tax_' . $key] = $taxonomy->labels->name;
			}
		}

		return $locations;
	}

	public static function register_layout_location_fields($post)
	{
		$type = $post->post_type == 'ba-cheetah-header' ? 'header' : 'footer';

	?>
		<div>
			<p>
				<?= sprintf(
					__('Select which locations this %s should be applied to. If no %1$s is set to be applied to a location, wordpress will display the default %1$s of the theme in use.', 'ba-cheetah'),
					__($type, 'ba-cheetah'));
				?>
			</p>
		</div>

		<div class="notice notice-warning is-dismissible">
			<p>
				<?= sprintf(
					__('Attention! Using a Custom %s may not be supported by all themes. If you apply a custom %1$s to a page and the page fails or looks awkward, we don\'t recommend using this feature.', 'ba-cheetah'),
					__($type, 'ba-cheetah'));
				?>
			</p>
		</div>

		<label>
			<input class="ba-cheetah-layout-all-cb" type="checkbox" />
			<?= __('Select all', 'ba-cheetah'); ?>
		</label>

		<input type="hidden" name="ba-cheetah-layout-part-edited" value="1" />

		<?php
		$options_group = self::get_layout_location_options_array();
		foreach ($options_group as $option_group) {
			if (isset($option_group['options'])) :
		?>
				<h3><?= $option_group['title']; ?></h3>
				<?php
				foreach ($option_group['options'] as $key => $option) :
					$value = get_post_meta($post->ID, $key, true);
				?>
					<p>
						<label>
							<input class="ba-cheetah-layout-location-cb" type="checkbox" name="ba-layout-apply-location[]" value="<?= $key; ?>" <?php checked($value, true); ?> />
							<?= $option; ?>
						</label>
					</p>
				<?php
				endforeach;
				?>
		<?php
			endif;
		}
		?>
<?php
	}

	public static function save_layout_data($post_id)
	{
		global $post;

		// Header fixed option

		if (isset($_POST['ba-cheetah-layout-part-edited'])) {

			if ($post->post_type == 'ba-cheetah-header') {
				if (isset($_POST['ba-cheetah-fixed-header'])) {
					update_post_meta($post_id, 'ba-cheetah-fixed-header', true);
				} else {
					delete_post_meta($post_id, 'ba-cheetah-fixed-header');
				}
			}


			// Header and footer apply rules
			if (in_array($post->post_type, array('ba-cheetah-header', 'ba-cheetah-footer'))) {
				$options_group = self::get_layout_location_options_array();
				foreach ($options_group as $option_group) {
					foreach ($option_group['options'] as $option_key => $option_name) {

						$enabled = isset($_POST['ba-layout-apply-location']) && in_array($option_key, $_POST['ba-layout-apply-location']) ? 1 : 0;

						// accept only one header or footer as the chosen one
						if ($enabled) {
							$args = array(
								'posts_per_page' => -1,
								'post_type' => $post->post_type,
								'suppress_filters' => true
							);

							$posts_array = get_posts($args);

							foreach ($posts_array as $post_array) {
								update_post_meta($post_array->ID, $option_key, 0);
							}
						}

						update_post_meta(
							$post_id,
							$option_key,
							$enabled
						);
					}
				}
			}
		}

		// Post header and footer exception

		if (isset($_POST['ba-cheetah-footer-option'])) {

			// Case to use custom
			if ($_POST['ba-cheetah-footer-option'] == 'custom') {
				update_post_meta(
					$post_id,
					'ba-cheetah-custom-footer-id',
					$_POST['ba-cheetah-custom-footer-id']
				);
			} else {
				delete_post_meta($post_id, 'ba-cheetah-custom-footer-id');
			}

			update_post_meta(
				$post_id,
				'ba-cheetah-footer-option',
				$_POST['ba-cheetah-footer-option']
			);
		}

		if (isset($_POST['ba-cheetah-header-option'])) {

			// Case to use custom
			if ($_POST['ba-cheetah-header-option'] == 'custom') {
				update_post_meta(
					$post_id,
					'ba-cheetah-custom-header-id',
					$_POST['ba-cheetah-custom-header-id']
				);
			} else {
				delete_post_meta($post_id, 'ba-cheetah-custom-header-id');
			}

			update_post_meta(
				$post_id,
				'ba-cheetah-header-option',
				$_POST['ba-cheetah-header-option']
			);
		}

		// Popup width option
        if ($post && $post->post_type === 'ba-cheetah-popup' && isset($_POST['ba-cheetah-popup-width'])) {
            $popup_width = (float)$_POST['ba-cheetah-popup-width'];
            if ($popup_width) {
                update_post_meta($post_id, 'ba-cheetah-popup-width', $popup_width . 'px');
            }
        }
	}

	public static function getLayoutPostTypes()
	{

		$content_types = self::$content_types;
		if (class_exists(BACheetahUserTemplatesPostType::class)) {
			$content_types = array_merge($content_types, BACheetahUserTemplatesPostType::$content_types);
		}

		return $content_types;
	}

	static public function submenu_file($submenu_file, $parent_file)
	{

		global $pagenow;

		$screen   = get_current_screen();

		$post_types = self::getLayoutPostTypes();
		$post_types[] = 'ba-cheetah-layout';

		if (($pagenow === 'edit.php' || $pagenow === 'post-new.php') && in_array($screen->post_type, $post_types)) {
			$submenu_file = 'edit.php?post_type=' . $screen->post_type;
		}


		return $submenu_file;
	}
}

BACheetahUserTemplatesLayout::init();
