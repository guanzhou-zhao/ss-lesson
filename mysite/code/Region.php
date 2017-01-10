<?php

class Region extends DataObject {
  private static $has_one = array (
    'RegionsPage' => 'RegionsPage',
    'Photo' => 'Image'
  );
  private static $db = array (
    'Title' => 'Varchar',
    'Description' => 'Text',
  );
  private static $summary_fields = array (
    'Photo.Filename' => 'Photo file name',
    'Title' => 'Title of region',
    'Description' => 'Short description'
  );

  public function getGridThumbnail() {
    if ($this->Photo()->exists()) {
      return $this->Photo()->SetHeight(200);
    }
    return "(no image)";
  }

  public function getCMSFields() {
    $fields = FieldList::create(
      TextField::create('Title'),
      TextareaField::create('Description'),
      $uploader = UploadField::create('Photo')
    );

    $uploader
      ->setFolderName('region-photos')
      ->getValidator()->setAllowedExtensions(array('png','gif','jpeg','jpg'));

    return $fields;
  }
}
