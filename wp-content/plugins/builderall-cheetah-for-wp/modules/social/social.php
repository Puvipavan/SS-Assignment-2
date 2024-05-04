<?php

/**
 * @class BACheetahSocialIconsModule
 */
class BACheetahSocialIconsModule extends BACheetahModule
{

	/**
	 * Source @link https://www.lockedownseo.com/social-media-colors/
	 */
	const SOCIAL_NETWORK_COLORS = array(
		'facebook' 		=> '#1877f2',
		'twitter' 		=> '#1da1f2',
		'youtube' 		=> '#ff0000',
		'instagram' 	=> '#c32aa3',
		'google' 		=> '#4285f4',
		'pinterest' 	=> '#bd081c',
		'linkedin' 		=> '#0a66c2',
		'vimeo' 		=> '#1ab7ea',
		'tumblr' 		=> '#2c4762',
		'snapchat' 		=> '#fffc00',
		'whatsapp' 		=> '#25d366',
		'tiktok' 		=> '#010101', 
		'mastodon' 		=> '#2b90d9',
		'apple' 		=> '#a6b1b7',
		'amazon' 		=> '#ff9900',
		'microsoft' 	=> '#f35022',
		'periscope' 	=> '#40a4c4',
		'foursquare' 	=> '#f94877',
		'yelp' 			=> '#d32323',
		'swarm' 		=> '#ffa633',
		'medium' 		=> '#02b875',
		'skype' 		=> '#00aff0',
		'android' 		=> '#a4c639',
		'stumbleupon' 	=> '#e94826',
		'flickr' 		=> '#f40083',
		'yahoo' 		=> '#430297',
		'twitch' 		=> '#9146ff',
		'soundcloud' 	=> '#ff5500',
		'spotify' 		=> '#1ed760',
		'dribbble' 		=> '#ea4c89',
		'slack' 		=> '#4a154b', 
		'reddit' 		=> '#ff5700',
		'deviantart'	=> '#05cc47',
		'pocket' 		=> '#ee4056',
		'quora'			=> '#aa2200',
		'vine'			=> '#00b489',
		'steam'			=> '#171a21',
		'discord'		=> '#7289da',
		'telegram'		=> '#0088cc',
		'clarity'		=> '#61bed9',
		'homeadvisor'	=> '#f89000',
		'houzz'			=> '#4dbc15',
		'angieslist'	=> '#29a036',
		'glassdoor'		=> '#0caa41',
		'wordpress'		=> '#21759b',
	);

	const SHAPES = array(
		'rounded'	=> '0.4em',
		'square'	=> '0',
		'circle'	=> '50%',
		'custom'	=> 'initial'
	);

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Social Icons', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Social', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'social.svg',
		));
	}

	/**
	 * Convert shape field to border units
	 *
	 * @return string
	 */
	public function shape_to_unit() {
		return self::SHAPES[$this->settings->icon_shape];
	}

	/**
	 * Get the social network color by Font Aewsome 5 brand string
	 *
	 * @param String $fa_icon_name the social icon name
	 * @return String
	 */
	public function get_social_network_color($fa_icon_name = '')
	{
		foreach (self::SOCIAL_NETWORK_COLORS as $name => $hex) {
			if (strrpos($fa_icon_name, $name) !== false) {
				return $hex;
			}
		}
		return '#000';
	}
}

/**
 * Subform social network item
 */
