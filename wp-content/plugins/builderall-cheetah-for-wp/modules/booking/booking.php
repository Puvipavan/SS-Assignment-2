<?php

require_once 'app/services/ba-cheetah-booking-calendars.php';

/**
 * BACheetahBookingModule
 */

class BACheetahBookingModule extends BACheetahModule
{

	public function __construct()
	{
		parent::__construct(array(
			'name'            => 'Booking',
			'description'     => __('Embed a Builderall Booking Calendar', 'ba-cheetah'),
			'category'        => __('Builderall Apps', 'ba-cheetah'),
			'icon'            => 'builderall-booking.svg',
		));
	}

	public function getEmbedLink() {
		$link = $this->settings->calendar;
		if ($this->settings->group) {
			$link .= '/' . $this->settings->group;
		}
		$link .= '?show_event_infos=' . $this->settings->show_event_infos;
		return $link;
	}

	public static function get_calendars() {
		$calendars = BACheetahBookingCalendars::getCalendars();
		$return = [];
		foreach ($calendars as $key => $calendar) {
			array_push($return, array(
				'value' 	=> $calendar['url'],
				'label' 	=> $calendar['title'],
				'type' 		=> $calendar['type'],
				'classes' 	=> $calendar['classes'],
			));
		}
		return $return;
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahBookingModule', array(
	'my-tab-1'      => array(
		'title'         => __('Booking', 'ba-cheetah'),
		'sections'      => array(
			'booking-section-1'  => array(
				'title'         => __('Booking Options', 'ba-cheetah'),
				'fields'        => array(
					'calendar' => array(
						'type'          => 'select',
						'label'         => __('Calendar', 'ba-cheetah'),
						'help'			=> __('Select a calendar created with Builderall Booking. You can customize the appearance of the calendar in our own scheduling application', 'ba-cheetah'),
						'options'       => array()
					),
					'group' => array(
						'type'          => 'select',
						'label'         => __('Group', 'ba-cheetah'),
						'options'       => array()
					),
					'show_event_infos' => array(
						'type'          => 'select',
						'label'         => __('Display calendar information?', 'ba-cheetah'),
						'options'       => array(
							'true' => __('Yes', 'ba-cheetah'),
							'false' => __('No', 'ba-cheetah'),
						)
					),
					'heigh' => array(
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
							'selector' => '{node} .ba-module__booking',
							'property' => 'height'
						)
					),
					'raw' => array(
						'type' => 'raw',
						'content' => '<br><br>
							<a href="https://booking.builderall.com/calendars" target="_blank" class="ba-cheetah-button ba-cheetah-button-primary">'
							.__('Manage my calendars', 'ba-cheetah').
							'<i style="margin-left: 8px" class="fas fa-external-link-alt"></i>'.
							'</a>'
					)
				),
			)
		)
	)
));
