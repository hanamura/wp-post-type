<?php

namespace WPPostType;

class ImageSize
{
  public $name = null;
  public $width = 1000;
  public $height = 1000;
  public $crop = false;

  function __construct($name, $options = null)
  {
    $this->name = $name;

    $options = array_merge(array(
      'width' => 1000,
      'height' => 1000,
      'crop' => false,
    ), $options);

    $this->width = $options['width'];
    $this->height = $options['height'];
    $this->crop = $options['crop'];

    add_image_size(
      $this->name,
      $this->width,
      $this->height,
      $this->crop
    );
  }
}
