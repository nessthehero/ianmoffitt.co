<?php

	// Node preprocessing
	function nth_preprocess_node(&$variables) {

		$node = $variables['node'];
		$type = $node->getType();
		// $lang = $node->language();
		$mode = $variables['view_mode'];
		$_q = \Drupal::request()->request->all();
		$_embed = array();

		if ($node->hasField('field_heading')) {
			$variables['heading'] = $node->get('field_heading')->value;
		} else {
			$variables['heading'] = $node->get('title')->value;
		}

		switch ($type) {

			case 'home':

				break;

			case 'work':

				$variables['icon'] = 'briefcase';
				$variables['type'] = 'Work Project';
				$variables['thumbnail'] = image_url($node, 'field_thumbnail', 'result');

				if (!$node->field_components->isEmpty()) {
					$variables['components'] = load_paragraphs($node->field_components);
				}

				break;

			default:

				break;

		}

	}
