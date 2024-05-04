<?php

/**
 * @class BACheetahPostsModule
 */
class BACheetahPostsModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Posts', 'ba-cheetah' ),
			'description'     => '',
			'category'        => __( 'Layout', 'ba-cheetah' ),
			'partial_refresh' => true,
			'icon'            => 'posts.svg',
		));
	}

	public function get_all_settings() {
		$settings = (array) $this->settings;
		$settings['id'] = 'ba-node-' . $this->node;
		return $settings;
	}

	public function get_subtitle($post_type = 'post') {

		$subtitle = [];

		// woocommerce price
		if (isset($this->settings->display_price) && $this->settings->display_price == 'show' && $post_type == 'product') {
			$product = wc_get_product( get_the_ID() );
			array_push($subtitle, $product->get_price_html());
		}

		// date
		if (isset($this->settings->display_date) && $this->settings->display_date == 'show' && $post_type != 'product') 
			array_push($subtitle, get_the_date());

		// time
		if (isset($this->settings->display_time) && $this->settings->display_time == 'show' && $post_type != 'product') 
			array_push($subtitle, get_the_time());

		// author
		if (isset($this->settings->display_author) && $this->settings->display_author == 'show' && $post_type != 'product') 
			array_push($subtitle, get_the_author());

		// comments
		if (isset($this->settings->display_comments) && $this->settings->display_comments == 'show' && $post_type != 'product') 
			array_push($subtitle, get_comments_number() .' '.__('comments'));
		
		// category
		if (isset($this->settings->display_category) && $this->settings->display_category == 'show') {
			if ($post_type == 'product') {
				$product = wc_get_product( get_the_ID() );
				array_push($subtitle, wc_get_product_category_list( $product->get_id(), ', '));
			}
			else {
				$category = get_the_category();
				if (count($category) && isset($category[0]->name))
				array_push($subtitle, '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '">' . esc_html( $category[0]->name ) . '</a>');
			}
		}

		$separator = isset($this->settings->separator) ? $this->settings->separator : '|';
		return join(sprintf(' %s ', $separator), $subtitle);
	}

	function get_posts_excerpt_length(){
		return $this->settings->excerpt_length ? $this->settings->excerpt_length : '55';
	}

	function default_photo() {
		// default no set
		$photo = 'default';
		$photo_src = BACheetahCardModule::DEFAULT_PHOTO;

		// image set
		if (isset($this->settings->default_photo) && $this->settings->default_photo) {
			$photo = $this->settings->default_photo;
			$photo_src = $this->settings->default_photo_src;
		}

		// image removed
		else if (isset($this->settings->default_photo) && !$this->settings->default_photo) {
			$photo = false;
			$photo_src = false;
		}
		return [
			'photo' => $photo,
			'photo_src' => $photo_src
		];
	}
}

// btn link is the link of the post
$post_button_settings = BACheetahCardModule::get_button_sections_config();
foreach (['btn_link', 'btn_click_action', 'btn_popup_id', 'btn_video_link'] as $field) {
	unset($post_button_settings['sections']['cta']['fields'][$field]);
}

// adjust default image config
$post_styles_settings = BACheetahCardModule::get_styles_sections_config();
$post_styles_settings['sections']['image']['fields']['image_position']['default'] = 'column';
$post_styles_settings['sections']['image']['fields']['image_size']['default'] = '200';

BACheetah::register_module('BACheetahPostsModule', array(
	'general' => array(
		'title'     => __( 'Content', 'ba-cheetah' ),
		'file'      => BA_CHEETAH_DIR . 'includes/ui-loop-settings.php',
	),
	'layout' => array(
		'title' => __('Layout', 'ba-cheetah'),
		'sections' => array(
			'display' => array(
				'title' => '',
				'fields' => array(
					'columns' => array(
						'type' => 'select',
						'label' => __('Number of columns', 'ba-cheetah'),
						'responsive' => true,
						'default' => '3',
						'options' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
						),
					),
					'gap' => array(
						'type' => 'unit',
						'label' => __('Gap', 'ba-cheetah'),
						'help' => __('The spacing between grid cells', 'ba-cheetah'),
						'units' => array('px'),
						'slider' => true,
						'default' => '20',
						'default_unit' => 'px',
						'responsive' => true,
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} .ba-module__posts',
							'property' => 'gap'
						)
					),
					'excerpt_length' => array(
						'type' => 'unit',
						'label' => __('Excerpt length', 'ba-cheetah'),
						'help' => __('Number of words that will be displayed in the post summary content', 'ba-cheetah'),
						'units' => array('words'),
						'slider' => true,
						'default' => '55',
						'default_unit' => 'words',
					),
					'default_photo'        => array(
						'type'        => 'photo',
						'label'       => __('Default photo', 'ba-cheetah'),
						'default'	  => BACheetahCardModule::DEFAULT_PHOTO,
						'show_remove' => true,
					),
				)
			),
			'meta' => array(
				'title' => __('Meta', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'separator' => array(
						'type' => 'text',
						'label' => __('Separator', 'ba-cheetah'),
						'default' => '|'
					),
					'display_date' => array(
						'type' => 'button-group',
						'label' => __('Date', 'ba-cheetah'),
						'default' => 'show',
						'options' => array(
							'show' => '<svg width="17.007" height="11.071"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>',
							'hide' => '<svg width="17.007" height="15.383"><use xlink:href="#ba-cheetah-icon--eye-hide"></use></svg>',
						)
					),
					'display_time' => array(
						'type' => 'button-group',
						'label' => __('Hour', 'ba-cheetah'),
						'default' => 'hide',
						'options' => array(
							'show' => '<svg width="17.007" height="11.071"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>',
							'hide' => '<svg width="17.007" height="15.383"><use xlink:href="#ba-cheetah-icon--eye-hide"></use></svg>',
						)
					),
					'display_author' => array(
						'type' => 'button-group',
						'label' => __('Author', 'ba-cheetah'),
						'default' => 'show',
						'options' => array(
							'show' => '<svg width="17.007" height="11.071"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>',
							'hide' => '<svg width="17.007" height="15.383"><use xlink:href="#ba-cheetah-icon--eye-hide"></use></svg>',
						)
					),
					'display_comments' => array(
						'type' => 'button-group',
						'label' => __('Comments', 'ba-cheetah'),
						'default' => 'hide',
						'options' => array(
							'show' => '<svg width="17.007" height="11.071"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>',
							'hide' => '<svg width="17.007" height="15.383"><use xlink:href="#ba-cheetah-icon--eye-hide"></use></svg>',
						)
					),
					'display_category' => array(
						'type' => 'button-group',
						'label' => __('Category', 'ba-cheetah'),
						'default' => 'hide',
						'options' => array(
							'show' => '<svg width="17.007" height="11.071"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>',
							'hide' => '<svg width="17.007" height="15.383"><use xlink:href="#ba-cheetah-icon--eye-hide"></use></svg>',
						)
					),
					'display_price' => array(
						'type' => 'button-group',
						'label' => __('Price', 'ba-cheetah'),
						'default' => 'show',
						'options' => array(
							'show' => '<svg width="17.007" height="11.071"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>',
							'hide' => '<svg width="17.007" height="15.383"><use xlink:href="#ba-cheetah-icon--eye-hide"></use></svg>',
						)
					),
				)
			)
		)
	),
	'style' => $post_styles_settings,
	'button' => $post_button_settings,
));
