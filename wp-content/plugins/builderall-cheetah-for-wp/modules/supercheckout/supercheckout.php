<?php

require_once 'app/services/ba-cheetah-supercheckout.php';

if(!defined('BA_CHEETAH_SC_URL')) {
	define('BA_CHEETAH_SC_URL', 'https://s-checkout.builderall.com/');
}

/**
 * BACheetahSupercheckoutModule
 */
class BACheetahSupercheckoutModule extends BACheetahModule
{

	/**

	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Supercheckout', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Builderall Apps', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'builderall-supercheckout.svg',
		));
	}

	public static function get_products()
	{
		$products = BACheetahSupercheckout::getProducts();
		$return = [];
		foreach ($products as $key => $product) {
			$return[] = array(
				'value' => $key,
				'label' => $product['name']
			);
		}
		return $return;
	}
}

/**
 * Register the module and its form settings.
 */

BACheetah::register_module('BACheetahSupercheckoutModule', array(
	'general'      => array(
		'title'         => __('General', 'ba-cheetah'),
		'sections'      => array(
			'general'  => array(
				'fields'        => array(
					'product' => array(
						'title'         => __('Product', 'ba-cheetah'),
						'type'          => 'select',
						'label'         => __('Supercheckout product', 'ba-cheetah'),
						'help'			=> __('Select the desired product. You can create and edit products directly on Supercheckout', 'ba-cheetah'),
						'options'       => array()
					),
					'raw' => array(
						'type' => 'raw',
						'content' => '<br><br>
							<a href="'.BA_CHEETAH_SC_URL.'?token=' . BACheetahSupercheckout::getToken() . '" id=""supercheckout-login target="_blank" class="ba-cheetah-button ba-cheetah-button-primary">'
							. __('Manage my products', 'ba-cheetah') .
							'<i style="margin-left: 8px" class="fas fa-external-link-alt"></i>' .
							'</a>'
					),
				),
			),
			'layout' => array(
				'fields' => array(
					'height' => array(
						'type' => 'unit',
						'label' => __('Height', 'ba-cheetah'),
						'units' => array('px', 'vh'),
						'default_unit' => 'px',
						'default' => '740',
						'responsive' => true,
						'slider' => array(
							'px' => array(
								'min' => 0,
								'max' => 2000,
								'step' => 10,
							),
							'vh' => array(
								'min' => 0,
								'max' => 100,
								'step' => 10,
							)
						),
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} .ba-module__supercheckout',
							'property' => 'height'
						)
					),
				)
			)
		),
	),

));