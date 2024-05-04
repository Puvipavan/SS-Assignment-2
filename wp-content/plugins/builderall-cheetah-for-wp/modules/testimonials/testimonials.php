<?php

/**
 * @class BACheetahTestimonialsModule
 */
class BACheetahTestimonialsModule extends BACheetahModule
{

	const DEFAULT_AVATAR = BA_CHEETAH_URL . 'img/demo/avatar.jpg';

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Testimonials', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Layout', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'testimonial.svg',
		));
	}

	public function enqueue_scripts()
	{
		if ($this->is_carousel_active() || BACheetahModel::is_builder_active()) {
			$this->add_js('splide');
			$this->add_css('splide');
			$this->add_css('splide-theme');
		}
	}

	public function is_carousel_active()
	{
		return $this->settings && $this->settings->display == 'carousel';
	}


	/**
	 * Add splide classes to testimonials HTML element
	 * if is carousel active
	 *
	 * @param string $class
	 * @return string
	 */

	public function splide_class($css_class = '')
	{
		if ($this->is_carousel_active()) {
			return $css_class;
		}
	}

	/**
	 * The content must expand. so it varies according to the order 
	 *
	 * @return string
	 */

	public function get_box_template_rows()
	{
		if ($this->settings->layout != 'fluid-row') {
			return '1fr auto';
		}
		$contentPosition = $this->get_section_order('content');
		$rows = array_fill(0, 3, 'auto');
		$rows[$contentPosition] = '1fr';
		return join(' ', $rows);
	}


	/**
	 * Return a order of section
	 *
	 * @param string $section
	 * @return integer
	 */

	public function get_section_order($section = '')
	{
		if ($this->settings->layout != 'fluid-row') {
			return false;
		}
		foreach ($this->settings->order as $key => $element) {
			if ($element == $section) {
				return $key;
			}
		}
		return 'initial';
	}

	/**
	 * Return grid row css attr
	 *
	 * @param string $section
	 * @return string
	 */


	public function get_grid_row($section = '')
	{
		$order = $this->get_section_order($section);
		if ($order !== false) {
			return 'grid-row: ' . ($order + 1) . ';';
		}
	}
}

/**
 * Subform testimonial items
 */
