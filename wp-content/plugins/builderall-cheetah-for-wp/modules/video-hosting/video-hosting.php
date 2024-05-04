<?php

require_once 'app/services/ba-cheetah-video-hosting.php';

/**
 * BACheetahVideoHostingModule
 */

class BACheetahVideoHostingModule extends BACheetahModule
{

	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Video Hosting', 'ba-cheetah'),
			'description'     => __('Embed a Builderall Video Hosting', 'ba-cheetah'),
			'category'        => __('Builderall Apps', 'ba-cheetah'),
			'icon'            => 'builderall-video-hosting.svg',
		));
	}

    public function getEmbedLink() {
        $secret = $this->settings->video_hosting;
        $params = [];

        if($this->settings->loop) {
            $params[] = 'loop=1';
        }

        if($this->settings->controls) {
            $params[] = 'controls=1';

            if($this->settings->speed) {
                $params[] = 'speed=1';
            }
        }

        if($this->settings->autoplay) {
            $params[] = 'autoplay=1';
        }

        if($this->settings->countdown && !$this->settings->autoplay) {
            $params[] = 'countdown=1';
        }

        if($this->settings->allowpause) {
            $params[] = 'allowpause=1';
        }

        if($this->settings->soundfs) {
            $params[] = 'soundfs=1';
        }

        $params = implode('&', $params);

        return "https://videomng.builderall.com/embed/{$secret}/?{$params}";
    }

	public static function get_videos() {
		$videos = BACheetahVideoHosting::getVideosHosting();
		$return = [];
		foreach ($videos as $key => $video) {
			array_push($return, array(
				'value' => $video['secret'],
				'label' => $video['name']
			));
		}
		return $return;
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahVideoHostingModule', array(
    'my-tab-1'      => array(
        'title'         => __('Video hosting', 'ba-cheetah'),
        'sections'      => array(
            'video-hosting-section-1'  => array(
                'title'         => __('Video hosting options', 'ba-cheetah'),
                'fields' => array(
                    'video_hosting' => array(
                        'type' => 'select',
                        'label' => __('Video', 'ba-cheetah'),
                        'help' => __('Select a video created with Builderall Video hosting.', 'ba-cheetah'),
                        'options' => array()
                    ),
                    'autoplay' => array(
                        'type' => 'button-group',
                        'label' => __('Auto Play', 'ba-cheetah'),
                        'default' => '0',
                        'options' => array(
                            '0' => __('No', 'ba-cheetah'),
                            '1' => __('Yes', 'ba-cheetah'),
                        ),
                        'preview' => array(
                            'type' => 'none',
                        ),
                        'toggle' => array(
                            '0' => array(
                                'fields' => array(''),
                            ),
                            '1' => array(
                                'fields' => array('countdown'),
                            ),
                        ),
                    ),
                    'countdown' => array(
                        'type' => 'button-group',
                        'label' => __('Countdown', 'ba-cheetah'),
                        'default' => '0',
                        'options' => array(
                            '0' => __('No', 'ba-cheetah'),
                            '1' => __('Yes', 'ba-cheetah'),
                        )
                    ),
                    'controls' => array(
                        'type' => 'button-group',
                        'label' => __('Show controls', 'ba-cheetah'),
                        'default' => '0',
                        'options' => array(
                            '0' => __('No', 'ba-cheetah'),
                            '1' => __('Yes', 'ba-cheetah'),
                        ),
                        'toggle' => array(
                            '0' => array(
                                'fields' => array('allowpause', 'soundfs'),
                            ),
                            '1' => array(
                                'fields' => array('speed'),
                            ),
                        ),
                    ),
                    'soundfs' => array(
                        'type' => 'button-group',
                        'label' => __('Sound and full-screen controls', 'ba-cheetah'),
                        'default' => '0',
                        'options' => array(
                            '0' => __('No', 'ba-cheetah'),
                            '1' => __('Yes', 'ba-cheetah'),
                        ),
                    ),
                    'allowpause' => array(
                        'type' => 'button-group',
                        'label' => __('Allow pause', 'ba-cheetah'),
                        'default' => '0',
                        'options' => array(
                            '0' => __('No', 'ba-cheetah'),
                            '1' => __('Yes', 'ba-cheetah'),
                        )
                    ),
                    'speed' => array(
                        'type' => 'button-group',
                        'label' => __('Playback Speed', 'ba-cheetah'),
                        'default' => '0',
                        'options' => array(
                            '0' => __('No', 'ba-cheetah'),
                            '1' => __('Yes', 'ba-cheetah'),
                        )
                    ),
                    'loop' => array(
                        'type' => 'button-group',
                        'label' => __('Loop', 'ba-cheetah'),
                        'default' => '0',
                        'options' => array(
                            '0' => __('No', 'ba-cheetah'),
                            '1' => __('Yes', 'ba-cheetah'),
                        )
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
                            'selector' => '{node} .ba-module__video-hosting',
                            'property' => 'height'
                        )
                    ),
                    'raw' => array(
                        'type' => 'raw',
                        'content' => '<br><br>
                            <a href="'.BA_CHEETAH_OFFICE_URL.'us/office/video-manager" target="_blank" class="ba-cheetah-button ba-cheetah-button-primary">'
                            . __('Manage my videos', 'ba-cheetah') .
                            '<i style="margin-left: 8px" class="fas fa-external-link-alt"></i>' .
                            '</a>'
                    )
                ),
            )
        )
    )
));
