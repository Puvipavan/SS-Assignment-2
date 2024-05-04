<?php

/**
 * @class BACheetahIframeModule
 */
class BACheetahIframeModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Iframe', 'ba-cheetah' ),
			'description'     => '',
			'category'        => __('Basic', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'iframe.svg',
		));
	}

	public function get_iframe() {
		if ($this->settings->source == 'url' && !empty($this->settings->url)) {
			return 
			'<iframe 
				src="'. esc_url($this->settings->url) .'"
				scrolling="'. esc_attr($this->settings->scrolling) .'"
				frameborder="0"
				width="100%"
			></iframe>';
		} 

		else if ($this->settings->source == 'html' && !empty($this->settings->html)) {
			if (strpos($this->settings->html, 'iframe') !== false) {
				return $this->settings->html;
			} else {
				return __('Invalid iframe code', 'ba-cheetah');
			}
		}

		else {
			return __('Configure iframe source', 'ba-cheetah');
		}
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahIframeModule', array(
	'general' => array(
		'title'    => __( 'General', 'ba-cheetah' ),
		'sections' => array(
			'general' => array(
				'title'  => '',
				'fields' => array(
					'source' => array(
						'type' => 'button-group',
						'label' => __('Source', 'ba-cheetah'),
						'default' => 'url',
						'options' => array(
							'url' => 'URL',
							'html' => 'HTML'
						),
						'toggle' => array(
							'url' => array('fields' => array('url')),
							'html' => array('fields' => array('html', 'styles_raw')),
						)
					),
					'url' => array(
						'type'        => 'text',
						'label'       => 'URL',
						'placeholder' => 'https://www.example.com'
					),
					'html' => array(
						'type'        => 'textarea',
						'label'       => 'HTML',
						'rows'          => '10',
						'placeholder' => '<iframe src="https://www.example.com" height="450" allowfullscreen="" loading="lazy"></iframe>'
					),
				)
			),
		)
	),
	'style' => array(
		'title'    => __( 'Style', 'ba-cheetah' ),
		'sections' => array(
			'style' => array(
				'title' => '',
				'fields' => array(
					'styles_raw' => array(
						'type' => 'raw',
						'content' => '<br>'.__('Styles will only be applied correctly if the embedding code does not contain the respective attributes or inline styles defined', 'ba-cheetah').'<br><br>'
					),
					'height' => array(
						'type' => 'unit',
						'label' => __('Height', 'ba-cheetah'),
						'units' => array('px', 'rem', 'vh', '%'),
						'default_unit' => 'px',
						'default' => '400',
						'responsive' => true,
						'slider' => array(
							'min' => '10',
							'max' => '1000',
							'step' => '10'
						),
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} iframe',
							'property' => 'height'
						)
					),
					'scrolling' => array(
						'type' => 'select',
						'label' => __('Scrolling', 'ba-cheetah'),
						'options' => array(
							'auto' => 'Auto',
							'no' => 'No',
							'yes' => 'Yes',
						),
						'default' => 'auto',
					),
					'border'  => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} iframe',
						),
					),
				),
			),
		),
	),
));
