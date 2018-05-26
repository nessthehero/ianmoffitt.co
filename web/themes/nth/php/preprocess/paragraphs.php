<?php

	// Html preprocessing
	function nth_preprocess_paragraph(&$variables) {

		$paragraph = $variables['paragraph'];

		if (!empty($variables['view_mode'])) {
			$mode = $variables['view_mode'];
		} else {
			$mode = 'full';
		}

		if (!empty($paragraph)) {

			$type = $paragraph->type->getValue()[0]['target_id'];

			switch ($type) {

				case 'accordion' :

					$variables['heading'] = $paragraph->get('field_accordion_heading')->value;
					$variables['description'] = $paragraph->get('field_description')->value;
					$accordions = $paragraph->field_accordion_items;
					$variables['items'] = load_paragraphs($accordions);

					break;

				case 'accordion_item' :

					if (!$paragraph->field_accordion_item_content->isEmpty()) {
						$variables['content'] = $paragraph->get('field_accordion_item_content')->value;
					}

					if (!$paragraph->field_accordion_item_heading->isEmpty()) {
						$variables['heading'] = $paragraph->get('field_accordion_item_heading')->value;
					}
					break;

				case 'code_sample':

					$variables['#attached']['library'][] = 'nth/prism';

					$variables['heading'] = $paragraph->get('field_heading')->value;
					$variables['language'] = $paragraph->get('field_language')->value;

					$variables['code'] = $paragraph->get('field_code')->value;

					break;

				case 'media':

					$variables['attributes']['class'][] = 'media';

					$items = $paragraph->get('field_media_items');

					if ($items->count() > 1) {
						$variables['attributes']['class'][] = 'media--slider';
					}

					break;

				case 'media_item':

					$variables['caption'] = $paragraph->get('field_caption')->value;

					$variables['image'] = image_url($paragraph, 'field_image', 'masthead');
					$variables['alt'] = $paragraph->field_image->alt;

					break;

				case 'wysiwyg':

					$variables['heading'] = $paragraph->get('field_heading')->value;
					$variables['copy'] = $paragraph->get('field_copy')->value;

					break;

				case 'team':

					$teammates = $paragraph->field_teammates;
					$variables['teammates'] = load_paragraphs($teammates);

					break;

				case 'team_member':

					if (!$paragraph->field_teammate->isEmpty()) {

						$variables['teammate'] = '';

						$variables['involvement'] = $paragraph->get('field_involvement')->value;
						$teammate = $paragraph->get('field_teammate')->first();

						if ($teammate->_loaded) {
							$variables['teammate'] = load_node($teammate->target_id);
						}

					}

					break;

				default:

					break;

			}

		}

	}
