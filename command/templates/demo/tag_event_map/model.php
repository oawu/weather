{<{<{ if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class <?php echo ucfirst (camelize ($name));?> extends OaModel {

  static $table_name = '<?php echo pluralize ($name);?>';

  static $validates_uniqueness_of = array (
    array (array ('tag_id', 'event_id'), 'message' => 'columns(tag_id, event_id) Repeat!')
  );

  static $has_one = array (
  );

  static $has_many = array (
    array ('tags', 'class_name' => 'Tag'),
    array ('events', 'class_name' => 'Event')
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

<?php
    if ($images) { ?>
<?php foreach ($images as $image) { ?>
    OrmImageUploader::bind ('<?php echo $image; ?>', '<?php echo ucfirst (camelize ($name)) . ucfirst ($image) . $image_uploader_class_suffix; ?>');
<?php
      }
    } ?>

<?php
    if ($files) { ?>
<?php foreach ($files as $file) { ?>
    OrmFileUploader::bind ('<?php echo $file; ?>', '<?php echo ucfirst (camelize ($name)) . ucfirst ($file) . $file_uploader_class_suffix; ?>');
<?php
      }
    } ?>
  }
}