BACheetah::register_settings_form('social_item', array(
	'title' => __('Social networks', 'ba-cheetah'),
	'tabs'  => array(
		'general'      => array(
			'title'         => '',
			'sections'      => array(
				'general'       => array(
					'title'         => '',
					'fields'        => array(
						'icon'         => array(
							'type'          => 'icon',
							'label'         => __('Icon', 'ba-cheetah'),
						),
						'name'         => array(
							'type'          => 'text',
							'label'         => __('Name', 'ba-cheetah'),
							'placeholder'   => __('Name', 'ba-cheetah'),
							'help'			=> __('Icon name for the title attribute and screen readers', 'ba-cheetah')
						),
						'link'         => array(
							'type'          => 'link',
							'label'         => __('Text', 'ba-cheetah'),
							'label'         => 'Link',
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
BACheetah::register_module('BACheetahSocialIconsModule', array(
	'items' => array(
		'title' => __('Items', 'ba-cheetah'),
		'sections' => array(
			'items' => array(
				'title' => '',
				'fields' => array(
					"items" => array(
						'type'          => 'form',
						'label'         => __('List item', 'ba-cheetah'),
						'form'          => 'social_item',
						'preview_text'  => 'name',
						'multiple'      => true,
						'limit'         => 15,
						'default' => array(
							array('icon' => 'fab fa-instagram', 'name' => 'Instagram', 'link' => 'https://www.instagram.com'),
							array('icon' => 'fab fa-facebook-f', 'name' => 'Facebook', 'link' => 'https://www.facebook.com/'),
							array('icon' => 'fab fa-youtube', 'name' => 'YouTube', 'link' => 'http://youtube.com/'),
							array('icon' => 'fab fa-twitter', 'name' => 'Twitter', 'link' => 'https://twitter.com'),							
						)
					),
				)
			),
		)
	),
	'styles' => array(
		'title' => __('Style', 'ba-cheetah'),
		'sections' => array(
			'content' => array(
				'title' => __('Layout', 'ba-cheetah'),
				'fields' => array(
					'icon_shape' => array(
						'label' => __('Shape', 'ba-cheetah'),
						'type' => 'select',
						'default' => 'circle',
						'options' => array(
							'rounded' => __('Rounded', 'ba-cheetah'),
							'square' => __('Square', 'ba-cheetah'),
							'circle' => __('Circle', 'ba-cheetah'),
							'custom' => __('Customize', 'ba-cheetah').'...',
						),
						'toggle' => array(
							'custom' => array(
								'fields' => array('border')
							)
						)
					),
					'border' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__social ul .social__item',
						),
					),
					'alignment' => array(
						'type' => 'align',
						'label' => __('Alignment', 'ba-cheetah'),
						'responsive' => true,
						'values' => array(
							'left' => 'flex-start',
							'center' => 'center',
							'right' => 'flex-end'
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__social ul',
							'property'	=> 'justify-content'
						),
						'default' => 'flex-start'
					),
					'size' => array(
						'type' => 'unit',
						'label' => __('Size', 'ba-cheetah'),
						'default' => '25',
						'units' => array('px'),
						'default_unit' => 'px',
						'responsive' => true,
						'slider' => array(
							'min' => 0,
							'max' => 300,
							'step' => 1,
						),
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} ul .social__item a',
							'property' => 'font-size',
						),
					),
					'padding' => array(
						'type' => 'unit',
						'label' => __('Padding', 'ba-cheetah'),
						'help' => __('The space between the icon and the box border', 'ba-cheetah'),
						'default' => '0.5',
						'units' => array('em', 'px'),
						'default_unit' => 'em',
						'responsive' => true,
						'slider' => array(
							'px' => array(
								'min' => '0',
								'max' => '100',
								'step' => '1'
							),
							'px' => array(
								'min' => '0',
								'max' => '10',
								'step' => '0.1'
							)
						),
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} ul .social__item a',
							'property' => 'padding',
						),
					),
					'gap' => array(
						'type' => 'unit',
						'label' => __('Spacing between items', 'ba-cheetah'),
						'default' => '5',
						'units' => array('px'),
						'default_unit' => 'px',
						'slider' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
						'preview'    => array(
							'type' => 'css',
							'selector' => '{node} .ba-module__social ul',
							'property' => 'gap',
						),
					),
				)
			),
			'colors' => array(
				'title' => __('Colors', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'use_default_colors' => array(
						'type' => 'button-group',
						'label' => __('Color', 'ba-cheetah'),
						'default' => 'yes',
						'options' => array(
							'yes' => __('Original', 'ba-cheetah'),
							'no' => __('Custom', 'ba-cheetah'),
						),
						'toggle' => array(
							'no' => array(
								'fields' => array('background', 'color')
							)
						)
					),
					'background' => array(
						'type' 		=> 'color',
						'label'		=> __('Background', 'ba-cheetah'),
						'default'	=> '0080fc',
					),
					'color' => array(
						'type' 		=> 'color',
						'label'		=> __('Color', 'ba-cheetah'),
						'default'	=> 'ffffff',
					),
					'colors_on_hover' => array(
						'type' => 'button-group',
						'label' => __('On Hover', 'ba-cheetah'),
						'default' => 'disabled',
						'options' => array(
							'disabled' => __('Disabled', 'ba-cheetah'),
							'original' => __('Original', 'ba-cheetah'),
							'custom' => __('Custom', 'ba-cheetah'),
						),
						'preview' => 'none',
						'toggle' => array(
							'custom' => array(
								'fields' => array('background_hover', 'color_hover', 'transition_duration'),
							),
							'original' => array(
								'fields' => array('transition_duration')
							)
						)
					),
					'background_hover' => array(
						'type' 		=> 'color',
						'label'		=> __('Background', 'ba-cheetah'),
						'default'	=> 'dddddd',
						'preview' => 'none'
					),
					'color_hover' => array(
						'type' 		=> 'color',
						'label'		=> __('Color', 'ba-cheetah'),
						'default'	=> '0080fc',
						'preview' => 'none'
					),
					'transition_duration' => array(
						'type' => 'unit',
						'label' => __('Transition duration', 'ba-cheetah'),
						'default' => '300',
						'units' => array('ms'),
						'default_unit' => 'ms',
						'slider' => array(
							'min' => 100,
							'max' => 3000,
							'step' => 50,
						),
						'preview' => 'none'
					),
				)
			),
		)
	)
));
