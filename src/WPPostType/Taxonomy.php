<?php

namespace WPPostType;

class Taxonomy
{
	public $name = null;
	protected $_types = null;
	protected $_options = null;

	function __construct($name, $types, $options = null)
	{
		$this->name = $name;
		$this->_types = $types;
		$this->_options = $options;

		add_action('init', array($this, 'onInit'));
	}

	public function onInit()
	{
		$name_text = ucwords(preg_replace('/[ _-]+/', ' ', $this->name));

		$labels = array(
			'name' => _x($name_text, 'taxonomy general name'),
			'singular_name' => _x($name_text, 'taxonomy singular name'),
			'search_items' =>  __("Search {$name_text}"),
			'all_items' => __("All {$name_text}"),
			'parent_item' => __("Parent {$name_text}"),
			'parent_item_colon' => __("Parent {$name_text}:"),
			'edit_item' => __("Edit {$name_text}"),
			'update_item' => __("Update {$name_text}"),
			'add_new_item' => __("Add New {$name_text}"),
			'new_item_name' => __("New {$name_text} Name"),
			'menu_name' => __($name_text),
		);
		$default_options = array(
			'hierarchical' => true,
			'labels' => $labels,
			'public' => true
		);

		$options = array_merge($default_options, $this->_options ? $this->_options : array());

		register_taxonomy($this->name, $this->_types, $options);
	}
}