BACheetah::register_settings_form('testimonial_item', array(
	'title' => __('Testimonial', 'ba-cheetah'),
	'tabs'  => array(
		'general'      => array(
			'title'         => '',
			'sections'      => array(
				'testimonial'	=> array(
					'title'		=> 'Testimonial',
					'fields'	=> array(
						'author' => array(
							'type'	=> 'text',
							'label'	=> __('Author', 'ba-cheetah'),
						),
						'role' => array(
							'type'	=> 'text',
							'label'	=> __('Role', 'ba-cheetah'),
						),
						'photo' => array(
							'type' => 'photo',
							'label' => __('Photo', 'ba-cheetah'),
							'show_remove' => true,
						),
						'content'	=> array(
							'label'			=> __('Testimonial', 'ba-cheetah'),
							'type'          => 'editor',
							'media_buttons' => true,
							'wpautop'       => true
						),
					)
				),
			)
		)
	)
));

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahTestimonialsModule', array(
	'testimonials' => array(
		'title' => __('Testimonials', 'ba-cheetah'),
		'sections' => array(
			'items' => array(
				'title' => '',
				'fields' => array(
					"testimonials" => array(
						'type'          => 'form',
						'label'         => __('Testimonial', 'ba-cheetah'),
						'form'          => 'testimonial_item',
						'preview_text'  => 'content',
						'multiple'      => true,
						'limit'         => 15,
						'default' => array_fill(0, 3, array(
							'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque vel nulla ut odio lacinia faucibus. Vestibulum ut volutpat tellus, eget venenatis odio',
							'author' => 'John Doe',
							'role' => 'CEO',
							'photo' => 'default',
							'photo_src' => BACheetahTestimonialsModule::DEFAULT_AVATAR
						))
					),
				)
			),
		)
	),
	'layout' => array(
		'title' => __('Layout', 'ba-cheetah'),
		'sections' => array(
			'layout' => array(
				'title' => __('Layout', 'ba-cheetah'),
				'fields' => array(
					'display' => array(
						'type' => 'button-group',
						'label' => __('Layout', 'ba-cheetah'),
						'default' => 'carousel',
						'options' => array(
							'carousel' => __('Carousel', 'ba-cheetah'),
							'grid' => __('Grid', 'ba-cheetah'),
						),
						'toggle' => array(
							'carousel' => array(
								'sections' => array('carousel')
							)
						)
					),
					'layout' => array(
						'type' => 'button-group',
						'label' => __('Layout', 'ba-cheetah'),
						'default' => 'fluid-row',
						'options' => array(
							'fluid-row' => __('Fluid row', 'ba-cheetah'),
							'avatar-left' => __('Avatar left', 'ba-cheetah'),
							'avatar-right' => __('Avatar right', 'ba-cheetah'),
						),
						'toggle' => array(
							'fluid-row' => array(
								'sections' => array('ordering')
							)
						)
					),
					'columns' => array(
						'type' => 'select',
						'label' => __('Number of columns', 'ba-cheetah'),
						'help' => __('Number of grid columns or number of carousel slides.', 'ba-cheetah'),
						'responsive' => true,
						'default' => '2',
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
						'help' => __('The spacing between grid cells or between carousel slides', 'ba-cheetah'),
						// 'responsive' => true,
						'units' => array('px'),
						'slider' => true,
						'default' => '20',
						'default_unit' => 'px',
						'responsive' => true
					)
				)
			),
			'carousel' => array(
				'title' => __('Carousel', 'ba-cheetah'),
				'fields' => array(
					'autoplay' => array(
						'type'    => 'button-group',
						'label'   => __('Auto Play', 'ba-cheetah'),
						'default' => 'true',
						'options' => array(
							'false' => __('No', 'ba-cheetah'),
							'true'  => __('Yes', 'ba-cheetah'),
						),
					),
					'interval'              => array(
						'type'     => 'unit',
						'label'    => __('Interval', 'ba-cheetah'),
						'default'  => '5',
						'sanitize' => 'BACheetahUtils::sanitize_non_negative_number',
						'units'    => array('seconds'),
						'slider'   => true,
					),
					'transition_speed' => array(
						'type'     => 'unit',
						'label'    => __('Transition Speed', 'ba-cheetah'),
						'default'  => '2',
						'sanitize' => 'BACheetahUtils::sanitize_non_negative_number',
						'units'    => array('seconds'),
						'slider'   => true,
					),
					'pagination' => array(
						'type'    => 'button-group',
						'label'   => __('Show dots?', 'ba-cheetah'),
						'default' => 'true',
						'options' => array(
							'false' => __('No', 'ba-cheetah'),
							'true'  => __('Yes', 'ba-cheetah'),
						),
					),
					'carousel_nav_background' => array(
						'label' => __('Navigation background', 'ba-cheetah'),
						'type' => 'color',
						'default' => 'f6f6fc',
						'show_alpha' => true,
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} .splide__arrows .splide__arrow, {node} .splide__pagination__page',
							'property' => 'background'
						),
					),
					'carousel_nav_emphasis_color' => array(
						'label' => __('Navigation Emphasis color', 'ba-cheetah'),
						'type' => 'color',
						'default' => '4054b2',
						'show_alpha' => true,
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '{node} .splide__arrow svg',
									'property' => 'fill'
								),
								array(
									'selector' => '{node} .splide__pagination__page.is-active',
									'property' => 'background'
								),
							)
						),
					),
				)
			),
		)
	),
	'styles'      => array(
		'title' => __('Style', 'ba-cheetah'),
		'sections' => array(
			'box' => array(
				'title'  => __('Box', 'ba-cheetah'),
				'fields' => array(
					'box_background_type' => array(
						'label' => __('Background type', 'ba-cheetah'),
						'type' => 'button-group',
						'default' => 'solid',
						'options' => array(
							'solid' => __('Solid', 'ba-cheetah'),
							'gradient' => __('Gradient', 'ba-cheetah'),
						),
						'toggle' => array(
							'solid' => array('fields' => array('box_background')),
							'gradient' => array('fields' => array('box_background_gradient'))
						)
					),
					'box_background_gradient' => array(
						'type'    => 'gradient',
						'label' => __('Gradient Background', 'ba-cheetah'),
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .testimonials__box',
							'property'  => 'background-image',
						),
					),
					'box_background' => array(
						'label' => __('Background', 'ba-cheetah'),
						'type' => 'color',
						'default' => 'fcfcfc',
						'show_alpha'    => true,
						'show_reset' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .testimonials__box',
							'property'	=> 'background'
						),
					),
					'box_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'slider'     => true,
						'responsive' => true,
						'units'      => array('px'),
						'default' 	 => '20',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .testimonials__box',
							'property' => 'padding',
						),
					),
					'box_border'  => array(
						'type' => 'border',
						'label' => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} .testimonials__box',
						),
						'default' => array(
							"shadow" => array(
								'color' => 'rgba(196,196,196,0.63)',
								'horizontal' => '0',
								'vertical' => '3',
								'blur' => '2',
								'spread' => '0',
							)
						)
					),

				)
			),
			'avatar' => array(
				'title' => __('Avatar', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'avatar_size' => array(
						'type' => 'unit',
						'label' => __('Size', 'ba-cheetah'),
						'responsive' => true,
						'units' => array('%', 'px'),
						'slider' => array(
							'px' => array(
								'min' => 50,
								'max' => 500,
								'step' => 1
							),
							'%' => array(
								'min' => 0,
								'max' => 100,
								'step' => 1
							)
						),
						'default' => '100',
						'default_unit' => 'px',
						'responsive' => true,
						'preview' => array(
							'type'          => 'css',
							'rules'           => array(
								array(
									'selector'     => '{node} .testimonials__box .testimonials__avatar',
									'property'     => 'height'
								),
								array(
									'selector'     => '{node} .testimonials__box .testimonials__avatar',
									'property'     => 'width'
								),
							)
						)
					),
					'avatar_margin' => array(
						'type'       => 'dimension',
						'label'      => __('Margin', 'ba-cheetah'),
						'responsive' => true,
						'default'	 => 10,
						'slider'     => array(
							'min' => -100,
							'max' => 100,
							'step' => 1
						),
						/*
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .testimonials__box .testimonials__avatar',
							'property' 	=> 'margin'
						),
						*/
					),
					'avatar_border' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .testimonials__box .testimonials__avatar',
						),
						'default' => array(
							'radius' => array(
								'top_left' => '25',
								'top_right' => '25',
								'bottom_left' => '25',
								'bottom_right' => '25',
							),
							'shadow' => array(
								'color' => 'rgba(10,0,0,0.18)',
								'horizontal' => '0',
								'vertical' => '10',
								'blur' => '10',
								'spread' => '',
							),
						)
					)
				)
			),
			'content' => array(
				'title'  => __('Content', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'content_color' => array(
						'label' => __('Color', 'ba-cheetah'),
						'type' => 'color',
						'default' => '9498b2',
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .testimonials__box .testimonials__content',
							'property'	=> 'color'
						),
					),
					'content_background' => array(
						'label' => __('Background', 'ba-cheetah'),
						'type' => 'color',
						'show_alpha' => true,
						'show_reset' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .testimonials__box .testimonials__content',
							'property'	=> 'background'
						),
					),
					'content_typography' => array(
						'type'       => 'typography',
						'label'      => __('Typography', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .testimonials__box .testimonials__content',
						),
						'default' =>   array(
							'font_family' => 'Helvetica',
							'font_weight' => '400',
							'text_align' => 'center',
							'font_size' => array(
								'length' => '16',
								'unit' => 'px',
							),
						)
					),
					'content_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'slider'     => true,
						'responsive' => true,
						'units'      => array('px'),
						'default' 	 => '35',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .testimonials__box .testimonials__content',
							'property' => 'padding',
						),
					),
				),
			),
			'author' => array(
				'title' => __('Author', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'author_color' => array(
						'label' => __('Name Color', 'ba-cheetah'),
						'type' => 'color',
						'default' => '4054b2',
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .testimonials__box .testimonials__credits .testimonials__author',
							'property'	=> 'color'
						),
					),
					'author_typography' => array(
						'type'       => 'typography',
						'label'      => __('Name Typography', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .testimonials__box .testimonials__credits .testimonials__author',
						),
						'default' => array(
							'font_family' => 'Helvetica',
							'font_weight' => '700',
						)
					),
					'author_hr' => array(
						'type' => 'raw',
						'content' => '<hr>'
					),
					'role_color' => array(
						'label' => __('Role Color', 'ba-cheetah'),
						'type' => 'color',
						'default' => '9999b2',
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .testimonials__box .testimonials__credits .testimonials__role',
							'property'	=> 'color'
						),
					),
					'role_typography' => array(
						'type'       => 'typography',
						'label'      => __('Role Typography', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .testimonials__box .testimonials__credits .testimonials__role',
						),
						'default' => array(
							'font_family' => 'Helvetica',
							'font_weight' => '400',
						)
					),
					'author_background' => array(
						'label' => __('Background', 'ba-cheetah'),
						'type' => 'color',
						'show_alpha' => true,
						'show_reset' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .testimonials__box .testimonials__credits',
							'property'	=> 'background'
						),
					),
					'author_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'slider'     => true,
						'responsive' => true,
						'units'      => array('px'),
						'default' 	 => '15',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .testimonials__box .testimonials__credits',
							'property' => 'padding',
						),
					),
				)
			),
			'ordering' => array(
				'title' => __('Ordering', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'order' => array(
						'type' => 'ordering',
						'options' => array(
							'avatar' => __('Avatar', 'ba-cheetah'),
							'content' => __('Content', 'ba-cheetah'),
							'author' => __('Author', 'ba-cheetah'),
						),
						'default' => array(
							'avatar',
							'author',
							'content',
						)
					),
				)
			)
		)
	),
));
