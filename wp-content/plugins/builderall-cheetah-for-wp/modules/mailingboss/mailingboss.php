<?php

require_once 'app/services/ba-cheetah-mailingboss.php';
require_once 'app/ba-cheetah-mailingboss-fields.php';

/**
 * @class BACheetahMailingbossFormModule
 */
class BACheetahMailingbossModule extends BACheetahModule
{

	/**

	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Mailingboss', 'ba-cheetah'),
			'description'     => __('', 'ba-cheetah'),
			'category'        => __('Builderall Apps', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'builderall-mailingboss.svg',
		));

		$this->add_js('google-recaptcha', 'https://www.google.com/recaptcha/api.js');
	}

	/**
	 * Cache fields when settings saved
	 *
	 */
	public function update($settings)
	{
		if (!empty($settings->mailingboss_list)) {
			$fields = BACheetahMailingboss::getListFields($settings->mailingboss_list);
			$settings->mailingboss_list_cache_fields = json_encode($fields);
		}
		
		return $settings;
	}

	/**
	 * Get fields from api only if editor is active or is not cached
	 *
	 */

	public function getFields() 
	{
		if (empty($this->settings->mailingboss_list_cache_fields)) {
			return BACheetahMailingboss::getListFields($this->settings->mailingboss_list);
		} else {
			return json_decode($this->settings->mailingboss_list_cache_fields, true);
		}
	}

	public function renderRecaptcha()
	{
		if (isset($this->settings->recaptcha_type) && !empty($this->settings->recaptcha_type) ) {
			if ($this->settings->recaptcha_type == 'v2_checkbox') {
				BACheetahRecaptchaV2::render();
			}
		}
	}

	public function get_button_settings()
	{
		$settings = array(
			'id' 	=> 'ba-cheetah-node-' . $this->node,
			'type' 	=> 'submit',
			'tag'	=> 'button'
		);
		foreach ($this->settings as $key => $value) {
			if (strstr($key, 'btn_')) {
				$key              = str_replace('btn_', '', $key);
				$settings[$key] = $value;
			}
		}
		return $settings;
	}

