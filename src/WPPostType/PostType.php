<?php

namespace WPPostType;

class PostType
{
  // props
  public $name = null;
  protected $_options = null;



  // init
  function __construct($name, $options = null)
  {
    $this->name = $name;
    $this->_options = $options;

    // register
    add_action('init', array($this, 'onInit'));

    // head
    add_action('admin_head', array($this, 'onAdminHead'));

    // edit - remove meta boxes
    add_action('admin_menu', array($this, 'onAdminMenu'));

    // edit
    add_action("add_meta_boxes_{$this->name}", array($this, 'onAddMetaBoxes'));

    // save
    add_action('save_post', array($this, 'onSavePost'), 10, 2);
    add_filter('wp_insert_post_data', array($this, 'onInsertPostData'), 99, 2);

    // remove
    add_action('trashed_post', array($this, 'onTrashedPost'));
    add_action('deleted_post', array($this, 'onDeletedPost'));

    // attachment
    add_action('add_attachment', array($this, 'onAddAttachment'));
    add_action('edit_attachment', array($this, 'onEditAttachment'));
    add_action('delete_attachment', array($this, 'onDeleteAttachment'));

    // list
    add_filter("manage_{$name}_posts_columns", array($this, 'onManagePostsColumns'));
    add_action("manage_{$name}_posts_custom_column", array($this, 'onManagePostsCustomColumn'), 10, 2);

    // map meta cap
    add_filter('map_meta_cap', array($this, 'onMapMetaCap'), 10, 4);
  }



  // register
  public function onInit()
  {
    $name_text = ucwords(preg_replace('/[ _-]+/', ' ', $this->name));

    $labels = array(
      'name' => _x($name_text, 'post type general name'),
      'singular_name' => _x($name_text, 'post type singular name'),
      'add_new' => _x('Add New', ''),
      'add_new_item' => __("Add New {$name_text}"),
      'edit_item' => __("Edit {$name_text}"),
      'new_item' => __("New {$name_text}"),
      'view_item' => __("View {$name_text}"),
      'search_items' => __("Search {$name_text}"),
      'not_found' => __("No {$name_text} Found"),
      'not_found_in_trash' => __("No {$name_text} Found in Trash"),
      'parent_item_colon' => ''
    );
    $default_options = array(
      'labels' => $labels,
      'public' => true,
      'menu_position' => 5,
      'supports' => false,
      'taxonomies' => array()
    );

    $options = array_merge($default_options, $this->_options ? $this->_options : array());

    register_post_type($this->name, $options);
  }



  // head
  public function onAdminHead()
  {
    $screen = get_current_screen();

    if (!$screen || $screen->post_type !== $this->name) {
      return;
    }
    if ($screen->base === 'post' && $screen->id === $this->name) {
      $this->onCheckedAdminHead();
    } else if ($screen->base === 'edit' && $screen->id === 'edit-' . $this->name) {
      $this->onCheckedAdminHeadEdit();
    }
  }

  public function onCheckedAdminHead()
  {
    // override
  }

  public function onCheckedAdminHeadEdit()
  {
    // override
  }



  // edit - remove meta boxes
  public function onAdminMenu()
  {
    // override
  }



  // edit
  public function onAddMetaBoxes()
  {
    // override
  }



  // save
  public function onSavePost($post_id, $post)
  {
    if ($post->post_type !== $this->name) {
      return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }
    $this->onCheckedSavePost($post_id, $post);
  }

  public function onCheckedSavePost($post_id, $post)
  {
    // override
  }

  public function onInsertPostData($data, $postarr)
  {
    if ($data['post_type'] !== $this->name) {
      return $data;
    }
    if ($data['post_status'] === 'auto-draft' || $postarr['ID'] === 0) {
      return $data;
    }
    return $this->onCheckedInsertPostData($data, $postarr);
  }

  public function onCheckedInsertPostData($data, $postarr)
  {
    // override
    return $data;
  }



  // remove
  public function onTrashedPost($post_id)
  {
    if ($post = get_post($post_id)) {
      if ($post->post_type === $this->name) {
        $this->onCheckedTrashedPost($post_id, $post);
      }
    }
  }

