<?php

	namespace Nth\Finders;

	use \Drupal\node\Entity\Node;

	// Generic Entity finder
	//
	// Can be used for almost any entity. Offers basic taxonomy based filtering,
	// and other data retrieval methods.
	//
	// Shouldn't be used for things that require more complex handling, like programs, events, or faculty.
	//
	// Example of Use:
	//
	// Each 'field' you provide initial values for refers to the field of
	// the entity itself. For example
	//	$taxonomy = array(
	//		array(
	//			'field' => 'field_taxonomy_field',
	//			'value' => array(1, 2, 3)
	//		)
	//	);
	//
	// The machine name of a content type can be found when editing that content type
	// in the URL or near the title field when on the main edit page for that content type.
	//	$_items = new FinderQuery($taxonomy, array('machine_name'));
	//
	// Some methods can be called on this object at this point.
	//	$_items->shuffle();
	//
	// Provide an array of node IDs to remove them from the results set.
	//	$_items->remove();
	//
	// When you are ready to get the results...
	//	$items = $_items->results();
	//
	// By default, it returns 4 items as defined by MAX_POSTS.
	// Use -1 to return all items.
	//
	// Returns an array of each of the following:
	//
	//	array(
	//		'nid'             => $nid,
	//		'sticky'          => $node->isSticky(),
	//		'promoted'        => $node->isPromoted(),
	//		'title'           => $title
	//	);
	//
	// You can give this array to load_nodes() in /php/custom/nodes.php
	// to get an array of markup.

  // TODO: Implement the iFinder interface and rewrite to suit.

	class Finder
	{

		protected $posts = array();
		protected $rawPosts = array();
		protected $taxonomy = array();
		protected $sort = 'date';
		protected $mode = 'nonstrict';
		protected $machine = array();
		protected $promoted = false;
		protected $fields = array();

		const MAX_POSTS = 4;

		/**
		 * FinderQuery constructor.
		 *
		 * @param        $taxonomy
		 * @param array  $machine
		 * @param string $sort
		 * @param string $mode
		 */
		public function __construct($taxonomy, $machine = array('article'), $fields = array(), $sort = 'date', $mode = 'nonstrict')
		{

			$this->taxonomy = array_filter($taxonomy);
			$this->sort = $sort;
			$this->mode = $mode;
			$this->machine = $machine;

			$this->setFields($fields);

			$this->filter();

		}

		protected function filter()
		{

			$cached = '';
			$this->posts = [];

			$cache_tax = '';
			if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
				foreach ($this->taxonomy as $filter) {
					$cache_tax .= '-' . implode('-', $filter['value']);
				}
			}

			$cache_fields = '';
			if (!empty($this->fields) && is_array($this->fields)) {
				foreach ($this->fields as $key => $filter) {
					if (!empty($filter['value'])) {
						$cache_fields .= '-' . $key;
					}
				}
			}

			if (!empty($this->machine)) {
				if (is_array($this->machine)) {
					$cache_pre = implode('-', $this->machine);
				} else {
					$cache_pre = $this->machine;
				}
			} else {
				$cache_pre = 'node';
			}

			$cache_pre .= '-sorted-by-' . $this->sort;

			$cache_key = $cache_pre . '-' . date('Y-m-d-h', time()) . $cache_fields . $cache_tax;

			if ($this->promoted) {
				$cache_key = "promoted-" . $cache_key;
			}

			$cache_time = '+1 hours';
			$expire = strtotime($cache_time, time());

			if ($cached = \Drupal::cache()->get($cache_key)) {
				if (isset($cached->data) && !empty($cached->data)) {
					$this->posts = $cached->data;
				}
			}

			if (count($this->posts) == 0) {

				$query = \Drupal::entityQuery('node');

				if (!empty($this->machine)) {
					if (is_array($this->machine)) {
						foreach ($this->machine as $machine) {
							$query->condition('type', $machine);
						}
					} else {
						$query->condition('type', $this->machine);
					}
				}

				$query->condition('status', 1);

				if ($this->promoted) {
					$query->condition('promote', 1, '=');
				}

				if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
					foreach ($this->taxonomy as $filter) {
						if (!empty($filter['value'])) {
							switch ($this->mode) {
								case 'strict':
									$query->condition($filter['field'], $filter['value']);
									break;

								default:
									$query->condition($filter['field'], $filter['value'], 'IN');
									break;
							}
						}
					}
				}

				if (!empty($this->fields) && is_array($this->fields)) {
					foreach ($this->fields as $key => $filter) {
						if (!empty($filter['value'])) {
							if (!empty($filter['compare'])) {
								$query->condition($key, $filter['value'], $filter['compare']);
							} else {
								$query->condition($key, $filter['value'], '=');
							}
						}
					}
				}

				if ($this->sort == 'date') {
					$query->sort('created', 'DESC');
				} else {
					$query->sort('title', 'ASC');
				}

				$query->sort('sticky');

				$posts = $query->execute();

				$node_storage = \Drupal::entityTypeManager()->getStorage('node');
				$_nodes = $node_storage->loadMultiple($posts);

				foreach ($posts as $key => $p) {
					$this->posts[] = $this->_load_data($p, $_nodes);
				}

				\Drupal::cache()->set($cache_key, $this->posts, $expire);

			}

			$this->rawPosts = $this->posts;

		}

		public function setFields($fields)
		{

			if (is_array($fields)) {

				$this->fields = array();

				foreach ($fields as $key => $field) {
					$this->fields[$key] = $field;
				}

			}

		}

		private function _load_data($nid, $nodes)
		{

			$node = $nodes[$nid];
			$return = array();

			if (!empty($node)) {

				$title = $node->title->value;
				if ($node->hasField('field_heading') && !$node->field_heading->isEmpty()) {
					$title = $node->field_heading->value;
				}

				$return = array(
					'nid'      => $nid,
					'sticky'   => $node->isSticky(),
					'promoted' => $node->isPromoted(),
					'title'    => $title,
					'node' => $node
				);

				if (!empty($this->fields)) {
					foreach ($this->fields as $key => $field) {
						if ($node->hasField($key)) {
							if (!empty($node->{$key}->target_id)) {
								$return[$key] = $node->{$key}->target_id;
							} else {
								$return[$key] = $node->{$key}->value;
							}
						}
					}
				}

			}

			return $return;

		}

		public function resetFiltering()
		{

			$this->posts = $this->rawPosts;

		}

		public function results($amt = MAX_POSTS, $offset = 0)
		{

			if ($amt == -1) {
				return $this->posts;
			} else {
				$slice = array_slice($this->posts, ($offset * $amt), $amt);

				return $slice;
			}

		}

		public function remove($items)
		{

			foreach ($items as $key => $item) {

				foreach ($this->posts as $j => $post) {

					if ($post['nid'] == $item['nid']) {
						array_splice($this->posts, $j, 1);
					}

				}

			}

			return $this;

		}

		public function getOnlyPromoted()
		{

			$this->promoted = true;

			$this->filter();

		}

		public function shuffle()
		{

			shuffle($this->posts);

			// Sticky posts remain at top
			usort($this->posts, function ($a, $b) {
				return $b['sticky'] - $a['sticky'];
			});

		}

		public function filterByKeyword($keyword = '')
		{

//			$filteredposts = array();
//
//			if (!empty($keyword)) {
//
//				foreach ($this->posts as $j => $p) {
//
//					$title = $p->title;
//					$body = nv($p, 'body');
//					$summ = nv($p, 'body', 'teaser');
//
//					$haystack = strtolower($title . ' ' . render($body) . ' ' . render($summ));
//					$needle = strtolower($key);
//
//					if (strpos($haystack, $needle) !== false) {
//						$filteredposts[] = $p;
//					}
//
//				}
//
//				$this->posts = $filteredposts;
//
//			}

		}

		public function filterByTaxonomy($field, $tid_array)
		{

//			if (!empty($tid_array) && is_array($tid_array)) {
//				foreach ($this->posts as $j => $p) {
//					$tags = nv($p, $field);
//					$check_tax = array();
//
//					if (!empty($tags['tid'])) {
//						$check_tax[] = $tags['tid'];
//					} else {
//						foreach ($tags as $key => $value) {
//							if (!empty($value['tid'])) {
//								$check_tax[] = $value['tid'];
//							}
//						}
//					}
//
//					foreach ($tid_array as $key => $value) {
//						if (!in_array($value, $check_tax)) {
//							unset($this->posts[$j]);
//						}
//					}
//				}
//			}

		}

		public function sortByDate()
		{

			$this->sort = 'date';

			$this->filter();

		}

		public function sortByTitle()
		{

			$this->sort = 'title';

			$this->filter();

		}

		public function count($filtered = false)
		{

			if ($filtered) {
				return count($this->posts);
			} else {
				return count($this->rawPosts);
			}

		}

		public function pageCount($amt = 10)
		{

			return ceil(count($this->posts) / $amt);

		}

		public function first()
		{

			if (empty($this->posts)) {
				return array();
			}

			return current($this->posts);

		}

	}

