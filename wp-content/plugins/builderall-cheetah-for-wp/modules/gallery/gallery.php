<?php

class BACheetahGalleryModule extends BACheetahModule
{

	public function __construct()
	{
		parent::__construct(array(
			'name' => __('Gallery', 'ba-cheetah'),
			'description' => __('Display multiple photos in a gallery view.', 'ba-cheetah'),
			'category' => __('Media', 'ba-cheetah'),
			'icon' => 'format-gallery.svg',
			'partial_refresh' => true,
			'editor_export' => false,
		));

		$this->add_styles_scripts();
	}

	public function add_styles_scripts()
	{
		$this->add_js('jquery-wookmark');
		$this->add_js('jquery-mosaicflow');
		$this->add_js('imagesloaded');

		$override_lightbox = apply_filters('ba_cheetah_override_lightbox', false);
		if (!$override_lightbox) {
			$this->add_js('jquery-fancybox');
			$this->add_js('jquery-mosaic');

			$this->add_css('jquery-fancybox');
			$this->add_css('jquery-fancybox-custom');
			$this->add_css('jquery-mosaic');
		} else {
			wp_dequeue_script('jquery-fancybox');
			wp_dequeue_script('jquery-mosaic');

			wp_dequeue_style('jquery-fancybox');
			wp_dequeue_style('jquery-fancybox-custom');
			wp_dequeue_style('jquery-mosaic');
		}
	}

	public function update($settings)
	{
		$settings->photo_data = $this->get_photos();

		return $settings;
	}

	/**
	 * @method get_photos
	 */
	public function get_photos()
	{
		$photos   = array();
		$ids      = $this->settings->photos;
		$medium_w = get_option('medium_size_w');
		$large_w  = get_option('large_size_w');

		if (empty($this->settings->photos)) {
			return $photos;
		}

		foreach ($ids as $id) {

			$photo = BACheetahPhoto::get_attachment_data($id);

			// Use the cache if we didn't get a photo from the id.
			if (!$photo) {

				if (!isset($this->settings->photo_data)) {
					continue;
				}

				if (is_array($this->settings->photo_data)) {
					$photos[$id] = $this->settings->photo_data[$id];
				} elseif (is_object($this->settings->photo_data)) {
					$photos[$id] = $this->settings->photo_data->{$id};
				} else {
					continue;
				}
			}

			// Only use photos who have the sizes object.
			if (isset($photo->sizes)) {

				// Photo data object
				$data              = new stdClass();
				$data->id          = $id;
				$data->alt         = $photo->alt;
				$data->caption     = $photo->caption;
				$data->description = $photo->description;
				$data->title       = $photo->title;

				// Photo src
				if ($this->settings->photo_size < $medium_w && isset($photo->sizes->medium)) {
					$data->src = $photo->sizes->medium->url;
				} elseif ($this->settings->photo_size <= $large_w && isset($photo->sizes->large)) {
					$data->src = $photo->sizes->large->url;
				} else {
					$data->src = $photo->sizes->full->url;
				}

				// Photo Link
				if (isset($photo->sizes->large)) {
					$data->link = $photo->sizes->large->url;
				} else {
					$data->link = $photo->sizes->full->url;
				}

				// Push the photo data
				$photos[$id] = $data;
			}
		}

		return $photos;
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahGalleryModule', array(
	'general' => array(
		'title' => __('General', 'ba-cheetah'),
		'sections' => array(
			'general' => array(
				'title' => '',
				'fields' => array(
					'layout'              => array(
						'type'    => 'select',
						'label'   => __('Layout', 'ba-cheetah'),
						'default' => 'grid',
						'options' => array(
							'grid'    => __('Grid', 'ba-cheetah'),
							'mosaic' => __('Mosaic', 'ba-cheetah'),
						),
						'toggle'  => array(
							'grid' => array(
								'fields' => array('columns'),
							),
						),
					),
					'columns' => array(
						'type' => 'select',
						'label' => __('Columns', 'ba-cheetah'),
						'default' => '4',
						'responsive' => true,
						'options' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
							'7' => '7',
							'8' => '8',
							'9' => '9',
							'10' => '10',
						),
					),
					'max_row_height' => array(
						'type'     => 'unit',
						'label'    => __('Max row height', 'ba-cheetah'),
						'default'  => '240',
						'sanitize' => 'absint',
						'units'    => array('px'),
						'slider' => array(
							'min' => 40,
							'max' => 1000,
							'step' => 10
						),
						'preview'  => array(
							'type' => 'none',
						),
					),
					'photos' => array(
						'type' => 'multiple-photos',
						'label' => __('Photos', 'ba-cheetah'),
						'connections' => array('multiple-photos'),
					),
					'photo_size' => array(
						'type' => 'select',
						'label' => __('Photo Size', 'ba-cheetah'),
						'default' => '300',
						'options' => array(
							'200' => _x('Small', 'Photo size.', 'ba-cheetah'),
							'300' => _x('Medium', 'Photo size.', 'ba-cheetah'),
							'400' => _x('Large', 'Photo size.', 'ba-cheetah'),
						),
					),
					'show_captions' => array(
						'type' => 'select',
						'label' => __('Show Captions', 'ba-cheetah'),
						'default' => '0',
						'options' => array(
							'0' => __('Never', 'ba-cheetah'),
							'hover' => __('On Hover', 'ba-cheetah'),
							'below' => __('Below Photo', 'ba-cheetah'),
						),
						'help' => __('The caption pulls from whatever text you put in the caption area in the media manager for each image. The caption is also pulled directly from SmugMug if you have captions set in your gallery.', 'ba-cheetah'),
					),
					'click_action' => array(
						'type' => 'select',
						'label' => __('Click Action', 'ba-cheetah'),
						'default' => 'lightbox',
						'options' => array(
							'none' => _x('None', 'Click action.', 'ba-cheetah'),
							'lightbox' => __('Lightbox', 'ba-cheetah'),
							'file'     => __('Photo File', 'ba-cheetah'),
						),
						'toggle' => array(
							'lightbox' => array(
								'fields' => array('lightbox_image_size'),
							),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
					'lightbox_image_size' => array(
						'type' => 'photo-sizes',
						'label' => __('Lightbox Photo Size', 'ba-cheetah'),
						'default' => 'large',
					)
				)
			)
		)
	),

	'style' => array( // Tab
		'title' => __('Style', 'ba-cheetah'),
		'sections' => array(
			'general' => array(
				'title' => '',
				'fields' => array(
					'spacing' => array(
						'type'     => 'unit',
						'label'    => __('Photo Spacing', 'ba-cheetah'),
						'default'  => '20',
						'sanitize' => 'absint',
						'units'    => array('px'),
						'slider'   => true,
						'preview'  => array(
							'type' => 'none',
						),
					),

					'border' => array(
						'type' => 'border',
						'label' => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview' => array(
							'type' => 'css',
							'selector' => '.ba-cheetah-photo-content img',
						),
						'default' => array(
							'style' => '',
							'color' => '',
							'width' => array(
								'top' => '',
								'right' => '',
								'bottom' => '',
								'left' => '',
							),
							'radius' => array(
								'top_left' => '15',
								'top_right' => '15',
								'bottom_left' => '15',
								'bottom_right' => '15',
							),
							'shadow' => array(
								'color' => 'rgba(76,76,76,0.56)',
								'horizontal' => '0',
								'vertical' => '2',
								'blur' => '3',
								'spread' => '',
							),
						),
					),
				),
			),
		),
	),
));