  public function onCheckedTrashedPost($post_id, $post)
  {
    // override
  }

  public function onDeletedPost($post_id)
  {
    if ($post = get_post($post_id)) {
      if ($post->post_type === $this->name) {
        $this->onCheckedDeletedPost($post_id, $post);
      }
    }
  }

  public function onCheckedDeletedPost($post_id, $post)
  {
    // override
  }



  // attachment
  public function onAddAttachment($attachment_id)
  {
    if ($attachment = get_post($attachment_id)) {
      if ($post = get_post($attachment->post_parent)) {
        if ($post->post_type === $this->name) {
          $this->onCheckedAddAttachment($attachment_id, $attachment, $post);
        }
      }
    }
  }

  public function onCheckedAddAttachment($attachment_id, $attachment, $post)
  {
    // override
  }

  public function onEditAttachment($attachment_id)
  {
    if ($attachment = get_post($attachment_id)) {
      if ($post = get_post($attachment->post_parent)) {
        if ($post->post_type === $this->name) {
          $this->onCheckedEditAttachment($attachment_id, $attachment, $post);
        }
      }
    }
  }

  public function onCheckedEditAttachment($attachment_id, $attachment, $post)
  {
    // override
  }

  public function onDeleteAttachment($attachment_id)
  {
    if ($attachment = get_post($attachment_id)) {
      if ($post = get_post($attachment->post_parent)) {
        if ($post->post_type === $this->name) {
          $this->onCheckedDeleteAttachment($attachment_id, $attachment, $post);
        }
      }
    }
  }

  public function onCheckedDeleteAttachment($attachment_id, $attachment, $post)
  {
    // override
  }



  // list
  public function onManagePostsColumns($columns)
  {
    // override
    return $columns;
  }

  public function onManagePostsCustomColumn($column, $post_id)
  {
    // override
  }



  // map meta cap
  public function onMapMetaCap($caps, $cap, $user_id, $args)
  {
    // post type object
    // ----------------

    $post_type_object = get_post_type_object($this->name);

    if (is_null($post_type_object)) {
      return $caps;
    }

    if (
      $post_type_object->capability_type === 'post' ||
      $post_type_object->capability_type === 'page'
    ) {
      return $caps;
    }

    // meta caps
    // ---------

    $edit_post = $post_type_object->cap->edit_post;
    $delete_post = $post_type_object->cap->delete_post;
    $read_post = $post_type_object->cap->read_post;

    // receiving meta caps?
    // --------------------

    if (
      $cap === $edit_post ||
      $cap === $delete_post ||
      $cap === $read_post
    ) {
      $post = get_post($args[0]);

      // map meta caps
      // -------------

      switch ($cap) {
      case $edit_post:
        return $this->onCheckedMapMetaCapEditPost($user_id, $post, $post_type_object);
      case $delete_post:
        return $this->onCheckedMapMetaCapDeletePost($user_id, $post, $post_type_object);
      case $read_post:
        return $this->onCheckedMapMetaCapReadPost($user_id, $post, $post_type_object);
      }
    }

    // default
    // -------

    return $caps;
  }

  public function onCheckedMapMetaCapEditPost($user_id, $post, $post_type_object)
  {
    if (intval($post->post_author) === $user_id) {
      return array($post_type_object->cap->edit_posts);
    } else {
      return array($post_type_object->cap->edit_others_posts);
    }
  }

  public function onCheckedMapMetaCapDeletePost($user_id, $post, $post_type_object)
  {
    if (intval($post->post_author) === $user_id) {
      return array($post_type_object->cap->delete_posts);
    } else {
      return array($post_type_object->cap->delete_others_posts);
    }
  }

  public function onCheckedMapMetaCapReadPost($user_id, $post, $post_type_object)
  {
    if (intval($post->post_author) === $user_id) {
      return array($post_type_object->cap->read);
    } else if ($post->post_status !== 'private') {
      return array($post_type_object->cap->read);
    } else {
      return array($post_type_object->cap->read_private_posts);
    }
  }
}
