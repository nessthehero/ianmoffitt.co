<?php

	// Node preprocessing
	function nth_preprocess_node(&$variables)
	{

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

			default:

				break;

		}

	}
