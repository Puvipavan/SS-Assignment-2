<?php

/**
 * @class BACheetahMapModule
 */
class BACheetahMapModule extends BACheetahModule
{

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Map', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Media', 'ba-cheetah'),
			'partial_refresh' => true,
			'enabled'		  => true,
			'icon'            => 'location.svg',
		));
	}

	public function render_map()
	{
		$url_attrs = http_build_query([
			'z' 		=> $this->settings->zoom ? $this->settings->zoom : 10,
			't'			=> isset($this->settings->map_type) ? $this->settings->map_type : 'm',
			'q' 		=> $this->settings->locale,
			'output'	=> 'embed'

		]);

		$iframe_attrs = [
			"src" 				=> "https://maps.google.com/maps?" . $url_attrs,
			"title" 			=> $this->settings->locale,
			"aria-label" 		=> $this->settings->locale,
			"frameborder" 		=> "0",
			"scrolling" 		=> "no",
			"marginheight" 		=> "0",
			"marginwidth" 		=> "0",
			"width" 			=> "100%",
			"height" 			=> "100%",
			"style" 			=> "border:0",
			"loading" 			=> "lazy",
			"allowfullscreen" 	=> "1"
		];

		echo '<iframe ';
		foreach ($iframe_attrs as $key => $value) {
			echo sprintf('%s="%s"', $key, $value);
		}
		echo '></iframe>';
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module(
	'BACheetahMapModule',
	array(
		'map'      => array(
			'title' => __('Map', 'ba-cheetah'),
			'sections' => array(
				'locale' => array(
					'title' => '',
					'fields' => array(
						'locale' => array(
							'type' => 'text',
							'label' =>  __('Location', 'ba-cheetah'),
							'default' => 'Orlando, Forida, EUA',
							'help' => __('You can enter an address, a town, city or village, postcodes, variables of latitude and longitude (in either decimal form or as degrees, minutes and seconds) and landmarks. Ex: Orlando, Forida, EUA or 28.538336,-81.379234', 'ba-cheetah'),
							'description' => 'Ex: Orlando, Forida, EUA or 28.538336,-81.379234'
						),
						/*
						'key' => array(
							'type' => 'text',
							'label' =>  __('API Key', 'ba-cheetah'),
							'placeholder' => __('Paste your API Key here'),
							'description' => '
								<a href="https://developers.google.com/maps/documentation/embed/get-api-key" target="_blank" rel="noopener nofollow" style="text-decoration:underline">Get the API Key</a> and 
								<a href="https://console.cloud.google.com/apis/library/maps-embed-backend.googleapis.com" target="_blank" rel="noopener nofollow" style="text-decoration:underline">active the Maps Embed API</a>
							'
						),
						*/
						'map_type' => array(
							'label' => __('Type', 'ba-cheetah'),
							'type' => 'button-group',
							'default' => 'm',
							'options' => array(
								'm' => __('Map', 'ba-cheetah'),
								'k' => __('Satellite', 'ba-cheetah')
							)
						),
						'zoom' => array(
							'type' => 'unit',
							'label' => __('Zoom', 'ba-cheetah'),
							'units' => array('%'),
							'default' => '10',
							'default_unit' => '%',
							'slider' => array(
								'min'   => 0,
								'max'   => 20,
								'step'  => 1,
							),
						),
						'height' => array(
							'type' => 'unit',
							'label' => __('Height', 'ba-cheetah'),
							'units' => array('px', 'vh'),
							'default' => '400',
							'default_unit' => 'px',
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__map',
								'property' 	=> 'height',
							),
							'slider' => array(
								'min'   => 0,
								'max'   => 1000,
								'step'  => 10,
							),
						),
						'border'  => array(
							'type'       => 'border',
							'label'      => __('Border', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__map',
							),
						),
					),
				),
			),
		),
	)
);