	public static function get_lists() {
		$lists = BACheetahMailingboss::getLists();
		$return = [];
		foreach ($lists as $key => $list) {
			array_push($return, array(
				'value' => $list['general']['list_uid'],
				'label' => $list['general']['name'],
				'url_subscribe' => $list['url_subscribe'],
			));
		}
		return $return;
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahMailingbossModule', array(
	'general'      => array(
		'title'         => __('General', 'ba-cheetah'),
		'sections'      => array(
			'general'  => array(
				'title'         => __('List', 'ba-cheetah'),
				'fields'        => array(
					'mailingboss_list' => array(
						'type'          => 'select',
						'label'         => __('Mailingboss list', 'ba-cheetah'),
						'help'			=> __('Select the desired list. You can create and edit lists directly on Mailingboss', 'ba-cheetah'),
						'options'       => array()
					),
					'mailingboss_list_cache_fields' => array(
						'type' => 'hidden',
					),
					'target'   => array(
						'type'    => 'button-group',
						'label'   => __('Target', 'ba-cheetah'),
						'default' => '_blank',
						'options' => array(
							'_blank'     => __('New tab', 'ba-cheetah'),
							'_self' => __('Same tab', 'ba-cheetah'),
						)
					),
					'url_subscribe' => array(
						'type'        => 'text',
						'label'       => 'URL'
					),
					'raw' => array(
						'type' => 'raw',
						'content' => '<br><br>
							<a href="'.BA_CHEETAH_OFFICE_URL.'/us/office/mailingboss" target="_blank" class="ba-cheetah-button ba-cheetah-button-primary">'
							. __('Manage my lists', 'ba-cheetah') .
							'<i style="margin-left: 8px" class="fas fa-external-link-alt"></i>' .
							'</a>'
					)
				),
			),

			'recaptcha'  => array(
				'title'         => __('Integrations', 'ba-cheetah'),
				'collapsed'		=> true,
				'fields'        => array(
					'recaptcha_type' => array(
						'type'          => 'select',
						'label'         => __('Recaptcha', 'ba-cheetah'),
						'default'		=> '',
						'options'       => array(
							'' => __('Disabled', 'ba-cheetah'),
							'v2_checkbox' => 'reCAPTCHA v2 - "I\'m not a robot" checkbox',
						),
						'preview' => array('type' => 'none'),
						'toggle' => array(
							'v2_checkbox' => array('fields' => array(
								'sitekey', 'secretkey', 'raw_recaptcha'
							))
						)
					),
					'raw_recaptcha' => array(
						'type' => 'raw',
						'label' => 'Recaptcha Keys',
						'description' => '<br>'.__('You need to put your Recaptcha keys in the integrations tab of the Builderall Builder settings to use Recaptcha', 'ba-cheetah'),
						'content' => '<a href="'.admin_url('admin.php?page=ba-cheetah-settings#integrations').'" target="_blank" class="ba-cheetah-button ba-cheetah-button-primary">'
							. __('Configure your keys', 'ba-cheetah') .
							'</a>'
					),
					'utm_bindings' => array(
						'type' => 'button-group',
						'label' => __('UTM Bindings', 'ba-cheetah'),
						'help' => __('Enabling this field, if there are UTM parameters in the page URL, they will be passed to the corresponding field of the form.', 'ba-cheetah'),
						'default' => 'disabled',
						'preview' => array(
							'type' => 'none'
						),
						'options' => array(
							'disabled' => __('Disabled', 'ba-cheetah'),
							'enabled' => __('Enabled', 'ba-cheetah'),
						)
					)
				),
			),

			'button'  => array(
				'title'  => 'Button',
				'collapsed' => true,
				'fields' => array_merge( array(
					'btn_text'           => array(
						'type'        => 'text',
						'label'       => __('Title', 'ba-cheetah'),
						'default'     => __('Submit', 'ba-cheetah'),
						'preview'     => array(
							'type'     => 'text',
							'selector' => '{node} .ba-module__button .button__content .button__text',
						),
					),
					'btn_sub_text'           => array(
						'type'        => 'text',
						'label'       => __('Subtitle', 'ba-cheetah'),
						'default'     => '',
						'preview'     => array(
							'type'     => 'text',
							'selector' => '{node} .ba-module__button .button__content .button__subtext',
						),
					),
					'btn_icon'           => array(
						'type'        => 'icon',
						'label'       => __('Icon', 'ba-cheetah'),
						'show_remove' => true,
						'show'        => array(
							'fields' => array('btn_icon_position'),
							'sections' => array('btn_icon_styles')
						),
					),
					'btn_icon_position'  => array(
						'type'    => 'select',
						'label'   => __('Icon Position', 'ba-cheetah'),
						'default' => 'before',
						'options' => array(
							'before' => __('Before Text', 'ba-cheetah'),
							'after'  => __('After Text', 'ba-cheetah'),
						),
					),
				), BACheetahTracking::module_tracking_fields('btn_')),
			),
		),
	),

	'fields_style'   => array(
		'title'    => __('Form style', 'ba-cheetah'),
		'sections' => array(
			'layout' => array(
				'title'  => __('Layout', 'ba-cheetah'),
				'fields' => array(
					'columns' => array(
						'label' => __('Columns', 'ba-cheetah'),
						'default' => '1',
						'type' => 'select',
						'responsive' => true,
						'options' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						)
					),
					'gap' => array(
						'label' => __('Gap', 'ba-cheetah'),
						'type' => 'unit',
						'default_unit' => 'px',
						'responsive' => true,
						'default' => '10',
						'units' => array('px'),
						'slider' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .mailingboss__form',
							'property'	=> 'gap'
						),
					)
				)
			),

			'box' => array(
				'title'  => __('Container', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'box_background' => array(
						'label' => __('Background', 'ba-cheetah'),
						'type' => 'color',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .mailingboss__form',
							'property'	=> 'background'
						),
					),
					'box_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'slider'     => true,
						'responsive' => true,
						'units'      => array('px'),
						'default' 	 => 0,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .mailingboss__form',
							'property' => 'padding',
						),
					),
					'box_border'  => array(
						'type' => 'border',
						'label' => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} .mailingboss__form',
						),
						'default' => array(
							"style" => "",
							"color" => "",
							"width" => array(
								"top" => 0,
								"right" => 0,
								"bottom" => 0,
								"left" => 0
							),
							"radius" => array(
								"top_left" => "0",
								"top_right" => "0",
								"bottom_left" => "0",
								"bottom_right" => "0"
							),
							"shadow" => array(
								"color" => "c4c4c4",
								"horizontal" => "0",
								"vertical" => "0",
								"blur" => "0",
								"spread" => "0"
							)
						)
					),

				)
			),

			'label' => array(
				'title'  => __('Labels', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'display_labels' => array(
						'type' => 'button-group',
						'label' => __('Display labels?', 'ba-cheetah'),
						'options' => array(
							'no' => __('No', 'ba-cheetah'),
							'yes' => __('Yes', 'ba-cheetah'),
						),
						'default' => 'yes',
						'toggle' => array(
							'yes' => array(
								'fields' => array('label_color', 'label_typography', 'label_padding')
							)
						)
					),
					'label_color' => array(
						'label' => __('Label Color', 'ba-cheetah'),
						'type' => 'color',
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .mailingboss__form .mailingboss-label',
							'property'	=> 'color'
						),
					),
					'label_typography' => array(
						'type'       => 'typography',
						'label'      => __('Label Typography', 'ba-cheetah'),
						'responsive' => true,
						'default' => array(),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .mailingboss__form .mailingboss-label',
						),
					),
					'label_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'responsive' => true,
						'slider'     => true,
						'units'      => array('px'),
						'preview'    => array(
							'type'     => 'css',
							'selector'  => '{node} .mailingboss__form .mailingboss-label',
							'property' => 'padding',
						),
					),
				)
			),

			'fields' => array(
				'title'  => __('Fields', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'placeholder_content' => array(
						'type' => 'button-group',
						'label' => __('Placeholder Content', 'ba-cheetah'),
						'options' => array(
							'none' => __('None', 'ba-cheetah'),
							'label' => __('The Label', 'ba-cheetah'),
							'help_text' => __('The Help Text', 'ba-cheetah'),
						),
						'default' => 'label'
					),
					'field_background_color' => array(
						'label' => __('Background', 'ba-cheetah'),
						'type' => 'color',
						'default' => 'fff',
						'show_alpha'    => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .mailingboss__form input, {node} .mailingboss__form select, {node} .mailingboss__form textarea',
							'property'	=> 'background',
							'important' => true,
						),
					),
					'field_color' => array(
						'label' => __('Color', 'ba-cheetah'),
						'type' => 'color',
						'default' => '3a3a3a',
						'show_alpha'    => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .mailingboss__form input, {node} .mailingboss__form select, {node} .mailingboss__form textarea',
							'property'	=> 'color',
							'important' => true,
						),
					),
					'field_typography' => array(
						'type'       => 'typography',
						'label'      => __('Field Typography', 'ba-cheetah'),
						'responsive' => true,
						'default' => array(),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .mailingboss__form input, {node} .mailingboss__form textarea, {node} .mailingboss__form select',
						),
					),
					'field_border'  => array(
						'type' => 'border',
						'label' => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} .mailingboss__form input, {node} .mailingboss__form textarea, {node} .mailingboss__form select',
							'important' => true
						),
						'default' => array(
							"style" => "solid",
							"color" => "ced4da",
							"width" => array(
								"top" => 1,
								"right" => 1,
								"bottom" => 1,
								"left" => 1
							),
							"radius" => array(
								"top_left" => "5",
								"top_right" => "5",
								"bottom_left" => "5",
								"bottom_right" => "5"
							),
							"shadow" => array(
								"color" => "c4c4c4",
								"horizontal" => "0",
								"vertical" => "0",
								"blur" => "0",
								"spread" => "0"
							)
						)
					),
					'field_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'responsive' => true,
						'slider'     => true,
						'units'      => array('px'),
						'preview'    => array(
							'type'     => 'css',
							'selector'  => '{node} .mailingboss__form input, {node} .mailingboss__form textarea, {node} .mailingboss__form select',
							'property' => 'padding',
						),
					),
				)
			),

			'checkbox_and_radios' => array(
				'title'  => __('Checkbox and Radio', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'cr_color' => array(
						'label' => __('Color', 'ba-cheetah'),
						'type' => 'color',
						'show_alpha'    => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .mailingboss-cr-label',
							'property'	=> 'color',
							'important' => true,
						),
					),
					'cr_typography' => array(
						'type'       => 'typography',
						'label'      => __('Label Typography', 'ba-cheetah'),
						'responsive' => true,
						'default' => array(),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .mailingboss-cr-label',
						),
					),

					'cr_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'responsive' => true,
						'slider'     => true,
						'units'      => array('px'),
						'preview'    => array(
							'type'     => 'css',
							'selector'  => '{node} .mailingboss-cr-label',
							'property' => 'padding',
						),
					),
				)
			),
		)
	),

	'button_style'   => array(
		'title'    => __('Button style', 'ba-cheetah'),
		'sections' => array(
			'style'  => array(
				'title'  => __('Size & Align', 'ba-cheetah'),
				'fields' => array(
					'btn_width'        => array(
						'type'    => 'select',
						'label'   => __('Width', 'ba-cheetah'),
						'default' => 'auto',
						'options' => array(
							'auto'   => _x('Auto', 'Width.', 'ba-cheetah'),
							'full'   => __('Full Width', 'ba-cheetah'),
							'custom' => __('Custom', 'ba-cheetah'),
						),
						'toggle'  => array(
							'auto'   => array(
								'fields' => array('btn_align'),
							),
							'full'   => array(),
							'custom' => array(
								'fields' => array('btn_align', 'btn_custom_width'),
							),
						),
					),
					'btn_custom_width' => array(
						'type'    => 'unit',
						'label'   => __('Custom Width', 'ba-cheetah'),
						'default' => '200',
						'slider'  => array(
							'px' => array(
								'min'  => 0,
								'max'  => 1000,
								'step' => 10,
							),
						),
						'units'   => array(
							'px',
							'vw',
							'%',
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__button .button__button',
							'property' => 'width',
						),
					),
					'btn_align'        => array(
						'type'       => 'align',
						'label'      => __('Align', 'ba-cheetah'),
						'default'    => 'flex-start',
						'values' => array(
							'left' => 'flex-start',
							'center' => 'center',
							'right' => 'flex-end'
						),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__button',
							'property' => 'justify-content',
						),
					),
					'btn_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'responsive' => true,
						'slider'     => true,
						'units'      => array('px'),
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__button .button__button',
							'property' => 'padding',
						),
					),
				),
			),
			'colors' => array(
				'title'  => __('Background', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'btn_bg_color_type' => array(
						'label' => __('Background type', 'ba-cheetah'),
						'type' => 'button-group',
						'default' => 'solid',
						'options' => array(
							'solid' => __('Solid', 'ba-cheetah'),
							'gradient' => __('Gradient', 'ba-cheetah'),
						),
						'toggle' => array(
							'solid' => array('fields' => array('btn_bg_color')),
							'gradient' => array('fields' => array('btn_bg_gradient'))
						)
					),
					'btn_bg_color'          => array(
						'label' => __('Background color', 'ba-cheetah'),
						'type'        => 'color',
						'default'     => '0080FC',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button .button__button',
							'property'  => 'background-color',
						),
					),
					'btn_bg_gradient' => array(
						'type'    => 'gradient',
						'label' => __('Gradient Background', 'ba-cheetah'),
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button .button__button',
							'property'  => 'background-image',
						),
					),
					'btn_miau' => array(
						'type' => 'raw',
						'content' => '<hr>'
					),
					'btn_bg_hover_color_type' => array(
						'label'       => __('Background Hover Type', 'ba-cheetah'),
						'type' => 'button-group',
						'default' => 'solid',
						'options' => array(
							'solid' => __('Solid', 'ba-cheetah'),
							'gradient' => __('Gradient', 'ba-cheetah'),
						),
						'toggle' => array(
							'solid' => array('fields' => array('btn_bg_hover_color')),
							'gradient' => array('fields' => array('btn_bg_hover_gradient'))
						)
					),
					'btn_bg_hover_color'    => array(
						'label'       => __('Background Hover Color', 'ba-cheetah'),
						'type'        => 'color',
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type' => 'none',
						),
					),
					'btn_bg_hover_gradient' => array(
						'label'       => __('Gradient Background Hover Color', 'ba-cheetah'),
						'type'    => 'gradient',
						'preview'     => array(
							'type'      => 'none'
						),
					),
					'btn_transition' => array(
						'type'    => 'select',
						'label'   => __('Background Animation', 'ba-cheetah'),
						'default' => 'disable',
						'options' => array(
							'disable' => __('Disabled', 'ba-cheetah'),
							'enable'  => __('Enabled', 'ba-cheetah'),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
				),
			),
			'border' => array(
				'collapsed' => true,
				'title'  => __('Border', 'ba-cheetah'),
				'fields' => array(
					'btn_border' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button .button__button',
							'important' => true,
						),
						'default' => array(
							'style' => 'solid',
							'color' => '',
							'width' => array(
								'top' => '0',
								'right' => '0',
								'left' => '0',
								'bottom' => '0'
							)
						)
					),
					'btn_border_hover_color' => array(
						'type'        => 'color',
						'connections' => array('color'),
						'label'       => __('Border Hover Color', 'ba-cheetah'),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type' => 'none',
						),
					),
				),
			),
			'text'   => array(
				'title'  => __('Title', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'btn_text_color'       => array(
						'type'        => 'color',
						'label'       => __('Text Color', 'ba-cheetah'),
						'default'     => 'fff',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button .button__button .button__text',
							'property'  => 'color',
							'important' => true,
						),
					),
					'btn_text_hover_color' => array(
						'type'        => 'color',
						'connections' => array('color'),
						'label'       => __('Text Hover Color', 'ba-cheetah'),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'none',
						),
					),
					'btn_typography'       => array(
						'type'       => 'typography',
						'label'      => __('Typography', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__button .button__button .button__text',
						),
						'default' => array(
							"font_size" => array(
								"length" => "18",
								"unit" => "px"
							),
						),
					),
				),
			),
			'sub_text'   => array(
				'title'  => __('Subtitle', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'btn_sub_text_color'       => array(
						'type'        => 'color',
						'label'       => __('Text Color', 'ba-cheetah'),
						'default'     => 'fff',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button .button__button .button__subtext',
							'property'  => 'color',
							'important' => true,
						),
					),
					'btn_sub_typography'       => array(
						'type'       => 'typography',
						'label'      => __('Typography', 'ba-cheetah'),
						'responsive' => true,
						'default' => array(
							"font_size" => array(
								"length" => "12",
								"unit" => "px"
							),
						),
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__button .button__button .button__subtext',
						),
					),
				),
			),
			'btn_icon_styles'  => array(
				'title'  => __('Icon', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'btn_icon_color' => array(
						'label'      => __('Icon Color', 'ba-cheetah'),
						'type'       => 'color',
						'default'    => 'fff',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button i.button__icon',
							'property'  => 'color',
							'important' => true,
						),
					),
					'btn_icon_size' => array(
						'label'      => __('Icon size', 'ba-cheetah'),
						'type'       => 'unit',
						'default'    => '',
						'default_unit' => 'px',
						'units' => array('px', 'em'),
						'slider' 	=> true,
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button i.button__icon',
							'property'  => 'font-size',
						),
					),
					'btn_gap' => array(
						'label'      => __('Distance from text', 'ba-cheetah'),
						'type'       => 'unit',
						'default'    => '',
						'default_unit' => 'px',
						'units' => array('px'),
						'slider' 	=> true,
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button .button__button',
							'property'  => 'gap',
						),
					),
				),
			),
		),
	),

));
