<?php

require_once 'app/services/ba-cheetah-webinar.php';

class BACheetahWebinarModule extends BACheetahModule
{

	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Webinar', 'ba-cheetah'),
			'description'     => __('Embed a Builderall Webinar', 'ba-cheetah'),
			'category'        => __('Builderall Apps', 'ba-cheetah'),
			'icon'            => 'builderall-webinar.svg',
		));
	}

    public function getEmbedLink() {
        $id_secret = $this->settings->webinar_id_secret;
        return "https://webinar2.builderall.com/embed/{$id_secret}";
    }

	public static function get_webinars() {
		$webinars = BACheetahWebinar::getWebinars();
		$return = [];
		foreach ($webinars as $key => $webinar) {
			array_push($return, array(
				'value' => $webinar['id'].'/'.$webinar['secret'],
				'label' => $webinar['name']
			));
		}
		return $return;
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahWebinarModule', array(
    'my-tab-1'      => array(
        'title'         => __('Webinar', 'ba-cheetah'),
        'sections'      => array(
            'webinar-section-1'  => array(
                'title'         => __('Webinar options', 'ba-cheetah'),
                'fields'        => array(
                    'webinar_id_secret' => array(
                        'type'          => 'select',
                        'label'         => __('Webinar', 'ba-cheetah'),
                        'help'			=> __('Select a webinar created with Builderall Webinar.', 'ba-cheetah'),
                        'options'       => array()
                    ),
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
                            'selector' => '{node} .ba-module__webinar',
                            'property' => 'height'
                        )
                    ),
                    'raw' => array(
                        'type' => 'raw',
                        'content' => '<br><br>
                            <a href="'.BA_CHEETAH_OFFICE_URL.'us/office/new-webinar" target="_blank" class="ba-cheetah-button ba-cheetah-button-primary">'
                            .__('Manage my webinars', 'ba-cheetah').
                            '<i style="margin-left: 8px" class="fas fa-external-link-alt"></i>'.
                            '</a>'
                    )
                ),
            )
        )
    )
));
