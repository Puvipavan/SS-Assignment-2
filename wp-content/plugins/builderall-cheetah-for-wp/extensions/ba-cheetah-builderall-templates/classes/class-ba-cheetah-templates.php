<?php

class BACheetahTemplates {

	static public function init() {
		add_filter('ba_cheetah_get_templates', __CLASS__ . '::register_builderall_templates', 1, 2);
	}

	static public function get_ba_templates()
	{

		$templates_file 			= BA_CHEETAH_DIR . 'data/templates.dat';
		$ba_templates_cached 		= get_transient('ba_templates_cached');
		$ba_templates 				= file_exists($templates_file) ? unserialize(file_get_contents($templates_file)) : array();

		// If the cache does not exist (or has expired) or if the template file does not exist
		if (!$ba_templates_cached || !$ba_templates) {

			// skip cache in content center
			$skip_cache = BA_CHEETAH_TEMPLATE_API_ENABLED ? '?skip_cache=1' : '';

			// Get templates
			$templates_req = wp_remote_get(BA_CHEETAH_TEMPLATE_API_URL . 'ba-cheetah-template' . $skip_cache, array('timeout' => 100));
			if (wp_remote_retrieve_response_code($templates_req) === 200) {
				$body = wp_remote_retrieve_body($templates_req);

				// Cache templates in json file
				file_put_contents($templates_file, serialize($body));
				// Specifies how long to remain in cache
				set_transient('ba_templates_cached', 1, 60 * 60 * 24 * 30); // 30 days

				return json_decode($body, true);
			} else {
				return array();
			}
		}

		return json_decode($ba_templates, true);
	}

	static public function register_builderall_templates($templates, $type) {

		$ba_templates = self::get_ba_templates();

		$i = 0;
		foreach($ba_templates as $ba_template) {

			if($ba_template['ba-cheetah-template-category'] && $ba_template['type'] == $type) {
				
				if (!isset($ba_template['metas'])) continue;

				$template_version = $ba_template['metas']['editor_version'] ? $ba_template['metas']['editor_version'] : BA_CHEETAH_VERSION;
				$template = (object) array(
					'id' => $ba_template['id'], 
					'name' => $ba_template['title']['rendered'],
					'preview_url' => $ba_template['link'],
					'image' => $ba_template['image'] ? $ba_template['image'] : BA_CHEETAH_BUILDERALL_TEMPLATES_URL . 'img/template-default-thumbnail.png',
					'categories' => array(
						$ba_template['category']['slug'] => $ba_template['category']['name']
					),
					'index' => $i,
					'pro' => isset($ba_template['metas']['pro']) ? $ba_template['metas']['pro'] : false,
					'type' => $ba_template['type'],
					'global' => false,
					'group' => $ba_template['category']['parent'] ? $ba_template['category']['parent'] : $ba_template['category']['name'],
					'editor_version' => $template_version,
					'compatible' => /*(boolean) rand(0, 1)*/ version_compare($template_version, BA_CHEETAH_VERSION, '<='),
				);

				$i++;
				$templates[] = $template;

			}

		}
		return $templates;
	}

	static public function clear_cache() {
		delete_transient('ba_templates_cached');
	}

}

BACheetahTemplates::init();