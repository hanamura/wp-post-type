<?php

namespace WPPostType;

class ImageSize
{
	// props
	public $name = null;
	protected $_options = null;



	// init
	function __construct($name, $options = null)
	{
		$this->name = $name;
		$this->_options = $options;

		add_image_size(
			$this->name,
			isset($this->_options['width']) ? $this->_options['width'] : 1000,
			isset($this->_options['height']) ? $this->_options['height'] : 1000,
			isset($this->_options['crop']) ? $this->_options['crop'] : false
		);
	}
}
