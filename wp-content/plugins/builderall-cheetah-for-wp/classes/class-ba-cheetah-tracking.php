<?php
final class BACheetahTracking
{

	/**
	 * Check if facebook pixel is enabled
	 *
	 * @return boolean
	 */
	public static function is_facebook_pixel_enabled() {
		return get_option( '_ba_cheetah_facebook_pixel_id' ) !=  false;
	}

	/**
	 * Get attr to render event data in a button or tag who trigger a configured tracking event
	 *
	 * @param StdClass $settings
	 * @param string $key
	 * @return void
	 */
	public static function get_pixel_attr($settings, $key = 'tracking_pixel_event'){
		$pixel = '';
		if ($settings instanceof stdClass && property_exists($settings, $key) && !empty($settings->{$key})) {
			$pixel = sprintf('data-pixel-event="%s"', $settings->{$key});
		}
		return $pixel;
	}

	/**
	 * Helper to render pixel fields
	 *
	 * @return void
	 */
	public static function module_tracking_fields($prefix = '') {
		$fields = [];
		if (self::is_facebook_pixel_enabled()) {
			$fields = [
				$prefix . 'tracking_pixel_event' => array(
					'type' => 'text',
					'help' => __('Facebook Pixel custom event that will fire on click', 'ba-cheetah'),
					'label' => '<span class="dashicons dashicons-facebook" style="font-size: 20px;line-height: 1; color: #0080fc;"></span>'.
						__('Pixel event', 'ba-cheetah'),
					'preview' => array(
						'type' => 'none'
					)
				)
			];
		}

		return $fields;
	}


	/**
	 * Get events that triggered onload page
	 *
	 * @return array
	 */
	public static function get_page_load_events()
	{
		$pixel_events_onload = [];
		$postmeta = get_metadata( 'post', BACheetahModel::get_post_id(), '_ba_cheetah_data_settings', true );
		
		if (property_exists($postmeta, 'pixel_events_onload') && !empty($postmeta->pixel_events_onload)) {
			$pixel_events_onload = array_map( function($event) { 
				return sprintf("fbq('%s','%s');", 
					$event->event == 'PageView' ? 'track' : 'trackCustom',
					$event->event
				);
			}, $postmeta->pixel_events_onload);
		}

		if (empty($pixel_events_onload)) {
			array_push($pixel_events_onload, "fbq('track','PageView');");
		}

		return $pixel_events_onload;
	}

	/**
	 * Facebook pixel code for rendering before </head> tag
	 *
	 * @return void
	 */

	public static function render_facebook_pixel_header_code () {
		if (!BACheetahModel::is_builder_active() && self::is_facebook_pixel_enabled()) {
			$pixelID = get_option( '_ba_cheetah_facebook_pixel_id' );
			$pixel_events_onload = self::get_page_load_events();?>
			<!-- Meta Pixel Code -->
			<script>
			!function(f,b,e,v,n,t,s)
			{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};
			if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
			n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t,s)}(window, document,'script',
			'https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '<?php echo $pixelID; ?>');
			<?php echo join($pixel_events_onload) ?>
			console.log('a');
			</script>
			<noscript><img height="1" width="1" style="display:none" 
			src="<?php echo "https://www.facebook.com/tr?id=$pixelID&ev=PageView&noscript=1" ?>"
			/></noscript>
			<!-- End Meta Pixel Code -->
			<?php
		}
	}
}
